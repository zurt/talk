<?php
class Invite_model extends CI_Model {

	public function __construct() {
		$this->load->database();
	}
	
	public function get_invite($inviteUuid) {
		
		$this->db->select('*');
		$this->db->from('group');
		$this->db->where('inviteUuid', $inviteUuid);
		
		$query = $this->db->get();
		//error_log($this->db->last_query());
		return $query->row("groupUuid");
	}
	
	/*
	public function add_invite($uniqId, $userId) {
		$data["inviteUuid"] = uniqid();
		$data['gameUuid'] = $uniqId;
		$data['userId'] = $userId;
		$data['acceptedInvite'] = 0;
		
		if ($this->db->insert('invites', $data)) {
			return $data["inviteUuid"];
		}
		else {
			return 0;
		}
	}
	public function update_invite($inviteUuid, $data) {
		$this->db->where('inviteUuid', $inviteUuid);
		$this->db->update('invites', $data);
	}//of update_invites
	
	
	public function delete_invite($gameUuid) {
		$this->db->delete('invites', array('gameUuid' => $gameUuid));
	}
	*/
}//of invites