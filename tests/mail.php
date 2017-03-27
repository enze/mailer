<?php

require_once(__dir__ . '/../vendor/autoload.php');

$mail = new xb\mailer\Mailer;

$mail->smtp = 'smtp.your.com';
$mail->port = '465';
$mail->ssl = true;
$mail->username = 'you@your.com';
$mail->password = '';
$mail->charset = 'utf-8';
$mail->mailFrom = ['you@your.com' => 'you'];
$mail->mailTo = ['other@other.com' => 'other'];
$mail->title = '测试邮件';
$mail->message = '亲爱的xxx，这是一封测试邮件，请忽略！！';
	
try {
	$mail->send();
} catch (\Exception $e) {
	echo $e->getMessage();
}