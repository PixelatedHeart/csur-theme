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
				<?php if (have_posts()) : the_post();
							$category = get_the_category(); 
							$this_category = $category->cat_ID;
					?>
					<div class="post">
							<?php  
							//Función para abrir el div metaspost que engloba el autor y la fecha
							csur_open_div_metaspost();
							
							//Función para mostrar el autor del post o no
							csur_show_author();
							
							//Función para mostrar la fecha del post o no
							csur_show_date();
							
							//Función para cerrar el div que engloba al autor y la fecha
							csur_close_div_metaspost();
				 			?>
				
						<h2><?php the_title(); ?></h2>
						<p>
							<?php the_content(); ?>
							<br style="clear:both;"></br>
						
							<?php edit_post_link(__("| Editar |"), ''); ?>
						</p>
						<?php 
						$video = get_post_meta($post->ID, 'video', 'true');
						if( $video == '' ) 
						{
							;
						}
						else{
							?><p><a class="videolink" href="<?php echo $video;?>">Ver v&iacute;deo</a></p><?php
						}
						?>
				<!-- SHARE THIS -->		
				<div align="center"><span class='st_facebook' st_title='<?php the_title(); ?>' st_url='<?php the_permalink(); ?>' displayText='Facebook'></span><span class='st_twitter' st_title='<?php the_title(); ?>' st_url='<?php the_permalink(); ?>' displayText='Twitter'></span><span class='st_email' st_title='<?php the_title(); ?>' st_url='<?php the_permalink(); ?>' displayText='Correo electrónico'></span><span class='st_sharethis' st_title='<?php the_title(); ?>' st_url='<?php the_permalink(); ?>' displayText='Share This'></span><span class='st_fblike' st_title='<?php the_title(); ?>' st_url='<?php the_permalink(); ?>' displayText=''></span><span class='st_plusone' st_title='<?php the_title(); ?>' st_url='<?php the_permalink(); ?>' displayText=''></span></div>
				<!-- SHARE THIS -->

					</div><!-- fin post -->
					<?php 	
						//Función para mostrar los enlaces a post anterior y siguiente
						csur_sig_ant_post_enlace();
					
						//Función para Post - Debajo de la entrada
						csur_post_debajo_entrada();
				    
				     	//Funcion para mostrar los comentarios o no
				     	if (obt_get_themeoption("comentarios") == 'no') : 
						else :
//							include(TEMPLATEPATH."/post-share.php");			
							include (TEMPLATEPATH . '/comments.php');
						endif;
	
						//Función para mostrar el enlace a Suscríbete
						csur_show_subscribe();
	
	 					//Función para mostrar los trackbacks o no
	 					csur_show_trackbacks();
	 				?>
									

<?php endif; ?>
</div><!-- fin cont -->
				
<?php 
	//Código para poder cambiar desde el panel si el sidebar va a derecha o izquierda
	csur_right_sidebar();
?>		
</div>
			
<?php get_footer(); ?>