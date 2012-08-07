<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mail {

	/**
	 * Library function to send messages through email
	 *
	 */
	
	public function __construct() {
		//parent::__construct();
	
		$this->ci =& get_instance();
		
		//$this->ci->load->library('curl');
	}
	
	
	
	public function sendMail($to, $from, $subject, $post) {
		/*
		curl -s -k --user api:key-24sviiqk3e4xkty-ce-70h5p2qorpe72 \
		    https://api.mailgun.net/v2/talktrippp.mailgun.org/messages \
		    -F from='Excited User <tripp@madeofglass.com>' \
		    -F to=trippp@gmail.com\
		    -F subject='Hello' \
		    -F text='Testing some Mailgun awesomness!'
		*/
		
		//$this->ci->curl->ssl(FALSE);
		//$this->ci->curl->http_login($username = '', $password = '', $type = 'any');
		
		$ch = curl_init("https://api.mailgun.net/v2/talktrippp.mailgun.org/messages");
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, array('from'=>$from, 'to'=>$to, 'subject'=>$subject, 'text'=>$post));
		curl_setopt($ch, CURLOPT_USERPWD, "api:key-24sviiqk3e4xkty-ce-70h5p2qorpe72");
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
		//curl_setopt($ch, CURLOPT_MUTE, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$result = curl_exec($ch);
		curl_close($ch);
		
		//$return = $this->ci->curl->simple_post('https://api.mailgun.net/v2/talktrippp.mailgun.org/messages', array('from'=>$from, 'to'=>$to, 'subject'=>$subject, 'text'=>$post), array(CURLOPT_SSL_VERIFYPEER=>FALSE, CURLOPT_USERPWD=>"api:key-24sviiqk3e4xkty-ce-70h5p2qorpe72"));
		//, CURLOPT_MUTE=>1
		
		//$this->ci->curl->debug_request();
		//$this->ci->curl->debug();
		//error_log($to . " " . $from . " " . $post);
	}

}