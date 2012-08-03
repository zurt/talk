<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Group extends CI_Controller
{
	function __construct()
	{
		parent::__construct();

		$this->load->model('group_model');
		$this->load->model('post_model');
//		$this->load->model('tabk_auth/');

		$this->load->helper('url');
		$this->load->helper('date');
		
		$this->load->library('tank_auth');
		$this->lang->load('tank_auth');
		$this->load->config('tank_auth', TRUE);
		
		$this->load->library('gravatar');
		$this->load->library('encrypt');
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
			$updateData['memberCount'] = 1;
			$updateData['inviteUuid'] = sha1(uniqid());
		
			$groupUuid = $this->group_model->add_group($updateData);
			error_log($groupUuid);
			//now add the user to the group
			$updateData=array();
			$updateData['groupUuid'] = $groupUuid;
			$updateData['userId'] = $userId;
			$updateData['active'] = 1;
			$data['newGroup'] = $this->group_model->add_member($updateData);
		}
		redirect('/');
	}
	
	function leaveGroup() {
		if ($this->tank_auth->is_logged_in()) {
			$data['userId'] = $this->tank_auth->get_user_id();		
			$data['groupUuid'] = $this->input->post('groupUuid');
			$updateData['active'] = 0;
			$this->group_model->remove_member($data, $updateData);
			
			$this->group_model->decrease_member_count($data);
			
			//if there are no members in a group anymore, make the group inactive
			if($this->group_model->get_member_count($data['groupUuid']) == 0) {
				$updateData['active']=0;
				$this->group_model->update_group($data, $updateData);
			}
		}
		
		redirect('/');
	}
	
	function viewGroup($groupUuid = 0) {
		$data= array();
		if ($this->tank_auth->is_logged_in()) {
			if ($groupUuid != 0) {
				$data['user_id'] = $this->tank_auth->get_user_id();
				$data['username'] = $this->tank_auth->get_username();
				$data['groupUuid'] = $groupUuid;

				//get user info
				$data['user'] = $this->users->get_user_by_id($data['user_id'], 1);

				//get the group data
				$posts = $this->post_model->get_posts($groupUuid);

				for($i=0; $i < count($posts); $i++) {
					$posts[$i]->content = $this->encrypt->_decrypt($posts[$i]->content);
				}
				
				$data['posts'] = $posts;
				foreach($data['posts'] as $post) {
					$post->image = $this->gravatar->buildGravatarURL($post->email);
					$post->dateCreated = relative_time($post->dateCreated);
				}

				//get members in group
				$data['members'] = $this->group_model->get_members($groupUuid);
				foreach($data['members'] as $member) {
					$member->image = $this->gravatar->buildGravatarURL($member->email);
				}
				
				//spit it out
				$this->load->view('templates/header2', $data);
				$this->load->view('_group', $data);
				$this->load->view('templates/footer', $data);
			}
		}
	}
}

/* End of file group.php */
/* Location: ./application/controllers/group.php */