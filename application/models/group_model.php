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
	
	
	public function increase_member_count($data) {
		$this->db->where("groupUuid", $data['groupUuid']);
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
		$this->db->join("group", "member.groupUuid=group.groupUuid");
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
	
		
	public function remove_member($data, $updateData) {
		$this->db->where("groupUuid", $data['groupUuid']);
		$this->db->where('userId', $data['userId']);
		$this->db->set($updateData);
		$this->db->update("member");
		error_log($this->db->last_query());
	}
}

?>