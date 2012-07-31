<?php
class Group_model extends CI_Model {

	public function __construct() {
		$this->load->database();
	}
	
	
	public function add_group($data) {
		//requires: uniqId, userId, comment
		
		$data['dateCreated'] = date('Y-m-d H:i:s');
		$this->db->insert('group', $data);	
	}
	

	/*
	public function get_comments($uniqId) {
		$this->db->select("c.*, username, email, coalesce(username, email, `first_name` ) AS preferredname");
		$this->db->from("comments as c");
		$this->db->join("users", "users.id=c.userId");
		$this->db->where("gameUuid", $uniqId);
		$this->db->order_by("date", "DESC");
		
		$query = $this->db->get();
		//error_log($this->db->last_query());
		return $query->result();
	}
	*/
}

?>