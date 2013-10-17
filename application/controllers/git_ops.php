<?php
/**
 * 运维审批操作流程
 */
class Git_ops extends CI_Controller
{
	//重载父类的构造方法，并导入相关的类库进来
	private  $user_id;
	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('message','form','url'));
		$this->load->library(array('table','pagination','session','dx_auth'));
		$this->load->model('Git_op_level_model','gol',TRUE);
		$this->load->model('Gits_model','git',TRUE);
		$this->load->model('Git_key_model','key',TRUE);
		$this->load->model('Gitsgroup_model','group',TRUE);
		$this->load->model('Users_model','user',TRUE);
		$this->dx_auth->check_uri_permissions();//检查用户权限
		$this->user_id=$this->session->userdata('DX_user_id');
	}
	public function index()
	{
		$this->alllist();
		
	}
	public function alllist()
	{
		$config['base_url'] = base_url('index.php/git_ops/index/');
		$config['total_rows'] = $this->gol->count_alllist(1);
		$config['per_page'] = 10;
		$offset=intval($this->uri->segment(3));
		$rs=$this->gol->alllist($offset,$config['per_page'],1);
		$data['rs']=$rs;
		$this->pagination->initialize($config);
		$data['page']=$this->pagination->create_links();
		$this->load->view('git/git_ops',$data);
	}
	/**
	 * 审批通过的时候，如果需要分配受控目录的话，给用户分配受控目录
	 * 点击提交通过的时候，
	 */
	public function pass($gits_opid)
	{//根绝类型的不同显示不同的通过视图
		$gits_oprs=$this->gol->find_one($gits_opid);
		$type=$gits_oprs['apply_type'];
		//1，type=0 分配受控目录和机器标示
		if($type==0)
		{
			$data['gits_oprs']=$gits_oprs;
			$data['keys']=$this->key->getinfo_by_git_id($gits_oprs['git_id']);
			$this->load->view('git/pass_op1',$data);
		}
		else if ($type==1)
		{
			$this->load->view('git/pass_op2',$gits_oprs);
			//2，type=1分配机器标识,并把单条计入插入到用户的机器列表中
		}
		else
		{
			//3，type=2，直接通过，并且把用户加入到相应的用户组；
			$this->gol->save(array('state'=>1,'user_id'=>$this->user_id,'optime'=>time()),$gits_opid);
			$this->load->model('gitsgroupuser_model','guser',TRUE);
			if($gits['add_datagroups']!="")
			{
				$groups=explode(',', $gits_oprs['newgroups_id']);
				foreach ($groups as $k=>$v)
				{
					$this->guser->save(array('group_id'=>$v,'user_id'=>$gits['add_user'],'addtime'=>time()));
				}
			}
			// git 字符串增加
			$gits=$this->git->get_one($gits_oprs['git_id']);
			$addgroup_str=$gits['add_datagroups'].",".$gits_oprs['newgroups_id'];
			$this->git->save(array('add_datagroups'=>$addgroup_str),$gits_oprs['git_id']);
			//git 字符串增加结束
			$users=$this->git->get_userinfo_by_git_id($gits_oprs['git_id']);
			$subject="您的git用户组申请已经可以使用了";
			$msg="您的git用户组申请已经开通了，请您使用！";
			sendcloud($users['email'], $subject, $msg);
			echo "已发送邮件通知用户啦！";
		}
	}
	/**
	 * 保存审批通过的用户信息
	 */
	public  function savepass1()
	{
		$git_auth=$this->input->post("git_auth");
		$key_id=$this->input->post("key_id");
		$keys=array_combine($key_id, $git_auth);
		$gits_opid=$this->input->post('gits_opid');
		$git_id=$this->input->post('git_id');
		$cfilename=$this->input->post('cfilename');
		//分配受控目录，和机器标识以及用户git组，修改审批信息
		$this->git->save(array('cfilename'=>$cfilename,'git_state'=>1),$git_id);
		foreach($keys as $key=>$value)
		{
			$this->key->save(array('git_auth'=>$value,'key_state'=>1),$key);
		}
		$this->gol->save(array('state'=>1,'user_id'=>$this->user_id,'optime'=>time()),$gits_opid);
		$gits=$this->git->get_one($git_id);
		//修改用户组
		$this->load->model('gitsgroupuser_model','guser',TRUE);
		if($gits['add_datagroups']!="")
		{
			$groups=explode(',', $gits['add_datagroups']);
			foreach ($groups as $k=>$v)
			{
				$this->guser->save(array('group_id'=>$v,'user_id'=>$gits['add_user'],'addtime'=>time()));
			}
		}
		//发送邮件通知用户
		$userinfo=$this->user->get_useremail_by_id($gits['add_user']);
		$user_email=$userinfo['email'];
		$subject="您的git认证申请已经开通";
		$message="您git认证申请已经开通,请参照找相关说明进行使用";
		sendcloud($user_email, $subject, $message);
		echo "已经发送邮件通知了相关用户";
	}
	/**
	 * 保存机器标识申请
	 */
	public function savepass2()
	{
		$git_auth=$this->input->post("git_auth");
		$gits_opid=$this->input->post('gits_opid');
		$git_id=$this->input->post('git_id');
		//分配受控目录，和机器标识以及用户git组，修改审批信息
		$this->gol->save(array('state'=>1,'user_id'=>$this->user_id,'optime'=>time()),$gits_opid);
		$gits=$this->git->get_one($git_id);
		//修改用户组
		//添加机器
		$this->key->save(array('git_auth'=>$git_auth,'key_state'=>1,'gitpub'=>$this->input->post('gitpub'),'git_id'=>$git_id));
		//发送邮件通知用户
		$userinfo=$this->user->get_useremail_by_id($gits['add_user']);
		$user_email=$userinfo['email'];
		$subject="您的git认证添加机器已经申请成功";
		$message="您git认证  添加机器已经成功，您现在可以使用该机器连接自己的库以及git组";
		sendcloud($user_email, $subject, $message);
		echo "已经发送邮件通知了相关用户";
	}
	/**
	 * 审批驳回
	 */
	public function reject($gits_opid)
	{
		$gits_oprs=$this->gol->find_one($gits_opid);
		$this->load->view('git/op_reject',$gits_oprs);
	}
	/**
	 * 保存驳回的原因
	 */
	public function savereject()
	{
		$gits_opid=$this->input->post('gits_opid');
		$gits_oprs=$this->gol->find_one($gits_opid);
		$git_id=$this->input->post('git_id');
		$description=$this->input->post('description');
		$gits_oprs['optime']=time();
		$gits_oprs['state']=-1;
		$gits_oprs['user_id']=$this->user_id;
		$gits_oprs['description']=$description;
		unset($gits_oprs['gits_opid']);
		if($this->gol->save($gits_oprs,$gits_opid))
		{
			$msg="您的git认证申请被驳回";
			$content="git认证相关申请被{$this->session->userdata('DX_realname')}驳回.驳回原因：$description";
			$tmp_to=$this->git->get_userinfo_by_git_id($git_id);
			$to=$tmp_to['email'];
			if($gits_oprs['apply_type']==0)
			{//修改用户git信息，告诉用户git申请失败
				$data['git_state']=-1;
				$this->git->save($data,$git_id);
			}
			sendcloud($to, $msg, $content);
			echo 1;
		}
		else
		{
			echo "暂时不能驳回用户请求！";
		}
	}
	/**
	 * 操作说明
	 * 如果是是新增git认证的话，则在在做说明中，显示上传文件，然后添加那些组。
	 * 如果是新增机器的话，同样需要上传文件。
	 * 同时分配用户组给用户
	 */
	public function showinfo($gits_opid)
	{
		$gits_oprs=$this->gol->find_one($gits_opid);
		$apply_type=$gits_oprs['apply_type'];
		$data['gits']=$this->git->get_one($gits_oprs['git_id']);
		$data['gituser']=$this->git->get_userinfo_by_git_id($gits_oprs['git_id']);
		$data['keys']=$this->key->getinfo_by_git_id($gits_oprs['git_id']);
		$data['gits_oprs']=$gits_oprs;
		//根据审批的内容不同选择展示不同的操作说明界面
		// 一共有三种操作说明界面，1.是新增加的用户组，以及多个机器。
		 if($apply_type==0)
		 {
		 	//1,获取git信息，以及上传文件，git组信息
		 	 $data['title']="增加新的git认证";
		 	 if($data['gits']['add_datagroups']!="")
		 	 {
		 		$data['addgroups']=$this->group->getuser_group_by_str($data['gits']['add_datagroups']);
		 	 }
		 }
		 else if($apply_type==1)
		 {//2.只增加一台机器
		 	$data['title']="增加一台机器";
		 	$data['oldgroups']=$this->group->getuser_group_by_str($data['gits']['add_datagroups']);
		 	
		 }
		 else if($apply_type==2)
		 {//3.增加新的git组用户信息
		 	$data['title']="增加新的git组";
		 	if($data['gits']['add_datagroups']!="")
		 	{
		 		$data['oldgroups']=$this->group->getuser_group_by_str($data['gits']['add_datagroups']);
		 	}
		 	if($gits_oprs['newgroups_id']!="")
		 	{
		 	$data['addgroups']=$this->group->getuser_group_by_str($gits_oprs['newgroups_id']);
		 	}
		 }
		 $this->load->view('git/git_op_showinfo',$data);
	}
	/**
	 * 文件上传
	 */
	public function upfile()
	{
		$gits_opid=$this->uri->segment(3);//获取运维审批的记录
		 $type=$this->uri->segment(4);//获取用户的申请类型
		 $gits_oprs=$this->gol->find_one($gits_opid);
		 
		if($type==0)//新增加git认证审批上传文件
		{
			$filename=$this->key->getinfo_by_git_id_state($gits_oprs['git_id']);//获取未分配标示的内容
			$this->help_uploadfile($filename);
		}
		else if($type==1)//新增加机器上传文件
		{
			$filename[]=array('gitpub'=>$gits_oprs['filename']);
			$this->help_uploadfile($filename);
		}
		else
		{
			echo "数据异常，上传文件失败！";
		}
	}
	/**
	 * 辅助函数，相关的。上传文件函数
	 * $filename 为上传文件名的数组
	 */
	public function help_uploadfile(array $filename)
	{
		foreach($filename as $f)
		{
			if(!$this->check_filename($f['gitpub']))
			{
				$this->_ftp_upload($f['gitpub']);
				
			}
			else
			{
				echo "文件已经上传成功！";
			}
		}
		echo "上传成功！"; 
	}
	public function git_total()
	{
		  $totals=$this->gol->total();
		  $this->load->view('git/git_total',array('totals'=>$totals));
	}
	/**
	 * 文件名检查
	 * @param string $filename
	 * @return boolean
	 */
	public function check_filename($filename)
	{
		$this->load->library('ftp');
		$this->ftp->connect();
		$list=$this->ftp->list_files('/pub/');
		$filename='/pub/'.$filename;
		if(in_array($filename, $list))
		{
			return  TRUE;
		}
		else
		{
			return FALSE;
		}
	
	}
	/**
	 * ftp 文件上传
	 */
	private function _ftp_upload($filename)
	{
		$this->load->library('ftp');
		$this->ftp->connect();
		return $this->ftp->upload('./uploads/pub/'.$filename, '/pub/'.$filename,'auto');
	}
}