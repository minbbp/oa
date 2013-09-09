<?php
/**
 * 与git账号操作相关的数据库操作
 */
class Gits_model extends CI_Model
{
	
	public function __construct()
	{
		parent::__construct();
	}
	/**
	 * 保存申请key时的数据
	 * @param unknown $gitdata
	 */
	public function save_apply($gitdata)
	{
		 $this->db->insert('gits',$gitdata);
		return $this->db->insert_id();
	}
	/**
	 * 查看自己已经申请的所有的
	 * 以分页的形式展示
	 * @param $user_id 用户的id号
	 * @param $num 每页显示的数量
	 * @param $offset 偏移量
	 * @return array 一个结果集
	 */
	 public function show_mygit($user_id,$num,$offset)
	 {
	 	$this->db->select('git_id, git_account,git_state,h_state,op_state,addtime');
	 	$this->db->order_by('git_id desc');
	 	$query=$this->db->get_where('gits',array('add_user'=>$user_id),$num,$offset);
	 	return $query;
	 }
	 /**
	  *  统计出来当前数据表的总的行数
	  */
	 public function get_count()
	 {
	 	return $this->db->count_all('gits');
	 }
	 /**
	  * 获取所有git账号
	  * @param int $num 每页显示数量
	  * @param int $offset 分页偏移量
	  * @return array; 返回一个包含所有结果集的数组
	  */
	public function show_allgit($num,$offset)
	{
		$this->db->select('git_id, git_account,git_state,h_state,op_state,addtime,username');
		$this->db->from('gits');
		$this->db->join('users','users.id=gits.add_user');
		$this->db->order_by('git_id desc');
		$query=$this->db->get('',$num,$offset);
		return $query;
	}
	/**
	 * 
	 * @param int $num
	 * @param int $offset
	 * @return 一个结果集
	 */
	public function show_one_user_git($user_id,$num,$offset)
	{
		$this->db->select('git_id, git_account,git_state,addtime');
		$this->db->from('gits');
		$this->db->order_by('git_id desc');
		$query=$this->db->get_where('',array('add_user'=>$user_id),$num,$offset);
		return $query;
	}
	public function show_one_user_count($user_id)
	{
		return $this->db->get_where('gits',array('add_user'=>$user_id))->num_rows();
	}
	/**
	 * 获取一个主管应该审批的信息
	 */
	public function show_one_level($h_level,$offset,$num)
	{
		return $this->db->query("select *,(select username from users where id=gits.add_user)username from gits where h_level='$h_level' order by git_id desc limit $offset,$num");
		
	}
	public function show_one_level_count($h_level)
	{
		return $this->db->get_where('gits',array('h_level'=>$h_level))->num_rows();
	}
	/**
	 * 通过git_id 返回一条结果集
	 */
	public function get_one($git_id)
	{
		$query=$this->db->get_where('gits',array('git_id'=>$git_id));
		return $query->row_array();
	}
	/**
	 * 保存记录到数据库
	 */
	public function save($data,$git_id=NULL)
	{
		if($git_id)
		{
			return $this->db->update('gits',$data,array('git_id'=>$git_id));
		}
		else
		{
			return $this->db->insert('gits',$data);
		}
	}
	/**
	 *  删除一条记录
	 * @param int $git_id
	 */
	public function delete($git_id)
	{
		return $this->db->delete('gits',array('git_id'=>$git_id));
	}	
	/**
	 * 统计管理员的记录，没人处理的条数
	 */
	public function total_operator()
	{
		$sql="select username,count(*)total from gits,users where gits.operator=users.id group by gits.operator";
		return $this->db->query($sql)->result_array();
	}
	
	public function get_key_account()
	{
		$this->db->select('git_id,git_account');
		$query_gits=$this->db->get_where('gits',array('git_state'=>1));
		return $query_gits->result_array();
	}
}