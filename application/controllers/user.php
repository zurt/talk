<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Controller
{
	function __construct()
	{
		parent::__construct();

		
		$this->load->model('user_model');

		$this->load->helper('url');
		//$this->load->helper('strip_html_tags');
		//$this->load->helper('close_tags');
		
		$this->load->library('tank_auth');
		$this->lang->load('tank_auth');
		$this->load->config('tank_auth', TRUE);
		
		//$this->load->library('encrypter');
		//$this->load->library('mail');
		
	}

	function index() {
		$data['user_id'] = $this->tank_auth->get_user_id();
		$data['username'] = $this->tank_auth->get_username();
		
		//get user info
		$data['user'] = $this->users->get_user_by_id($data['user_id'], 1);
		
		//get user prefs
		$data['prefs'] = $this->user_model->get_user_prefs($data['user_id']);
		
		//print_r($data['prefs']);
		$this->load->view('templates/header2', $data);
		$this->load->view('_user_prefs', $data);
		$this->load->view('templates/footer', $data);	
	}//of index
	
	
	function prefs() {
		if ($this->tank_auth->is_logged_in()) {
			$updateData['email_notif'] = $this->input->post('email_notif');
			if($updateData['email_notif'] != 1) {
				$updateData['email_notif'] = 0;
			}
			$updateData['user_id'] = $userId = $this->tank_auth->get_user_id();
			//$updateData['groupName'] = $this->input->post('groupname');
		
			$groupUuid = $this->user_model->update_email_prefs($updateData);

			redirect('/user/');
		}
	}//of prefs
	
	
}

/* End of file post.php */
/* Location: ./application/controllers/post.php */