<?php
/**
 * 服务器审批的控制器，
 */
class Server_approve extends CI_Controller
{
	private $user_id;//当前操作的用户的id
	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('form','url','message'));
		$this->load->library(array('dx_auth','session','pagination','form_validation'));
                $this->load->model('server_need_model','sn',TRUE);
                $this->load->model('server_approve_model','sa',TRUE);
                $this->load->model('server_manage_model','s',TRUE);
                $this->load->model('server_owner_model','so',TRUE);
		$this->dx_auth->check_uri_permissions();//检查用户权限
		$this->user_id=$this->session->userdata('DX_user_id');
                $this->role_id=$this->session->userdata("DX_role_id");//2为超级管理员 5是运维 6是主管
                
	}
        public function index(){
            $data['title'] = "服务器审批";
            $arr['uid'] = $this->user_id;
            $arr['rid'] = $this->role_id;
            $count = $this->sa->get_count($arr);
            $page_size=10;//每页数量
            //分页
            $configpage['base_url'] =site_url('Server_approve/index');
            $configpage['total_rows'] = $count;//一共有多少条数据
            $configpage['per_page'] = $page_size; //每页显示条数
            $configpage['first_link'] = '首页';
            $configpage['next_link'] = '下一页';
            $configpage['uri_segment']=3;//分页的数据查询偏移量在哪一段上
            $offset=intval($this->uri->segment(3));
            $data['temp'] = $this->sa->select_approve($arr,$offset,$page_size);
            $data['temp2'] = $this->sn->select_need($data['temp']);
            $data['list'] = $this->so->select_owners($data['temp2']);
            $this->pagination->initialize($configpage);
            $data['link'] = $this->pagination->create_links();
            $data['role_id'] = $this->role_id;
            $this->load->view('server/server_approve',$data);
        }
         public function server_agree_op($sa_id) {
            //先去检查是否合法
            $uid = $this->user_id;
            $role_id = $this->role_id;
            if($role_id == 5){
                $now = $this->sa->agree_op($sa_id); 
                $this->op_agree_email($sa_id);
                if($now){
                    $message['status'] = 1;
                    $message['msg'] = "操作成功";
                }else{
                    $message['status'] = 0;
                    $message['msg'] = "操作失败,请联系管理员";
                }
            }else{
                $message['status'] = 0;
                $message['msg'] = "您不是运维人员";
            }
            echo json_encode($message);
        }
        public function server_agree($sa_id) {
            //先去检查是否合法
            $uid = $this->user_id;
            $role_id = $this->role_id;
            if($role_id != 5){
            $bool = $this->sa->check($uid,$sa_id,$role_id);
            if ($bool == 1) {
                if($role_id == 6){
                    //此为主管
                $message = $this->sa->agree($sa_id);
                }else{
                    //此为运维
                $message = $this->sa->agree_op($sa_id);    
                }
            }else{
                $message['status'] = 0;
                $message['msg'] = "非法请求";
            }
            }else{
                $message['status'] = 2;
                $message['msg'] = site_url('server_approve/op_approve')."/".$sa_id;
            }
            echo json_encode($message);
        }
        public function op_approve($sa_id,$type=false,$keyword=false) {
            $data['title'] = "分配服务器";
            $keyword = urldecode($keyword);
                     if($type == 'owner'){
                    $uid = $this->sn->get_id_by_username($keyword);
                    $arr2 = $this->sn->get_id_by_uid($uid);
                    $wherein = $this->so->get_id_by_snid($arr2);
                    $configpage['uri_segment']=6;//分页的数据查询偏移量在哪一段上
                    $offset=intval($this->uri->segment(6));
                    $configpage['base_url'] =site_url('Server_approve/op_approve/'.$sa_id."/".$type.'/'.$keyword);
                }else if($type == 's_use'){
                    $array = $this->sn->get_use();
                    foreach($array as $k => $v){
                        if($keyword == $v){
                            $whereu['s_use'] = $k;
                        }
                    }
                    $configpage['uri_segment']=6;//分页的数据查询偏移量在哪一段上
                    $offset=intval($this->uri->segment(6));
                    $configpage['base_url'] =site_url('Server_approve/op_approve/'.$sa_id."/".$type.'/'.$keyword);
                }else if($type == 's_type'){
                    $st_id = $this->st->get_id_by_name($keyword);
                    $wheres = $st_id;
                    $configpage['uri_segment']=6;//分页的数据查询偏移量在哪一段上
                    $offset=intval($this->uri->segment(6));
                    $configpage['base_url'] =site_url('Server_approve/op_approve/'.$sa_id."/".$type.'/'.$keyword);
                }else if($type== 's_cpu' ||$type== 's_mem' ||$type== 's_disk'||$type== 's_internet'||$type== 's_cpu'){
                    $whereu[$type] = $keyword;
                    $configpage['uri_segment']=6;//分页的数据查询偏移量在哪一段上
                    $offset=intval($this->uri->segment(6));
                    $configpage['base_url'] =site_url('Server_approve/op_approve/'.$sa_id."/".$type.'/'.$keyword);
                }else{
                    $configpage['uri_segment']=4;//分页的数据查询偏移量在哪一段上
                    $offset=intval($this->uri->segment(4));         
                    $configpage['base_url'] =site_url('Server_approve/op_approve/'.$sa_id."/");
                }
                
             if($type == 'owner'){
                  $count = $this->s->get_counts($wherein);
            }else if($type == 's_type'){
                $count = $this->s->get_countss($wheres);
            }else if($type== 's_cpu' ||$type== 's_mem' ||$type== 's_disk'||$type== 's_internet'||$type== 's_cpu'||$type== 's_use'){
               $count = $this->s->get_count($whereu); 
            }else{
                //$count = $this->s->get_count($where);
                $count = $this->s->get_count();
            }
            $page_size=2;//每页数量
            $configpage['total_rows'] = $count;//一共有多少条数据
            $configpage['per_page'] = $page_size; //每页显示条数
            $configpage['first_link'] = '首页';
            $configpage['next_link'] = '下一页';
            $this->pagination->initialize($configpage);
            $data['link'] = $this->pagination->create_links();
                         if($type ==  'owner'){
             $data['list'] = $this->s->getlists($offset,$page_size,$wherein);
            }else if($type == 's_type'){
               $data['list'] = $this->s->getlistss($offset,$page_size,$wheres);
            }else if($type== 's_cpu' ||$type== 's_mem' ||$type== 's_disk'||$type== 's_internet'||$type== 's_cpu'||$type== 's_use'){
             $data['list'] = $this->s->getlist($offset,$page_size,$whereu);
            }else{
                //$data['list'] = $this->s->getlist($offset,$page_size,$where);
                $data['list'] = $this->s->getlist($offset,$page_size);
            }
            $arr = $this->sa->get_sn($sa_id);
            $data['info'] = $this->sn->find_need($arr['sn_id']);
            $data['sa_id'] = $sa_id;
            $this->load->view('server/server_op_approve',$data);
        }
        public function server_see($id) {
            $data['info'] = $this->sn->find_need($id);
            $this->load->view('server/server_approve_see',$data);
        }
        public function server_disagree($sa_id) {
            $data['title'] = "服务器退回处理";
            $uid = $this->user_id;
            $role_id = $this->role_id;
            $bool = $this->sa->check($uid,$sa_id,$role_id);
            if ($bool == 1) {
                $data['sa_id'] = $sa_id;
                $this->load->view('server/server_disagree',$data);
            }else{
                echo "非法请求";
                }
            }
          public function server_save_disagree() {
              $where['sa_id'] = $this->input->post('sa_id');
              $data['sa_cause'] = $this->input->post('description');
              if($this->role_id == 5){
              $data['sa_current_id'] =$this->session->userdata('DX_user_id');
              }
              $role_id = $this->role_id;
              $message = $this->sa->disagree($data,$where);
              $this->disagree_email($where['sa_id'],$data['sa_cause']);
              echo json_encode($message);
            }
        public function disagree_email($sa_id,$mes) {
                $where['sa_id'] = $sa_id;
                //发邮件
                $res = $this->db->where($where)->get('server_approve');
                $result = current($res->result_array());
                $info = $this->sn->find_need($result['sn_id']);//得到审批id关联需求id的信息
                $name = $info['sn_realname'];//收件人的真实姓名
                $to = $this->sn->get_email_by_uid($info['u_id']);
                $arr = $this->sn->get_info_by_uid($result['sa_current_id']);
                $m = "您的申请已被".$arr['realname']."驳回<br /><p>具体原因是：</p><p>".$mes."</p>";
                $messages = $this->load->view('mail/mail_new_common',array('name'=>$name,'msg'=>$m),true);
                $subject="您的申请被驳回";
                sendcloud($to, $subject, $messages);
        }
                public function op_agree_email($sa_id) {
                $where['sa_id'] = $sa_id;
                //发邮件
                $res = $this->db->where($where)->get('server_approve');
                $result = current($res->result_array());
                $info = $this->sn->find_need($result['sn_id']);
                $name = $info['sn_realname'];//申请人真实名字
                $to = $this->sn->get_email_by_uid($info['u_id']);//申请人email
                
                $wheres['sn_id'] =  $result['sn_id'];
                $ress = $this->db->where($wheres)->get('server_owner');
                $results = $ress->result_array();
                $str = '';
                foreach($results as $v){
                   $ip = $this->s->get_name_by_id($v['s_id']);
                   $str.= "<p>服务器：".$ip."帐号为：".$v['account']."</p>";
                }
                $m = "<p>您的申请已被运维人员批复,具体信息如下:</p>".$str;
                $messages = $this->load->view('mail/mail_new_common',array('name'=>$name,'msg'=>$m),true);
                $subject="您的申请已批准";
                sendcloud($to, $subject, $messages);
        }
        
        
        
      
}	
?>