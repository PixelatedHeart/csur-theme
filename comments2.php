<?php
IF (!empty($_SERVER["SCRIPT_FILENAME"]) && basename($_SERVER['SCRIPT_FILENAME']) == "comments.php") die ("Error");
IF (function_exists("post_password_required")){
	IF (post_password_required()) {
		echo "		<div class=\"comments\">\n";
		echo "<h3>".obt_translate("This post is password-protected")."</h3>\n";
		echo "		</div>\n";
		RETURN;
	};
};


echo "		<div class=\"comments\">\n";
IF ($obt_comments_order == "newer") include(TEMPLATEPATH."/comments-form.php");
include(TEMPLATEPATH."/ads/before-comments.php");
echo "<p><a name=\"".obt_translate("comments")."\" id=\"comments\"></a></p>\n";
echo "<h3>";
IF ($obt_total_comments == 1) echo obt_translate("1 comment");
ELSEIF ($obt_total_comments > 1) echo obt_translate("%1 comments",$obt_total_comments);
ELSE{
	IF ($post->comment_status == "open") echo obt_translate("No comments");
	ELSE echo obt_translate("Comments disabled");
};
IF ($obt_total_pending_comments){
	echo " - ";
	echo ($obt_total_pending_comments == 1)? obt_translate("1 comment awaiting moderation") : obt_translate("%1 comments awaiting moderation",$obt_total_pending_comments);
};
echo "</h3>\n";
include(TEMPLATEPATH."/ads/before-comments-links.php");
IF (obt_get_themeoption("feed-comments") || (obt_get_themeoption("feed-single") && $post->comment_status == "open")){
	echo "<ul class=\"social-buttons\">\n";
	IF (obt_get_themeoption("feed-comments")) echo "\t<li><a href=\"".obt_fix_link(obt_get_feed_url("comments"))."\" title=\"".obt_translate("Subscribe to all comments on this blog")."\" rel=\"nofollow\"><img src=\"".get_bloginfo("template_directory")."/images/social-rss.gif\" width=\"14\" height=\"14\" alt=\"".obt_translate("All comments on this blog")."\" />".obt_translate("All comments on this blog")." <small>(RSS)</small></a></li>\n";
	IF (obt_get_themeoption("feed-single") && $post->comment_status == "open") echo "\t<li><a href=\"".obt_fix_link(comments_rss())."\" title=\"".obt_translate("Subscribe to comments on this post")."\" rel=\"nofollow\"><img src=\"".get_bloginfo("template_directory")."/images/social-rss.gif\" width=\"14\" height=\"14\" alt=\"".obt_translate("Comments on this post")."\" />".obt_translate("Comments on this post")." <small>(RSS)</small></a></li>\n";
	echo "</ul>\n";
};
include(TEMPLATEPATH."/ads/after-comments-links.php");
IF (strlen($post->post_password) && $_COOKIE["wp-postpass_".COOKIEHASH] != $post->post_password){
	echo "<form action=\"".get_option("siteurl")."/wp-pass.php\" method=\"post\">\n";
	echo "<p>".obt_translate("This post is password-protected").":</p>\n";
	echo "<p>\n";
	echo "<label for=\"contact-name\"><input type=\"text\" name=\"post_password\" id=\"post_password\" size=\"20\" class=\"text\" style=\"width:120px\" /></label> ";
	echo "<input name=\"submit\" type=\"submit\" value=\"".obt_translate("Submit")."\" class=\"button\" />\n";
	echo "</p>\n";
	echo "</form>\n";
}ELSE{
	IF ($obt_total_comments || $obt_total_pending_comments){
		IF ($obt_comments_order != "newer" && $post->comment_status == "open"){
			echo "<ul class=\"social-buttons\">\n";
			echo "\t<li><a href=\"".((is_single() || is_page())? "" : $obt_post_url)."#".obt_translate("respond")."\" title=\"".obt_translate("Write a comment on this post")."\"><img src=\"".get_bloginfo("template_directory")."/images/social-comments.gif\" width=\"14\" height=\"14\" alt=\"".obt_translate("Write a comment on this post")."\" />".obt_translate("Write a comment")."</a></li>";
			echo "</ul>\n";
		};
		$obt_comment_count = 0;
		FOREACH ($obt_comments as $comment){
			IF ($comment->comment_approved || !$obt_can_moderate_comments) $obt_comment_count++;
			$wpdb->current_comment = $obt_comment_count-1;
			include(TEMPLATEPATH."/ads/before-comment.php");
			$obt_comment_prefix = ($comment->comment_author_email == get_the_author_email())? "my-" : "";
			$obt_comment_class = ($comment->comment_approved)? "" : " grayed";
			echo "<p><a name=\"".obt_translate("comment")."-".get_comment_ID()."\" id=\"comment-".get_comment_ID()."\"></a></p>\n";
			echo "			<div class=\"{$obt_comment_prefix}comment\"><div class=\"{$obt_comment_prefix}comment-content{$obt_comment_class} clear\">\n";
			echo "<p>\n";
			IF (obt_get_themeoption("gravatars")){
				if (function_exists("get_avatar")){
					if (get_option("avatar_default") == "mystery") $gravatar_default = "http://www.gravatar.com/avatar/ad516503a11cd5ca435acc9bb6523536?s=26";
					elseif (get_option("avatar_default") == "blank") $gravatar_default = includes_url("images/blank.gif");
					elseif (!empty($comment->comment_author_email) && get_option("avatar_default") == "gravatar_default") $gravatar_default = "";
					elseif (get_option("avatar_default") == "gravatar_default") $gravatar_default = "http://www.gravatar.com/avatar/?s=26";
					else $gravatar_default = get_option("avatar_default");
					$gravatar_rating = get_option("avatar_rating");
				}else{
					$gravatar_default = get_bloginfo("template_directory")."/images/default-gravatar.gif";
					$gravatar_rating = "X";
				};
				echo "<img src=\"http://www.gravatar.com/avatar/".md5($comment->comment_author_email)."?rating={$gravatar_rating}&amp;size=26&amp;default=".urlencode($gravatar_default)."\" width=\"26\" height=\"26\" alt=\"Gravatar\" class=\"gravatar\" />\n";
			};
			include(TEMPLATEPATH."/ads/before-comment-header.php");
			$comment_number = ($obt_comments_order == "newer")?  $obt_total_comments-$obt_comment_count-$obt_comments_start+1 : $obt_comment_count+$obt_comments_start;
			IF ($comment->comment_approved){
				IF ($obt_comments_order != "newer" && $obt_comments_this_page == 1) $obt_comment_url = "{$obt_post_url}#".obt_translate("comment")."-".get_comment_ID();
				ELSE $obt_comment_url = obt_comments_page_url($obt_post_url,$obt_comments_this_page,get_comment_ID());
				echo "<a href=\"{$obt_comment_url}\" title=\"".obt_translate("Permanent link to this comment")."\"><strong>#{$comment_number}</strong></a>. ";
			}ELSE echo "??. ";
			$obt_comment_author = (strlen($comment->comment_author))? $comment->comment_author : obt_translate("Anonymous");
			echo (preg_match("'^http:\/\/(.*)\.(.*)$'",$comment->comment_author_url))? "<a href=\"{$comment->comment_author_url}\" rel=\"nofollow\" title=\"".obt_translate("Visit %1's website",wp_specialchars($obt_comment_author,true))."\"><strong>".wp_specialchars($obt_comment_author,true)."</strong></a>" : "<strong>".wp_specialchars($obt_comment_author,true)."</strong>";
			echo  (obt_get_themeoption("gravatars"))? "<br />\n" : ", ";
			echo obt_translate("%1 ago",obt_time_ago($comment->comment_date_gmt))."\n";
			IF ($obt_can_edit_comments && strlen($comment->comment_author_email)) echo " / <a href=\"mailto:".wp_specialchars($comment->comment_author_email,true)."\" title=\"".obt_translate("Send an email to %1",wp_specialchars($obt_comment_author,true))."\">".wp_specialchars($comment->comment_author_email,true)."</a> / {$comment->comment_author_IP}";
			include(TEMPLATEPATH."/ads/after-comment-header.php");
			echo "</p>\n";
			echo "<div class=\"comment-text\">\n";
			IF (!$comment->comment_approved){
				echo "<p><strong>".obt_translate("Unapproved")."</strong>: ".obt_translate("This comment is awaiting moderation").".</p>\n";
			};
			include(TEMPLATEPATH."/ads/before-comment-content.php");
			$obt_comment_text = get_comment_text();
			$obt_comment_text = apply_filters("comment_text",$obt_comment_text);
			echo $obt_comment_text;
			include(TEMPLATEPATH."/ads/after-comment-content.php");
			IF ($obt_can_edit_comments){
				echo "<p>";
				IF (OBT_COMMENT_MODERATION_LINKS){
					IF (!$comment->comment_approved && $obt_can_moderate_comments){
						$obt_moderation_link = get_option("siteurl")."/wp-admin/comment.php?action=approvecomment&amp;p={$comment->comment_post_ID}&amp;c={$comment->comment_ID}";
						IF (function_exists("wp_nonce_url")) $obt_moderation_link = wp_nonce_url($obt_moderation_link,"approve-comment_{$comment->comment_ID}");
						echo "<a href=\"{$obt_moderation_link}\"><strong>".obt_translate("Approve")."</strong></a> | ";
					};
					echo "<a href=\"".get_option("siteurl")."/wp-admin/comment.php?action=editcomment&amp;c={$comment->comment_ID}\">".obt_translate("Edit")."</a> | ";
					IF ($comment->comment_approved && $obt_can_moderate_comments){
						$obt_moderation_link = get_option("siteurl")."/wp-admin/comment.php?action=unapprovecomment&amp;p={$comment->comment_post_ID}&amp;c={$comment->comment_ID}";
						IF (function_exists("wp_nonce_url")) $obt_moderation_link = wp_nonce_url($obt_moderation_link,"unapprove-comment_{$comment->comment_ID}");
						echo "<a href=\"{$obt_moderation_link}\">".obt_translate("Unapprove")."</a> | ";
					};
					IF ($obt_can_delete_comments){
						$obt_moderation_link = get_option("siteurl")."/wp-admin/comment.php?action=deletecomment&amp;p={$comment->comment_post_ID}&amp;c={$comment->comment_ID}";
						IF (function_exists("wp_nonce_url")) $obt_moderation_link = wp_nonce_url($obt_moderation_link,"delete-comment_{$comment->comment_ID}");
						echo "<a href=\"{$obt_moderation_link}\" onclick=\"return confirm('".obt_translate("Delete this comment?")."')\">".obt_translate("Delete")."</a> | ";
					};
					$obt_moderation_link = get_option("siteurl")."/wp-admin/comment.php?action=deletecomment&amp;dt=spam&amp;p={$comment->comment_post_ID}&amp;c={$comment->comment_ID}";
					IF (function_exists("wp_nonce_url")) $obt_moderation_link = wp_nonce_url($obt_moderation_link,"delete-comment_{$comment->comment_ID}");
					echo "<a href=\"{$obt_moderation_link}\" onclick=\"return confirm('".obt_translate("Mark as spam this comment?")."')\">".obt_translate("Spam")."</a>";
				}ELSE{
					echo "<a href=\"".get_option("siteurl")."/wp-admin/post.php?action=editcomment&amp;comment={$comment->comment_ID}\">".obt_translate("Edit")."</a>";
					IF ($obt_can_delete_comments) echo " | <a href=\"".get_option("siteurl")."/wp-admin/post.php?action=deletecomment&amp;p={$comment->comment_post_ID}&amp;comment={$comment->comment_ID}\" onclick=\"return confirm('".obt_translate("Delete this comment?")."')\">".obt_translate("Delete")."</a>";
				};
				echo "</p>\n";
			}ELSE edit_comment_link(obt_translate("Edit comment"),"<p>","</p>\n");
			echo "</div>\n";
			echo "			</div></div>\n";
			include(TEMPLATEPATH."/ads/after-comment.php");
		};
		include(TEMPLATEPATH."/comments-pagination.php");
		IF ($obt_comments_order == "newer" && $post->comment_status == "open"){
			echo "<ul class=\"social-buttons\">\n";
			echo "\t<li><a href=\"".((is_single() || is_page())? "" : $obt_post_url)."#".obt_translate("respond")."\" title=\"".obt_translate("Write a comment on this post")."\"><img src=\"".get_bloginfo("template_directory")."/images/social-comments.gif\" width=\"14\" height=\"14\" alt=\"".obt_translate("Write a comment on this post")."\" />".obt_translate("Write a comment")."</a></li>";
			echo "</ul>\n";
		};
	}ELSE{
		IF ($post->comment_status == "open") echo "<p>".obt_translate("Be the first to write a comment on this post").".</p>\n";
		ELSE echo "<p>".obt_translate("Comments have been disabled for this post").".</p>\n";
	};
};
echo "<script type=\"text/javascript\">\n";
echo "<!--\n";
echo "thankComment(\"".addslashes(obt_translate("Thank you. Your comment has been submitted, but now it has to be validated."))."\");\n";
echo "//-->\n";
echo "</script>\n";
include(TEMPLATEPATH."/ads/after-comments.php");
IF ($obt_comments_order != "newer") include(TEMPLATEPATH."/comments-form.php");
IF (!$obt_comments_paginated){
	include(TEMPLATEPATH."/ads/before-trackbacks.php");
	echo "<p><a name=\"".obt_translate("trackbacks")."\" id=\"trackbacks\"></a></p>\n";
	echo "<h3>";
	IF ($obt_total_trackbacks == 1) echo obt_translate("1 trackback");
	ELSEIF ($obt_total_trackbacks > 1) echo obt_translate("%1 trackbacks",$obt_total_trackbacks);
	ELSE{
		IF ($post->ping_status == "open") echo obt_translate("No trackbacks");
		ELSE echo obt_translate("Trackbacks disabled");
	};
	echo "</h3>\n";
	include(TEMPLATEPATH."/ads/before-trackbacks-links.php");
	echo "<ul class=\"social-buttons\">\n";
	echo "\t<li><a href=\"http://technorati.com/search/".urlencode(get_option("home")."/")."\"><img src=\"".get_bloginfo("template_directory")."/images/social-technorati.gif\" width=\"14\" height=\"14\" alt=\"".obt_translate("All links to this blog")."\" />".obt_translate("All links to this blog")." (Technorati)</a></li>\n";
	echo "\t<li><a href=\"http://technorati.com/search/".urlencode($obt_post_url)."\"><img src=\"".get_bloginfo("template_directory")."/images/social-technorati.gif\" width=\"14\" height=\"14\" alt=\"".obt_translate("Links to this post")."\" />".obt_translate("Links to this post")." (Technorati)</a></li>\n";
	echo "</ul>\n";
	include(TEMPLATEPATH."/ads/after-trackbacks-links.php");
	IF ($obt_total_trackbacks){
		echo "<ul class=\"social-buttons\">\n";
		FOREACH ($obt_trackbacks as $comment){
			IF (in_array($comment->comment_type,array("pingback","trackback"))){
				echo "\t<li><img src=\"".get_bloginfo("template_directory")."/images/social-trackbacks.gif\" width=\"14\" height=\"14\" alt=\"".wp_specialchars($comment->comment_author,true)."\" /><a href=\"{$comment->comment_author_url}\" rel=\"nofollow\">".wp_specialchars($comment->comment_author,true)."</a>: ".obt_translate("%1 ago",obt_time_ago($comment->comment_date_gmt))."\n";
				IF ($obt_can_edit_comments){
					echo " - ";
					IF (OBT_COMMENT_MODERATION_LINKS){
						IF (!$comment->comment_approved && $obt_can_moderate_comments){
							$obt_moderation_link = get_option("siteurl")."/wp-admin/comment.php?action=approvecomment&amp;p={$comment->comment_post_ID}&amp;c={$comment->comment_ID}";
							IF (function_exists("wp_nonce_url")) $obt_moderation_link = wp_nonce_url($obt_moderation_link,"approve-comment_{$comment->comment_ID}");
							echo "<a href=\"{$obt_moderation_link}\"><strong>".obt_translate("Approve")."</strong></a> | ";
						};
						echo "<a href=\"".get_option("siteurl")."/wp-admin/comment.php?action=editcomment&amp;c={$comment->comment_ID}\">".obt_translate("Edit")."</a> | ";
						IF ($comment->comment_approved && $obt_can_moderate_comments){
							$obt_moderation_link = get_option("siteurl")."/wp-admin/comment.php?action=unapprovecomment&amp;p={$comment->comment_post_ID}&amp;c={$comment->comment_ID}";
							IF (function_exists("wp_nonce_url")) $obt_moderation_link = wp_nonce_url($obt_moderation_link,"unapprove-comment_{$comment->comment_ID}");
							echo "<a href=\"{$obt_moderation_link}\">".obt_translate("Unapprove")."</a> | ";
						};
						IF ($obt_can_delete_comments){
							$obt_moderation_link = get_option("siteurl")."/wp-admin/comment.php?action=deletecomment&amp;p={$comment->comment_post_ID}&amp;c={$comment->comment_ID}";
							IF (function_exists("wp_nonce_url")) $obt_moderation_link = wp_nonce_url($obt_moderation_link,"delete-comment_{$comment->comment_ID}");
							echo "<a href=\"{$obt_moderation_link}\" onclick=\"return confirm('".obt_translate("Delete this trackback?")."')\">".obt_translate("Delete")."</a> | ";
						};
						$obt_moderation_link = get_option("siteurl")."/wp-admin/comment.php?action=deletecomment&amp;dt=spam&amp;p={$comment->comment_post_ID}&amp;c={$comment->comment_ID}";
						IF (function_exists("wp_nonce_url")) $obt_moderation_link = wp_nonce_url($obt_moderation_link,"delete-comment_{$comment->comment_ID}");
						echo "<a href=\"{$obt_moderation_link}\" onclick=\"return confirm('".obt_translate("Mark as spam this trackback?")."')\">".obt_translate("Spam")."</a>";
					}ELSE{
						echo "<a href=\"".get_option("siteurl")."/wp-admin/post.php?action=editcomment&amp;comment={$comment->comment_ID}\">".obt_translate("Edit")."</a>";
						IF ($obt_can_delete_comments) echo " | <a href=\"".get_option("siteurl")."/wp-admin/post.php?action=deletecomment&amp;p={$comment->comment_post_ID}&amp;comment={$comment->comment_ID}\" onclick=\"return confirm('".obt_translate("Delete this trackback?")."')\">".obt_translate("Delete")."</a>";
					};
				}ELSE edit_comment_link(obt_translate("Edit trackback"),"<p>","</p>\n");
				echo "</li>\n";
			};
		};
		echo "</ul>\n";
	};
	IF ($post->ping_status == "open") echo "<p><small>".obt_translate("To notify a mention on this post in your blog, enable automated notification (Options &gt; Discussion in WordPress) or specify this trackback url").": ".obt_breakable_url(obt_fix_link(trackback_url(false)))."</small></p>\n";
	ELSE echo "<p>".obt_translate("Trackbacks have been disabled for this post").".</p>\n";
	include(TEMPLATEPATH."/ads/after-trackbacks.php");
	include(TEMPLATEPATH."/ads/before-comments-sidebar.php");
	include(TEMPLATEPATH."/ads/after-comments-sidebar.php");
};
echo "		</div>\n";
?>
