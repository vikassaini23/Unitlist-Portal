<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Program extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Program_model','program');
	}

	public function index()
	{
		$this->load->helper('url');
		$this->load->view('template/submenu');
		$this->load->view('program_view');
	}

	public function ajax_list()
	{
		$list = $this->program->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $program) {
			$no++;
			$row = array();
			$row[] = $program->ProgramId;
			$row[] = $program->Program;


			//add html for action
			$row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_program('."'".$program->ProgramId."'".')"><i class="glyphicon glyphicon-pencil"></i> Edit</a>
				  <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Delete" onclick="edit_del('."'".$program->ProgramId."'".')"><i class="glyphicon glyphicon-trash"></i> Delete</a>';
		
			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->program->count_all(),
						"recordsFiltered" => $this->program->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}

	public function ajax_edit($ProgramId)
	{
		$data = $this->program->get_by_id($ProgramId);
		echo json_encode($data);
	}


	public function ajax_add()
	{
		$this->_validate();
		$data = array(
				'Program' => $this->input->post('Program'),
				'CreatedBy' => $this->input->post('CreatedBy'),
			);
		$insert = $this->program->save($data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_update()
	{
		$this->_validate();
		$data = array(
				'Program' => $this->input->post('Program'),
				'LastUpdatedBy' => $this->input->post('LastUpdatedBy'),
				'LastUpdatedOn' => $this->input->post('LastUpdatedOn'),
			);
		$this->program->update(array('ProgramId' => $this->input->post('ProgramId')), $data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_update_del()
	{
		$this->_validate();
		$data = array(
				'Program' => $this->input->post('Program'),
				'IsDeleted' => $this->input->post('IsDeleted'),
				'LastUpdatedBy' => $this->input->post('LastUpdatedBy'),
				'LastUpdatedOn' => $this->input->post('LastUpdatedOn'),
			);
		$this->program->update(array('ProgramId' => $this->input->post('ProgramId')), $data);
		echo json_encode(array("status" => TRUE));
	}
	private function _validate()
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

		if($this->input->post('Program') == '')
		{
			$data['inputerror'][] = 'Program';
			$data['error_string'][] = 'Program name is required';
			$data['status'] = FALSE;
		}

		if($data['status'] === FALSE)
		{
			echo json_encode($data);
			exit();
		}
	}

}
