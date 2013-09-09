<?php
class Grouplevel extends CI_Controller
{
	private $user_id;
	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('form','url'));
		$this->load->library(array('email','form_validation','pagination','dx_auth'));
		$this->dx_auth->check_uri_permissions();//检查用户权限
		$this->user_id=$this->dx_auth->get_user_id();
		$this->load->model('Group_ops_model','gop',TRUE);// op操作模型
		$this->load->model('Group_level_model','gle',TRUE);//主管操作模型
		$this->load->model('Users_model','users',TRUE);
		$this->load->model('Gitsgroup_model','group',TRUE);
		$this->load->model('Group_creators_model','cre',TRUE);
	}
	/**
	 * 我的审核列表
	 */
	public function alllist()
	{
		$config['per_page']=5;
		$config['total_rows']=$this->gle->alllist_count($this->user_id);
		$config['base_url']=base_url('index.php/grouplevel/alllist');
		$offset=intval($this->uri->segment(3));
		$rs=$this->gle->alllist($this->user_id,$config['per_page'],$offset);
		$this->pagination->initialize($config);
		$page=$this->pagination->create_links();
		$data['groups']=$rs;
		$data['page']=$page;
		$data['title']='审核信息管理';
		$this->load->view('gitp/group_level_list',$data);
		
	}
	/**
	 *  审核内容添加
	 */
	public function edit($gle_id)
	{
		//   审核的同时把相关的信息都列出来，包括账号的用途，账号的描述，申请人，组中的成员
		$data['info']=$this->gle->get_allinfo($gle_id);
		$data['title']='git组信息审批';
		$change_id=$data['info']['change_id'];
		$change_user=$this->users->get_useremail_by_id($change_id);
		$data['change']=$change_user;
		$this->load->view('gitp/level_edit',$data);
	}
	/**
	 * 保存审核内容
	 */
	public function save($gle_id)
	{
		$data['gle_state']=$this->input->post('gle_state');
		$data['gle_description']=$this->input->post('gle_description');
		$data['addtime']=time();
		if($this->gle->save($data,$gle_id))
		{
			$this->check_commit($gle_id);
			redirect('grouplevel/alllist');
		}
		else
		{
			show_404();
		}
	}
	/**
	 * 检查其他人是否提交了，如果说我是最后一个提交，则提交信息给
	 * 确认了一点，就是如果一个人需要他的直接领导审批，那么他也需要项目创建者进行审批。
	 * 或者说，一旦有人修改某个git账号组，该账号组就应该被锁定。
	 */
	public function check_commit($gle_id)
	{
		$gle_rs=$this->gle->find_one($gle_id);
		$group_rs=$this->group->find_one($gle_rs['group_id']);
		//print_r($gle_rs);print_r($group_rs);
		//只有主管审核通过的时候才过去检查,否则不予推送到op端
		if($gle_rs['gle_state']==1)
		{
			//检查创建用户和修改用户是否是同一个，如果是同一个的话，怎不去查询创建者审批表。直接把工作流推送到op
			/* echo $group_rs['group_creator'];
			echo "<br/>";
			echo $gle_rs['change_id']; */
			if($group_rs['group_creator']==$gle_rs['change_id'])
			{
				  echo "现在应该给op直接提交工作流程";
				  $data['group_id']=$gle_rs['group_id'];
				  $data['change_id']=$gle_rs['change_id'];
				  $data['gop_state']=0;
				  $this->gop->save($data);
			}
			else
			{
				echo "检查创建信息创建者审核通过则直接op推送信息";
				if($this->cre->check_state($gle_id))
				{//如果说创建者审核通过就给op发送审核通过信息
					$data['group_id']=$gle_rs['group_id'];
					$data['change_id']=$gle_rs['change_id'];
					$data['gop_state']=0;
					$this->gop->save($data);
					echo "  <br/>看看这里";
				}
				
			}
		}
	}
}