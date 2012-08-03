<h4 class="mainHeader">Join a Group</h4>
<form action="/invite" method="post">
	Invite code:
	<input type="text" name="inviteUuid">
	<input type="submit" value="Submit" id="invite_Submit" class="btn">
</form>



<? if (count($groups) > 0) { ?>
<h4 class="mainHeader">Your Groups</h4>
<?
//list groups
foreach($groups as $group) {
	echo "<a href='/group/" . $group->groupUuid . "'>" . $group->groupName . "</a> (" . $group->memberCount . " member";
	if ($group->memberCount != 1) {
		echo "s";
	}
	echo ") (Invite code: " . $group->inviteUuid;
	echo " )<br>";
}
}
?>



<h4 class="mainHeader">Make a new group</h4>
<form action="/addGroup" method="post">
	Group name:
	<input type="text" name="groupname" rel="tooltip" data-original-title="The group name you want people to see.">
	<input type="submit" value="Submit" id="newgroup_Submit" class="btn">
</form>




<? if (count($groups) > 0) { ?>
<h4 class="mainHeader">Leave a group</h4>
<form action="/leaveGroup" method="post">
<?
//list groups
foreach($groups as $group) {
	echo '<input type="radio" name="groupUuid" value="' . $group->groupUuid . '">';
	echo " " . $group->groupName . "<br>";
}
?>
	<input type="submit" value="Submit" id="leaveGroup_Submit" class="btn">
</form>
<? } ?>