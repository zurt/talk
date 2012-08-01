
<p>Hi, <strong><?php echo $username; ?></strong>! You are logged in now. <?php echo anchor('/auth/logout/', 'Logout'); ?></p>

<h3>Make a new group</h3>

<form action="/group/addGroup" method="post">
	Group name:
	<input type="text" name="groupname" rel="tooltip" data-original-title="The group name you want people to see.">
	<input type="submit" value="Submit" id="newgroup_Submit" class="btn">
</form>

<h3>Groups you belong to:</h3>
<?
//list groups
foreach($groups as $group) {
	echo $group->groupName . "<br>";
}
?>

<h3>Leave a group</h3>
<form action="/group/leaveGroup" method="post">
<?
//list groups
foreach($groups as $group) {
	echo '<input type="radio" name="groupUuid" value="' . $group->groupUuid . '">';
	echo $group->groupName . "<br>";
}
?>
	<input type="submit" value="Submit" id="newgroup_Submit" class="btn">
</form>

<h3>Close a group</h3>