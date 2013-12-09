<?php
/**
 * 用户条操作成功之后，显示相关信息，之后进行页面条状
 * 已经用户对邮件的一个发送扩展
 */
if(!function_exists('message'))
{
	/**
	 * @param array $data,传递给视图的数据
	 * $data=array('content'=>'','title'=>'','url'=>'',time=>'');
	 * @param string $view，渲染指定的视图文件
	 * 使用方法，
	 * 1,在对应的文件中$this->load->helper('message');
	 * 2,直接调用  message(array('url'=>'','content'=>'','time'=>'')
	 * time 对应的秒数，content为对用的提示内容，url 为显示完毕内容之后跳转的视图页面
	 */
	function message($data,$view)
	{
		$_CI=&get_instance();
		$_CI->load->view($view,$data);
	}
}
 /**
 * 使用sendcloud 发送邮件
 */
if(!function_exists('sendcloud_f'))
{
	/**
	 * 默认情况下，发送邮件到本地文件，不再调用服务器进行发送文件。如要真实发送邮件修改state即可
	 * @param  $to 发送者，可以是数组也可以是字符串
	 * @param string $subject  发送的邮件的标题
	 * @param string $message  可以是一个视图，字符以及等相关资源
	 * @param string $cc  抄送，可以是字符也可以是数组。发送多个的时候，推荐使用数组
	 */
	function sendcloud_f($to,$subject,$message,$cc=null)
	{
		//把收件人，邮件标题，以及邮件内容写入到一个文本文件。真正的环境中的时候在开启邮件发送
		$message = iconv("utf-8", "utf-8//IGNORE", $message);
		$state=TRUE;
		if($state===TRUE)
		{
			$_CI=&get_instance();
			$_CI->load->library('email');
			$_CI->email->from(SYS_EMAIL,SYS_EMAILNAME);
			$_CI->email->to($to);
			if($cc)
			{
				$_CI->email->cc($cc);
			}
			$_CI->email->subject($subject);
			$_CI->email->message($message);
			//file_put_contents(dirname(BASEPATH)."/email_path/20131127_1.eml", $_CI->email->_finalbody);
			   $_CI->email->send();
			 // $_CI->email->print_debugger();
			
			 file_put_contents(dirname(BASEPATH)."/email_path/20131127_3.eml", $_CI->email->_finalbody);
				return true;
			 //file_put_contents(dirname(BASEPATH)."/email_path/20131127_3_debug.eml",$_CI->email->print_debugger()) ;
			
		}
		else
		{
			 $email_path=dirname(BASEPATH)."/email_path/";
			 $filename=$email_path.date('Y-m-d').".email.txt";
			//把文件写入到队列
			$time=date('Y-m-d H:i:s');
			$str="startmail:============time:$time=================================================\r\n";
			if(is_array($to))
			{
				$str.="to:".implode(',', $to)."\r\n";
			}
			else
			{
				$str.="to:".$to."\r\n";
			}
			$str.="subject:".$subject."\r\n";
			$str.="content:".$message;
			if(is_array($cc))
			{
				$str.="cc:".implode(',', $cc)."\r\n";
			}
			else
			{
				$str.="cc:".$cc."\r\n";
			}
			$str.="endmail:=============================================================\r\n\r\n";
			return file_put_contents($filename,$str,FILE_APPEND);
		}
	}
} 
/**
 * 使用curl 方式重写了邮件发送方法
 */
if(!function_exists('sendcloudold'))
{
	function sendcloudold($to,$subject,$message,$cc=null,$open=TRUE){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
		//curl_setopt($ch, CURLOPT_URL, 'https://sendcloud.sohu.com/webapi/mail.send.json');
		curl_setopt($ch, CURLOPT_URL, '10.11.49.49:8002/webapi/mail.send.json');
		//不同于登录SendCloud站点的帐号，您需要登录后台创建发信子帐号，使用子帐号和密码才可以进行邮件的发送。
		$info=array(
			'api_user' => 'postmaster@adrdop-sendmore.sendcloud.org',
			'api_key' => 'd2oG5mXj',
			'from' => 'postmaster@adrdop-sendmore.sendcloud.org',
			'fromname' => '运维支撑平台',
			'to' => _email_change($to),
			'subject' => $subject,
			'html' => $message,
			);
		if($cc)
		{
			$info['cc']=_email_change($cc);
		}
		curl_setopt($ch, CURLOPT_POSTFIELDS,$info);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		if($open)
		{
			$result = curl_exec($ch);
		}
		else
		{
			//记录发送内容到文本文件
			$email_path=dirname(BASEPATH)."/email_path/";
			$filename=$email_path.date('Y-m-d').".email.txt";
			$info['addtime']=date('Y-m-d H:i:s');
			$info['html'] = preg_replace( "<style(.*?)</style>","",$info['html'] );
			$info['html']=strip_tags($info['html']);
			$str=implode('|---|', $info);
			$str="\r\n".trim($str)."\r\n";
			file_put_contents($filename,$str,FILE_APPEND);
			$result=TRUE;
		}
		curl_close($ch);
		if($result === false) //请求失败
		{
			log_message('error', 'last error : ' . curl_error($ch));
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
}
/**
 * 把符合CI 格式的数据进行装换，转换为sendcloud 发送的邮件地址列表
 */
if(!function_exists('_email_change'))
{
	function _email_change($email)
	{
		
		$str="";
		if(is_array($email))
		{
			$str.=implode(';', $email);
		}
		else
		{
			$str.=implode(';',explode(',', $email));
		}
		return $str;
	}
}
function sendcloud($to,$subject,$msg,$cc=null)
{
	include BASEPATH."sendcloud/SendCloudLoader.php"; // 导入SendCloud依赖
	//include '/path/to/sendcloud_php/SendCloudLoader.php';或者 导入SendCloud依赖
	try {
		// 设置脚本执行的最长时间，以免附件较大时，需要传输比较久的时间
		// Fatal error: Maximum execution time of 30 seconds exceeded
		// http://php.net/manual/en/function.set-time-limit.php
		// set_time_limit(300);
	
		$sendCloud = new SendCloud('postmaster@adrdop-sendmore.sendcloud.org','d2oG5mXj');
		$sendCloud->setServer('internal.smtpcloud.sohu.com',25);
		$message = new SendCloud\Message();
		$message->addRecipient($to) // 添加第一个收件人
		//->addRecipients(array('to2@sendcloud.com', 'to3@sendcloud.com')) // 添加批量接受地址
		->setReplyTo('postmaster@adrdop-sendmore.sendcloud.org'); // 添加回复地址
		if($cc)
		{
			$message->addCcs(_new_email_change($cc));
		} // 添加cc地址
		$message->setFromName('精准广告研发') // 添加发送者称呼
		->setFromAddress('postmaster@adrdop-sendmore.sendcloud.org') // 添加发送者地址
		->setSubject($subject)  // 邮件主题
		->setBody($msg); // 邮件正文html形式
		//$message->setAltBody('SendCloud PHP SDK 测试正文，请参考');// 邮件正文纯文本形式，这个不是必须的。
		return $sendCloud->send($message);
		//print '<br>emailIdList:';
		//print var_dump($sendCloud->getEmailIdList());// 取得emailId列表
	} catch (Exception $e) {
		//print "出现错误:";
		//print $e->getMessage();
		log_message('error', $e->getMessage());
	}
}
function _new_email_change($email)
{
	if(!is_array($email))
	{
		$email=explode(',', $email);
	}
	return $email;
}