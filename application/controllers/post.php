<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Post extends CI_Controller
{
	function __construct()
	{
		parent::__construct();

		$this->load->model('post_model');
		$this->load->model('group_model');
		$this->load->model('user_model');

		$this->load->helper('url');
		$this->load->helper('strip_html_tags');
		$this->load->helper('close_tags');
		//$this->load->helper('auto_link');
		
		$this->load->library('tank_auth');
		$this->lang->load('tank_auth');
		$this->load->config('tank_auth', TRUE);
		
		$this->load->library('encrypter');
		$this->load->library('mail');
		$this->load->library('bbcode');
	}

	function index() {
	}//of index
	
	function addPost() {

		if ($this->tank_auth->is_logged_in()) {
			
			$updateData['author'] = $userId = $this->tank_auth->get_user_id();		
			//grab the name of the group and then add it to the db
			$updateData['groupUuid'] = $this->input->post('groupUuid');
			$updateData['postUuid'] = uniqid();
			$updateData['dateCreated'] = date('Y-m-d H:i:s');
			
			$post = strip_html_tags($this->input->post('post'), 'img|b|i|strong');
			$post = close_tags($post);
			
			$updateData['originalContent']=$post;
			
			$post = auto_link($post);
			$post = $this->bbcode->bbcode2html($post);
			//echo $post;
			
			//error_log($post);
			
			//let's send an email about this post...
			//we aren't currently queueing them up, which is kind of a pisser
			//but it seems appfog doesn't support cron jobs
			//i need to look into ironworker to see if that will serve the need
			
			$footer = "\n\n----------------\n";
			$footer .= "Did you know that if you reply to this email, your reply will be automagically posted to the group?  It's true.\n\n";
			$footer .= "Want to see the rest of the conversation?  Drop in on it here: http://talk.aws.af.cm/group/" . $updateData['groupUuid'] . "\n\n";
			
			$footer .= "Don't want to receive email notifications when someone posts to a group?  Toggle the setting at: http://talk.aws.af.cm/user";
			
			$author =  $this->users->get_user_by_id($userId, 1);
			$subject = $author->username . " has a new Cheep message for you!";
			
			$members = $this->group_model->get_members($updateData['groupUuid']);
			foreach($members as $member) {
				if ($member->userId != $updateData['author']) {
					$prefs = $this->user_model->get_user_prefs($member->userId);
				
					if(!empty($prefs) && $prefs[0]->email_notif == 1) {
						//$emailPost = $author . " said:\n" . $post . $footer;
						$group = $this->group_model->get_group_info($updateData['groupUuid']);
						$emailPost = $author->username . " has posted to " . $group->groupName . ":";
						$emailPost .= "\n" . $post;
						$emailPost .= "\n\nView it here: http://talk.aws.af.cm/group/" . $updateData['groupUuid'] . "#" . $updateData['postUuid'] . "\n\n";
						$emailPost .= $footer;
						$sendAddress = "cheep+" . $updateData['groupUuid'] . "@talktrippp.mailgun.org";
						
						$this->mail->sendMail($member->email, $sendAddress, $subject, $emailPost);
					}
				}
			}
			
			if ($post != "") {
				$updateData['content'] = $this->encrypter->encryptData($post);
				$postUuid = $this->post_model->add_post($updateData);
			}
			redirect('/group/' . $updateData['groupUuid'] . "#" . $updateData['postUuid']);
		}
	}//of addPost
	
	
}

/* End of file post.php */
/* Location: ./application/controllers/post.php */