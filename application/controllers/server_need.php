<?php
/**
 * 服务器申请相关的控制器，
 * 
 */
class Server_need extends CI_Controller
{
    
	//private $user_id;//当前操作的用户的id
	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('form','url','message'));
		$this->load->library(array('dx_auth','session','pagination','form_validation'));
                $this->load->model('server_need_model','sn',TRUE);
                $this->load->model('server_approve_model','sa',TRUE);
                $this->load->model('server_type_model','st',TRUE);
		$this->dx_auth->check_uri_permissions();//检查用户权限
		$this->user_id=$this->session->userdata('DX_user_id');
	}
        
        public function index(){
            $data['title']="服务器申请";
            $data['list'] = $this->st->get_all('&nbsp&nbsp&nbsp&nbsp');
            $data['use_list'] = $this->sn->get_use();
            $this->load->view("server/server_apply",$data);
        }
        
        public function add_apply() {
            //验证开始 设置规则
           $this->form_validation->set_rules('cpu', 'cpu', 'trim|is_natural|required');
           $this->form_validation->set_rules('mem', 'mem', 'trim|is_natural|required');
           $this->form_validation->set_rules('disk', 'disk', 'trim|is_natural|required');
           $this->form_validation->set_rules('desc', 'desc', 'trim|required');
           $this->form_validation->set_message('required', '您必须输入信息');
           $this->form_validation->set_message('is_natural', '输入的不是一个整数');
            //开始验证
            if ($this->form_validation->run() == FALSE)
              {
                $this->apply();
              }else{
                    $data['sn_cpu'] = $this->input->post('cpu');
                    $data['sn_mem'] = $this->input->post('mem');
                    $data['sn_disk'] = $this->input->post('disk');
                    $data['sn_internet'] = $this->input->post('internet');
                    if($data['sn_internet'] == 1){
                    $data['sn_isp'] = $this->input->post('isp');
                    }
                    if($this->input->post('type')){
                    $data['sn_type'] = implode(',',$this->input->post('type'));
                    }
                    $data['sn_desc'] = $this->input->post('desc');
                    $data['sn_use'] = $this->input->post('use');
                    $data['sn_num'] = $this->input->post('num');
                    $data['sn_time'] = time();
                    $data['u_id'] = $this->user_id;
                    $sn_id = $this->sn->add_apply($data);
                    if(is_int($sn_id)){
                        //需求表插入成功->审批表插入
                        $pid = $this->session->userdata('DX_pid');
                        $level_info = $this->session->userdata['level_info'];
                        $c = $this->session->userdata('DX_email');
                        $sa_id = $this->sa->add_approve($sn_id,$pid,$level_info,$c);
                            if(is_int($sa_id)){
                                 $message['status'] =  1;
                                 $message['msg'] = "提交成功";
                             }else {
                                  $message['status'] =  0;
                                  $message['msg'] = "提交失败,请联系管理员";
                                  log_message('error','审批表插入失败');
                             }
                             
                    }else{
                        $message['status'] =  0;
                        $message['msg'] = "提交失败,请联系管理员";
                        log_message('error','需求表插入失败');
                    }
                    echo json_encode($message);
              }

        }

        
	
}
	
?>