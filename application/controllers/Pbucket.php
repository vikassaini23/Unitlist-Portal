<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pbucket extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Programbucket_model','pbucket');
	}

	public function index()
	{
		$this->load->helper('url');
		$this->load->view('template/submenu');
		$this->load->view('programbucket_view');
	}

	public function ajax_list()
	{
		$list = $this->pbucket->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $pbucket) {
			$no++;
			$row = array();
			$row[] = $pbucket->ProgramBucketId;
			$row[] = $pbucket->ProgramBucket;


			//add html for action
			$row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_pbucket('."'".$pbucket->ProgramBucketId."'".')"><i class="glyphicon glyphicon-pencil"></i> Edit</a>
				  <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Delete" onclick="edit_del('."'".$pbucket->ProgramBucketId."'".')"><i class="glyphicon glyphicon-trash"></i> Delete</a>';
		
			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->pbucket->count_all(),
						"recordsFiltered" => $this->pbucket->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}

	public function ajax_edit($ProgramBucketId)
	{
		$data = $this->pbucket->get_by_id($ProgramBucketId);
		//$data->dob = ($data->dob == '0000-00-00') ? '' : $data->dob;  if 0000-00-00 set tu empty for datepicker compatibility
		echo json_encode($data);
	}


	public function ajax_add()
	{
		$this->_validate();
		$data = array(
				'ProgramBucket' => $this->input->post('ProgramBucket'),
				'CreatedBy' => $this->input->post('CreatedBy'),
			);
		$insert = $this->pbucket->save($data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_update()
	{
		$this->_validate();
		$data = array(
				'ProgramBucket' => $this->input->post('ProgramBucket'),
				'LastUpdatedBy' => $this->input->post('LastUpdatedBy'),
				'LastUpdatedOn' => $this->input->post('LastUpdatedOn'),
			);
		$this->pbucket->update(array('ProgramBucketId' => $this->input->post('ProgramBucketId')), $data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_update_del()
	{
		$this->_validate();
		$data = array(
				'ProgramBucket' => $this->input->post('ProgramBucket'),
				'IsDeleted' => $this->input->post('IsDeleted'),
				'LastUpdatedBy' => $this->input->post('LastUpdatedBy'),
				'LastUpdatedOn' => $this->input->post('LastUpdatedOn'),
			);
		$this->pbucket->update(array('ProgramBucketId' => $this->input->post('ProgramBucketId')), $data);
		echo json_encode(array("status" => TRUE));
	}

	/*public function ajax_delete($ProgramBucketId)
	{
		$this->pbucket->delete_by_id($ProgramBucketId);
		echo json_encode(array("status" => TRUE));
	}
*/

	private function _validate()
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

		if($this->input->post('ProgramBucket') == '')
		{
			$data['inputerror'][] = 'ProgramBucket';
			$data['error_string'][] = 'Program Bucket name is required';
			$data['status'] = FALSE;
		}

		if($data['status'] === FALSE)
		{
			echo json_encode($data);
			exit();
		}
	}

}
