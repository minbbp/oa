<?php
/**
 * 服务器分类表
 * 
 *
 *
 */
class Server_owner_model extends CI_Model
{
	/**
	 * 重写父类的构造方法
	 */
	public function __construct()
	{
		parent::__construct();	
                $this->load->model('server_need_model','sn',TRUE);
	}
        public function so_insert($data) {
            $this->db->insert('server_owner',$data);
            $num = $this->db->insert_id();
            return $num;
        }
        public function select_owner($s_id) {
            $where['s_id'] = $s_id;
            $res = $this->db->where($where)->get('server_owner');
            $result = $res->result_array();
            $arr = array();
            $str = '';
            foreach ($result as $v) {
               $wheres['sn_id'] = $v['sn_id'];
               $ress = $this->db->where($wheres)->get('server_need');
               $results = current($ress->result_array());
               $arr[] = $this->sn->get_username_by_id($results['u_id']);
            }
            if($arr){
            $str = implode(',',$arr);
            }
            return $str;
        }
         public function get_owner_list($s_id) {
            $where['s_id'] = $s_id;
            $res = $this->db->where($where)->get('server_owner');
            $result = $res->result_array();
            foreach ($result as &$v) {
               $wheres['sn_id'] = $v['sn_id'];
               $ress = $this->db->where($wheres)->get('server_need');
               $results = current($ress->result_array());
               $uname = $this->sn->get_username_by_id($results['u_id']);
               $v['account'] = $uname."--".$v['account'];
               $v['so_name'] = $uname;
            }
            return $result;
        }          
        public function del_owner($arr) {
            $this->db->where_in('so_id',$arr)->delete('server_owner');
            return $this->db->affected_rows();;
        }
        public function get_id_by_snid($arr) {
            $res = $this->db->where_in('sn_id',$arr)->get('server_owner');
            $result = $res->result_array();
            $new = array();
            foreach ($result as $value) {
                $new[] = $value['s_id'];
            }
           return $new;
        }

             
}
