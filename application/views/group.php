
<p>Hi, <strong><?php echo $username; ?></strong>! <?php echo anchor('/auth/logout/', 'Logout'); ?></p>
<p><a href="/">Main page</a></p>


<h3>Posts:</h3>
<?
//list groups
foreach($posts as $post) {
	echo $post->username . " said:<br>";
	echo $post->content;
	echo "<br>";
}
?>


<h3>Post</h3>
<form action="/group/addPost" method="post">
	<textarea name="post"></textarea>
	<input type="hidden" name="groupUuid" value="<? echo $groupUuid; ?>">
	<input type="submit" value="Submit" id="newgroup_Submit" class="btn">
</form>