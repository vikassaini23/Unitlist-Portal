<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Unit extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Unit_model','unit');
	}

	public function index()
	{
		$this->load->helper('url');
		$this->load->view('template/submenu');
		$data['fetch_programbucket'] = $this->unit->programbucket();
		$data['fetch_unittype'] = $this->unit->unittype();
		$data['fetch_program'] = $this->unit->program();
		$data['statename'] = $this->unit->statename();
		$data['fetch_donor'] = $this->unit->donor();
		$data['getLastInserted'] = $this->unit->getLastInserted();		
		$this->load->view('unit_view',$data);
	}

	Public function get_state()
	{
		$query=$this->db->get('state');
        $result= $query->result();
        $data=array();
		foreach($result as $r)
		{
			$data['value']=$r->StateId;
			$data['label']=$r->StateName;
			$json[]=$data;	
		}
		echo json_encode($json);	
	}

	Public function get_district()
	{

		  $result=$this->db->where('StateId',$_POST['DistrictId'])
						->get('district')
						->result();
     
        $data=array();
		foreach($result as $r)
		{
			$data['value']=$r->DistrictId;
			$data['label']=$r->PrathamDistrictName;
			$json[]=$data;
			
			
		}
		echo json_encode($json);

	}

		Public function get_block()
	{

		  $result=$this->db->where('DistrictId',$_POST['BlockId'])
						->get('block')
						->result();
     
        $data=array();
		foreach($result as $r)
		{
			$data['value']=$r->BlockId;
			$data['label']=$r->PrathamBlockName;
			$json[]=$data;
			
			
		}
		echo json_encode($json);

	}

	Public function get_village()
	{

		  $result=$this->db->where('BlockId',$_POST['VillageId'])
						->get('village')
						->result();
     
        $data=array();
		foreach($result as $r)
		{
			$data['value']=$r->VillageId;
			$data['label']=$r->VillageName;
			$json[]=$data;
			
			
		}
		echo json_encode($json);

	}

	public function ajax_list()
	{
		 $list = $this->unit->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $unit) {
			$no++;
			$row = array();
			$row[] = $unit->UnitId;
			$row[] = $unit->UnitName;
			$row[] = $unit->UnitCode;
			$row[] = $unit->VillageName;
			$row[] = $unit->Program;
			$row[] = $unit->UnitTypeName;
			$row[] = $unit->ProgramBucket;
			$row[] = $unit->DonorName;
			$row[] = $unit->PrathamBlockName;		
			$row[] = $unit->PrathamDistrictName;		
			$row[] = $unit->StateName;
			//add html for action
			$row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_unit('."'".$unit->UnitId."'".')"><i class="glyphicon glyphicon-pencil"></i></a>
				  <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Delete" onclick="edit_del('."'".$unit->UnitId."'".')"><i class="glyphicon glyphicon-trash"></i></a>';
		
			$data[] = $row;
		}


		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->unit->count_all(),
						"recordsFiltered" => $this->unit->count_filtered(),
						"state" => $this->unit->statename(),
						"district" => $this->unit->districtname(),
						"block" => $this->unit->blockname(),
						"data" => $data,
						"getLastInserted"=>$this->unit->getLastInserted(),
						"village"=>$this->unit->villagename(),
				);
		//output to json format
		echo json_encode($output);
	
	}

	public function ajax_edit($UnitId)
	{
		$data = $this->unit->get_by_id($UnitId);
		echo json_encode($data);
	}

	public function ajax_edit_unit($UnitId)
	{
		$data = $this->unit->get_by_id($UnitId);
		echo json_encode($data);
	}

	/*public function ajax_add()
	{
		$this->_validate();
		$data = array(

				'UnitName' => $this->input->post('UnitName'),
				'CreatedBy' => $this->input->post('CreatedBy'),
			);
		$insert = $this->unit->save($data);
		echo json_encode(array("status" => TRUE));
	}*/

	public function ajax_update()
	{
		$this->_validate();
		$data = array(
				'UnitTypeId'=> $this->input->post('UnitTypeId'),
				'ProgramId'=> $this->input->post('ProgramId'),
				'DonorId'=> $this->input->post('DonorId'),
				'ProgramBucketId'=> $this->input->post('ProgramBucketId'),
				'UnitName'=> $this->input->post('UnitName'),
				'BatchNo'=> $this->input->post('BatchNo'),
				'NoOfTestingCycles'=> $this->input->post('NoOfTestingCycles'),
				'LastUpdatedBy' => $this->input->post('LastUpdatedBy'),
				'LastUpdatedOn' => $this->input->post('LastUpdatedOn'),
			);
		$this->unit->update(array('UnitId' => $this->input->post('UnitId')), $data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_update_del()
	{
		$this->_validate();
		$data = array(
				'UnitName' => $this->input->post('UnitName'),
				'IsDeleted' => $this->input->post('IsDeleted'),
				'LastUpdatedBy' => $this->input->post('LastUpdatedBy'),
				'LastUpdatedOn' => $this->input->post('LastUpdatedOn'),
			);
		$this->unit->update(array('UnitId' => $this->input->post('UnitId')), $data);
		echo json_encode(array("status" => TRUE));
	}

	// Ajax Add Unit begin here

	public function ajax_add_unit()
	{ 
		$this->_validate();
		$data = array(
				
				'StateId'=> $this->input->post('StateId'),
				'DistrictId'=> $this->input->post('DistrictId'),
				'BlockId'=> $this->input->post('BlockId'),
				'VillageId'=> $this->input->post('VillageId'),
				'UnitTypeId'=> $this->input->post('UnitTypeId'),
				'ProgramId'=> $this->input->post('ProgramId'),
				'DonorId'=> $this->input->post('DonorId'),
				'ProgramBucketId'=> $this->input->post('ProgramBucketId'),
				'UnitCode'=> $this->input->post('UnitCode'),
				'UnitName'=> $this->input->post('UnitName'),
				'BatchNo'=> $this->input->post('BatchNo'),
				'NoOfTestingCycles'=> $this->input->post('NoOfTestingCycles'),
				'CreatedBy' => $this->input->post('CreatedBy'),
			);
		$insert = $this->unit->save_unit($data);
		echo json_encode(array("status" => TRUE));
	}

	// Ajax Add Unit End here
	private function _validate()
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

		if($this->input->post('UnitName') == '')
		{
			$data['inputerror'][] = 'UnitName';
			$data['error_string'][] = 'Unit Name is required';
			$data['status'] = FALSE;
		}
		if($data['status'] === FALSE)
		{
			echo json_encode($data);
			exit();
		}
	}
}
