<?php
class Group_model extends CI_Model {

	public function __construct() {
		$this->load->database();
	}
	
	
	public function add_group($data) {
		//requires: uniqId, userId, comment
		
		$data['dateCreated'] = date('Y-m-d H:i:s');
		$this->db->insert('group', $data);
		return ($this->db->insert_id());	
	}
	
	
	public function add_member($data) {
		//requires: userId, groupId
		$this->db->insert('member', $data);
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
	
	public function remove_member($data) {
		$this->db->where("g.id = groupId");
		$this->db->where('m.userId', $data['memberId']);
		$this->db->where("g.groupUuid", $data['groupUuid']);
		$this->db->set("m.active", 0);
		$this->db->update("member as m, `group` as g");
		error_log($this->db->last_query());
	}
}

?>