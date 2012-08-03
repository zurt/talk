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
		
		$this->load->library('gravatar');
	}

	function index($inviteUuid = 0) {
		if ($this->tank_auth->is_logged_in()) {
			//get the group id for that invite
			if ($inviteUuid==0) {
				$inviteUuid =  $this->input->post('inviteUuid');
			}
			if ($inviteUuid != "0") {
				
				$groupUuid = $this->invite_model->get_invite($inviteUuid);
				
				//if the group is active, then the member can join
				if($this->group_model->is_group_active($groupUuid) == 1) {
					//if the group has less than 8 people, then the member can join
					if($this->group_model->get_member_count($groupUuid) < 8) {
						//then join the member
						$updateData['groupUuid'] = $groupUuid;
						$updateData['userId'] = $this->tank_auth->get_user_id();
						$updateData['active'] = 1;
						$updateData['dateJoined'] = date('Y-m-d H:i:s');
			
						$count = $this->group_model->is_member_of_group($updateData);
						//error_log($count);
						if($count == 0) {
							$data['newGroup'] = $this->group_model->add_member($updateData);
							$this->group_model->increase_member_count($updateData);
						}
					
						//then redirect them to the group page
						redirect('/group/' . $groupUuid);
					}
					else {
						$message = "Group is full";
						$this->session->set_flashdata('message', $message);
					}
				}
				else {
					$message = "Group is no longer active";
					$this->session->set_flashdata('message', $message);
				}	
			}
			redirect("/");
		}
		else {
			//redirect for sign-up
		}
	}//of index

	
}

/* End of file invite.php */
/* Location: ./application/controllers/invte.php */