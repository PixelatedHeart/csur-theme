<?php
IF ($obt_comments_per_page > 0 && $obt_total_comments > $obt_comments_per_page){
	$obt_comments_this_real_page = ($obt_comments_order == "newer")? $obt_comments_total_pages - $obt_comments_this_page + 1: $obt_comments_this_page;
	echo "<p class=\"pagination\">\n";
	IF ($obt_comments_this_real_page > 1){
		$obt_comments_current_page = ($obt_comments_order == "newer")? $obt_comments_total_pages - $obt_comments_this_real_page + 2: $obt_comments_this_real_page - 1;
		echo "<a href=\"".(($obt_comments_this_real_page == 2)? $obt_post_url."#".obt_translate("comments") : obt_comments_page_url($obt_post_url,$obt_comments_current_page))."\">&larr; ".obt_translate("Previous")."</a>\n";
	}ELSE echo "<s>&larr; ".obt_translate("Previous")."</s>\n";
	IF ($obt_comments_this_real_page + 2 > $obt_comments_total_pages){
		$obt_comments_page_start = max($obt_comments_total_pages-4,1);
		$obt_comments_page_end = $obt_comments_total_pages;
	}ELSEIF ($obt_comments_this_real_page - 2 < 1){
		$obt_comments_page_start = 1;
		$obt_comments_page_end = min(5,$obt_comments_total_pages);
	}ELSE {
		$obt_comments_page_start = max($obt_comments_this_real_page - 2,1);
		$obt_comments_page_end = min($obt_comments_this_real_page+2,$obt_comments_total_pages);
	};
	IF ($obt_comments_page_start > 1){
		$obt_comments_current_page = ($obt_comments_order == "newer")? $obt_comments_total_pages : 1;
		echo "<a href=\"{$obt_post_url}#".obt_translate("comments")."\">".obt_fill_zeroes($obt_comments_current_page)."</a> ... \n";
	};
	FOR ($x = $obt_comments_page_start; $x <= $obt_comments_page_end; $x++){
		$obt_comments_current_page = ($obt_comments_order == "newer")? $obt_comments_total_pages - $x + 1 : $x;
		IF ($x != $obt_comments_this_real_page) echo "<a href=\"".(($x == 1)? $obt_post_url."#".obt_translate("comments") : obt_comments_page_url($obt_post_url,$obt_comments_current_page))."\">".obt_fill_zeroes($obt_comments_current_page)."</a>\n";
		ELSE echo "<s>".obt_fill_zeroes($obt_comments_current_page)."</s>\n";
	};
	IF ($obt_comments_page_end < $obt_comments_total_pages){
		$obt_comments_current_page = ($obt_comments_order == "newer")? 1 : $obt_comments_total_pages;
		echo "... <a href=\"".obt_comments_page_url($obt_post_url,$obt_comments_current_page)."\">".obt_fill_zeroes($obt_comments_current_page)."</a>\n";
	};
	IF ($obt_comments_this_real_page < $obt_comments_total_pages){
		$obt_comments_current_page = ($obt_comments_order == "newer")? $obt_comments_total_pages - $obt_comments_this_real_page : $obt_comments_this_real_page + 1;
		echo "<a href=\"".obt_comments_page_url($obt_post_url,$obt_comments_current_page)."\">".obt_translate("Next")." &rarr;</a>\n";
	}ELSE echo "<s>".obt_translate("Next")." &rarr;</s>\n";
	echo "</p>\n";
};
?>
