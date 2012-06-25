<?php
$obt_memory_limit = trim(ini_get("memory_limit"));
$obt_memory_unit = strtolower(substr($obt_memory_limit,-1));
$obt_memory_limit = (int) $obt_memory_limit;
SWITCH ($obt_memory_unit) {
	CASE "g": $obt_memory_limit *= 1024;
	CASE "m": $obt_memory_limit *= 1024;
	CASE "k": $obt_memory_limit *= 1024;
};
IF ($obt_memory_limit >= 0 && $obt_memory_limit < 24*1024*1024) @ini_set("memory_limit","24M");

require_once(dirname(__FILE__)."/theme-translation.php");
require_once(dirname(__FILE__)."/theme-functions.php");
require_once(dirname(__FILE__)."/theme-options.php");
require_once(dirname(__FILE__)."/theme-admin.php");
require_once(dirname(__FILE__)."/theme-feeds.php");
require_once(dirname(__FILE__)."/theme-feed-content.php");
require_once(dirname(__FILE__)."/theme-widgets.php");
require_once(dirname(__FILE__)."/theme-comment-notification.php");
require_once(dirname(__FILE__)."/theme-antispam.php");
require_once(dirname(__FILE__)."/theme-tags.php");


function csur_get_url($blog){
	global $wpdb;
	
	$options = $wpdb->get_var("
	SELECT option_value
	FROM wp_".$blog."_options
	WHERE option_id = 1
	");
	
	return $options;

}

function csur_get_title($blog){
	global $wpdb;
	
	$options = $wpdb->get_var("
	SELECT option_value
	FROM wp_".$blog."_options
	WHERE option_id = 2
	");
	
	return $options;

}

function csur_get_options($blog){
	global $wpdb;
	
	$optblog = $wpdb->get_row("
	SELECT *
	FROM wp_".$blog."_posts
	WHERE post_status = 'publish' 
	AND post_type = 'post'
	ORDER BY post_date DESC
	LIMIT 1
	");
	
	return $optblog;

}

function csur_width(){
	//Si se ha establecido un ancho para el sidebar, le tenemos que restar el ancho de los márgenes interiores del sidebar (si los tuviera)
	if (obt_get_themeoption("width-sidebar") != ''){
		//La función csur_width_sidebar() se encuentra en functions.php
		$anchoS = obt_get_themeoption("width-sidebar");
	}else{
		$anchoS = '350px';//Es el ancho total contando los 10px de margen por cada lado, en realidad el ancho será 350px 
	}
	
	//Si se ha establecido un ancho para el contenido, le tenemos que restar el ancho de los márgenes interiores del contenido (si los tuviera)
	if (obt_get_themeoption("width-cont") != ''){
		//La función csur_width_post() se encuentra en functions.php
		$anchoP = obt_get_themeoption("width-cont");
	}else{
		$anchoP = '630px';//Es el ancho total contando los 10px de margen por cada lado, en realidad el ancho será 630px
	}
	
	$nuevoAnchoPost = csur_width_post($anchoP);
	$nuevoAnchoSidebar = csur_width_sidebar($anchoS);
 
	//Función que establece el ancho de la columna del contenido y del sidebar, está en functions.php
	csur_ancho_cont_sidebar($nuevoAnchoSidebar, $nuevoAnchoPost);
	
	return $nuevoAnchoPost;
}

function csur_width_post($ancho){
	$anchoPostOriginal = $ancho;
	$longAnchoPOriginal = strlen($anchoPostOriginal);
	$anchoPost = explode('px',$anchoPostOriginal);
	$longAnchoP = strlen($anchoPost[0]);
	
	if($longAnchoPOriginal == $longAnchoP)
		$tipo = '%';
	else
		$tipo = 'px';
			
	if($tipo == '%')
	{
		$anchoPost = explode('%',$anchoPostOriginal);
	}
	
	$anchoPost = (integer)$anchoPost[0];
	
	if(obt_get_themeoption("margin-post") != '')
	{
		$margenOriginalPost = obt_get_themeoption("margin-post");
		
		if($tipo == 'px')
		{
			$margenPost = explode('px',$margenOriginalPost);
			$margenPost = (integer)$margenPost[0];
		}
		else
		{
			$margenPost = explode('%',$margenOriginalPost);
			$margenPost = (integer)$margenPost[0];
		}	
	}
	else
	{
		if($tipo == 'px')
			$margenPost = 15; 
		else
			$margenPost = 1;
	}
	
	$tamanyoMargen = (int)$margenPost*2;
	
	(int)$nuevoAnchoPost = (int)$anchoPost - (int)$tamanyoMargen;

	
	if($tipo == 'px')
		$nuevoAnchoPost = $nuevoAnchoPost.'px';
	else
		$nuevoAnchoPost = $nuevoAnchoPost.'%';
		
	return $nuevoAnchoPost;
}

function csur_width_sidebar($ancho){
	$anchoSidebarOriginal = $ancho;
	$longAnchoSOriginal = strlen($anchoSidebarOriginal);
	$anchoSidebar = explode('px',$anchoSidebarOriginal);
	$longAnchoS = strlen($anchoSidebar[0]);
	
	if($longAnchoSOriginal == $longAnchoS)
		$tipo = '%';
	else
		$tipo = 'px';
			
	if($tipo == '%')
	{
		$anchoSidebar = explode('%',$anchoSidebarOriginal);
	}
	
	$anchoSidebar = (integer)$anchoSidebar[0];
	
	if(obt_get_themeoption("margin-sidebar") != '')
	{
		$margenOriginal = obt_get_themeoption("margin-sidebar");
		
		if($tipo == 'px')
		{
			$margenSidebar = explode('px',$margenOriginal);
			$margenSidebar = (integer)$margenSidebar[0];
		}
		else
		{
			$margenSidebar = explode('%',$margenOriginal);
			$margenSidebar = (integer)$margenSidebar[0];
		}	
	}
	else
	{
		if($tipo == 'px')
			$margenSidebar = 15;
		else
			$margenSidebar = 1;
	}
	
	$tamanyoMargenSidebar = (int)$margenSidebar*2;
	(int)$nuevoAnchoSidebar = (int)$anchoSidebar - (int)$tamanyoMargenSidebar;

	if($tipo == 'px')
		$nuevoAnchoSidebar = $nuevoAnchoSidebar.'px';
	else
		$nuevoAnchoSidebar = $nuevoAnchoSidebar.'%';
		
	return $nuevoAnchoSidebar;
}


function csur_plantilla_parrilla(){ ?>
	<style type="text/css">
			#contparrilla {
				width: 996px;
				float: left;
			}
	</style><?php
}

function csur_ancho_cont_sidebar_vacios($nuevoAnchoSidebar, $nuevoAnchoPost){ ?>
	<style type="text/css">
					#cont {
						width: 630px;
						float: <?php if (obt_get_themeoption("sidebar-position") == 'sidebar_left') : 
											echo "right";
									  else:
									  		echo "left"; 
									  endif; ?>;
					}
					#sidebar {
						width: <?php echo $nuevoAnchoSidebar; ?>; /* + 15 de padding por cada lado hacen 350px */
						float: <?php if (obt_get_themeoption("sidebar-position") == 'sidebar_left') : 
											echo "left";
									  else:
									  		echo "right"; 
									  endif; ?>;
						border:
									<?php // Para mostrar o no el recuadro del sidebar yde los posts desde el panel del administrador
									if ((obt_get_themeoption("frame") == 'frame-sidebar-posts-yes') || (obt_get_themeoption("frame") == '')) :
										echo "1px solid #d1d1d1";
									else: 
										echo "0";
								 	endif; ?>;
						padding: 10px <?php if (obt_get_themeoption("margin-sidebar") == '') : 
											echo "15px";
									  else:
									  		echo obt_get_themeoption("margin-sidebar"); 
									  endif; ?>;
					}
					body.entrada #sidebar {
						width: <?php echo $nuevoAnchoSidebar; ?>; /* + 15 de padding por cada lado hacen 350px */
					}
	</style><?php
}

function csur_ancho_cont_sidebar_llenos($nuevoAnchoSidebar, $nuevoAnchoPost){ ?>
	<style type="text/css">
						#cont {
							width: <?php echo obt_get_themeoption("width-cont"); ?>;
							float: <?php if (obt_get_themeoption("sidebar-position") == 'sidebar_left') : 
											echo "right";
									  else:
									  		echo "left"; 
									  endif; ?>;
						}
						#sidebar {
							width: <?php echo $nuevoAnchoSidebar; ?>;
							float: <?php if (obt_get_themeoption("sidebar-position") == 'sidebar_left') : 
											echo "left";
									  else:
									  		echo "right"; 
									  endif; ?>;
							border: <?php 
									// Para mostrar o no el recuadro del sidebar yde los posts desde el panel del administrador
									if ((obt_get_themeoption("frame") == 'frame-sidebar-posts-yes') || (obt_get_themeoption("frame") == '')) :
										echo "1px solid #d1d1d1";
									else: 
										echo "0";
								 	endif; ?>;
							padding: 10px <?php if (obt_get_themeoption("margin-sidebar") == '') : 
											echo "15px";
									  else:
									  		echo obt_get_themeoption("margin-sidebar"); 
									  endif; ?>;
						}
						body.entrada #sidebar {
							width: <?php echo $nuevoAnchoSidebar; ?>;
						}
	</style><?php
}

function csur_solo_ancho_cont_lleno($nuevoAnchoSidebar, $nuevoAnchoPost){ ?>
	<style type="text/css">
							#cont {
								width: <?php echo obt_get_themeoption("width-cont"); ?>;
								float: <?php if (obt_get_themeoption("sidebar-position") == 'sidebar_left') : 
											echo "right";
									  else:
									  		echo "left"; 
									  endif; ?>;
							}
							#sidebar {
								width: <?php echo $nuevoAnchoSidebar; ?>;
								float: <?php if (obt_get_themeoption("sidebar-position") == 'sidebar_left') : 
											echo "left";
									  else:
									  		echo "right"; 
									  endif; ?>;
								border: <?php 
									// Para mostrar o no el recuadro del sidebar yde los posts desde el panel del administrador
									if ((obt_get_themeoption("frame") == 'frame-sidebar-posts-yes') || (obt_get_themeoption("frame") == '')) :
										echo "1px solid #d1d1d1";
									else: 
										echo "0";
								 	endif; ?>;
								padding: 10px <?php if (obt_get_themeoption("margin-sidebar") == '') : 
											echo "15px";
									  else:
									  		echo obt_get_themeoption("margin-sidebar"); 
									  endif; ?>;
							}
							body.entrada #sidebar {
								width: <?php echo $nuevoAnchoSidebar; ?>;
							}
						</style><?php
}

function csur_solo_ancho_sidebar_lleno($nuevoAnchoSidebar, $nuevoAnchoPost){ ?>
	<style type="text/css">
									#cont {
										width: 630px;
										float: <?php if (obt_get_themeoption("sidebar-position") == 'sidebar_left') : 
														echo "right";
									  				else:
									  					echo "left"; 
									  				endif; ?>;
									}
									#sidebar {
										width: <?php echo $nuevoAnchoSidebar; ?>;
										float: <?php if (obt_get_themeoption("sidebar-position") == 'sidebar_left') : 
														echo "left";
									  				else:
									  					echo "right"; 
									  				endif; ?>;
										border: <?php 
													// Para mostrar o no el recuadro del sidebar yde los posts desde el panel del administrador
													if ((obt_get_themeoption("frame") == 'frame-sidebar-posts-yes') || (obt_get_themeoption("frame") == '')) :
														echo "1px solid #d1d1d1";
													else: 
														echo "0";
								 					endif; ?>;
										padding: 10px <?php if (obt_get_themeoption("margin-sidebar") == '') : 
											echo "15px";
									  else:
									  		echo obt_get_themeoption("margin-sidebar"); 
									  endif; ?>;
									}
									body.entrada #sidebar {
										width: <?php echo $nuevoAnchoSidebar; ?>
									}
								</style><?php
}


//Función que establece el ancho de la columna del contenido y del sidebar, está en functions.php
function csur_ancho_cont_sidebar($nuevoAnchoSidebar, $nuevoAnchoPost){
	// Para poder cambiar el ancho de cont desde el panel del administrador
	if (obt_get_themeoption("plantilla") == 'parrilla') :
		
		csur_plantilla_parrilla();
	
	else : 
			if (($nuevoAnchoPost == '') && ($nuevoAnchoSidebar == '')) :
				
				csur_ancho_cont_sidebar_vacios($nuevoAnchoSidebar, $nuevoAnchoPost);
				
			else : 
				if (($nuevoAnchoPost != '') && ($nuevoAnchoSidebar != '')) :
					
					csur_ancho_cont_sidebar_llenos($nuevoAnchoSidebar, $nuevoAnchoPost);
					
				else:
					if ($nuevoAnchoPost != ''):
					
						csur_solo_ancho_cont_lleno($nuevoAnchoSidebar, $nuevoAnchoPost);
					
					else: 
							if ($nuevoAnchoSidebar != ''):
							
								csur_solo_ancho_sidebar_lleno($nuevoAnchoSidebar, $nuevoAnchoPost);
							
							endif;
					 endif;
				 endif;
		 endif;
	endif;
}

// Para poder hacer cambios desde el panel del administrador del color del título
function csur_color_title(){
	
	$user_agent = $_SERVER['HTTP_USER_AGENT'];
	$posicion = strrpos($user_agent, "MSIE");
	 
	if ($posicion === false) {
			$ie = false;
	} else {
			$ie = true;
	} 
	if($ie){ ?>
			<style type="text/css">
				h2, h2 a{
					font-size:1.2em;
				}
			</style>
	<?php }
		
	if (obt_get_themeoption("title-color") == '') :  ?>	
		<style type="text/css">
			h2 a, h2 a:visited {
				color: #4d729a;
				margin-top: 10px;
				margin-bottom: 10px;
				text-decoration: none;
			}
			h2 a:hover{
				color: #4d729a;
				margin-top: 10px;
				margin-bottom: 10px;
				text-decoration: underline;
			}
		</style>
	<?php else : ?>
		<style type="text/css">
			h2 a, h2 a:visited {
				color: <?php echo obt_get_themeoption("title-color"); ?>;
				margin-top: 10px;
				margin-bottom: 10px;
				text-decoration: none;
			}
			h2 a:hover{
				color: <?php echo obt_get_themeoption("title-color"); ?>;
				margin-top: 10px;
				margin-bottom: 10px;
				text-decoration: underline;
			}
		</style>
	<?php endif;
}

// Para poder poner y quitar los recuadros de las imágenes
function csur_images_frames(){
	if ((obt_get_themeoption("image-frame") == 'frame-yes') || (obt_get_themeoption("image-frame") == '')) :  ?>
		<style type="text/css">
			.post p img {
					padding: 5px;
					border: 1px solid #d1d1d1;
					margin: 0 10px 0 0;
			}
		</style>
	<?php else : ?>
		<style type="text/css">
			.post p img {
					padding: 5px;
					border: 0 solid #000000;
					margin: 0 10px 0 0;
			}
		</style>
	<?php endif;
}

// Para poder poner y quitar los recuadros del sidebar y de los posts
function csur_post_sidebar_frames(){
	if ((obt_get_themeoption("frame") == 'frame-sidebar-posts-yes') || (obt_get_themeoption("frame") == '')) :  ?>
		<style type="text/css">
			.post, .modulo-0, .modulo-2, .modulo-4, .modulo-6, .modulo-1, .modulo-3, .modulo-5 {
				border: 1px solid #d1d1d1;	
			}
		</style>
	<?php else : ?>
		<style type="text/css">
			.post, .modulo-0, .modulo-2, .modulo-4, .modulo-6, .modulo-1, .modulo-3, .modulo-5 {
				border:0;	
			}
		</style>
	<?php endif;
}

// Para poder cambiar los márgenes internos entre el texto y el recuadro
function csur_internal_margins(){
	if (obt_get_themeoption("margin-post") == '') :  ?>
		<style type="text/css">
			.post {
				padding: 10px 15px;
			}
			.modulo-0{
				padding: 5px 15px 10px;
			}
		</style>
	<?php else : ?>
		<style type="text/css">
			.post {
				padding: 10px <?php echo obt_get_themeoption("margin-post"); ?>;
			}
			.modulo-0{
				padding: 5px <?php echo obt_get_themeoption("margin-post"); ?> 10px;
			}
		</style>
	<?php endif;
}

//Código para poder cambiar desde el panel si el sidebar va a derecha o izquierda
function csur_left_sidebar(){
	if (obt_get_themeoption("plantilla") == 'parrilla') : 
	else : 
	  	if (obt_get_themeoption("sidebar-position") == 'sidebar_left') :	
			 get_sidebar();
		else: 
		endif;
	endif;
}

//Código para poder cambiar desde el panel si el sidebar va a derecha o izquierda
function csur_right_sidebar(){
	if (obt_get_themeoption("plantilla") == 'parrilla') : 
	  else : 
	  		if ((obt_get_themeoption("sidebar-position") == 'sidebar-right') || (obt_get_themeoption("sidebar-position") == '')) :
				get_sidebar();
			else:
			endif;
	endif;
}

// Si se pone una cabecera personalizada, no debe aparecer el tÃ­tulo del blog.
function csur_custom_header(){
	if (obt_get_themeoption("image") == '' || obt_get_themeoption("image") == 'http://') :  ?>
		<h2><?php bloginfo('name'); ?></h2>
	<?php else :
	endif;
}

//Función para mostrar los enlaces a post anterior y siguiente
function csur_sig_ant_post_enlace(){
	global $current_user;
    
    get_currentuserinfo();
	if($current_user->user_level > 8): ?>
		<br>
		<div class="navigation">
     		<div class="clear-page"></div>
     		<div class="alignleft"><span class="post-link-format"><?php previous_post_link('&laquo; %link') ?></span></div>
      		<div class="alignright"><span class="post-link-format"><?php next_post_link('%link &raquo;') ?></span></div>
      		<div class="clear"></div>
    	</div>
    	<br>
    <?php 	endif;
}

//Función para abrir el div metaspost que englobará a autor y fecha
function csur_open_div_metaspost(){
	if ((obt_get_themeoption("autor") == 'si') ||  (obt_get_themeoption("fecha") == 'si')): ?>
		<p class="metaspost">
	<?php endif;
}

//Función para cerrar el div metaspost que engloba a autor y fecha
function csur_close_div_metaspost(){
	if ((obt_get_themeoption("autor") == 'si') ||  (obt_get_themeoption("fecha") == 'si')): ?>
		</p>
	<?php endif;
}

//Función para mostrar el autor o no
function csur_show_author(){
	if (obt_get_themeoption("autor") == 'si') : ?>
		Por <?php the_author_posts_link(); ?>. 
	<?php else : 
	endif;
}

//Función para mostrar la fecha del post o no
function csur_show_date(){
	if (obt_get_themeoption("fecha") == 'si') : 
		echo the_time('l'); echo ', '; echo the_time('j'); echo ' de '; echo the_time('F'); echo ' de '; echo the_time('Y'); echo'.';
	else : 
	endif;
}

//Función para Post - Debajo de la entrada
function csur_post_debajo_entrada(){
	if ( function_exists('dynamic_sidebar') && dynamic_sidebar('Post - Debajo de la entrada') ) : 
	else :
	endif;
}

//Función para mostrar el enlace a Suscríbete
function csur_show_subscribe(){
	if (obt_get_themeoption("suscribete") == 'no') : 
	else : ?>
		<h3>Suscr&iacute;bete</h3>
		<ul style="list-style-type:none;margin-left:0;">
			<li><a href="<?php bloginfo('comments_rss2_url'); ?>" class="rsslink">Suscr&iacute;bete a los comentarios del blog</a></li>
			<li class="rsslink"><?php post_comments_feed_link($link_text = 'Suscr&iacute;bete a los comentarios de esta entrada'); ?></li>
		</ul>
	<?php endif;
}

//Función para mostrar los trackbacks o no
function csur_show_trackbacks(){
	if (obt_get_themeoption("trackbacks") == 'no') : 
	else : ?>
		<h3>Trackbacks</h3>
		<ul style="list-style-type:none;margin-left:0;">
			<li><a href="<?php the_permalink() ?>#trackbacks" class="tracklink">Todos los enlaces a este blog (Technorati)</a></li>
			<li><a href="#" class="tracklink">Enlaces a esta entrada (Technorati)</a></li>
		</ul>
		<p style="font-size:x-small;">Para notificar de una menci&oacute;n en tu blog a esta entrada, habilita la notificaci&oacute;n autom&aacute;tica (Opciones > Discusi&oacute;n en WordPress) o especifica esta url de trackback: <?php the_permalink(); ?>Trackback/</p>
	<?php endif;
}

//Funciones para Sala de Prensa
//Función para establecer el ancho del módulo0
function csur_ancho_modulo0($nuevoAnchoPost){ ?>
		<style type="text/css">
			.modulo-0 {
				width: <?php echo $nuevoAnchoPost; ?>; 
				float: <?php if (obt_get_themeoption("sidebar-position") == 'sidebar_left') : 
								echo "right";
							else:
								echo "left"; 
							endif; ?>;
				
				<?php if(obt_get_themeoption("background-color-SP") != ''): ?>
					background-color: <?php echo obt_get_themeoption("background-color-SP"); ?>;
				<?php endif; ?>
				
				<?php if((obt_get_themeoption("background-SP") != '')): ?>
					background: url(<?php echo obt_get_themeoption("background-SP"); ?>);
				<?php endif;?>
				
			}
		</style><?php
}

//Función para establecer el ancho del cont
function csur_ancho_div_cont($nuevoAnchoPost){ ?>
		<style type="text/css">
			#cont {
				width: <?php echo $nuevoAnchoPost; ?>; 
				float: <?php if (obt_get_themeoption("sidebar-position") == 'sidebar_left') : 
								echo "right";
							else:
								echo "left"; 
							endif; ?>;	
			}
		</style><?php
}

//Función para mostrar los módulos de la portada de Sala de Prensa
function csur_show_modules(){
	
	csur_show_module(0);
	csur_show_module(1);
	csur_show_module(2);
	csur_show_module(3);
	csur_show_module(4);
	csur_show_module(5);
	csur_show_module(6);
}

function csur_show_module($module){ 
	global $post;
	
	switch ($module){
				case 0:
					$cat = 3242;
					break;
				case 1:
					$cat = 3241;
					break;
				case 2:
					$cat = 3243;
					break;
				case 3:
					$cat = 3244;
					break;
				case 4:
					$cat = 3245;
					break;
				case 5:
					$cat = 3246;
					break;
				case 6:
					$cat = 3247;
					break;
				default:
					$cat = 0;
	} 
	?>
	<div class="modulo-<?php echo $module; ?>">
		<?php			
			$module_query = 'showposts=1&cat='.$cat.'&order=DESC';
			query_posts($module_query);
			
	 			if ( have_posts() ) : while ( have_posts() ) : the_post();  
						//Función para abrir el div metaspost que engloba el autor y la fecha
						csur_open_div_metaspost();
							
						//Función para mostrar el autor del post o no
						csur_show_author();
							
						//Función para mostrar la fecha del post o no
						csur_show_date();
							
						//Función para cerrar el div que engloba al autor y la fecha
						csur_close_div_metaspost();
				 		?>
						<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
						<div class="modulo-<?php echo $module; ?>-foto">
							<?php $imagen_portada = get_post_meta($post->ID, 'imagen-portada', true); 
								if($imagen_portada != ''): ?>
								<img src="<?php echo $imagen_portada; ?>" />		
							<?php endif;?>
						</div>
						<div class="modulo-<?php echo $module; ?>-excerpt">
							<?php 
								/*if($module == 0){
									echo improved_trim_excerpt('', 165);
								}else{
									if($imagen_portada == '')
										echo improved_trim_excerpt('', 220);
									else
										the_excerpt();
								}*/
								the_excerpt();
							?>
							
							<?php edit_post_link(__("| Editar |"), ''); ?>
						</div>
				<?php endwhile; else:
				endif;
			//Reset Query
			wp_reset_query(); ?>
	</div>
	
	<?php
}

//function improved_trim_excerpt($text, $excerpt_length) {
function improved_trim_excerpt($text) {
	global $post;
//	if($excerpt_length == '')
			$excerpt_length = 120;
	if ( '' == $text ) {
		$text = get_the_content('');
		$text = apply_filters('the_content', $text);
		$text = str_replace(']]>', ']]&gt;', $text);
		$text = preg_replace('@<script[^>]*?>.*?</script>@si', '', $text);
		$text = strip_tags($text, '<p>');
		$words = explode(' ', $text, $excerpt_length + 1);
		if (count($words)> $excerpt_length) {
			array_pop($words);
			array_push($words, '[...]');
			$text = implode(' ', $words);
		}
	}
	return $text;
}

remove_filter('get_the_excerpt', 'wp_trim_excerpt');
add_filter('get_the_excerpt', 'improved_trim_excerpt');
?>