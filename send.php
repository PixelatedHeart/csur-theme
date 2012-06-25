<?php
$obt_errors = array();
IF ($_SERVER["REQUEST_METHOD"] == "POST"){
	$_POST = array_map("stripslashes",$_POST);
	$_POST = array_map("trim",$_POST);

	$obt_errors = array();
	IF (!strlen($_POST["send-to"])) $obt_errors["send-to"] = true;
	ELSEIF ( !preg_match("/[[:alnum:]_.-]+[@][[:alnum:]_.-]{2,}.[[:alnum:]_.-]{2,}/",$_POST["send-to"]) ) $obt_errors["send-to"] = true;
	IF (!strlen($_POST["send-name"])) $obt_errors["send-name"] = true;
	IF (!strlen($_POST["send-email"])) $obt_errors["send-email"] = true;
	ELSEIF ( !preg_match("/[[:alnum:]_.-]+[@][[:alnum:]_.-]{2,}.[[:alnum:]_.-]{2,}/",$_POST["send-email"]) ) $obt_errors["send-email"] = true;

	IF (!count($obt_errors)){
		$obt_is_spam = false;
		IF (obt_is_spam($_POST["send-name"])) $obt_is_spam = true;
		ELSEIF (obt_is_spam($_POST["send-email"])) $obt_is_spam = true;
		ELSEIF (obt_is_spam($_POST["send-subject"])) $obt_is_spam = true;
	};

	echo "<div class=\"post-text clear\">\n";
	echo "<p><strong>".obt_translate("Send this post by email")."</strong></p>";
	IF ($obt_is_spam) echo "<p>".obt_translate("Error").". ".obt_translate("The email could include spam and hasn't been delivered").".</p>";
	ELSEIF (count($obt_errors)){
		echo "<p>".obt_translate("Error").". ".obt_translate("Check the following data").":</p>\n";
		echo "<ul>\n";
		IF ($obt_errors["send-to"]) echo "\t<li>".obt_translate("Email")."</li>\n";
		IF ($obt_errors["send-name"]) echo "\t<li>".obt_translate("Your name")."</li>\n";
		IF ($obt_errors["send-email"]) echo "\t<li>".obt_translate("Your email")."</li>\n";
		echo "</ul>\n";
	}ELSE{
		$obt_subject = obt_html_decode(obt_translate("Post submission on %1",get_bloginfo("name")));
		$obt_message = obt_html_decode(obt_translate("%1 thinks this post will be of interest to you",$_POST["send-name"])).":\n\n";
		$obt_message .= "{$obt_post_title}\n{$obt_post_url}\n\n";
		$obt_message .= "--\n".get_bloginfo("name")."\n".get_option("home");
		$obt_message = wordwrap($obt_message,70);
		$obt_headers = "From: {$_POST["send-name"]} <{$_POST["send-email"]}>\n";
		$obt_headers .= "Message-ID: <".md5(time())."@".preg_replace("'^www\.'","",strtolower($_SERVER["HTTP_HOST"])).">\n";
		$obt_headers .= "X-Mailer: PHP version ".phpversion()."\n";
		$obt_headers .= "MIME-Version: 1.0\n";
		$obt_headers .= "Content-type: text/plain; charset=\"".get_option("blog_charset")."\"\n";
		@ini_set("sendmail_from",$_POST["send-email"]);
		IF (@mail($_POST["send-to"],$obt_subject,$obt_message,$obt_headers)) echo "<p>".obt_translate("Thank you").". ".obt_translate("The post has been sent to the specified email address").".</p>";
		ELSE echo "<p>".obt_translate("Error").". ".obt_translate("Email couldn't be sent").".</p>";
		@ini_restore("sendmail_from");
	};
	echo "</div>\n";
};
IF ($_SERVER["REQUEST_METHOD"] == "GET" || count($obt_errors)) include(TEMPLATEPATH."/send-form.php");
?>