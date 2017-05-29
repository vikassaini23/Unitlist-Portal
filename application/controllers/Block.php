<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Block extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Block_model','block');
	}

	public function index()
	{
		$this->load->helper('url');

		$this->load->view('template/submenu');	
		$data['getLastInserted'] = $this->block->getLastInserted();	
		$this->load->view('block_view');
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


	public function ajax_list()
	{
		$list = $this->block->get_datatables();
		
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $block) {
			$no++;
			$row = array();
			$row[] = $block->BlockId;
			$row[] = $block->PrathamBlockName;
			$row[] = $block->CensusBlockName;
			$row[] = $block->DISEBlockName;
			$row[] = $block->BlockCode;
			$row[] = $block->PrathamDistrictName;
			$row[] = $block->StateName;


			//add html for action
			$row[] = '<a class="btn btn-sm btn-primary edit_block" href="javascript:void(0)" title="Edit" onclick="edit_block('."'".$block->BlockId."'".')"><i class="glyphicon glyphicon-pencil"></i> Edit</a>
				  <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Delete" onclick="edit_del('."'".$block->BlockId."'".')"><i class="glyphicon glyphicon-trash"></i> Delete</a>';
		
			$data[] = $row;
		}


		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->block->count_all(),
						"recordsFiltered" => $this->block->count_filtered(),
						"state" => $this->block->statename(),
						"district" => $this->block->districtname(),
						"getLastInserted"=>$this->block->getLastInserted(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	
	}

	public function ajax_edit($BlockId)
	{
		 $data = $this->block->get_by_id($BlockId);
		echo json_encode($data);
	}


	public function ajax_add()
	{
		$this->_validate();
		$data = array(
				'DistrictId' => $this->input->post('DistrictId'),
				'PrathamBlockName' => $this->input->post('PrathamBlockName'),
				'IsFoundInCensusList' => $this->input->post('IsFoundInCensusList'),
				'CensusBlockName' => $this->input->post('CensusBlockName'),
				'IsFoundInDISEList' => $this->input->post('IsFoundInDISEList'),
				'DISEBlockName' => $this->input->post('DISEBlockName'),
				'BlockCode' => $this->input->post('BlockCode'),
				'CreatedBy' => $this->input->post('CreatedBy'),
			);
		$insert = $this->block->save($data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_update()
	{
		$this->_validate();
		$data = array(
				'PrathamBlockName' => $this->input->post('PrathamBlockName'),
				'IsFoundInCensusList' => $this->input->post('IsFoundInCensusList'),
				'CensusBlockName' => $this->input->post('CensusBlockName'),
				'IsFoundInDISEList' => $this->input->post('IsFoundInDISEList'),
				'DISEBlockName' => $this->input->post('DISEBlockName'),
				'LastUpdatedBy' => $this->input->post('LastUpdatedBy'),
				'LastUpdatedOn' => $this->input->post('LastUpdatedOn'),
			);
		$this->block->update(array('BlockId' => $this->input->post('BlockId')), $data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_update_del()
	{
		$this->_validate();
		$data = array(
				'PrathamBlockName' => $this->input->post('PrathamBlockName'),
				'CensusBlockName' => $this->input->post('CensusBlockName'),
				'DISEBlockName' => $this->input->post('DISEBlockName'),
				'IsDeleted' => $this->input->post('IsDeleted'),
				'LastUpdatedBy' => $this->input->post('LastUpdatedBy'),
				'LastUpdatedOn' => $this->input->post('LastUpdatedOn'),
			);
		$this->block->update(array('BlockId' => $this->input->post('BlockId')), $data);
		echo json_encode(array("status" => TRUE));
	}

	private function _validate()
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

		if($this->input->post('PrathamBlockName') == '')
		{
			$data['inputerror'][] = 'PrathamBlockName';
			$data['error_string'][] = 'Block name is required';
			$data['status'] = FALSE;
		}
		if($this->input->post('DISEBlockName') == '')
		{
			$data['inputerror'][] = 'DISEBlockName';
			$data['error_string'][] = 'Block name is required';
			$data['status'] = FALSE;
		}
		if($this->input->post('CensusBlockName') == '')
		{
			$data['inputerror'][] = 'CensusBlockName';
			$data['error_string'][] = 'Block name is required';
			$data['status'] = FALSE;
		}
		if($data['status'] === FALSE)
		{
			echo json_encode($data);
			exit();
		}
	}

}
