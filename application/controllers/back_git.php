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
		
		$this->load->helper(array('form','url','message','pagination'));
		$this->load->library(array('dx_auth','session'));
		//用户以及用户主管信息已经存储到了session中了
		//$this->load->model('dx_auth/Users','users',TRUE);//加载授权用户的模型
		//$this->load->model('Users_model','myusers',TRUE);//加载授权用户的模型
		//$this->load->model('Gits_model','git',TRUE);//加载git账号库的模型
		$this->load->model('Gitsgroup_model','group',TRUE);
		$this->load->model('Git_op_level_model','gol',TRUE);
		$this->load->model('Group_creators_model','creator',TRUE);
		$this->dx_auth->check_uri_permissions();//检查用户权限
		$this->user_id=$this->session->userdata('DX_user_id');
		//echo "<pre>";
		//print_r($this->session->all_userdata());
		//echo $this->session->userdata('DX_realname');
		//echo '</pre>';
	}

	/**
	 * git 账号申请
	 */
	function gitshowapply()
	{
		$this->output->enable_profiler(TRUE);
		$data['msg']="请输入您生成的sshkey";
		//使用git组显示信息，获取所有的用户组
		$data['git_groups']=$this->group->get_all();
	   $this->load->view("git/git_gitshowapply",$data);
	}
	
	/**
	 * 获取申请者提交的表单内容
	 */
	public function apply_add()
	{
		$data['git_type']=$this->input->post('git_type');
		$is_group=$this->input->post('is_group');
		//如果申请类型为1，或3 怎生成文件，否则不生成文件
		if($data['git_type']==1 || $data['git_type']==3)
		{
			$git_pub=$this->_filter_line($this->input->post('git_pub'));
			$pubfilename=$this->session->userdata('DX_username')."".date("YmdHis").".pub";
			if(FALSE===file_put_contents('./uploads/pub/'.$pubfilename, $git_pub))
			{
				echo "不能保存文件！";
				exit;
			}
			else
			{
				$data['gitpub']=$pubfilename;
			}
		}
		//保存数据，发送邮件通知相关人员
		$data['add_datagroups']=implode(',', $this->input->post('add_datagroups'));
		$data['git_state']=0;
		$data['addtime']=time();
		$data['add_user']=$this->user_id;
		if($data['git_type']==1)
		{
			if($is_group==2){unset($data['add_datagroups']);}
		}
		if($data['git_type']==3)
		{
			unset($data['add_datagroups']);
		}
	
		$insert_id=$this->git->save_apply($data);
		if($insert_id!=0)
		{
			// 插入数据成功后，发送邮件通知
			$this->apply_add_sendemail($insert_id);
		}
		else
		{
			echo "数据写入失败！";
			exit;
		}
	
	}
	
	/**
	 * 用户添加git认证成功后，发送邮件请求，以及请相关的人员进行审批
	 */
	private function  apply_add_sendemail($insert_id)
	{
		$pid=$this->session->userdata('DX_pid');
		if($pid==0)
		{//直接发送请求给op，可以是全部的op，但是目前只发送给其中的一个。
			//1, 先把要审核的信息存储到数据库中，2，信息存储完毕之后发送邮件给运维
			$opdata['type_id']=1;
			$opdata['git_id']=$insert_id;
			$opdata['user_id']=$this->user_id;
			$opdata['state']=0;
			if($this->gol->save($opdata))
			{
				//数据存储之后，发送邮件给运维工程师
				$subject="请处理".$this->session->userdata('DX_realname')."的git认证申请";
				$msg="<p>您好！<p> <p>$subject</p><p>请不要 回复此邮件！</p>";
				sendcloud(ADRD_EMAIL_ONE, $subject, $msg);
				echo "我们已经发送邮件通知运维工程师处理您的申请啦！";
			}
			else
			{
				echo "无法通知运维工程师！请联系管理员！";
			}
		}
		else
		{//发送邮件通知，主管以及发送信息给git组审批人员
			//1,获取当期这一条信息，通过查看是否加入指定的git组，如果有git组，发送信息通知主管。通知git组的创建者信息
			//通知主管，通知git组
			$level_info=$this->session->userdata('level_info');
			$opdata['type_id']=0;
			if($level_op_id=$this->gol->save($opdata))
			{// 发送邮件通知主管
				$maildata['name']=$level_info['realname'];
				$maildata['msg']="请审批您的下属{$this->session->userdata('DX_realname')}的git认证申请";
				$msg=$this->load->view('mail/mail_common',$maildata,TRUE);
				$subject="请审批{$this->session->userdata('DX_realname')}git认证申请 ";
				sendcloud($level_info['email'], $subject, $msg);
				$gits=$this->git->get_one($insert_id);
				if(!empty($gits['add_datagroups']) && $gits['git_type']!=3)
				{//通知相关用户组人员进行审批
					$gits_array=explode(',', $gits['add_datagroups']);
					$creator_data['gle_id']=$level_op_id;
					$creator_data['gcre_state']=0;
					$creator_data['git_id']=$insert_id;
					$creator_data['change_id']=$this->user_id;
					foreach ($gits_array as $group_id)
					{
						$creator_data['group_id']=$group_id;
						$creator_data['gcre_creator']=$this->group->get_creator_by_group_id($group_id);
						$this->creator->save($creator_data);
					}
					//通过联合查询，给用户组用户发送邮件
					if(！$this->sendmail_togroupcreator(($gits['add_datagroups'])))
					{
						echo "不能发送邮件通知git组创建者！";
						exit;
					}
				}
				echo "系统已经发送邮件通知相关人员进行审批！";
			}
			else
			{
				echo "向主管提交审批失败！";exit;
			}
				
		}
	}
	//为git组创建者发送邮件的方法
	public function sendmail_togroupcreator($group_id)
	{
		$emails=$this->group->get_email_by_group_id($group_id);
		print_r($emails);
		$tmp=array();
		foreach ($emails as $email)
		{
			array_push($tmp, $email['email']);
		}
		$subject="请审批{$this->session->userdata('DX_realname')}加入您的git组申请";
		$msg="<p>您好！</p><p>$subject</p><p>祝：工作顺利！</p>";
		return sendcloud($tmp,$subject,$msg);
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
	 * 我的git账号信息列表,显示所有的git账号信息
	 */
	
	public function mygit()
	{
		$this->load->library(array('table','pagination'));
		$userinfo=$this->users->get_user_by_id($this->user_id);
	
		$config['base_url'] = base_url('index.php/git/mygit/');
		$config['total_rows'] = $this->git->mygit_count($this->user_id);
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
		//$this->load->model('Gits_model','git',TRUE);
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
	
			$this->load->model('Gitsgroup_model','group',TRUE);
			$gits['add_datagroups']=$this->group->get_rs_by_csv($gits['add_datagroups']);
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
					$groupinfo=$this->group->find_one($group);
					$data['git_id']=$git_id;
					$data['group_id']=$group;
					$data['change_id']=$this->user_id;
					$data['gcre_state']=0;
					$data['gcre_creator']=$groupinfo['group_creator'];
					$this->gcre->save($data);
					$creator_info=$this->myusers->get_useremail_by_id($data['gcre_creator']);
					$subject="{$userinfo['realname']}要求加入您的git组";
					$message="{$userinfo['realname']}要求加入您的git组,请您尽快进行审核！<br/><br/>&nbsp;&nbsp;此email系自动发送，请不要回复，谢谢！";
					$this->_sendmail($creator_info['email'], $subject, $message);
				}
			}
		}
		/**
		 * 主管审核的时候，查看的data组相关信息
		 */
		public function gitgroups()
		{
			 $group_str=$this->input->get('str');
			 $this->load->model('Gitsgroup_model','group',TRUE);
			 echo json_encode($this->group->get_rs_by_csv($group_str),true);
		}
		
	}
	
?>