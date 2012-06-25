<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<?php 
global $obt_comments_paginated, $paged, $obt_comments_this_page;
IF (is_home()){
	$obt_title = wp_specialchars(get_bloginfo("name"),true);
	IF (strlen(get_bloginfo("description"))) $obt_title .= ": ".wp_specialchars(get_bloginfo("description"),true);
	$obt_description = wp_specialchars(obt_first_titles(5),true);
}ELSEIF (is_404()){
	$obt_title = $obt_description = obt_translate("No post was found");
}ELSEIF (is_single() || is_page()){
	$obt_title = apply_filters("the_title",$post->post_title);
	IF (!strlen($obt_title)) $obt_title = obt_translate("Untitled");
	IF (strlen($post->post_password)) $obt_title = obt_translate("%1 (protected)",$obt_title);
	$obt_title = wp_specialchars($obt_title,true);
	$obt_description = $obt_title;
	IF ($obt_comments_paginated){
		IF (is_single()) $obt_description = obt_translate("You are currently viewing the comments for the post %1 on the blog %2",$obt_title,wp_specialchars(get_bloginfo("name"),true));
		IF (is_page()) $obt_description = obt_translate("You are currently viewing the comments for the post %1 on the blog %2",$obt_title,wp_specialchars(get_bloginfo("name"),true));
		$obt_title = obt_translate("Comments").": ".$obt_title;
	}ELSE{
		IF (!strlen($post->post_excerpt)){
			$obt_post_excerpt = get_the_content();
			$obt_post_excerpt = apply_filters("get_the_excerpt",$obt_post_excerpt);
			$obt_post_excerpt = apply_filters("the_excerpt",$obt_post_excerpt);
			$obt_post_excerpt = obt_excerpt($obt_post_excerpt);
		}ELSE{
			$obt_post_excerpt = $post->post_excerpt;
			$obt_post_excerpt = apply_filters("get_the_excerpt",$post->post_excerpt);
			$obt_post_excerpt = apply_filters("the_excerpt",$obt_post_excerpt);
			$obt_post_excerpt = str_replace(array("<br />","</p>","</ul>","</ol>","</blockquote>","\n")," ",$obt_post_excerpt);
			$obt_post_excerpt = strip_tags($obt_post_excerpt);
			WHILE (strpos($obt_post_excerpt,"  ") !== false) $obt_post_excerpt = str_replace("  "," ",$obt_post_excerpt);
			$text = trim($obt_post_excerpt);
		};
		IF (strlen($obt_post_excerpt)) $obt_description .= ": ".$obt_post_excerpt;
	};
}ELSEIF (is_category()){
	$obt_title = $obt_description = wp_specialchars(ucfirst(single_cat_title("",false)),true);
	$obt_category_description = category_description();
	IF (trim($obt_category_description) == "</p>") $obt_category_description = "";
	IF (strlen($obt_category_description)) $obt_title .= ": ".obt_excerpt($obt_category_description,10);
	$obt_description .= ": ".wp_specialchars(obt_first_titles(5),true);
	unset($obt_category);
}ELSE{
	IF (obt_is_tag()) $obt_title = $obt_description = ucfirst(obt_current_tag());
	ELSEIF (is_search()){
		$obt_title = $obt_description = wp_specialchars(ucfirst($s),true);
		IF ($obt_tags = wp_specialchars(ucfirst(obt_tag_keywords(3,$s)),true)) $obt_title .= ": {$obt_tags}";
	}ELSEIF (is_year()) $obt_title = $obt_description = get_the_time("Y");
	ELSEIF (is_month()) $obt_title = $obt_description = obt_translate("%1 %2",obt_translate_months(get_the_time("F")),get_the_time("Y"));
	ELSEIF (is_day()) $obt_title = $obt_description = obt_translate("%2 %1 %3",get_the_time("j"),obt_translate_months(get_the_time("F")),get_the_time("Y"));
	ELSEIF (is_author()) $obt_title = $obt_description = wp_specialchars(get_the_author_nickname(),true);
	$obt_description .= ": ".wp_specialchars(obt_first_titles(5),true);
};
IF ($paged > 1){
	$obt_title .= ": ".obt_translate("Page %1",$paged);
	$obt_description = obt_translate("Page %1",$paged).": {$obt_description}";
};
IF ($obt_comments_this_page > 1){
	$obt_title .= ": ".obt_translate("Page %1",$obt_comments_this_page);
	$obt_description = obt_translate("Page %1",$obt_comments_this_page).": {$obt_description}";
};
IF (!is_404()) IF (is_category()){
	IF (!strlen($obt_category_description)) IF ($obt_tags = wp_specialchars(ucfirst(obt_tag_keywords(3,single_cat_title("",false))),true)) $obt_title .= ": {$obt_tags}";
	$obt_keywords = wp_specialchars(ucfirst(single_cat_title("",false)),true);
	IF ($obt_tags = wp_specialchars(obt_tag_keywords(9,single_cat_title("",false)),true)) $obt_keywords .= ", {$obt_tags}";
}ELSEIF (obt_is_tag()){
	IF ($obt_tags = wp_specialchars(ucfirst(obt_tag_keywords(3,obt_current_tag())),true)) $obt_title .= ": {$obt_tags}";
	$obt_keywords = wp_specialchars(ucfirst(obt_current_tag()),true);
	IF ($obt_tags = wp_specialchars(obt_tag_keywords(9,obt_current_tag()),true)) $obt_keywords .= ", {$obt_tags}";
}ELSE{
	IF (is_date() || is_author()) $obt_title .= ": ".wp_specialchars(ucfirst(obt_tag_keywords(3)));
	$obt_keywords = wp_specialchars(ucfirst(obt_tag_keywords()),true);
};
IF (!is_home()){
	IF (obt_get_themeoption("site-title") != "beginning") $obt_title .= ": ".wp_specialchars(get_bloginfo("name"),true);
	ELSE $obt_title = wp_specialchars(get_bloginfo("name"),true).": {$obt_title}";
};


?>
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>

<head profile="http://gmpg.org/xfn/11">
<?php 
	// Para poder hacer cambios desde el panel del administrador
	if (obt_get_themeoption("font-color") == '') :  ?>
		<style type="text/css">
		body {
				 font: .75em/1.35em "Trebuchet MS", Verdana, "Lucida Sans Unicode", "Lucida Grande", Tahoma, Arial, sans-serif;
				 }
	</style>
	<?php else : ?>
	<style type="text/css">
		body {
				 font: .75em/1.35em "Trebuchet MS", Verdana, "Lucida Sans Unicode", "Lucida Grande", Tahoma, Arial, sans-serif;
				 color: <?php echo obt_get_themeoption("font-color"); ?>;
				 }
	</style>
	<?php endif; ?>
	
	<?php if (obt_get_themeoption("image") == '' || obt_get_themeoption("image") == 'http://') :  ?>
	<style type="text/css">
		#header {
			background: transparent;
		}
	</style>
	<?php else : ?>
	
	<style type="text/css">
		#header {
			background: transparent;
		}
	</style>
	<?php endif; ?>

	<?php if (obt_get_themeoption("colorlink") == '') :  ?>
	<style type="text/css">
		a, a:visited {
			color: #74a6cf;
		}
	</style>
	<?php else : ?>
	<style type="text/css">
		a, a:visited {
			color: <?php echo obt_get_themeoption("colorlink"); ?>;
		}
	</style>
	<?php endif; ?>
	
	<?php if (obt_get_themeoption("colorlink-hover") == '') :  ?>
	<style type="text/css">
		a:hover {
			color: #ee3b33;
		}
	</style>
	<?php else : ?>
	<style type="text/css">
		a:hover {
			color: <?php echo obt_get_themeoption("colorlink-hover"); ?>;
		}
	</style>
	<?php endif; ?>
	
	
	<?php if ((obt_get_themeoption("background-color") == '') && (obt_get_themeoption("background-image") == '')) :  ?>
	<style type="text/css">
		body {
			/*background: transparent url(http://devel.mecus.es/canalsur/wp-content/themes/Portada/img/back-body.gif) 0 0 repeat-x;*/
		}
	</style>
	<?php else : 
			if ((obt_get_themeoption("background-color") != '') && (obt_get_themeoption("background-image") != '')) :?>
			<style type="text/css">
				body {
					background: transparent url(<?php echo obt_get_themeoption("background-image"); ?>) 0 0 repeat-x;
				}
			</style>
			<?php else : 
					if (obt_get_themeoption("background-image") != '') :?>
					<style type="text/css">
						body {
							background: transparent url(<?php echo obt_get_themeoption("background-image"); ?>) 0 0 repeat-x;
						}
					</style>
					<?php else : ?>
						<style type="text/css">
							body {
								background: <?php echo obt_get_themeoption("background-color"); ?>;
							}
						</style>
					<?php endif; ?>
			<?php endif; ?>
	<?php endif; ?>
	
	<?php // Para poder hacer cambios desde el panel del administrador del color del título
		if (obt_get_themeoption("title-color") == '') :  ?>
			<style type="text/css">
			h3 {
				color: #4d729a;
				border-bottom: 1px dashed #cfcfcf;
				margin-top: 10px;
				margin-bottom: 10px;
				text-decoration: none;
				line-height:1.3em;
			}
			</style>
		<?php else : ?>
			<style type="text/css">
			h3 {
				color: <?php echo obt_get_themeoption("title-color"); ?>;
				border-bottom: 1px dashed #cfcfcf;
				margin-top: 10px;
				margin-bottom: 10px;
				text-decoration: none;
				line-height:1.3em;
			}
			</style>
		<?php endif; ?>



    <meta
        http-equiv="Content-Type"
        content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>"
    />
<link rel="shortcut icon" href="<?php bloginfo('template_directory'); ?>/images/favicon.ico" type="image/x-icon" />

    <title>
        <?php bloginfo('name'); ?>
        <?php if ( is_single() ) { ?>
            &raquo; Blog Archive
        <?php } ?>
        <?php wp_title(); ?>
    </title>

    <meta
        name="generator"
        content="WordPress <?php bloginfo('version'); ?>"
    /> <!-- leave this for stats -->

    <link
        rel="stylesheet"
        href="<?php bloginfo('stylesheet_url'); ?>"
        type="text/css" media="screen"
    />

    <link
        rel="alternate"
        type="application/rss+xml"
        title="<?php bloginfo('name'); ?> RSS Feed"
        href="<?php bloginfo('rss2_url'); ?>"
    />

    <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
<?php wp_head(); ?>
		
		<!--[if lt IE 7]>
			<script src="http://ie7-js.googlecode.com/svn/version/2.0(beta3)/IE7.js" type="text/javascript"></script>
			<link rel="stylesheet" type="text/css" media="screen" href="http://irandalucia.es/wp-content/themes/ira/css/ie/ie6.css" />
		<![endif]-->
		<!--[if lt IE 8]>
			<script src="http://ie7-js.googlecode.com/svn/version/2.0(beta3)/IE8.js" type="text/javascript"></script>
			<link rel="stylesheet" type="text/css" media="screen" href="http://irandalucia.es/wp-content/themes/ira/css/ie/ie7.css" />
		<![endif]-->




	</head>

	<body class="entrada">
	
<!– PUBLICIDAD SOBRECABECERA –>
<div align="center" style="padding-top:10px;">
	<table width="973" border="0" cellspacing="0" cellpadding="0" bgcolor="#d8d8d8">
		<tr>
			<td width="15"><img src="http://blogs.canalsur.es/files/publi90x15.gif"></td>
			<td width="728" bgcolor="#FFFFFF">
			</td>
			<td width="230">
				<div><a href="http://www.canalsur.es/portal_rtva/web/noticia/id/185575"><img src="http://www.canalsur.es/resources/archivos/2012/1/19/1326969660699banner_230x90_fondo_blanco.jpg"/>
				</div>
			</td>
		</tr>
	</table>
</div>
<!– // PUBLICIDAD SOBRECABECERA –>


				
								
				<?php if ( function_exists('dynamic_sidebar') && dynamic_sidebar('Sobre-cabecera') ) : ?>
				  <?php  else : ?>
				  <?php  endif;	?>
				 
				<div id="sobre_cabecera" style="text-align:center;padding-bottom:10px;">
					<?php echo obt_get_themeoption('sobre_cabecera'); ?>
				</div>
				
				<div id="imagen_cabecera" style="text-align:center;padding-bottom:5px;">
					<a href="<?php bloginfo('url'); ?>">
						<img src="<?php if (obt_get_themeoption('image') == '' || obt_get_themeoption('image') == 'http://') : 
										echo 'http://blogs.canalsur.es/wp-content/themes/Tema_CanalSur/img/background-cabecera.jpg';?>">	
									<?php else:
										echo obt_get_themeoption('image'); ?>">
									<?php endif; ?>
					</a>
				</div>
<div id="header">
				<h1 class="titulo"><?php the_title(); ?></h1>
	<?php 
	// Para poder hacer desaparecer el menú desde el panel del administrador (En "Configurar plantilla para temas")
	if ((obt_get_themeoption("menu-display") == 'menu_yes') || (obt_get_themeoption("menu-display") == '')) :  ?>
	
	<ul>
		<li>
				    <?php if ( function_exists('dynamic_sidebar') && dynamic_sidebar('menu') ) : 
				    else :
				    ?>
					<li><a href="http://canalsur.es">canalsur.es</a></li>
					<li><a href="http://blogs.canalsur.es">Blogs RTVA</a></li>
					<li><a href="http://www.canalsur.es/portal_rtva/impe/web/parrilla">Programación</a></li>
					<li><a href="http://www.radiotelevisionandalucia.es/tvcarta/impe/web/portada">Emisiones en directo</a></li>
					<li><a href="http://www.canalsur.es/portal_rtva/impe/web/bandeja?seccion=555">Radio a la carta</a></li>
					<li><a href="http://latienda.canalsur.es/">La Tienda</a></li>
					<li><a href="http://informativos.canalsur.es" class="last">Informativos</a></li>
					<?php endif; ?>
		</li>
	</ul>
	<?php else:?>
	<?php endif; ?>
</div><!-- end header -->
		<div id="wrapper">

