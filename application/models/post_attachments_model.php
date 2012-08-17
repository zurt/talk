<?php
class Post_attachments_model extends CI_Model {

	public function __construct() {
		$this->load->database();
	}
	
	public function store_uri($data) {
		$this->db->insert('post-attachments', $data);
		return ($this->db->insert_id());
	}
	
	public function get_uri($postUuid) {
		$this->db->select("uri");
		$this->db->where("postUuid", $postUuid);
		$this->db->like('mimeType', 'image');
		$this->db->from('post-attachments');
		$query = $this->db->get();
		//error_log($this->db->last_query());
		return $query->result();
	}

}//of invites