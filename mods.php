<?php

//////////////////////////////////////////////////////////////
//===========================================================
// mods.php(For individual softwares)
//===========================================================
// SOFTACULOUS 
// Version : 1.0
// Inspired by the DESIRE to be the BEST OF ALL
// ----------------------------------------------------------
// Started by: Alons
// Date:       10th Jan 2009
// Time:       21:00 hrs
// Site:       http://www.softaculous.com/ (SOFTACULOUS)
// ----------------------------------------------------------
// Please Read the Terms of use at http://www.softaculous.com
// ----------------------------------------------------------
//===========================================================
// (c)Softaculous Inc.
//===========================================================
//////////////////////////////////////////////////////////////

if(!defined('SOFTACULOUS')){

	die('Hacking Attempt');

}


/**
 * This function will allow you to modify the XML that is being passed.
 * You can modify it here and Softaculous will parse it as it parses the XML of install.xml of packages.
 *
 * @package      softaculous
 * @subpackage   scripts
 * @author       Pulkit Gupta
 * @param        string $str The key of the Language string array.
 * @return       string The parsed string if there was a equivalent language key otherwise the key itself if no key was defined.
 * @since     	 1.0
 */
/*function __wp_mod_install_xml($xml){
	
	global $__settings, $settings, $error, $software, $globals, $softpanel, $notes, $adv_software;
	
}*/

/**
 * This function will parse your mod_install.xml and shows an option of choose plugin to users 
 *
 * @package      softaculous
 * @subpackage   scripts
 * @author       Pulkit Gupta
 * @param        string $str The key of the Language string array.
 * @return       string The parsed string if there was a equivalent language key otherwise the key itself if no key was defined.
 * @since     	 1.0
 */
function __wp_mod_settings(){
	
	global $__settings, $settings, $error, $software, $globals, $softpanel, $notes, $adv_software;
	
	$install = @implode(file($globals['path'].'/conf/mods/'.$software['softname'].'/mod_install.xml'));
	
	$install = parselanguages($install);
	
	$tmp_settings = array();

	file_put_contents('/tmp/sidekick.log',"test111\n");
	
	if(preg_match('/<softinstall (.*?)>(.*?)<\/softinstall>/is', $install)){
		
		$tmp_settings = load_settings($install, $adv_software, 1);
	}
	
	$key = parselanguages('{{select_plugins}}');
	
	$settings[$key] += $tmp_settings[$key];
	
}

/**
 * If anything is needed to be execute before the installation procedure starts than it should be done here.
 *
 * @package      softaculous
 * @subpackage   scripts
 * @author       Pulkit Gupta
 * @param        string $str The key of the Language string array.
 * @return       string The parsed string if there was a equivalent language key otherwise the key itself if no key was defined.
 * @since     	 1.0
 */
function __pre_mod_install(){
	
	global $__settings, $settings, $error, $software, $globals, $softpanel, $notes, $adv_software;
	
}

/**
 * If anything is needed to be execute after the installation procedure starts than it should be done here.
 *
 * @package      softaculous
 * @subpackage   scripts
 * @author       Pulkit Gupta
 * @param        string $str The key of the Language string array.
 * @return       string The parsed string if there was a equivalent language key otherwise the key itself if no key was defined.
 * @since     	 1.0
 */
function __post_mod_install(){
	
	global $__settings, $settings, $error, $software, $globals, $softpanel, $notes, $adv_software;
	
	$__settings['active_plugins'] = array();

	file_put_contents('/tmp/sidekick.log',"test0\n");
	
	// First get the active plugin list 
	$query = "SELECT option_value FROM ".$__settings['dbprefix']."options WHERE option_name = 'active_plugins';";
	$result = sdb_query($query, $__settings['softdbhost'], $__settings['softdbuser'], $__settings['softdbpass'], $__settings['softdb']);
	
	$__settings['active_plugins'] = _unserialize($result[0]['option_value']);

	// Check which plugin is checked for installation
	if(!empty($__settings['sidekick'])){

		$url = 'https://downloads.wordpress.org/plugin/sidekick.zip';
		$plugin_file = '/tmp/sidekick.zip';
		@unlink($plugin_file);

		// Download the file from the repo

		if( ini_get('allow_url_fopen') ) {
			file_put_contents($plugin_file, file_get_contents($url));
		} else {
			$ch = curl_init($url);
			$fp = fopen($plugin_file, 'wb');
			curl_setopt($ch, CURLOPT_FILE, $fp);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_exec($ch);
			curl_close($ch);
			fclose($fp);
		}
		
		// Unzip the file
		if(file_exists('/tmp/sidekick.zip')){
			if(!sunzip('/tmp/sidekick.zip', $__settings['softpath'].'/wp-content/plugins/')){
				$error[] = 'Could not unzip the plugin files - sidekick.zip';
			}
		}
		
		// MKDIR some files (If needed uncomment the following line and change the directory name as per your requirement)
		//@smkdir($__settings['softpath'].'/wp-content/uploads/', $globals['odc']);

		// CHMOD some files (If needed uncomment the following line and change the directory name as per your requirement)
		//@schmod($__settings['softpath'].'/wp-content/uploads/', $globals['odc']);
		
		$__settings['active_plugins'][] = 'sidekick/sidekick.php';
		
		// Now make the SQL related changes (If required)
		
		// $query = "INSERT INTO ".$__settings['dbprefix']."options (option_name, option_value) VALUES ('smm_version', '1.3.1'), ('disable_smm', '0');";
		// $result = sdb_query($query, $__settings['softdbhost'], $__settings['softdbuser'], $__settings['softdbpass'], $__settings['softdb']);

	}

	// This should be at the end (As there may be many plugins)
	$__settings['active_plugins'] = serialize($__settings['active_plugins']);


	$query = "UPDATE ".$__settings['dbprefix']."options
	SET option_value = '".$__settings['active_plugins']."' 
	WHERE option_name = 'active_plugins';";
	$result = sdb_query($query, $__settings['softdbhost'], $__settings['softdbuser'], $__settings['softdbpass'], $__settings['softdb']);

	require_once($globals['path'].'/conf/mods/'.$software['softname'].'/sk_settings.php');

	$query = "INSERT INTO " .
	$__settings['dbprefix'] . 
	"options (option_name, option_value) 
	VALUES 
	('sk_distributor_id', " . SK_DISTRIBUTOR_ID . "), 
	('sk_firstuse', " . SK_DISTRIBUTOR_ID . "), 
	('sk_do_activation_redirect', " . SK_FIRST_USE . "), 
	('sk_custom_class', '" . SK_ACTIVATION_REDIRECT . "');
	";
	$result = sdb_query($query, $__settings['softdbhost'], $__settings['softdbuser'], $__settings['softdbpass'], $__settings['softdb']);

	if ((isset($__settings['multisite']) && $__settings['multisite'])) {

		$query = "UPDATE ".$__settings['dbprefix']."sitemeta
		SET meta_value = 'a:1:{s:21:\"sidekick/sidekick.php\";i:1436383168;}' 
		WHERE meta_key = 'active_sitewide_plugins';";
		$result = sdb_query($query, $__settings['softdbhost'], $__settings['softdbuser'], $__settings['softdbpass'], $__settings['softdb']);

	}



}

?>