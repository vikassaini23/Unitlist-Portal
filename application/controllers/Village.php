<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Village extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Village_model','village');
	}

	public function index()
	{
		$this->load->helper('url');
		$this->load->view('template/submenu');
		$data['fetch_programbucket'] = $this->village->programbucket();
		$data['fetch_unittype'] = $this->village->unittype();
		$data['fetch_program'] = $this->village->program();
		$data['statename'] = $this->village->statename();
		$data['fetch_donor'] = $this->village->donor();
		$data['getLastInserted'] = $this->village->getLastInserted();		
		$this->load->view('village_view',$data);
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
		 $list = $this->village->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $village) {
			$no++;
			$row = array();
			$row[] = $village->VillageId;
			$row[] = $village->VillageName;
			$row[] = $village->VillageCode;
			$row[] = $village->VillageType;
			$row[] = $village->PrathamBlockName;		
			$row[] = $village->PrathamDistrictName;		
			$row[] = $village->StateName;
			$row[] = '<a class="btn btn-sm btn-success" href="javascript:void(0)" title="Add Unit" onclick="add_unit('."'".$village->VillageId."'".')"><i class="glyphicon glyphicon-plus"><b>&nbsp;'."".$village->total."".'</b></i></a>';	
		
			//add html for action
			$row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_village('."'".$village->VillageId."'".')"><i class="glyphicon glyphicon-pencil"></i> </a>
			<a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Delete" onclick="edit_del('."'".$village->VillageId."'".')"><i class="glyphicon glyphicon-trash"></i> </a>';
		
			$data[] = $row;
		}


		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->village->count_all(),
						"recordsFiltered" => $this->village->count_filtered(),
						"state" => $this->village->statename(),
						"district" => $this->village->districtname(),
						"block" => $this->village->blockname(),
						"data" => $data,
						"getLastInserted"=>$this->village->getLastInserted(),
						"village"=>$this->village->villagename(),
				);
		//output to json format
		echo json_encode($output);
	
	}

	public function ajax_edit($VillageId)
	{
		$data = $this->village->get_by_id($VillageId);
		echo json_encode($data);
	}

	public function ajax_edit_unit($VillageId)
	{
		$data = $this->village->get_by_id($VillageId);
		echo json_encode($data);
	}

	public function ajax_add()
	{
		$this->_validate();
		$data = array(

				'VillageName'=> $this->input->post('VillageName'),
				'VillageCode'=> $this->input->post('VillageCode'),
				'VillageType'=> $this->input->post('VillageType'),
				'BlockId'=> $this->input->post('BlockId'),
				'DidLibraryEverHappen'=> $this->input->post('DidLibraryEverHappen'),
				'DidRIorRIPlusEverHappen'=> $this->input->post('DidRIorRIPlusEverHappen'),
				'DidBalwadiEverHappen'=> $this->input->post('DidBalwadiEverHappen'),
				'DidBalwachanEverHappen'=> $this->input->post('DidBalwachanEverHappen'),
				'DidUpperPrimaryEverHappen'=> $this->input->post('DidUpperPrimaryEverHappen'),
				'DidHLearningEverHappen'=> $this->input->post('DidHLearningEverHappen'),
				'DidAnyOtherInterventionsHappen'=> $this->input->post('DidAnyOtherInterventionsHappen'),
				'CreatedBy' => $this->input->post('CreatedBy'),
			);
		$insert = $this->village->save($data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_update()
	{
		$this->_validate();
		$data = array(
				'VillageName'=> $this->input->post('VillageName'),
				'VillageType'=> $this->input->post('VillageType'),
				'DidLibraryEverHappen'=> $this->input->post('DidLibraryEverHappen'),
				'DidRIorRIPlusEverHappen'=> $this->input->post('DidRIorRIPlusEverHappen'),
				'DidBalwadiEverHappen'=> $this->input->post('DidBalwadiEverHappen'),
				'DidBalwachanEverHappen'=> $this->input->post('DidBalwachanEverHappen'),
				'DidUpperPrimaryEverHappen'=> $this->input->post('DidUpperPrimaryEverHappen'),
				'DidHLearningEverHappen'=> $this->input->post('DidHLearningEverHappen'),
				'DidAnyOtherInterventionsHappen'=> $this->input->post('DidAnyOtherInterventionsHappen'),
				'LastUpdatedBy' => $this->input->post('LastUpdatedBy'),
				'LastUpdatedOn' => $this->input->post('LastUpdatedOn'),
			);
		$this->village->update(array('VillageId' => $this->input->post('VillageId')), $data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_update_del()
	{
		$this->_validate();
		$data = array(
				'VillageName' => $this->input->post('VillageName'),
				'IsDeleted' => $this->input->post('IsDeleted'),
				'LastUpdatedBy' => $this->input->post('LastUpdatedBy'),
				'LastUpdatedOn' => $this->input->post('LastUpdatedOn'),
			);
		$this->village->update(array('VillageId' => $this->input->post('VillageId')), $data);
		echo json_encode(array("status" => TRUE));
	}

	// Ajax Add Unit begin here

	public function ajax_add_unit()
	{ 
		$this->_validate_unit();
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
		$insert = $this->village->save_unit($data);
		echo json_encode(array("status" => TRUE));
	}


	// Ajax Add Unit End here

	private function _validate()
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

		if($this->input->post('VillageName') == '')
		{
			$data['inputerror'][] = 'VillageName';
			$data['error_string'][] = 'Village name is required';
			$data['status'] = FALSE;
		}
		if($data['status'] === FALSE)
		{
			echo json_encode($data);
			exit();
		}
	}

// Validation for Unit Form
	private function _validate_unit()
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

		if($this->input->post('UnitName') == '')
		{
			$data['inputerror'][] = 'UnitName';
			$data['error_string'][] = 'Unit name is required';
			$data['status'] = FALSE;
		}
		if($data['status'] === FALSE)
		{
			echo json_encode($data);
			exit();
		}
	}

}
