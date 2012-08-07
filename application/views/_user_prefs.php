<h4 class="mainHeader">User preferences</h4>
<form action="/user/prefs" method="post">
	<p>
		<input type="checkbox" value="1" name="email_notif" <? if(!empty($prefs) && $prefs[0]->email_notif==1){ echo 'checked="checked"'; }?>> Receive email updates
	</p>
	<input type="submit" value="Submit" id="leaveGroup_Submit" class="btn">
</form>