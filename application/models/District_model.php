<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class District_model extends CI_Model {
	var $table = 'district';
	var $table_state = 'state';
	var $column_order = array('DistrictId', 'StateId', 'PrathamDistrictName', 'CensusDistrictName', 'DISEDistrictName','DistrictCode',null); //set column field database for datatable orderable

	var $column_search = array('DistrictId','PrathamDistrictName','district.CreatedBy','state.StateName'); //set column field database for datatable searchable just firstname , lastname , address are searchable
	var $order = array('DistrictId' => 'desc'); // default order 

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query()
	{
		$this->db->select('district.*, state.StateName');
		$this->db->from('state');
		$this->db->join('district','district.StateId = state.StateId ');
		

		//$this->db->from($this->table);

		$i = 0;
	
		foreach ($this->column_search as $item) // loop column 
		{
			if($_POST['search']['value']) // if datatable send POST for search
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

				if(count($this->column_search) - 1 == $i) //last loop
					$this->db->group_end(); //close bracket
			}
			$i++;
		}
		
		if(isset($_POST['order'])) // here order processing
		{
			$this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		} 
		else if(isset($this->order))
		{
			$order = $this->order;
			$this->db->order_by(key($order), $order[key($order)]);
		}
	}



	function get_datatables()
	{

		$this->_get_datatables_query();

		$this->db->where('district.IsDeleted','!1');
		
		if($_POST['length'] != -1)
	

		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		return $query->result();
	}

	function count_filtered()
	{
		$this->_get_datatables_query();
		$query = $this->db->get();
		return $query->num_rows();
	}

	function getLastInserted() {
		$last = $this->db->query("SELECT DistrictId FROM district ORDER BY DistrictId DESC LIMIT 1");	
		$last = $last->result_array();
		return $last;
	}

	function statename()
	{
		//$this->_get_datatables_query();
		$this->db->select('state.StateId, state.StateName');
		$this->db->from('state');
		$query = $this->db->get();
		return $query->result_array();
		//return $this->db->get()->result_array();	    
	    //$query = $this->db->get();	
		
	    // print_r($query);
	    // die();
		
		// foreach($query->result_array() as $row) {
		// 	$array[] = $row['StateId'];			
		// 	$array[] = $row['StateName'];
		// 	//$questionresults[1] = $row->question;
  //       	//$array->StateName; // add each user id to the array
  //   	}

    // return $array;
    // return $query;
	}

	public function count_all()
	{
		$this->db->from($this->table);
		return $this->db->count_all_results();
	}

	public function get_by_id($DistrictId)
	{
		$this->db->from($this->table);
		$this->db->where('DistrictId',$DistrictId);
		$query = $this->db->get();

		return $query->row();
	}

	public function save($data)
	{
		$this->db->insert($this->table, $data);
		return $this->db->insert_id();
	}


	public function update($where, $data)
	{
		$this->db->update($this->table, $data, $where);
		return $this->db->affected_rows();
	}



}
