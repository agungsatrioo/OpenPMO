<?php

class User extends CI_Controller {

	function __construct() {
		parent::__construct();
		
		// Cek apakah ada session username
		$username = $this->session->userdata('username');
        $level   = $this->session->userdata('level');
        
		if($level!=1) exit("403 Forbidden");
        
		// Jika tidak ada atau kosong, arahkan ke halaman login
		if (empty($username)) {
			$this->session->set_flashdata("error", "Login session expired!");
			redirect("login");
		}
	}

	function index(){
        $data['ajax_url']= base_url('user/ajaxUsers');
        $data['pdfHTML5_column']="[0,1,2,3]";
        $data['pdfHTML5_title']= "User List";
        $data['unorderable']= "[-1,2,0,3,4,5,6,7,8,9,10,11]";
        $data['view'] = "user/v_userlist";
        $data['success'] = $this->session->flashdata("success");
		$data['error'] = $this->session->flashdata("error");
		$this->load->view("index", $data);
    }
    
    function priv_setup() {
        
    }

	function do_add_user(){
		$post = $this->input->post(NULL, TRUE);
		if(strlen($post['password'])<=32) {
			$psstemp = password_hash($post['password'], PASSWORD_DEFAULT);
			$post['password'] = $psstemp;
		}
		$this->db_model->create("t_user",$post);
		$this->session->set_flashdata("success", "Berhasil menambahkan pengguna");
		//Mengarahkan ke suatu halaman
        redirect('user');
	}

	function edit_user($id) {
        $post = $this->input->post(NULL, TRUE);
		if(strlen($post['password'])<=32) {
			$psstemp = password_hash($post['password'], PASSWORD_DEFAULT);
			$post['password'] = $psstemp;
		}
		$this->db_model->update("t_user","id_user='$id'",$post);
		$this->session->set_flashdata("success", "Berhasil menyunting pengguna");
		//Mengarahkan ke suatu halaman
        redirect('user');
	}
	
	function delete_user($id) {
		$this->db_model->delete("t_user", array("id_user"=>$id));
		$this->session->set_flashdata("success", "Berhasil menghapus user $id");
		redirect("user");
        
	}
	

	function user_form($id="") {
		$data['uploadimage'] = true;
		$data['uploadlabel'] = "Drop image here to upload (or click here to browse). When you upload no file, your current profile photo will not changed.";
		$data['element'] = 'imge';
        $data['isEdit']= true;
        if(!empty($id)) {
            $result = $this->db_model->read("*","t_user","id_user = '$id'");
			
            $data['result'] = $result[0];
            $data['form_edit'] = TRUE;
        } else {
            $data['result'] = "";
        }
        
        $data['level'] = $this->session->userdata('level');
		$data['view'] = "user/v_user_form";
		
		$this->load->view("index",$data);
    }
    
    function generateCard($queryResult) {
        
        $projects = count($this->db_model->read("*","t_project","pic={$queryResult->id_user}"));
        $tasks = count($this->db_model->read("*","t_project_progress","pic={$queryResult->id_user}"));
        $subtasks = count($this->db_model->read("*","t_project_subprogress","pic={$queryResult->id_user}"));
        
        return '<div class="twPc-div">
	<div>
		<a class="twPc-avatarLink">
			<img alt="Mert Salih Kaplan" src="'.base_url('media/uploads/').(!empty($queryResult->img)?$queryResult->img:"unknown.png").'" class="twPc-avatarImg">
		</a>
		<div class="twPc-divUser">
			<div class="twPc-divName">
				<a>'.$queryResult->fullname.'</a>
			</div>
			<span>
				<a><span>'.$queryResult->username.'</span></a>
			</span>
		</div>
		<div class="twPc-divStats">
			<ul class="twPc-Arrange">
				<li class="twPc-ArrangeSizeFit">
					<a>
						<span class="twPc-StatLabel twPc-block">Project(s)</span>
						<span class="twPc-StatValue">'.$projects.'</span>
					</a>
				</li>
				<li class="twPc-ArrangeSizeFit">
					<a>
						<span class="twPc-StatLabel twPc-block">Task(s)</span>
						<span class="twPc-StatValue">'.$tasks.'</span>
					</a>
				</li>
				<li class="twPc-ArrangeSizeFit">
					<a>
						<span class="twPc-StatLabel twPc-block">Subtask(s)</span>
						<span class="twPc-StatValue">'.$subtasks.'</span>
					</a>
				</li>
			</ul>
		</div>
	</div>
</div>';
    }
    
    function drawLevel($id_user,$type) {
        //Defines icons
        $allow = "fa fa-check";
        $half = "fa fa-adjust";
        $restrict = "fa fa-close";
        //End of defitions
        $userFetch = $this->db_model->read("level","t_user","id_user = $id_user")[0];
        $level = $this->users->access($userFetch->level);
        
        switch($type) {
            case "project_create":
                if($level->project_create=="1") {
                    $icon = $allow;
                } else {
                    $icon = $restrict;
                }
                break;
            case "project_update":
                $count = $level->project_update+$level->project_update_other;
                switch($count) {
                    case "2":
                        $icon = $allow;
                    break;
                    case "1":
                        $icon = $half;
                    break;
                    case "0":
                        $icon = $restrict;
                    break;
                }
                break;
            case "project_delete":
                 $count = $level->project_delete+$level->project_delete_other;
                switch($count) {
                    case "2":
                        $icon = $allow;
                    break;
                    case "1":
                        $icon = $half;
                    break;
                    case "0":
                        $icon = $restrict;
                    break;
                }
                break;
            case "task_create":
                if($level->task_create=="1") {
                    $icon = $allow;
                } else {
                    $icon = $restrict;
                }
                break;
            case "task_update":
                 $count = $level->task_update+$level->task_update_other;
                switch($count) {
                    case "2":
                        $icon = $allow;
                    break;
                    case "1":
                        $icon = $half;
                    break;
                    case "0":
                        $icon = $restrict;
                    break;
                }
                break;
            case "task_delete":
                 $count = $level->task_delete+$level->task_delete_other;
                switch($count) {
                    case "2":
                        $icon = $allow;
                    break;
                    case "1":
                        $icon = $half;
                    break;
                    case "0":
                        $icon = $restrict;
                    break;
                }
                break;
            case "subtask_create":
                if($level->subtask_create=="1") {
                    $icon = $allow;
                } else {
                    $icon = $restrict;
                }
                break;
            case "subtask_update":
                 $count = $level->subtask_update+$level->subtask_update_other;
                switch($count) {
                    case "2":
                        $icon = $allow;
                    break;
                    case "1":
                        $icon = $half;
                    break;
                    case "0":
                        $icon = $restrict;
                    break;
                }
                break;
            case "subtask_delete":
                 $count = $level->subtask_delete+$level->subtask_delete_other;
                switch($count) {
                    case "2":
                        $icon = $allow;
                    break;
                    case "1":
                        $icon = $half;
                    break;
                    case "0":
                        $icon = $restrict;
                    break;
                }
                break;
                
        }
        return "<h4><i class='{$icon}' id='tipster' title='Test'></i></h4>";
    }

	function ajaxUsers() {

		$join = array(
					array(
						"table"=>"t_user_level",
						"fkey" => "t_user.level=t_user_level.id_level"
						)
					);

         $list = $this->db_model->read("*","t_user","","id_user",$join,true);
        $data = array();
        $no = 0;
        foreach ($list as $result) {
            $no++;
            $row = array();
            $row[] = $result->id_user;
            $row[] = $this->generateCard($result);
            $row[] = $result->desc;
            
            $row[] = $this->drawLevel($result->id_user,"project_create");
            $row[] = $this->drawLevel($result->id_user,"project_update");
            $row[] = $this->drawLevel($result->id_user,"project_delete"); 
            
            $row[] = $this->drawLevel($result->id_user,"task_create");
            $row[] = $this->drawLevel($result->id_user,"task_update");
            $row[] = $this->drawLevel($result->id_user,"task_delete");
            
            $row[] = $this->drawLevel($result->id_user,"subtask_create");
            $row[] = $this->drawLevel($result->id_user,"subtask_update");
            $row[] = $this->drawLevel($result->id_user,"subtask_delete");
            
			
			if(($result->username!=$this->session->userdata('username'))) {
				if($result->username==$this->session->userdata('username')) {
					if($this->session->userdata('profiles')['can_alter']==1) {
						$edit_btn = "<a class='btn btn-warning' href='". site_url("user/user_form/") . $result->id_user."'><i class='fa fa-book'></i> Sunting</a>";
					}
					if($this->session->userdata('profiles')['can_delete']==1) {
						$del_btn = "<a id='delete' class='btn btn-danger' href='". site_url("user/delete_user/")."'><i class='fa fa-trash'></i> Hapus</a>";
					}
				} else {
					if($this->session->userdata('profiles')['alter_other']==1) {
						$edit_btn = "<a class='btn btn-warning' href='". site_url("user/user_form/") . $result->id_user."'><i class='fa fa-book'></i> Sunting</a>";
					}
					if($this->session->userdata('profiles')['delete_other']==1) {
						$del_btn = "<a id='delete' class='btn btn-danger' href='". site_url("user/delete_user/") ."'><i class='fa fa-trash'></i> Hapus</a>
                        
                        ";
					}
				}
				$row[] = " ".$edit_btn."<br>".$del_btn;
			} else {
				$row[] = "";
			}
			
            

            $data[] = $row;
        }
        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => count($list),
                        "recordsFiltered" => count($list),
                        "data" => $data,
                );
        echo json_encode($output);
    }
   }
