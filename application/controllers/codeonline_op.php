<?php
/**
 * 测试人员确认控制器
 * @author minbbp
 * @version 1.0
 */
class Codeonline_op extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Codeonline_apply_model','cam',TRUE);
		$this->load->model('Codeonline_apply_table_model','cat',TRUE);
		$this->load->model('Users_model','user',TRUE);
		$this->load->model('Codeonline_model','cm',TRUE);
	}
	/**
	 * 我的测试确认信息,默认是未审批的数据
	 */
	public function myapply()
	{
		$type_id=2;//暂时测试数据，需要真实数据位1
		$config['total_rows']=$this->cam->count_alllist($this->user_id,$type_id);
		$offset=intval($this->uri->segment(3));
		$config['base_url'] = base_url('index.php/codeonline_op/myapply/');
		$rs=$this->cam->alllist($offset,PER_PAGE,$this->user_id,$type_id);
		$this->pagination->initialize($config);
		$page=$this->pagination->create_links();
		$this->load->view('codeonline_apply/op_myapply',array('apply_rs'=>$rs,'page'=>$page,'title'=>'上线处理'));
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
			
			$ap_table=$this->cat->get_one($old_rs['apply_id']);
			$ap_table['end_state']=1;
			$ap_table['end_time']=time();
			//修改上线模块的git标签
			$cm_data['m_online']=$ap_table['git_tag'];
			$this->cm->save($cm_data,$ap_table['m_id']);//修改上线后的模块标签
			unset($ap_table['apply_id']);
			if($this->cat->save($ap_table,$old_rs['apply_id']))
			{
				echo json_encode(array('status'=>1,'msg'=>'上线成功！'));
				// 发送邮件通知申请者，发送邮件告知全院。
				$this->pass_mail($a_id);
			
			}
			else
			{
				echo json_encode(array('status'=>0,'msg'=>'申请单修改失败！'));
				log_message('error','上线测试审批失败a_id='.$a_id);
			}
		}
		else
		{
			echo json_encode(array('status'=>0,'msg'=>'审批失败！'));
			log_message('error','上线测试审批失败a_id='.$a_id);
		}
	}
	/**
	 * 发送信息告知全院
	 */
	private function pass_mail($a_id)
	{
		$apply_rs=$this->cam->get_one($a_id);
		$data=$this->cat->showinfo_by_id($apply_rs['apply_id']);
		$msg=$this->load->view('mail/mail_codeonline_common',$data,TRUE);
		$msg_email=$this->load->view('mail/mail_new_common',array('name'=>'everyone','msg'=>$msg),TRUE);
		$subject="运维代码上线通知";
		sendcloud('adrd@sohu-inc.com', $subject, $msg_email,array('aguan@sohu-inc.com','zhouzhou200833@sohu-inc.com'));
	}
	/**
	 * 显示驳回视图
	 */
	public function reject($a_id)
	{
		$this->load->view('codeonline_apply/op_reject',array('a_id'=>$a_id));
	}
	/**
	 * 驳回用户申请信息
	 * @param number $a_id
	 */
	public  function rejectsave($a_id)
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
		$msg=$this->session->userdata('DX_realname')."驳回了你的上线申请！驳回原因:<font color='red'>".$apply_rs['description']."</font>";
		$message=$this->load->view('mail/mail_new_common',array('name'=>$to_users['realname'],'msg'=>$msg),TRUE);
		$subject="运维驳回上线申请通知";
		sendcloud($to_users['email'], $subject, $message);
	}
	/**
	 * 在未处理之前驳回用户的申请信息
	 * @param unknown $a_id
	 */
	public function back($a_id)//退回申请
	{
		//直接退回用户的申请，然后发送邮件告知用户。
		$data['a_optime']=time();
		$data['a_status']=-1;
		$data['description']=$this->input->post('description');
		if($this->cam->save($data,$a_id))
		{
			//修改申请单的状态。发送邮件通知申请者，并告知驳回原因
			$old_rs=$this->cam->get_one($a_id);
			$ap_table=$this->cat->get_one($old_rs['apply_id']);
			$ap_table['myapply_status']=2;
			unset($ap_table['apply_id']);
			if($this->cat->save($ap_table,$old_rs['apply_id']))
			{
				echo json_encode(array('status'=>1,'msg'=>'退回成功！'));
				// 发送退回邮件
				$this->_back_mail($a_id);
		
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
	//发送驳回邮件
	private function _back_mail($a_id)
	{
		$cat_rs=$this->cat->get_one($a_id);
		$apply_rs=$this->cam->get_one($cat_rs['apply_id']);
		$to_users=$this->user->get_useremail_by_id($cat_rs['apply_user']);
		$msg=$this->session->userdata('DX_realname')."退回了你的上线申请！ 你可以对你的申请单进行修改！";
		$message=$this->load->view('mail/mail_new_common',array('name'=>$to_users['realname'],'msg'=>$msg),TRUE);
		$subject="运维退回上线申请单通知";
		sendcloud($to_users['email'], $subject, $message);
	}
}