<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class District extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('District_model','district');
	}

	public function index()
	{
		$this->load->helper('url');
		$data['fetch_statename'] = $this->district->statename();
		$data['getLastInserted'] = $this->district->getLastInserted();
		$this->load->view('template/submenu',$data);		
		$this->load->view('district_view',$data);
	}



	public function ajax_list()
	{
		 $list = $this->district->get_datatables();
		  $data = array();
		  $no = $_POST['start'];
		  foreach ($list as $district) {
			$no++;
			$row = array();
			$row[] = $district->DistrictId;
			$row[] = $district->PrathamDistrictName;
			$row[] = $district->CensusDistrictName;
			$row[] = $district->DISEDistrictName;
			$row[] = $district->DistrictCode;
			$row[] = $district->StateName;

			//add html for action
			$row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_district('."'".$district->DistrictId."'".')"><i class="glyphicon glyphicon-pencil"></i> Edit</a>
				  <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Delete" onclick="edit_del('."'".$district->DistrictId."'".')"><i class="glyphicon glyphicon-trash"></i> Delete</a>';
		
			$data[] = $row;
		}


		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->district->count_all(),
						"recordsFiltered" => $this->district->count_filtered(),
						"state" => $this->district->statename(),
						"data" => $data,
						"getLastInserted"=>$this->district->getLastInserted(),
				);
		//output to json format
		echo json_encode($output);
	
	}

	public function ajax_edit($DistrictId)
	{
		$data = $this->district->get_by_id($DistrictId);
		//$data->dob = ($data->dob == '0000-00-00') ? '' : $data->dob;  if 0000-00-00 set tu empty for datepicker compatibility
		echo json_encode($data);
	}


	public function ajax_add()
	{
		$this->_validate();
		$data = array(
				'StateId' => $this->input->post('StateId'),
				'PrathamDistrictName' => $this->input->post('PrathamDistrictName'),
				'IsFoundInCensusList' => $this->input->post('IsFoundInCensusList'),
				'CensusDistrictName' => $this->input->post('CensusDistrictName'),
				'IsFoundInDISEList' => $this->input->post('IsFoundInDISEList'),
				'DISEDistrictName' => $this->input->post('DISEDistrictName'),
				'DistrictCode' => $this->input->post('DistrictCode'),
				'CreatedBy' => $this->input->post('CreatedBy'),
			);
		$insert = $this->district->save($data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_update()
	{
		$this->_validate();
		$data = array(
				'PrathamDistrictName' => $this->input->post('PrathamDistrictName'),
				'IsFoundInCensusList' => $this->input->post('IsFoundInCensusList'),
				'CensusDistrictName' => $this->input->post('CensusDistrictName'),
				'IsFoundInDISEList' => $this->input->post('IsFoundInDISEList'),
				'DISEDistrictName' => $this->input->post('DISEDistrictName'),
				'DistrictCode' => $this->input->post('DistrictCode'),
				'LastUpdatedBy' => $this->input->post('LastUpdatedBy'),
				'LastUpdatedOn' => $this->input->post('LastUpdatedOn'),
			);
		$this->district->update(array('DistrictId' => $this->input->post('DistrictId')), $data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_update_del()
	{
		$this->_validate();
		$data = array(
				'PrathamDistrictName' => $this->input->post('PrathamDistrictName'),
				'CensusDistrictName' => $this->input->post('PrathamDistrictName'),
				'DISEDistrictName' => $this->input->post('PrathamDistrictName'),
				'IsDeleted' => $this->input->post('IsDeleted'),
				'LastUpdatedBy' => $this->input->post('LastUpdatedBy'),
				'LastUpdatedOn' => $this->input->post('LastUpdatedOn'),
			);
		$this->district->update(array('DistrictId' => $this->input->post('DistrictId')), $data);
		echo json_encode(array("status" => TRUE));
	}

	private function _validate()
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

		if($this->input->post('PrathamDistrictName') == '')
		{
			$data['inputerror'][] = 'PrathamDistrictName';
			$data['error_string'][] = 'District name is required';
			$data['status'] = FALSE;
		}
		if($this->input->post('CensusDistrictName') == '')
		{
			$data['inputerror'][] = 'CensusDistrictName';
			$data['error_string'][] = 'Census District Name is required';
			$data['status'] = FALSE;
		}
		if($this->input->post('DISEDistrictName') == '')
		{
			$data['inputerror'][] = 'DISEDistrictName';
			$data['error_string'][] = 'DISE District Name is required';
			$data['status'] = FALSE;
		}

		if($data['status'] === FALSE)
		{
			echo json_encode($data);
			//echo($error_string);
			exit();
		}
	}

}
