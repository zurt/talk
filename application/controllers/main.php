<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Main extends CI_Controller
{
	function __construct()
	{
		parent::__construct();

		$this->load->model('group_model');

		$this->load->helper('url');
		$this->load->library('tank_auth');
		
		$this->load->library('gravatar');
	}

	function index()
	{
		if (!$this->tank_auth->is_logged_in()) {
			redirect('/auth/login/');
		} else {
			$data['user_id'] = $this->tank_auth->get_user_id();
			$data['username'] = $this->tank_auth->get_username();
			
			//get user info
			$data['user'] = $this->users->get_user_by_id($data['user_id'], 1);
			
			
			//get groups
			$data['groups'] = $this->group_model->get_member_groups($data['user_id']);
			
			$this->load->view('templates/header2', $data);
			$this->load->view('_main', $data);
			$this->load->view('templates/footer', $data);
		}
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */