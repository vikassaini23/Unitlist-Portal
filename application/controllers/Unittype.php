<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Unittype extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Unittype_model','unittype');
	}

	public function index()
	{
		$this->load->helper('url');
		$this->load->view('template/submenu');
		$this->load->view('unittype_view');
	}

	public function ajax_list()
	{
		$list = $this->unittype->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $unittype) {
			$no++;
			$row = array();
			$row[] = $unittype->UnitTypeId;
			$row[] = $unittype->UnitTypeName;


			//add html for action
			$row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_unittype('."'".$unittype->UnitTypeId."'".')"><i class="glyphicon glyphicon-pencil"></i> Edit</a>
				  <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Delete" onclick="edit_del('."'".$unittype->UnitTypeId."'".')"><i class="glyphicon glyphicon-trash"></i> Delete</a>';
		
			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->unittype->count_all(),
						"recordsFiltered" => $this->unittype->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}

	public function ajax_edit($UnitTypeId)
	{
		$data = $this->unittype->get_by_id($UnitTypeId);
		echo json_encode($data);
	}


	public function ajax_add()
	{
		$this->_validate();
		$data = array(
				'UnitTypeName' => $this->input->post('UnitTypeName'),
				'CreatedBy' => $this->input->post('CreatedBy'),
			);
		$insert = $this->unittype->save($data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_update()
	{
		$this->_validate();
		$data = array(
				'UnitTypeName' => $this->input->post('UnitTypeName'),
				'LastUpdatedBy' => $this->input->post('LastUpdatedBy'),
				'LastUpdatedOn' => $this->input->post('LastUpdatedOn'),
			);
		$this->unittype->update(array('UnitTypeId' => $this->input->post('UnitTypeId')), $data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_update_del()
	{
		$this->_validate();
		$data = array(
				//'UnitTypeName' => $this->input->post('UnitTypeName'),
				'IsDeleted' => $this->input->post('IsDeleted'),
				'LastUpdatedBy' => $this->input->post('LastUpdatedBy'),
				'LastUpdatedOn' => $this->input->post('LastUpdatedOn'),
			);
		$this->unittype->update(array('UnitTypeId' => $this->input->post('UnitTypeId')), $data);
		echo json_encode(array("status" => TRUE));
	}
	private function _validate()
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

		if($this->input->post('UnitTypeName') == '')
		{
			$data['inputerror'][] = 'UnitTypeName';
			$data['error_string'][] = 'Unit Type name is required';
			$data['status'] = FALSE;
		}

		if($data['status'] === FALSE)
		{
			echo json_encode($data);
			exit();
		}
	}

}
