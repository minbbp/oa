<?php
/**
 * 服务器需求表
 * 
 */
class Server_need_model extends CI_Model
{

	/**
	 * 重写父类的构造方法
	 */
	public function __construct()
	{
		parent::__construct();	
                //$this->load->model('server_type_model','st',TRUE);
                $this->load->model('codeonline_model','co',TRUE);
	}
        public function add_apply($data)
	{   
		$this->db->insert('server_need',$data);
		return $this->db->insert_id();
	}
        /*
         * 查询所有信息数组
         */
        public function select_need($data) {
            foreach($data as &$v){
                $where['sn_id'] = $v['sn_id'];
                $res = $this->db->where($where)->get('server_need');
                $arr = current($res->result_array());
                $uid = $arr['u_id'];
                $tid = $arr['sn_type'];
                $v['sn_use'] = $this->select_use($arr['sn_use']);
                $v['sn_time'] = $arr['sn_time'];
                $v['sn_name'] = $this->get_username_by_id($uid);
                $v['m_name'] = $this->co->get_name_by_id($tid);
                $v['sn_realname'] = $this->get_realname_by_id($uid);
            }
            return $data;
        }
        
        /*
         * 用途
         */
         public function select_use($id) {
                $use['1'] = '开发';
                $use['2'] = '测试';
                $use['3'] = '生产';
                $use['4'] = '预上线';
            return $use["$id"];
        }
        public function get_use() {
                $use['1'] = '开发';
                $use['2'] = '测试';
                $use['3'] = '生产';
                $use['4'] = '预上线';
                return $use;
        }
        /*
         * 查询单条
         */
        public function find_need($sn_id) {
                $where['sn_id'] = $sn_id;
                $res = $this->db->where($where)->get('server_need');
                $arr = current($res->result_array());
                $uid = $arr['u_id'];
                $tid = $arr['sn_type'];
                $arr['sn_use'] = $this->select_use($arr['sn_use']);
                $arr['sn_time'] = $arr['sn_time'];
                $arr['sn_name'] = $this->get_username_by_id($uid);
                $arr['sn_realname'] = $this->get_realname_by_id($uid);
                $arr['m_name'] = $this->co->get_name_by_id($tid);
                return $arr;
        }
        /*
         * 获取用户id
         */
        public function get_username_by_id($id)
	{
                $where['id'] = $id; 
		$res = $this->db->select('username')->where($where)->get('users');
                $result = $res->result_array();
                $username = current($result);
                return $username['username'];
	}
       public function get_realname_by_id($id)
	{
                $where['id'] = $id; 
		$res = $this->db->select('realname')->where($where)->get('users');
                $result = $res->result_array();
                $username = current($result);
                return $username['realname'];
	}
        /*
         * 根据用户名 获取uid
         */
        public function get_id_by_username($username)
	{
                $where['username'] = $username; 
		$res = $this->db->where($where)->get('users');
                $result = $res->result_array();
                $uid = current($result);
                return $uid['id'];
	}
        /*
         * 根据真实名字 获取uid
         */
                public function get_id_by_realname($username)
	{
                $where['realname'] = $username; 
		$res = $this->db->where($where)->get('users');
                $result = $res->result_array();
                $uid = current($result);
                return $uid['id'];
	}
        public function get_id_by_uid($id) {
            $where['u_id'] = $id;
            $res = $this->db->where($where)->get('server_need');
            $result = $res->result_array();
            $new = array();
            foreach ($result as $value) {
                $new[] = $value['sn_id'];
            }
            return $new;
        }
        public function get_email_by_uid($uid)
	{
                $where['id'] = $uid; 
		$res = $this->db->where($where)->get('users');
                $result = $res->result_array();
                $uid = current($result);
                return $uid['email'];
	}
        public function get_info_by_uid($uid)
	{
                $where['id'] = $uid; 
		$res = $this->db->where($where)->get('users');
                $result = $res->result_array();
                $uid = current($result);
                return $uid;
	}
        public function get_uid_by_snid($sn_id) {
            $where['sn_id'] = $sn_id;
            $res = $this->db->where($where)->get('server_need');
            $result = current($res->result_array());
            return $result['u_id'];
        }
        
}
