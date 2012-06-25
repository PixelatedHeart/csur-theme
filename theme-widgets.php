<?php
FUNCTION obt_widget_admin(){
	global $user_ID, $user_identity, $user_level, $user_login;
	IF (strlen($user_ID)){
		echo "<h3>".obt_translate("Control panel")."</h3>\n";
		echo "<p>".obt_translate("Logged-in as %1","<strong>{$user_identity}</strong>")." <a href=\"".get_option("siteurl")."/wp-login.php?action=logout&amp;redirect_to=".urlencode($_SERVER["REQUEST_URI"])."\">(".obt_translate("exit").")</a>.</p>\n";
		echo "<ul>\n";
		echo "\t<li><a href=\"".get_option("siteurl")."/wp-admin/\">".obt_translate("Administration dashboard")."</a></li>\n";
		IF ($user_level >= 1) echo "\t<li><a href=\"".get_option("siteurl")."/wp-admin/post".((obt_wp_is_21())? "-new" : "").".php\">".obt_translate("Write a post")."</a></li>\n";
		echo "</ul>\n";
	};
};
FUNCTION obt_widget_archives(){
	echo "<h3>".obt_translate("Archives")."</h3>\n";
	echo "<ul>\n";
	flush();
	IF (obt_use_buffer()){
		ob_start();
		wp_get_archives("type=monthly&show_post_count=1");
		$archives = ob_get_contents();
		ob_end_clean();
		IF (strlen($archives)){
			$archives = obt_quote_attributes($archives);
			$archives = str_replace("&nbsp;"," ",$archives);
			$archives = preg_replace("'<a href=\"([^\"]*?)\" title=\"([^\"]*?)\">(.*?)</a> \((.*?)\)'e","'<a href=\"'.obt_fix_link('\\1').'\" title=\"'.obt_translate('View all posts for the month %1',obt_translate_month_year('\\2')).'\">'.obt_translate_month_year('\\2').'</a> (<small>\\4</small>)'",$archives);
			$archives = obt_format_list($archives);
			echo $archives;
		}ELSE echo "\t<li>".obt_translate("No posts")."</li>\n";
	}ELSE{
		wp_get_archives("type=monthly&show_post_count=1");
	};
	echo "</ul>\n";
};
FUNCTION obt_widget_bloggers(){
	global $wpdb;
	flush();
	$display_name = (obt_wp_is_2())? "display_name" : "user_nickname";
	$post_type = (obt_wp_is_21())? "post_type = 'post' AND " : "";
	$now = current_time("mysql",1);
	IF ($bloggers = $wpdb->get_results("SELECT DISTINCT {$wpdb->users}.ID, user_nicename, {$display_name}, COUNT({$wpdb->posts}.ID) as count FROM {$wpdb->users} LEFT JOIN {$wpdb->posts} ON post_author = {$wpdb->users}.ID WHERE {$post_type}post_status = 'publish' AND post_date_gmt < '$now' GROUP BY post_author ORDER BY {$display_name}")){
		echo "<h3>".obt_translate("Bloggers")."</h3>\n";
		echo "<ul>\n";
		FOREACH ($bloggers as $blogger){
			echo "\t<li>";
			$blogger_link = (function_exists("get_author_posts_url"))? get_author_posts_url($blogger->ID,$blogger->user_nicename) : get_author_link(false,$blogger->ID,$blogger->user_nicename);
			$blogger_link = obt_fix_link($blogger_link);
			$blogger_feed = get_author_rss_link(false,$blogger->ID,$blogger->user_nicename);
			$blogger_feed = obt_fix_feed_link($blogger_feed,$blogger_link);
			IF (obt_use_buffer() && obt_get_themeoption("feed-bloggers")) echo "<span class=\"rss\"><a href=\"{$blogger_feed}\" title=\"".obt_translate("Subscribe to posts written by %1",wp_specialchars($blogger->{$display_name},false))."\" rel=\"nofollow\">RSS</a></span>";
			echo "<a href=\"{$blogger_link}\" title=\"".obt_translate("View all posts written by %1",wp_specialchars($blogger->{$display_name},false))."\">".wp_specialchars($blogger->{$display_name},false)."</a>";
			IF (!obt_use_buffer() && obt_get_themeoption("feed-bloggers")) echo " (<a href=\"{$blogger_feed}\" title=\"RSS\" rel=\"nofollow\">RSS</a>)";
			IF (obt_use_buffer()) echo " (<small>{$blogger->count}</small>)";
			ELSE echo " ({$blogger->count})";
			echo "</li>";
		};
		echo "</ul>\n";
	};
};
FUNCTION obt_widget_categories(){
	echo "<h3>".obt_translate("Categories")."</h3>\n";
	echo "<ul>\n";
	flush();
	IF (obt_use_buffer()){
		ob_start();
		IF (function_exists("wp_list_cats")) wp_list_cats("sort_column=name&optioncount=1&feed=RSS&hide_empty=1");
		ELSE wp_list_categories("orderby=name&show_count=0&feed=&hide_empty=1");
		$categories = ob_get_contents();
		ob_end_clean();
		IF (strpos($categories,"<a ") !== false){
			$categories = str_replace(" class='children'","",$categories);
			IF (obt_get_themeoption("feed-categories")) $categories = preg_replace("'<a href=\"([^\"]*?)\" title=\"([^\"]*?)\">(.*?)</a> \(<a href=\"([^\"]*?)\" title=\"([^\"]*?)\">RSS</a>\) \((.*?)\)'e","'<span class=\"rss\"><a href=\"'.obt_fix_feed_link('\\4','\\1').'\" title=\"'.obt_translate('Subscribe to posts under the category %1','&laquo;\\3&raquo;').'\" rel=\"nofollow\">RSS</a></span><a href=\"'.obt_fix_link('\\1').'\" title=\"'.obt_translate('View all posts under the category %1','&laquo;\\3&raquo;').'\">\\3</a> (<small>\\6</small>)'",$categories);
			ELSE $categories = preg_replace("'<a href=\"([^\"]*?)\" title=\"([^\"]*?)\">(.*?)</a> \(<a href=\"([^\"]*?)\" title=\"([^\"]*?)\">RSS</a>\) \((.*?)\)'e","'<a href=\"'.obt_fix_link('\\1').'\" title=\"'.obt_translate('View all posts under the category %1','&laquo;\\3&raquo;').'\">\\3</a> (<small>\\6</small>)'",$categories);
			$categories = obt_format_list($categories);
			echo $categories;
		}ELSE echo "\t<li>".obt_translate("No posts")."</li>\n";
	}ELSE{
		$feed = (obt_get_themeoption("feed-categories"))? "RSS" : "";
		IF (function_exists("wp_list_cats")) wp_list_cats("sort_column=name&optioncount=1&feed={$feed}&hide_empty=1");
		ELSE wp_list_categories("orderby=name&show_count=1&feed={$feed}&hide_empty=1");
	};
	echo "</ul>\n";
};
FUNCTION obt_widget_favorites(){
	global $wpdb;
	flush();
	IF ($links = $wpdb->get_results("SELECT link_url,link_name FROM {$wpdb->links} WHERE link_visible = 'Y' ORDER BY link_name")){
		echo "<h3>".obt_translate("Favorite sites")."</h3>\n";
		echo "<ul>\n";
		FOREACH ($links as $link){
			echo "\t<li><a href=\"{$link->link_url}\">".wp_specialchars($link->link_name,true)."</a></li>\n";
		};
		echo "</ul>\n";
	};
};
FUNCTION obt_widget_latestcomments(){
	global $wpdb, $user_ID, $obt_comments_per_page, $obt_comments_order, $obt_can_moderate_comments;
	echo "<h3>".obt_translate("Latest comments")."</h3>\n";
	echo "<ul>\n";
	$post_type = (obt_wp_is_21())? ", post_type" : "";
	$now = current_time("mysql",1);
	flush();
	IF ($comments = $wpdb->get_results("SELECT comment_ID, comment_post_ID, comment_author, comment_content, post_title, post_status{$post_type} FROM {$wpdb->comments} LEFT JOIN {$wpdb->posts} ON comment_post_ID = ID WHERE comment_approved = '1' AND post_status IN ('publish','static') AND post_date_gmt < '$now' AND comment_type IN ('','comment') AND post_password = '' ORDER BY comment_date_gmt DESC LIMIT 0,9")){
		FOREACH ($comments as $comment){
			$comment_count++;
			$posts[$comment->comment_post_ID][] = $comment;
			IF ($comment_count + count($posts) >= 10) BREAK;
		};
		unset($comments);
		FOREACH ($posts as $comments){
			$comment_page = false;
			IF ($obt_comments_per_page > 0){
				extract(wp_get_current_commenter(),EXTR_SKIP);
				$obt_comment_where = "comment_post_ID = '{$comments[0]->comment_post_ID}' AND ";
				IF ($obt_can_moderate_comments) $obt_comment_where .= "comment_approved = '1'";
				ELSEIF ($user_ID) $obt_comment_where .= "(comment_approved = '1' OR (user_id = '{$user_ID}' AND comment_approved = '0'))";
				ELSEIF (strlen($comment_author)) $obt_comment_where .= "(comment_approved = '1' OR (comment_author = '".addslashes($comment_author)."' AND comment_author_email = '".addslashes($comment_author_email)."' AND comment_approved = '0'))";
				ELSE $obt_comment_where .= "comment_approved = '1'";
				$total_comments = $wpdb->get_var("SELECT COUNT(comment_post_ID) AS total_comments FROM {$wpdb->comments} WHERE {$obt_comment_where} AND comment_type IN ('','comment')");
			};
			$post_title = apply_filters("the_title",$comments[0]->post_title);
			IF (!strlen($post_title)) $post_title = obt_translate("Untitled");
			$post_title = wp_specialchars($post_title,true);
			$post_url = get_permalink($comments[0]->comment_post_ID);
			IF ($comments[0]->post_type == "page" || $comments[0]->post_status == "static") $post_url = obt_fix_link($post_url);
			echo "\t<li><a href=\"{$post_url}\" title=\"".obt_translate("Permanent link to this post")."\">{$post_title}</a>:";
			echo "\n<ul>\n";
			$comment_count = 0;
			FOREACH ($comments as $comment){
				$comment_url = $post_url."#".obt_translate("comment")."-{$comment->comment_ID}";
				IF ($obt_comments_per_page > 0){
					$comment_count++;
					$comment_position = $total_comments - count($comments) + $comment_count;
					$comment_page = obt_comment_page($comment_position,$total_comments);
					IF ($obt_comments_order == "newer" || $comment_page > 1)  $comment_url = obt_comments_page_url($post_url,$comment_page,$comment->comment_ID);
				};
				$comment_author = (strlen($comment->comment_author))? $comment->comment_author : obt_translate("Anonymous");
				echo "\t<li title=\"".wp_specialchars(obt_excerpt($comment->comment_content),true)."\"><div class=\"fixed\"><div class=\"fixed-content\"><a href=\"{$comment_url}\" title=\"".obt_translate("Permanent link to this comment")."\">".wp_specialchars($comment_author,true)."</a>: ".wp_specialchars(obt_excerpt($comment->comment_content),true)."</div></div></li>\n";
			};
			echo "</ul></li>\n";
		};
	}ELSE echo "\t<li>".obt_translate("No comments")."</li>\n";
	echo "</ul>\n";
};
FUNCTION obt_widget_latestentries(){
	global $wpdb;
	echo "<h3>".((is_home() && !is_paged())? obt_translate("Next posts") : obt_translate("Latest posts"))."</h3>\n";
	echo "<ul>\n";
	flush();
	$post_type = (obt_wp_is_21())? "post_type = 'post' AND " : "";
	$now = current_time("mysql",1);
	$offset = (is_home() && !is_paged())? get_option("posts_per_page") : 0;
	IF ($posts = $wpdb->get_results("SELECT ID, post_title FROM {$wpdb->posts} WHERE {$post_type}post_status = 'publish' AND post_date_gmt < '$now' ORDER BY post_date_gmt DESC LIMIT {$offset},10")){
		FOREACH ($posts as $post){
			$post_title = apply_filters("the_title",$post->post_title);
			IF (!strlen($post_title)) $post_title = obt_translate("Untitled");
			$post_title = wp_specialchars($post_title,true);
			echo "\t<li><a href=\"".get_permalink($post->ID)."\" title=\"".obt_translate("Permanent link to this post")."\">{$post_title}</a></li>\n";
		};
	}ELSE echo "\t<li>".((is_home() && !is_paged() && !is_404())? obt_translate("No more posts") : obt_translate("No posts"))."</li>\n";
	echo "</ul>\n";
};
FUNCTION obt_widget_login(){
	global $user_ID, $user_login;
	IF (!strlen($user_ID) && get_option("users_can_register")){
		echo "<h3>".obt_translate("Log-in")."</h3>\n";
		echo "<form action=\"".get_option("siteurl")."/wp-login.php\" method=\"post\">\n";
		echo "<p><label for=\"log\"><input type=\"text\" name=\"log\" id=\"log\" value=\"".wp_specialchars(stripslashes($user_login),1)."\" size=\"22\" class=\"text\" /> ".obt_translate("User")."</label><br />\n";
		echo "<input type=\"password\" name=\"pwd\" id=\"pwd\" size=\"22\" class=\"text\" /> ".obt_translate("Password")."<br />\n";
		echo "<input type=\"submit\" name=\"submit\" value=\"".obt_translate("Submit")."\" class=\"button\" /> &nbsp;\n";
		echo "<label for=\"rememberme\"><input type=\"checkbox\" name=\"rememberme\" id=\"rememberme\" value=\"forever\" checked class=\"checkbox\" /> ".obt_translate("Remember me")."</label>\n";
		echo "<input type=\"hidden\" name=\"redirect_to\" value=\"".$_SERVER["REQUEST_URI"]."\" /></p>\n";
		echo "</form>\n";
		echo "<ul>\n";
		echo "\t<li><a href=\"".get_option("siteurl")."/wp-register.php\">".obt_translate("Sign-up")."</a></li>\n";
		echo "\t<li><a href=\"".get_option("siteurl")."/wp-login.php?action=lostpassword\">".obt_translate("Recover password")."</a></li>\n";
		echo "</ul>\n";
	};
};
FUNCTION obt_widget_pages(){
	echo "<h3>".obt_translate("Pages")."</h3>\n";
	obt_widget_pages_list();
};
FUNCTION obt_widget_pages_list(){
	echo "<ul>\n";
	echo "\t<li><a href=\"".get_option("home")."/\" title=\"".obt_translate("Back to homepage")."\">".obt_translate("Home")."</a></li>\n";
	include(TEMPLATEPATH."/ads/before-menu.php");
	flush();
	$obt_sections = wp_list_pages("sort_column=menu_order&show_date=0&title_li=&echo=0");
	$obt_sections = str_replace(array(" class=\"page_item\"","\t"),"",$obt_sections);
	$obt_sections = preg_replace("'<a href=\"([^\"]*?)\"'e","'<a href=\"'.obt_fix_link('\\1').'\"'",$obt_sections);
	$obt_sections = preg_replace("' class=\"(.*?)\"'","",$obt_sections);
	$obt_sections = obt_format_list($obt_sections);
	echo $obt_sections;
	unset($obt_sections);
	include(TEMPLATEPATH."/ads/after-menu.php");
	echo "</ul>\n";
};
FUNCTION obt_widget_mostcommented(){
	global $wpdb;
	echo "<h3>".obt_translate("Most commented posts")."</h3>\n";
	echo "<ul>\n";
	flush();
	$post_type = (obt_wp_is_21())? ", post_type" : "";
	$now = current_time("mysql",1);
	IF ($posts = $wpdb->get_results("SELECT ID, post_title, post_name, post_status{$post_type}, COUNT(comment_post_ID) AS 'total_comments' FROM {$wpdb->posts} LEFT JOIN {$wpdb->comments} ON ID = comment_post_ID WHERE comment_type IN ('','comment') AND post_status IN ('publish','static') AND post_date_gmt < '$now' AND post_password = '' AND comment_approved = '1' GROUP BY comment_post_ID ORDER BY total_comments DESC LIMIT 0,10")){
		FOREACH ($posts as $post){
			$post_title = apply_filters("the_title",$post->post_title);
			IF (!strlen($post_title)) $post_title = obt_translate("Untitled");
			$post_title = wp_specialchars($post_title,true);
			$post_url = get_permalink($post->ID);
			IF ($post->post_type == "page" || $post->post_status == "static") $post_url = obt_fix_link($post_url);
			echo "\t<li><a href=\"{$post_url}\" title=\"".obt_translate("Permanent link to this post")."\">{$post_title}</a>: ";
			echo "<a href=\"{$post_url}#".obt_translate("comments")."\" title=\"".(($post->total_comments == 1)? obt_translate("Read the comment on this post") : obt_translate("Read the comments on this post"))."\">({$post->total_comments})</a></li>";
		};
	}ELSE echo "\t<li>".obt_translate("No comments")."</li>\n";
	echo "</ul>\n";
};
FUNCTION obt_widget_search(){
	global $s, $obt_search_forms;
	$obt_search_forms++;
	echo "<h3>".obt_translate("Search")."</h3>\n";
	echo "<form method=\"get\" id=\"searchform\" action=\"".get_option("home")."/\">\n";
	echo "<p>\n";
	echo "<input type=\"text\" name=\"s\" value=\"".wp_specialchars($s,true)."\" size=\"25\" class=\"text\"/>\n";
	echo "<input type=\"submit\" value=\"".obt_translate("Search")."\" class=\"button\" />\n";
	echo "</p>\n";
	echo "</form>\n";
};
FUNCTION obt_widget_tags(){
	global $wpdb;
	IF (function_exists("wp_tag_cloud")){
		flush();
		$tags = $wpdb->get_results("SELECT * FROM {$wpdb->terms} LEFT JOIN {$wpdb->term_taxonomy} ON {$wpdb->terms}.term_id = {$wpdb->term_taxonomy}.term_id WHERE taxonomy = 'post_tag' AND count > 0 ORDER BY count DESC LIMIT 0,30");
		usort($tags,"obt_sort_tags");
		IF (count($tags)){
			echo "<h3>".obt_translate("Tags")."</h3>\n";
			echo "<ul>\n";
			FOREACH ($tags as $tag){
				$tag_name = wp_specialchars($tag->name,true);
				$tag_link = get_tag_link($tag->term_id);
				$tag_feed = obt_tag_feed($tag_link);
				$tag_link = obt_fix_link($tag_link);
				$tag_feed = obt_fix_feed_link($tag_feed,$tag_link);
				echo "\t<li>";
				IF (obt_use_buffer() && obt_get_themeoption("feed-tags")) echo "<span class=\"rss\"><a href=\"{$tag_feed}\" title=\"".obt_translate("Subscribe to posts tagged %1","&laquo;{$tag_name}&raquo;")."\" rel=\"nofollow\">RSS</a></span>";
				echo "<a href=\"{$tag_link}\" title=\"".obt_translate("View all posts tagged %1","&laquo;{$tag_name}&raquo;")."\">{$tag_name}</a>";
				IF (!obt_use_buffer() && obt_get_themeoption("feed-tags")) echo " (<a href=\"{$tag_feed}\" title=\"RSS\" rel=\"nofollow\">RSS</a>)";
				IF (obt_use_buffer()) echo " (<small>{$tag->count}</small>)";
				ELSE echo " ({$tag->count})";
				echo "</li>\n";
			};
		echo "</ul>\n";
		};
	}ELSEIF (function_exists("UTW_ShowWeightedTagSet")){
		global $utw, $baseurl, $home, $siteurl, $prettyurls;
		$tags = $utw->GetWeightedTags("weight","desc",30,false);
		IF (count($tags)){
			echo "<h3>".obt_translate("Tags")."</h3>\n";
			echo "<ul>\n";
			FOREACH ($tags as $tag){
				$tag_name = str_replace("_"," ",$tag->tag);
				$tag_name = str_replace("-"," ",$tag_name);
				$tag_name = stripslashes($tag_name);
				$tag_name = wp_specialchars($tag_name);
				$tag_name_url = urlencode(stripslashes(strtolower($tag->tag)));
				IF ($prettyurls == "yes"){
					$tag_link = "{$home}{$baseurl}{$tag_name_url}/";
					$tag_feed = "{$tag_link}feed/";
				}ELSE{
					$tag_link = "$home/index.php?tag={$tag_name_url}";
					$tag_feed = "{$tag_link}&amp;feed=rss";
				};
				echo "\t<li>";
				IF (obt_use_buffer() && obt_get_themeoption("feed-tags")) echo "<span class=\"rss\"><a href=\"{$tag_feed}\" title=\"".obt_translate("Subscribe to posts tagged %1","&laquo;{$tag_name}&raquo;")."\" rel=\"nofollow\">RSS</a></span>";
				echo "<a href=\"{$tag_link}\" title=\"".obt_translate("View all posts tagged %1","&laquo;{$tag_name}&raquo;")."\">{$tag_name}</a>";
				IF (!obt_use_buffer() && obt_get_themeoption("feed-tags")) echo " (<a href=\"{$tag_feed}\" title=\"RSS\" rel=\"nofollow\">RSS</a>)";
				IF (obt_use_buffer()) echo " (<small>{$tag->count}</small>)";
				ELSE echo " ({$tag->count})";
				echo "</li>\n";
			};
			echo "</ul>\n";
		};
	}ELSEIF (function_exists("STP_GetTagcloud")){
		global $STagging;
		flush();
		$tags = $STagging->getAllTags(false,false,true,true,false);
		$tags = array_slice($tags,0,30);
		IF (count($tags)){
			echo "<h3>".obt_translate("Tags")."</h3>\n";
			echo "<ul>\n";
			FOREACH ($tags as $tag){
				$tag_name = wp_specialchars($tag["name"],true);
				$tag_link = $tag["link"];
				$tag_feed = obt_tag_feed($tag_link);
				$tag_link = obt_fix_link($tag_link);
				$tag_feed = obt_fix_feed_link($tag_feed,$tag_link);
				echo "\t<li>";
				IF (obt_use_buffer() && obt_get_themeoption("feed-tags")) echo "<span class=\"rss\"><a href=\"{$tag_feed}\" title=\"".obt_translate("Subscribe to posts tagged %1","&laquo;{$tag_name}&raquo;")."\" rel=\"nofollow\">RSS</a></span>";
				echo "<a href=\"{$tag_link}\" title=\"".obt_translate("View all posts tagged %1","&laquo;{$tag_name}&raquo;")."\">{$tag_name}</a>";
				IF (!obt_use_buffer() && obt_get_themeoption("feed-tags")) echo " (<a href=\"{$tag_feed}\" title=\"RSS\" rel=\"nofollow\">RSS</a>)";
				IF (obt_use_buffer()) echo " (<small>{$tag["count"]}</small>)";
				ELSE echo " ({$tag->count})";
				echo "</li>\n";
			};
			echo "</ul>\n";
		};
	};
};
FUNCTION obt_widget_themeswitcher(){
	IF (function_exists("wp_theme_switcher")){
		echo "<h3>".obt_translate("Theme switcher")."</h3>\n";
		flush();
		IF (obt_use_buffer()){
			ob_start();
			wp_theme_switcher();
			$themes = ob_get_contents();
			ob_end_clean();
			$themes = str_replace(array("\t"," id=\"themeswitcher\""),"",$themes);
			$themes = obt_format_list($themes);
			echo $themes;
		}ELSE{
			wp_theme_switcher();
		};
		echo "\n";
	};
};
FUNCTION obt_widget_where(){
	global $s, $obt_comments_paginated;
	IF (is_home() || is_404()){
		echo "<h3>".obt_translate("About")."...</h3>\n";
		include(TEMPLATEPATH."/about.php");
	}ELSEIF (is_single()){
		IF ($obt_comments_paginated){
			global $obt_post_title, $obt_post_url;
			echo "<h3>".obt_translate("Comments")."</h3>\n";
			echo "<p>".obt_translate("You are currently viewing the comments for the post %1 on the blog %2","<a href=\"{$obt_post_url}\">{$obt_post_title}</a>","<a href=\"".get_option("home")."/\">".wp_specialchars(get_bloginfo("name"),true)."</a>").". ".obt_translate("If you haven't found what you were looking for, try the search form or browse the different categories and sections").".</p>\n";
		}ELSE{
			echo "<h3>".obt_translate("Post")."</h3>\n";
			echo "<p>".obt_translate("You are currently viewing an individual post on the blog %1","<a href=\"".get_option("home")."/\">".wp_specialchars(get_bloginfo("name"),true)."</a>").". ".obt_translate("If you haven't found what you were looking for, try the search form or browse the different categories and sections").".</p>\n";
		};
	}ELSEIF (is_page()){
		IF ($obt_comments_paginated){
			global $obt_post_title, $obt_post_url;
			echo "<h3>".obt_translate("Comments")."</h3>\n";
			echo "<p>".obt_translate("You are currently viewing the comments for the section %1 on the blog %2","<a href=\"{$obt_post_url}\">{$obt_post_title}</a>","<a href=\"".get_option("home")."/\">".wp_specialchars(get_bloginfo("name"),true)."</a>").". ".obt_translate("If you haven't found what you were looking for, try the search form or browse the different categories and sections").".</p>\n";
		}ELSE{
			echo "<h3>".obt_translate("Section")."</h3>\n";
			echo "<p>".obt_translate("You are currently viewing a section on the blog %1","<a href=\"".get_option("home")."/\">".wp_specialchars(get_bloginfo("name"),true)."</a>").". ".obt_translate("If you haven't found what you were looking for, try the search form or browse the different categories and sections").".</p>\n";
		};
	}ELSEIF (is_category()){
		echo "<h3>".wp_specialchars(ucfirst(single_cat_title("",false)),true)."</h3>\n";
		IF (category_description()) echo "<p>".category_description()."</p>\n";
		echo "<p>".obt_translate("You are currently viewing the posts under the category %1 on the blog %2","&laquo;".wp_specialchars(single_cat_title("",false),true)."&raquo;","<a href=\"".get_option("home")."/\">".wp_specialchars(get_bloginfo("name"),true)."</a>").". ".obt_translate("If you haven't found what you were looking for, try the search form or browse the different categories and sections").".</p>\n";
	}ELSEIF (obt_is_tag()){
		echo "<h3>".wp_specialchars(ucfirst(obt_current_tag()),true)."</h3>\n";
		echo "<p>".obt_translate("You are currently viewing the posts tagged %1 on the blog %2","&laquo;".wp_specialchars(obt_current_tag(),true)."&raquo;","<a href=\"".get_option("home")."/\">".wp_specialchars(get_bloginfo("name"),true)."</a>").". ".obt_translate("If you haven't found what you were looking for, try the search form or browse the different categories and sections").".</p>\n";
	}ELSEIF (is_search()){
		echo "<h3>".wp_specialchars(ucfirst($s),true)."</h3>\n";
		echo "<p>".obt_translate("You are currently viewing the posts matching the search %1 on the blog %2","&laquo;".wp_specialchars($s,true)."&raquo;","<a href=\"".get_option("home")."/\">".wp_specialchars(get_bloginfo("name"),true)."</a>").". ".obt_translate("If you haven't found what you were looking for, try the search form or browse the different categories and sections").".</p>\n";
	}ELSEIF (is_year()){
		echo "<h3>".get_the_time("Y")."</h3>\n";
		echo "<p>".obt_translate("You are currently viewing the posts for the year %1 on the blog %2",get_the_time("Y"),"<a href=\"".get_option("home")."/\">".wp_specialchars(get_bloginfo("name"),true)."</a>").". ".obt_translate("If you haven't found what you were looking for, try the search form or browse the different categories and sections").".</p>\n";
	}ELSEIF (is_month()){
		echo "<h3>".obt_translate("%1 %2",obt_translate_months(get_the_time("F")),get_the_time("Y"))."</h3>\n";
		echo "<p>".obt_translate("You are currently viewing the posts for the month %1 on the blog %2",obt_translate("%1 %2",obt_translate_months(get_the_time("F")),get_the_time("Y")),"<a href=\"".get_option("home")."/\">".wp_specialchars(get_bloginfo("name"),true)."</a>").". ".obt_translate("If you haven't found what you were looking for, try the search form or browse the different categories and sections").".</p>\n";
	}ELSEIF (is_day()){
		echo "<h3>".obt_translate("%2 %1 %3",get_the_time("j"),obt_translate_months(get_the_time("F")),get_the_time("Y"))."</h3>\n";
		echo "<p>".obt_translate("You are currently viewing the posts for the day %1 on the blog %2",obt_translate("%2 %1 %3",get_the_time("j"),obt_translate_months(get_the_time("F")),get_the_time("Y")),"<a href=\"".get_option("home")."/\">".wp_specialchars(get_bloginfo("name"),true)."</a>").". ".obt_translate("If you haven't found what you were looking for, try the search form or browse the different categories and sections").".</p>\n";
	}ELSEIF (is_author()){
		echo "<h3>".wp_specialchars(get_the_author_nickname(),true)."</h3>\n";
		echo "<p>".obt_translate("You are currently viewing the posts written by %1 on the blog %2",wp_specialchars(get_the_author_nickname()),"<a href=\"".get_option("home")."/\">".wp_specialchars(get_bloginfo("name"),true)."</a>").". ".obt_translate("If you haven't found what you were looking for, try the search form or browse the different categories and sections").".</p>\n";
	};
};
IF (function_exists("register_sidebar")){
	$sidebar_options["before_title"] = "<h3>";
	$sidebar_options["after_title"] = "</h3>";
	$sidebar_options["before_widget"] = "";
	$sidebar_options["after_widget"] = "";
	//$sidebar_options["name"] = obt_translate("Sidebar - Main");
//	register_sidebar($sidebar_options);
//	$sidebar_options["name"] = obt_translate("Sidebar - Left");
//	register_sidebar($sidebar_options);
//	$sidebar_options["name"] = obt_translate("Sidebar - Right");
//	register_sidebar($sidebar_options);
//	$sidebar_options["name"] = obt_translate("Footer - Left");
//	register_sidebar($sidebar_options);
//	$sidebar_options["name"] = obt_translate("Footer - Center");
//	register_sidebar($sidebar_options);
//	$sidebar_options["name"] = obt_translate("Footer - Right");
		register_sidebar(
		array(
		'name' => 'Sobre-cabecera',
		'before_widget' => '<div style="text-align:center">',
		'after_widget' => '</div>',
		'before_title' => '<h3>',
		'after_title' => '</h3>',
		));

		register_sidebar(
		array(
		'name' => 'Menú',
		'before_widget' => '<ul><li>',
		'after_widget' => '</li></ul>',
		'before_title' => '<h3>',
		'after_title' => '</h3>',
		));

		register_sidebar(
		array(
		'name' => 'Index - Debajo de cada post',
		'before_widget' => '',
		'after_widget' => '',
		'before_title' => '<h3>',
		'after_title' => '</h3>',
		));

		register_sidebar(
		array(
		'name' => 'Post - Debajo de la entrada',
		'before_widget' => '',
		'after_widget' => '',
		'before_title' => '<h3>',
		'after_title' => '</h3>',
		));

		register_sidebar(
		array(
		'name' => 'Sidebar - Parte superior',
		'before_widget' => '',
		'after_widget' => '',
		'before_title' => '<h3>',
		'after_title' => '</h3>',
		));

		register_sidebar(
		array(
		'name' => 'Sidebar - Doble columna - Izq',
		'before_widget' => '',
		'after_widget' => '',
		'before_title' => '<h3>',
		'after_title' => '</h3>',
		));

		register_sidebar(
		array(
		'name' => 'Sidebar - Doble columna - Dcha',
		'before_widget' => '',
		'after_widget' => '',
		'before_title' => '<h3>',
		'after_title' => '</h3>',
		));

		register_sidebar(
		array(
		'name' => 'Sidebar - Parte inferior',
		'before_widget' => '',
		'after_widget' => '',
		'before_title' => '<h3>',
		'after_title' => '</h3>',
		));

		register_sidebar(
		array(
		'name' => 'Footer - Columna izqda',
		'before_widget' => '<div class="footeritem">',
		'after_widget' => '&nbsp;</div><!-- fin footeritem -->',
		'before_title' => '<h3>',
		'after_title' => '</h3>',
		));

		register_sidebar(
		array(
		'name' => 'Footer - Columna central',
		'before_widget' => '<div class="footeritem">',
		'after_widget' => '&nbsp;</div><!-- fin footeritem -->',
		'before_title' => '<h3>',
		'after_title' => '</h3>',
		));

		register_sidebar(
		array(
		'name' => 'Footer - Columna dcha',
		'before_widget' => '<div class="footeritem">',
		'after_widget' => '&nbsp;</div><!-- fin footeritem -->',
		'before_title' => '<h3>',
		'after_title' => '</h3>',
		));

};


IF (function_exists("register_sidebar_widget")){
	FUNCTION obt_unregister_sidebar_widgets(){
		unregister_sidebar_widget("Archives");
		unregister_sidebar_widget("Calendar");
		unregister_sidebar_widget("Categories");
		unregister_sidebar_widget("Meta");
	
		unregister_sidebar_widget("Recent Comments");
		unregister_sidebar_widget("Recent Posts");
		unregister_sidebar_widget("Pages");
		unregister_sidebar_widget("Search");
	};
	//add_action("widgets_init","obt_unregister_sidebar_widgets");
	
register_sidebar_widget( 'Menú', 'widget_menu' );	
register_sidebar_widget( 'Comparte esta entrada', 'widget_comparte' );	

	
	
	//register_sidebar_widget(obt_translate("Archives")." [1 Blog Theme]","obt_widget_archives");
	//register_sidebar_widget(obt_translate("Bloggers")." [1 Blog Theme]","obt_widget_bloggers");
	//register_sidebar_widget(obt_translate("Calendar")." [1 Blog Theme]","obt_widget_calendar");
	//register_sidebar_widget(obt_translate("Categories")." [1 Blog Theme]","obt_widget_categories");
	//register_sidebar_widget(obt_translate("Control panel")." (".obt_translate("if you are registered and logged-in").") [1 Blog Theme]","obt_widget_admin");
	//register_sidebar_widget(obt_translate("Favorite sites")." [1 Blog Theme]","obt_widget_favorites");
	//register_sidebar_widget(obt_translate("Latest comments")." [1 Blog Theme]","obt_widget_latestcomments");
	//register_sidebar_widget(obt_translate("Latest posts")." [1 Blog Theme]","obt_widget_latestentries");
	//register_sidebar_widget(obt_translate("Log-in")." (".obt_translate("if users can register").") [1 Blog Theme]","obt_widget_login");
	//register_sidebar_widget(obt_translate("Most commented posts")." [1 Blog Theme]","obt_widget_mostcommented");
	//register_sidebar_widget(obt_translate("Pages")." [1 Blog Theme]","obt_widget_pages");
	//register_sidebar_widget(obt_translate("Search")." [1 Blog Theme]","obt_widget_search");
	//register_sidebar_widget(obt_translate("Subscribe")." (RSS) [1 Blog Theme]","obt_widget_subscribe");
	//register_sidebar_widget(obt_translate("Tags")." (".obt_translate("if %1 is installed","<a href=\"http://simpletagging.herewithme.fr/\" target=\"_blank\">Simple Tagging</a>").") [1 Blog Theme]","obt_widget_tags");
	//register_sidebar_widget(obt_translate("Theme switcher")." (".obt_translate("if %1 is installed","<a href=\"http://dev.wp-plugins.org/wiki/ThemeSwitcher\" target=\"_blank\">Theme Switcher</a>").") [1 Blog Theme]","obt_widget_themeswitcher");
	//register_sidebar_widget(obt_translate("You are currently viewing")."... [1 Blog Theme]","obt_widget_where");
	FUNCTION obt_admin_head(){
		echo "<style type=\"text/css\">\n";
		echo "#sbadmin #zones {\n";
		echo "	width: 900px ! important;\n";
		echo "	}\n";
		echo "#lastmodule, .dropzone ul, .module {\n";
		echo "	width: 126px ! important;\n";
		echo "	}\n";
		echo "#lastmodule, * .module, .placematt {\n";
		echo "	width: 124px ! important;\n";
		echo "	}\n";
		echo "#lastmodule span, * .handle {\n";
		echo "	font-size: 80% ! important;\n";
		echo "	line-height: 100% ! important;\n";
		echo "	text-align: left ! important;\n";
		echo "	width: 102px ! important;\n";
		echo "	}\n";
		echo ".dropzone {\n";
		echo "	width: 126px ! important;\n";
		echo "	}\n";
		echo ".dropzone h3, .dropzone h4{\n";
		echo "	text-align: left ! important;\n";
		echo "	}\n";
		echo ".dropzone h3{\n";
		echo "	margin-left: 5px ! important;\n";
		echo "	}\n";
		echo ".wrap {\n";
		echo "	word-wrap: break-word ! important;\n";
		echo "	}\n";
		echo "</style>\n";
	};
	add_action("admin_head", "obt_admin_head");
};
?>