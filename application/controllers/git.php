<?php
/**
 * 与git账号管理的控制器，
 * 包括申请，处理，查询，以及用户信息统计。
 * @author minbbp
 * @version 1.0.1
 */
class Git extends CI_Controller
{
	private $user_id;//当前操作的用户的id
	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('form','url','message'));
		$this->load->library(array('dx_auth','session','pagination'));
		//用户以及用户主管信息已经存储到了session中了
		$this->load->model('dx_auth/Users','users',TRUE);//加载授权用户的模型
		$this->load->model('Users_model','myusers',TRUE);//加载授权用户的模型
		$this->load->model('Gits_model','git',TRUE);//加载git账号库的模型
		$this->load->model('Gitsgroup_model','group',TRUE);
		$this->load->model('Git_op_level_model','gol',TRUE);
		$this->load->model('Group_creators_model','creator',TRUE);
		$this->load->model('Git_key_model','key',TRUE);
		$this->dx_auth->check_uri_permissions();//检查用户权限
		$this->user_id=$this->session->userdata('DX_user_id');
	}

	/**
	 * git 账号申请视图展示
	 */
	function gitshowapply()
	{
		//使用git组显示信息，获取所有的用户组
	   $data['git_groups']=$this->group->get_all();
	   $this->load->view("git/git_gitshowapply",$data);
	}
	/**
	 * 获取申请者提交的表单内容,根据不同的申请内容展示提交不同的审批流程
	 */
	public function apply_add()
	{
		$is_group=$this->input->post('is_group');
		//保存git授权申请，保存成功之后保存文件，把文件批量保key表中
		if(1==$is_group)//加入组
		{
			$data['add_datagroups']=@implode(',',$this->input->post('add_datagroups'));
		}
	    $data['add_user']=$this->user_id;
	    $data['git_state']=0;
	    $data['addtime']=time();
	    //保存用户申请数据
	    $insert_id=$this->git->save_apply($data);
	    if($insert_id!=0)
	    {//保存文件，进行批量保存
	    	$gitpubs=$this->input->post('gitpub');
	    	$savedata=array();
	    	foreach($gitpubs as $sdata)
	    	{
	    		 // 写文件，保存文件内容
	    		$time=date('Y-m-d-h-i-s').'_'.rand(0, 10);//文件命名
	    		//过滤字符串
	    		$git_pub=$this->_filter_line($sdata);
	    		$tmp['gitpub']=$this->session->userdata('DX_username')."".$time.".pub";
	    		if(FALSE===file_put_contents('./uploads/pub/'.$tmp['gitpub'], $git_pub))
				{
					echo "不能保存文件！";
					log_message('error','->pub 目录没有写入权限');
					exit;
				}
				$tmp['git_id']=$insert_id;
				$tmp['addtime']=time();
				$tmp['key_state']=0;
				$savedata[]=$tmp;
	    	}
	    	// 使用批量保存文件，如果保存失败
	    	if(false==$this->key->save_batch($savedata))
	    	{
	    		echo "保存ssh-key 失败！";
	    		$msg=$this->session->userdata('DX_realname')."->ssh-key保存导数据库时出现错误！";
	    		log_message('error',$msg);
	    		exit;
	    	}
	    	//保存审批信息，并发送邮件通知运维
	    	$this->apply_add_sendemail($insert_id);
	    }
	    else
	    {
	    	// 保存失败之后，提示用户保存失败
	    	   echo "添加git授权失败！";
	    	   $msg=$this->session->userdata('DX_realname')."->保存git认证信息失败！";
	    	   log_message('error',$msg);
	    	   exit;
	    }
	}
	
	/**
	 * 用户添加git认证成功后，发送邮件请求，以及请相关的人员进行审批
	 * @param int $insert_id 新添加的git认证id
	 */
	private function  apply_add_sendemail($insert_id)
	{
		$pid=$this->session->userdata('DX_pid');
		//如果为主管或者没有主管的用户直接发送用户运维直接进行审批
		if($pid==0)
		{	
			//直接发送请求给op，但是目前只发送给刘士超进行处理。
			//1, 先把要审核的信息存储到数据库中
			//2,信息存储完毕之后发送邮件给运维
			$opdata['type_id']=1;//1为运维审批，0为主管审批
			$opdata['git_id']=$insert_id;
			$opdata['user_id']=$this->user_id;
			$opdata['state']=0;
			$opdata['btime']=time();
			if($this->gol->save($opdata))
			{
				//数据存储之后，发送邮件给运维工程师,同时抄送申请者
				$subject="请处理".$this->session->userdata('DX_realname')."的git认证申请!";
				$data['msg']=$subject;
				$data['name']='刘士超';
				$msg=$this->load->view('mail/mail_common',$data,TRUE);
				sendcloud(ADRD_EMAIL_ONE, $subject, $msg,$this->session->userdata('DX_email'));
				echo "我们已经发送邮件通知运维工程师处理您的申请啦！";
			}
			else
			{
				echo "无法通知运维工程师！请联系管理员！";
				log_message('error','git认证无法保存_数据给运维工程师');
				exit;
			}
		}
		else
		{
			//发送邮件通知，主管以及发送信息给git组审批人员
			//1,获取当期这一条信息，通过查看是否加入指定的git组，如果有git组，发送信息通知主管。通知git组的创建者信息
			//通知主管，通知git组
			$level_info=$this->session->userdata('level_info');
			$leveldata['git_id']=$insert_id;
			$leveldata['user_id']=$level_info['id'];
			$leveldata['state']=0;
			$leveldata['type_id']=0;
			$leveldata['btime']=time();
			$level_op_id=$this->gol->save($leveldata);
			if($level_op_id)
			{// 发送邮件通知主管
				$gits=$this->git->get_one($insert_id);
				if($gits['add_datagroups']!="")
				{//通知相关用户组人员进行审批
						$gits_array=@explode(',', $gits['add_datagroups']);
						$creator_data['gle_id']=$level_op_id;
						$creator_data['gcre_state']=0;
						$creator_data['git_id']=$insert_id;
						$creator_data['change_id']=$this->user_id;
						$creator_data['addtime']=time();
						foreach ($gits_array as $group_id)
						{
							$creator_data['group_id']=$group_id;
							$creator_data['gcre_creator']=$this->group->get_creator_by_group_id($group_id);
							// 如果说组的拥有者为当前用户的主管，则不需要主管再进行审批。
							if($creator_data['gcre_creator']!=$level_info['id'])
							{
								$this->creator->save($creator_data);
							}
						}
			//通过联合查询，给用户组用户发送邮件
						//$maildata['name']=$level_info['realname'];
						//$maildata['msg']="请审批{$this->session->userdata('DX_realname')}的git认证申请！";
						//$msg=$this->load->view('mail/mail_common',$maildata,TRUE);
						$subject="请审批{$this->session->userdata('DX_realname')}git认证申请 ";
						//发送信息通知主管的时候，抄送邮件给申请者一份
						$git_groups_info=$this->sendmail_togroupcreator($gits['add_datagroups']);
						$son_msg=$this->load->view('mail/new_mail_git',array('realname'=>$this->session->userdata('DX_realname'),'levelinfo'=>$level_info,'gitgroups'=>$git_groups_info),TRUE);
						echo "系统已经发送邮件通知相关审批人员!";
						$msg=$this->load->view('mail/mail_common',array('name'=>'各位负责人','msg'=>$son_msg),TRUE);
						$cc=array();
						foreach ($git_groups_info as $g)
						{
							array_push($cc, $g['email']);
						}
						array_push($cc,$this->session->userdata('DX_email'));
						sendcloud($level_info['email'],$subject,$msg,$cc);
				}
				else
				{
					//发送邮件通知主管即可
					
					echo "系统已经发送邮件通知相关人员进行审批！";
					$maildata['name']=$level_info['realname'];
					$maildata['msg']="请审批{$this->session->userdata('DX_realname')}的git认证申请！";
					$msg=$this->load->view('mail/mail_common',$maildata,TRUE);
					$subject="请审批{$this->session->userdata('DX_realname')}git认证申请 ";
					sendcloud($level_info['email'],$subject,$msg,$this->session->userdata('DX_email'));
				}
			  
			}
			else
			{
				echo "向主管提交审批失败！";
				log_message('error','git认证提交主管审批，提交数据失败！');
				exit;
			}
		}
	}
	/**
	 * 以下方法为测试方法
	 */
	/* public function test_sendmail_tog()
	{
		//$this->sendmail_togroupcreator('1,2');
		$git_groups_info=$this->sendmail_togroupcreator('1,2');
		$level_info=$this->session->userdata('level_info');
		 $son_msg=$this->load->view('mail/new_mail_git',array('realname'=>$this->session->userdata('DX_realname'),'levelinfo'=>$level_info,'gitgroups'=>$git_groups_info),TRUE);
		$msg=$this->load->view('mail/mail_common',array('name'=>'各位负责人','msg'=>$son_msg));
	} */
	/**
	 * 为git组创建者发送邮件的方法
	 * 用户申请git认证时发送邮件通知git组的创建者
	 * @param int $group_id
	 * @return boolean 如果发送成功返回true，失败返回false 
	 */
	public function sendmail_togroupcreator($group_id)
	{
		//log_message('error','调用到这里了'.$group_id);
		$emails=$this->group->get_email_by_group_id($group_id);
		$level_info=$this->session->userdata('level_info');
		$tmp=array();
		foreach ($emails as $email)
		{
			if($email['email']!=$level_info['email'])
			{
				array_push($tmp, $email);
			}
		}
		return $tmp;
		// 去除主管的邮件信息,如果用户不为空发送邮件通知
		//log_message('error',json_encode($tmp));
		/* if(!empty($tmp))
		{
			$subject="{$this->session->userdata('DX_realname')}申请加入您的git组";
			$data['name']='git组管理员';
			$data['msg']="{$subject},请您审批！";
			$msg=$this->load->view('mail/mail_common',$data,TRUE);
			return sendcloud($tmp,$subject,$msg);
			
		} */
	}
	/**
	 * 过滤回车换行符
	 * @param string $descclear
	 */
	private function _filter_line($descclear)
	{
		//不过滤换行符，只过滤回车符
		$descclear = str_replace(chr(10),'',$descclear);//过滤ctrl+m
		$descclear = str_replace(chr(13),'',$descclear);//过滤ctrl+m
		return $descclear. chr(10); 
		
	}
	/**
	 * 我的git认证,向用户展示所有的git认证
	 */
	public function mygit()
	{
		$userinfo=$this->users->get_user_by_id($this->user_id);
		$config['base_url'] = base_url('index.php/git/mygit/');
		$config['total_rows'] = $this->git->mygit_count($this->user_id);
		$config['per_page'] = PER_PAGE;
		$offset=intval($this->uri->segment(3));
		$rs=$this->git->show_mygit($this->user_id,$config['per_page'],$offset);
		$this->pagination->initialize($config);
		$page=$this->pagination->create_links();
		$mygit=array('all_gits'=>$rs->result(),"page"=>$page,'user'=>$userinfo->row_array());
		$this->load->view("git/git_mygit",$mygit);
	}
	/**
	 * 给用户展示git机器数。
	 * 返回一个html视图
	 */
	public function gitkey($git_id)
	{
		
		 $data['allkeys']=$this->key->getinfo_by_git_id($git_id);
		 if(!empty($data['allkeys']))
		 {
		  $this->load->view('git/gitkey',$data);
		 }
		 else
		 {
		 	echo 0;
		 }
	}
	/**
	 *  给用展示所属的git组信息,如果信息不为空就展示信息，信息为空就直接显示一个弹窗告诉用户。
	 */
	public  function showgroup($git_id)
	{
		$git_info=$this->git->get_one($git_id);
		$data['groups']=$this->group->getuser_group_by_str($git_info['add_datagroups']);
		if(!empty($data['groups']))
		{
			$this->load->view('git/showgroup',$data);
		}
	}
	/**
	 * 增加git组，增加的都是没有添加过的git组
	 */
	public function addgroup($git_id)
	{
		$data['git_id']=$git_id;
		$data['nogroups']=$this->group->get_str_nouser($this->user_id);
		$this->load->view('git/addgroup',$data);
	}
	/**
	 * 保存key,保存完毕之后发送邮件通知相关的审批人员进行审批
	 */
	public function savegroup()
	{
		// 判断身份认证，根据角色进行保存
		$data['newgroups_id']=$this->input->post('group_id');
		//print_r($data['newgroups_id']);exit;
		$data['git_id']=$this->input->post('git_id');
		$data['apply_type']=2;//增加git组审批
		$data['state']=0;//审批状态为未审批
		$data['btime']=time();//提交审批时间
		if($data['newgroups_id']=="")
		{
			echo "用户组信息不能为空！";
			exit;
		}
		else
		{
			$data['newgroups_id']=implode(',', $data['newgroups_id']);
		}
		//保存审批信息
		if($this->session->userdata('DX_pid')==0)//直接保存审批信息让op进行审批
		{
			$data['type_id']=1;//运维直接进行审批
			if($this->gol->save($data))
			{
				//发送邮件通知指定的运维人员
				$this->help_savekey_sendmail(ADRD_EMAIL_ONE,2);
			}
			else
			{
				echo "保存审批数据失败！";exit;
			}
		}
		else
		{//保存信息给主管和组的拥有者审批，如果说主管和组的拥有者为同一个人的话，只需要主管自己审批即可
			$data['type_id']=0;
			$levelinfo=$this->session->userdata('level_info');
			$data['user_id']=$levelinfo['id'];
			if($gle_id=$this->gol->save($data))
			{
				//保存主管审批之后，保存组的创建者进行审批
				//1,获取git组的信息，拼装数据进行保存
				 $groups=$this->group->getuser_group_by_str($data['newgroups_id']);
				 $creator_save=array();
				 $pid=$this->session->userdata('DX_pid');
				 $emails=array();
				 foreach ($groups as $group)
				 {
				 	//如果组的创建者为主管或者为用户自己本人
				 	if($group['gcre_creator']!=$pid || $group['gcre_creator']!=$this->user_id)
				 	{
				 		$tmp['gcre_creator']=$group['group_creator'];
				 		$tmp['group_id']=$group['group_id'];
				 		$tmp['change_id']=$this->user_id;
				 		$tmp['gcre_state']=0;
				 		$tmp['gle_id']=$gle_id;
				 		$tmp['git_id']=$data['git_id'];
				 		array_push($emails, $tmp['email']);
				 		$creator_save[]=$tmp;
				 	}
				 }
				 if($this->creator->save_batch($creator_save))
				 {
				 	array_push($emails,$levelinfo['email']);
				 	array_unique($emails);
				 	//$emails=implode(',', $emails);
				 	$this->help_savekey_sendmail($emails,2);
				 }
				 else
				 {
				 	echo "git组保存数据失败！";exit;
				 }
			}
			else
			{
				echo "写入数据失败！";exit;
			}
		}
		
	}
	/**
	 *  增加机器的form页面，其中form页面隐藏一个git值
	 */
	public function addkey($git_id)
	{
		$data['git_id']=$git_id;
		$this->load->view('git/addkey',$data);
	}
	/**
	 * 保存key ,发邮件通知审批
	 */
	public function savekey()
	{
		//保存数据，上传文件
		$data['git_id']=$this->input->post('git_id',TRUE);
		$gitpub=$this->_filter_line($this->input->post('gitpub'));
		$filename=$this->session->userdata('DX_username')."".time().".pub";
		if(FALSE!==file_put_contents('./uploads/pub'.$filename, $gitpub))
		{//保存成功,之后保存数据
			$data['filename']=$filename;
			$data['apply_type']=1;//新增机器的类型
			$this->help_savekey_apply($data);
		}
		else
		{
			echo '文件保存失败！';
			log_message('error','保存文件失败！');
		}
	}
	//审批保存数据辅助方法
	public function help_savekey_apply($data)
	{//判断主管，或者直接保存op
		if($this->session->userdata('DX_pid')==0)
		{//直接保存数据给op进行操作
			$data['type_id']=1;
			$data['state']=0;
			$data['btime']=time();
			if($this->gol->save($data))
			{
				$this->help_savekey_sendmail(ADRD_EMAIL_ONE,1);
			}
			else
			{
				echo "保存审批信息失败！";
			}
		}
		else
		{//给主管保存审批信息
			$levelifo=$this->session->userdata('level_info');
			$data['type_id']=0;
			$data['state']=0;
			$data['btime']=time();
			$data['user_id']=$this->session->userdata('DX_pid');
			if($this->gol->save($data))
			{
				$this->help_savekey_sendmail($levelifo['email'],1);
			}
			else
			{
				echo "保存审批信息失败！";
			}
		}
	}
	//审批数据保存之后发送邮件通知
	/**
	 * 给用户发送邮件通知
	 * @param int $to 接受邮件的用户
	 * @param int $type 1,为增加机器，2 为增加git组
	 */
	public function help_savekey_sendmail($to,$type)
	{
		$data['name']='领导';
		if($type==1)
		{
			$data['msg']="请尽快审批{$this->session->userdata('DX_realname')}git认证添加机器申请!";
			$subject='git认证机器添加审批';
		}
		else
		{
			$data['msg']="请尽快审批{$this->session->userdata('DX_realname')}git认证添加组申请!";
			$subject="git 认证 git组添加审批";
		}
		$message=$this->load->view('mail/mail_common',$data,true);
		echo 1;
		sendcloud($to,$subject, $message);
	}
	/**
	 * git 认证管理
	 */
	public function alllist()
	{
		//echo "haha";
		//print_r($this->git->alllist(5,0));
		//echo $this->git->count_alllist();
		 $git_state=$this->uri->segment(3)==""?1:$this->uri->segment(3);
		$config['base_url'] = base_url('index.php/git/alllist/'.$git_state.'/');
		$config['total_rows'] = $this->git->count_alllist($git_state);
		$config['per_page'] = PER_PAGE;
		$config['uri_segment'] =4;
		$offset=intval($this->uri->segment(4));
		$rs=$this->git->alllist($config['per_page'],$offset,$git_state);
		$this->pagination->initialize($config);
		$page=$this->pagination->create_links();
		$mygit=array('all_gits'=>$rs,"page"=>$page,'git_state'=>$git_state);
		$this->load->view("git/git_alllist",$mygit);
	}
	/**
	 * 禁用操作是在用户离职的时候对用户进行禁用，禁用之后就是删除了。不存在回复的情况。一般情况下是不会对用户进行禁用操作的
	 * 禁用一个用户的git认证
	 * @param int $git_id
	 */
	public function git_disable($git_id)
	{
		$data['git_state']=2;
		if($this->git->save($data,$git_id))
		{
			 echo json_encode(array('state'=>1,'msg'=>'禁用操作成功!'));
		}
		else
		{
			echo json_encode(array('state'=>0,'msg'=>'禁用 操作失败!'));
		}
	}
	public function git_delete($git_id)
	{
		$data['git_state']=-1;
		if($this->git->save($data,$git_id))
		{
			echo json_encode(array('state'=>1,'msg'=>'删除操作成功!'));
		}
		else
		{
			echo json_encode(array('state'=>0,'msg'=>'删除操作失败!'));
		}
	}
	/**
	 * 通过用户名对git认证进行搜索
	 */
	public function git_search()
	{
		$this->load->model('Users_model','user',TRUE);
		$username=$this->input->post('username',TRUE);//过滤用户输入
		$searchuserinfo=$this->user->searchuser($username);
		if(!empty($searchuserinfo))
		{
		$rs=$this->git->show_one_user_git($searchuserinfo['id'],100,0)->result();
		$tmp=array();
		foreach($rs as $r)
		{
			$r->realname=$searchuserinfo['realname'];
			$tmp[]=$r;
		}
		$mygit=array('all_gits'=>$tmp,'user'=>$username,'state'=>1);
		}
		else
		{
		  $mygit=array('user'=>$username,'all_gits'=>'','state'=>0);
		}
		$this->load->view("git/git_alllist",$mygit);
	}
	
}
	
?>