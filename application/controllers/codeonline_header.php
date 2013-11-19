<?php
/**
 * 测试人员确认控制器
 * @author minbbp
 * @version 1.0
 */
class Codeonline_header extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Codeonline_apply_model','cam',TRUE);
		$this->load->model('Codeonline_apply_table_model','cat',TRUE);
		$this->load->model('Users_model','user',TRUE);
	}
	/**
	 * 我的测试确认信息,默认是未审批的数据
	 */
	public function myapply()
	{
		$type_id=1;//暂时测试数据，需要真实数据位1
		$config['total_rows']=$this->cam->count_alllist($this->user_id,$type_id);
		$offset=intval($this->uri->segment(3));
		$config['base_url'] = base_url('index.php/codeonline_tester/myapply/');
		$rs=$this->cam->alllist($offset,PER_PAGE,$this->user_id,$type_id);
		$this->pagination->initialize($config);
		$page=$this->pagination->create_links();
		$this->load->view('codeonline_apply/head_myapply',array('apply_rs'=>$rs,'page'=>$page,'title'=>'测试确认工单'));
	}
	/**
	 * 
	 * @param number $a_id 审批时的信息
	 */
	public function pass($a_id)
	{
		$old_rs=$this->cam->get_one($a_id);//保存旧的值，需要插入新的审批数据
		$data['a_status']=1;
		$data['a_optime']=time();//处理时间
		if($this->cam->save($data,$a_id))
		{
			//判断申请人和项目负责人是否是同一个，如果为同一个的话，则直接要运维进行处理，否则的话，需要项目负责人进行审批
			$old_rs['prve_id']=$a_id;
			unset($old_rs['a_id']);
			$users=$this->cat->get_head_by_apply_id($old_rs['apply_id']);
			$old_rs['type_id']=2;
			$old_rs['user_id']=$users['op_id'];
			if($this->cam->save($old_rs))
			{
				echo json_encode(array('status'=>1,'msg'=>'审批通过！'));
				$this->pass_mail($a_id);
				
			}
			else
			{
				echo json_encode(array('status'=>0,'msg'=>'推送审批信息失败！'));
				log_message('error','推送审批信息失败！');
			}
		}
		else
		{
			echo json_encode(array('status'=>0,'msg'=>'审批失败！'));
			log_message('error','上线测试审批失败a_id='.$a_id);
		}
	}
	private function pass_mail($a_id)
	{
		$apply_rs=$this->cam->get_one($a_id);
		$apply_next_users=$this->user->get_useremail_by_id($apply_rs['user_id']);
		$apply_table_rs=$this->cat->get_one($apply_rs['apply_id']);
		$app_user=$this->user->get_useremail_by_id($apply_table_rs['apply_user']);
		$message=$this->load->view('mail/mail_new_common',array('name'=>$apply_next_users['realname'],'msg'=>'请对"'.$app_user['realname'].'"的上线申请进行审批!'),TRUE);
		sendcloud($apply_next_users['email'], '通知邮件', $message,$app_user['email']);
	}
	/**
	 * 驳回用户输入申请
	 */
	public function reject($a_id)
	{
		$this->load->view('codeonline_apply/head_reject',array('a_id'=>$a_id));
	}
	/**
	 * 驳回用户申请信息
	 * @param number $a_id
	 */
	public  function savereject($a_id)
	{
		$data['a_optime']=time();
		$data['a_status']=-1;
		$data['description']=$this->input->post('description');
		if($this->cam->save($data,$a_id))
		{
			//修改申请单的状态。发送邮件通知申请者，并告知驳回原因
			$old_rs=$this->cam->get_one($a_id);
			$ap_table=$this->cat->get_one($old_rs['apply_id']);
			$ap_table['end_state']=-1;
			$ap_table['end_time']=time();
			unset($ap_table['apply_id']);
			if($this->cat->save($ap_table,$old_rs['apply_id']))
			{
				echo json_encode(array('status'=>1,'msg'=>'驳回成功！'));
				// 发送邮件通知申请者，并告知驳回原因
				$this->reject_mail($a_id);
				
			}
			else
			{
				echo json_encode(array('status'=>0,'msg'=>'申请单修改失败！'));
				log_message('error','上线测试审批失败a_id='.$a_id);
			}
		}
		else
		{
			echo json_encode(array('status'=>0,'msg'=>'驳回失败！'));
			log_message('error','上线测试审批失败a_id='.$a_id);
		}
	}
	private function reject_mail($a_id)
	{
		$cat_rs=$this->cat->get_one($a_id);
		$apply_rs=$this->cam->get_one($cat_rs['apply_id']);
		$to_users=$this->user->get_useremail_by_id($cat_rs['apply_user']);
		$msg=$this->session->userdata('DX_realname')."驳回了你的上线申请&#33;驳回原因:<font color='red'>".$apply_rs['description']."</font>";
		$message=$this->load->view('mail/mail_new_common',array('name'=>$to_users['realname'],'msg'=>$msg),TRUE);
		$subject="驳回上线申请通知";
		//echo $message;exit;
		sendcloud($to_users['email'], $subject, $message);
	}
}