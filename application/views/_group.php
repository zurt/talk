<div class="row">
	<div class="span8">
	<?
//list posts
foreach($posts as $post) { ?>
	<div class="post">
		<div class="postHeader" id="<? echo $post->postUuid; ?>">
			<a name="<? echo $post->postUuid; ?>"><? echo "<img src=\"" . $post->image . "\">";
			echo " " . $post->username . ", " . $post->dateCreated;
			?></a>
		</div>
		<div class="postContent">
	<? echo $post->content;
	?>
	</div>
</div>
<? } ?>


<?
if (count($posts)==0) {
	echo "<h3>First Post!</h3>";
}
else {
	echo "<h3>Post</h3>";
}
?>
<form action="/group/addPost" method="post">
	<textarea name="post"></textarea>
	<input type="hidden" name="groupUuid" value="<? echo $group->groupUuid; ?>">
	<br>
	<input type="submit" value="Submit" id="newgroup_Submit" class="btn">
</form>
</div>



	<div class="span4">
<h3>Members</h3>
<?
foreach($members as $member) {
	echo "<div class=\"memberListItem\">";
	echo "<img src=\"" . $member->image . "\">";
	echo " " . $member->username . ", joined " . $member->dateJoined;
	echo "</div>";
}
?>
</div></div>