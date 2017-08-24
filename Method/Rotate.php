<?php
namespace GDO\Logs\Method;

use GDO\Cronjob\MethodCronjob;
use GDO\Date\Time;
use GDO\Logs\Module_Logs;

final class Rotate extends MethodCronjob
{
	public function run()
	{
		$module = Module_Logs::instance();
		
		if ($module->cfgLogRotation())
		{
			$now = date('Ymd');
			$last = $module->cfgLastRotation();
			$now = Time::getDate(mktime(0,0,0));
			if ($last != $now)
			{
				$this->logRotate();
// 				$module->saveConfigValue('log_last_rotation', $now);
			}
		}
	}
	
	public function logRotate()
	{
		$this->logNotice('Log Rotation');
		
	}
}
