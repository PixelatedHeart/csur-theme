<?php if (obt_get_themeoption("footer") == 'sin') : 
	else : ?>
				<div id="footer" class="clear">
				<?php	endif; ?>

	<?php if (obt_get_themeoption("footer") == 'sin') : 
	else : ?>
				<?php echo obt_get_themeoption('footer_left'); ?>

				 <?php if ( function_exists('dynamic_sidebar') && dynamic_sidebar('Footer - Columna izqda') ) : ?>
				  <?php  else : ?>
				   <?php  endif;
				    ?>
						<?php echo obt_get_themeoption('footer_center'); ?>

				 <?php if ( function_exists('dynamic_sidebar') && dynamic_sidebar('Footer - Columna central') ) : ?>
				  <?php  else : ?>
				   <?php  endif;
				    ?>
											<?php echo obt_get_themeoption('footer_right'); ?>

				 <?php if ( function_exists('dynamic_sidebar') && dynamic_sidebar('Footer - Columna dcha') ) : ?>
					  <?php  else : ?>
				   <?php  endif;
				    ?>
					

<?php endif; ?>


					<div class="creditos clear">
						<p>Radio y Televisión de Andalucía | <a href="http://www.canalsur.es/portal_rtva/impe/web/noticia?id=130433">Aviso legal</a> | Hecho por <a href="http://mecus.es" title="Mecus">Mecus</a> en <a href="http://wordpress.org" title="WordPress web site">WordPress</a>.</p>
					</div><!-- fin creditos -->
	<?php if (obt_get_themeoption("footer") == 'sin') : 
	else : ?>
				</div><!-- fin footer -->
	<?php endif; ?>		
			</div><!-- fin maincont -->
		</div><!-- fin wrapper -->
<!-- START Nielsen Online SiteCensus V6.0 -->
<!-- END Nielsen Online SiteCensus V6.0 -->
		
		<!- Google Analytics ->
		<!- End analytics ->
	</body>
</html>
