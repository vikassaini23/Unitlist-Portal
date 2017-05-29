<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Person extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('person_model','person');
	}

	public function index()
	{
		$this->load->helper('url');
		$this->load->view('template/submenu');
		$this->load->view('person_view');
	}

	public function ajax_list()
	{
		$list = $this->person->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $person) {
			$no++;
			$row = array();
			$row[] = $person->ProgramBucketId;
			$row[] = $person->ProgramBucket;
/*			$row[] = $person->gender;
			$row[] = $person->address;
			$row[] = $person->dob;*/

			//add html for action
			$row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_person('."'".$person->ProgramBucketId."'".')"><i class="glyphicon glyphicon-pencil"></i> Edit</a>
				  <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="edit_del('."'".$person->ProgramBucketId."'".')"><i class="glyphicon glyphicon-trash"></i> Delete</a>';
		
			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->person->count_all(),
						"recordsFiltered" => $this->person->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}

	public function ajax_edit($ProgramBucketId)
	{
		$data = $this->person->get_by_id($ProgramBucketId);
		//$data->dob = ($data->dob == '0000-00-00') ? '' : $data->dob;  if 0000-00-00 set tu empty for datepicker compatibility
		echo json_encode($data);
	}

	public function ajax_edit_del($ProgramBucketId)
	{
		$data = $this->person->get_by_id($ProgramBucketId);
		//$data->dob = ($data->dob == '0000-00-00') ? '' : $data->dob;  if 0000-00-00 set tu empty for datepicker compatibility
		echo json_encode($data);
	}

	public function ajax_add()
	{
		$this->_validate();
		$data = array(
				'ProgramBucket' => $this->input->post('ProgramBucket'),
				'CreatedBy' => $this->input->post('CreatedBy'),
				// 'lastName' => $this->input->post('lastName'),
				// 'gender' => $this->input->post('gender'),
				// 'address' => $this->input->post('address'),
				// 'dob' => $this->input->post('dob'),
			);
		$insert = $this->person->save($data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_update()
	{
		$this->_validate();
		$data = array(
				'ProgramBucket' => $this->input->post('ProgramBucket'),
			/*	'lastName' => $this->input->post('lastName'),
				'gender' => $this->input->post('gender'),
				'address' => $this->input->post('address'),
				'dob' => $this->input->post('dob'),*/
			);
		$this->person->update(array('ProgramBucketId' => $this->input->post('ProgramBucketId')), $data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_update_del()
	{
		$this->_validate();
		$data = array(
				'ProgramBucket' => $this->input->post('ProgramBucket'),
				'IsDeleted' => $this->input->post('IsDeleted'),
			/*	'gender' => $this->input->post('gender'),
				'address' => $this->input->post('address'),
				'dob' => $this->input->post('dob'),*/
			);
		$this->person->update(array('ProgramBucketId' => $this->input->post('ProgramBucketId')), $data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_delete($ProgramBucketId)
	{
		$this->person->delete_by_id($ProgramBucketId);
		echo json_encode(array("status" => TRUE));
	}


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

/*		if($this->input->post('lastName') == '')
		{
			$data['inputerror'][] = 'lastName';
			$data['error_string'][] = 'Last name is required';
			$data['status'] = FALSE;
		}

		if($this->input->post('dob') == '')
		{
			$data['inputerror'][] = 'dob';
			$data['error_string'][] = 'Date of Birth is required';
			$data['status'] = FALSE;
		}

		if($this->input->post('gender') == '')
		{
			$data['inputerror'][] = 'gender';
			$data['error_string'][] = 'Please select gender';
			$data['status'] = FALSE;
		}

		if($this->input->post('address') == '')
		{
			$data['inputerror'][] = 'address';
			$data['error_string'][] = 'Addess is required';
			$data['status'] = FALSE;
		}*/

		if($data['status'] === FALSE)
		{
			echo json_encode($data);
			exit();
		}
	}

}
