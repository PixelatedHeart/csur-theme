<?php get_header(); ?>

<?php
	//Establecemos el ancho del contenido y del sidebar
	csur_width();
 
	// Para poder hacer cambios desde el panel del administrador del color del tÃ­tulo
	csur_color_title(); 
 
	// Para poder poner y quitar los recuadros de las imÃ¡genes
	 csur_images_frames();
	 
	// Para poder poner y quitar los recuadros del sidebar y de los posts
	csur_post_sidebar_frames();
	 
	// Para poder cambiar los márgenes internos entre el texto y el recuadro
	csur_internal_margins();
	?>
				
<div id="maincont">
	<?php 
		//Código para poder cambiar desde el panel si el sidebar va a derecha o izquierda
		csur_left_sidebar();
	 ?>
	<div id="cont<?php if (obt_get_themeoption("plantilla") == 'parrilla') : echo 'parrilla'; else : endif; ?>">		

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
				<h2><?php _e('Archivos del aÃ±o:'); ?> <?php the_time('Y'); ?></h2>
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
						<?php
							// Si se pone una cabecera personalizada, no debe aparecer el tÃ­tulo del blog.
							csur_custom_header();
						?>
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
				<!-- SHARE THIS -->		
				<div align="center"><span class='st_facebook' st_title='<?php the_title(); ?>' st_url='<?php the_permalink(); ?>' displayText='Facebook'></span><span class='st_twitter' st_title='<?php the_title(); ?>' st_url='<?php the_permalink(); ?>' displayText='Twitter'></span><span class='st_email' st_title='<?php the_title(); ?>' st_url='<?php the_permalink(); ?>' displayText='Correo electrónico'></span><span class='st_sharethis' st_title='<?php the_title(); ?>' st_url='<?php the_permalink(); ?>' displayText='Share This'></span><span class='st_fblike' st_title='<?php the_title(); ?>' st_url='<?php the_permalink(); ?>' displayText=''></span><span class='st_plusone' st_title='<?php the_title(); ?>' st_url='<?php the_permalink(); ?>' displayText=''></span></div>
				<!-- SHARE THIS -->
					<br style="clear:both;"></br>
					
					<?php 
						$video = get_post_meta($post->ID, 'video', 'true');
						if( $video == '' ) 
						{
							;
						}
						else{
							?><p><a class="videolink" href="<?php echo $video;?>">Ver vídeo</a></p><?php
						}
						?>
					
				<?php } ?>
				</div>
					<?php echo obt_get_themeoption('bajo_post'); ?>

				    <?php if ( function_exists('dynamic_sidebar') && dynamic_sidebar('Index - Debajo de cada post') ) : 
				    else :

				    ?>

					<?php endif; ?>

				
				<?php 	if (obt_get_themeoption("comentarios") == 'no') : 
						else :
					 		$comentarios = $wpdb->get_var("SELECT COUNT(*) FROM $wpdb->comments WHERE comment_post_ID = '$post->ID' AND comment_approved = '1' AND comment_type = ''");
					
							if($comentarios == '0'){
								?><h3>No hay comentarios</h3>
								<ul class="post_com">
									<li class="comentarios">Comentarios (<?php echo $comentarios; ?>)</li>
							<?php }
							else{ ?>	
								<h3>Comentarios</h3>
								<ul class="post_com">
									<li class="comentarios"><a href="<?php the_permalink() ?>#comments" title="Comentarios en <?php the_title(); ?>">Comentarios (<?php echo $comentarios; ?>)</a></li>
							<?php } ?>					
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

	<?php 
	//Código para poder cambiar desde el panel si el sidebar va a derecha o izquierda
	csur_right_sidebar();
	?>	
</div>
				
				
<?php get_footer(); ?>