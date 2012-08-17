<?php
class Group_model extends CI_Model {

	public function __construct() {
		$this->load->database();
	}
	
	
	public function add_group($data) {
		//requires: uniqId, userId, comment, date
		$this->db->insert('group', $data);
		return ($data['groupUuid']);	
	}
	
	public function update_group($data, $updateData) {
		$this->db->where("groupUuid", $data['groupUuid']);
		$this->db->set($updateData);
		$this->db->update("group");
	}
	
	public function delete_group($data) {
		$this->db->where("groupUuid", $data['groupUuid']);
		$this->db->set("content", "");
		$this->db->update("post");	
		//error_log($this->db->last_query());	
	}
	
	public function get_group_info($groupUuid) {
		$this->db->select("*");
		$this->db->where("groupUuid", $groupUuid);
		$this->db->from("group");
		$query = $this->db->get();
		//error_log($this->db->last_query());
		return $query->row();	
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
		return $query->row('memberCount');
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
		//error_log($this->db->last_query());	
	}
	

	public function get_member_groups($userId) {
		$this->db->select("group.*");
		$this->db->from("member");
		$this->db->join("group", "member.groupUuid=group.groupUuid");
		$this->db->where("userId", $userId);
		$this->db->where("member.active", 1);
		//$this->db->order_by("date", "DESC");
		
		$query = $this->db->get();
		error_log($this->db->last_query());
		return $query->result();
	}
	
	public function get_total_posts($groupUuid) {
		$this->db->select("count(post.postUuid) as postCount");
		$this->db->from("post");
		$this->db->where("groupUuid", $groupUuid);
		$query = $this->db->get();
		$count = $query->row_array();
		//error_log($this->db->last_query());
		return 	$count['postCount'];		
	}
	
	public function get_unseen_posts($userId, $groupUuid) {
		$this->db->select("postUuid, count(post.postUuid) as unseenPostCount");
		$this->db->from("post");
		//$this->db->join("group", "member.groupUuid=group.groupUuid");
		$this->db->join("member", "member.groupUuid=post.groupUuid");
		$this->db->where("userId", $userId);
		$this->db->where("member.active", 1);
		$this->db->where("member.groupUuid", $groupUuid);
		$this->db->where("member.groupUuid", "post.groupUuid", FALSE);
		$this->db->where("dateLastActivity <", "dateCreated", FALSE);
		//$this->db->order_by("date", "DESC");
		
		$query = $this->db->get();
		/*$count = $query->row_array();
		error_log($this->db->last_query());
		return 	$count['unseenPostCount'];	*/
		return $query->result();
	}


	public function is_member_of_group($data) {
		$this->db->select("count(userId) as count");
		$this->db->from("member");
		$this->db->where("userId", $data['userId']);
		$this->db->where("groupUuid", $data['groupUuid']);
	
		$query = $this->db->get();		
		//error_log($this->db->last_query());
		$count = $query->row_array();
		
		if (isset($count['count'])) {
			return 	$count['count'];
		}
		else {
			return 0;
		}
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
		$this->db->select("username, email, dateJoined, m.userId");
		$this->db->from("member as m");
		$this->db->join("users", "m.userId=users.id");
		$this->db->join("group", "m.groupUuid=group.groupUuid");
		$this->db->where("m.active", 1);
		$this->db->where("m.groupUuid", $groupUuid);
		//$this->db->order_by("date", "DESC");
		
		$query = $this->db->get();
		//error_log($this->db->last_query());
		return $query->result();		
	}
}

?>