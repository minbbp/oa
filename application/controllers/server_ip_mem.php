<?php
/**
 * 服务器ip+扩容控制器
 */
class Server_ip_mem extends CI_Controller
{
    
	//private $user_id;//当前操作的用户的id
	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('form','url','message'));
		$this->load->library(array('dx_auth','session','pagination','form_validation'));
                $this->load->model('server_owner_model','so',TRUE);
                $this->load->model('server_manage_model','s',TRUE);
                $this->load->model('server_ip_model','si',TRUE);
                $this->load->model('server_need_model','sn',TRUE);
                $this->load->model('server_approve_model','sa',TRUE);
                $this->load->model('server_expansion_model','se',TRUE);
                $this->load->model('codeonline_model','co',TRUE);
		$this->dx_auth->check_uri_permissions();//检查用户权限
		$this->user_id=$this->session->userdata('DX_user_id');
	}
        
        public function index(){
            $data['title'] = "服务器外网申请";
            $data['smalltitle'] = "我的服务器";
            $arr = $this->so->get_sid_by_uid($this->user_id);
            $wherein = array();
            foreach ($arr as $v) {
                $wherein[] = $v['s_id'];
            }
             $configpage['uri_segment']=3;//分页的数据查询偏移量在哪一段上
             $offset=intval($this->uri->segment(3));
             $configpage['base_url'] =site_url('server_ip_mem/index/');
             $count = $this->s->get_counts($wherein);
            $page_size = 2;//每页数量
            $configpage['total_rows'] = $count;//一共有多少条数据
            $configpage['per_page'] = $page_size; //每页显示条数
            $configpage['first_link'] = '首页';
            $configpage['next_link'] = '下一页';
            $data['list'] = $this->s->getlists($offset,$page_size,$wherein);
            $this->pagination->initialize($configpage);
            $data['link'] = $this->pagination->create_links();
            $this->load->view('server/server_ip_mem_apply',$data);
        }
        public function server_see($id){
            $data['info'] = current($this->s->find_server($id));
            $data['list'] = $this->co->get_all_list();
            $data['use_list'] = $this->sn->get_use();
            $data['list_owner'] = $this->so->get_owner_list($id);
            $data['ipnei_list'] = $this->group_ip($this->si->get_ip($id),1);
            $data['ipwai_list'] = $this->group_ip($this->si->get_ip($id),2);
            $this->load->view('server/server_ip_mem_see',$data);
        }
        public function apply_ip($id) {
            $data['s_id'] = $id;
            $data['info'] = current($this->s->find_server($id));
            $data['title'] = "申请外网";
            $this->load->view('server/server_apply_ip',$data);
        }
        public function save_apply() {
            $data['u_id'] = $this->user_id;
            $data['se_type'] = $this->input->post('se_type');
            $data['s_id'] = $this->input->post('s_id');
            if($data['se_type'] == 1){
            $data['se_ports'] = $this->input->post('ports') ? $this->input->post('ports'):null;
            $data['se_desc'] = $this->input->post('description');
            }else{
            $data['se_cpu'] = $this->input->post('cpu');
            $data['se_mem'] = $this->input->post('mem');
            $data['se_disk'] = $this->input->post('disk');  
            }
            $num = $this->se->insert_apply($data);
            if($num){
                //$this->sea->insert_approve($data);
            }
           
            
        }










        public function group_ip($array,$type){
            $temp = array();
            foreach ($array as $value) {
                if($value['si_type']==$type){
                    $temp[] = $value;
                }
            }
            return $temp;
        }
}
?>