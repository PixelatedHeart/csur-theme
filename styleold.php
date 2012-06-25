<?php
@header("Content-type: text/css");
@header("Cache-control: max-age=".(12*60*60).", must-revalidate");
@header("Expires: ".gmdate("D, d M Y H:i:s",time()+12*60*60)." GMT");

FOREACH ($_GET as $key=>$value){
	unset($_GET[$key]);
	$_GET[str_replace("amp;","",$key)] = $value;
};

IF ($_GET["gzip"] && extension_loaded("zlib")) ob_start("ob_gzhandler");

$align = ($_GET["align"] == "left")? "left" : "center";
$width = strtolower($_GET["width"]);
IF (substr($width,-1) == "%"){
	$width = substr($width,0,-1)*1;
	IF ($width < 50) $width = 50;
	IF ($width > 100) $width = 100;
	$width .= "%";
}ELSEIF (substr($width,-2) == "px"){
	$width = substr($width,0,-2)*1;
	IF ($width < 400) $width = 400;
	$width .= "px";
}ELSE $width = "90%";
$sidebaralign = ($_GET["sidebaralign"] == "right")? "right" : "left";
$sidebarwidth = strtolower($_GET["sidebarwidth"]);
IF (substr($sidebarwidth,-1) == "%"){
	$sidebarwidth = substr($sidebarwidth,0,-1)*1;
	IF ($sidebarwidth < 10) $sidebarwidth = 10;
	IF ($sidebarwidth > 90) $sidebarwidth = 90;
	$sidebarwidth .= "%";
}ELSEIF (substr($sidebarwidth,-2) == "px"){
	$sidebarwidth = substr($sidebarwidth,0,-2)*1+20;
	IF ($sidebarwidth < 100) $sidebarwidth = 100;
	$sidebarwidth .= "px";
}ELSE $sidebarwidth = "40%";
$leftsidebarwidth = strtolower($_GET["leftsidebarwidth"]);
IF (substr($leftsidebarwidth,-1) == "%"){
	$leftsidebarwidth = substr($leftsidebarwidth,0,-1)*1;
	IF ($leftsidebarwidth < 10) $leftsidebarwidth = 10;
	IF ($leftsidebarwidth > 90) $leftsidebarwidth = 90;
	$leftsidebarwidth .= "%";
}ELSEIF (substr($leftsidebarwidth,-2) == "px"){
	$leftsidebarwidth = substr($leftsidebarwidth,0,-2)*1+20;
	IF ($leftsidebarwidth < 40) $leftsidebarwidth = 40;
	$leftsidebarwidth .= "px";
}ELSE $leftsidebarwidth = "50%";

$linkhue = $_GET["linkhue"]*1;
$linksaturation = $_GET["linksaturation"]*1;
$headerhue = $_GET["headerhue"]*1;
$headersaturation = $_GET["headersaturation"]*1;
IF (1 > $linkhue || $linkhue > 255) $linkhue = 150;
IF (1 > $linksaturation || $linksaturation > 255) $linksaturation = 200;
IF (1 > $headerhue || $headerhue > 255) $headerhue = 150;
IF (1 > $headersaturation || $headersaturation > 255) $headersaturation = 70;

$headerlight = $_GET["headerlight"];
IF (!in_array($headerlight,array("light","dark"))) $headerlight = "light";
$background = $_GET["background"];
IF (!in_array($background,array("white","lightgray"))) $background = "white";
$menu = $_GET["menu"];
IF (!in_array($menu,array("horizontal","regular"))) $menu = "horizontal";
$imagenomargins = $_GET["imagenomargins"];

require_once(dirname(__FILE__)."/style-colors.php");
require_once(dirname(__FILE__)."/ads/before-css.php");
?>
body {
	border: 0px;
<?php IF ($background == "white"){ ?>
	background: #ffffff;
<?php }ELSE{ ?>
	background: #fafafa;
<?php }; ?>
	color: #333333;
	font: 13px/15px 'Lucida Sans Unicode', 'Lucida Grande', Tahoma, Verdana, sans-serif;
	letter-spacing: -1px;
	margin: 0px;
	text-align: <?php echo $align ?>;
	}
a {
	color: #<?php obt_hsl2rgb($linkhue,$linksaturation,80) ?>;
	}
	a:hover, a:active, a:focus {
		background: #<?php obt_hsl2rgb($linkhue,round($linksaturation/2),240) ?>;
		}
	a:hover img, a:active img, a:focus img {
		background: #<?php obt_hsl2rgb($linkhue,round($linksaturation/2),240) ?>;
		}
<?php IF ($headerlight == "dark"){ ?>
	.header a, .footer a {
		color: #ffffff;
		}
	.header a:hover, .header a:active, .header a:focus, .footer a:hover, .footer a:active, .footer a:focus {
		background: #<?php obt_hsl2rgb($headerhue,$headersaturation,100) ?>;
		}
<?php }; ?>
blockquote {
	border-left: 1px solid #dcdcdc;
	margin: 15px 0px 0px 1px;
	padding-left: 20px;
	}
	.my-comment blockquote {
		border-color: #<?php obt_hsl2rgb($headerhue,$headersaturation/2,220) ?>;
		}
code, pre {
	font: 15px monospace;
	overflow: hidden;
	white-space: normal;
	}
	pre ol li {
		margin-top: 0px;
		}
form {
	margin: 15px 0px 0px;
	width: 100%;
	}
	* +html form {
		margin-top: 5px;
		}
	* html form {
		margin-top: 5px;
		}
	form p {
		margin-top: 10px;
		}
h1, h2, h3, h4, h5, h6 {
	margin: 0px;
	}
	h1, h2 {
		color: #000000;
		font: bold 29px/25px 'Lucida Sans Unicode', 'Lucida Grande', Tahoma, Verdana, sans-serif;
		}
	h1 {
		letter-spacing: -1px;
		padding: 5px 0px 4px 0px;
		text-transform: uppercase;
		}
		h1 a img {
			border: 0px;
<?php IF ($imagenomargins){ ?>
<?php 	IF ($headerlight == "light"){ ?>
		margin: -25px 0px -19px 2px;
<?php 	}ELSE{ ?>
		margin: -22px 0px -22px 2px;
<?php 	}; ?>
<?php }ELSE{ ?>
			margin: -5px 0px -1px 2px;
<?php }; ?>
			}
	h2 {
		letter-spacing: -2px;
		margin: 20px 0px 0px -2px;
		padding-bottom: 5px;
		width: 90%;
		}
		.sidebar h2, .footer h2 {
			border: 0px;
			font: normal 12px Arial, Helvetica, sans-serif;
			letter-spacing: 0px;
			margin: 0px;
			}
	h3 {
		color: #000000;
<?php IF ($background == "white"){ ?>
			border-bottom: 1px solid #dcdcdc;
<?php }ELSE{ ?>
			border-bottom: 1px solid #d7d7d7;
<?php }; ?>
		font: bold 14px/14px 'Lucida Sans Unicode', 'Lucida Grande', Tahoma, Verdana, sans-serif;
		letter-spacing: -1px;
		margin-top: 20px;
		padding-bottom: 1px;
		}
<?php IF ($background != "white"){ ?>
		.post h3, .post-info h3 {
			border-color: #dcdcdc;
			}
<?php }; ?>
		.footer h3 {
<?php IF ($headerlight == "light"){ ?>
			border-color: #<?php obt_hsl2rgb($headerhue,$headersaturation,210) ?>;
<?php }ELSE{ ?>
			border-color: #<?php obt_hsl2rgb($headerhue,$headersaturation/2,130) ?>;
			color: #ffffff;
<?php }; ?>
			}
	h1 a, h2 a {
		text-decoration: none;
		}
		h2 a:hover, h2 a:focus {
			text-decoration: underline;
			}
		.post-text h1 a, .post-text h2 a {
			text-decoration: underline;
			}
	.header h1 a:hover, .header h1 a:active, .header h1 a:focus, .header h1 a:hover img, .header h1 a:active img, .header h1 a:focus img {
		background: transparent;
		}
	h2 a:hover, h2 a:active, h2 a:focus {
		background: transparent;
		}
	.post-text h1, .post-text h2, .post-text h3, .post-text h4, .post-text h5, .post-text h6 {
		border: 0px;
		color: #000000;
		font: bold 20px/20px 'Lucida Sans Unicode', 'Lucida Grande', Tahoma, Verdana, sans-serif;
		letter-spacing: -1px;
		margin: 20px 0px 0px;
		padding: 0px;
		text-transform: none;
		width: 90%;
		}
img {
	border: 0px;
	}
	.post-text img, .comment img, .my-comment img {
		background: #ffffff;
		border: 1px solid #dcdcdc;
		padding: 3px;
		}
	.post-text a img, .comment a img, .my-comment a img {
		border-color: #<?php obt_hsl2rgb($linkhue,$linksaturation/3,200) ?>;
		border-bottom-color: #<?php obt_hsl2rgb($linkhue,$linksaturation,80) ?>;
		}
	.social-buttons img {
		border: 0px;
		float: left;
		margin: 1px 5px 0px 0px;
		}
	img.gravatar {
		float: left;
		margin: 0px 15px 10px 0px;
		}
	img.left {
		clear: left;
		margin: 0px 15px 15px 0px;
		}
	img.right {
		clear: right;
		margin: 0px 0px 15px 15px;
		}
	img.wp-smiley {
		background: transparent;
		border: 0px;
		padding: 0px;
		}
input, textarea {
	font: 13px/15px 'Lucida Sans Unicode', 'Lucida Grande', Tahoma, Verdana, sans-serif;
	letter-spacing: -1px;
	margin-bottom: 1px;
	}
	* +html input, * html +textarea {
		margin-bottom: 0px;
		}
	* html input, * html textarea {
		margin-bottom: 0px;
		}
	input.text, textarea.textarea {
		background: #ffffff;
		border-color: #a0a0a0 #dcdcdc #dcdcdc #a0a0a0;
		border-style: solid;
		border-width: 2px 1px 1px 2px;
		padding: 1px 10px;
		}
	input.text:hover, input.text:focus, textarea.textarea:hover, textarea.textarea:focus {
		background: #<?php obt_hsl2rgb($linkhue,round($linksaturation/2),250) ?>;
		border-color: #<?php obt_hsl2rgb($linkhue,round($linksaturation/3),160) ?> #<?php obt_hsl2rgb($linkhue,round($linksaturation/3),220) ?> #<?php obt_hsl2rgb($linkhue,round($linksaturation/3),220) ?> #<?php obt_hsl2rgb($linkhue,round($linksaturation/3),160) ?>;
		}
	label input.text {
		margin-right: 3px;
		}
	input.button {
		background: #dcdcdc;
		border-color: #c8c8c8 #a0a0a0 #a0a0a0 #c8c8c8;
		border-style: solid;
		border-width: 1px 2px 2px 1px;
		cursor: pointer;
		padding: 0px 10px;
		overflow: visible;
		width: auto;
		}
		* +html input.button {
			height: 21px;
			line-height: 16px;
			}
		* html input.button {
			height: 17px;
			line-height: 17px;
			}
		input.button:hover, input.button:focus {
			background: #<?php obt_hsl2rgb($linkhue,round($linksaturation/2),220) ?>;
			border-color: #<?php obt_hsl2rgb($linkhue,round($linksaturation/3),200) ?> #<?php obt_hsl2rgb($linkhue,round($linksaturation/3),160) ?> #<?php obt_hsl2rgb($linkhue,round($linksaturation/3),160) ?> #<?php obt_hsl2rgb($linkhue,round($linksaturation/3),200) ?>;
			}
	textarea.textarea {
		padding: 5px 10px;
		}
	.footer input.text, .sidebar input.text {
		width: 60%;
		}
		.sidebar-left input.text, .sidebar-right input.text {
			width: 80%;
			}
	.footer input.button, .sidebar input.button {
		width: auto;
		}
	.footer textarea.textarea, .sidebar textarea.textarea {
		width: 80%;
		}
	.post-content input.text, #comments-post input.text {
		width: 50%;
		}
	.post-content textarea.textarea, #comments-post textarea.textarea {
		width: 70%;
		}
ol {
	border: 0px;
	clear: left;
	margin: 0px 0px 0px 35px;
	padding: 0px;
	}
	ol li {
		border: 0px;
		list-style: decimal outside;
		margin-top: 15px;
		}
p {
	margin: 15px 0px 0px;
	}
	.post-text li p {
		overflow: visible;
		width: auto;
		}
ul {
<?php IF ($background == "white"){ ?>
	border-bottom: 1px solid #ebebeb;
<?php }ELSE{ ?>
	border-bottom: 1px solid #e6e6e6;
<?php }; ?>
	clear: left;
	list-style: none outside;
	margin: 15px 0px 0px;
	padding: 0px;
	}
	li ul {
		border-bottom: none;
		margin: 0px;
		}
	li {
<?php IF ($background == "white"){ ?>
		border-top: 1px solid #ebebeb;
<?php }ELSE{ ?>
		border-top: 1px solid #e6e6e6;
<?php }; ?>
		list-style-type: none;
		}
	li li {
		padding-left: 10px;
		}
	.footer ul, .footer li {
<?php IF ($headerlight == "light"){ ?>
			border-color: #<?php obt_hsl2rgb($headerhue,$headersaturation,225) ?>;
<?php }ELSE{ ?>
			border-color: #<?php obt_hsl2rgb($headerhue,$headersaturation/2,110) ?>;
<?php }; ?>
		}
<?php IF ($menu == "horizontal"){ ?>
	.header-menu ul {
		border: 0px;
		display: inline;
		float: right;
		list-style: none outside;
		margin: 0px;
		}
	.header-menu li {
		border: 0px;
<?php IF ($headerlight == "light"){ ?>
		border-left: 1px solid #<?php obt_hsl2rgb($headerhue,$headersaturation,225) ?>;
<?php }ELSE{ ?>
		border-left: 1px solid #<?php obt_hsl2rgb($headerhue,$headersaturation/2,110) ?>;
<?php }; ?>
		font-weight: bold;
		float: left;
		letter-spacing: -1px;
		margin-left: 20px;
		padding: 10px 1px 5px 15px;
		}
	.header-menu li ul {
		border: 0px;
		display: block;
		float: none;
		margin: 0px;
		padding: 0px;
		}
	.header-menu li li {
		border: 0px;
		display: block;
		float: none;
		font-weight: normal;
		margin: 0px;
		padding: 0px;
		}
	.header-menu li li ul {
		margin: 0px;
		}
	.header-menu li li li {
		padding-left: 10px;
		}
<?php }; ?>
<?php IF ($background != "white"){ ?>
	.post ul, .post-info ul, post li, .post-info li {
		border-color: #ebebeb;
		}
<?php }; ?>
	.post-text ul, .comment-text ul {
		border: 0px;
		margin: 0px 0px 0px 27px;
		padding: 0px;
		}
	.post-text ul li, .comment-text ul li {
		border: 0px;
		list-style: disc outside;
		margin-top: 15px;
		}
	ul.social-buttons li {
		clear: both;
		}
small, .rss {
	font-size: 90%;
	}
.body {
	margin: <?php echo ($align == "center")? "0px auto" : "0px" ?>;
	width: <?php echo $width ?>;
	}
	.body-content {
		padding: 0px 30px 28px;
		text-align: left;
		}
		* +html .body-content {
			margin-bottom: 28px;
			}
.clear:after {
	content: "."; 
	display: block; 
	height: 0; 
	clear: both; 
	visibility: hidden;
	}
	/* \*/ * html .clear {
		height: 1%;
		} /* */
.clearer {
	clear: both;
	position: absolute;
	}
.comments {
	padding-top: 10px;
	}
.fixed {
	height: 1.25em;
	overflow: hidden;
	width: 100%;
	}
	.fixed-content {
		width: 200%;
		}
.footer {
<?php IF ($headerlight == "light"){ ?>
		background: #<?php obt_hsl2rgb($headerhue,$headersaturation,240) ?> url(images/lines.gif);
<?php }ELSE{ ?>
		background: #<?php obt_hsl2rgb($headerhue,$headersaturation,80) ?>;
		color: #<?php obt_hsl2rgb($headerhue,$headersaturation/2,200) ?>;
<?php }; ?>
	}
	.footer-ad {
		padding: 0px 10px;
		}
	.footer-bar {
<?php IF ($headerlight == "light"){ ?>
		background: #<?php obt_hsl2rgb($headerhue,$headersaturation,200) ?>;
		height: 1px;
		margin: 1px 0px;
<?php }ELSE{ ?>
		background: #<?php obt_hsl2rgb($headerhue,$headersaturation,100) ?>;
		height: 1px;
		margin: 1px 0px 0px;
<?php }; ?>
		overflow: hidden;
		}
	.footer-content {
		margin: <?php echo ($align == "center")? "0px auto" : "0px" ?>;
		padding: 10px 0px 23px;
		text-align: left;
		width: <?php echo $width ?>;
		}
	.footer-columns {
		padding: 0px 20px;
		}
	.footer-column-content {
		overflow: hidden;
		padding: 0px 10px 2px;
		}
	.footer-column-center {
		float: left;
		width: 34%;
		}
	.footer-column-left, .footer-column-right {
		float: left;
		width: 33%;
		}
	.footer-credits {
		padding: 3px 30px 2px;
		clear: both;
		}
		* +html .footer-credits {
			margin-top: -13px;
			}
	.footer-shadow {
<?php IF ($background == "white"){ ?>
		background: #dcdcdc;
<?php }ELSE{ ?>
		background: #d7d7d7;
<?php }; ?>
		height: 1px;
		overflow: hidden;
		}
.grayed, .grayed a {
	color: #999999;
	}
.header {
	margin: <?php echo ($align == "center")? "0px auto" : "0px" ?>;
	width: <?php echo $width ?>;
	}
	.header-background {
<?php IF ($headerlight == "light"){ ?>
		background: #<?php obt_hsl2rgb($headerhue,$headersaturation,240) ?> url(images/lines.gif);
<?php }ELSE{ ?>
		background: #<?php obt_hsl2rgb($headerhue,$headersaturation,80) ?>;
<?php }; ?>
		}
	.header-bar {
<?php IF ($headerlight == "light"){ ?>
		background: #<?php obt_hsl2rgb($headerhue,$headersaturation,200) ?>;
		height: 1px;
		margin: 1px 0px;
<?php }ELSE{ ?>
		background: #<?php obt_hsl2rgb($headerhue,$headersaturation,100) ?>;
		height: 1px;
		margin: 0px 0px 1px;
<?php }; ?>
		overflow: hidden;
		}
 	.header-content {
		padding: 0px 30px 0px 28px;
		text-align: left;
		}
<?php IF ($menu == "horizontal"){ ?>
	.header-menu {
		clear: right;
		float: right;
		padding-bottom: 10px;
		width: 60%;
		}
<?php }; ?>
	.header-title {
		clear: left;
		float: left;
<?php IF ($headerlight == "light"){ ?>
		padding: 20px 0px 15px;
<?php }ELSE{ ?>
		padding: 17px 0px 18px;
<?php }; ?>
<?php IF ($menu == "horizontal"){ ?>
		width: 40%;
<?php }ELSE{ ?>
		width: 100%;
<?php }; ?>
		}
	.header-shadow {
<?php IF ($background == "white"){ ?>
		background: #dcdcdc;
<?php }ELSE{ ?>
		background: #d7d7d7;
<?php }; ?>
		height: 1px;
		}
	.header-top {
<?php IF ($headerlight == "light"){ ?>
		background: #<?php obt_hsl2rgb($linkhue,$linksaturation/2,120) ?>;
		border-bottom: 2px solid #<?php obt_hsl2rgb($linkhue,$linksaturation,80) ?>;
		border-top: 1px solid #<?php obt_hsl2rgb($linkhue,$linksaturation/2,140) ?>;
		height: 1px;
		margin-bottom: 1px;
<?php }ELSE{ ?>
		background: #<?php obt_hsl2rgb($headerhue,$headersaturation/2,60) ?>;
		border-bottom: 2px solid #<?php obt_hsl2rgb($headerhue,$headersaturation,70) ?>;
		border-top: 1px solid #<?php obt_hsl2rgb($headerhue,$headersaturation/2,40) ?>;
		height: 1px;
<?php }; ?>
		}
.main {
	float: <?php echo ($sidebaralign == "left")? "right" : "left" ?>;
	margin-<?php echo ($sidebaralign == "left")? "left" : "right" ?>: -<?php echo $sidebarwidth ?>;
	width: 100%;
	}
	.main-content {
		margin-<?php echo ($sidebaralign == "left")? "left" : "right" ?>: <?php echo $sidebarwidth ?>;
		padding: <?php echo ($sidebaralign == "left")? "5px 0px 2px 5px" : "5px 5px 2px 0px" ?>;
		}
.none {
	display: none;
	}
.pagination {
	padding: 2px 0px;
	text-align: right;
	}
	.pagination a, .pagination s {
		padding: 1px 6px;
		text-decoration: none;
		}
	.pagination a {
<?php IF ($background == "white"){ ?>
		border: 1px solid #<?php obt_hsl2rgb($linkhue,$linksaturation/3,200) ?>;
<?php }ELSE{ ?>
		border: 1px solid #<?php obt_hsl2rgb($linkhue,$linksaturation/3,192) ?>;
<?php }; ?>
		}
	.pagination s {
<?php IF ($background == "white"){ ?>
		border: 1px solid #dcdcdc;
		color: #dcdcdc;
<?php }ELSE{ ?>
		border: 1px solid #d7d7d7;
		color: #d7d7d7;
<?php }; ?>
		}
	.pagination strong {
		font-weight: normal;
		padding-right: 8px;
		}
.post, .post-info, .comment, .my-comment {
<?php IF ($background != "white"){ ?>
	background: #ffffff;
<?php }; ?>
	border: 1px solid #dcdcdc;
	margin-top: 15px;
	overflow: hidden;
	padding: 1px;
	}
	.post-info {
		margin-top: 3px;
		}
	.post-content, .post-info-content, .comment-content, .my-comment-content {
		padding: 2px 20px 15px;
		}
		.post-content, .post-info-content {
			padding-bottom: 20px;
			}
		* +html .comment-content, * +html .my-comment-content {
			padding-top: 2px;
			}
		* html .post-content, * html .post-info-content, * html .comment-content, * html .my-comment-content {
			padding-top: 17px;
			}
		.my-comment {
			border-color: #<?php obt_hsl2rgb($headerhue,$headersaturation/2,220) ?>;
			}
		.my-comment-content {
			background: #<?php obt_hsl2rgb($headerhue,$headersaturation,250) ?>;
			}
	.post-header, .post-text {
		color: #333333;
		font: 16px/22px Georgia, 'Times New Roman', Times, serif;
		letter-spacing: 0px;
		}
		.post-header {
			line-height: 16px;
			margin: 20px 0px -20px;
			padding: 2px 0px;
			}
		.post-header a {
			text-decoration: none
			}
		.post-header a:hover {
			background: transparent;
			text-decoration: underline;
			}
		.post-text {
			padding-bottom: 5px;
			}
	.comment-text {
		clear: both;
		margin-top: -5px;
		}
		* +html .comment-text {
			margin-top: 0px;
			}
		* html .comment-text {
			margin-top: -15px;
			}
.rss {
	float: right;
	margin: 1px 0px -2px 5px;
	padding-right: 1px;
	}
	* +html .rss {
		margin-top: 0px;
		}
	* html .rss {
		margin-top: 0px;
		}
.share {
	overflow: hidden;
	padding-right: 20px;
	}
	.share-left {
		float: left;
		width: 50%;
		}
	.share-right {
		float: right;
		margin-right: -20px;
		width: 50%;
		}
		* html .share-right {
			margin-right: -10px;
			}
.sidebar {
	float: <?php echo ($sidebaralign == "left")? "left" : "right" ?>;
	margin-top: 10px;
	overflow: hidden;
	width: <?php echo $sidebarwidth ?>;
	}
	.sidebar-content {
		padding: <?php echo ($sidebaralign == "left")? "0px 20px 2px 0px" : "0px 0px 2px 20px" ?>;
		}
	.sidebar-left {
		float: left;
		width: <?php echo $leftsidebarwidth ?>;
		}
		* html .sidebar-left {
			margin-right: -<?php echo $leftsidebarwidth ?>;
			position: relative;
			z-index: 2;
			}
	.sidebar-right {
		float: right;
		margin-right: -<?php echo $leftsidebarwidth ?>;
		width: 100%;
		}
		* html .sidebar-right {
			margin-right: 0px;
			position: relative;
			z-index: 1;
			}
		.sidebar-right .sidebar-content {
			margin-right: <?php echo $leftsidebarwidth ?>;
			}
			* html .sidebar-right .sidebar-content {
<?php IF ($sidebaralign == "right"){ ?>
				margin: 0px 0px 0px 20px;
<?php }ELSE{ ?>
				margin-right: 0px;
<?php }; ?>
				padding-left: <?php echo $leftsidebarwidth ?>;
				}
<?php IF (strpos($sidebarwidth,"%") !== false){ ?>
		body.lt1024 .sidebar-left {
			float: none;
			width: auto;
			}
		body.lt1024 .sidebar-right {
			float: none;
			margin: 0px;
			width: auto;
			}
			body.lt1024 .sidebar-right .sidebar-content {
				margin: 0px;
				}
<?php }; ?>
#wp-calendar {
	width: 100%;
	}
	#wp-calendar caption {
		border-bottom: 1px solid #dcdcdc;
		font: 13px/15px 'Lucida Sans Unicode', 'Lucida Grande', Tahoma, Verdana, sans-serif;
		margin: 15px 0px;
		text-align: left;
		}
	#wp-calendar td, th {
		font-weight: normal;
		padding: 0px;
		}
	#wp-calendar td a {
		font-weight: bold;
		}
@media print {
	.header-menu, .sidebar, .footer {
		display: none;
		}
	.header-title {
		width: 100%;
		padding: 25px 0px 5px;
		}
	.header-top {
		border-top-width: 0px;
		border-bottom-width: 3px;
		height: 0px;
		}
	.header, .header-content, .body, .body-content, .main, .main-content {
		margin: 0px;
		padding: 0px;
		width: 100%;
		}
	.comments {
		page-break-before: always;
		}
	.comments, .comments-content {
		margin-left: 0px;
		margin-right: 0px;
		padding-left: 0px;
		padding-right: 0px;
		width: 100%;
		}
	.post, .post-info, .comment, .my-comment {
		overflow: visible;
		}
	}
<?php require_once(dirname(__FILE__)."/ads/after-css.php") ?>