<?php
namespace GDO\Logs\Method;

use GDO\Date\GDO_Date;
use GDO\Date\Time;
use GDO\File\Filewalker;
use GDO\Form\GDO_Form;
use GDO\Form\GDO_Submit;
use GDO\Form\MethodForm;
use GDO\Template\Response;
use GDO\User\GDO_User;
use GDO\User\User;

final class View extends MethodForm
{
	/**
	 * @var User
	 */
	private $user;
	
	private $dateMin, $dateMax;
	
	public function execute()
	{
		$this->dateMin = Time::getDate();
		$this->dateMax = Time::getDate();
		return parent::execute()->add($this->renderLogfiles());
	}
	
	public function createForm(GDO_Form $form)
	{
		$form->addField(GDO_Date::make('log_date_from'));
		$form->addField(GDO_Date::make('log_date_to'));
		$form->addField(GDO_User::make('log_user'));
		$form->addField(GDO_Submit::make()->label('view'));
	}
	
	public function formValidated(GDO_Form $form)
	{
		$this->dateMin = $form->getField('log_date_from')->getValue();
		$this->dateMax = $form->getField('log_date_to')->getValue();
		$this->user = $form->getField('log_user')->getValue();
	}
	
	public function renderLogfiles()
	{
		$path = GWF_PATH . 'protected/logs/';
		$path .= $this->user ? $this->user->getUserName() : '';
		$response = new Response('');
		Filewalker::traverse($path, [$this, 'renderLogfile'], false, true, $response);
		return $response;
	}
	
	public function renderLogfile($entry, $path, $response)
	{
		$response->add($this->templatePHP('logfile.php', ['path' => $path]));
	}
}