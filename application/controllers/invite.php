<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Invite extends CI_Controller
{
	function __construct()
	{
		parent::__construct();

		$this->load->model('group_model');
		$this->load->model('post_model');
		$this->load->model('invite_model');

		$this->load->helper('url');
		$this->load->library('tank_auth');
	}

	function index($inviteUuid = 0) {
		if ($this->tank_auth->is_logged_in()) {
			//get the group id for that invite
			if ($inviteUuid==0) {
				$inviteUuid =  $this->input->post('inviteUuid');
			}
			if ($inviteUuid != 0) {
			
				$groupUuid = $this->invite_model->get_invite($inviteUuid);
			
				//then join the member
				$updateData['groupUuid'] = $groupUuid;
				$updateData['userId'] = $this->tank_auth->get_user_id();
				$updateData['active'] = 1;
				$data['newGroup'] = $this->group_model->add_member($updateData);
				$this->group_model->increase_member_count($updateData);
			
				//then redirect them to the group page
				redirect('/group/' . $groupUuid);
			}
			else {
				redirect("/");
			}
		}
		else {
			//redirect for sign-up
		}
	}//of index

	
}

/* End of file invite.php */
/* Location: ./application/controllers/invte.php */