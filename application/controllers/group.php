<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Group extends CI_Controller
{
	function __construct()
	{
		parent::__construct();

		$this->load->model('group_model');

		$this->load->helper('url');
		$this->load->library('tank_auth');
	}

	function index() {
	}//of index
	
	function addGroup() {

		if ($this->tank_auth->is_logged_in()) {
			$updateData['creatorId'] = $userId = $this->tank_auth->get_user_id();		
			//grab the name of the group and then add it to the db
			$updateData['groupName'] = $this->input->post('groupname');
			$updateData['groupUuid'] = uniqid();
			$updateData['active'] = 1;
		
			$groupId = $this->group_model->add_group($updateData);
			
			//now add the user to the group
			$updateData=array();
			$updateData['groupId'] = $groupId;
			$updateData['userId'] = $userId;
			$updateData['active'] = 1;
			$data['newGroup'] = $this->group_model->add_member($updateData);
			
		}
		
		redirect('/');
		//$this->load->view('templates/header', $data);
		//$this->load->view('main', $data);
		//$this->load->view('templates/footer', $data);
	}
	
	function leaveGroup() {
		if ($this->tank_auth->is_logged_in()) {
			$data['memberId'] = $this->tank_auth->get_user_id();		
			$data['groupUuid'] = $this->input->post('groupUuid');
			$updateData['active'] = 0;
			$data['newGroup'] = $this->group_model->remove_member($data, $updateData);
			
			//if there are no members in a group anymore, make the group inactive
			
		}
		
		redirect('/');
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */