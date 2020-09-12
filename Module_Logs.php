<?php
namespace GDO\Logs;

use GDO\Core\GDO_Module;
use GDO\Date\GDT_Date;
use GDO\DB\GDT_Checkbox;
use GDO\Core\Logger;
use GDO\Core\Application;

/**
 * This module offers logging of requests and log rotation.
 * The zipped logs can be sent to all admins regularly.
 * 
 * @author gizmore@wechall.net
 * @version 6.08
 * @since 5.00
 */
final class Module_Logs extends GDO_Module
{
	public function getConfig()
	{
		return array(
			GDT_Checkbox::make('log_requests')->initial('1'),
			GDT_Checkbox::make('log_rotation_mail')->initial('0'),
			GDT_Checkbox::make('log_rotation')->initial('1'),
			GDT_Date::make('last_log_rotation'),
		);
	}
	public function cfgLogRequests() { return $this->getConfigValue('log_requests'); }
	public function cfgLogRotationMail() { return $this->getConfigValue('log_rotation_mail'); }
	public function cfgLogRotation() { return $this->getConfigValue('log_rotation'); }
	public function cfgLastRotation() { return $this->getConfigVar('last_log_rotation'); }
	
	public function renderAdminTabs() { return $this->templatePHP('admin_tabs.php'); }
	
	public function href_administrate_module() { return href('Logs', 'Overview'); }
	
	public function onLoadLanguage() { return $this->loadLanguage('lang/logs'); }
	
	public function onInit()
	{
	    if (!Application::instance()->isCLI())
	    {
    		if ($this->cfgLogRequests())
    		{
    			Logger::log('requests', json_encode($_REQUEST));
    		}
	    }
	}
}
