<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Village_model extends CI_Model {
	var $table = 'village';
	var $table_state = 'state';
	var $table_district = 'district';
	var $table_block = 'block';
	var $table_unit = 'unit';
	var $column_order = array('VillageId', 'VillageName','VIllageCode','VIllageType','block.PrathamBlockName', 'district.PrathamDistrictName', 'state.StateName',null); //set column field database for datatable orderable

	var $column_search = array('village.VillageId','village.VillageName','village.CreatedBy','block.PrathamBlockName', 'district.PrathamDistrictName', 'state.StateName'); //set column field database for datatable searchable just firstname , lastname , address are searchable
	var $order = array('VillageId' => 'desc'); // default order 

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query()
	{
		$this->db->select('village.*, count(unit.VillageId) as total, block.PrathamBlockName, district.PrathamDistrictName, state.StateName');
		$this->db->group_by('village.VillageId','block.BlockId');
		$this->db->from('unit');		
		$this->db->join('village','village.VillageId  = unit.VillageId ','right');
		$this->db->join('block','village.BlockId = block.BlockId');
		$this->db->join('district','block.DistrictId = district.DistrictId');		
		$this->db->join('state','state.StateId = district.StateId');

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

		$this->db->where('village.IsDeleted','!1');
		
		if($_POST['length'] != -1)
	

		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		return $query->result();
	}

	function getLastInserted() {
		$last = $this->db->query("SELECT VillageId FROM village ORDER BY VillageId DESC LIMIT 1");	
		$last = $last->result_array();
		return $last;
	}

	function count_filtered()
	{
		$this->_get_datatables_query();
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

	function blockname()
	{
		$this->db->select('block.BlockId, block.PrathamBlockName');
		$this->db->from('block');
		$query = $this->db->get();
		return $query->result_array();	
	}

	function villagename()
	{
		$this->db->select('village.VillageId, village.VillageName');
		$this->db->from('village');
		$query = $this->db->get();
		return $query->result_array();	
	}

	function programbucket()
	{
		$this->db->select('programbucket.ProgramBucketId, programbucket.ProgramBucket');
		$this->db->from('programbucket');
		$query = $this->db->get();
		return $query->result_array();	
	}

	function unittype()
	{
		$this->db->select('unittype.UnitTypeId, unittype.UnitTypeName');
		$this->db->from('unittype');
		$query = $this->db->get();
		return $query->result_array();	
	}

	function program()
	{
		$this->db->select('program.ProgramId, program.Program');
		$this->db->from('program');
		$query = $this->db->get();
		return $query->result_array();	
	}

	function donor()
	{
		$this->db->select('donor.DonorId, donor.DonorName');
		$this->db->from('donor');
		$query = $this->db->get();
		return $query->result_array();	
	}

	public function count_all()
	{
		$this->db->from($this->table);
		return $this->db->count_all_results();
	}

	 public function get_by_id($VillageId)
	{
	$this->db->select('village.*, block.PrathamBlockName, block.BlockId, district.PrathamDistrictName,district.DistrictId, state.StateId, state.StateName');
	$this->db->from('village');	
	$this->db->join('block','block.BlockId = village.BlockId');	
	$this->db->join('district','district.DistrictId = block.DistrictId');
	$this->db->join('state','state.StateId= district.StateId');
	$this->db->where('village.VillageId',$VillageId);
	$query = $this->db->get();
	return $query->row();
	}

	public function save($data)
	{
		$this->db->insert($this->table, $data);
		return $this->db->insert_id();
	}

	public function save_unit($data)
	{
		$this->db->insert($this->table_unit, $data);
		return $this->db->insert_id();
	}

	public function update($where, $data)
	{
		$this->db->update($this->table, $data, $where);
		return $this->db->affected_rows();
	}



}
