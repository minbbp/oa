<?php
/**
 * 主管审批流程,凡是这个文件获取到的数据的员工都是需要主管进行审批的。所以当前脚本不需要判断审批的人的身份
 */
class Git_level extends CI_Controller
{
	private $user_id;
	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('message','form','url'));
		$this->load->library(array('table','pagination','session','dx_auth'));
		$this->load->model('Users_model','users',TRUE);
		$this->load->model('Git_op_level_model','gol',TRUE);
		$this->load->model('Gits_model','git',TRUE);
		$this->load->model('Group_creators_model','creator',TRUE);
		$this->dx_auth->check_uri_permissions();//检查用户权限
		$this->user_id=$this->session->userdata('DX_user_id');
	}
	/**
	 * 审批列表显示,审批类型分三种，0为新增申请的审批，1为新增机器的审批器，2为新增git组的审批
	 */
	public function index()
	{
	
		$config['base_url'] = base_url('index.php/git_level/index/');
		$config['total_rows'] = $this->gol->count_alllist(0,$this->user_id);
		$config['per_page'] = 5;
		$offset=intval($this->uri->segment(3));
		$rs=$this->gol->alllist($offset,$config['per_page'],0,$this->user_id);
		$data['rs']=$rs;
		$this->pagination->initialize($config);
		$data['page']=$this->pagination->create_links();
		$this->load->view('git/git_level',$data);
	}
	/**
	 * 主管审批通过，主管审批通过，需要做2件事，检查是否是git组添加，如果是git组添加的话，
	 * 直接需要去检查所属的git组持有者是否也审核通过了。如果所属git组也审核通过，则把要操作的数据转到运维进处理。
	 * 否则的话发邮件通知用指定的用户.
	 * 如果驳回了用户的申请请求，怎发送邮件通知用户。并提示当前的工作流程已经结束。否则的话提交运维进行操作。
	 * 这里运维不需要对用户之前的申请进行检查，只操作就可以啦！
	 * @param int $gits_opid
	 * 这里压根就不用对用户的身份进行校验，这里需要审批的数据都是普通用户的数据
	 */
	public function pass($gits_opid)
	{
		 $gops=$this->gol->find_one($gits_opid);
		 $gops['etime']=time();
		 $gops['state']=1;
		 if($this->gol->save($gops,$gits_opid))
		 {
		  if($gops['apply_type']!=1)//1，为git认证新增加机器
			 {//检查用户组持有者是否申请通过了。否则直接给运维存取数据
		 	  	if($this->creator->check_state($gits_opid))
		 	 	 {
		 	  		//如果通过了检查，则直接发送消息给op
		 	  		$this->add_op($gits_opid);
		 	 	 }
		 	  	else
		 	  	{
		 	  		echo "请等待git组其他用户的审批！";exit;
		 	 	 }
		 	}
			else
			{//直接插入数据给运维人员
		 	  $this->add_op($gits_opid);
			}
		 }
		 else
		 {
		 	echo "系统故障，暂时不能审核通过！";exit;
		 }
	}
	public function add_op($gits_opid)
	{
		$gops=$this->gol->find_one($gits_opid);
		$gops['etime']=time();
		$gops['state']=0;
		$gops['level_id']=$gits_opid;
		$gops['type_id']=1;
		unset($gops['gits_opid'],$gops['user_id']);
		if($this->gol->save($gops))
		{
			$tmp=$this->git->get_userinfo_by_git_id($gops['git_id']);
			$subject="请对{$tmp['realname']}git申请进行处理";
			$edata['msg']="{$subject}";
			$edata['name']='刘士超';
			$msg=$this->load->view('mail/mail_common',$edata,TRUE);
			sendcloud(ADRD_EMAIL_ONE,$subject,$msg);
			echo "系统已经发送邮件通知运维了！";
			exit;
		}
		else
		{
			echo "数据写入失败！";exit;
		}
		
	}
	/**
	 * 驳回用户的申请
	 * @param int $gits_opid
	 */
	public function reject($gits_opid)
	{
		$gops=$this->gol->find_one($gits_opid);
		$this->load->view('git/reject',$gops);
	}
	public function savereject()
	{
		$gits_opid=$this->input->post('gits_opid');
		$git_id=$this->input->post('git_id');
		$data['description']=$this->input->post('description');
		$data['etime']=time();
		$data['state']=-1;
		if($this->gol->save($data,$gits_opid))
		{
			$tmp=$this->git->get_userinfo_by_git_id($git_id);
			$subject="您的上级主管驳回了您的git申请操作";
			$edata['msg']=$subject."驳回原因:".$data['description'];
			$edata['name']=$tmp['realname'];
			$msg=$this->load->view('mail/mail_common',$edata,TRUE);
			sendcloud($tmp['email'], $subject, $msg);
			echo 1;
			exit;
		}
		else
		{
			echo "数据写入失败！";exit;
		}
		
	}
	/**
	 * 获取一个用户申请的详细信息
	 * 申请者信息，git组信息。
	 */
	public function showinfo($gits_opid)
	{
		$this->load->model('Gitsgroup_model',group,TRUE);
		$gops=$this->gol->find_one($gits_opid);
		$gits=$this->git->get_one($gops['git_id']);
		if($gops['newgroups_id']!="")
		{
			$group=$this->group->getuser_group_by_str($gops['newgroups_id']);
		}
		if($gits['add_datagroups']!="")
		{
			$group2=$this->group->getuser_group_by_str($gits['add_datagroups']);
		}
		if($gops['apply_type']==0)//新增git认证审批
		{
			if(!empty($group2))
			{
				$msg="";
				foreach ($group2 as $g)
				{
					$msg.=$g['group_name'].",";
				}
				$msg=substr($msg,0,-1);
			}
			else
			{
				$msg="未加入任何git组";
			}
			echo "<h4>新增git认证申请</h4><p>加入的git组：".$msg.".</p>";
		}
		else if($gops['apply_type']==1)//git认证新增机器
		{
			echo "<h4>新增机器<h4><p>在原有的git认证上新增加一台机器</p>";
		}
		else//新增git用户组
		{
			foreach($group as $g)
			{
				$str.=$g['group_name'].",";
			}
			echo "<p>新增加的git组：".substr($str,0,-1)."</p>";
		}
	}
}