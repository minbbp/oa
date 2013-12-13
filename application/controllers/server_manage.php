<?php
/**
 * 服务器的控制器，
 */
class Server_manage extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('form','url','message'));
		$this->load->library(array('dx_auth','session','pagination','form_validation'));
                $this->load->model('server_manage_model','s',TRUE);
                $this->load->model('server_ip_model','si',TRUE);
                $this->load->model('server_need_model','sn',TRUE);
                $this->load->model('server_owner_model','so',TRUE);
                $this->load->model('server_approve_model','sa',TRUE);
                $this->load->model('codeonline_model','co',TRUE);
		$this->dx_auth->check_uri_permissions();//检查用户权限
		$this->user_id=$this->session->userdata('DX_user_id');
	}
        public function index($type=false,$keyword=false){
            //逻辑有点混乱。有时间再优化。。。
           $keyword = urldecode($keyword);
                if($type == 'owner'){
                    if($keyword != ''){
                    $uid = $this->sn->get_id_by_realname($keyword);
                    $arr2 = $this->sn->get_id_by_uid($uid);
                    $wherein = $this->so->get_id_by_snid($arr2);
                    }else{
                        $wherein = false;
                    }
                    $configpage['uri_segment']=5;//分页的数据查询偏移量在哪一段上
                    $offset=intval($this->uri->segment(5));
                    $configpage['base_url'] =site_url('server_manage/index/'.$type.'/'.$keyword);
                }else if($type == 's_use'){
                    $array = $this->sn->get_use();
                    foreach($array as $k => $v){
                        if($keyword == $v){
                            $whereu['s_use'] = $k;
                            break;
                        }else{
                            $whereu = false;
                        }
                    }
                    $configpage['uri_segment']=5;//分页的数据查询偏移量在哪一段上
                    $offset=intval($this->uri->segment(5));
                    $configpage['base_url'] =site_url('server_manage/index/'.$type.'/'.$keyword);
                }else if($type == 's_type'){
                    $st_id = $this->co->get_id_by_name($keyword);
                    $wheres = $st_id;
                    $configpage['uri_segment']=5;//分页的数据查询偏移量在哪一段上
                    $offset=intval($this->uri->segment(5));
                    $configpage['base_url'] =site_url('server_manage/index/'.$type.'/'.$keyword);
                }else if($type== 's_cpu' ||$type== 's_mem' ||$type== 's_disk'||$type== 's_internet'||$type== 's_cpu'){
                    $whereu[$type] = $keyword;
                    $configpage['uri_segment']=5;//分页的数据查询偏移量在哪一段上
                    $offset=intval($this->uri->segment(5));
                    $configpage['base_url'] =site_url('Server_manage/index/'.$type.'/'.$keyword);
                }else{
                    $configpage['uri_segment']=3;//分页的数据查询偏移量在哪一段上
                    $offset=intval($this->uri->segment(3));         
                    $configpage['base_url'] =site_url('server_manage/index/');
                }
            $data['title'] = "服务器管理";
            if($type == 'owner'){
                  $count = $this->s->get_counts($wherein);
            }else if($type == 's_type'){
                $count = $this->s->get_countss($wheres);
            }else if($type== 's_cpu' ||$type== 's_mem' ||$type== 's_disk'||$type== 's_internet'||$type== 's_cpu' || $type== 's_use'){
                if($type== 's_use' && !$whereu){
                     $count = 0;
                }else{
               $count = $this->s->get_count($whereu);
                }
            }else{
                $count = $this->s->get_count();
            }
            $page_size=PER_PAGE;//每页数量
            $configpage['total_rows'] = $count;//一共有多少条数据
            $configpage['per_page'] = $page_size; //每页显示条数
            $configpage['first_link'] = '首页';
            $configpage['next_link'] = '下一页';
             if($type ==  'owner'){
             $data['list'] = $this->s->getlists($offset,$page_size,$wherein);
            }else if($type == 's_type'){
               $data['list'] = $this->s->getlistss($offset,$page_size,$wheres);
            }else if($type== 's_cpu' ||$type== 's_mem' ||$type== 's_disk'||$type== 's_internet'||$type== 's_cpu'||$type== 's_use'){
                if($type== 's_use' && !$whereu){
                     $data['list'] = null;
                }else{
             $data['list'] = $this->s->getlist($offset,$page_size,$whereu);
                }
            }else{
                $data['list'] = $this->s->getlist($offset,$page_size);
            }
            $this->pagination->initialize($configpage);
            $data['link'] = $this->pagination->create_links();
            $data['u_type'] = $type;
            $data['u_keyword'] = $keyword;
            $this->load->view('server/server_manage',$data);
        }
        public function server_update($id) {
            $data['info'] = current($this->s->find_server($id));
            $data['title'] = "服务器详细信息";
            $data['list'] = $this->co->get_all_list();
            $data['use_list'] = $this->sn->get_use();
            $data['list_owner'] = $this->so->get_owner_list($id);
            $data['ipnei_list'] = $this->group_ip($this->si->get_ip($id),1);
            $data['ipwai_list'] = $this->group_ip($this->si->get_ip($id),2);
            $this->load->view('server/server_see',$data);
        }
        public function server_see($id) {
            $data['info'] = current($this->s->find_server($id));
            $data['title'] = "服务器详细信息";
            $data['list'] = $this->co->get_all_list();
            $data['use_list'] = $this->sn->get_use();
            $data['list_owner'] = $this->so->get_owner_list($id);
            $data['ipnei_list'] = $this->group_ip($this->si->get_ip($id),1);
            $data['ipwai_list'] = $this->group_ip($this->si->get_ip($id),2);
            $this->load->view('server/server_manage_see',$data);
        }
        public function server_edit() {
            $where['s_id'] = $this->input->post('s_id');
            $data['s_cpu'] = $this->input->post('cpu');
            $data['s_mem'] = $this->input->post('mem');
            $data['s_disk'] = $this->input->post('disk');
            $data['s_internet'] = $this->input->post('internet');
            $data['s_winternet'] = $this->input->post('winternet');
            $data['s_isp'] = $this->input->post('isp');
            if($this->input->post('type')){
            $data['s_type'] = implode(',',$this->input->post('type'));
            }
            $data['s_desc'] = $this->input->post('desc');
            $data['s_use'] = $this->input->post('use');
            $owner = $this->input->post('owner');
            if($owner){
             $nums = $this->so->del_owner($owner);
            }
            $row = $this->s->edit_server($data,$where);
            $this->si->del_ip($where['s_id']);
            if($this->input->post('internetnei')){
                    $arr = $this->input->post('internetnei');
                    $nums2 = $this->si->insert_ip($arr,$where['s_id'],1);
                }
           if($this->input->post('internetwai')){
                    $arr2 = $this->input->post('internetwai');
                    $nums3 = $this->si->insert_ip($arr2,$where['s_id'],2);
                }
            if($row == 1 || $nums!=0 ||$nums2!=0||$nums3!=0){
                $m['status'] = 1;
                $m['msg'] = "编辑成功";
            }else{
                //如果删除------没法提示
                $m['status'] = 1;
                $m['msg'] = "编辑成功";
            }
            echo json_encode($m);
        }
        public function server_add() {
             $data['title'] = "服务器添加";
             $data['use_list'] = $this->sn->get_use();
             $data['list'] = $this->co->get_all_list();
             $this->load->view('server/server_add',$data);
        }
        public function server_insert() {
            $data['s_cpu'] = $this->input->post('cpu');
            $data['s_mem'] = $this->input->post('mem');
            $data['s_disk'] = $this->input->post('disk');
            $data['s_internet'] = $this->input->post('internet');
            $data['s_winternet'] = $this->input->post('winternet');
            if($data['s_winternet']==''){
                $data['s_winternet']=NULL;
            }
            $data['s_isp'] = $this->input->post('isp');
            if($this->input->post('type')){
            $data['s_type'] = implode(',',$this->input->post('type'));
            }
            $data['s_desc'] = $this->input->post('desc');
            $data['s_use'] = $this->input->post('use');
            foreach($data as $k => $v){
                if($v=='' && $k!='s_type' && $k!='s_winternet'){
                    $bool = 1;
                    break;
                }else{ 
                    $bool = 2;
                }
            }
            if ($bool == 2) {
                $num = $this->s->server_insert($data);
                if($num){
                    $message['status'] = 1;
                    $message['msg'] = "添加成功";
                }else{
                    $message['status'] = 0;
                    $message['msg'] = "添加失败,请联系管理员";
                }
            }else{
                $message['status'] = 0;
                $message['msg'] = "不能为空";
            }
            echo json_encode($message);
        }
         public function server_del($id){
             //判断是否有使用人 如果有 不让删除 先清空使用人
            $num = $this->so->count_owner($id);
            if($num == 0){
            $row = $this->s->server_delete($id);
           if($row == 1){
                $message['status']=1;
                $message['msg']="删除成功";
            }else{
                $message['status']=0;
                $message['msg']="删除失败,请刷新后重试";
            }
            }else{
                $message['status']=0;
                $message['msg']="删除失败,请删除使用人在删除机器";
            }
            echo json_encode($message);
        }
        public function server_allocate($sn_id,$sa_id,$s_id) {
            $data['s_id'] = $s_id;
            $data['sn_id'] = $sn_id;
            $data['sa_id'] = $sa_id;
            $this->load->view('server/server_allocate_see',$data);
        }
        public function server_allocate_add() {
            $data['sn_id'] = $this->input->post('sn_id');
            $data['account'] = trim($this->input->post('account'));
            $data['pwd'] = trim($this->input->post('pwd'));
            $data['u_id'] = $this->sn->get_uid_by_snid($data['sn_id']);
            $arr = explode('-', $this->input->post('s_id'));
            if($data['account']!='' && $data['pwd']!=''){
                for($i=0;$i<count($arr);$i++){
                    $data['s_id'] = $arr[$i];
                    $num = $this->so->so_insert($data);
                $tempw['s_id'] = $arr[$i];
                $this->db->set('s_owner', 's_owner+1', FALSE);
                 $this->db->where($tempw)->update('server');
                }
            if($num ){
                $message['status'] = 1;
                $message['msg'] = '分配成功';
            }else{
                $message['status'] = 0;
                $message['msg'] = '分配失败，请联系管理员';
            }
            }else{
                $message['status'] = 0;
                $message['msg'] = '分配失败，帐号不能为空';
            }
            echo json_encode($message);
        }
        public function search() {
            $use = $_GET['use'];
            echo json_encode($this->sn->get_use());
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