<?php
class Member_model extends CI_Model {

	public function __construct() {
		$this->load->database();
	}
	
	public function user_last_activity($userId, $groupUuid) {
		$date = date('Y-m-d H:i:s');
		//$sql = 'INSERT INTO user_prefs (user_id, last_activity) VALUES (' . $data['user_id'] . ',"' . $date . '") ON DUPLICATE KEY UPDATE last_activity="' . $date . '"';
		
		$this->db->where("groupUuid", $groupUuid);
		$this->db->where("userId", $userId);
		$this->db->set("dateLastActivity", $date);
		$this->db->update("member");
		//return ($this->db->insert_id());
	}
	
}
?>