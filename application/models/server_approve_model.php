<?php
/**
 * 服务器审批表
 * 
 *
 *
 */
class Server_approve_model extends CI_Model
{
	/**
	 * 重写父类的构造方法
	 */
	public function __construct()
	{
		parent::__construct();	
                $this->load->model('server_need_model','sn',TRUE);
	}
        public function add_approve($sn_id,$pid,$level_info,$c='')
	{   
            $data['sn_id'] = $sn_id;
            $data['sa_status'] = 0;
            $data['sa_current_id'] = $pid;
            $data['sa_cause']='';
            //$data['sa_approve_time'] = time();
            if($pid == 0){
                //pid=0为主管 直接交给运维审批Type=2
                $data['sa_type'] = 2;
            }else{
                $data['sa_type'] = 1;
                //写邮件 ok
                
                $info['info'] = $this->sn->find_need($sn_id);
                $to = $level_info['email'];
                $info['levelinfo'] = $level_info;
                $subject = '新的服务器申请审批';
                $m = $this->load->view('mail/mail_server_apply',$info,true);
                $name = $level_info['realname'];
                $message = $this->load->view('mail/mail_new_common',array('name'=>$name,'msg'=>$m),true);
                sendcloud($to, $subject, $message,$c);
                
            }
            $this->db->insert('server_approve',$data);
            return $this->db->insert_id();
	}
        public function select_approve($data,$offset,$page_size) {
            $rid = $data['rid'];
            $uid = $data['uid'];
            if($rid == 6){
                //6为主管
                $where['sa_type'] = 1;
                $where['sa_current_id'] = $uid;
            }else if($rid == 5){
                //5为运维
                $where['sa_type'] = 2;
            }else{
                $where['sa_current_id'] = $uid;
            }
            $where['sa_status'] = 0;
            $res = $this->db->where($where)->limit($page_size,$offset)->get('server_approve');
            $result = $res->result_array();
            return $result ? $result : array();
        }

        public function get_count($data) {
            $rid = $data['rid'];
            $uid = $data['uid'];
            if($rid == 6){
                //6为主管
                $where['sa_type'] = 1;
                $where['sa_current_id'] = $uid;
            }else if($rid == 5){
                //5为运维
                $where['sa_type'] = 2;
            }else{
                $where['sa_current_id'] = $uid;
            }
            $where['sa_status'] = 0;
            $res = $this->db->where($where)->get('server_approve');
            $result = $res->result_array();
            return count($result);
        }
        public function check($uid,$sa_id,$role_id) {
            if($role_id !=2 && $role_id !=5){
                $where['sa_id'] = $sa_id;
                $where['sa_current_id'] = $uid;
                $res = $this->db->where($where)->get('server_approve');
                $result = $res->result_array();
                return $result ? 1:0;
            }else{
                return 1;
            }

        }
        /*
         * 主管同意
         */
        public function agree($sa_id){
            $data['sa_status'] = 1;
            $data['sa_approve_time'] = time();
            $where['sa_id'] = $sa_id;
            $this->db->update('server_approve',$data,$where);
            $now = $this->db->affected_rows();
            if($now == 1){
                $res = $this->db->where($where)->get('server_approve');
                $result = current($res->result_array());
                    if($result['sa_type'] == 1){
                        $new['sn_id'] = $result['sn_id'];
                        $new['sa_type'] = 2;
                        $new['sa_status'] = 0;
                        $this->db->insert('server_approve',$new);
                        if($this->db->insert_id()){
                        $message['status'] = 1;
                        $message['msg'] = "审批通过"; 
                         //写邮件
                         
                            $info['info'] = $this->sn->find_need($result['sn_id']);
                            $to = ADRD_OP_TWO;
                            $subject = '新的服务器申请审批';
                            $m = $this->load->view('mail/mail_server_apply',$info,true);
                            $name = "运维人员";
                            $messages = $this->load->view('mail/mail_new_common',array('name'=>$name,'msg'=>$m),true);
                            //$c = 
                            sendcloud($to, $subject, $messages);
                            //
                            
                        }else{
                        log_message('error',$this->db->last_query());
                        $message['status'] = 0;
                        $message['msg'] = "审批失败,请联系管理员";  
                        }
                    }else{
                        log_message('error',$this->db->last_query());
                        $message['status'] = 0;
                        $message['msg'] = "审批失败,请联系管理员";  
                    }
            }else{
                log_message('error',$this->db->last_query());
                $message['status'] = 0;
                $message['msg'] = "审批失败,请联系管理员";   
            }
            return $message;
        }
       public function agree_op($sa_id) {
            $data['sa_status'] = 1;
            $data['sa_approve_time'] = time();
            $where['sa_id'] = $sa_id;
            $this->db->update('server_approve',$data,$where);
            $now = $this->db->affected_rows();
            return $now;
        }
        public function disagree($data,$where) {
            $data['sa_status'] = -1;
            $this->db->update('server_approve',$data,$where);
            $num = $this->db->affected_rows();
            if($num == 1){
                  $message['status'] = 1;
                  $message['msg'] = "操作成功"; 
            }else{
                  $message['status'] = 0;
                  $message['msg'] = "操作失败,请联系管理员"; 
            }
            return $message;
        }
        public function get_sn($id) {
            $where['sa_id'] = $id;
            $res = $this->db->where($where)->get("server_approve");
            $result = current($res->result_array());
            return $result;
        }

}
