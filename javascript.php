<?php
@header("Content-type: text/javascript");
@header("Cache-control: max-age=".(12*60*60).", must-revalidate");
@header("Expires: ".gmdate("D, d M Y H:i:s",time()+12*60*60)." GMT");

IF ($_GET["gzip"] && extension_loaded("zlib")) ob_start("ob_gzhandler");

require_once(dirname(__FILE__)."/ads/before-js.php");
?>
function showForm(name,id){
	var list = document.getElementById(name+id);
	var menu = document.getElementById(name+"Menu"+id);
	var form = document.getElementById(name+"Form"+id);
	listHTML = list.innerHTML.toString();
	list.innerHTML = list.innerHTML.substr(0,list.innerHTML.toLowerCase().lastIndexOf("<li>"));
	menu.innerHTML = menu.innerHTML.substr(0,menu.innerHTML.toLowerCase().lastIndexOf("<li>"));
	form.style.display = "block";
};
function showMenu(name,id){
	var list = document.getElementById(name+id);
	var menu = document.getElementById(name+"Menu"+id);
	var placer = document.getElementById(name+"Placer"+id);
	if (placer) placer.style.display = "none";
	else list.style.display = "none";
	menu.style.display = "block";
};
function initMenu(id,name,text,image,alt,text2,image2,alt2){
	if (document.getElementById){
		var menu = document.getElementById(name+"Menu"+id);
		var form = document.getElementById(name+"Form"+id);
		var placer = document.getElementById(name+"Placer"+id);
		if (menu) if (menu.style){
			menu.style.display = "none";
			var list = "<ul id=\""+name+id+"\" class=\"social-buttons\">\n";
			list += "<li><a href=\"javascript:// "+alt+"\" onclick=\"showMenu('"+name+"',"+id+")\" title=\""+alt+"\"><img src=\""+image+"\" width=\"14\" height=\"14\" alt=\""+alt+"\" />"+text+"</a></li>\n";
			if (text2) if (form){
				form.style.display = "none";
				var menuItem = "<li><a href=\"javascript:// "+alt2+"\" onclick=\"showForm('"+name+"',"+id+")\" title=\""+alt2+"\"><img src=\""+image2+"\" width=\"14\" height=\"14\" alt=\""+alt2+"\" />"+text2+"</a></li>\n";
				menu.innerHTML += menuItem;
				list += menuItem;
			};
			list += "</ul>\n";
			if (placer) placer.innerHTML = list;
			else document.write(list);
		};
	};
};
function getCookie(name){
	name = name + "=";
	var cookieArray = document.cookie.split(";");
	for(var i = 0; i < cookieArray.length; i++){
		var cookie = cookieArray[i];
		while (cookie.charAt(0) == " ") cookie = cookie.substring(1,cookie.length);
		if (cookie.indexOf(name) == 0) return unescape(cookie.substring(name.length,cookie.length).replace(/\+/g," "));
	};
	return "";
};
function fillForm(cookiehash,email,name,url){
	if (document.getElementById){
		if (email) if (document.getElementById(email)) document.getElementById(email).value = getCookie("comment_author_email_"+cookiehash);
		if (name) if (document.getElementById(name)) document.getElementById(name).value = getCookie("comment_author_"+cookiehash);
		if (url) if (document.getElementById(url)) document.getElementById(url).value = getCookie("comment_author_url_"+cookiehash);
	};
};
function thankComment(text){
	if (document.getElementById){
		var hash = document.location.hash;
		if (hash.substring(0,9) == "#comment-") if (document.location.href.indexOf(document.referrer) == 0){
			hash = hash.substring(1);
			if (!document.getElementById(hash)) alert(text);
		};
	};
};
function addClassName(){
	if (document.getElementsByTagName){
		var browserWidth = document.all ? document.body.clientWidth : window.innerWidth;
		if (browserWidth){
			var className = "";
			if (browserWidth > 1600) className += " mt1600";
			if (browserWidth > 1400) className += " mt1400";
			if (browserWidth > 1280) className += " mt1280";
			if (browserWidth > 1024) className += " mt1024";
			if (browserWidth > 800-25) className += " mt800";
			if (browserWidth < 1600-25) className += " lt1600";
			if (browserWidth < 1400-25) className += " lt1400";
			if (browserWidth < 1280-25) className += " lt1280";
			if (browserWidth < 1024-25) className += " lt1024";
			if (browserWidth < 800-25) className += " lt800";
			var body = document.getElementsByTagName("body")[0];
			body.className = body.className.replace(/ (mt|lt)[0-9]+/gi,"");
			body.className += className;
		};
	};
};
function resizeDivs(){
	clearTimeout(timer);
	var timer = setTimeout("addClassName();",50);
};
window.onresize = resizeDivs;
window.onload = resizeDivs;
<?php require_once(dirname(__FILE__)."/ads/after-js.php") ?>