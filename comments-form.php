<?php
echo "<p><a name=\"".obt_translate("respond")."\" id=\"respond\"></a></p>\n";
IF ($post->comment_status == "open"){
	echo "<h3>".obt_translate("Write a comment")."</h3>\n";
	echo "<p>".obt_translate("If you want to add your comment on this post, simply fill out the next form").":</p>\n";
	IF (get_option("comment_registration") && !$user_ID) echo "<p>".obt_translate("You have to be logged-in to write a comment").": <a href=\"".get_option("siteurl")."/wp-login.php?redirect_to=".urlencode($_SERVER['REQUEST_URI'])."\">(".obt_translate("Log-in").")</a>.</p>\n";
	ELSE {
		include(TEMPLATEPATH."/ads/before-comments-form.php");
		echo "<form action=\"".get_option("siteurl")."/wp-comments-post.php\" method=\"post\" id=\"comments-post\">\n";
		IF ($user_ID) echo "<p>".obt_translate("Logged-in as %1","<a href=\"".get_option("siteurl")."/wp-admin/profile.php\" title=\"".obt_translate("Profile for this account")."\"><strong>{$user_identity}</strong></a>")." <a href=\"".((function_exists("wp_logout_url"))? wp_logout_url($_SERVER["REQUEST_URI"]) : get_option("siteurl")."/wp-login.php?action=logout&amp;redirect_to=".urlencode($_SERVER["REQUEST_URI"]))."\">(".obt_translate("exit").")</a>.</p>\n<p>\n";
		ELSE {
			echo "<p>\n";
			echo obt_translate("Name")."".((get_option("require_name_email"))? " *" : "")." "."<label for=\"comments-form-author\"><input type=\"text\" name=\"author\" id=\"comments-form-author\" value=\"".wp_specialchars($comment_author,true)."\" size=\"60\" class=\"text\" /> </label><br />\n";
			echo obt_translate("Email")."".((get_option("require_name_email"))? " *" : "")." "."<label for=\"comments-form-email\"><input type=\"text\" name=\"email\" id=\"comments-form-email\" value=\"".wp_specialchars($comment_author_email,true)."\" size=\"62\" class=\"text\" /></label><br />\n";
			echo obt_translate("Url")." "."<label for=\"comments-form-url\"><input type=\"text\" name=\"url\" id=\"comments-form-url\" value=\"".wp_specialchars($comment_author_url,true)."\" size=\"65\" class=\"text\" /> </label><br />\n";
		};
		echo "<textarea name=\"comment\" id=\"comment\" cols=\"58\" rows=\"6\" class=\"textarea\"></textarea><br />\n";
		IF (obt_get_themeoption("comment-notification")){
			$obt_notification_checked = false;
			IF (!$obt_can_moderate_comments){
				IF ($user_ID) $commenter_email = $userdata->user_email;
				ELSEIF (strlen($comment_author_email)) $commenter_email = $comment_author_email;
				IF ($commenter_email){
					$obt_notification_emails = get_post_custom_values("comment-notification",$post->ID);
					$obt_notification_emails = $obt_notification_emails[0];
					$obt_unserialized_notification_emails = unserialize($obt_notification_emails);
					IF ($obt_unserialized_notification_emails) $obt_notification_emails = $obt_unserialized_notification_emails;
					unset($obt_unserialized_notification_emails);
					IF (!is_array($obt_notification_emails)) $obt_notification_emails = array();
					IF (isset($obt_notification_emails[$commenter_email])) $obt_notification_checked = (strlen($obt_notification_emails[$commenter_email]) > 0);
					ELSE $obt_notification_checked = (obt_get_themeoption("comment-notification-default") == 1);
				}ELSE $obt_notification_checked = (obt_get_themeoption("comment-notification-default") == 1);
			};
			echo "<label for=\"notifyme\"><input type=\"checkbox\" name=\"notifyme\" id=\"notifyme\" value=\"1\"".(($obt_notification_checked)? " checked" : "")." class=\"checkbox\" /> ".obt_translate("Email me the comments to this post")."</label>\n";
		};
		echo "</p>\n";
		echo "<p>\n";
		echo "<input name=\"submit\" type=\"submit\" value=\"".obt_translate("Submit")."\" class=\"button\" />\n";
		echo "<input type=\"hidden\" name=\"comment_post_ID\" value=\"{$post->ID}\" />\n";
		$obt_time = time();
		$obt_hash = md5($obt_time."/".$_SERVER["SERVER_SOFTWARE"]);
		echo "<input type=\"hidden\" name=\"obt_time\" value=\"{$obt_time}\" />\n";
		echo "<input type=\"hidden\" name=\"obt_hash\" value=\"{$obt_hash}\" />\n";
		do_action("comment_form",$post->ID);
		echo "</p>\n";
		echo "</form>\n";
		IF (!$user_ID && !strlen($comment_author)){
			echo "<script type=\"text/javascript\">\n";
			echo "<!--\n";
			echo "fillForm(\"".COOKIEHASH."\",\"comments-form-email\",\"comments-form-author\",\"comments-form-url\");\n";
			echo "//-->\n";
			echo "</script>\n";
		};
		include(TEMPLATEPATH."/ads/after-comments-form.php");
		IF (!$user_ID && get_option("require_name_email")) echo "<p><small>* ".obt_translate("Required fields")."</small></p>\n";
		echo "<p><small>".obt_translate("You can use these XHTML tags").": ".trim(allowed_tags()).".</small></p>\n";
	};
}ELSEIF ($obt_total_comments){
	echo "<h3>".obt_translate("Comments disabled")."</h3>\n";
	echo "<p>".obt_translate("Comments have been disabled for this post").".</p>\n";
};
?>