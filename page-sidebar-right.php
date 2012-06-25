<?php
/*
Template Name: Plantilla Pagina Sidebar Derecha
*/
?>
<?php get_header(); ?>

<?php //Si se ha establecido un ancho para el sidebar, le tenemos que restar el ancho de los márgenes interiores del sidebar (si los tuviera)
if (obt_get_themeoption("width-sidebar") != '')
{
	$anchoSidebarOriginal = obt_get_themeoption("width-sidebar");
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
	
	$nuevoAnchoSidebar = $anchoSidebar - ($margenSidebar*2);
	
	if($tipo == 'px')
		$nuevoAnchoSidebar = $nuevoAnchoSidebar.'px';
	else
		$nuevoAnchoSidebar = $nuevoAnchoSidebar.'%';
}
else
{
	$nuevoAnchoSidebar = '';
}
?>
	
	<?php 
	
			if ((obt_get_themeoption("width-cont") == '') && ($nuevoAnchoSidebar == '')) :  ?>
				<style type="text/css">
					#cont {
						width: 598px;
						float: left;
					}
					#sidebar {
						width: 320px;
						float: right;
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
						width: 320px;
					}
				</style>
		<?php else : 
				if ((obt_get_themeoption("width-cont") != '') && ($nuevoAnchoSidebar != '')) :  ?>
					<style type="text/css">
						#cont {
							width: <?php echo obt_get_themeoption("width-cont"); ?>;
							float: left;
						}
						#sidebar {
							width: <?php echo obt_get_themeoption("width-sidebar"); ?>;
							float: right;
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
					</style>
				<?php else:
					if (obt_get_themeoption("width-cont") != ''): ?>
						<style type="text/css">
							#cont {
								width: <?php echo obt_get_themeoption("width-cont"); ?>;
								float: left;
							}
							#sidebar {
								width: 320px;
								float: right;
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
								width: 320px;
							}
						</style>
					<?php else: 
							if ($nuevoAnchoSidebar != ''): ?>
								<style type="text/css">
									#cont {
										width: 598px;
										float: left;
									}
									#sidebar {
										width: <?php echo obt_get_themeoption("width-sidebar"); ?>;
										float: right;
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
								</style>
							<?php endif; ?>
					<?php endif; ?>
				<?php endif; ?>
		<?php endif; ?>
	
	<?php 
	// Para poder hacer cambios desde el panel del administrador del color del t’tulo
	if (obt_get_themeoption("title-color") == '') :  ?>
		<style type="text/css">
			h2 a, h2 a:visited {
				color: #4d729a;
				margin-top: 10px;
				margin-bottom: 10px;
				text-decoration: none;
				line-height:1.3em;
			}
			h2 a:hover{
				color: #4d729a;
				margin-top: 10px;
				margin-bottom: 10px;
				text-decoration: underline;
				line-height:1.4em;
			}
		</style>
	<?php else : ?>
		<style type="text/css">
			h2 a, h2 a:visited {
				color: <?php echo obt_get_themeoption("title-color"); ?>;
				margin-top: 10px;
				margin-bottom: 10px;
				text-decoration: none;
				line-height:1.3em;
			}
			h2 a:hover{
				color: <?php echo obt_get_themeoption("title-color"); ?>;
				margin-top: 10px;
				margin-bottom: 10px;
				text-decoration: underline;
				line-height:1.4em;
			}
		</style>
	<?php endif; ?>
	
	<?php 
	// Para poder poner y quitar los recuadros de las im‡genes
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
	<?php endif; ?>

	<?php 
	// Para poder poner y quitar los recuadros del sidebar y de los posts
	if ((obt_get_themeoption("frame") == 'frame-sidebar-posts-yes') || (obt_get_themeoption("frame") == '')) :  ?>
		<style type="text/css">
			.post {
				border: 1px solid #d1d1d1;	
			}
		</style>
	<?php else : ?>
		<style type="text/css">
			.post {
				border:0;	
			}
		</style>
	<?php endif; ?>
	<?php 
	// Para poder cambiar los márgenes internos entre el texto y el recuadro
	if (obt_get_themeoption("margin-post") == '') :  ?>
		<style type="text/css">
			.post {
						 		border: 1px solid #d1d1d1;
								padding: 10px 15px;
						}
		</style>
	<?php else : ?>
		<style type="text/css">
			.post {
						 		border: 1px solid #d1d1d1;
								padding: 10px <?php echo obt_get_themeoption("margin-post"); ?>;
						}
		</style>
	<?php endif; ?>
				
<div id="maincont">
	
	<div id="cont">		

		<?php if (have_posts()) : ?>

		<?php $post = $posts[0]; ?>
	
		<?php if (is_category()) { ?>
			<div>
				<h2><?php echo single_cat_title(); ?></h2>
			</div>
		<?php } elseif (is_day()) { ?>
			<div>	
				<h2><?php _e('Archivos del d&iacute;a'); ?> <?php the_time('j F Y'); ?></h2>
			</div>
		<?php } elseif (is_month()) { ?>
			<div>	
				<h2><?php _e('Archivos del mes:'); ?> <?php the_time('F Y'); ?></h2>
			</div>
		<?php } elseif (is_year()) { ?>
			<div>	
				<h2><?php _e('Archivos del a–o:'); ?> <?php the_time('Y'); ?></h2>
			</div>
		<?php } elseif (is_author()) { ?>
			<div>	
				<h2><?php _e('Archivos del autor:'); ?> 
					<?php if(isset($_GET['author_name'])) :
    						// NOTE: 2.0 bug requires: get_userdatabylogin(get_the_author_login());
    						$curauth = get_userdatabylogin($author_name);
						else :
    						$curauth = get_userdata(intval($author));
						endif;
 				echo $curauth->nickname; ?></h2>
			</div>
		<?php } elseif (is_search()) { ?>
			<div>	
				<h2><?php _e('Resultados de la b&uacute;squeda'); ?></h2>
			</div>
		<?php } elseif (is_tag()) { ?>
			<div>	
				<h2><?php _e('Archivos con la etiqueta: '); ?> <?php echo single_tag_title(); ?></h2>
			</div>
		<?php }else { ?>
					<div>	
						<?php // Si se pone una cabecera personalizada, no debe aparecer el t’tulo del blog.
						if (obt_get_themeoption("image") == '' || obt_get_themeoption("image") == 'http://') :  ?>
								<h2><?php bloginfo('name'); ?></h2>
						<?php else : ?>
						<?php endif; ?>
			</div>

		
		<?php } ?>
		
		<?php while (have_posts()) : the_post(); ?>
		<?php
			foreach((get_the_category()) as $category) { 
				$this_category = $category->cat_ID; 
			} 
		?>

			<div class="post">
				<?php if ((obt_get_themeoption("autor") == 'si') ||  (obt_get_themeoption("fecha") == 'si')): ?>
					<p class="metaspost">
				<?php endif; ?>
				
				<?php if (obt_get_themeoption("autor") == 'si') : ?>
				Por <?php the_author_posts_link(); ?>. 
<?php	else : 
	endif; ?>
				
				<?php if (obt_get_themeoption("fecha") == 'si') : 
				 	echo the_time('l'); echo ', '; echo the_time('j'); echo ' de '; echo the_time('F'); echo ' de '; echo the_time('Y'); echo'.';
				else : 
					endif; ?>
				<?php if ((obt_get_themeoption("autor") == 'si') ||  (obt_get_themeoption("fecha") == 'si')): ?>
					</p>
				<?php endif; ?>

				<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
				<?php if (is_search()) { ?>
					<span class="destacado"><?php the_excerpt(); ?></span>
				<?php } else { ?>
					<?php the_content(__('Seguir leyendo &raquo;')); ?>
					<?php edit_post_link(__("| Editar |"), ''); ?>
					<br style="clear:both;"></br>
					
					<?php 
						$video = get_post_meta($post->ID, 'video', 'true');
						if( $video == '' ) 
						{
							;
						}
						else{
							?><p><a class="videolink" href="<?php echo $video;?>">Ver v’deo</a></p><?php
						}
						?>
					
				<?php } ?>
				</div>
				

				    <?php if ( function_exists('dynamic_sidebar') && dynamic_sidebar('Index - Debajo de cada post') ) : 
				    else :
				    ?>
					<?php endif; ?>

				
				<?php if (obt_get_themeoption("comentarios") == 'no') : 
	else : ?>
					<?php $comentarios = $wpdb->get_var("SELECT COUNT(*) FROM $wpdb->comments WHERE comment_post_ID = '$post->ID' AND comment_approved = '1' AND comment_type = ''"); ?>
					<?php
					if($comentarios == '0'){
						?>	<h3>No hay comentarios</h3>
							<ul class="post_com">
							<li class="comentarios">Comentarios (<?php echo $comentarios; ?>)</li>
						<?php
					}
					else{
						?>	<h3>Comentarios</h3>
							<ul class="post_com">
							<li class="comentarios"><a href="<?php the_permalink() ?>#comments" title="Comentarios en <?php the_title(); ?>">Comentarios (<?php echo $comentarios; ?>)</a></li>
						<?php
					}
					?>					
					
					</ul>
		<?php endif; ?>
		
			<br />
		<?php endwhile; ?>
	
		<?php 
				if (obt_get_themeoption("ant-sig") == '') : 
					posts_nav_link( ' &#183; ',  __('&laquo; Entradas anteriores'), __('Siguientes entradas &raquo;'), __('') );
				else:
				endif;
		?>
	<?php else : ?>
		<div class="post">
			<h2><?php _e('No encontrado'); ?></h2>
		
			<p><?php _e('Lo siento, ninguna entrada cumple tu criterio de b&uacute;squeda.'); ?></p>
		
			<h3><?php _e('Buscar'); ?></h3>
		
			<?php include (TEMPLATEPATH . '/searchform.php'); ?>
		</div>
	<?php endif; ?>
</div>

<?php get_sidebar(); ?>


</div>
				
				
<?php get_footer(); ?>