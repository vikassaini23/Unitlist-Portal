<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Donor extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Donor_model','donor');
	}

	public function index()
	{
		$this->load->helper('url');
		$this->load->view('template/submenu');
		$this->load->view('donor_view');
	}

	public function ajax_list()
	{
		$list = $this->donor->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $donor) {
			$no++;
			$row = array();
			$row[] = $donor->DonorId;
			$row[] = $donor->DonorName;


			//add html for action
			$row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_donor('."'".$donor->DonorId."'".')"><i class="glyphicon glyphicon-pencil"></i> Edit</a>
				  <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Delete" onclick="edit_del('."'".$donor->DonorId."'".')"><i class="glyphicon glyphicon-trash"></i> Delete</a>';
		
			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->donor->count_all(),
						"recordsFiltered" => $this->donor->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}

	public function ajax_edit($DonorId)
	{
		$data = $this->donor->get_by_id($DonorId);
		echo json_encode($data);
	}


	public function ajax_add()
	{
		$this->_validate();
		$data = array(
				'DonorName' => $this->input->post('DonorName'),
				'CreatedBy' => $this->input->post('CreatedBy'),
			);
		$insert = $this->donor->save($data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_update()
	{
		$this->_validate();
		$data = array(
				'DonorName' => $this->input->post('DonorName'),
				'LastUpdatedBy' => $this->input->post('LastUpdatedBy'),
				'LastUpdatedOn' => $this->input->post('LastUpdatedOn'),
			);
		$this->donor->update(array('DonorId' => $this->input->post('DonorId')), $data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_update_del()
	{
		$this->_validate();
		$data = array(
				'DonorName' => $this->input->post('DonorName'),
				'IsDeleted' => $this->input->post('IsDeleted'),
				'LastUpdatedBy' => $this->input->post('LastUpdatedBy'),
				'LastUpdatedOn' => $this->input->post('LastUpdatedOn'),
			);
		$this->donor->update(array('DonorId' => $this->input->post('DonorId')), $data);
		echo json_encode(array("status" => TRUE));
	}
	private function _validate()
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

		if($this->input->post('DonorName') == '')
		{
			$data['inputerror'][] = 'DonorName';
			$data['error_string'][] = 'Donor name is required';
			$data['status'] = FALSE;
		}

		if($data['status'] === FALSE)
		{
			echo json_encode($data);
			exit();
		}
	}

}
