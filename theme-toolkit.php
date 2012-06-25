<?php
/*
Wordpress Theme Toolkit MU 1.12.mu1
Modified for MU by Jason Ling http://jason.lah.cc/ 

Modifications made by Javier Garc�a to fit the theme. Download the original file!

08/05/2006 - Initial public release
*/

/*
Hack Name: Wordpress Theme Toolkit
Plugin URI: http://frenchfragfactory.net/ozh/my-projects/wordpress-theme-toolkit-admin-menu/
Description: Helps theme authors set up an admin menu. Helps theme users customise the theme.
Version: 1.12
Author: Ozh
Author URI: http://planetOzh.com/
*/

/************************************************************************************
*							 DO NOT MODIFY THIS FILE !
************************************************************************************/

/* RELEASE HISTORY :
* 1.0 : initial release
* 1.1 : update for Wordpress 2.0 compatibility
* 1.11 : added {separator} template
* 1.12 : more or less minor bug fixing (one when no plugin activated, other with rare mod_security issue) and better compliancy with WP 2.0 roles
*/

if (!function_exists('themetoolkit')) {
	function themetoolkit($theme='',$array='',$file='') {
		global ${$theme};
		if ($theme == '' or $array == '' or $file == '') {
			die ('No theme name, theme option, or parent defined in Theme Toolkit');
		}
		${$theme} = new ThemeToolkit($theme,$array,$file);
	}
}

if (!class_exists('ThemeToolkit')) {
	class ThemeToolkit{

		var $option, $infos;

		function ThemeToolkit($theme,$array,$file){
			
			// is it WP 2.0+ and do we have plugins like "../themes/foo/functions.php" running ?
			if ( count(@preg_grep('#^\.\./themes/[^/]+/functions.php$#', get_settings('active_plugins'))) > 0 ) {
				wp_cache_flush();
				$this->upgrade_toolkit();
			}
			
			$this->infos['path'] = '../themes/' . basename(dirname($file));

			/* Create some vars needed if an admin menu is to be printed */
			if ($array['debug']) {
				if ((basename($file)) == $_GET['page']) $this->infos['debug'] = 1;
				unset($array['debug']);
			}
			if ((basename($file)) == $_GET['page']){
				$this->infos['menu_options'] = $array;
				$this->infos['classname'] = $theme;
			}
			$this->option=array();

			/* Get infos about the theme and particularly its 'shortname'
			 * which is used to name the entry in wp_options where data are stored */
			$this->do_init();

			/* Read data from options table */
			$this->read_options();

			/* Are we in the admin area ? Add a menu then ! */
			$this->file = $file;
			add_action('admin_menu', array(&$this, 'add_menu'));
		}


		/* Add an entry to the admin menu area */
		function add_menu() {
			add_submenu_page('themes.php', obt_translate('Configure %1',$this->infos['theme_name']), obt_translate('Configure %1',$this->infos['theme_name']), 2, 'theme-options.php', array(&$this,'admin_menu'));
			//add_theme_page(obt_translate('Configurar %1 (ADMIN)',$this->infos['theme_name']), obt_translate('Configurar %1 (ADMIN)',$this->infos['theme_name']), 'edit_themes', basename($this->file), array(&$this,'admin_menu'));
			/* Thank you MCincubus for opening my eyes on the last parameter :) */
		}

		/* Get infos about this theme */
		function do_init() {
			$themes = get_themes();
			$shouldbe= basename($this->infos['path']);
			foreach ($themes as $theme) {
				$current= basename($theme['Template Dir']);
				if ($current == $shouldbe) {
					if (get_settings('template') == $current) {
						$this->infos['active'] = TRUE;
					} else {
						$this->infos['active'] = FALSE;
					}
				$this->infos['theme_name'] = $theme['Name'];
				$this->infos['theme_shortname'] = $current;
				$this->infos['theme_site'] = $theme['Title'];
				$this->infos['theme_version'] = $theme['Version'];
				$this->infos['theme_author'] = preg_replace("#>\s*([^<]*)</a>#", ">\\1</a>", $theme['Author']);
				}
			}
		}

		/* Read theme options as defined by user and populate the array $this->option */
		function read_options() {
			$options = get_option('theme-'.$this->infos['theme_shortname'].'-options');
			$options['_________junk-entry________'] = 'ozh is my god';
			foreach ($options as $key=>$val) {
				$this->option["$key"] = stripslashes($val);
			}
			array_pop($this->option);
			return $this->option;
			/* Curious about this "junk-entry" ? :) A few explanations then.
			 * The problem is that get_option always return an array, even if
			 * no settings has been previously saved in table wp_options. This
			 * junk entry is here to populate the array with at least one value,
			 * removed afterwards, so that the foreach loop doesn't go moo. */
		}

		/* Write theme options as defined by user in database */
		function store_options($array) {
			update_option('theme-'.$this->infos['theme_shortname'].'-options','');
			if (update_option('theme-'.$this->infos['theme_shortname'].'-options',$array)) {
				return obt_translate('Options successfully stored');
			} else {
				return obt_translate('Could not save options!');
			}
		}

		/* Delete options from database */
		  function delete_options() {
			/* Remove entry from database */
			delete_option('theme-'.$this->infos['theme_shortname'].'-options');
			/* Revert theme back to Kubrick if this theme was activated */
			if ($this->infos['active']) {
				update_option('template', 'default');
				update_option('stylesheet', 'default');
				do_action('switch_theme', 'Default');
			}
			/* Go back to Theme admin */
			print '<meta http-equiv="refresh" content="0;URL=themes.php?activated=true">';
			echo "<script> self.location(\"themes.php?activated=true\");</script>";
			exit;
		}

		/* Check if the theme has been loaded at least once (so that this file has been registered as a plugin) */
		function is_installed() {
			global $wpdb;
			$where = 'theme-'.$this->infos['theme_shortname'].'-options';
			$check = $wpdb->get_var("SELECT COUNT(*) FROM $wpdb->options WHERE option_name = '$where'");
			if ($check == 0) {
				return FALSE;
			} else {
				return TRUE;
			}
		}

		/* Theme used for the first time (create blank entry in database) */
		function do_firstinit() {
			global $wpdb;
			$options = array();
			foreach(array_keys($this->option) as $key) {
				$options["$key"]='';
			}
			add_option('theme-'.$this->infos['theme_shortname'].'-options',$options, 'Options for theme '.$this->infos['theme_name']);
			return obt_translate('Theme options added in database (1 entry in table %1)',$wpdb->options);
		}

		/* The mother of them all : the Admin Menu printing func */
		function admin_menu () {
			global $cache_settings, $wpdb;

			/* Process things when things are to be processed */
			if (@$_POST['action'] == 'store_option') {
				unset($_POST['action']);
				$msg = $this->store_options($_POST);
			} elseif (@$_POST['action'] == 'delete_options') {
				$this->delete_options();
			} elseif (!$this->is_installed()) {
				$msg = $this->do_firstinit();
			}

			if (@$msg) print "<div class='updated'><p><b>" . $msg . "</b></p></div>\n";

      echo ('<div class="wrap">');
			echo '<div class="wrap"><h2>'.obt_translate('Thank you!').'</h2>';
			echo '<p>'.obt_translate('Thank you for installing %1, a theme for Wordpress. This theme was made by %2',$this->infos['theme_site'],$this->infos['theme_author']).'. </p>';

			if (!$this->infos['active']) { /* theme is not active */
				echo '<p>('.obt_translate('Please note that this theme is currently not activated on your site as the default theme').'.)</p>';
			}

			$cache_settings = '';
			$check = $this->read_options();
			
			echo '<h2>'.obt_translate('Configure %1',$this->infos['theme_name']).'</h2>';
			echo '<p>'.obt_translate('This theme allows you to configure some variables to suit your blog').':</p>
			<form action="" method="post">
			<p class="submit"><input type="submit" value="'.obt_translate('Store options').'" /></p>
			<input type="hidden" name="action" value="store_option">
			<table cellspacing="2" cellpadding="5" border="0" width=100% class="editform">';

			/* Print form, here comes the fun part :) */
			$cont = 0;
			foreach ($this->infos['menu_options'] as $key=>$val) {
				$items='';
				$matches = explode("##",$val);
				$matches = array_map("trim",$matches);
				if ($matches[1]) {
					$items = split("\|", $matches[1]);
				}
				$cont++;
/********** HACK *************/
			global $current_user;
			get_currentuserinfo();
			if (($current_user->ID == 1) || ($current_user->ID == 4) || ($current_user->ID == 65)) {
				print "<tr valign='top'><th scope='row' width='33%'>\n";
				} elseif (($cont <= 47)) {
					print "<tr valign='top' style='visibility:collapse;'><th scope='row' width='33%'>\n";
				} else {
					print "<tr valign='top'><th scope='row' width='33%'>\n";
				}
/*********** HACK ***********/

				if (@$items) {
					$type = array_shift($items);
					switch ($type) {
					case 'separator':
						print '<h3>'.$matches[0]."</h3></th>\n<td>&nbsp;</td>";
						break;
					case 'radio':
						print $matches[0]."</th>\n<td>";
						while ($items) {
							$v=array_shift($items);
							$t=array_shift($items);
							$checked='';
							if ($v == $this->option[$key]) $checked='checked';
							print "<label for='${key}${v}'><input type='radio' id='${key}${v}' name='$key' value='$v' $checked /> $t</label>";
							if (@$items) print "<br />\n";
						}
						break;
					case 'textarea':
						$rows=array_shift($items);
						$cols=array_shift($items);
					print "<label for='$key'>".$matches[0]."</label></th>\n<td>";
						print "<textarea name='$key' id='$key' rows='$rows' cols='$cols'>" . $this->option[$key] . "</textarea>";
						break;
					case 'checkbox':
						print $matches[0]."</th>\n<td>";
						while ($items) {
							$k=array_shift($items);
							$v=array_shift($items);
							$t=array_shift($items);
							$checked='';
							if ($v == $this->option[$k]) $checked='checked';
							print "<label for='${k}${v}'><input type='checkbox' id='${k}${v}' name='$k' value='$v' $checked /> $t</label>";
							if (@$items) print "<br />\n";
						}
						break;
					}
				} else {
					print "<label for='$key'>".$matches[0]."</label></th>\n<td>";
					print "<input type='text' name='$key' id='$key' value='" . $this->option[$key] . "' />";
				}

				if ($matches[2]) print '<br/>'. $matches[2];
				print "</td></tr>\n";
			}
			echo '</table>
			<p class="submit"><input type="submit" value="'.obt_translate('Store options').'" /></p>
			</form>';

			if ($this->infos['debug'] and $this->option) {
				$g = '<span style="color:#006600">';
				$b = '<span style="color:#0000CC">';
				$o = '<span style="color:#FF9900">';
				$r = '<span style="color:#CC0000">';
				echo '<h2>'.obt_translate('Programmer\'s corner').'</h2>';
				echo '<p>'.obt_translate('The array %1 is actually populated with the following keys and values','<em>$'. $this->infos['classname'] . '->option</em>').':</p>
				<p><pre class="updated">';
				$count = 0;
				foreach ($this->option as $key=>$val) {
					$val=str_replace('<','&lt;',$val);
					if ($val) {
						print '<span class="ttkline">'.$g.'$'.$this->infos['classname'].'</span>'.$b.'-></span>'.$g.'option</span>'.$b.'[</span>'.$g.'\'</span>'.$r.$key.'</span>'.$g.'\'</span>'.$b.']</span>'.$g.' = "</span>'. $o.$val.'</span>'.$g."\"</span></span>\n";
						$count++;
					}
				}
				if (!$count) print "\n\n";
				echo '</pre><p>To disable this report (for example before packaging your theme and making it available for download), remove the line "&nbsp;<em>\'debug\' => \'debug\'</em>&nbsp;" in the array you edited at the beginning of this file.</p>';
			}

			echo '<h2>'.obt_translate('Delete theme options').'</h2>
			<p>'.obt_translate('To completely remove these theme options from your database (reminder: they are all stored in a single entry, in Wordpress options table %1), click on the following button. You will be then redirected to','<em>'.$wpdb->options.'</em>').' <a href="themes.php">'.obt_translate('the themes admin interface').'</a>';
			if ($this->infos['active']) {
				echo ' '.obt_translate('and the default theme will have been activated');
			}
			echo '.
			<p><strong>'.obt_translate('Special notice for people allowing their readers to change theme').'</strong> ('.obt_translate('i.e. using a Theme Switcher on their blog').')<br/>
			'.obt_translate('Unless you really remove the theme files from your server, this theme will still be available to users, and therefore will self-install again as soon as someone selects it. Also, all custom variables as defined in the above menu will be blank, this could lead to unexpected behaviour. Press "Delete" only if you intend to remove the theme files right after this').'.</p>
			<form action="" method="post">
			<input type="hidden" name="action" value="delete_options">
			<p class="submit"><input type="submit" value="'.obt_translate('Delete options').'" onclick="return confirm(\''.obt_translate('Are you really sure you want to delete?').'\');"/></p>
			</form>';
			
			ob_start(array(&$this,'footercut'));

			echo '<h2>'.obt_translate('Credits').'</h2>';
			echo '<p>'.obt_translate('%1 has been created by %2',$this->infos['theme_site'],$this->infos['theme_author']).'. ';
			echo obt_translate('This administration menu uses %1 by %2. And everything was made possible thanks to %3','<a href="http://frenchfragfactory.net/ozh/my-projects/wordpress-theme-toolkit-admin-menu/" title="Wordpress Theme Toolkit : create a admin menu for your own theme as easily as editing 3 lines">Wordpress Theme Toolkit</a>','<a href="http://frenchfragfactory.net/ozh/" title="planetOzh">Ozh</a>','<a href="http://wordpress.org/" title="Best. Blogware. Ever.">Wordpress</a>').'.</p>
			</div>
			<div class="footer"><div style="float:right;margin-left:-180px;padding-right:5%"><p>'.obt_translate('%1 by %2 for %3','<a href="http://frenchfragfactory.net/ozh/my-projects/wordpress-theme-toolkit-admin-menu/">Theme Toolkit</a>','<br /><a href="http://planetOzh.com/"><img src="http://frenchfragfactory.net/ozh/wp-images/btn_planetozh.png" border="0" alt="planetOzh.com" /></a><br />',$this->infos['theme_site']).'</p></div>';
			echo '</div><!-- footercut -->';
		}

		/* Make this footer part of the real #footer DIV of Wordpress admin area */
		function footercut($string) {
			return preg_replace('#</div><!-- footercut -->.*<div id="footer">#m', '', $string);
		}

		/***************************************
		 * Really, the whole plugin management
		 * system is really neat in WP, and very
		 * easy to use.
		 **************************************/

		/* Clean plugins lists in order to work with Wordpress 2.0 */
		function upgrade_toolkit () {
			$plugins=get_settings('active_plugins');
			$delete=@preg_grep('#^\.\./themes/[^/]+/functions.php$#', $plugins);
			$result=array_diff($plugins,$delete);
			$temp = array();
			foreach($result as $item) $temp[]=$item;
			$result = $temp;
			update_option('active_plugins',$result);
			wp_cache_flush;
		}

	}
}

?>
