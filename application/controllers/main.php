<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Main extends CI_Controller
{
	function __construct()
	{
		parent::__construct();

		$this->load->model('group_model');

		$this->load->helper('url');
		$this->load->library('tank_auth');
	}

	function index()
	{
		if (!$this->tank_auth->is_logged_in()) {
			redirect('/auth/login/');
		} else {
			$data['user_id'] = $this->tank_auth->get_user_id();
			$data['username'] = $this->tank_auth->get_username();
			
			$data['groups'] = $this->group_model->get_member_groups($data['user_id']);
			
			$this->load->view('templates/header2', $data);
			$this->load->view('main', $data);
			$this->load->view('templates/footer', $data);
		}
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */