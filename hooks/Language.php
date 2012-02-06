<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); 

/**
 * CodeIgniter Language Hook
 *
 * Translates URIs from a foreign language
 *
 * @package			CodeIgniter
 * @subpackage		Hooks
 * @category		Hooks
 * @author			Benjamin Currie
 * @created			03/02/2012
 * @link			http://github.com/benjamincurrie/CodeIgniter-Language-Switch
 */

class Language
{
	/**
	 * Route
	 * Using the language config we attempt to detect whether the URI is foreign.
	 * If we detect the URI is translated we set the language and translate the URI into a localized version.
	 * SEO links only works using REQUEST_URI
	 */
	function route()
	{
		include(APPPATH.'config/config.php');
		include(APPPATH.'config/language.php');
		
		// Get the URI
		$uri = $_SERVER['REQUEST_URI'];
		// Remove the script
		$uri = preg_replace('/^('.addcslashes($_SERVER['SCRIPT_NAME'], '/').'|'.addcslashes(dirname($_SERVER['SCRIPT_NAME']), '/').')?\/?(.*?)(\?.*)?$/', '$2', $uri);
		
		if($uri!="")
		{
			// SEO URL's
			if($config['url_style']=="SEO")
			{
				// Let's attempt to translate the URI
				$segments = explode('/', $uri);
				// Is the first segment in the controllers
				if(file_exists(APPPATH.'controllers/'.$segments[0].'.php') OR is_dir(APPPATH.'controllers/'.$segments[0]))
				{
					// If so let's just go with the default language
					define('LANGUAGE', $config['language']);
				}
				else
				{
					foreach($config['segment_translations'] as $language=>$translations) {
						if(in_array($segments[0], $translations)) {
							$translations = array_flip($translations);
							define('LANGUAGE', $language);
							foreach($segments as $id=>$segment) {
								$segments[$id] = isset($translations[$segment]) ? $translations[$segment] : $segment;
							}
						}
					}
				}
				
				$translated_uri = implode('/', $segments);
				
				$_SERVER['REQUEST_URI'] = str_replace($uri, $translated_uri, $_SERVER['REQUEST_URI']);
			}

			if($config['url_style']=="Path" OR ($config['url_style']=="SEO" && !defined('LANGUAGE')))
			{
				// We default back to Path if the uri wasn't translated
				$lang = preg_replace('/([a-z]{2})(\/?(.*))?/i', '$1', $uri);
				if(isset($config['language_codes'][$lang]) && isset($config['languages'][$config['language_codes'][$lang]]))
				{
					define('LANGUAGE', $config['language_codes'][$lang]);
					$_SERVER['REQUEST_URI'] = preg_replace('/([a-z]{2})(\/?(.*))?/i', '$2', $uri);
				}
			}
		}
	}
	
	/**
	 * Set
	 * Sets the language in the config before the controller
	 */
	function set()
	{
		global $SEC;
		include(APPPATH.'config/language.php');
		
		if(defined('LANGUAGE'))
		{
			if($this->_set(LANGUAGE)) return;
		}
		
		if($config['url_style']=="Query" && isset($_GET['lang']))
		{
			if($this->_set($SEC->xss_clean($_GET['lang']))) return;
		}
		
		// if($config['url_style']=="Subdomain")
		// {
		// 	$host = explode('.', $_SERVER['HTTP_HOST']);
		// 	$lang = $host[0];
		// 	if($this->_set($lang)) return;
		// }
		
		if($config['browser_language_detection'])
		{
			// We couldn't detect a language, try HTTP_ACCEPT_LANGUAGE
			$language_codes = $config['language_codes'];
			
			$accepted_languages = explode(',', strtolower(preg_replace('/([a-z]{2,8}(-[A-Z]{2,8})?)(;(.*)?)/i', '$1', $_SERVER['HTTP_ACCEPT_LANGUAGE'])));
			
			foreach($accepted_languages as $language)
			{
				if($this->_set($language)) return;
			}
		}
	}
	
	/**
	 * Set
	 * Confirms language exists in config and sets it
	 */
	private function _set($language) {
		global $CFG;
		include(APPPATH.'config/language.php');
		
		if(isset($config['languages'][$language]))
		{
			$CFG->set_item('language', $language);
			return true;
		}
		elseif(isset($config['language_codes'][$language]) && isset($config['languages'][$config['language_codes'][$language]]))
		{
			$CFG->set_item('language', $config['language_codes'][$language]);
			return true;
		}
		elseif(isset($config['language_codes'][substr($language, 0, 2)]) && isset($config['languages'][$config['language_codes'][substr($language, 0, 2)]])) {
			$CFG->set_item('language', $config['language_codes'][substr($language, 0, 2)]);
			return true;
		}
	}

}