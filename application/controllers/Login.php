<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {
	function index() {
		// Ambil feedback success dan error
		$data['success'] = $this->session->flashdata("success");
		$data['error'] = $this->session->flashdata("error");
        
	   $username = $this->session->userdata('username');
        
        if(empty($username)) {
            $this->load->view("v_login", $data);
        } else {
            redirect("home");
        }
       
	}

	function do_login() {
		// Ambil POST dengan nama username dan password
		$username = $this->input->post("username");
		$password = $this->input->post("password");
        
		$where = array(
			"username" => $username
		);
		
        //read params: $select $table $where $order $join, $contains
        $result = $this->db_model->read("*","t_user",$where);
        $is_correct = password_verify($password, $result[0]->password);

		// Jika data ditemukan (jumlah data tidak sama dengan nol)
		if (count($result)>0 && $is_correct) {
			// Login berhasil
			// Set session untuk user yang login
			// Set session menggunakan sintax dibawah ini
			// $this->session->set_userdata("nama_session","value");
			
			$this->session->set_userdata("username", $result[0]->username);
			$this->session->set_userdata("id_user", $result[0]->id_user);
            //do_profiles($result);
            
			$level = $result[0]->level;
			$this->session->set_userdata("level", $level);   

			$priv_where = array("id_level" => $level);
			$privileges = $this->db_model->read("*","t_user_level",$priv_where);
			$profiles = array(
							"fullname" => $result[0]->fullname,
							"id_user" => $result[0]->id_user,
							"about_me" => $result[0]->about_me,
							"can_alter" => $privileges[0]->can_alter,
							"can_delete" => $privileges[0]->can_delete,
							"alter_other" => $privileges[0]->alter_other,
							"delete_other" => $privileges[0]->delete_other);

			$this->session->set_userdata("profiles", $profiles);
			redirect("home");
        } elseif(count($result)>0 && !$is_correct) {
            $this->session->set_flashdata("error","Your password is incorrect!");
            redirect("login");
        } elseif(count($result)<1) {
            $this->session->set_flashdata("error","There are no username {$username} in PMO!");
            redirect("login");
		} else {
            $this->session->set_flashdata("error","Your login credentials is incorrect!");
            redirect("login");
        }
	}
	
	function logout() {
		$this->session->unset_userdata('username');
		$this->session->unset_userdata('level');
		$this->session->unset_userdata('profiles');
        redirect('home');
	}
}
