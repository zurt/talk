<?php
class Group_model extends CI_Model {

	public function __construct() {
		$this->load->database();
	}
	
	
	public function add_group($data) {
		//requires: uniqId, userId, comment
		
		$data['dateCreated'] = date('Y-m-d H:i:s');
		$this->db->insert('group', $data);
		return ($data['groupUuid']);	
	}
	
	public function update_group($data, $updateData) {
		$this->db->where("groupUuid", $data['groupUuid']);
		$this->db->set($updateData);
		$this->db->update("group");
	}
	
	public function is_group_active($groupUuid) {
		$this->db->select("active");
		$this->db->from("group");
		$this->db->where("groupUuid", $groupUuid);
		$query = $this->db->get();
		$return = $query->row();
		//error_log($this->db->last_query());
		return $return->active;
	}
	
	public function get_member_count($groupUuid) {
		$this->db->select("memberCount");
		$this->db->from("group");
		$this->db->where("groupUuid", $groupUuid);
		$query = $this->db->get();
		//error_log($this->db->last_query());
		return $query->row();	
	}
	
	public function increase_member_count($data) {
		$this->db->where("groupUuid", $data['groupUuid']);
		$this->db->set("memberCount", "memberCount+1", FALSE);
		$this->db->update("group");
	}
	
	public function decrease_member_count($data) {
		$this->db->where("groupUuid", $data['groupUuid']);
		$this->db->set("memberCount", "memberCount-1", FALSE);
		$this->db->update("group");
		error_log($this->db->last_query());	
	}
	

	public function get_member_groups($userId) {
		$this->db->select("*");
		$this->db->from("member");
		$this->db->join("group", "member.groupUuid=group.groupUuid");
		$this->db->where("userId", $userId);
		$this->db->where("member.active", 1);
		//$this->db->order_by("date", "DESC");
		
		$query = $this->db->get();
		//error_log($this->db->last_query());
		return $query->result();
	}


	public function is_member_of_group($data) {
		$this->db->select("*");
		$this->db->from("member");
		$this->db->where("userId", $data['userId']);
		$this->db->where("groupUuid", $data['groupUuid']);
	
		//$query = $this->db->get();		
		return ($this->db->count_all_results());
	}


	public function add_member($data) {
		//requires: userId, groupId
		$this->db->insert('member', $data);
	}
	
		
	public function remove_member($data, $updateData) {
		$this->db->where("groupUuid", $data['groupUuid']);
		$this->db->where('userId', $data['userId']);
		$this->db->set($updateData);
		$this->db->update("member");
		//error_log($this->db->last_query());
	}
	
	public function get_members($groupUuid) {
		$this->db->select("username, email, m.userId");
		$this->db->from("member as m");
		$this->db->join("users", "m.userId=users.id");
		$this->db->join("group", "m.groupUuid=group.groupUuid");
		$this->db->where("m.active", 1);
		//$this->db->order_by("date", "DESC");
		
		$query = $this->db->get();
		//error_log($this->db->last_query());
		return $query->result();		
	}
}

?>