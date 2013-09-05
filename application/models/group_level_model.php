<?php
/**
 * 主管审核的模型，主要对主管审核的内容进行操作
 */
class Group_level_model extends CI_Model
{
	private $table='group_level';
	public function __construct()
	{
		parent::__construct();
	}
	/**
	 * 对主管的操作进行保存
	 * @param array $data
	 * @param int $gle_id
	 */
	public function save($data,$gle_id=NULL)
	{
		if($gle_id)
		{
			return $this->db->update($this->table,$data,array('gle_id'=>$gle_id));
		}
		else
		{
			return $this->db->insert($this->table,$data);
		}
	}
	/**
	 * 列表显示信息
	 */
	public function alllist($user_id,$num,$offset)
	{
		$query=$this->db->query("select *,(select realname from users where users.id=$this->table.change_id)realname,(select group_name from git_groups where group_id=$this->table.group_id)group_name from $this->table where  gle_level=$user_id  order by gle_id desc limit $offset,$num ");
		return $query->result_array();
	}
	public function alllist_count($user_id)
	{
		$query=$this->db->query("select * from $this->table where  gle_level=$user_id ");
		return $query->num_rows();
	}
	/**
	 * 把git账户组相关的信息查询出来
	 * @param int $gle_id
	 */
	public function get_allinfo($gle_id)
	{
		$rsone=$this->db->query("select * from $this->table,git_groups where $this->table.group_id=git_groups.group_id and gle_id=$gle_id")->row_array();
		$group_id=$rsone['group_id'];
		$users_git=$this->db->query("select gits.git_account from gits,git_groups_user where gits.git_id=git_groups_user.git_account and git_groups_user.group_id=$group_id")->result_array();		
		$rsone['git_accounts']=$users_git;
		return $rsone;
	}
}