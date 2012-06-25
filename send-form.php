<?php
echo "<form action=\"{$obt_post_url}\" method=\"post\">\n";
echo "<p>\n";
echo "<label for=\"send-to-{$post->ID}\"><input type=\"text\" name=\"send-to\" id=\"send-to-{$post->ID}\" value=\"".(($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["send-name"]))? wp_specialchars($_POST["send-to"],true) : ""	)."\" size=\"20\" class=\"text\" /> ".obt_translate("Email")."</label><br />\n";
echo "<label for=\"send-name-{$post->ID}\"><input type=\"text\" name=\"send-name\" id=\"send-name-{$post->ID}\" value=\"".(($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["send-name"]))? wp_specialchars($_POST["send-name"],true) : wp_specialchars($comment_author,true))."\" size=\"20\" class=\"text\" /> ".obt_translate("Your name")."</label><br />\n";
echo "<label for=\"send-email-{$post->ID}\"><input type=\"text\" name=\"send-email\" id=\"send-email-{$post->ID}\" value=\"".(($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["send-name"]))? wp_specialchars($_POST["send-email"],true) : wp_specialchars($comment_author_email,true))."\" size=\"20\" class=\"text\" /> ".obt_translate("Your email")."</label>\n";
echo "</p>\n";
echo "<p>\n";
echo "<input name=\"submit\" type=\"submit\" value=\"".obt_translate("Send")."\" class=\"button\" />\n";
$obt_time = time();
$obt_hash = md5($obt_time."/".$_SERVER["SERVER_SOFTWARE"]);
echo "<input type=\"hidden\" name=\"obt_time\" value=\"{$obt_time}\" />\n";
echo "<input type=\"hidden\" name=\"obt_hash\" value=\"{$obt_hash}\" />\n";
echo "</p>\n";
echo "</form>\n";
?>