<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Post extends CI_Controller
{
	function __construct()
	{
		parent::__construct();

		$this->load->model('post_model');

		$this->load->helper('url');
		$this->load->helper('strip_html_tags');
		$this->load->helper('close_tags');
		
		$this->load->library('tank_auth');
		$this->load->library('encrypter');
	}

	function index() {
	}//of index
	
	function addPost() {

		if ($this->tank_auth->is_logged_in()) {
			$updateData['author'] = $userId = $this->tank_auth->get_user_id();		
			//grab the name of the group and then add it to the db
			$updateData['groupUuid'] = $this->input->post('groupUuid');
			$updateData['postUuid'] = uniqid();
			
			$post = strip_html_tags($this->input->post('post'), 'img|b|i|strong');
			$post = close_tags($post);
			//error_log($post);
			if ($post != "") {
				$updateData['content'] = $this->encrypter->encryptData($post);
				$postUuid = $this->post_model->add_post($updateData);
			}
			redirect('/group/' . $updateData['groupUuid']);
		}
	}//of addPost
	
}

/* End of file post.php */
/* Location: ./application/controllers/post.php */