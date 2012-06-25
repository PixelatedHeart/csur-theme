<?php
//Sidebar

?>
		
<?php 	// Para poder hacer cambios desde el panel del administrador del color del título
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
			h2{
				color: #4d729a;
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
			h2 {
				color: <?php echo obt_get_themeoption("title-color"); ?>;
			}
			</style>
<?php endif; ?>
<?php 	// Para cambiar el ancho de la subcolumna izquierda del sidebar
		if (obt_get_themeoption("width-col1") == '') :  ?>
			<style type="text/css">
			.col1 {
						width: 47%;
						}
			</style>
<?php else : ?>
			<style type="text/css">
			.col1 {
						width: <?php echo obt_get_themeoption("width-col1"); ?>;
						}
			</style>
<?php endif; ?>
<?php 	// Para cambiar el ancho de la subcolumna derecha del sidebar
		if (obt_get_themeoption("width-col2") == '') :  ?>
			<style type="text/css">
			.col2 {
						width: 47%;
						}
			</style>
<?php else : ?>
			<style type="text/css">
			.col2 {
						width: <?php echo obt_get_themeoption("width-col2"); ?>;
						}
			</style>
<?php endif; ?>
		
				<div id="sidebar">
					<?php echo obt_get_themeoption('sidebar_sup'); ?>
				 <?php if ( function_exists('dynamic_sidebar') && dynamic_sidebar('Sidebar - Parte superior') ) : ?>

				  <?php  else : ?>
				<h3>Buscar</h3>
					<?php include (TEMPLATEPATH . '/searchform.php'); ?>
					<h3>P&aacute;ginas</h3>
					<ul>
						<li><a href="<?php bloginfo('home'); ?>/">Inicio</a></li>
					</ul>

					<?php  endif;
				    ?>

					<div class="col1">
				    					<?php echo obt_get_themeoption('sidebar_left'); ?>
					 <?php if ( function_exists('dynamic_sidebar') && dynamic_sidebar('Sidebar - Doble columna - Izq') ) : ?>
				  <?php  else : ?>
				  					<h3>Todos los blogs</h3>
						<ul>
							<li><a href="http://blogs.canalsur.es/elpublicolee/">El P&uacute;blico lee</a></li>
							<li><a href="http://blogs.canalsur.es/elclubdelasideas/">El Club de las Ideas</a></li>
							<li><a href="http://blogs.canalsur.es/esposible/">Es Posible</a></li>
							<li><a href="http://blogs.canalsur.es/viajealsur/">Viaje al Sur</a></li>
							<li><a href="http://blogs.canalsur.es/losreporteros/">Los Reporteros</a></li>
							<li><a href="http://blogs.canalsur.es/lamemoria/">La Memoria</a></li>
							<li><a href="http://blogs.canalsur.es/tesis/">Tesis</a></li>
							<li><a href="http://blogs.canalsur.es/testigoshoy/">Testigos Hoy</a></li>
						</ul>

				  <?php  endif;
				    ?>

					</div><!-- fin col1 -->
					<div class="col2">
						<?php echo obt_get_themeoption('sidebar_right'); ?>
		 <?php if ( function_exists('dynamic_sidebar') && dynamic_sidebar('Sidebar - Doble columna - Dcha') ) : ?>
				  <?php  else : ?>
				  						<h3>Todos los blogs</h3>
						<ul>
							<li><a href="http://blogs.canalsur.es/parrilla_cstv/">Canal Sur Televisi&oacute;n</a></li>
							<li><a href="http://blogs.canalsur.es/parrilla_cs2/">Canal Sur 2</a></li>
							<li><a href="http://blogs.canalsur.es/parrilla_at/">Andaluc&iacute;a Televisi&oacute;n</a></li>
							<li><a href="http://blogs.canalsur.es/parrilla_csr/">Canal Sur Radio</a></li>
							<li><a href="http://blogs.canalsur.es/parrilla_cfr/">Canal Fiesta Radio</a></li>
							<li><a href="http://blogs.canalsur.es/parrilla_ra">Radio Andaluc&iacute;a Informaci&oacute;n</a></li>
							<li><a href="http://blogs.canalsur.es/parrilla_cfl/">Canal Flamenco Radio</a></li>
						</ul>

				   <?php  endif;
				    ?>

					</div><!-- fin col2 -->
						<?php echo obt_get_themeoption('sidebar_down'); ?>

				 <?php if ( function_exists('dynamic_sidebar') && dynamic_sidebar('Sidebar - Parte inferior') ) : ?>
				  <?php  else : ?>
				  				<!--	<p class="clear"><a href="http://blogs.canalsur.es/notasdeprensa/"><strong>Notas de prensa</strong></a></p>
					<p><a href="http://blogs.canalsur.es/defensorrtva/"><strong>Defensor de la Audiencia de la Radio y Televisi&oacute;n de Andaluc&iacute;a</strong></a></p>-->

 				  <?php  endif;
				    ?>
				</div><!-- fin sidebar -->

<?php
// End sidebar
?>
