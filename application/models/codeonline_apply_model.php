<?php
/**
 * 保存审批模型
 * @author minbbp
 *
 */
class Codeonline_apply_model extends MY_Model
{
	protected  $_table="codeonline_apply";
	protected  $primary_key="a_id";
	public  function __construct()
	{
		parent::__construct();
	}
	/**
	 * 列表显示
	 * 联合查询申请人，申请模块信息
	 * @param int $offset
	 * @param int $limit
	 * @param int $user_id
	 */
	public function alllist($offset,$num,$user_id,$type_id,$a_status=0)
	{
		 $sql="select apply.apply_id,apply.a_id,apply.type_id,cat.git_tag,apply.a_status,u.realname,cm.m_name  from codeonline_apply as apply,users as u,codeonline_apply_table as cat,codeonline_model as cm   where apply.user_id=$user_id and apply.type_id=$type_id  and  apply.a_status=$a_status  and apply.apply_id=cat.apply_id  and cat.m_id=cm.m_id   and  u.id=cat.apply_user  order by apply.a_id limit $offset,$num";
		 return $this->db->query($sql)->result_array();
	}
	public function count_alllist($user_id,$type_id,$a_status=0)
	{
		$sql="select apply.apply_id,apply.a_id,apply.type_id,cat.git_tag,apply.a_status,u.realname,cm.m_name  from codeonline_apply as apply,users as u,codeonline_apply_table as cat,codeonline_model as cm   where apply.user_id=$user_id and apply.type_id=$type_id  and  apply.a_status=$a_status  and apply.apply_id=cat.apply_id  and cat.m_id=cm.m_id   and  u.id=cat.apply_user order by apply.a_id desc";
		return $this->db->query($sql)->num_rows();
	}
}