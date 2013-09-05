<?php
/**
 * 测试cI的模型使用
 * @author minbbp
 *使用数据模型的时候，并不会去链接数据库。在载入模型的时候可以选择手动链接数据库。也可以在模型中使用数据库自己进行链接。
 *也可在配置文件中，让数据库链接自动去实例化。
 */
class User_model extends CI_Model
{
	
	var $user_name="";
	var $user_pwd="";
	var $user_email="";
	var $user_addtime="";
	
	/**
	 * 重写父类的构造方法
	 */
	public function __construct()
	{
		parent::__construct();	
	}
	/**
	 * 随机插入一条数据
	 */
	public function insert_rand_one()
	{
		//$this->load->database();
		
		$this->user_name="test".rand(10,100);
		$this->user_pwd=md5($this->user_name);
		$this->user_email=$this->user_name."@sohu.com";
		$this->user_addtime=time();
		//print_r($this->db);
		//exit;
		$this->db->insert('users', $this);
	}
	public function getAll()
	{
		$this->db->get();
	}
	public function deleteOne($user_id)
	{
		$this->db->where('user_id',$user_id);
		$this->db->delete('users');
			
	}
	/**
	 * 显示数据表的总的行数
	 */
	public function show_count()
	{
		return $this->db->count_all('users');
		 
	}
	/**
	 * 分页显示数据
	 * $mun_start 开始显示的条数
	 * @param $num 每页显示的条数
	 */
	public function show_data($num,$num_start)
	{
		  $query=$this->db->get('users',$num,$num_start);
		  $tmp=array();
		  foreach( $query->result()as $row)
		  {
		  	$tmp[]=$row;
		  }
		  return $tmp;
	}
}
