<?php
global $obt_use_buffer, $obt_translated_month_years, $obt_wp_is_mu, $obt_wp_is_21, $obt_wp_is_2;

$obt_use_buffer = true;
$obt_translated_month_years = array();

$obt_wp_is_mu = (strpos($wp_version,"mu") !== false)? true : false;
$obt_wp_is_21 = ($obt_wp_is_mu || get_bloginfo("version") >= "2.1");
$obt_wp_is_2 = ($obt_wp_is_mu || get_bloginfo("version") >= "2");

$obt_social_domains["act.fresqui.com"] = "fresqui-act";
$obt_social_domains["ocio.fresqui.com"] = "fresqui-ocio";
$obt_social_domains["tec.fresqui.com"] = "fresqui-tec";
$obt_social_domains["meneame.net"] = "meneame";
$obt_social_domains["www.enchilame.com"] = "enchilame";

FUNCTION obt_breakable_url($url){
	$url = str_replace("/","/&#8203;",$url);
	$url = str_replace("/&#8203;/&#8203;","//&#8203;",$url);
	IF (substr($url,-7) == "&#8203;") $url = substr($url,0,-7);
	RETURN $url;
};
FUNCTION obt_clean_meta($text){
	$text = str_replace(" <","<",$text);
	$text = str_replace("\t<","<",$text);
	$text = str_replace(array(",",", "),array(",  ",", "),$text);
	$text = preg_replace("'<meta name=[\"\']description[\"\']([^>]*?)>'si","",$text);
	$text = preg_replace("'<meta name=[\"\']keywords[\"\']([^>]*?)>'si","",$text);
	$text = preg_replace("'<meta name=[\"\']robots[\"\']([^>]*?)>'si","",$text);
	WHILE (strpos($text,"\n\n") !== false) $text = str_replace("\n\n","\n",$text);
	IF (substr($text,0,1) == "\n") $text = substr($text,1);
	IF (substr($text,-1) != "\n") $text .= "\n";
	IF ($text == "\n") $text = "";
	RETURN $text;
};
FUNCTION obt_comment_page($comment_position,$total_comments){
	global $obt_comments_order, $obt_comments_per_page, $obt_comments_post_full;
	IF ($obt_comments_order == "newer" && $obt_comments_post_full) $comment_page = ceil($total_comments/$obt_comments_per_page) - ceil(($total_comments - $comment_position + 1) / $obt_comments_per_page) + 1;
	ELSE $comment_page = ceil($comment_position/$obt_comments_per_page);
	RETURN $comment_page;
};
FUNCTION obt_comments_page_url($url,$page,$comment = 0){
	IF (get_option("permalink_structure") && substr($url,-1) == "/") $url .= obt_translate("comments")."/{$page}/";
	ELSE $url .= ((strpos($url,"?") === false)? "?" : "&amp;").obt_translate("comments")."={$page}";
	IF ($comment > 0) $url .= "#".obt_translate("comment")."-{$comment}";
	ELSE $url .= "#".obt_translate("comments");
	RETURN $url;
};
FUNCTION obt_display_paragraphs($text,$total_paragraphs = 0){
	global $wp_query, $obt_post_url;
	IF ($total_paragraphs > 0) $text = preg_replace("'<a href=([\"\'])#'","<a href=\\1{$obt_post_url}#",$text);
	$text = str_replace(array("</p>","</ul>","</ol>","</blockquote>"),array("</p>~separator~","</ul>~separator~","</ol>~separator~","</blockquote>~separator~"),$text);
	$text = explode("~separator~",$text);
	FOR ($x = count($text)-1; $x > 0; $x--){
		IF (!strlen(trim(preg_replace("'</[^>]*?>'si"," ",$text[$x])))){
			$text[$x-1] .= $text[$x];
			unset($text[$x]);
		};
	};
	$text = array_values($text);
	$wp_query->paragraph_count = count($text);
	$tags = array();
	FOREACH ($text as $x=>$paragraph){
		$wp_query->current_paragraph = $x;
		IF ($total_paragraphs > 0 && $total_paragraphs < $wp_query->paragraph_count){
			IF (preg_match_all("'<([a-z\:\-]+)[ >]'",$paragraph,$matches)) $tags = array_merge(array_reverse($matches[1]),$tags);
			IF (preg_match_all("'<\/([a-z]+)>'",$paragraph,$matches)){
				FOREACH ($matches[1] as $closing_tag) FOREACH ($tags as $y=>$tag) IF ($closing_tag == $tag){
					unset($tags[$y]);
					BREAK;
				};
			};
		};
		include(TEMPLATEPATH."/ads/before-post-paragraph.php");
		echo $paragraph;
		IF ($x == $total_paragraphs-1){
			FOREACH ($tags as $tag) echo "</{$tag}>";
			echo "\n";
		};
		include(TEMPLATEPATH."/ads/after-post-paragraph.php");
		IF ($x == $total_paragraphs-1) RETURN;
	};
};
FUNCTION obt_excerpt($text,$total_words = 55){
	$text = str_replace(array("<br />","</p>","</ul>","</ol>","</blockquote>","\n")," ",$text);
	$text = strip_tags($text);
	$words = explode(" ",$text,$total_words+1);
	IF (count($words) > $total_words){
		array_pop($words);
		$text = implode(" ",$words);
		IF (substr($text,-3) != "..."){
			WHILE (in_array(substr($text,-1),array(".",",",";",":"))) $text = substr($text,0,-1);
			$text .= "...";
		};
	};
	WHILE (strpos($text,"  ") !== false) $text = str_replace("  "," ",$text);
	$text = trim($text);
	RETURN $text;
};
FUNCTION obt_fill_zeroes($number,$digits = 2){
	IF (strlen($number) < $digits) $number = str_repeat("0",$digits - strlen($number)).$number;
	RETURN $number;
};
FUNCTION obt_first_titles($total_posts){
	global $posts;
	$first_titles = "";
	FOR ($x = 0; $x < min($total_posts,count($posts)); $x++){
		$title = apply_filters("the_title",$posts[$x]->post_title);
		IF (!strlen($title)) $title = obt_translate("Untitled");
		$first_titles .= $title;
		IF (substr($posts[$x]->post_title,0,-1) != ".") $first_titles .= ".";
		IF ($x !=  min($total_posts,count($posts))-1) $first_titles .= " ";
	};
	RETURN $first_titles;
};
FUNCTION obt_fix_code($matches){
	$text = $matches[2];
	$text = trim(str_replace(array("<pre>","</pre>"),"",$text));
	$text = str_replace(array("&quot;","&laquo;","&raquo;"),"\"",$text);
	$text = explode("\n",$text);
	$open_brackets = 0;
	IF (count($text) > 1){
		FOREACH ($text as $x=>$line){
			$text[$x] = str_replace("<br />","",$text[$x]);
			$text[$x] = trim($text[$x]);
			$ending_bracket = (substr($text[$x],0,1) == "}")? 1 : 0;
			IF ($open_brackets-$ending_bracket > 0 && substr($text[$x],0,6) != "&nbsp;") $text[$x] = str_repeat("&nbsp;",3*($open_brackets-$ending_bracket)).$text[$x];
			$open_brackets = $open_brackets + substr_count($text[$x],"{") - substr_count($text[$x],"}");
			$text[$x] = "<li>{$text[$x]}</li>";
		};
		$text = "<pre><ol>\n".implode("",$text)."\n</ol></pre>\n";
	}ELSE $text = "<code>".implode("\n",$text)."</code>";
	RETURN $text;
};
FUNCTION obt_fix_link($url){
	global $wp_rewrite;
	$permalink_structure = get_option("permalink_structure");
	$trailing_slash = (substr($permalink_structure,-1) == "/");
	IF ($permalink_structure){
		IF (!$trailing_slash) IF (substr($url,-1) == "/") $url = substr($url,0,-1);
		IF ($trailing_slash) IF (substr($url,-1) != "/") $url .= "/";
	};
	RETURN $url;
};
FUNCTION obt_format_list($text){
	$text = str_replace("\t","",$text);
	$text = str_replace(array("\t","<ul>","</ul>","</ul>\n"),array("","\n<ul>\n","\n</ul>","</ul>"),$text);
	$text = str_replace(array("\t","<li>","<li>\n","\n</li>","</li>"),array("","\n<li>","<li>","</li>","</li>\n"),$text);
	WHILE (strpos($text,"\n\n") !== false) $text = str_replace("\n\n","\n",$text);
	$text = str_replace("<li>","\t<li>",$text);
	IF (substr($text,0,1) == "\n") $text = substr($text,1);
	RETURN $text;
};
FUNCTION obt_html_decode($text){
	IF (function_exists("html_entity_decode")) $text = @html_entity_decode($text,ENT_QUOTES,get_bloginfo("charset"));
	RETURN $text;
};
FUNCTION obt_is_spam($string){
	$bad_headers = array("Content-Type:","MIME-Version:","Content-Transfer-Encoding:","Return-path:","Subject:","From:","Envelope-to:","To:","bcc:","cc:");
	FOREACH ($bad_headers as $bad_header) IF (strpos(strtolower($string),strtolower($bad_header)) === 0) RETURN true;
	RETURN false;
};
FUNCTION obt_month_name($month){
	RETURN mysql2date("F",date("Y-m-d H:i:s",mktime(0,0,0,$month,1,2007)));
};
FUNCTION obt_post_page_url($url,$page){
	global $post;
	IF ($page == 1) RETURN $url;
	IF (get_option("permalink_structure") && $post->post_status == "publish"){
		IF (substr($url,-1) != "/") $url .= "/";
		$url .= "{$page}/";
	}ELSE $url .= ((strpos($url,"?") === false)? "?" : "&amp;")."page={$page}";
	RETURN $url;
};
FUNCTION obt_quote_attributes($text){
	$text = str_replace(array("='","' ","'>"),array("=\"","\" ","\">"),$text);
	RETURN $text;
};
FUNCTION obt_related_posts($date){
	global $post, $wpdb;
	$post_type = (get_bloginfo("version") >= "2.1")? "post_type = 'post' AND " : "";
	IF (function_exists("get_the_tags")){
		IF ($post_tags = get_the_tags($post->ID)){
			$post_tag_ids = array();
			FOREACH ($post_tags as $post_tag) $post_tag_ids[] = $post_tag->term_taxonomy_id;
			$post_tag_ids = implode(", ",$post_tag_ids);
			$related_posts = $wpdb->get_results("SELECT DISTINCT ID, post_title, post_date_gmt, COUNT(term_taxonomy_id) as count FROM {$wpdb->posts}, {$wpdb->term_relationships} WHERE object_id = ID AND term_taxonomy_id IN ({$post_tag_ids}) AND {$post_type}post_status = 'publish' AND post_date_gmt < '$date' AND ID <> '{$post->ID}' GROUP BY ID ORDER BY count DESC, post_date_gmt DESC LIMIT 0,5");
		};
	}ELSEIF (function_exists("UTW_ShowRelatedPostsForCurrentPost")){
		global $utw, $tablepost2tag, $tabletags;
		IF ($post_tags = $utw->GetTagsForPost($post->ID)){
			$post_tag_names = "'";
			FOREACH ($post_tags as $post_tag) $post_tag_names .= mysql_real_escape_string($post_tag->tag)."', '";
			$post_tag_names = substr($post_tag_names,0,-3);
			$related_posts = $wpdb->get_results("SELECT DISTINCT ID, post_title, post_date_gmt, COUNT(post_id) as count FROM {$wpdb->posts}, {$tablepost2tag}, {$tabletags} WHERE {$tablepost2tag}.post_id = ID AND {$tablepost2tag}.tag_id = {$tabletags}.tag_id AND tag IN ({$post_tag_names}) AND {$post_type}post_status = 'publish' AND post_date_gmt < '$date' AND ID <> '{$post->ID}' GROUP BY ID ORDER BY count DESC, post_date_gmt DESC LIMIT 0,5");
		};
	}ELSEIF (function_exists("STP_GetRelatedPosts")){
		global $STagging;
		IF ($post_tags = $STagging->getPostTags($post->ID)){
			$post_tag_names = "'";
			FOREACH ($post_tags as $post_tag) $post_tag_names .= mysql_real_escape_string($post_tag)."', '";
			$post_tag_names = substr($post_tag_names,0,-3);
			$related_posts = $wpdb->get_results("SELECT DISTINCT ID, post_title, post_date_gmt, COUNT(tag_name) as count FROM {$wpdb->posts}, {$STagging->info['stptable']} WHERE post_id = ID AND tag_name IN ({$post_tag_names}) AND {$post_type}post_status = 'publish' AND post_date_gmt < '$date' AND ID <> '{$post->ID}' GROUP BY ID ORDER BY count DESC, post_date_gmt DESC LIMIT 0,5");
		};
	};
	IF (!is_array($related_posts)) $related_posts = array();
	IF (count($related_posts < 5)){
		IF ($post_categories = get_the_category($post->ID)){
			$post_category_ids = array();
			FOREACH ($post_categories as $post_category) $post_category_ids[] = $post_category->cat_ID.$post_category->term_taxonomy_id;
			$post_category_ids = implode(", ",$post_category_ids);
			$post_ids = array();
			$post_ids[] = $post->ID;
			FOREACH ($related_posts as $related_post) $post_ids[] = $related_post->ID;
			$post_ids = implode(", ",$post_ids);
			IF (function_exists("get_the_tags")) $related_posts_by_category = $wpdb->get_results("SELECT DISTINCT ID, post_title, post_date_gmt, COUNT(term_taxonomy_id) as count FROM {$wpdb->posts}, {$wpdb->term_relationships} WHERE object_id = ID AND term_taxonomy_id IN ({$post_category_ids}) AND {$post_type}post_status = 'publish' AND post_date_gmt < '$date' AND ID NOT IN ({$post_ids}) GROUP BY ID ORDER BY count DESC, post_date_gmt DESC LIMIT 0,".(5-count($related_posts)));
			ELSE $related_posts_by_category = $wpdb->get_results("SELECT DISTINCT ID, post_title, post_date_gmt, COUNT(category_id) as count FROM {$wpdb->posts}, {$wpdb->post2cat} WHERE ID = post_id AND category_id IN ({$post_category_ids}) AND {$post_type}post_status = 'publish' AND post_date_gmt < '$date' AND ID NOT IN ({$post_ids}) GROUP BY ID ORDER BY count DESC, post_date_gmt DESC LIMIT 0,".(5-count($related_posts)));
			IF (!is_array($related_posts_by_category)) $related_posts_by_category = array();
			$related_posts = array_merge($related_posts,$related_posts_by_category);
		};
	};
	RETURN $related_posts;
};
FUNCTION obt_rewrite_comments_url($variable = "REQUEST_URI"){
	IF (!in_array($variable,array("REQUEST_URI","PATH_INFO"))) $variable = "REQUEST_URI";
	$url = $_SERVER[$variable];
	IF (preg_match("'^(.+/)(".obt_translate("comments")."/([0-9]+)?)/?(.*)?$'",$url,$matches) && url_to_postid($url) == 0) {
		$_GET[obt_translate("comments")] = $matches[3];
		IF (!$matches[3]) $_GET["comments"] = 1;
		$_SERVER[$variable] = $matches[1].$matches[4];
	};
	IF ($variable != "PATH_INFO" && isset($_SERVER["PATH_INFO"])) obt_rewrite_comments_url("PATH_INFO");
};
add_action("init","obt_rewrite_comments_url");
FUNCTION obt_sort_categories($category1,$category2){
	IF ($category1->category_nicename == $category2->category_nicename) RETURN 0;
	ELSEIF ($category1->category_nicename < $category2->category_nicename) RETURN -1;
	ELSE RETURN 1;
};
FUNCTION obt_status_301($url){
	@header("HTTP/1.1 301 Moved Permanently");
	@header("Location: $url");
	DIE;
};
FUNCTION obt_status_404(){
	global $wp_query;
	@header("HTTP/1.1 404 Not Found");
	$wp_query->is_404 = true;
	require_once(dirname(__FILE__)."/index.php");
	DIE;
};
FUNCTION obt_texturize($text){
	$tags = "a|b|strong|i|em";
	$match = "'</({$tags})>\"'";
	$replace = "\"</\\1>";
	WHILE (preg_match($match,$text)) $text = preg_replace($match,$replace,$text);
	$match = "'\"<({$tags})(\s[^>]*)?>'";
	$replace = "<\\1\\2>\"";
	WHILE (preg_match($match,$text)) $text = preg_replace($match,$replace,$text);

	$texturize = true;
	$text_array = preg_split("/(<.*>)/Us",$text,-1,PREG_SPLIT_DELIM_CAPTURE);
	$text = "";
	FOREACH ($text_array as $current_text){
		IF (isset($current_text{0}) && $current_text{0} != "<" && $texturize) {
			$current_text = preg_replace("'(\s|\A|\(|\[|{|/|-|\xc2\xbf|\xc2\xa1)\"(?!\s)'", "\\1&laquo;\\2", $current_text);
			$current_text = preg_replace("'\"(\s|\S|\Z)'", "&raquo;\\1", $current_text);
			$current_text = str_replace("\xc2\xab","&laquo;",$current_text);
			$current_text = str_replace("\xc2\xbb","&raquo;",$current_text);
			$current_text = str_replace(array("&#8220;","\xe2\x80\x9c"),"&laquo;",$current_text);
			$current_text = str_replace(array("&#8221;","&#8243;","\xe2\x80\x9d","\xe2\x80\xb3"),"&raquo;",$current_text);
			$current_text = str_replace(array("&#8216;","&#8217;","&#8242;","\xe2\x80\x98","\xe2\x80\x99","\xe2\x80\xb2"),"'",$current_text);
		}ELSEIF (strstr($current_text,"<code") !== false || strstr($current_text,"<pre") !== false || strstr($current_text,"<kbd") !== false || strstr($current_text,"<style") !== false || strstr($current_text,"<script") !== false) $texturize = false;
		ELSE $texturize = true;
		$current_text = preg_replace("'&([^#])(?![a-zA-Z1-4]{1,8};)'","&#038;$1",$current_text);
		$text .= $current_text;
	};
	$match = "'&raquo;</({$tags})>'";
	$replace = "</\\1>&raquo;";
	WHILE (preg_match($match,$text)) $text = preg_replace($match,$replace,$text);
	$match = "'<({$tags})(\s[^>]*)?>&laquo;'";
	$replace = "&laquo;<\\1\\2>";
	WHILE (preg_match($match,$text)) $text = preg_replace($match,$replace,$text);
	$text = preg_replace_callback("'(<pre>)?<code>(.*?)</code>(</pre>)?'si","obt_fix_code",$text);
	RETURN $text;
};
FUNCTION obt_time_ago($date,$exact = true){
	IF (!is_integer($date)){
		$date .= " GMT";
		$date = strtotime($date);
	};
	$now = current_time("timestamp",1);
	$diff = $now - $date;
	IF ($diff < 60) RETURN obt_translate("1 minute");
	ELSEIF ($diff < 3600){
		$minutes = floor($diff/60);
		IF ($minutes == 1) RETURN obt_translate("1 minute");
		ELSE RETURN obt_translate("%1 minutes",$minutes);
	}ELSEIF ($diff < 86400){
		$hours = floor($diff/3600);
		$minutes = floor(($diff-($hours*3600))/60);
		IF ($hours == 1) $diff = obt_translate("1 hour");
		ELSE $diff = obt_translate("%1 hours",$hours);
		IF ($exact){
			IF ($minutes == 1) $diff .= " ".obt_translate("and")." ".obt_translate("1 minute");
			ELSEIF ($minutes > 1) $diff .= " ".obt_translate("and")." ".obt_translate("%1 minutes",$minutes);
		};
		RETURN $diff;
	}ELSE{
		list($date_days,$date_months,$date_years) = explode(" ",date("d n Y",$date));
		list($now_days,$now_months,$now_years) = explode(" ",date("d n Y",$now));
		$last_month_days = date("t",mktime(0,0,0,$now_months,0,$now_years));
		$years = $now_years - $date_years;
		IF ($now_months < $date_months){
			$years--;
			$now_months = $now_months+12;
		};
		$months = $now_months - $date_months;
		IF ($now_days < $date_days){
			$months--;
			$now_days = $now_days + $last_month_days;
		};
		$days = $now_days - $date_days;
		IF ($years){
			IF ($years == 1) $diff = obt_translate("1 year");
			ELSE $diff = obt_translate("%1 years",$years);
			IF ($exact){
				IF ($months == 1) $diff .= " ".obt_translate("and")." ".obt_translate("1 month");
				ELSEIF ($months > 1) $diff .= " ".obt_translate("and")." ".obt_translate("%1 months",$months);
			};
			RETURN $diff;
		}ELSEIF ($months){;
			IF ($months == 1) $diff = obt_translate("1 month");
			ELSE $diff = obt_translate("%1 months",$months);
			IF ($exact){
				IF ($days == 1) $diff .= " ".obt_translate("and")." ".obt_translate("1 day");
				ELSEIF ($days > 1) $diff .= " ".obt_translate("and")." ".obt_translate("%1 days",$days);
			};
			RETURN $diff;
		}ELSEIF ($days > 1) RETURN obt_translate("%1 days",$days);
		ELSE RETURN obt_translate("1 day");
	};
};
FUNCTION obt_time_from_text($text){
	$text= explode(" ",$text);
	RETURN mktime($text[0],$text[1],$text[2],$text[3],$text[4],$text[5]);
};
FUNCTION obt_translate($text,$p1 = "", $p2 = "", $p3 = ""){
	global $obt_translation;
	IF (strlen($obt_translation[$text])) $text = $obt_translation[$text];
	$text = str_replace("%1",$p1,$text);
	$text = str_replace("%2",$p2,$text);
	$text = str_replace("%3",$p3,$text);
	RETURN $text;
};
FUNCTION obt_translate_months($text){
	IF (obt_month_name(1) != obt_translate("January")) $text = preg_replace("'\b".obt_month_name(1)."\b'i",obt_translate("January"),$text);
	IF (obt_month_name(2) != obt_translate("February")) $text = preg_replace("'\b".obt_month_name(2)."\b'i",obt_translate("February"),$text);
	IF (obt_month_name(3) != obt_translate("March")) $text = preg_replace("'\b".obt_month_name(3)."\b'i",obt_translate("March"),$text);
	IF (obt_month_name(4) != obt_translate("April")) $text = preg_replace("'\b".obt_month_name(4)."\b'i",obt_translate("April"),$text);
	IF (obt_month_name(5) != obt_translate("May")) $text = preg_replace("'\b".obt_month_name(5)."\b'i",obt_translate("May"),$text);
	IF (obt_month_name(6) != obt_translate("June")) $text = preg_replace("'\b".obt_month_name(6)."\b'i",obt_translate("June"),$text);
	IF (obt_month_name(7) != obt_translate("July")) $text = preg_replace("'\b".obt_month_name(7)."\b'i",obt_translate("July"),$text);
	IF (obt_month_name(8) != obt_translate("August")) $text = preg_replace("'\b".obt_month_name(8)."\b'i",obt_translate("August"),$text);
	IF (obt_month_name(9) != obt_translate("September")) $text = preg_replace("'\b".obt_month_name(9)."\b'i",obt_translate("September"),$text);
	IF (obt_month_name(10) != obt_translate("October")) $text = preg_replace("'\b".obt_month_name(10)."\b'i",obt_translate("October"),$text);
	IF (obt_month_name(11) != obt_translate("November")) $text = preg_replace("'\b".obt_month_name(11)."\b'i",obt_translate("November"),$text);
	IF (obt_month_name(12) != obt_translate("December")) $text = preg_replace("'\b".obt_month_name(12)."\b'i",obt_translate("December"),$text);
	RETURN $text;
};
FUNCTION obt_translate_month_year($text){
	global $obt_translated_month_years;
	IF ($obt_translated_month_years[$text]) RETURN $obt_translated_month_years[$text];
	preg_match("'\b[0-9]{4}\b'",$text,$matches);
	$year = $matches[0];
	IF (preg_match("'\b".obt_month_name(1)."\b'",$text,$matches)) $month = obt_translate("January");
	ELSEIF (preg_match("'\b".obt_month_name(2)."\b'",$text,$matches)) $month = obt_translate("February");
	ELSEIF (preg_match("'\b".obt_month_name(3)."\b'",$text,$matches)) $month = obt_translate("March");
	ELSEIF (preg_match("'\b".obt_month_name(4)."\b'",$text,$matches)) $month = obt_translate("April");
	ELSEIF (preg_match("'\b".obt_month_name(5)."\b'",$text,$matches)) $month = obt_translate("May");
	ELSEIF (preg_match("'\b".obt_month_name(6)."\b'",$text,$matches)) $month = obt_translate("June");
	ELSEIF (preg_match("'\b".obt_month_name(7)."\b'",$text,$matches)) $month = obt_translate("July");
	ELSEIF (preg_match("'\b".obt_month_name(8)."\b'",$text,$matches)) $month = obt_translate("August");
	ELSEIF (preg_match("'\b".obt_month_name(9)."\b'",$text,$matches)) $month = obt_translate("September");
	ELSEIF (preg_match("'\b".obt_month_name(10)."\b'",$text,$matches)) $month = obt_translate("October");
	ELSEIF (preg_match("'\b".obt_month_name(11)."\b'",$text,$matches)) $month = obt_translate("November");
	ELSEIF (preg_match("'\b".obt_month_name(12)."\b'",$text,$matches)) $month = obt_translate("December");
	ELSE{
		$month = trim(str_replace($year,"",$text));
		$month = explode(" ",$month);
		$month = $month[0];
		$month = obt_translate_months($month);
	};
	$obt_translated_month_years[$text] = obt_translate("%1 %2",$month,$year);
	RETURN $obt_translated_month_years[$text];
};
FUNCTION obt_use_buffer(){
	global $obt_use_buffer;
	RETURN $obt_use_buffer;
};
IF (!function_exists("wp_get_current_commenter")){
	FUNCTION wp_get_current_commenter() {
		$comment_author = "";
		if (isset($_COOKIE["comment_author_".COOKIEHASH])) $comment_author = $_COOKIE["comment_author_".COOKIEHASH];
		$comment_author_email = "";
		if (isset($_COOKIE["comment_author_email_".COOKIEHASH])) $comment_author_email = $_COOKIE["comment_author_email_".COOKIEHASH];
		$comment_author_url = "";
		if (isset($_COOKIE["comment_author_url_".COOKIEHASH])) $comment_author_url = $_COOKIE["comment_author_url_".COOKIEHASH];
		RETURN compact("comment_author","comment_author_email","comment_author_url");
	};
};
FUNCTION obt_wp_is_2(){
	global $obt_wp_is_2;
	RETURN $obt_wp_is_2;
};
FUNCTION obt_wp_is_21(){
	global $obt_wp_is_21;
	RETURN $obt_wp_is_21;
};
FUNCTION obt_wp_is_mu(){
	global $obt_wp_is_mu;
	RETURN $obt_wp_is_mu;
};
?>