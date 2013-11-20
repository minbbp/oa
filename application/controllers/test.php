<?php
//测试文件跳转
//以后获取主管信息，可以直接从session中获取，来查看是否拥有主管信息
class  Test extends MY_Controller
{
	public function  __construct()
	{
		parent::__construct();
		$this->load->helper(array('message','url'));
		$this->load->library('session');
	}
	//输出一个视图之后，显示固定的时间之后进行条状
	public function index()
	{
		$data['content']="haha";
		$data['time']=5;
		$data['url']='http://baidu.com';
		//echo "<pre>";
		//print_r($this->session->all_userdata());
		//echo "</pre>";
		if($this->session->userdata('level_info')!=FALSE)
		{
			echo "您有主管";
			$levelinfo=$this->session->userdata('level_info');
			echo "您的主管为:".$levelinfo['realname'];
		}
		else
		{
			echo "您没有主管";
		}
		//message($data, 'message/success');
	}
	public function alllist()
	{
		//跳转数据
		message(array('time'=>3,'url'=>'http://google.com.hk','content'=>'操作失败！跳转到google'), 'message/error');
	}
	//测试通过，可以直接使用啦！直接使用helper啦！
	public function sendmail()
	{
		sendcloud('wb-zhibinliu@sohu-inc.com', '使用扩展发送的邮件', '使用扩展发送的测试邮件');
	}
	public function server()
	{
		$this->load->view('server_apply');
	}
	public function add_apply()
	{
		
	}
	public function  test_new_mail()
	{
		$subject="邮件内容测试！";
		$msg=$this->load->view('mail/mail_new_common',array('name'=>'韩旭','msg'=>'测试邮件问题,测试邮件问题,,,,,,,'),TRUE);
		echo $msg;
		sendcloud('wb-zhibinliu@sohu-inc.com', $subject,$msg);
	}
}