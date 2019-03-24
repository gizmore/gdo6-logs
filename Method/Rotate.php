<?php
namespace GDO\Logs\Method;

use GDO\Logs\Module_Logs;
use GDO\File\FileUtil;
use GDO\Mail\Mail;
use GDO\User\GDO_User;
use GDO\Core\Application;
use GDO\Core\Logger;
use GDO\Cronjob\MethodCronjob;

/**
 * Cronjob for log rotation.
 * Sends logzip via mail.
 */
final class Rotate extends MethodCronjob
{
	public function run()
	{
		$module = Module_Logs::instance();
		
		if (Application::instance()->isWindows())
		{
			Logger::logCron("Log rotation on windows is not supported.");
			return false;
		}
		
		if ($module->cfgLogRotation())
		{
			$now = date('Y-m-d');
			$last = $module->cfgLastRotation();
			if ($last != $now)
			{
				$this->logRotate();
				$module->saveConfigVar('last_log_rotation', $now);
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
		
		$command = "cd protected && find logs -daystart -mtime +1 -delete";
		exec($command, $output, $return_val);
		echo print_r($output, 1);
		
		if (Module_Logs::instance()->cfgLogRotationMail())
		{
			$this->sendLogMails($filename);
		}
	}
	
	private function sendLogMails($filename)
	{
		foreach (GDO_User::admins() as $user)
		{
			$this->sendLogMail($user, $filename);
		}
	}
	
	private function sendLogMail(GDO_User $user, $filename)
	{
		$mail = Mail::botMail();
		$mail->setReceiver($user->getMail());
		$mail->setReceiverName($user->displayNameLabel());
		
		$sitename = sitename();
		$username = $user->displayNameLabel();
		
		$mail->setSubject(tusr($user, 'mail_subj_log', [$sitename]));
		$mail->setBody(tusr($user, 'mail_body_logs', [$username, $sitename]));
		
		$mail->addAttachmentFile($filename, 'protected/logs_zipped/'.$filename);
		
		$mail->sendToUser($user);
	}
}
