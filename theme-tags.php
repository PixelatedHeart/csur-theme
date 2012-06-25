<?php
IF (strlen($_POST["tags_input"])){
	$obt_tags_input = explode(",",$_POST["tags_input"]);
	$obt_tags_input_count = count($obt_tags_input);
	FOR ($x = 0; $x < $obt_tags_input_count; $x++){
		$obt_tags_input[$x] = trim($obt_tags_input[$x]);
		IF (!strlen($obt_tags_input[$x])) unset($obt_tags_input[$x]);
	};
	$_POST["tags_input"] = implode(",",$obt_tags_input);
};
FUNCTION obt_add_st_tag($text,$link = false){
	global $STagging;
	$text = strip_tags(stripslashes($text));
	$tag = $STagging->tag_convertUserInput($text);
	IF (strlen(trim($tag))){
		$_REQUEST["tag_list"] .= ", {$tag}";
		IF ($link) $text = "<a href=\"".obt_fix_link($STagging->getTagPermalink($tag))."\">{$text}</a>";
	};
	RETURN addslashes($text);
};
FUNCTION obt_add_tag($text,$remove = false,$link = false){;
	$text = strip_tags(stripslashes($text));
	IF (strlen(trim($text))){
		global $obt_tags_to_add;
		$obt_tags_to_add .= ", {$text}";
		IF ($link){
			global $wp_rewrite;
			$tag = explode(",",$text);
			$tag = $tag[0];
			$tag = sanitize_title($tag);
			$tag_url = $wp_rewrite->get_tag_permastruct();
			IF (!strlen($tag_url)) $tag_url = get_option("home")."/?tag=".$tag;
			ELSE $tag_url = get_option("home").str_replace("%tag%",$tag,$tag_url);
			$text = "<a href=\"".obt_fix_link($tag_url)."\">{$text}</a>";
		};
	};
	RETURN ($remove)? "" : addslashes($text);
};
FUNCTION obt_add_tags($id){
	global $obt_tags_to_add;
	IF (strlen($obt_tags_to_add)) wp_add_post_tags($id,substr($obt_tags_to_add,2));
	unset($obt_tags_to_add);
};
FUNCTION obt_check_tags($text){
	IF (function_exists("the_tags")){
		$text = preg_replace("'\[tag\](.*?)\[/tag\]'e","obt_add_tag('\\1')",$text);
		$text = preg_replace("'\[tags\](.*?)\[/tags\]'e","obt_add_tag('\\1',true)",$text);
		$text = preg_replace("'\[taglink\](.*?)\[/taglink\]'e","obt_add_tag('\\1',false,true)",$text);
	}ELSEIF (function_exists("STP_GetCurrentTagSet")){
		global $STagging;
		$text = preg_replace("'\[tag\](.*?)\[/tag\]'e","obt_add_st_tag('\\1')",$text);
		$text = preg_replace("'\[taglink\](.*?)\[/taglink\]'e","obt_add_st_tag('\\1',true)",$text);
	};
	RETURN $text;
};
add_filter("content_save_pre","obt_check_tags");
add_action("edit_post","obt_add_tags");
add_action("publish_post","obt_add_tags");
FUNCTION obt_current_tag(){
	IF (function_exists("single_tag_title")){
		RETURN single_tag_title("",false);
	}ELSEIF (function_exists("UTW_ShowCurrentTagSet")){
		global $utw;
		$tagset = $utw->GetCurrentTagSet();
		FOREACH ($tagset as $tag) $tags .= "{$tag->tag}, ";
		RETURN substr($tags,0,-2);
	}ELSEIF (function_exists("STP_GetCurrentTagSet")) RETURN STP_GetCurrentTagSet();
};
FUNCTION obt_is_tag(){
	IF (function_exists("is_tag")) RETURN is_tag();
	ELSEIF (function_exists("STP_IsTagView")) RETURN STP_IsTagView();
	RETURN false;
};
FUNCTION obt_sort_tags($tag1,$tag2){
	IF ($tag1->slug == $tag2->slug) RETURN 0;
	ELSEIF ($tag1->slug < $tag2->slug) RETURN -1;
	ELSE RETURN 1;
};
FUNCTION obt_tag_feed($url){
	IF (get_option("permalink_structure")){
		IF (substr($url,-1) != "/") $url .= "/";
		$url .= "feed/";
	}ELSE $url = str_replace("?","?feed=rss2&amp;",$url);
	RETURN $url;
};
FUNCTION obt_tag_in_title($title,$tag){
	$title = str_replace(array("<",">"),"",$title);
	$title = str_replace(array(":",".",",","/","\\")," ",$title);
	$title = sanitize_title($title);
	$tag = str_replace(array("<",">"),"",$tag);
	$tag = str_replace(array(":",".",",","/","\\")," ",$tag);
	$tag = sanitize_title($tag);
	IF (!strlen($tag)) RETURN false;
	IF (strpos($title,$tag) !== false) RETURN true;
	$tag = explode("-",$tag);
	FOREACH ($tag as $word){
		$words++;
		IF (strlen($word) > 3) $long_words++;
		IF (strpos($title,$word) !== false){
			$match_words++;
			IF (strlen($word) > 4) $long_match_words++;
		};
	};
	IF ($long_words) RETURN ($long_match_words >= $long_words/2);
	ELSE RETURN ($match_words >= $words/2);
};
FUNCTION obt_tag_keywords($total_keywords = 10,$exclude = ""){
	global $posts, $obt_tag_keywords;
	IF (!is_array($obt_tag_keywords)){
		$tag_keywords = array();
		IF (count($posts)){
			IF (function_exists("get_the_tags")){
				FOREACH ($posts as $x=>$post) IF ($tags = get_the_tags($post->ID)) FOREACH ($tags as $y=>$tag) $tag_keywords[$tag->name] += 1000000+(obt_tag_in_title($post->post_title,$tag->name))*10000-$x*100-$y;
			}ELSEIF (function_exists("UTW_ShowTagsForCurrentPost")){
				global $utw;
				FOREACH ($posts as $x=>$post) IF ($tags = $utw->GetTagsForPost($post->ID)) FOREACH($tags as $y=>$tag){
					$tag = str_replace("_"," ",$tag->tag);
					$tag = str_replace("-"," ",$tag);
					$tag = stripslashes($tag);
					$tag_keywords[$tag] += 1000000+(obt_tag_in_title($post->post_title,$tag))*10000-$x*100-$y;
				};
			}ELSEIF (function_exists("STP_GetMetaKeywords")){
				global $STagging;
				IF ($tag_posts = $STagging->getPostTags()) FOREACH ($posts as $x=>$post) IF ($tag_posts[$post->ID]) FOREACH($tag_posts[$post->ID] as $y=>$tag) $tag_keywords[$tag] += 1000000+(obt_tag_in_title($post->post_title,$tag))*10000-$x*100-$y;
			};
			arsort($tag_keywords);
			$tag_keywords = array_keys($tag_keywords);
			$category_keywords = array();
			FOREACH ($posts as $x=>$post) IF ($categories = get_the_category($post->ID)) FOREACH ($categories as $y=>$category) $category_keywords[$category->cat_name] += 10000-$x*100-$y;
			arsort($category_keywords);
			$category_keywords = array_keys($category_keywords);
			$tag_keywords = array_merge($tag_keywords,$category_keywords);
		};
		$obt_tag_keywords = $tag_keywords;
	}ELSE $tag_keywords = $obt_tag_keywords;
	$exclude = explode(", ",strtolower($exclude));
	IF (count($exclude)) FOREACH ($tag_keywords as $x=>$tag_keyword) IF (in_array(strtolower($tag_keyword),$exclude)) unset($tag_keywords[$x]);
	$dots = (count($tag_keywords) > $total_keywords)? "..." : "";
	RETURN implode(", ",array_slice($tag_keywords,0,$total_keywords)).$dots;
};
FUNCTION obt_tag_redirection(){
	IF (function_exists("the_tags")){
		$url = "http://".$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"];
		$tag_base = get_option("tag_base");
		IF (!strlen($tag_base)) $tag_base = "/tag";
		IF (preg_match("'$tag_base/([^/$#]*)'",$url,$matches)){
			$permalink = true;
			$tag = $matches[1];
		}ELSEIF (preg_match("'[?&;]tag=([^&#]*)'",$url,$matches)) $tag = $matches[1];
		IF (strlen($tag)){
			$new_tag = explode("+",$tag);
			FOREACH ($new_tag as $x=>$void){
				$new_tag[$x] = urldecode($new_tag[$x]);
				$new_tag[$x] = str_replace(array(" ","%20"),"-",$new_tag[$x]);
				$new_tag[$x] = sanitize_title($new_tag[$x]);
			};
			$new_tag = implode("+",$new_tag);
			IF (preg_replace("'(%..)'e","strtolower('\\1')",$new_tag) != preg_replace("'(%..)'e","strtolower('\\1')",$tag)){
				IF ($permalink) obt_status_301(preg_replace("'{$tag_base}/([^/$#]*)'","{$tag_base}/$new_tag\\2",$url));
				ELSE obt_status_301(preg_replace("'([?&;])tag=([^&#]*)'","\\1tag=$new_tag",$url));
			};
		};
	};
};
add_action("template_redirect","obt_tag_redirection");
?>