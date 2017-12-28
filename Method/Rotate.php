<?php
namespace GDO\Logs\Method;

use GDO\Core\MethodCronjob;
use GDO\Date\Time;
use GDO\Logs\Module_Logs;
use GDO\File\GDO_File;
use GDO\File\FileUtil;
use GDO\Mail\Mail;

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
		
		FileUtil::createDir(GWF_PATH . 'protected/logs_zipped');
		
		$filename = sitename() . '_' . date('Ymd') . '.log.zip';
		$command = "cd protected && find logs -daystart -mtime +1 | zip -r9 logs_zipped/$filename -@";
		exec($command, $output, $return_val);
		echo print_r($output, 1);
		
		if (Module_Logs::instance()->cfgLogRotationMail())
		{
			$this->sendLogMails($filename);
		}
	}
	
	private function sendLogMails($filename)
	{
		$mail = Mail::botMail();
		$mail->addAttachmentFile($filename, 'logs_zipped'.$filename)
	}
}
