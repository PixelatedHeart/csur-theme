<?php
$obt_errors = array();
IF ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["contact-name"])){
	$_POST = array_map("stripslashes",$_POST);
	$_POST = array_map("trim",$_POST);

	$obt_errors = array();
	IF (!strlen($_POST["contact-name"])) $obt_errors["contact-name"] = true;
	IF (!strlen($_POST["contact-email"])) $obt_errors["contact-email"] = true;
	ELSEIF ( !preg_match("/[[:alnum:]_.-]+[@][[:alnum:]_.-]{2,}.[[:alnum:]_.-]{2,}/",$_POST["contact-email"]) ) $obt_errors["contact-email"] = true;
	IF (!strlen($_POST["contact-subject"])) $obt_errors["contact-subject"] = true;
	IF (!strlen($_POST["contact-message"])) $obt_errors["contact-message"] = true;

	IF (!count($obt_errors)){
		$obt_is_spam = false;
		IF (obt_is_spam($_POST["contact-name"])) $obt_is_spam = true;
		ELSEIF (obt_is_spam($_POST["contact-email"])) $obt_is_spam = true;
		ELSEIF (obt_is_spam($_POST["contact-subject"])) $obt_is_spam = true;
		ELSEIF (obt_is_spam($_POST["contact-message"])) $obt_is_spam = true;

		IF (!$obt_is_spam){
			IF (strlen(get_option("wordpress_api_key")) && function_exists("akismet_http_post")){
				global $akismet_api_host, $akismet_api_port;
				$obt_akismet = $_SERVER;
				unset($obt_akismet["HTTP_COOKIE"]);
				$obt_akismet["blog"] = get_option("home");
				$obt_akismet["comment_author"] = $_POST["contact-name"];		
				$obt_akismet["comment_author_email"] = $_POST["contact-email"];			
				$obt_akismet["comment_author_url"] = "";
				$obt_akismet["comment_content"] = $_POST["contact-message"];
				$obt_akismet["comment_type"] = "email";		
				$obt_akismet["permalink"] = "http://".$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"];	
				$obt_akismet["referrer"] = $_SERVER["HTTP_REFERER"];
				$obt_akismet["user_agent"] = $_SERVER["HTTP_USER_AGENT"];
				$obt_akismet["user_ip"] = preg_replace( '/[^0-9., ]/','',$_SERVER["REMOTE_ADDR"]);
				$obt_akismet_querystring = "";
				FOREACH ($obt_akismet as $obt_akismet_key=>$obt_akismet_value) $obt_akismet_querystring .= $obt_akismet_key."=".urlencode(stripslashes($obt_akismet_value))."&";
				$obt_akismet_response = akismet_http_post($obt_akismet_querystring,$akismet_api_host,"/1.1/comment-check",$akismet_api_port);
				$obt_is_spam = ($obt_akismet_response[1] == "true");
			};
		};
	};

	echo "<div class=\"post-text clear\">\n";
	IF ($obt_is_spam) echo "<p>".obt_translate("Error").". ".obt_translate("The email could include spam and hasn't been delivered").".</p>";
	ELSEIF (count($obt_errors)){
		echo "<p>".obt_translate("Error").". ".obt_translate("Check the following data").":</p>\n";
		echo "<ul>\n";
		IF ($obt_errors["contact-name"]) echo "\t<li>".obt_translate("Name")."</li>\n";
		IF ($obt_errors["contact-email"]) echo "\t<li>".obt_translate("Email")."</li>\n";
		IF ($obt_errors["contact-subject"]) echo "\t<li>".obt_translate("Subject")."</li>\n";
		IF ($obt_errors["contact-message"]) echo "\t<li>".obt_translate("Message")."</li>\n";
		echo "</ul>\n";
	}ELSE{
		$obt_subject = "[".get_bloginfo("name")."] {$_POST["contact-subject"]}";
		$obt_message = wordwrap($_POST["contact-message"],70);
		$obt_headers = "From: {$_POST["contact-name"]} <{$_POST["contact-email"]}>\n";
		$obt_headers .= "Message-ID: <".md5(time())."@".preg_replace("'^www\.'","",strtolower($_SERVER["HTTP_HOST"])).">\n";
		$obt_headers .= "X-Mailer: PHP version ".phpversion()."\n";
		$obt_headers .= "MIME-Version: 1.0\n";
		$obt_headers .= "Content-type: text/plain; charset=\"".get_option("blog_charset")."\"\n";
		@ini_set("sendmail_from",$_POST["contact-email"]);
		IF (@mail(get_the_author()." <".get_the_author_email().">",$obt_subject,$obt_message,$obt_headers)) echo "<p>".obt_translate("Thank you").". ".obt_translate("Your email has been sent and you'll get an answer as soon as possible").".</p>";
		ELSE echo "<p>".obt_translate("Error").". ".obt_translate("Email couldn't be sent").".</p>";
		@ini_restore("sendmail_from");
	};
	echo "</div>\n";
};
IF ($_SERVER["REQUEST_METHOD"] == "GET" || count($obt_errors) || $obt_is_spam){
	echo "<form action=\"{$obt_post_url}\" method=\"post\">\n";
	echo "<p>\n";
	echo "<label for=\"contact-name\"><input type=\"text\" name=\"contact-name\" id=\"contact-name\" value=\"".(($_SERVER["REQUEST_METHOD"] == "GET")? wp_specialchars($comment_author,true) : wp_specialchars($_POST["contact-name"],true))."\" size=\"20\" class=\"text\" /> ".obt_translate("Name")." *</label><br />\n";
	echo "<label for=\"contact-email\"><input type=\"text\" name=\"contact-email\" id=\"contact-email\" value=\"".(($_SERVER["REQUEST_METHOD"] == "GET")? wp_specialchars($comment_author_email,true) : wp_specialchars($_POST["contact-email"],true))."\" size=\"20\" class=\"text\" /> ".obt_translate("Email")." *</label><br />\n";
	echo "<label for=\"contact-subject\"><input type=\"text\" name=\"contact-subject\" id=\"contact-subject\" value=\"".(($_SERVER["REQUEST_METHOD"] == "GET")? "" : wp_specialchars($_POST["contact-subject"],true))."\" size=\"20\" class=\"text\" /> ".obt_translate("Subject")." *</label><br />\n";
	echo "<textarea name=\"contact-message\" id=\"contact-message\" cols=\"30\" rows=\"6\" class=\"textarea\">".(($_SERVER["REQUEST_METHOD"] == "GET")? "" : wp_specialchars($_POST["contact-message"],true))."</textarea><br />\n";
	echo "</p>\n";
	echo "<p>\n";
	echo "<input name=\"submit\" type=\"submit\" value=\"".obt_translate("Send email")."\" class=\"button\" />\n";
	$obt_time = time();
	$obt_hash = md5($obt_time."/".$_SERVER["SERVER_SOFTWARE"]);
	echo "<input type=\"hidden\" name=\"obt_time\" value=\"{$obt_time}\" />\n";
	echo "<input type=\"hidden\" name=\"obt_hash\" value=\"{$obt_hash}\" />\n";
	echo "</p>\n";
	echo "</form>\n";
	IF ($_SERVER["REQUEST_METHOD"] == "GET" && !strlen($comment_author)){
		echo "<script type=\"text/javascript\">\n";
		echo "<!--\n";
		echo "fillForm(\"".COOKIEHASH."\",\"contact-email\",\"contact-name\");\n";
		echo "//-->\n";
		echo "</script>\n";
	};
	echo "<p><small>* ".obt_translate("Required fields")."</small></p>\n";
};
?>