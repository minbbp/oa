<?php
/**
 * 与git账号管理的控制器，
 * 包括申请，处理，查询，以及用户信息统计。
 * @author minbbp
 * @version 1.0.0
 */
class Git extends CI_Controller
{

	private $user_id;//当前操作的用户的id
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('form');
		$this->load->helper('url');
		$this->load->library('dx_auth');
		$this->load->library('email');
		$this->load->model('dx_auth/Users','users',TRUE);//加载授权用户的模型
		$this->load->model('Users_model','myusers',TRUE);//加载授权用户的模型
		$this->load->model('Gits_model','git',TRUE);//加载git账号库的模型
		$this->dx_auth->check_uri_permissions();//检查用户权限
		$this->user_id=$this->dx_auth->get_user_id();
		$this->load->model('Gitsgroup_model',group,TRUE);
	}

	/**
	 * git 账号申请
	 */
	function gitshowapply()
	{
		
		$data['msg']="请输入您成的的sshkey";
		$data['state']=0;
		//使用git组显示信息，获取所有的用户组
		$data['git_groups']=$this->group->get_all();
		//print_r($data);
		$this->load->view("git_gitshowapply",$data);
	}
	
	/**
	 * 获取申请者提交的表单内容
	 */
	public function apply_add()
	{
		//获取ssh-key
		//生成文件名
		$filename=$this->dx_auth->get_username()."".date("YmdHms").".pub";
		//获取当前用户的详细信息
		$userinfo=$this->users->get_user_by_id($this->user_id);
		$userarray=$userinfo->row_array();
		//获取用户的输入信息
		 $data=$this->input->post('gitpub');
		 $data=$this->_filter_line($data);
		
		$addgroups=$this->input->post('add_datagroups');
		$addgroups=implode(',', $addgroups);//把用户组的对应关系以CSV字符串进行保存到数据库中
		//把用户信息的ssh-key存入文件，并把文件名存入数据库
		if(FALSE!==file_put_contents('./uploads/pub/'.$filename, $data))
		{
			
			$mygitdata=array(
					'gitpub'=>$filename,
					'add_user'=>$userarray['id'],
					'addtime'=>time(),
					'add_datagroups'=>$addgroups,
					'git_type'=>$this->input->post('git_type'),
					'git_state'=>0
			);
			//如果为主管，直接放行，把自己的id号加入进去
			if($userarray['pid']==0 )
			{//主管以及没有主管的员工
					$mygitdata['h_level']=0;
					$mygitdata['h_state']=10;
			}
			else
			{//拥有主管的员工
				$mygitdata['h_level']=$userarray['pid'];
			}
			$Mygit_save=$this->git->save_apply($mygitdata);
			
			if(FALSE!=$Mygit_save)
			{
				
				// 发信通知申请者，的申请，并告知申请的进度,主管和员工的工作申请流程单
				if($userarray['level']==0)
				{
					//获取主管的邮件
					 $hlevel_info=$this->users->get_user_by_id($mygitdata['h_level']);
					 $hlevel_email=$hlevel_info->row_array();
					 $mydata['msg']="您已成功申请，请耐心等待您的主管<b>{$hlevel_email['realname']}</b>的审核！";
					 $mail_data['title']="{$userarray['realname']}git账号申请流程通知";
					 $mail_data['content']=$mygitdata;
					 $mail_data['username']=$userarray['username'];
					 $mail_data['level']=$userarray['realname'];
					 $sendmail_to_level=$this->load->view('mail/mail_git_apply',$mail_data,TRUE);
					 $this->_sendmail($userarray['email'], $mail_data['title'],$sendmail_to_level,$hlevel_email['email']);
					 $this->_sendmsg_group($Mygit_save);
				}
				else
				{//如果申请者本身为主管，或者其他人员，则直接发邮件给操作者op人员
					$mail_user_data['title']="您申请git账号的工作流程通知";
					$mail_user_data['content']=$mygitdata;
					$mail_user_data['username']=$userarray['username'];
					$sendmail_to_user=$this->load->view('mail/mail_git_apply',$mail_user_data,TRUE);
					$this->_sendmail($userarray['email'], $mail_user_data['title'],$sendmail_to_user);
					$op_title="请审核{$userarray['realname']}git账号申请";
					$this->_sendmail(array(ADRD_EMAIL_ONE,ADRD_EMAIL_TWO),$op_title, $sendmail_to_user);
					
				}
			}
			else
			{
				$mydata['msg']='申请失败，请联系管理员！';
				//直接给申请者发送邮件，同时给管理员发送，告示申请邮件失败，请管理员尽快处理
			}
		}
		else
		{
			$mydata['msg']= "文件空间不能写入！";
		}
		$mydata['state']=1;
		$this->load->view("git_gitshowapply",$mydata);
	}
	/**
	 * 过滤回车换行符
	 * @param string $descclear
	 */
	private function _filter_line($descclear)
	{
		$descclear = str_replace("\r","",$descclear);//过滤换行
		$descclear = str_replace("\n","",$descclear);//过滤换行
		$descclear = str_replace("\t","",$descclear);//过滤换行
		$descclear = str_replace("\r\n","",$descclear);//过滤换行
		$descclear=preg_replace("/\s+/", "", $descclear);//过滤多余回车
		return $descclear;
	}
	/**
	 * 我的git账号信息列表
	 */
	public function mygit()
	{
		$this->load->library(array('table','pagination'));
		$userinfo=$this->users->get_user_by_id($this->user_id);
		
		$config['base_url'] = base_url().'index.php/git/mygit/';
		$config['total_rows'] = $this->git->get_count();
		$config['per_page'] = 4;
	
		//$rs=$this->git->show_mygit($user_id,10,0);
		$offset=intval($this->uri->segment(3));
		$rs=$this->git->show_mygit($this->user_id,$config['per_page'],$offset);
		$this->pagination->initialize($config);
		$page=$this->pagination->create_links();
		$mygit=array('all_gits'=>$rs->result(),"page"=>$page,'user'=>$userinfo->row_array());
		$this->load->view("git_mygit",$mygit);
	}
	/**
	 * git 列表，管理员查看的,所有的git列表。按照状态字段，正序排列
	 * 对没有受理的点击处理按钮可以进行处理
	 */
	public function alllist()
	{
		$this->load->library(array('table','pagination'));
		$config['base_url'] = base_url().'index.php/git/alllist/';
		$config['total_rows'] = $this->git->get_count();
		$config['per_page'] = 4;
		$offset=intval($this->uri->segment(3));
		$rs=$this->git->show_allgit($config['per_page'],$offset);
		$this->pagination->initialize($config);
		$page=$this->pagination->create_links();
		$mygit=array('all_gits'=>$rs->result(),"page"=>$page);
		$this->load->view("git_alllist",$mygit);
	}
	/**
	 * 状态说明，-1为失败，0问受理，1为正在处理，2为处理成功，3为禁用。
	 * 当状态要修改为1的时候，则把指定的相关文件上传到指定的ftp服务器
	 * 改变git_state的控制器
	 * 如果要把状态值改为1，则需要把文件上传到指定的ftp服务器，然后修改该表状态
	 * 
	 */
	public function git_change_state()
	{
	
		$h_state=$this->input->get('h_state');
		$git_id=$this->input->get('git_id');
		$git_row=$this->_get_gitrow($git_id);
		unset($git_row['git_id']);
		$this->load->model('Gits_model','git',TRUE);
		$git_row['operator']=$this->dx_auth->get_user_id();
		$git_row['operatime']=time();
		//如果上级审核通过，这里的这个1并不是真实的状态而是一个可以上传服务器的标识符
		if($h_state==1)
		{
			if($this->git->save($git_row,$git_id))
			{
				$filename=$git_row['gitpub'];
				if(FALSE==$this->check_filename($filename))
				{
					if($this->_ftp_upload($filename))
					{
						echo "文件已上传到服务器，请您处理！";
					}
					else
					{
						echo "文件不能上传";
					}
				}
				else
				{
					echo "文件 {$filename}已经上传过了！";
				}
			}
			else
			{
				echo "不能保存修改者的信息！";
			}
				
		}
		else if($h_state==2)
		{
			//修改账号状态为禁用状态
			$git_row['git_state']=-2;
			if($this->git->save($git_row,$git_id))
			{
				echo " 禁用成功！";
				//  发送禁用邮件通知
			}
			else
			{
				echo "禁用失败！";
				exit;
			}
		}
		else if($h_state==3)
		{
			//修改账号状态为删除状态
			$git_row['git_state']=2;
			if($this->git->save($git_row,$git_id))
			{
				echo " 删除成功！";
				//  发送禁用邮件通知
			}
			else
			{
				echo "删除失败！";
				exit;
			}
		}
		else if($h_state==4)
		{
			//修改账号状态为开启状态
			$git_row['git_state']=1;
			if($this->git->save($git_row,$git_id))
			{
				echo " 开启成功！";
				//  发送禁用邮件通知
			}
			else
			{
				echo "开启失败！";
				exit;
			}
		}
	
	}
	/**
	 * 通过git_id获取一条记录
	 */
	private function _get_gitrow($git_id)
	{
		return $this->git->get_one($git_id);
	}
	/**
	 * 使用ftp检查文件名是否已经存在，若存在返回true，不存在返回false
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
	/**
	 * 删除一条记录
	 */
	public function delete()
	{
		$git_id=$this->input->get('git_id');
		if($this->git->delete($git_id))
		{
			echo "删除成功！";
		}
		else
		{
			echo "删除失败！";
		}
	}
	/**
	 *修改一条记录，主要是要发一些备注信息，以及成功，或失败后给用户发送邮件通知
	 */
	public function gitupdate($git_id)
	{
		$git_one=$this->git->get_one($git_id);
		$data['git_one']=$git_one;
		$userinfo=$this->users->get_user_by_id($git_one['add_user']);
		$data['user']=$userinfo->row_array();
		$this->load->view('git_gitupdate',$data);
	}
	/**
	 * 保存，处理结果。可以选择给用户发送邮件
	 * @param int $git_id
	 */
	public function git_save($git_id)
	{
		$this->output->enable_profiler(TRUE);
		$gitdata['git_account']=$this->input->post('git_account');
		$gitdata['git_description']=$this->input->post('git_description');
		$gitdata['op_state']=$this->input->post('op_state');
		if($gitdata['op_state']==2 || $gitdata['op_state']==11)
		{
			$gitdata['git_state']=1;
		}
		$sendmail=$this->input->post('sendmail');
		$sendmailforlevel=$this->input->post('sendmailforlevel');
		if($this->git->save($gitdata,$git_id))
		{
			$this->load->model('Users_model','user',TRUE);
			
			if($sendmail==1)
			{
				//配置发送邮件的相关信息，给操作，主管，申请者发送邮件
				$gitinfo=$this->git->get_one($git_id);
				$add_userinfo=$this->user->get_useremail_by_id($gitinfo['add_user']);
				$op_info=$this->user->get_useremail_by_id($gitinfo['operator']);
				$data['git']=$gitinfo;
				$data['add_userinfo']=$add_userinfo;
				$data['opinfo']=$op_info;
				if($sendmailforlevel==1)
				{
					$levelinfo=$this->user->get_useremail_by_id($gitinfo['h_level']);
					$data['level']=$levelinfo;
					$sendmail_content=$this->load->view('mail/mail_git_op_user_level',$data,TRUE);
					$subject="{$add_userinfo['realname']}的git账号申请处理结果流程单";
					$sendrs=$this->_sendmail($add_userinfo['email'],$subject, $sendmail_content,array($op_info['email'],$levelinfo['email']));
				}
				else
				{
					
					$sendmail_content=$this->load->view('mail/mail_git_op_user_level',$data,TRUE);
					$subject="{$add_userinfo['realname']}的git账号申请处理结果流程单";
					$sendrs=$this->_sendmail($add_userinfo['email'],$subject, $sendmail_content,$op_info['email']);
				}
				if($sendrs)
				{
					redirect('git/alllist');
				}
				else
				{
					echo " 邮件发送失败！";
					exit;
				}
			}
			else
			{
				redirect('git/alllist');
			}
	
		}
		else
		{
			echo "状态更改失败！";
			exit;
		}
		 
	}
		
	public function git_user_search()
	{
		$username=$this->input->post('username');
		$this->load->model('dx_auth/users', 'users');
		$this->load->model('Gits_model','git',TRUE);
		$userinfo_query=$this->users->get_user_by_username($username);
		$userinfo=$userinfo_query->row_array();
		if(!empty($userinfo))
		{
			$this->load->library(array('table','pagination'));
	
			$config['base_url'] = base_url().'index.php/git/git_user_search/';
			$config['total_rows'] = $this->git->show_one_user_count($userinfo['id']);
			$config['per_page'] = 4;
			$offset=intval($this->uri->segment(3));
			$rs=$this->git->show_one_user_git($userinfo['id'],$config['per_page'],$offset);
			$this->pagination->initialize($config);
			$page=$this->pagination->create_links();
			$mygit=array('all_gits'=>$rs->result(),"page"=>$page,'userinfo'=>$userinfo);
			$this->load->view("git_oneuserlist",$mygit);
		}
		else
		{
			$this->alllist();
		}
	}
	/**
	 * git 账号处理统计，统计每个操作员分别处理了多少
	 */
	public function git_total()
	{
		$this->load->model('Gits_model','git',TRUE);
		$data['total']=$this->git->total_operator();
		$this->load->view('git_total',$data);
	}
	/**
	 * 直接上级主管的审批的列表
	 */
	public function h_level_apply()
	{
		$this->load->library(array('table','pagination'));
		
		$config['base_url'] = base_url().'index.php/git/h_level_apply/';
		$config['total_rows'] = $this->git->show_one_level_count($this->dx_auth->get_user_id());
		$config['per_page'] = 6;
		$offset=intval($this->uri->segment(3));
		
		$all_apply=$this->git->show_one_level($this->dx_auth->get_user_id(),$offset,$config['per_page']);
		$data['gits']=$all_apply;
		
		$this->pagination->initialize($config);
		$page=$this->pagination->create_links();
		$data['page']=$page;
		$this->load->view('git_level_apply',$data);
	}
	/**
	 * 上级主管审批
	 */
	public function h_apply($git_id)
	{
		$this->load->model('Gits_model','git',TRUE);
		$this->load->model('dx_auth/Users','user',TRUE);
		$gits=$this->git->get_one($git_id);
		$userinfo=$this->user->get_user_by_id($gits['add_user']);
		$data=array(
			'gits'=>$gits,
			'userinfo'=>$userinfo->row_array()
		);
		$this->load->view('git_h_apply',$data);
	}
	/**
	 * 保存上级主管处理过的用户的git账号
	 */
	public function h_apply_change()
	{
		$this->load->model('Gits_model','git',TRUE);
		$git_id=$this->input->post('git_id');
		$changedata['h_state']=$this->input->post('h_state');
		$changedata['h_time']=time();
		$changedata['h_description']=$this->input->post('h_description');
		if($this->git->save($changedata,$git_id))
		{
			// 发送邮件通知
			$this->load->model('Users_model','user',TRUE);
			$gitinfo=$this->git->get_one($git_id);
			$useremail=$this->user->get_useremail_by_id($gitinfo['add_user']);
			$data['userinfo']=$useremail;
			$data['git']=$changedata;
			$render_email=$this->load->view('mail/mail_to_user_or_op',$data,TRUE);
			//如果上级主管审核通过，则发送邮件给申请者和任意一个op
			if($changedata['h_state']==1)
			{
				$this->_sendmail($useremail['email'], '您的git账号申请主管已经审核通过', $render_email,ADRD_EMAIL_ONE);
			}
			else
			{
				$this->_sendmail($useremail['email'], '您的git账号申请您的主管驳回了您的申请', $render_email);
			}
			$this->h_level_apply();
		}
	}
	/**
	 * 上级主管禁用账号,禁用账号目前取消
	 */
	/* public function h_disable_git()
	{
		$data_change['h_state']=$this->input->get('h_state');
		$data_change['h_time']=time();
		$git_id=$this->input->get('git_id');
		$this->load->model('Gits_model','git',TRUE);
		if($this->git->save($data_change,$git_id))
		{
			echo "禁用成功！";
			//   发送邮件通知管理
		}
		else
		{
			echo "您暂时无法禁用该账号，请于管理员联系！";
		}
	} */
	//发送邮件的函数
	private function _sendmail($to,$subject,$message,$cc=null)
	{
	 	$this->email->from(SYS_EMAIL,SYS_EMAILNAME);
	 	$this->email->to($to);
	 	if($cc)
	 	{
	 		$this->email->cc($cc);
	 	}
	 	$this->email->subject($subject);
	 	$this->email->message($message);
	   return $this->email->send();
	}
	/**
	 * 提交用户组申请，对应的用户组交给对应的用户进行审批
	 */
	public function _sendmsg_group($git_id)
	{
		$this->load->model('Group_creators_model','gcre',TRUE);
		$gitinfo=$this->git->get_one($git_id);
		 $userinfo=$this->myusers->get_useremail_by_id($this->user_id);
		$groups=explode(',', $gitinfo['add_datagroups']);
		if(!empty($groups))
		{
			foreach($groups as $key=>$group)
			{
				$groupinfo=$this->group->get_one($group);
				$data['git_id']=$git_id;
				$data['group_id']=$group;
				$data['change_id']=$this->user_id;
				$data['gcre_state']=0;
				$data['gcre_creator']=$groupinfo['group_creator'];
				$this->gcre->save($data);
				$creator_info=$this->myusers->get_useremail_by_id($data['gcre_creator']);
				$subject="{$userinfo['realname']}要求加入您的git组";
				$message="{$userinfo['realname']}要求加入您的git组,请您尽快进行审核！<br/><br/>此邮件系自动发送，请不要回复，谢谢！";
				$this->_sendmail($creator_info['email'], $subject, $message);
			}
		}
	}
}
?>