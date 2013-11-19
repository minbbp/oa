<?php
/**
 * 服务器分类的控制器，
 */
class Server_type extends CI_Controller
{
	private $user_id;//当前操作的用户的id
	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('form','url','message'));
		$this->load->library(array('dx_auth','session','pagination','form_validation'));
                $this->load->model('server_type_model','st',TRUE);
		$this->dx_auth->check_uri_permissions();//检查用户权限
		$this->user_id=$this->session->userdata('DX_user_id');
	}
        public function index(){
            $data['title'] = "服务器服务管理";
            $data['list'] = $this->st->get_typelist('&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp');
            $this->load->view('server/server_type',$data);
        }
        public function server_m($id){
            $data['arr'] = current($this->st->get_type_m($id));
            $arr = $data['arr'];
            $data['arrp'] = current($this->st->get_type_m($arr['st_pid']));
            $data['list'] = $this->st->get_typelist('&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp');
            $this->load->view('server/server_m',$data);
        }
        public function server_edit(){
              $data['st_id'] = $this->input->post('st_id');
              $data['st_name'] = trim($this->input->post('st_name'));
              $data['st_desc'] = trim($this->input->post('st_desc'));
              $data['st_yezi'] = $this->input->post('st_yezi');
              if($data['st_name'] != '' && $data['st_desc'] != '' ){
              $data['st_pid'] = $this->input->post('st_pid');
              $bool = $this->st->edit($data);
             if($bool){
                $message['status']=1;
                $message['msg']="编辑成功";
            }else{
                $message['status']=0;
                $message['msg']="编辑失败,请刷新后重试";
            }
              }else{
                $message['status']=0;
                $message['msg']="编辑失败,服务名或服务描述不能为空";
              }
             
            echo json_encode($message);
        }
        public function server_del($id){
            $row = $this->st->del_type($id);
            if($row == 1){
                $message['status']=1;
                $message['msg']="删除成功";
            }else{
                $message['status']=0;
                $message['msg']="删除失败,请刷新后重试";
            }
            echo json_encode($message);
        }
        public function server_m_add(){
            $data['list'] = $this->st->get_typelist('&nbsp&nbsp&nbsp&nbsp');
            $this->load->view('server/server_m_add',$data);
        }
        public function server_add() {
              $data['st_name'] = trim($this->input->post('st_name'));
              $data['st_desc'] = trim($this->input->post('st_desc'));
              $data['st_yezi'] = $this->input->post('st_yezi');
              if($data['st_name'] != '' && $data['st_desc'] != ''){
                    $data['st_pid'] = $this->input->post('st_pid');
                    $row = $this->st->add_type($data);
                    if($row == 1){
                    $message['status']=1;
                    $message['msg']="添加成功";
                    }else{
                    $message['status']=0;
                    $message['msg']="添加失败,请刷新后重试";
                    }
              }else{
                    $message['status']=0;
                    $message['msg']="添加失败,服务名或服务描述不能为空";
              }
            echo json_encode($message);
        }
        
      
}	
?>