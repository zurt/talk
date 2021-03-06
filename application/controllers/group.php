<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


/*
TO-DO

- explore other words for 'group' and 'post' (I'm partial to 'clan' at the moment; it has a nice double connotation with both online and meat space.)
- get a domain
- set up analytics
- make an admin page / panel
- daily digests
- scroll to last viewed message on a page
- think about rss feeds for groups (with security somehow)
- realtime messaging / don't force a refresh
- presence in some fashion (maybe not a 'this person is online', but perhaps a 'someone is entering a response' ala imessage)
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
		$this->load->model('post_attachments_model');
		$this->load->model('member_model');

		$this->load->helper('url');
		$this->load->helper('date');
		$this->load->helper('strip_html_tags');
		
		$this->load->library('form_validation');
		$this->load->library('s3');
		
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
			//grab the name of the group and then add it to the db
			$this->form_validation->set_rules('groupname', 'required|trim|xss_clean|strip_tags');
			
			if ($this->form_validation->run() == TRUE) {
				$updateData['groupName'] = strip_html_tags($this->input->post('groupname'));
				$updateData['creatorId'] = $userId = $this->tank_auth->get_user_id();
				//$updateData['groupName'] = $this->input->post('groupname');
				$updateData['groupUuid'] = uniqid();
				$updateData['active'] = 1;
				$updateData['memberCount'] = 1;
				$updateData['dateCreated'] = date('Y-m-d H:i:s');
				$updateData['inviteUuid'] = sha1(uniqid());
		
				$groupUuid = $this->group_model->add_group($updateData);
				//error_log($groupUuid);
				//now add the user to the group
				$updateData=array();
				$updateData['groupUuid'] = $groupUuid;
				$updateData['userId'] = $userId;
				$updateData['dateJoined'] = date('Y-m-d H:i:s');
				$updateData['active'] = 1;
				$updateData['dateLastActivity'] =  date('Y-m-d H:i:s');
				$data['newGroup'] = $this->group_model->add_member($updateData);
			}
		}
		
		$message = "Group created.  Share the invitation code or email address with others so they can join in on the conversation!";
		$this->session->set_flashdata('message', $message);
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
				$this->group_model->delete_group($data);
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
				//$data['groupUuid'] = $groupUuid;
				//get groups
				$data['groups'] = $this->group_model->get_member_groups($data['user_id']);
				foreach($data['groups'] as $group) {
					//$group->postCount = $this->group_model->get_total_posts($group->groupUuid);
					$group->unseenPostCount = $this->group_model->get_unseen_posts($data['user_id'], $group->groupUuid);
				}

				$data['group'] = $this->group_model->get_group_info($groupUuid);
				
				//get user info
				$data['user'] = $this->users->get_user_by_id($data['user_id'], 1);

				//get the group data
				$posts = $this->post_model->get_posts($groupUuid);

				for($i=0; $i < count($posts); $i++) {
					$posts[$i]->content = $this->encrypter->decryptData($posts[$i]->content);
					//error_log($posts[$i]->content);
				}
				
				$data['posts'] = $posts;
				
				$s3 = new S3($this->config->item('awsAccessKey'),  $this->config->item('awsSecretKey'));
				
				foreach($data['posts'] as $post) {
					$post->image = $this->gravatar->buildGravatarURL($post->email);
					$post->dateCreated = relative_time($post->dateCreated);
					
					//check if we have images
					$uriResults = $this->post_attachments_model->get_uri($post->postUuid);
					foreach($uriResults as $uri) {
						$url = $this->s3->getAuthenticatedURL('jabberlap', $uri->uri, 120);
						$post->content .= "<br><br>\n\n" . "<img src=\"" . $url . "\">";
					}
				}

				//get members in group
				$data['members'] = $this->group_model->get_members($groupUuid);
				foreach($data['members'] as $member) {
					$member->image = $this->gravatar->buildGravatarURL($member->email);
					$member->dateJoined = relative_time($member->dateJoined);
				}
				
				//update the user's last_activity field in user prefs
				$this->member_model->user_last_activity($data['user_id'], $groupUuid);
				
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