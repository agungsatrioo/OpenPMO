<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Users extends CI_Model {
    function access($id_level) {
        $result = $this->db_model->read("*","t_user_level","id_level=".$id_level);
        return $result[0];
    }
    
    function request($type,$table,$where) {  // UPDATE or DELETE
        $level   = $this->session->userdata('level');
        $id_user = $this->session->userdata('id_user');
        $permit = false;
        
        switch($table) {
            case "t_project":
                $priv_src = "project_{$type}";
                $priv_src_other = "project_{$type}_other";
            break;
            case "t_project_progress":
                $priv_src = "task_{$type}";
                $priv_src_other = "task_{$type}_other";
            break;
            case "t_project_subprogress":
                $priv_src = "subtask_{$type}";
                $priv_src_other = "subtask_{$type}_other";
            break;
        }
        
        switch($table) {
            case "t_user":
                if($level=="1") $permit=true;
                break;
            default:
                $table_src = $this->db_model->read("pic",$table,$where)[0];
                $table_update = $this->db_model->read(array($priv_src,$priv_src_other),"t_user_level", array("id_level"=>$level))[0]; 
                if($table_src->pic==$id_user) {
                    if($table_update->$priv_src==1) $permit = true;
                } else {
                    if($table_update->$priv_src_other==1) $permit = true;
                }
                break;
                
        }

        return $permit;
    }
}
?>