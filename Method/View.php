<?php
namespace GDO\Logs\Method;

use GDO\Date\GDT_Date;
use GDO\Date\Time;
use GDO\File\Filewalker;
use GDO\Form\GDT_Form;
use GDO\Form\GDT_Submit;
use GDO\Form\MethodForm;
use GDO\Core\GDT_Response;
use GDO\User\GDT_User;

final class View extends MethodForm
{
	private $user;
	
	private $dateMin, $dateMax;
	
	public function execute()
	{
		$this->dateMin = Time::getDate();
		$this->dateMax = Time::getDate();
		return parent::execute()->addField($this->renderLogfiles());
	}
	
	public function createForm(GDT_Form $form)
	{
		$form->addField(GDT_Date::make('log_date_from'));
		$form->addField(GDT_Date::make('log_date_to'));
		$form->addField(GDT_User::make('log_user'));
		$form->actions()->addField(GDT_Submit::make()->label('view'));
	}
	
	public function formValidated(GDT_Form $form)
	{
		$this->dateMin = $form->getField('log_date_from')->getValue();
		$this->dateMax = $form->getField('log_date_to')->getValue();
		$this->user = $form->getField('log_user')->getValue();
	}
	
	public function renderLogfiles()
	{
		$path = GDO_PATH . 'protected/logs/';
		$path .= $this->user ? $this->user->getUserName() : '';
		$response = GDT_Response::make();
		Filewalker::traverse($path, null, [$this, 'renderLogfile'], false, true, $response);
		return $response;
	}
	
	public function renderLogfile($entry, $path, $response)
	{
		$response->addField($this->templatePHP('logfile.php', ['path' => $path]));
	}
}
