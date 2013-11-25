<?php
/**
 * 服务器需求表
 * 
 *
 *
 */
class Server_manage_model extends CI_Model
{

	/**
	 * 重写父类的构造方法
	 */
	public function __construct()
	{
		parent::__construct();
                //$this->load->model('server_type_model','st',TRUE);
                $this->load->model('server_need_model','sn',TRUE);
                $this->load->model('server_owner_model','so',TRUE);
	}
        public function getlist($offset,$page_size,$where='')
	{   
            if($where){
            $res = $this->db->where($where)->limit($page_size,$offset)->get('server');
            }else{
            $res = $this->db->limit($page_size,$offset)->get('server');
            }
            $result = $res->result_array();
            foreach ($result as &$v) {
                $v['sn_use'] = $this->sn->select_use($v['s_use']);
                $val = $this->so->select_owner($v['s_id']);
                    if(mb_strlen($val) <15 ){
                        if(mb_strlen($val) == 0 ){
                        $v['s_owner'] = "暂时没有使用人";
                        $v['owner_status'] = 0;
                        }else{
                        $v['s_owner'] = $val;
                        $v['owner_status'] = 1;
                        }
                    }else{
                        $v['s_owner'] = mb_substr($val, 0, 15)."...";  
                        $v['owner_status'] = 1;
                    }        
            }
            return $result ? $result : array();
	}
                public function getlists($offset,$page_size,$wherein)
	{   
             if($wherein){
            $res = $this->db->where_in('s_id',$wherein)->limit($page_size,$offset)->get('server');
            $result = $res->result_array();
            foreach ($result as &$v) {
                $v['sn_use'] = $this->sn->select_use($v['s_use']);
                $val = $this->so->select_owner($v['s_id']);
                    if(mb_strlen($val) <15 ){
                        if(mb_strlen($val) == 0 ){
                        $v['s_owner'] = "暂时没有使用人";
                        $v['owner_status'] = 0;
                        }else{
                        $v['s_owner'] = $val;
                        $v['owner_status'] = 1;
                        }
                    }else{
                        $v['s_owner'] = mb_substr($val, 0, 15)."...";  
                        $v['owner_status'] = 1;
                    }        
        }
        
                    }else{
                      $result = array();  
                    }
            return $result ? $result : array();
	}
 public function getlistss($offset,$page_size,$where)
	{   
            if($where){
            $sql = "SELECT * FROM (`server`) WHERE find_in_set('$where',s_type)  LIMIT $offset,$page_size";
            $res=$this->db->query($sql);
            $result = $res->result_array();
            foreach ($result as &$v) {
                $v['sn_use'] = $this->sn->select_use($v['s_use']);
                $val = $this->so->select_owner($v['s_id']);
                    if(mb_strlen($val) <15 ){
                        if(mb_strlen($val) == 0 ){
                        $v['s_owner'] = "暂时没有使用人";
                        $v['owner_status'] = 0;
                        }else{
                        $v['s_owner'] = $val;
                        $v['owner_status'] = 1;
                        }
                    }else{
                        $v['s_owner'] = mb_substr($val, 0, 15)."...";  
                        $v['owner_status'] = 1;
                    }        
            }
        }else{
            $result = array();
        }
            return $result ? $result : array();
	}
        /*
         * 得到全部服务器列表
         */
        
        public function get_all_list($id) {
            $sql = "SELECT * FROM (`server`) WHERE find_in_set('$id',s_type)";
            $res=$this->db->query($sql);
            $result = $res->result_array();
            return $result ? $result : array();
        }
        public function find_server($id) {
            $where['s_id'] = $id;
            $res = $this->db->where($where)->get('server');
            $result = $res->result_array();
            return $result;
        }
        public function edit_server($data,$where) {
            $this->db->update('server',$data,$where);
            $row = $this->db->affected_rows();
            return $row;
        }
        public function server_insert($data) {
            $this->db->insert('server',$data);
            return $this->db->insert_id();
        }
        /*
         * 删除
         */
        public function server_delete($id){
            $where['s_id'] = $id;
            $this->db->delete('server',$where);
            return $this->db->affected_rows();
        }
        /*
         * 得到总数
         */
        public function get_count($where='') {
            if($where){
            $res = $this->db->where($where)->get('server');  
            }else{
             $res = $this->db->get('server');   
            }
            $result = $res->result_array();
            return count($result);
        }
         public function get_countss($where) {
             if($where){
            $sql = "SELECT * FROM (`server`) WHERE find_in_set('$where',s_type)";
            $res=$this->db->query($sql);
            $result = $res->result_array();
             }else{
                 $result = array();
             }
            return count($result);
        }
        public function get_counts($wherein) {
            if($wherein){
            $res = $this->db->where_in('s_id',$wherein)->get('server');
            $result = $res->result_array();
            }else{
              $result = array();  
            }
            return count($result);
        }
        public function get_name_by_id($s_id) {
            $res = $this->db->where('s_id',$s_id)->get('server');
            $result = current($res->result_array());
            return $result['s_internet'];
        }
        public function get_server_by_str($str)
        {
        	$sql="select * from  server where s_id in ('{$str}')";
        	return $this->db->query($sql)->result_array();
        }
}
