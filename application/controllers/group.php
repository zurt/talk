<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


/*
TO-DO

- set groups as inactive when the last member leaves
- set up an about page to explain what the service currently does
- add a few layout tweaks to the 2 main pages

- filter input boxes to avoid badness/hacking
- limit number of users in a group
- explore other words for 'group' and 'post' (I'm partial to 'clan' at the moment; it has a nice double connotation with both online and meat space.)
- track 'viewed' messages per user
- scroll to last viewed message on a page
- think about rss feeds for groups (with security somehow)
- daily digests
- realtime messages
- presence in some fashion (maybe not a 'this person is online', but perhaps a 'someone is entering a response' ala imessage)
- get a domain
- set up analytics
- make an admin page / panel
- encrypt group names, not just posts
- explore group 'topic' header
- explore prompts a little
- use the hell out of it!


*/


class Group extends CI_Controller
{
	function __construct()
	{
		parent::__construct();

		$this->load->model('group_model');
		$this->load->model('post_model');

		$this->load->helper('url');
		$this->load->helper('date');
		
		$this->load->library('tank_auth');
		$this->lang->load('tank_auth');
		$this->load->config('tank_auth', TRUE);
		
		$this->load->library('gravatar');
		$this->load->library('encrypter');
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
					$posts[$i]->content = $this->encrypter->decryptData($posts[$i]->content);
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