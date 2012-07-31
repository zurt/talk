<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Group extends CI_Controller
{
	function __construct()
	{
		parent::__construct();

		$this->load->helper('url');
		$this->load->library('tank_auth');
	}

	function index() {
	}//of index
	
	function addGroup() {
		$data = array();

		if ($this->tank_auth->is_logged_in()) {
			$userId = $this->tank_auth->get_user_id();		
			//grab the name of the group and then add it to the db
			$groupName = $this->input->post('groupname');
		
			$data['newGroup'] = $this->group_model->add_group($userId, $groupName);
		}
		
		$this->load->view('templates/header', $data);
		//$this->load->view('main', $data);
		$this->load->view('templates/footer', $data);
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */