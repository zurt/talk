<div class="row">
	<div class="span8">
	<?
//list posts
foreach($posts as $post) { ?>
	<div class="post">
		<div class="postHeader">
			<? echo "<img src=\"" . $post->image . "\">";
			echo " " . $post->username . ", " . $post->dateCreated;
			?>
		</div>
		<div class="postContent">
	<? echo $post->content;
}
?>
	</div>

<h3>Post</h3>
<form action="/group/addPost" method="post">
	<textarea name="post"></textarea>
	<input type="hidden" name="groupUuid" value="<? echo $groupUuid; ?>">
	<input type="submit" value="Submit" id="newgroup_Submit" class="btn">
</form>
</div>
</div>



	<div class="span4">
<h3>Members</h3>
<?
foreach($members as $member) {
	echo $member->username . "<br>";
}
?>
</div></div>