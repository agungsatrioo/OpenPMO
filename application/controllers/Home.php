<?php
defined('BASEPATH') OR exit('No direct scripts access allowed');

class Home extends CI_Controller {	
	function __construct()  {
		parent::__construct();
		
		if(!empty($this->session->userdata('username'))) {
            $data['username'] = $this->session->userdata('username');
            $data['level'] = $this->session->userdata('level');
        }
        
        if(empty(  $data['username'] ))  {
			$this->session->set_flashdata("error", "Login session expired!");
			redirect("login");
		}
		
		$priv_where = array("username" => $this->session->userdata('username'));
		$rs = $this->db_model->read("*","t_user",$priv_where);
		
			$profiles = array(
				"fullname" => $rs[0]->fullname,
				"about_me" => $rs[0]->about_me,
				"img" => $rs[0]->img
			);

			$this->session->set_userdata("dashboard", $profiles);
	}
	function index() {

		$list = $this->db_model->read("desc","t_user_level","id_level = ".$this->session->userdata('level'));
		
		$myProjs = $this->db_model->read("*","t_project_progress","pic = ".$this->session->userdata('profiles')['id_user']);
		
		$where = array('pic' => $this->session->userdata('profiles')['id_user'], 'status !=' => '2');

		$myProjs2 = $this->db_model->read("*","t_project_progress",$where);

		
		$data['myprojs'] = count($myProjs);
		$data['unfinprojs'] = count($myProjs2);
		$data['desc'] = $list[0]->desc;
		
		$data['view'] = "v_dashboard";
		$this->load->view("index", $data);
	}
    function profile() {
        $data['view'] = "v_dashboard";
		$this->load->view("index", $data);
    }
	
	function chartSource() {
		error_reporting(0);
		//select tgl_selesai, count(*) as 'hitungan' from t_project_progress where status=2 and pic=2 group by date(tgl_selesai)
		$jsonArray = array();
		$where = array('pic' => $this->session->userdata('id_user'), 'status' => '2');
		$list = $this->db_model->read("tgl_selesai, count(*) as hitungan","t_project_subprogress",$where,"","",false,"date(tgl_selesai)");
		
		$data = array();
		foreach ($list as $row) {
			$data[] = $row;
		}
		echo json_encode($data);
	}
}