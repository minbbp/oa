<?php
/**
 * 保存操作者的审核信息
 * @author minbbp
 *
 */
class Group_creators_model extends CI_Model
{
	private $table="group_creator";
	public function __construct()
	{
		parent::__construct();
	}
	/**
	 * 创建者持久化
	 * @param array $data
	 * @param int $gcre_id
	 */
	public function save($data,$gcre_id=NULL)
	{
		if($gcre_id)
		{
			return $this->db->update($this->table,$data,array('gcre_id'=>$gcre_id));
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
		$query=$this->db->query("select *,(select realname from users where users.id=$this->table.change_id)realname,(select group_name from git_groups where group_id=$this->table.group_id)group_name from $this->table where  gcre_creator=$user_id  order by gcre_id desc limit $offset,$num ");
		return $query->result_array();
	}
	public function alllist_count($user_id)
	{
		$query=$this->db->query("select * from $this->table where  gcre_creator=$user_id ");
		return $query->num_rows();
	}
	/**
	 * 把git账户组相关的信息查询出来
	 * @param int $gle_id
	 */
	public function get_allinfo($gcre_id)
	{
		$rsone=$this->db->query("select * from $this->table,git_groups where $this->table.group_id=git_groups.group_id and gcre_id=$gcre_id")->row_array();
		$group_id=$rsone['group_id'];
		$users_git=$this->db->query("select gits.git_account from gits,git_groups_user where gits.git_id=git_groups_user.git_account and git_groups_user.group_id=$group_id")->result_array();
		$rsone['git_accounts']=$users_git;
		return $rsone;
	}
	public function check_state($gle_id)
	{
		$creator_rs=$this->db->query("select * from $this->table where gle_id=$gle_id")->result_array();
		
		 $tmp=TRUE;
		//print_r($creator_rs);exit;
		foreach ($creator_rs as $creator)
		{
			if($creator['gcre_state']!=1)
			{
				$tmp=FALSE;
			}
		}
		return $tmp; 
	}
	/**
	 * 检查用户的git账号是否已经审批通过
	 */
	public function check_git_state($git_id)
	{
		$creator_rs=$this->db->query("select * from $this->table where git_id=$git_id")->result_array();
		
		$tmp=TRUE;
		//print_r($creator_rs);exit;
		foreach ($creator_rs as $creator)
		{
			if($creator['gcre_state']!=1)
			{
				$tmp=FALSE;
			}
		}
		return $tmp;
	}
	/**
	 * 获取一条数据的记录
	 * @param int $gcre_id
	 */
	public function find_one($gcre_id)
	{
		return $this->db->query("select * from $this->table where gcre_id=$gcre_id")->row_array();
	}
}