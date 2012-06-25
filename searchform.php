<form method="get" id="searchform" action="<?php bloginfo('home'); ?>/">
	<p><input name="s" value="<?php echo wp_specialchars($s, 1); ?>" size="25" class="text" type="text" style="width:70%;" />
	<input value="Buscar" class="button" type="submit" /></p>
</form>