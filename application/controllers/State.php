<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class State extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('State_model','state');
	}

	public function index()
	{
		$this->load->helper('url');
		$this->load->view('template/submenu');
		$this->load->view('state_view');
	}

	public function ajax_list()
	{
		$list = $this->state->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $state) {
			$no++;
			$row = array();
			$row[] = $state->StateId;
			$row[] = $state->StateName;


			//add html for action
			$row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_state('."'".$state->StateId."'".')"><i class="glyphicon glyphicon-pencil"></i> Edit</a>
				  <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Delete State" onclick="edit_del('."'".$state->StateId."'".')"><i class="glyphicon glyphicon-trash"></i> Delete</a>';
		
			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->state->count_all(),
						"recordsFiltered" => $this->state->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}

	public function ajax_edit($StateId)
	{
		$data = $this->state->get_by_id($StateId);
		//$data->dob = ($data->dob == '0000-00-00') ? '' : $data->dob;  if 0000-00-00 set tu empty for datepicker compatibility
		echo json_encode($data);
	}


	public function ajax_add()
	{
		$this->_validate();
		$data = array(
				'StateName' => $this->input->post('StateName'),
				'CreatedBy' => $this->input->post('CreatedBy'),
			);
		$insert = $this->state->save($data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_update()
	{
		$this->_validate();
		$data = array(
				'StateName' => $this->input->post('StateName'),
				'LastUpdatedBy' => $this->input->post('LastUpdatedBy'),
				'LastUpdatedOn' => $this->input->post('LastUpdatedOn'),
			);
		$this->state->update(array('StateId' => $this->input->post('StateId')), $data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_update_del()
	{
		$this->_validate();
		$data = array(
				'StateName' => $this->input->post('StateName'),
				'IsDeleted' => $this->input->post('IsDeleted'),
				'LastUpdatedBy' => $this->input->post('LastUpdatedBy'),
				'LastUpdatedOn' => $this->input->post('LastUpdatedOn'),
			);
		$this->state->update(array('StateId' => $this->input->post('StateId')), $data);
		echo json_encode(array("status" => TRUE));
	}

	/*public function ajax_delete($StateId)
	{
		$this->pbucket->delete_by_id($StateId);
		echo json_encode(array("status" => TRUE));
	}
*/

	private function _validate()
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

		if($this->input->post('StateName') == '')
		{
			$data['inputerror'][] = 'StateName';
			$data['error_string'][] = 'State name is required';
			$data['status'] = FALSE;
		}

		if($data['status'] === FALSE)
		{
			echo json_encode($data);
			exit();
		}
	}

}
