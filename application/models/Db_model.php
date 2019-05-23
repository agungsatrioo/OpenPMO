<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Db_model extends CI_Model {

	function create($table, $data) {
		// Bentuk Umum: $this->db->insert("nama_tabel", $data);
		$this->db->insert($table,$data);
	}
	
	private function __populate_column($select, $from, $join) {
		$result=array();
		
		if($select=="*") {
			$collumns_main = $this->db->list_fields($from);
			
			foreach($collumns_main as $a) {
				$result[] = $a;
			}
			
			if(!empty($join)) {
				foreach($join as $key=>$props) {
					$collumns_second = $this->db->list_fields($props['table']);
					foreach($collumns_second as $a) {
						$result[] = $a;
					}
				} 
			}
		} else {
			$result = explode(",", $select);
		}
		return $result;
	}
    
	private function _get_datatables_query($column_search, $column_order, $order) {
        $i = 0;
        
        
        foreach ($column_search as $item) {
            
            if($_POST['search']['value']) // if datatable send POST for search
            {
                
                
                if(strpos($item, ' as ') !== true)
                {
                    if($i===0) // first loop
                    {

                        $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                        $this->db->like($item, $_POST['search']['value']);
                    }
                    else
                    {
                        $this->db->or_like($item, $_POST['search']['value']);
                    }
                }

                if(count($column_search) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }
        
        if(isset($_POST['order'])) // here order processing
        {
            $this->db->order_by($column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } 
        else if(isset($order))
        {
            $this->db->order_by(key($order), $order[key($order)]);
        }

    }

	//read params: $select $table $where $order $join, $contains
	function read($select, $table, $where="", $order="",$join=array(), $containsDataTables=false, $group="") {
		if ( !empty($where) ) $this->db->where($where);
		if ( !empty($order) ) $this->db->order_by($order,'asc');
		if ( !empty($group) ) $this->db->group_by($group);
        $select_mod = array();
        
		$this->db->distinct();
        $this->db->select($select);
		
		if(!empty($join)) {
			foreach($join as $key=>$props) {
				$this->db->join($props['table'], $props['fkey']);
			} 
		}
		
		 if($containsDataTables) {

			$column_array = $this->__populate_column($select, $table,$join);
             
             foreach($column_array as $key => $one) {
                if(strpos($one, ' as ') !== false)
                unset($column_array[$key]);
            }
             
			$column_order = $column_array;	
			array_push($column_order, null);
            $this->_get_datatables_query($column_array,$column_order,array($order=>'asc'));
        }
        
		$query_tmp = $this->db->from($table);
		$query = $query_tmp->get();
		
		if ($query AND $query->num_rows() != 0) {
			return $query->result();
		} else {
			return array();
		}
	}
	
	function read_procedure($string) {
        $qry_res = $this->db->query("call ".$string);

        $res = $qry_res->result();

        $qry_res->next_result();
        $qry_res->free_result();

        if (count($res) > 0) {
              return $res;
        } else {
              return array();
        }
	}
	
	function update($table, $where, $data) {
        $permit = $this->users->request("update", $table, $where);

        if($permit==true) {
                $this->db->where($where);
		      $this->db->update($table, $data);
        } else {
            exit("Operation not allowed");
        }
        

	}
	
	function delete($table, $where) {
        $permit = $this->users->request("delete", $table, $where);

        if($permit==true) {
                $this->db->where($where);
		      $this->db->delete($table);	
        } else {
            exit("Operation not allowed");
        }
        
			
	}
}