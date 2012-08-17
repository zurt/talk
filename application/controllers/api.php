<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Example
 *
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array.
 *
 * @package		CodeIgniter
 * @subpackage	Rest Server
 * @category	Controller
 * @author		Phil Sturgeon
 * @link		http://philsturgeon.co.uk/code/
*/

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH.'/libraries/REST_Controller.php';

class Api extends REST_Controller
{
	public function __construct() {
		parent::__construct();

		$this->load->model('post_model');
		$this->load->model('post_attachments_model');
		$this->load->model('group_model');
		
		$this->load->library('tank_auth');
		$this->lang->load('tank_auth');
		$this->load->config('tank_auth', TRUE);
		
		$this->load->library('encrypter');
		$this->load->library('s3');
		$this->load->helper('url');
		$this->load->helper('strip_html_tags');
		$this->load->helper('close_tags');
		
	}


    /*
	add a new post
	*/
    function message_post() {
		//this is for incoming message from mailgun
		//a huge list of supported message elements
		/*
		recipient			string
		sender				string
		from				string
		subject				string
		body-plain			string
		stripped-text		string
		stripped-signature	string
		body-html			string
		stripped-html		string
		attachment-count	int
		attachment-x		string
		timestamp			int
		token				string
		signature			string
		message-headers		string
		content-id-map 		string
		*/
		
		
		$data['recipient'] = $this->input->post('recipient');
		$data['sender'] = $this->input->post('sender');
		$data['from'] = $this->input->post('from');
		$data['subject'] = $this->input->post('subject');
		$data['body-plain'] = $this->input->post('body-plain');
		$data['stripped-text'] = $this->input->post('stripped-text');
		$data['stripped-signature'] = $this->input->post('stripped-signature');
		$data['body-html'] = $this->input->post('body-html');
		$data['stripped-html'] = $this->input->post('stripped-html');
		$data['attachment-count'] = $this->input->post('attachment-count');
		$data['attachment-x'] = $this->input->post('attachment-x');
		$data['timestamp'] = $this->input->post('timestamp');
		$data['token'] = $this->input->post('token');
		$data['signature'] = $this->input->post('signature');
		$data['message-headers'] = $this->input->post('message-headers');
		$data['content-id-map'] = $this->input->post('content-id-map');
			
		//save images
		/*if($data['attachment-count'] > 0) {
			$data['attachment-content'] = $this->input->post('attachment-1');
		}*/
		
		//need to parse the 'recipient' field to get the group id
		//$match = preg_match ( "/[\+@]+/", $data['recipient']);
		$match = explode("@", $data['recipient']);
		$match = explode("+", $match[0]);
		$updateData['groupUuid'] = $match[1];
		
		$updateData['postUuid'] = uniqid();
		
		// Instantiate the class
		$s3 = new S3($this->config->item('awsAccessKey'),  $this->config->item('awsSecretKey'));
		$bucketName = "jabberlap";
		
		foreach($_FILES as $file){
		    $name = $file['name'];
		    $type = $file['type'];
			$data['attachment-content'] .= $updateData['groupUuid'] . "/" . $name . ":" . $type . "::";
			
			/*
			//this comes out because appfog is fried:
			//uploading code wipes out the old version and downloading source doesn't include changes made on the server
			//which means all ugc will be lost each time there is an upload.  barf.
			mkdir("./tmp/tester/");
			if (!move_uploaded_file($file['tmp_name'], "./tmp/tester/$name")) 
				$data['attachment-content'] .= "CANNOT MOVE $name" . PHP_EOL;
			*/

			// Put our file (also with public read access)
			$s3->putObjectFile($file['tmp_name'], $bucketName, $updateData['groupUuid'] . "/" . $name, S3::ACL_PUBLIC_READ);
			
			//now we need to store the attachment details in the post-attachment table, so we can easily get them out later
			$postData['postUuid'] = $updateData['postUuid'];
			$postData['uri'] = $updateData['groupUuid'] . "/" . $name;
			$postData['mimeType'] = $type;
			$this->post_attachments_model->store_uri($postData);
		}
	
		$this->post_model->add_email_content($data);
		
		/*
		TO-DO:
		- check that the group actually exists before doing any of this
		- a better way of assigning user names to people -- something that ensures uniqueness
		*/
			
		if ($data['stripped-text'] != "" || $data['attachment-count'] !=0) {
			//need to take the sender and look up the user by email
			$user = $this->users->get_user_by_login($data['sender']);

			//if the user doesn't belong, join the user
			if (empty($user)) {
				//the user isn't in the system
				$username = explode("<", $data['from']);
				$username=$username[0];
				
				$password = "";
				for($i=0; $i<6; $i++) {
					$password .= chr(rand(0, 25) + ord('a'));
				}
				
				$userTemp = $this->tank_auth->create_user($username, $data['sender'], $password, 0);
				$user->id = $userTemp['user_id'];
				
				//send an email with password
				$data['password'] = $password;
				$data['site_name'] = $this->config->item('website_name', 'tank_auth');
				$data['username'] = $data['sender'];
				$data['email'] = $data['sender'];
				
				//$this->tank_auth->_send_email($user->id, $data['sender'], &$data);
				$type="assign-password";
				
				$this->load->library('email');
				$this->email->from($this->config->item('webmaster_email', 'tank_auth'), $this->config->item('website_name', 'tank_auth'));
				$this->email->reply_to($this->config->item('webmaster_email', 'tank_auth'), $this->config->item('website_name', 'tank_auth'));
				$this->email->to($data['email']);
				$this->email->subject(sprintf($this->lang->line('auth_subject_'.$type), $this->config->item('website_name', 'tank_auth')));
				$this->email->message($this->load->view('email/'.$type.'-html', $data, TRUE));
				$this->email->set_alt_message($this->load->view('email/'.$type.'-txt', $data, TRUE));
				$this->email->send();
			}			
		
			if (isset($user->id)) {
				//then post it
		
				$updateData['author'] = $user->id;
				$updateData['content'] = $this->encrypter->encryptData($data['stripped-text']);

				$post = strip_html_tags($data['stripped-text'], 'img|b|i|strong');
				$post = close_tags($post);
				$updateData['originalContent'] = $post;

				//convert the timestamp
				$updateData['dateCreated'] = date("Y-m-d H:i:s", $data['timestamp']);

				//join group
				
				$data2['userId'] = $updateData['author'];
				$data2['groupUuid'] = $updateData['groupUuid'];
				//echo $this->group_model->is_member_of_group($data2);
				if($this->group_model->is_member_of_group($data2)==0) {
					
					if($this->group_model->get_member_count($data2['groupUuid']) < 8) {
						//then join the member
						$data2['active'] = 1;
						$data2['dateJoined'] = date('Y-m-d H:i:s');
		
						$count = $this->group_model->is_member_of_group($data2);
						
						if($count == 0) {
							$data['newGroup'] = $this->group_model->add_member($data2);
							$this->group_model->increase_member_count($data2);
						}
					}
				}
				
				//add post
				$postUuid = $this->post_model->add_post($updateData);
			}
		}
		
		$this->response("", 200); // 200 being the HTTP response code
    }//of game_post (new game)
    

	
}