<?php
namespace GDO\Logs;

use GDO\Core\Module;
use GDO\Date\GDT_Date;
use GDO\Type\GDT_Checkbox;

final class Module_Logs extends Module
{
    public function getConfig()
	{
		return array(
			GDT_Checkbox::make('log_requests')->initial('0'),
			GDT_Checkbox::make('log_rotation_mail')->initial('1'),
			GDT_Checkbox::make('log_rotation')->initial('1'),
			GDT_Date::make('last_log_rotation'),
		);
	}
	
	public function cfgLogRequests() { return $this->getConfigValue('log_requests'); }
	public function cfgLogRotationMail() { return $this->getConfigValue('log_rotation_mail'); }
	public function cfgLogRotation() { return $this->getConfigValue('log_rotation'); }
	public function cfgLastRotation() { return $this->getConfigValue('last_log_rotation'); }
	
	public function renderAdminTabs() { return $this->templatePHP('admin_tabs.php'); }
	
	public function href_administrate_module() { return href('Logs', 'Overview'); }
}
