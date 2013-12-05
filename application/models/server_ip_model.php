<?php
/**
 * 服务器需求表
 * 
 */
class Server_ip_model extends CI_Model
{
	/**
	 * 重写父类的构造方法
	 */
	public function __construct()
	{
		parent::__construct();	
	}
        public function insert_ip($arr,$s_id,$type)
	{       
                if($arr){
                    foreach ($arr as $value) {
                        $data['s_id'] = $s_id;
                        $data['si_ip'] = $value;
                        $data['si_type'] = $type;
                        $this->db->insert('server_ip',$data);
                        unset($data);
                    }
                }
		return $this->db->insert_id();
	}
        public function get_ip($id) {
            $where['s_id'] = $id;
            $res = $this->db->where($where)->get('server_ip')->result_array();
            return $res;
        }
        public function del_ip($s_id) {
           $where['s_id'] = $s_id; 
           $this->db->where($where)->delete('server_ip');
        }
       
        
}
