<?php
class User_model extends CI_Model {

	public function __construct() {
		$this->load->database();
	}
	
	public function user_email_prefs($data) {
		$sql = 'INSERT INTO user_prefs (user_id, email_notif) VALUES (' . $data['user_id'] . ',' . $data['email_notif'] . ') ON DUPLICATE KEY UPDATE email_notif=' . $data['email_notif'];
		$query = $this->db->query($sql);
		//return ($this->db->insert_id());
	}
	
	public function get_user_prefs($userId) {
		$this->db->select("*");
		$this->db->from("user_prefs");
		$this->db->where("user_id", $userId);

		$query = $this->db->get();
		//error_log($this->db->last_query());
		return $query->result();
	}

	
	/*
	public function add_group($data) {
		//requires: uniqId, userId, comment
		
		$data['dateCreated'] = date('Y-m-d H:i:s');
		$this->db->insert('group', $data);
		return ($this->db->insert_id());	
	}
	
	
	public function increase_member_count($data) {
		$this->db->where("group", $data['groupId']);
		$this->db->set("memberCount", "memberCount+1");
		$this->db->update("group");
	}
	
	public function decrease_member_count($data) {
		$this->db->where("groupUuid", $data['groupUuid']);
		$this->db->set("memberCount", "memberCount-1");
		$this->db->update("group");		
	}
	

	public function get_member_groups($userId) {
		$this->db->select("*");
		$this->db->from("member");
		$this->db->join("group", "member.groupId=group.id");
		$this->db->where("userId", $userId);
		$this->db->where("member.active", 1);
		//$this->db->order_by("date", "DESC");
		
		$query = $this->db->get();
		//error_log($this->db->last_query());
		return $query->result();
	}


	public function add_member($data) {
		//requires: userId, groupId
		$this->db->insert('member', $data);
	}
	
		
	public function remove_member($data) {
		$this->db->where("g.id = groupId");
		$this->db->where('m.userId', $data['memberId']);
		$this->db->where("g.groupUuid", $data['groupUuid']);
		$this->db->set("m.active", 0);
		$this->db->update("member as m, `group` as g");
		//error_log($this->db->last_query());
	}
	*/
}

?>