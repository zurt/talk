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
		
		$this->load->library('tank_auth');
		$this->lang->load('tank_auth');
		$this->load->config('tank_auth', TRUE);
		
		$this->load->library('encrypter');
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
	
		$this->post_model->add_email_content($data);

			
		if ($data['stripped-text'] != "") {
			//need to take the sender and look up the user by email
			$user = $this->users->get_user_by_login($data['sender']);

			//if the user doesn't belong, join the user
			if (empty($user)) {
				//
			}
			
			if (isset($user)) {
				//then post it
		
				$updateData['author'] = $user->id;
				$updateData['content'] = $this->encrypter->encryptData($data['stripped-text']);
				$updateData['postUuid'] = uniqid();

				//need to parse the 'recipient' field to get the group id
				//$match = preg_match ( "/[\+@]+/", $data['recipient']);
				$match = explode("@", $data['recipient']);
				$match = explode("+", $match[0]);
				$updateData['groupUuid'] = $match[1];

				//convert the timestamp
				$updateData['dateCreated'] = date("Y-m-d H:i:s", $data['timestamp']);

				//log_message("error", $updateData['groupUuid'] . " " . $updateData['author']);

				$postUuid = $this->post_model->add_post($updateData);
			}
		}
		
		$this->response("", 200); // 200 being the HTTP response code
    }//of game_post (new game)
    

	
}