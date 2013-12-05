<?php
/**
 * 服务器扩容表
 * 
 */
class Server_expansion_model extends CI_Model
{
	/**
	 * 重写父类的构造方法
	 */
	public function __construct()
	{
		parent::__construct();	
	}
        public function insert_apply($data) {
            $this->db->insert('server_expansion',$data);
            return $this->db->insert_id();
        }
       
        
}
