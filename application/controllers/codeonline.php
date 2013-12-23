<?php
/**
 * 代码上线，对代码上线进行处理，主要包含模块列表显示，以及针对特定的模块进行上线申请。
 * @author minbbp
 * @version 1.0 2013.11.6
 */
class Codeonline extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Codeonline_model','cm',TRUE);
		$this->load->model('Server_manage_model','ms',TRUE);//载入服务器模型
		$this->load->model('M_test_model','mt',TRUE);//载入服务器模型
		$this->load->model('Codeonline_files_model','cf',TRUE);//配置文件保存信息
		$this->load->model('Codeonline_apply_table_model','cat',TRUE);//保存申请信息
		$this->load->model('Codeonline_apply_model','ca',TRUE);
		$this->load->model('Users_model','user',TRUE);
		$this->load->model('Codeonline_require_model','crm',TRUE);
		$this->load->model('M_relymodel_model','mrm',TRUE);
	}
	/**
	 * 显示所有的父级模块，以及对应父级下的自己模块
	 * @param number $pid
	 * @return 返回一个显示模块的视图
	 */
	public  function index()
	{
		$config['total_rows']=$this->cm->total_alllist();
		$offset=intval($this->uri->segment(3));
		$re_rs=$this->cm->alllist($offset,PER_PAGE);
		$this->pagination->initialize($config);
		$page=$this->pagination->create_links();
		$this->load->view('codeonline/index',array('re_rs'=>$re_rs,'page'=>$page,'title'=>'上线模块'));
	}
	/**
	 * 子类层级关系,暂时没有使用到
	 */
	public function child($pid)
	{
		$config['total_rows']=$this->cm->total_alllist($pid);
		$config['per_page']=PER_PAGE;
		$config['uri_segment'] =4;
		$offset=intval($this->uri->segment(4));
		$config['base_url']=base_url('index.php/codeonline/child/'.$pid.'/');
		$re_rs=$this->cm->alllist($offset,PER_PAGE,$pid);
		$this->pagination->initialize($config);
		$page=$this->pagination->create_links();
		$this->load->view('codeonline/child',array('re_rs'=>$re_rs,'page'=>$page,'title'=>'子模块'));
	}
	/**
	 *  上线申请表单，需要查询出测试人员和对应的服务器列表
	 *  @param  number $m_id 对应模块的主键
	 */
	public function apply($m_id,$is_ungent=0)
	{
		if($is_ungent==1)
		{
			$data['title']="紧急上线申请";
		}
		else
		{
			$data['title']="上线申请";
		}
		
		$data['server_rs']=$this->ms->get_all_list($m_id);
		$data['tester_rs']=$this->mt->get_tester_by_m_id($m_id);
		$data['m_id']=$m_id;
		$data['relymodels']=$this->mrm->get_relymodel($m_id);
		$testjson=$this->crm->get_all();
		//echo json_encode($testjson);
		$data['testjson']=json_encode($testjson);
		$data['is_ungent']=$is_ungent;
		$this->load->view('codeonline/apply',$data);
	}
	/**
	 * 更新已经编辑好的上线申请，（1，用户保存了，但是未提交。2，用户提交了被退回）。修改通过myapply_status 进行控制。
	 * 当用户编辑的时候，驳回的用户需要重新修改，后再次提交审批。（删除旧的审批数据，创建新的审批数据）
	 * @param unknown $apply_id
	 * @param unknown $tester_id
	 * @param unknown $m_id
	 */
	public function update($apply_id,$tester_id,$m_id)
	{
		$data['title']="代码上线申请修改";
		$data['server_rs']=$this->ms->get_all_list($m_id);
		$data['tester_rs']=$this->mt->get_tester_by_m_id($m_id);
		$data['m_id']=$m_id;
		$data['relymodels']=$this->mrm->get_relymodel($m_id);
		$data['apply_row']=$this->cat->get_one($apply_id);
		//获取需求信息，获取文件信息
		$data['config_rs']=$this->cf->get_rs('apply_id',$apply_id);
		$data['require_row']=$this->crm->get_one($data['apply_row']['require_id']);
		//print_r($data['require_row']);
		$testjson=$this->crm->get_all();
		//echo json_encode($testjson);
		$data['testjson']=json_encode($testjson);
		$this->load->view('codeonline/update',$data);
	}
	/**
	 * 保存用户提交的上线申请
	 * @param number $m_id
	 */
	public function update_save($apply_id,$m_id,$status)
	{
		$codeonline_data['require_id']=$this->input->post('require_id');
		$rs=$this->crm->get_by_title($codeonline_data['require_id']);
		if($rs)
		{
			$codeonline_data['require_id']=$rs['required_id'];
		}
		else
		{
			echo json_encode(array('status'=>0,'msg'=>'需求编号选取错误！'));
			exit;
		}
		$codeonline_data['m_id']=$m_id;
		$codeonline_data['apply_user']=$this->user_id;
		$codeonline_data['git_url']=$this->input->post('git_url');
		$codeonline_data['git_tag']=$this->input->post('git_tag');
		$codeonline_data['server_update']=@implode(',',$this->input->post('server_update'));//这里需要额外处理
		$codeonline_data['tester_id']=$this->input->post('tester_id');
		$codeonline_data['online_time']=$this->input->post('online_time');
		$codeonline_data['online_description']=$this->input->post('online_description');
		$codeonline_data['end_state']=0;//未关闭
		$codeonline_data['apply_addtime']=time();
		$codeonline_data['myapply_status']=$status;//0,为保存数据不提交上线申请。1为提交上线申请
		$this->cat->save($codeonline_data,$apply_id);
		//删除以前的文件信息，保存新的文件信息,生成批量保存文件的信息
		$this->cf->delete($apply_id);
		//保存更新的配置文件的值，保存成功后才能提交更新配置，文件名不能为空的话
			$file_name=$this->input->post('file_name');
			$tmp_file_name=implode(',', $file_name);
			if(!empty($tmp_file_name))
			{
				$file_item=$this->input->post('file_item');
				$file_item_old_value=$this->input->post('file_item_old_value');
				$file_item_new_value=$this->input->post('file_item_new_value');
				$filedata=array();
				$length=count($file_name);
				for($i=0;$i<$length;$i++)
				{
					$tmp['file_name']=$file_name[$i];
					$tmp['file_item']=$file_item[$i];
					$tmp['file_item_old_value']=$file_item_old_value[$i];
					$tmp['file_item_new_value']=$file_item_new_value[$i];
					$tmp['apply_id']=$apply_id;
					$filedata[]=$tmp;
				}
				if(FALSE==$this->cf->save_batch($filedata))
				{
					echo json_encode(array('status'=>0,'msg'=>'保存数据失败！'));
					log_message('error','保存修改配置文件信息失败！');
				}
			}
			if($status==1)
			{
					if($this->codeonline_apply($apply_id,$codeonline_data['tester_id'],$m_id))
					{
								echo json_encode(array('status'=>1,'msg'=>'保存数据成功！'));
					}
					else
					{
							echo json_encode(array('status'=>0,'msg'=>'保存审批数据失败！'));
					}
			}
			else
			{
				echo json_encode(array('status'=>1,'msg'=>'保存数据成功！'));
			}
}
	/**
	 * 保存用户提交的上线申请
	 * @param number $m_id
	 */
	public function save($m_id,$status)
	{
		 $codeonline_data['require_id']=$this->input->post('require_id');
		 $rs=$this->crm->get_by_title($codeonline_data['require_id']);
		 if($rs)
		 {
		 	$codeonline_data['require_id']=$rs['required_id'];
		 }
		 else
		 {
		 	echo json_encode(array('status'=>0,'msg'=>'需求编号选取错误！'));
		 	exit;
		 }
		$codeonline_data['apply_no']="C_".date("YmdHis");
		$codeonline_data['m_id']=$m_id;
		$codeonline_data['apply_user']=$this->user_id;
		$codeonline_data['git_url']=$this->input->post('git_url');
		$codeonline_data['git_tag']=$this->input->post('git_tag');
		$codeonline_data['server_update']=@implode(',',$this->input->post('server_update'));//这里需要额外处理
		$codeonline_data['tester_id']=$this->input->post('tester_id');
		$codeonline_data['online_time']=$this->input->post('online_time');
		$codeonline_data['online_description']=$this->input->post('online_description');
		$codeonline_data['end_state']=0;//未关闭
		$codeonline_data['apply_addtime']=time();
		$codeonline_data['is_ungent']=$this->input->post('is_ungent');
		$codeonline_data['myapply_status']=$status;//0,为保存数据不提交上线申请。1为提交上线申请
		$apply_id=$this->cat->save($codeonline_data);
		//获取apply_id
		//保存文件信息,生成批量保存文件的信息
		if($apply_id)
		{
			$file_name=$this->input->post('file_name');
			$tmp_file_name=implode(',', $file_name);
			//log_message('error',$tmp_file_name);
			if(!empty($tmp_file_name))
			{
				$file_item=$this->input->post('file_item');
				$file_item_old_value=$this->input->post('file_item_old_value');
				$file_item_new_value=$this->input->post('file_item_new_value');
				$filedata=array();
				$length=count($file_name);
				for($i=0;$i<$length;$i++)
				{
					$tmp['file_name']=$file_name[$i];
					$tmp['file_item']=$file_item[$i];
					$tmp['file_item_old_value']=$file_item_old_value[$i];
					$tmp['file_item_new_value']=$file_item_new_value[$i];
					$tmp['apply_id']=$apply_id;
					$filedata[]=$tmp;
				}
				if(FALSE==$this->cf->save_batch($filedata))
				{
					echo json_encode(array('status'=>0,'msg'=>'保存配置文件数据失败！'));
					log_message('error','保存修改配置文件信息失败！');
				}
			}
			if($status==1)
			{
				if($this->codeonline_apply($apply_id,$codeonline_data['tester_id'],$m_id))
				{
					echo json_encode(array('status'=>1,'msg'=>'保存数据成功！'));
				}
				else
				{
					echo json_encode(array('status'=>0,'msg'=>'保存审批数据失败！'));
				}
			}
			else
			{
				echo json_encode(array('status'=>1,'msg'=>'保存数据成功！'));
			}
		}
		else
		{
			echo json_encode(array('status'=>0,'msg'=>'保存数据失败！'));
			log_message('error','保存文件信息失败！');
		}
	}
	/**
	 * 提交修改过的审批，或者提交没有修改过的审批
	 * @param number $apply_id
	 * @param number $tester_id
	 * @param number $m_id
	 */
	public function commit_apply($apply_id,$tester_id,$m_id)
	{
		$codeonline_data['myapply_status']=1;//提交审批
		if($this->cat->save($codeonline_data,$apply_id))
		{
			if($this->codeonline_apply($apply_id,$tester_id,$m_id))
			{
				echo json_encode(array('status'=>1,'msg'=>'已经提交工单了！'));
			}
			else
			{
				echo json_encode(array('status'=>0,'msg'=>'提交工单信息失败！'));
			}
		}
		else
		{
			echo json_encode(array('status'=>0,'msg'=>'提交信息失败！'));
		}
	}
	/**
	 * 推送用户审批信息,并发送邮件进行通知。
	 * 推送审批信息规则： 不论线上还是线下均发送邮件通知申请人。
	 * 如果说申请者和测试确认者为同一人，则发送邮件通知的时候发送一人即可
	 * @param number $apply_id
	 * @param number $tester_id
	 * @param number  $m_id 模块的主键信息
	 * @return bool if success return true else reutrn false;
	 * get_useremail_by_id()获取用户邮件信息
	 */
	public function codeonline_apply($apply_id,$tester_id,$m_id)
	{
		$view_data['c_model']=$this->cm->get_one($m_id);
		$view_data['change_file']=$this->cf->get_rs('apply_id',$apply_id);
		$view_data['apply_rs']=$this->cat->get_one($apply_id);
		$view_data['server_rs']=$this->ms->get_server_by_str($view_data['apply_rs']['server_update']);
		$view_data['require_row']=$this->crm->get_one($view_data['apply_rs']['require_id']);
			/**
			 * 如果不为同一个人发送两封邮件，发送测试者抄送申请者。否者只发送给申请者‘让申请者自己确认’。                                                                                                                 
			 */
			if($tester_id==$this->user_id)
			{
				if($this->user_id==$view_data['c_model']['m_head'])
				{
					$is_ungent=$view_data['apply_rs']['is_ungent'];
					if($is_ungent==1)//属于紧急上线，不能直接推送消息给运维需要推送消息给指定的主管
					{
						$view_data['to_users']=$this->user->get_useremail_by_id(UN_UNGENT_LEVEL);
						$view_data['to_adduser']=$this->user->get_useremail_by_id($this->user_id);
						$data['apply_id']=$apply_id;
						$data['type_id']=3;
						$data['user_id']=UN_UNGENT_LEVEL;
						$data['a_status']=0;
					}
					else
					{
						$view_data['to_users']=$this->user->get_useremail_by_id($view_data['c_model']['op_id']);
						$view_data['to_adduser']=$this->user->get_useremail_by_id($this->user_id);
						//推送信息给运维人员,同时抄送信息给申请者
						$data['apply_id']=$apply_id;
						$data['type_id']=2;
						$data['user_id']=$view_data['c_model']['op_id'];
						$data['a_status']=0;
					}
					
					if($this->ca->save($data))
					{
						//发送邮件
						$edata['name']=$view_data['to_users']['realname'];
						$edata['msg']=$this->load->view('mail/mail_codeonline.php',$view_data,TRUE);
						$msg=$this->load->view('mail/mail_common',$edata,TRUE);
						sendcloud($view_data['to_users']['email'], '[通知]上线申请审批',$msg,$view_data['to_adduser']['email']);
						return TRUE;
					}
					else
					{
						// 发送失败，保存数据失败
						log_message('error','推送审批数据失败');
						 return FALSE;
					}
				}
				else
				{
					//推送信息给项目负责人
					$view_data['to_users']=$this->user->get_useremail_by_id($view_data['c_model']['m_head']);
					$view_data['to_adduser']=$this->user->get_useremail_by_id($this->user_id);
					$data['apply_id']=$apply_id;
					$data['type_id']=1;
					$data['user_id']=$view_data['c_model']['m_head'];
					$data['a_status']=0;
					if($this->ca->save($data))
					{
						//发送邮件
						$edata['name']=$view_data['to_users']['realname'];
						$edata['msg']=$this->load->view('mail/mail_codeonline.php',$view_data,TRUE);
						$msg=$this->load->view('mail/mail_common',$edata,TRUE);
						sendcloud($view_data['to_users']['email'], '[通知]上线申请审批',$msg,$view_data['to_adduser']['email']);
						return TRUE;
					}
					else
					{
						// 发送失败，保存数据失败
						log_message('error','推送审批数据失败!');
						return FALSE;
					}
				}
			}
			else
			{
				//推送信息给项目测试人员进行
				$view_data['to_users']=$this->user->get_useremail_by_id($tester_id);
				$view_data['to_adduser']=$this->user->get_useremail_by_id($this->user_id);
				$data['apply_id']=$apply_id;
				$data['type_id']=0;
				$data['user_id']=$tester_id;
				$data['a_status']=0;
				if($this->ca->save($data))
				{
					//发送邮件
					$edata['name']=$view_data['to_users']['realname'];
					$edata['msg']=$this->load->view('mail/mail_codeonline.php',$view_data,TRUE);
					$msg=$this->load->view('mail/mail_common',$edata,TRUE);
					sendcloud($view_data['to_users']['email'], '[通知]上线申请测试确认审批',$msg,$view_data['to_adduser']['email']);
					return TRUE;
				}
				else
				{
					// 发送失败，保存数据失败
					
					log_message('error','推送审批数据失败!');
					return FALSE;
				}
			}
	
	}
	/**
	 * 检查需求名称是否存在
	 */
	 public  function check_require_name()
	{
		 $name=$this->input->post('name');
		
			if($this->crm->get_by_title($name))
			{
				echo 1;
			}
			else
			{
				echo 0;
			}
	} 
	/**
	 * 查看我的git认证申请
	 */
	public function myapply()
	{
		//echo " 我codeonline申请";
		//echo "<pre>";
		//print_r($this->cat->alllist(0,100,$this->user_id));
		$config['base_url'] = base_url('index.php/codeonline/myapply/');
		$config['total_rows'] = $this->cat->count_alllist($this->user_id);
		$offset=intval($this->uri->segment(3));
		$rs=$this->cat->alllist($offset,PER_PAGE,$this->user_id);
		$this->pagination->initialize($config);
		$page=$this->pagination->create_links();
		$myapply=array('codeonlines'=>$rs,"page"=>$page,'title'=>'我的上线申请');
		$this->load->view("codeonline/myapply",$myapply);
	}
	/**
	 * 通过申请单号的id查看申请单的信息，包括对应需求，对应服务器，对应修改配置文件的描述
	 */
	public function show($apply_id)
	{
		
		$data=$this->cat->showinfo_by_id($apply_id);
		$data['title']='代码上线工单';
		//echo '<pre>';
		//print_r($data);
		//echo '</pre>';
		$this->load->view('codeonline/showinfo',$data);
		//$this->load->view('mail/mail_new_common',array('name'=>'test','msg'=>$msg));
	}
	//一下为测试方法
	public function testview($apply_id,$tester_id,$m_id)
	{
		$view_data['c_model']=$this->cm->get_one($m_id);
		$view_data['change_file']=$this->cf->get_rs('apply_id',$apply_id);
		$view_data['apply_rs']=$this->cat->get_one($apply_id);
		$view_data['server_rs']=$this->ms->get_server_by_str($view_data['apply_rs']['server_update']);
		$view_data['require_row']=$this->crm->get_one($view_data['apply_rs']['require_id']);
		$view_data['to_users']=$this->user->get_useremail_by_id($view_data['c_model']['op_id']);
		$view_data['to_adduser']=$this->user->get_useremail_by_id($this->user_id);
		$msg=$this->load->view('mail/mail_codeonline.php',$view_data,TRUE);
		sendcloud('wb-zhibinliu@sohu-inc.com', '上线审批【通知】', $msg);
		echo $msg;
	}
	/**
	 * 选择需求列表
	 *
	 */
	public function alist()
	{
		//获取选取的月份;
		$months = isset($_POST['months']) ? $_POST['months'] : $this->input->get('months');
		$num =PER_PAGE;
		$offset=intval($this->uri->segment(3));
		$keywords = $this->input->post('keyword');
		if(empty($keywords)&&empty($months))
		{
			$count = $this->crm->count_alllist();
			$res=$this->crm->alllist($offset,$num);
		}
		else
		{	//如果关键字和月份都不为空，月份和关键字一起匹配，如果月份不为空，查询月份，否则关键字查询
			if($months == 6)
			{
				$mon = time()-(60*60*24*180);
			}
			elseif($months == 3)
			{
				$mon = time()-(60*60*24*90);
			}
			else
			{
				$mon = time()-(60*60*24*30);
			}
			if (!empty($keywords)&&!empty($months))
			{
				$res = $this->crm->get_by_keywords($offset,$num,$keywords,$mon);
			}
			elseif($months)
			{
				$res = $this->crm->get_by_keywords($offset,$num,'',$mon);
			}
			else
			{
				$res = $this->crm->get_by_keywords($offset,$num,$keywords);
			}
				
			 $count = count($res);
				
		}
		$config['base_url'] =site_url('codeonline/alist');
		$config['total_rows'] = $count;
		$config['per_page']=$num;
		$config['first_link'] = '首页';
		$config['next_link'] = '下一页';
		$this->pagination->initialize($config);
		$page=$this->pagination->create_links();
		$this->load->view('codeonline/alist',array('re_rs'=>$res,'page'=>$page,'title'=>'需求管理','months'=>$months,'keywords'=>$keywords));
	}
}