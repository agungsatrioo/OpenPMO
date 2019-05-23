<?php
class Elements extends CI_Model {
    function delete_button($link) {
        $html_element = "<a id='delete' class='btn btn-danger' href='". site_url("user/delete_user/") .$result->id_user."'><i class='fa fa-trash'></i> Hapus</a>";
    }
}
?>