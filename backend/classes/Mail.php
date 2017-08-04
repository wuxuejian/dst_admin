<?php
/**
 * 邮件类
 * @author Administrator
 *
 */
namespace backend\classes;
use yii;

use extension\phpmailer;
class Mail
{
	/**
	 * 
	 * @param array $sendto_email           收件人 
	 * @param string $subject				邮件主题
	 * @param string $body					邮件内容
	 */
	public function send($sendto_email,$subject, $body)
	{
		include_once(dirname(dirname(dirname(__FILE__))).'/extension/phpmailer/class.phpmailer.php');
		include_once(dirname(dirname(dirname(__FILE__))).'/extension/phpmailer/class.smtp.php');
		
		set_time_limit(0);
		//error_reporting(E_STRICT);
		date_default_timezone_set('Asia/Shanghai');//设定时区东八区
		
		$mail             = new \PHPMailer(); //new一个PHPMailer对象出来
		$body            = @eregi_replace("[\]",'',$body); //对邮件内容进行必要的过滤
		$mail->CharSet ="UTF-8";//设定邮件编码，默认ISO-8859-1，如果发中文此项必须设置，否则乱码
		$mail->IsSMTP(); // 设定使用SMTP服务
		// $mail->SMTPDebug  = 1;                     // 启用SMTP调试功能
		// 1 = errors and messages
		// 2 = messages only
		$mail->SMTPAuth   = true;                  // 启用 SMTP 验证功能
		$mail->SMTPSecure = "ssl";                 // 安全协议，可以注释掉
		$mail->Host       = 'smtp.exmail.qq.com';      // SMTP 服务器
		$mail->Port       = 465;                   // SMTP服务器的端口号(SSL)
		$mail->Username   = 'oa@dstcar.com';  // SMTP服务器用户名，PS：我乱打的
		$mail->Password   = 'QWEasd123456';            // SMTP服务器密码
		$mail->SetFrom('oa@dstcar.com', 'OA');
		//$mail->AddReplyTo('xxx@xxx.com','who');
		$mail->Subject    = $subject;
		$mail->AltBody    = 'To view the message, please use an HTML compatible email viewer!'; // optional, comment out and test
		$mail->MsgHTML($body);
		foreach ($sendto_email as $key=>$val)
		{
			//多个收件人多次调用
			$mail->AddAddress($val);
		}
		
		
		if(!$mail->Send()) {
// 			echo 'Mailer Error: ' . $mail->ErrorInfo;
			return false;
		} else {
			//echo "Message sent!恭喜，邮件发送成功！";
			return true;
		}
	}
	
}