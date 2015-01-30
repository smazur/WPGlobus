<?php


add_action( 'init', 'wpg_init', 2 );
function wpg_init() {

	// check if it isn't already initialized
	if ( defined( 'WPGLOBUS_INIT' ) ) {
		return;
	}

	define( 'WPGLOBUS_INIT', true );

	global $WPGlobus_Config;

	//wp_redirect('http://wpml2.dev/ru/news/hello-world');
	//exit();	

	//wpg_loadConfig();
	/*
	if(isset($_COOKIE['qtrans_cookie_test'])) {
		$q_config['cookie_enabled'] = true;
	} else  {
		$q_config['cookie_enabled'] = false;
	}
	// */

	// init Javascript functions
	//qtrans_initJS();

	// update Gettext Databases if on Backend
	//if(defined('WP_ADMIN') && $q_config['auto_update_mo']) qtrans_updateGettextDatabases();

	// update definitions if necessary
	//if(defined('WP_ADMIN') && current_user_can('manage_categories')) qtrans_updateTermLibrary();

	// extract url information
	//$q_config['url_info'] = wpg_extractURL($_SERVER['REQUEST_URI'], $_SERVER["HTTP_HOST"], isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '');

	/** @todo check at class-wpglobus.php:103 for set url_info */
	$WPGlobus_Config->url_info =
		WPGlobus_Utils::extract_url( $_SERVER['REQUEST_URI'], $_SERVER["HTTP_HOST"], isset( $_SERVER['HTTP_REFERER'] ) ? $_SERVER['HTTP_REFERER'] : '' );

	//error_log( print_r( $WPGlobus_Config->url_info, true ));

	/**
	 * Add hack for support AJAX
	 */
	/*
	if ( defined('DOING_AJAX') && DOING_AJAX && isset( $_SERVER['HTTP_REFERER'] ) ) {
		$referer_info = wpg_parseURL( $_SERVER['HTTP_REFERER'] );
		$q_config['url_info'] = wpg_extractURL(
			$referer_info['path'], $_SERVER["HTTP_HOST"], isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '');
	} */
	/* end hack code	*/

	// set test cookie
	//setcookie('qtrans_cookie_test', 'qTranslate Cookie Test', 0, $q_config['url_info']['home'], $q_config['url_info']['host']);

	// check cookies for admin

	/**
	 * Add hack in 1 line for support AJAX
	 * if(defined('WP_ADMIN')) {}
	 */
	/* 
	if(defined('WP_ADMIN') && !(defined('DOING_AJAX') && DOING_AJAX) ) {
		if(isset($_GET['lang']) && wpg_isEnabled($_GET['lang'])) {
			$q_config['language'] = $q_config['url_info']['language'];
			setcookie('qtrans_admin_language', $q_config['language'], time()+60*60*24*30);
		} elseif(isset($_COOKIE['qtrans_admin_language']) && wpg_isEnabled($_COOKIE['qtrans_admin_language'])) {
			$q_config['language'] = $_COOKIE['qtrans_admin_language'];
		} else {
			$q_config['language'] = $q_config['default_language'];
		}
	} else {
		// $q_config['language'] = $q_config['url_info']['language'];
		$WPGlobus_Config->language = $WPGlobus_Config->url_info['language'];
	}
	// */

	//$q_config['language'] = apply_filters('qtranslate_language', $q_config['language']);


	/*
	// detect language and forward if needed
	//if($q_config['detect_browser_language'] && $q_config['url_info']['redirect'] && !isset($_COOKIE['qtrans_cookie_test']) && $q_config['url_info']['language'] == $q_config['default_language']) {
		$target = false;
		$preferred_languages = array();
		if(isset($_SERVER["HTTP_ACCEPT_LANGUAGE"]) && preg_match_all("#([^;,]+)(;[^,0-9]*([0-9\.]+)[^,]*)?#i",$_SERVER["HTTP_ACCEPT_LANGUAGE"], $matches, PREG_SET_ORDER)) {
			$priority = 1.0;
			foreach($matches as $match) {
				if(!isset($match[3])) {
					$pr = $priority;
					$priority -= 0.001;
				} else {
					$pr = floatval($match[3]);
				}
				$preferred_languages[$match[1]] = $pr;
			}
			arsort($preferred_languages, SORT_NUMERIC);
			foreach($preferred_languages as $language => $priority) {
				if(strlen($language)>2) $language = substr($language,0,2);
				if(qtrans_isEnabled($language)) {
					if($q_config['hide_default_language'] && $language == $q_config['default_language']) break;
					$target = qtrans_convertURL(get_option('home'),$language);
					break;
				}
			}
		}
		//$target = apply_filters("qtranslate_language_detect_redirect", $target);
		if($target !== false) {
			//error_log( 'target is HERE' );
			wp_redirect($target);
			exit();
		} else {
			//error_log( 'target is FALSE' );
		}
	}
	// */

	/*
	// Check for WP Secret Key Mismatch
	global $wp_default_secret_key;
	if(strpos($q_config['url_info']['url'],'wp-login.php')!==false && defined('AUTH_KEY') && isset($wp_default_secret_key) && $wp_default_secret_key != AUTH_KEY) {
		global $error;
		$error = __('Your $wp_default_secret_key is mismatching with your AUTH_KEY. This might cause you not to be able to login anymore.','qtranslate');
	}
	*/

	// Filter all options for language tags
	/*
	if(!defined('WP_ADMIN')) {
		$alloptions = wp_load_alloptions();
		foreach($alloptions as $option => $value) {
			add_filter('option_'.$option, 'qtrans_useCurrentLanguageIfNotFoundUseDefaultLanguage',0);
		}
	} // */

	// load plugin translations
	//load_plugin_textdomain('qtranslate', false, dirname(plugin_basename( __FILE__ )).'/lang');

	// remove traces of language (or better not?)
	//unset($_GET['lang']);


	$_SERVER['REQUEST_URI'] = $WPGlobus_Config->url_info['url'];
	$_SERVER['HTTP_HOST']   = $WPGlobus_Config->url_info['host'];

	// fix url to prevent xss
	//$q_config['url_info']['url'] = qtrans_convertURL(add_query_arg('lang',$q_config['default_language'],$q_config['url_info']['url']));
}

/*
add_filter( 'the_posts', 'wpg_postsFilter', 0 );
function wpg_postsFilter($posts) {
	if(is_array($posts)) {
		foreach($posts as $post) {
			$post->post_content = __wpg_text_filter($post->post_content);
			
			# @todo make function for translating $post object 	
			#$post = qtrans_useCurrentLanguageIfNotFoundUseDefaultLanguage($post);
		}
	}
	return $posts;
} // */