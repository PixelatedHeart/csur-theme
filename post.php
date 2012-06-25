<?php
$obt_send_form = ($_SERVER["REQUEST_METHOD"] == "POST" && (is_single() || is_page()) && isset($_POST["send-name"]));
$obt_contact_form = (is_page() && $post->post_title == obt_translate("Contact"));

include(TEMPLATEPATH."/ads/before-post-title.php");
$obt_post_title = apply_filters("the_title",$post->post_title);
IF (!strlen($obt_post_title)) $obt_post_title = obt_translate("Untitled");
IF (strlen($post->post_password)) $obt_post_title = obt_translate("%1 (protected)",$obt_post_title);
$obt_post_title = wp_specialchars($obt_post_title,true);
$obt_post_url = (is_page())? obt_fix_link(get_permalink()) : get_permalink();
IF (!is_page()){
	include(TEMPLATEPATH."/ads/before-post-date.php");
	$obt_author_link = (function_exists("get_author_posts_url"))? get_author_posts_url($authordata->ID,$authordata->user_nicename) : get_author_link(false,$authordata->ID,$authordata->user_nicename);
	$obt_author_feed = get_author_rss_link(false,$authordata->ID,$authordata->user_nicename);
	$obt_author_link = obt_fix_link($obt_author_link);
	$obt_author_feed = obt_fix_feed_link($obt_author_feed,$obt_author_link);
	echo "<div class=\"post-header\">".obt_translate("By %1","<a href=\"{$obt_author_link}\" title=\"".obt_translate("View all posts written by %1",get_the_author())."\">".get_the_author()."</a>").", ".obt_translate("%1 ago",obt_time_ago($post->post_date_gmt))."</div>\n";
	include(TEMPLATEPATH."/ads/after-post-date.php");
}ELSEIF ($post->post_parent){
	echo "<div class=\"post-header\">\n";
	$obt_post_parent = $post->post_parent;
	WHILE ($obt_post_parent){
		flush();
		$obt_post_parent = get_post($obt_post_parent);
		$obt_post_parent_title = apply_filters("the_title",$obt_post_parent->post_title);
		IF (!strlen($obt_post_parent_title)) $obt_post_parent_title = obt_translate("Untitled");
		IF (strlen($obt_post_parent->post_password)) $obt_post_parent_title = obt_translate("%1 (protected)",$obt_post_parent_title);
		$obt_post_parent_title = wp_specialchars($obt_post_parent_title,true);
		$obt_post_parent_url = (is_page())? obt_fix_link(get_permalink($obt_post_parent->ID)) : get_permalink($obt_post_parent->ID);
		echo "<a href=\"{$obt_post_parent_url}\">{$obt_post_parent_title}</a> &rarr; \n";
		$obt_post_parent = $obt_post_parent->post_parent;
	};
	echo "</div>\n";
};
IF (!is_page() && !is_single() || $obt_comments_paginated || $obt_comment_form || $obt_send_form) echo "<h2><a href=\"{$obt_post_url}\" title=\"".obt_translate("Permanent link to this post")."\">{$obt_post_title}</a></h2>\n";
ELSE echo "<h2>{$obt_post_title}</h2>\n";
include(TEMPLATEPATH."/ads/after-post-title.php");
include(TEMPLATEPATH."/ads/before-post-content.php");
IF ($obt_comments_paginated){
	$wp_query->paragraph_count = 1;
	$wp_query->current_paragraph = 0;
	echo "<div class=\"post-text clear\">\n";
	include(TEMPLATEPATH."/ads/before-post-paragraph.php");
	echo "<p><a href=\"{$obt_post_url}\" title=\"".obt_translate("Permanent link to this post")."\">&larr; ".obt_translate("Read post")."</a></p>\n";
	include(TEMPLATEPATH."/ads/after-post-paragraph.php");
	echo "</div>\n";
}ELSEIF (strlen($post->post_password) && $_COOKIE["wp-postpass_".COOKIEHASH] != $post->post_password){
	$wp_query->paragraph_count = 1;
	$wp_query->current_paragraph = 0;
	echo "<div class=\"post-text clear\">\n";
	include(TEMPLATEPATH."/ads/before-post-paragraph.php");
	echo "<form action=\"".get_option("siteurl")."/wp-pass.php\" method=\"post\">\n";
	echo "<p>".obt_translate("This post is password-protected").":</p>\n";
	echo "<p>\n";
	echo "<label for=\"post_password\"><input type=\"password\" name=\"post_password\" id=\"post_password\" size=\"20\" class=\"text\" style=\"width:120px\" /></label> ";
	echo "<input name=\"submit\" type=\"submit\" value=\"".obt_translate("Submit")."\" class=\"button\" />\n";
	echo "</p>\n";
	echo "</form>\n";
	include(TEMPLATEPATH."/ads/after-post-paragraph.php");
	echo "</div>\n";
}ELSE{
	IF (!(is_single() || is_page()) && ((is_home() && obt_get_themeoption("posts") != "full") || (!is_home() && obt_get_themeoption("posts-archives") != "full"))){
		IF ((is_home() && obt_get_themeoption("posts") == "excerpt") || (!is_home() && obt_get_themeoption("posts-archives") == "excerpt")){
			IF (!strlen($post->post_excerpt)){
				$obt_words = (is_home())? obt_get_themeoption("posts-words")*1 : obt_get_themeoption("posts-archives-words")*1;
				IF (!$obt_words) $obt_words = 55;
				$obt_post_excerpt = get_the_content();
				$obt_post_excerpt = apply_filters("get_the_excerpt",$obt_post_excerpt);
				$obt_post_excerpt = apply_filters("the_excerpt",$obt_post_excerpt);
				$obt_post_excerpt = obt_excerpt($obt_post_excerpt,$obt_words);
				$obt_post_excerpt = "<p>{$obt_post_excerpt}</p>";
			}ELSE{
				$obt_post_excerpt = $post->post_excerpt;
				$obt_post_excerpt = apply_filters("get_the_excerpt",$post->post_excerpt);
				$obt_post_excerpt = apply_filters("the_excerpt",$obt_post_excerpt);
			};
			$obt_post_excerpt = str_replace("<br />","</p>\n<p>",$obt_post_excerpt);
			echo "<div class=\"post-text clear\">\n";
			obt_display_paragraphs($obt_post_excerpt);
			IF (strlen($post->post_excerpt) || substr($obt_post_excerpt,-7) == "...</p>" || $multipage) echo "<p><a href=\"{$obt_post_url}\">".obt_translate("Keep reading")." &rarr;</a></p>\n";
			unset($obt_post_excerpt);
			echo "</div>\n";
		}ELSE{
			$obt_paragraphs = (is_home())? obt_get_themeoption("posts-paragraphs")*1 : obt_get_themeoption("posts-archives-paragraphs")*1;
			IF (!$obt_paragraphs) $obt_paragraphs = 1;
			$obt_post_content = get_the_content(obt_translate("Keep reading")." &rarr;",0,"");
			$obt_post_content = apply_filters("the_content",$obt_post_content);
			$obt_post_content = str_replace(']]>', ']]&gt;', $obt_post_content);
			$obt_post_content = preg_replace("' <a href=\"([^\"]*?)\" class=\"more-link\">(.*?)</a></p>'","</p>\n<p><a href=\"\\1\">\\2</a>",$obt_post_content);
			echo "<div class=\"post-text clear\">\n";
			obt_display_paragraphs($obt_post_content,$obt_paragraphs);
			unset($obt_post_content);
			IF ($wp_query->paragraph_count > $obt_paragraphs || $multipage) echo "<p><a href=\"{$obt_post_url}\">".obt_translate("Keep reading")." &rarr;</a></p>\n";
			echo "</div>\n";
		};
	}ELSEIF (($_SERVER["REQUEST_METHOD"] != "POST" || !$obt_contact_form) && !$obt_send_form){
		IF ($post->post_type == "attachment"){
			$wp_query->paragraph_count = 1;
			$wp_query->current_paragraph = 0;
			echo "<div class=\"post-text clear\">\n";
			include(TEMPLATEPATH."/ads/before-post-paragraph.php");
			echo "<p><a href=\"".get_the_guid()."\" title=\"".obt_translate("Click to enlarge")."\"><img src=\"".get_the_guid()."\" alt=\"".get_the_guid()."\" width=\"".(round($post->iconsize[1]))."\" height=\"".(round($post->iconsize[0]))."\" /></a></p>";
			IF (strlen(trim(get_the_content("",false,"")))) echo "<p>".wp_specialchars(get_the_content(),true)."</p>\n";
			include(TEMPLATEPATH."/ads/after-post-paragraph.php");
			echo "</div>\n";
		}ELSEIF (strlen($post->post_content)){
			$obt_post_content = get_the_content(obt_translate("Keep reading")." &rarr;",0,"");
			$obt_post_content = preg_replace("'<a href=\"([^\"]*?)#more-([0-9]+)\"'","<a href=\"\\1#".obt_translate("more")."\"",$obt_post_content);
			$obt_post_content = preg_replace("'<span id=\"more-([0-9]+)\"></span>'","<a name=\"".obt_translate("more")."\" id=\"more-\\1\"></a>",$obt_post_content);
			$obt_post_content = apply_filters("the_content",$obt_post_content);
			$obt_post_content = str_replace(']]>', ']]&gt;', $obt_post_content);
			$obt_post_content = preg_replace("' <a href=\"([^\"]*?)\" class=\"more-link\">(.*?)</a></p>'","</p>\n<p><a href=\"\\1\">\\2</a>",$obt_post_content);
			echo "<div class=\"post-text clear\">\n";
			obt_display_paragraphs($obt_post_content);
			unset($obt_post_content);
			echo "</div>\n";
			include(TEMPLATEPATH."/post-pagination.php");
		}ELSE{
			$wp_query->paragraph_count = 1;
			$wp_query->current_paragraph = 0;
			echo "<div class=\"post-text clear\">\n";
			include(TEMPLATEPATH."/ads/before-post-paragraph.php");
			echo "<p>...</p>";
			include(TEMPLATEPATH."/ads/after-post-paragraph.php");
			echo "</div>\n";
		};
	};
};
echo "<div class=\"clearer\"></div>\n";
$obt_can_edit_post = $obt_can_edit_comments = $obt_can_delete_comments = $obt_can_moderate_comments = false;
IF ($user_ID){
	IF (function_exists("current_user_can")){
		$obt_can_edit_post = $obt_can_edit_comments = $obt_can_delete_comments = ($post->post_type == "page")? current_user_can("edit_page",$post->ID) : current_user_can("edit_post",$post->ID);
		$obt_can_moderate_comments = current_user_can("moderate_comments");
	}ELSEIF (function_exists("user_can_edit_post_comments")){
		$obt_can_edit_post = user_can_edit_post($user_ID,$post->ID);
		$obt_can_edit_comments = user_can_edit_post_comments($user_ID,$post->ID);
		$obt_can_delete_comments = user_can_delete_post_comments($user_ID,$post->ID);
	};
};
$obt_pending_comments = false;
$obt_total_pending_comments = 0;
IF ($obt_can_moderate_comments){
	flush();
	IF (is_single() || is_page()){
		$obt_pending_comments = $wpdb->get_results("SELECT * FROM {$wpdb->comments} WHERE comment_post_ID = '{$post->ID}' AND comment_approved = '0' ORDER BY comment_date");
		$obt_total_pending_comments = count($obt_pending_comments);
	}ELSE $obt_total_pending_comments = $wpdb->get_var("SELECT COUNT(comment_post_ID) AS total_pending_comments FROM {$wpdb->comments} WHERE comment_post_ID = '{$post->ID}' AND comment_approved = '0'");

};
IF ($obt_can_edit_post || $obt_total_pending_comments){
	echo "<p>";
	IF ($obt_total_pending_comments){
		IF ($obt_total_pending_comments == 1) echo "<a href=\"".((is_single() || is_page())? "" : $obt_post_url)."#".obt_translate("comments")."\" title=\"".obt_translate("Moderate the comment on this post")."\"><strong>".obt_translate("1 comment awaiting moderation")."</strong></a>";
		ELSEIF ($obt_total_pending_comments > 1) echo "<a href=\"".((is_single() || is_page())? "" : $obt_post_url)."#".obt_translate("comments")."\" title=\"".obt_translate("Moderate the comments on this post")."\"><strong>".obt_translate("%1 comments awaiting moderation",$obt_total_pending_comments)."</strong></a>";
	};
	IF ($obt_can_edit_post && $obt_total_pending_comments > 0) echo " | ";
	IF ($obt_can_edit_post){
		IF (obt_wp_is_21()) echo "<a href=\"".get_option("siteurl")."/wp-admin/".(($post->post_type == "page")? "page" : "post").".php?action=edit&amp;post={$post->ID}\" title=\"".obt_translate("Edit this post")."\">".obt_translate("Edit post")."</a>";
		ELSE echo "<a href=\"".get_option("siteurl")."/wp-admin/post.php?action=edit&amp;post={$post->ID}\" title=\"".obt_translate("Edit this post")."\">".obt_translate("Edit post")."</a>";
	};
	echo "</p>\n";
}ELSE edit_post_link(obt_translate("Edit post"),"<p>","</p>\n");
IF ($obt_send_form) include(TEMPLATEPATH."/send.php");
ELSEIF ($obt_contact_form) include(TEMPLATEPATH."/contact-form.php");
include(TEMPLATEPATH."/ads/after-post-content.php");
flush();
?>