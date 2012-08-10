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

	public function index() {
		if (!$this->tank_auth->is_logged_in()) {
			
			$data= array();
			
			$this->load->view('templates/header2', $data);
			$this->load->view('_index', $data);
			$this->load->view('templates/footer', $data);			
			//redirect('/auth/login/');
		} else {
			$data['user_id'] = $this->tank_auth->get_user_id();
			$data['username'] = $this->tank_auth->get_username();
			
			//get user info
			$data['user'] = $this->users->get_user_by_id($data['user_id'], 1);
			//get groups
			$data['groups'] = $this->group_model->get_member_groups($data['user_id']);
			
			
			foreach($data['groups'] as $group) {
				$group->postCount = $this->group_model->get_total_posts($group->groupUuid);
				$group->unseenPostCount = $this->group_model->get_unseen_posts($data['user_id'], $group->groupUuid);
				$group->members = $this->group_model->get_members($group->groupUuid);
				foreach($group->members as $member) {
					$member->image = $this->gravatar->buildGravatarURL($member->email);
				}
			}
			
			$this->load->view('templates/header2', $data);
			$this->load->view('_main', $data);
			$this->load->view('templates/footer', $data);
		}
	}//of index
	
	
	public function about() {
		$data['user_id'] = $this->tank_auth->get_user_id();
		$data['username'] = $this->tank_auth->get_username();
		
		//get user info
		$data['user'] = $this->users->get_user_by_id($data['user_id'], 1);
		//get groups
		$data['groups'] = $this->group_model->get_member_groups($data['user_id']);
		
		$this->load->view('templates/header2', $data);
		$this->load->view('_about', $data);
		$this->load->view('templates/footer', $data);		
	}
	
	public function test() {
		$data['user_id'] = $this->tank_auth->get_user_id();
		$data['username'] = $this->tank_auth->get_username();
		
		//get user info
		$data['user'] = $this->users->get_user_by_id($data['user_id'], 1);
		
				
		$this->load->view('templates/header2', $data);
		$this->load->view('_test', $data);
		$this->load->view('templates/footer', $data);		
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */