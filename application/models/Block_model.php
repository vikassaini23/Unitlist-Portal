<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Block_model extends CI_Model {
	var $table = 'block';
	var $table_state = 'state';
	var $table_district = 'district';
	var $column_order = array('BlockId', 'DistrictId', 'PrathamBlockName', 'CensusBlockName', 'DISEBlockName',null); //set column field database for datatable orderable

	var $column_search = array('block.BlockId','block.PrathamBlockName','block.BlockCode', 'block.CreatedBy', 'district.PrathamDistrictName', 'state.StateName'); //set column field database for datatable searchable just firstname , lastname , address are searchable
	var $order = array('BlockId' => 'desc'); // default order 

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query()
	{
		//$this->db->from($this->table);

		$this->db->select('block.*, district.PrathamDistrictName, state.StateName');
		$this->db->from('district');		
		$this->db->join('block','block.DistrictId = district.DistrictId');
		$this->db->join('state','district.StateId = state.StateId');
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

		$this->db->where('block.IsDeleted','!1');
		
		if($_POST['length'] != -1)
	

		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		return $query->result();
	}

	function getLastInserted() {
		$last = $this->db->query("SELECT BlockId FROM block ORDER BY BlockId DESC LIMIT 1");	
		$last = $last->result_array();
		return $last;
	}

	function count_filtered()
	{
		$this->_get_datatables_query();
		//$this->db->where('block.IsDeleted','!1');
		$query = $this->db->get();
		return $query->num_rows();
	}

	function statename()
	{
		$this->db->select('state.StateId, state.StateName');
		$this->db->from('state');
		$query = $this->db->get();
		return $query->result_array();
		
	}

	function districtname()
	{
		$this->db->select('district.DistrictId, district.PrathamDistrictName');
		$this->db->from('district');
		$query = $this->db->get();
		return $query->result_array();
		
	}

	public function count_all()
	{
		$this->db->from($this->table);
		return $this->db->count_all_results();
	}

	public function get_by_id($BlockId)
	{
		$this->db->select('block.*, district.PrathamDistrictName,district.DistrictId, state.StateId, state.StateName');
		$this->db->from('district');		
		$this->db->join('block','block.DistrictId = district.DistrictId');
		$this->db->join('state','district.StateId = state.StateId');
		$this->db->where('block.BlockId',$BlockId);
			// $this->db->from($this->table);
			//->where('BlockId','block'.$BlockId);
			//return $data->result_array();

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
