
<p>Hi, <strong><?php echo $username; ?></strong>! You are logged in now. <?php echo anchor('/auth/logout/', 'Logout'); ?></p>

<h3>Make a new group</h3>

<form action="/group/addGroup" method="post">
	Group name:
	<input type="text" name="groupname" rel="tooltip" data-original-title="The group name you want people to see.">
	<input type="submit" value="Submit" id="newgroup_Submit" class="btn">
</form>

<h3>Your groups</h3>
<?
//list groups
?>

<h3>Close a group</h3>