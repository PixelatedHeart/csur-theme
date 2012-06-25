<?php
require_once(dirname(__FILE__)."/theme-translation.php");
require_once(dirname(__FILE__)."/theme-functions.php");
require_once(dirname(__FILE__)."/theme-toolkit.php");


IF (function_exists("themetoolkit")){

	$theme_options["plantilla"] = obt_translate("Página completa")." ## radio|parrilla| ".obt_translate("Una columna a página completa (no tiene sidebar y el ancho ocupa toda la pantalla)")."|blog|".obt_translate("Blog (tiene sidebar lateral derecho)");
	$theme_options["aa"] = " ## separator";
	$theme_options["menu-display"] = obt_translate("Menú")." ## radio|menu_yes|".obt_translate("Muestra el menú")."|menu-no|".obt_translate("No muestra el menú");
	$theme_options["aaaa"] = " ## separator";
	$theme_options["sidebar-position"] = obt_translate("Posición de la barra lateral")." ## radio|sidebar_left|".obt_translate("Barra lateral a la izquierda")."|sidebar-right|".obt_translate("Barra lateral a la derecha");
	$theme_options["aaa"] = " ## separator";
	$theme_options["frame"] = obt_translate("Recuadro de barra lateral y posts")." ## radio|frame-sidebar-posts-yes|".obt_translate("Mostrar el recuadro de la barra lateral y de los posts")."|frame-sidebar-posts-no|".obt_translate("No mostrar el recuadro de la barra lateral ni el de los posts");
	$theme_options["ant-sig"] = obt_translate("Página anterior - Página siguiente")." ## ## ".obt_translate("Especifica si aparecen los enlaces de página anterior y página siguiente debajo de los posts en la página de inicio. Por defecto vacío - aparece el enlace.");
	$theme_options["aaaa"] = " ## separator";
	$theme_options["image-separator"] = obt_translate("Opciones de imágenes")." ## separator";
	$theme_options["image"] = obt_translate("Header image")." ## ## ".obt_translate("Specify the full url of the image you want to be displayed in the header");
	$theme_options["bb"] = " ## separator";
	$theme_options["image-frame"] = obt_translate("Recuadro de imagen")." ## radio|frame-yes|".obt_translate("Mostrar el recuadro de las imágenes en los posts")."|frame-no|".obt_translate("No mostrar el recuadro de las imágenes en los posts");
	$theme_options["bbb"] = " ## separator";
	$theme_options["width-separator"] = obt_translate("Ancho de columna")." ## separator";
	$theme_options["width-cont"] = obt_translate("Contenido")." ## ## ".obt_translate("Especifica el ancho de la columna de contenido (Por defecto: 630px ó 64%)");
	$theme_options["width-sidebar"] = obt_translate("Barra lateral")." ## ## ".obt_translate("Especifica el ancho de la barra lateral (Por defecto: 350px ó 34%)");
	$theme_options["width-col1"] = obt_translate("Subcolumna del sidebar iquierda")." ## ## ".obt_translate("Especifica el ancho de la bubcolumna del sidebar izquierda (Por defecto: 47%)");
	$theme_options["width-col2"] = obt_translate("Subcolumna del sidebar derecha")." ## ## ".obt_translate("Especifica el ancho de la subcolumna del sidebar derecha (Por defecto: 47%)");
	$theme_options["zz"] = " ## separator";
	$theme_options["margin-post-separator"] = obt_translate("Margenes internos")." ## separator";
	$theme_options["margin-post"] = obt_translate("Margen interno recuadro post")." ## ## ".obt_translate("Especifica el margen interno entre el texto y el recuadro del post (Por defecto: 15px ó 1%)");
	$theme_options["margin-sidebar"] = obt_translate("Margen interno sidebar")." ## ## ".obt_translate("Especifica el margen interno entre el texto y el recuadro del sidebar (Por defecto: 15px ó 1%)");
	$theme_options["bb"] = " ## separator";
	$theme_options["background-separator"] = obt_translate("Fondo de pantalla")." ## separator";
	$theme_options["background-color"] = obt_translate("Color de fondo")." ## ## ".obt_translate("Specify the background color");
	$theme_options["background-image"] = obt_translate("Imagen de fondo")." ## ## ".obt_translate("Specify the background image");
	$theme_options["dd"] = " ## separator";
	$theme_options["dd"] = " ## separator";
	$theme_options["font-separator"] = obt_translate("Fuentes")." ## separator";
	$theme_options["title-color"] = obt_translate("Color del título")." ## ## ".obt_translate("Especifica el color del título");
	$theme_options["font-color"] = obt_translate("Color de la fuente")." ## ## ".obt_translate("Especifica el color de la fuente");
	$theme_options["ee"] = " ## separator";
	$theme_options["ee"] = " ## separator";
	$theme_options["color-link-separator"] = obt_translate("Enlaces")." ## separator";
	$theme_options["colorlink"] = obt_translate("Color de los enlaces")." ## ## ".obt_translate("Specify the link color");
	$theme_options["colorlink-hover"] = obt_translate("Color de los enlaces al pasar por encima")." ## ## ".obt_translate("Specify the hover link color");
	$theme_options[""] = " ## separator";
	$theme_options["footer"] = obt_translate("Selección del footer. Se organizará dependiendo de las opciones (por defecto, no hay footer)")." ## radio|sin|".obt_translate("Sin footer")."|uno|".obt_translate("Una entrada (ocupará el espacio central de tres)")."|dos|".obt_translate("Dos entradas (se repartirán el espacio inferior al 50%)")."|tres|".obt_translate("Tres entradas (se repartirán el espacio inferior al 30%)");
	$theme_options["-"] = " ## separator";
	$theme_options["-"] = " ## separator";
	$theme_options["comentarios"] = obt_translate("Esto nos dirá si aparecen comentarios en el single de cada post (por defecto aparecen)")." ## radio|no|".obt_translate("Sin comentarios")."|si|".obt_translate("Con comentarios");
	$theme_options["eee"] = " ## separator";
	$theme_options["eee"] = " ## separator";
	$theme_options["suscribete"] = obt_translate("Esto nos dirá si aparecen los enlaces de suscripción en el single de cada post (por defecto aparecen)")." ## radio|no|".obt_translate("Sin suscripción")."|si|".obt_translate("Con suscripción");
	$theme_options["ddd"] = " ## separator";
	$theme_options["ddd"] = " ## separator";
	$theme_options["trackbacks"] = obt_translate("Esto nos dirá si aparecen los enlaces de trackbacks en el single de cada post (por defecto aparecen)")." ## radio|no|".obt_translate("Sin trackbacks")."|si|".obt_translate("Con trackbacks");
	$theme_options["dddd"] = " ## separator";
	$theme_options["dddd"] = " ## separator";
	$theme_options["fecha"] = obt_translate("Esto nos dirá si aparece la fecha en el index de cada blog (por defecto aparecen)")." ## radio|no|".obt_translate("Sin fecha")."|si|".obt_translate("Con fecha");
	$theme_options["+"] = " ## separator";
	$theme_options["+"] = " ## separator";
	$theme_options["autor"] = obt_translate("Aparecerá el autor al principio de cada post (por defecto aparece)")." ## radio|no|".obt_translate("Sin autor")."|si|".obt_translate("Con autor");
	$theme_options["++"] = " ## separator";
	$theme_options["analytics"] = obt_translate("Google Analytics")." ## ## ".obt_translate("Especifica el código de la cuenta de google analytics (Por ej.: UA-XXXXXXX-X )");

	$theme_options["ddddaf"] = " ## separator";
	$theme_options["ddddpo"] = " ## separator";
	$theme_options["author_config"] = obt_translate("Configuración de autor")." ## separator";
	$theme_options["sobre_cabecera"] = obt_translate("Sobre Cabecera")." ## textarea|8|70 ##".obt_translate("Aparece sobre la cabecera");
	$theme_options["aabac"] = " ## separator";	
	$theme_options["bajo_post"] = obt_translate("Bajo el post")." ## textarea|8|70 ##".obt_translate("Aparece bajo el post en el index");
	$theme_options["aabacdd"] = " ## separator";	
	$theme_options["sidebar_sup"] = obt_translate("Sidebar superior")." ## textarea|8|70 ##".obt_translate("Aparece sobre los widgets del sidebar superior");
	$theme_options["aabacdds"] = " ## separator";	
	$theme_options["sidebar_left"] = obt_translate("Sidebar izquierda")." ## textarea|8|70 ##".obt_translate("Aparece sobre los widgets del sidebar izquierdo");
	$theme_options["aabacdaa"] = " ## separator";	
	$theme_options["sidebar_right"] = obt_translate("Sidebar derecha")." ## textarea|8|70 ##".obt_translate("Aparece sobre los widgets del sidebar derecho");
	$theme_options["aabacdccc"] = " ## separator";	
	$theme_options["sidebar_down"] = obt_translate("Sidebar inferior")." ## textarea|8|70 ##".obt_translate("Aparece sobre los widgets del sidebar inferior");
	$theme_options["aabacdzzzz"] = " ## separator";	
	$theme_options["footer_left"] = obt_translate("Footer izquierdo")." ## textarea|8|70 ##".obt_translate("Aparece sobre los widgets del sidebar inferior");
	$theme_options["aabacdzzzzas"] = " ## separator";	
	$theme_options["footer_center"] = obt_translate("Footer central")." ## textarea|8|70 ##".obt_translate("Aparece sobre los widgets del sidebar inferior");
	$theme_options["aabacdzzzzfdsa"] = " ## separator";	
	$theme_options["footer_right"] = obt_translate("Footer derecho")." ## textarea|8|70 ##".obt_translate("Aparece sobre los widgets del sidebar inferior");
	$theme_options["aabacdzzzzgasd"] = " ## separator";	
	
	
		
	themetoolkit("obt_theme",$theme_options,__FILE__);
};



FUNCTION obt_get_themeoption($name){
	global $obt_theme;
	RETURN trim($obt_theme->option[$name]);
};
FUNCTION obt_themeoption($name){
	echo obt_get_themeoption($name);
};
?>
