<?php
defined('BASEPATH') OR exit('No direct scriptm access allowed');

class Project extends CI_Controller {
	// Constructor
	function __construct() {
		parent::__construct();
		
		// Cek apakah ada session username
		$username = $this->session->userdata('username');
        
		// Jika tidak ada atau kosong, arahkan ke halaman login
		if (empty($username)) {
			$this->session->set_flashdata("error", "Login session expired!");
			redirect("login");
		}
	}

	private function __set_result_status($_status) {
		switch($_status->status) {
				//case Done
				case 2:
					$res = $_status->status_name.' on '. date('d F Y', strtotime($_status->tgl_selesai));
				break;
				//case In progress
				case 4:
					$res = $_status->status_name.', will be done before '. date('d F Y', strtotime($_status->deadline));
				break;
				case 6:
					$res = $_status->status_name.', the problem is: '.$_status->problem_details;
				break;
				default:
					$res = $_status->status_name;
				break;
			}
			return $res;
	}
    
    function __switchPage($id_project="",$id_tasks="")  {
            $ajax_url="";
            $pdfHTML5_column="";
            $isEdit="";
            $unorderable="";
        if(!empty($id_project)&&!empty($id_tasks)) {
            $where = array("t_project_progress.id_progress"=>$id_tasks,
                      "t_project.id_project"=>$id_project);
        $join = array(
					array(
						"table"=>"t_project_progress",
						"fkey" => "t_project_progress.id_project=t_project.id_project"
						)
					);
		
		  $project_title = $this->db_model->read("","t_project",$where,"",$join);
        } elseif(!empty($id_project)) {
            $project_title = $this->db_model->read("nama_proyek","t_project","id_project = $id_project");
        }
			
            switch(current_url()) {
                case (base_url('project/details/').$id_project):
                    $ajax_url = base_url('project/list_tasks/').$id_project;
                    $pdfHTML5_column = "[0,1,2,3]";
                    $pdfHTML5_title = "Project ".$project_title[0]->nama_proyek." Tasks Report";
					$unorderable = "[-1,0]";
                    break; 
                case(base_url('project/subtasks/').$id_tasks);
                    $ajax_url = base_url('project/list_subtasks/').$id_tasks.'/true';
                    $pdfHTML5_column = "[0,1,2,3]";
                    $pdfHTML5_title = "Project  Tasks Report";
					$unorderable = "[-1,0]";
                    break; 
                case(base_url('project/my_subtasks'));
                    $ajax_url = base_url('project/list_subtasks/user');
                    $pdfHTML5_column = "[0,1,2,3]";
                    $pdfHTML5_title = "Project  Tasks Report";
					$unorderable = "[]";
                    break;
                case(base_url('project/all_subtasks'));
                    $ajax_url = base_url('project/list_subtasks/');
                    $pdfHTML5_column = "[0,1,2,3]";
                    $pdfHTML5_title = "Project  Tasks Report";
					$unorderable = "[]";
                    break; 
				case (base_url('project/all_tasks')):
                    $ajax_url = base_url('project/list_tasks/');
                    $pdfHTML5_column = "[0,1,2,3]";
                    $pdfHTML5_title = "Project Tasks Report";
                   $unorderable = "[]";
                    break;
				case (base_url('project/my_tasks')):
                    $ajax_url = base_url('project/list_tasks/user');
                    $pdfHTML5_column = "[0,1,2,3]";
                    $pdfHTML5_title = "Project Tasks Report";
					
                    break;
                case (base_url('project')):
                    $ajax_url = base_url('project/list_projects');
                    $pdfHTML5_column = "[0,1,2,3,4,5]";
					$pdfHTML5_title = "Project Report";
					$unorderable = "[-1,-2,0]";
                    break;
                case (base_url('project/project_form/').$id_project):
                    $isEdit=true;
					$pdfHTML5_title = "";              
                    $unorderable = "[]";
                    break;
                default:
                    $ajax_url = null;
					$pdfHTML5_title = "";
                    $unorderable = "[]";
                    break;
            }
            $props=array("ajax_url"=>$ajax_url,"pdfHTML5_column"=>$pdfHTML5_column,"isEdit"=>$isEdit,"pdfHTML5_title"=>$pdfHTML5_title,"unorderable"=>$unorderable);
            return $props;
    } 
    
	function index() {
		// Ambil feedback success dan error
		$data['success'] = $this->session->flashdata("success");
		$data['error'] = $this->session->flashdata("error");
        
        $data['ajax_url']= $this->__switchPage()['ajax_url'];
		$props = $this->__switchPage();
		
		if($this->session->userdata('profiles')['can_alter']==1) {
				$data['can_add'] = true;
			}
		
        $data['pdfHTML5_column']= $props['pdfHTML5_column'];
        $data['pdfHTML5_title']= $props['pdfHTML5_title'];
        $data['unorderable']= $props['unorderable'];
        
		$data['level'] = $this->session->userdata('level');
		
		$data['view'] = "project/v_list";
		$this->load->view("index", $data);
	}
    
    //End of main function
	
	function download($id,$table_name) {
        
        $file_query = "";
        $table = "";
        $id_download = "";
        
        switch($table_name) {
            case "0":
                $table = "t_project";
                $id_download = "id_project";
            break;
            case "1":
                $table = "t_project_progress";
                $id_download = "id_progress";
            break;
            case "2":
                $table = "t_project_subprogress";
                $id_download = "id_subprogress";
            break;
        }
        
		$file_query = $this->db_model->read("attachment",$table,"$id_download = '$id'");
		$filePath = 'media/uploads/'.$file_query[0]->attachment; // of course find the exact filename....

		if(file_exists($filePath)) {
			$fileName = basename($filePath);
			$fileSize = filesize($filePath);

			// Output headers.
			header("Cache-Control: private");
			header("Content-Type: application/stream");
			header("Content-Length: ".$fileSize);
			header("Content-Disposition: attachment; filename=".$fileName);

			// Output file.
			readfile ($filePath);                   
			exit();
		}
		else {
			die('The provided file path is not valid.');
		}
	}
	
	function all_tasks() {
        $data['ajax_url']= $this->__switchPage()['ajax_url'];
        $data['pdfHTML5_column']= $this->__switchPage()['pdfHTML5_column'];
        $data['pdfHTML5_title']= $this->__switchPage()['pdfHTML5_title'];
        
        
        $data['success'] = $this->session->flashdata("success");
		$data['error'] = $this->session->flashdata("error");
        
		$data['isAllTask'] = true;
		$data['isMyTask'] = false;
		$data['noAct'] = true;
		
        $data['nama_proyek'] = 'Semua Tugas';
		

		if($this->session->userdata('profiles')['can_alter']==1) {
			$data['can_add'] = true;
		}
		if($this->session->userdata('profiles')['alter_other']==1) {
			$data['can_add'] = true;
		}

        
        $data['level'] = $this->session->userdata('level');
		$data['view'] = "project/v_details";
		
		$this->load->view("index",$data);
        $this->session->set_flashdata("success","");
        $this->session->set_flashdata("error","");
    }
    
    function subtasks($project_task_id) {
        
        $where = array("t_project_progress.id_progress"=>$project_task_id);
        $join = array(
					array(
						"table"=>"t_project_progress",
						"fkey" => "t_project_progress.id_project=t_project.id_project"
						)
					);
		
		$judul = $this->db_model->read("","t_project",$where,"",$join);
		if(empty($judul)) exit("404 Page Not Found");
		
        
        $data['ajax_url']= $this->__switchPage($judul[0]->id_project, $project_task_id)['ajax_url'];
        $data['pdfHTML5_column']= $this->__switchPage($judul[0]->id_project, $project_task_id)['pdfHTML5_column'];
        $data['pdfHTML5_title']= $this->__switchPage($judul[0]->id_project, $project_task_id)['pdfHTML5_title'];
        $data['unorderable']= $this->__switchPage($judul[0]->id_project, $project_task_id)['unorderable'];

        $data['success'] = $this->session->flashdata("success");
		$data['error'] = $this->session->flashdata("error");
        
        $data['id_progress'] = $judul[0]->id_progress;
        $data['id_project'] = $judul[0]->id_project;
        $data['isMyTask'] = false;
        $data['noAct'] = false;
        $data['showbar'] = true;
        $data['isSubtasks'] = true;
		
        $data['nama_proyek'] = $judul[0]->nama_proyek;
        $data['judul'] = $judul[0]->bagian;
        $data['pic'] = $judul[0]->pic;
		
		$progress_bar = $this->db_model->read_procedure("bar_subsource(".$judul[0]->id_progress.")")[0];
		
		if($progress_bar->total!=0) {
			$data['done'] = ($progress_bar->selesai/$progress_bar->total)*100;
			$data['in_progress'] = ($progress_bar->in_progress/$progress_bar->total)*100;
			$data['review'] = ($progress_bar->review/$progress_bar->total)*100;
			$data['canceled'] = ($progress_bar->canceled/$progress_bar->total)*100;
			$data['in_problem'] = ($progress_bar->in_problem/$progress_bar->total)*100;
			$data['nihil'] = ($progress_bar->nihil/$progress_bar->total)*100;
		} else {
			 $data['showbar'] = false;
		}
		
        $data['level'] = $this->session->userdata('level');
		$data['view'] = "project/v_details";

		$this->load->view("index",$data);
        $this->session->set_flashdata("success","");
        $this->session->set_flashdata("error","");
    }
	
	function my_tasks() {
        $data['ajax_url']= $this->__switchPage()['ajax_url'];
        $data['pdfHTML5_column']= $this->__switchPage()['pdfHTML5_column'];
        $data['pdfHTML5_title']= $this->__switchPage()['pdfHTML5_title'];
        
        $data['success'] = $this->session->flashdata("success");
		$data['error'] = $this->session->flashdata("error");
        
		$data['isAllTask'] = true;
		$data['isMyTask'] = true;
		$data['noAct'] = true;
		
        $data['nama_proyek'] = 'Tugas Saya';
        
        $data['level'] = $this->session->userdata('level');
		$data['view'] = "project/v_details";
		
		$this->load->view("index",$data);
        $this->session->set_flashdata("success","");
        $this->session->set_flashdata("error","");
    }
    
    function my_subtasks() {
        $data['ajax_url']= $this->__switchPage()['ajax_url'];
        $data['pdfHTML5_column']= $this->__switchPage()['pdfHTML5_column'];
        $data['pdfHTML5_title']= $this->__switchPage()['pdfHTML5_title'];
            $data['unorderable']= $this->__switchPage()['unorderable'];
        
        $data['success'] = $this->session->flashdata("success");
		$data['error'] = $this->session->flashdata("error");
        
		$data['isAllTask'] = false;
		$data['isMyTask'] = false;
		$data['noAct'] = true;
        $data['isMySubtask'] = "yes";
		
        $data['nama_proyek'] = 'Subtugas Saya';
        
        $data['level'] = $this->session->userdata('level');
		$data['view'] = "project/v_details";
		
		$this->load->view("index",$data);
        $this->session->set_flashdata("success","");
        $this->session->set_flashdata("error","");
    }
    
    function all_subtasks() {
        $data['ajax_url']= $this->__switchPage()['ajax_url'];
        $data['pdfHTML5_column']= $this->__switchPage()['pdfHTML5_column'];
        $data['pdfHTML5_title']= $this->__switchPage()['pdfHTML5_title'];
            $data['unorderable']= $this->__switchPage()['unorderable'];
        
        $data['success'] = $this->session->flashdata("success");
		$data['error'] = $this->session->flashdata("error");
        
		$data['isAllTask'] = false;
		$data['isMyTask'] = false;
		$data['noAct'] = true;
        $data['isAllSubtask'] = "yes";
		
        $data['nama_proyek'] = 'Semua Subtugas';
        
        $data['level'] = $this->session->userdata('level');
		$data['view'] = "project/v_details";
		
		$this->load->view("index",$data);
        $this->session->set_flashdata("success","");
        $this->session->set_flashdata("error","");
    }
	
	function details($id) {
		
		$judul = $this->db_model->read("","t_project","id_project = '$id'");
		if(empty($judul)) exit("404 Page Not Found");
		
        $data['ajax_url']= $this->__switchPage($id)['ajax_url'];
        $data['pdfHTML5_column']= $this->__switchPage($id)['pdfHTML5_column'];
        $data['pdfHTML5_title']= $this->__switchPage($id)['pdfHTML5_title'];
        $data['unorderable']= $this->__switchPage($id)['unorderable'];

        $data['success'] = $this->session->flashdata("success");
		$data['error'] = $this->session->flashdata("error");
        
        $data['id_project'] = $id;
        $data['subtask'] = false;
        $data['isMyTask'] = false;
        $data['noAct'] = false;
        $data['showbar'] = true;
        $data['isSubtasks'] = false;
		
        $data['nama_proyek'] = $judul[0]->nama_proyek;
        $data['pic'] = $judul[0]->pic;

			 $data['showbar'] = false;

		
        $data['level'] = $this->session->userdata('level');
		$data['view'] = "project/v_details";

		$this->load->view("index",$data);
        $this->session->set_flashdata("success","");
        $this->session->set_flashdata("error","");
    }
    
    function do_tambah_proyek(){
		$post = $this->input->post(NULL, TRUE);
		
		$this->db_model->create("t_project",$post);
		$this->session->set_flashdata("success", "Berhasil menambahkan proyek");
		//Mengarahkan ke suatu halaman
        redirect('project');
	}
    
    function do_tambah_subtask($id) {
		$post = $this->input->post(NULL, TRUE);
        $id_progress = $post['id_progress'];

		$this->db_model->create("t_project_subprogress",$post);
		$this->session->set_flashdata("success", "Berhasil menambahkan proyek");
		//Mengarahkan ke suatu halaman
        redirect('project/subtasks/'.$id_progress);
	}
	
	function do_tambah_task($id_project){
		$post = $this->input->post(NULL, TRUE);

		
		$this->db_model->create("t_project_progress",$post);
		$this->session->set_flashdata("success", "Berhasil menambahkan proyek");
		//Mengarahkan ke suatu halaman
        redirect('project/details/'.$id_project);
	}
	
	function delete_project($id) {
		$this->db_model->delete("t_project", array("id_project"=>$id));
		$this->session->set_flashdata("success", "Berhasil menghapus proyek $id");
		redirect("project");
	}
	
    function delete_task($id) {
        $query = $this->db_model->read("id_project", "t_project_progress", "id_progress='$id'")[0];
		$this->db_model->delete("t_project_progress", array("id_progress"=>$id));
		$this->session->set_flashdata("success", "Berhasil menghapus task $id");
		redirect('project/details/'.$query->id_project);
	}
    
    function delete_subtask($id) {
        $query = $this->db_model->read("id_progresss", "t_project_subprogress", "id_subprogress='$id'")[0];
		$this->db_model->delete("t_project_subprogress", array("id_subprogress"=>$id));
		$this->session->set_flashdata("success", "Berhasil menghapus task $id");
		redirect('project/subtask/'.$query->id_progress);
	}
	
	function edit_task($id) {
        $post = $this->input->post(NULL, TRUE);
        $id_project = $post['id_project'];
		
		$this->db_model->update("t_project_progress",array("id_progress"=>$id),$post);
		$this->session->set_flashdata("success", "Berhasil menyunting tugas");
		//Mengarahkan ke suatu halaman
        redirect('project/details/'.$id_project);
	}	
    
    function edit_subtask($id) {
        $post = $this->input->post(NULL, TRUE);
        $id_progress = $post['id_progress'];
		
		$this->db_model->update("t_project_subprogress",array("id_subprogress"=>$id),$post);
		$this->session->set_flashdata("success", "Berhasil menyunting subtugas");
		//Mengarahkan ke suatu halaman
        redirect('project/subtasks/'.$id_progress);
	}
    
    function edit_project($id) {
        $post = $this->input->post(NULL, TRUE);
        $id_proyek = $post['id_project'];
		
		$query = $this->db_model->read("*", "t_project", "id_project='$id'")[0];
		
		
		$this->db_model->update("t_project",array("id_project"=>$id),$post);
		$this->session->set_flashdata("success", "Berhasil menyunting proyek");
		//Mengarahkan ke suatu halaman
        redirect('project');
	}
    
    function subtask_form($id_progress="", $id="") {  
        $data['isEdit']= $this->__switchPage($id)['isEdit'];
        if(!empty($id)) {
            $data['result'] = $this->db_model->read("*","t_project_subprogress","id_subprogress = '$id'")[0];
            $data['form_edit'] = TRUE;
            $data['id_subprogress'] = $data['result']->id_subprogress;
        }
        $judul = $this->db_model->read("*","t_project_progress","id_progress = '$id_progress'");
		
        $data['judul'] = $judul[0]->bagian;
        
        $data['subtask'] = true;
        $data['element'] = 'attachment';
		$data['uploadlabel'] = "Drop file here to upload (or click here to browse). When you upload no file, your current attachment will not changed.";
        $data['id_progress'] = $id_progress;
        
        $data['level'] = $this->session->userdata('level');
		$data['view'] = "project/v_details_form";
		
		$this->load->view("index",$data);
    }
    
    function details_form($id_proyek="", $id="") {  
        $data['isEdit']= $this->__switchPage($id)['isEdit'];
        if(!empty($id)) {
            $data['result'] = $this->db_model->read("*","t_project_progress","id_progress = '$id'")[0];
            $data['form_edit'] = TRUE;
        }
        $judul = $this->db_model->read("*","t_project","id_project = '$id_proyek'");
		
		$data['range'] = array("start_date"=>$judul[0]->start_date,
                              "deadline"=>$judul[0]->deadline);
        $data['judul'] = $judul[0]->nama_proyek;
        
        $data['subtask'] = false;
        $data['element'] = 'attachment';
		$data['uploadlabel'] = "Drop file here to upload (or click here to browse). When you upload no file, your current attachment will not changed.";
        $data['id_project'] = $id_proyek;
        $data['level'] = $this->session->userdata('level');
		$data['view'] = "project/v_details_form";
		
		$this->load->view("index",$data);
    }
    
    function project_form($id="") {
        $data['isEdit']= $this->__switchPage($id)['isEdit'];
        if(!empty($id)) {
            $result = $this->db_model->read("*","t_project","id_project = '$id'");
			
            $data['result'] = $result[0];
            $data['form_edit'] = TRUE;
        } else {
            $data['result'] = "";
        }
		
        $data['element'] = 'attachment';
		$data['uploadlabel'] = "Drop file here to upload (or click here to browse). When you upload no file, your current attachment will not changed.";
        $data['level'] = $this->session->userdata('level');
		$data['view'] = "project/v_project_form";
		
		$this->load->view("index",$data);
    }
    //List Projects Function
    
	//read params: $select $table $where $order $join, $contains
    public function list_projects() {
		$edit_btn = "";
		$del_btn = "";
		$join = array(
					array(
						"table"=>"t_user",
						"fkey" => "t_project.pic=t_user.id_user"
						)
					);
        $list = $this->db_model->read("*","t_project","","",$join,true);
        $data = array();
        $access = $this->users->access($this->session->userdata('level'));
        $no = 0;
        $i=0;
        if(count($list)>0) {
            foreach ($list as $result) {
			$download = "";
			$edit_btn = "";
			$del_btn = "";
            $no++;
            
            $total_per = 0;
            $row = array();
            $row[] = $result->id_project;
            $row[] = $result->nama_proyek;
            $row[] = date("d-m-Y", strtotime($result->start_date));
            $row[] = date("d-m-Y", strtotime($result->deadline));
            $row[] = $result->fullname;
            
            $total_tasks = $this->db_model->read("*","t_project_progress",array("id_project"=>$result->id_project));
            
            foreach($total_tasks as $tasks) {
                $task_completed = $this->db_model->read("cek_subprogres(id_progress) as percent","t_project_progress",array("id_project"=>$result->id_project))[0];
                $total_per += $task_completed->percent;
                $i++;
            }
            
            $tot = $total_per>0?($total_per/$i):0;
            $row[] = "<div class=\"progress\">
                                          <div class=\"progress-bar\" role=\"progressbar\" aria-valuenow=\"".$tot."\" aria-valuemin=\"0\" aria-valuemax=\"100\" style=\"width:".$tot."%\">
                                            ". round($tot)."%
                                          </div>
                                        </div>";
 
            //add html for action
			
			if(!empty($result->attachment)&&file_exists('media/uploads/'.$result->attachment)) $download = '<a class="btn btn-success"  href="'.base_url("project/download/"). $result->id_project.'/0"><i class="fa fa-download"></i> Unduh</a>';
            
            if($result->pic==$this->session->userdata('id_user')&&(date("Y-m-d")<=date("Y-m-d",strtotime($result->deadline)))) {
				if($access->project_update=="1") {
					$edit_btn = "<a class='btn btn-warning'  href='".base_url("project/project_form/"). $result->id_project."'><i class='glyphicon glyphicon-pencil'></i> Sunting</a>";
				}
				if($access->project_delete=="1") {
					$del_btn = "<a href='".base_url("project/delete_project/")."' id='delete' class='btn btn-danger'><i class='glyphicon glyphicon-trash'></i> Delete</a>";
				}
			} else {
				if($access->project_update_other=="1") {
					$edit_btn = "<a class='btn btn-warning'  href='".base_url("project/project_form/"). $result->id_project."'><i class='glyphicon glyphicon-pencil'></i> Sunting</a>";
				}
				if($access->project_delete_other=="1") {
					$del_btn = "<a href='".base_url("project/delete_project/")."' id='delete' class='btn btn-danger'><i class='glyphicon glyphicon-trash'></i> Delete</a>
                    ";
				}
			}
            
            /*
                DEFINISI MANTAN
                ------------------
                M = Mantan namanya juga
                A = Ada kenangan indah bersamanya
                N = Ninggalin gw karena sesuatu
                T = Tidak bersamanya lagi sekarang
                A = Agak jaim-jaiman kalo ketemu
                N = Ngarep pen balikan tapi malu u,u
                

            */
			
			
            $row[] = '<a class="btn btn-primary"  href="'.base_url("project/details/"). $result->id_project.'"><i class="glyphicon glyphicon-book"></i> Rincian</a> '.$download." ".$edit_btn." ".$del_btn;

            $data[] = $row;
        }
        }
        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => count($list),
                        "recordsFiltered" => count($list),
                        "data" => $data,
                );
        echo json_encode($output);
    }
    
    public function list_subtasks($id="") {
        $edit_btn = "";
		$del_btn = "";
        $show_fullname=false;
        $access = $this->users->access($this->session->userdata('level'));
		$join = array(
					
					
					
                    array(
						"table"=> "t_project_progress",
						"fkey" => "t_project_progress.id_progress=t_project_subprogress.id_progress"
						),
                    array(
						"table"=> "t_project",
						"fkey" => "t_project_progress.id_project = t_project.id_project"
						),
                    array(
						"table"=> "t_user",
						"fkey" => "t_project_subprogress.pic = t_user.id_user"
						),
                    array(
						"table"=> "t_project_status",
						"fkey" => "t_project_subprogress.status=t_project_status.id_pstatus"
						)
					);
        
 
        if(is_numeric($id)) {   
           $list = $this->db_model->read("t_project_subprogress.attachment as attch ,t_project_subprogress.id_subprogress,t_project.id_project, nama_proyek, t_project_subprogress.bagian, fullname, t_project_subprogress.status, status_name, t_project_progress.deadline, t_project_subprogress.tgl_selesai, t_project_subprogress.problem_details, id_user, t_project_subprogress.id_progress","t_project_subprogress","t_project_subprogress.id_progress = '$id'","",$join,true);
			$show_fullname = true;
			$action = true;
		} elseif($id=='user') {
            $where = array("t_project_subprogress.pic"=>$this->session->userdata('id_user'));
			$list = $this->db_model->read("t_project_subprogress.id_subprogress, t_project.id_project, t_project_subprogress.bagian,  t_project_subprogress.status, status_name, t_project_progress.deadline, t_project_subprogress.tgl_selesai, t_project_subprogress.problem_details","t_project_subprogress",$where,"",$join,true);
		} elseif(empty($id)) {
			$list = $this->db_model->read("t_project_subprogress.attachment as attch ,t_project_subprogress.id_subprogress,t_project.id_project, nama_proyek, t_project_subprogress.bagian, fullname, t_project_subprogress.status, status_name, t_project_progress.deadline, t_project_subprogress.tgl_selesai, t_project_subprogress.problem_details, id_user, t_project_subprogress.id_progress","t_project_subprogress","","",$join,true);
			$show_fullname = true;
		}
        
        $data = array();
        $no = 0;
        foreach ($list as $result) {
            $row = array();
            $no++;
            $download="";
            $row[] = $result->id_subprogress;
            $row[] = $result->bagian;
            if(is_numeric($id)||$show_fullname==true) $row[] = $result->fullname;
            $row[] = $this->__set_result_status($result);
            
            if(!@$noAct) {   
                if(file_exists('media/uploads/'.@$result->attch)&&!empty(@$result->attch)) $download = '<a class="btn btn-success"  href="'.base_url("project/download/"). @$result->id_subprogress.'/2"><i class="fa fa-download"></i> Unduh</a>';
                
				if($this->session->userdata('id_user')==@$result->id_user) {
					if($access->task_update=="1") {
						$edit_btn = "<a class='btn btn-warning' href='". site_url("project/subtask_form/") . @$result->id_progress."/".@$result->id_subprogress."'><i class='fa fa-book'></i> Sunting</a>";
					}
					if($access->task_update_other=="1") {
						$del_btn = "<a id='delete' class='btn btn-danger' href='". site_url("project/delete_subtask/")."'><i class='fa fa-trash'></i> Hapus</a>";
					}
				} else {
					if($access->task_delete=="1") {
						$edit_btn = "<a class='btn btn-warning' href='". site_url("project/subtask_form/") . @$result->id_progress."/".@$result->id_subprogress."'><i class='fa fa-book'></i> Sunting</a>";
					}
					if($access->task_delete_other=="1") {
						$del_btn = "<a id='delete' class='btn btn-danger' href='". site_url("project/delete_subtask/")."'><i class='fa fa-trash'></i> Hapus</a>";
					}

				}

				
				$row[] = $download." ".$edit_btn." ".$del_btn;
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
    
    private function antiZero($div1,$div2) {
        if(($div1>0)&&($div2>0)) {
            return ($div1/$div2)*100;
        } else {
            return 0;
        }
    }
    
    function generateHTMLStacked($details,$withCaption) {
        $html = '<?php if(@$showbar) { ?>
					  <div class="progress">
						<div class="progress-bar progress-bar-success" role="progressbar" style="width:'.$this->antiZero($details->selesai,$details->total).'%">
						  '.($withCaption?'Done':'').'
						</div>
						<div class="progress-bar progress-bar-warning" role="progressbar" style="width:'.$this->antiZero($details->in_progress,$details->total).'%">
						  '.($withCaption?'In progress':'').'
						</div>
					   <div class="progress-bar progress-bar-info" role="progressbar" style="width:'.$this->antiZero($details->review,$details->total).'%">
						  '.($withCaption?'Review':'').'
						</div>
						<div class="progress-bar progress-bar-danger" role="progressbar" style="width:'.$this->antiZero($details->canceled,$details->total).'%">
						  '.($withCaption?'Canceled':'').'
						</div>
					<div class="progress-bar progress-bar-warning progress-bar-striped" role="progressbar" style="width:'.$this->antiZero($details->in_problem,$details->total).'%">
						  '.($withCaption?'In problem':'').'
						</div>
					<div class="progress-bar progress-bar-blank" role="progressbar" style="width:'.$this->antiZero($details->nihil,$details->total).'%">
						  '.($withCaption?'Nihil':'').'
						</div>
					  </div>
					<?php } ?>';
        return $html;
    }

    //List project tasks function
    public function list_tasks($id="") {
		$edit_btn = "";
		$del_btn = "";
        $access = $this->users->access($this->session->userdata('level'));
		$join = array(
					array(
						"table"=> "t_user",
						"fkey" => "t_project_progress.pic = t_user.id_user"
						),
					array(
						"table"=> "t_project",
						"fkey" => "t_project_progress.id_project = t_project.id_project"
						),
					array(
						"table"=> "t_project_status",
						"fkey" => "t_project_progress.status=t_project_status.id_pstatus"
						)
					);
        
		if(is_numeric($id)) {   
			$list = $this->db_model->read("cek_subprogres(t_project_progress.id_progress) as percent, t_project.id_project, nama_proyek, bagian, fullname, t_project_progress.pic, id_progress, t_project_progress.attachment","t_project_progress","t_project_progress.id_project = '$id'","",$join,true);
			$show_fullname = true;
			$action = true;
		} elseif($id=='user') {
			$list = $this->db_model->read("t_project.id_project, nama_proyek, bagian,id_progress","t_project_progress","t_project_progress.pic = '".$this->session->userdata('profiles')['id_user']."'","",$join,true);
			
		} else {
			$list = $this->db_model->read("t_project.id_project, nama_proyek, bagian, fullname, status, status_name, t_project_progress.deadline, tgl_selesai, problem_details,id_progress","t_project_progress","","",$join,true);
			$show_fullname = true;
		}
        
        
        $data = array();
        $no = 0;
        foreach ($list as $result) {
			$edit_btn = "";
			$del_btn = "";
            $download= "";
            $no++;
            $row = array();
            $row[] = $result->id_progress;
			if(!is_numeric($id)) $row[] = $result->nama_proyek;
            $row[] = $result->bagian;
            if(empty($id)||is_numeric($id)) $row[] = $result->fullname;
            
            if(is_numeric($id)) { 
                $progress_bar = $this->db_model->read_procedure("bar_subsource(".$result->id_progress.")")[0];

			     $row[] = $this->generateHTMLStacked($progress_bar,false)."<div class=\"progress\">
                                          <div class=\"progress-bar\" role=\"progressbar\" aria-valuenow=\"".($result->percent)."\" aria-valuemin=\"0\" aria-valuemax=\"100\" style=\"width:".$result->percent."%\">
                                            ". round($result->percent)."%
                                          </div>
                                        </div>";
           }
                
                
            if(@$action) {
                if(!empty($result->attachment)&&file_exists('media/uploads/'.$result->attachment)) $download = '<a class="btn btn-success"  href="'.base_url("project/download/"). $result->id_progress.'/1"><i class="fa fa-download"></i> Unduh</a>';
                
				if($this->session->userdata('id_user')==$result->pic) {
					if($access->task_update=="1") {
						$edit_btn = "<a class='btn btn-warning' href='". site_url("project/details_form/") . $result->id_project."/".$result->id_progress."'><i class='fa fa-book'></i> Sunting</a>";
					}
					if($access->task_update_other=="1") {
						$del_btn = "<a id='delete' class='btn btn-danger' href='". site_url("project/delete_task/")."'><i class='fa fa-trash'></i> Hapus</a>";
					}
				} else {
					if($access->task_delete=="1") {
						$edit_btn = "<a class='btn btn-warning' href='". site_url("project/details_form/") . $result->id_project."/".$result->id_progress."'><i class='fa fa-book'></i> Sunting</a>";
					}
					if($access->task_delete_other=="1") {
						$del_btn = "<a id='delete' class='btn btn-danger' href='". site_url("project/delete_task/")."'><i class='fa fa-trash'></i> Hapus</a>";
					}

				}

				
				$row[] = '<a class="btn btn-primary"  href="'. site_url("project/subtasks/").$result->id_progress.'"><i class="glyphicon glyphicon-book"></i> Rincian</a>'." ".$download." ".$edit_btn." ".$del_btn;
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
