<?php
/**
 * 服务器分类表
 * 
 *
 *
 */
class Server_type_model extends CI_Model
{
	/**
	 * 重写父类的构造方法
	 */
	public function __construct()
	{
		parent::__construct();	
	}
        
        
        /*
         * 添加分类
         */
        
        public function add_type($data) {
            $this->db->insert('server_type',$data);
            return $this->db->affected_rows();
        }
        public function get_all(){
            $where['st_yezi'] = 1;
            $res = $this->db->where($where)->get('server_type');
            $result = $res->result_array();
            return $result;
        }

        /*
         * 删除
         */
        public function del_type($id){
            $where['st_id'] = $id;
            $this->db->delete('server_type',$where);
            return $this->db->affected_rows();
        }
        /*
         * 编辑
         */
        public function edit($arr){
            $where['st_id'] = $arr['st_id'];
            $data['st_name'] = $arr['st_name'];
            $data['st_pid'] = $arr['st_pid'];
            $data['st_desc'] = $arr['st_desc'];
            $data['st_yezi'] = $arr['st_yezi'];
            $bool=$this->db->update('server_type',$data,$where);
            return $bool;
        }


        /*
         * 获取详细信息
         */
        public function get_type_m($id){
            $where['st_id'] = $id;
            $res = $this->db->where($where)->get('server_type');
            $result = $res->result_array();
            return $result; 
        }

        

        /**
	 * 获取目录列表
	 * @return array 返回查询数组
	 */
        public function get_typelist($div='')
	{       
            $toplist = $this->get_top_typelist();
            if($toplist){
                foreach ($toplist as  $value) {
                        $value['st_name'] = ''.$value['st_name'];
                        $NewArr[] = $value;
                        $Res = $this->getallchild($value['st_id'],$div);
                        if($Res){
                            foreach($Res as $v){
                                         $NewArr[] = $v;
                                 }
			}
                }
                return $NewArr;
            }else{
                return array();
            }

	}
        
        /**
         * 得到顶级列表
         */
        public  function get_top_typelist(){
            $where['st_pid'] = 0;
            $res = $this->db->where($where)->get('server_type');
            $result = $res->result_array();
            return $result;
        }
        /**查询某个目录的所有子目录信息
         * 查询所有子类
         * @param type $st_id
         */
        public function getallchild($st_id,$div=''){
            $child = $this->getchild($st_id);
                if($child){
                foreach($child as &$val){
                        $val['st_name'] = $div ? $div .$val['st_name'] : $val['st_name'];
                        $NewArr[] = $val;
                        $Res = $this->getallchild($val['st_id'],$div);
                        if($Res){
                                foreach($Res as &$v){
                                        $v['st_name'] = $div ? $div .$v['st_name'] : $v['st_name'];
                                        $NewArr[] = $v;
                                }
                        }
                }
                return $NewArr;
                }else{
                        return;
                }
        }
        /**
         * 查询某个目录的子目录信息
         */
        public function getchild($st_id){
            $where['st_pid'] = $st_id;
            $res = $this->db->where($where)->get('server_type');
            $result = $res->result_array();
            return $result ? $result : array();
            
        }
        /*
         * 获取服务器类型
         */
        public function get_server_type($id) {
            $arr = explode(',', $id);
            foreach($arr as $v){
                $where['st_id'] = $v;
                $res = $this->db->where($where)->get('server_type');
                $result = $res->result_array();
                if($result){
                $results[] = current($result);
                }else{
                $results = array();
                }
            }
            return $results; 

        }
        /*
         * 根据服务获取id
         */
        public function get_id_by_name($name) {
                 $where['st_name'] = $name;
                $res = $this->db->where($where)->get('server_type');
                $result = current($res->result_array());
                return $result['st_id'];
        }
}
