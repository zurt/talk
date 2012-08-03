<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Post extends CI_Controller
{
	function __construct()
	{
		parent::__construct();

		$this->load->model('group_model');
		$this->load->model('post_model');

		$this->load->helper('url');
		$this->load->library('tank_auth');
		
		$this->load->library('encrypt');
	}

	function index() {
	}//of index
	
	function addPost() {

		if ($this->tank_auth->is_logged_in()) {
			$updateData['author'] = $userId = $this->tank_auth->get_user_id();		
			//grab the name of the group and then add it to the db
			$updateData['groupUuid'] = $this->input->post('groupUuid');
			$updateData['postUuid'] = uniqid();
			//$updateData['content'] = $this->input->post('post');
			$updateData['content'] = $this->encrypt->_encrypt($this->input->post('post'));
		
			$postUuid = $this->post_model->add_post($updateData);
			redirect('/group/' . $updateData['groupUuid']);
		}
		
		//$this->load->view('templates/header', $data);
		//$this->load->view('main', $data);
		//$this->load->view('templates/footer', $data);
	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */