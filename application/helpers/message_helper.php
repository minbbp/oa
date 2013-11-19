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
if(!function_exists('sendcloud'))
{
	/**
	 * 默认情况下，发送邮件到本地文件，不再调用服务器进行发送文件。如要真实发送邮件修改state即可
	 * @param  $to 发送者，可以是数组也可以是字符串
	 * @param string $subject  发送的邮件的标题
	 * @param string $message  可以是一个视图，字符以及等相关资源
	 * @param string $cc  抄送，可以是字符也可以是数组。发送多个的时候，推荐使用数组
	 */
	function sendcloud($to,$subject,$message,$cc=null)
	{
		//把收件人，邮件标题，以及邮件内容写入到一个文本文件。真正的环境中的时候在开启邮件发送
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
			return $_CI->email->send();
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
