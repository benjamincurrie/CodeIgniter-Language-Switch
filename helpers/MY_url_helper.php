<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Site URL
 * Used when creating internal anchors, translates a uri into the current language
 */
function site_url($uri, $lang = FALSE) {
	$CI =& get_instance();
	$CI->load->config('language');
	
	if(!is_array($uri)) {
		$uri = explode('?', $uri);
		$query = isset($uri[1]) ? '?'.$uri[1] : '';
		$uri = explode('/', ltrim($uri[0], '/'));
	}
	
	if(!$lang) $lang = $CI->config->item('language');
	$lang_code = array_search($lang, $CI->config->item('language_codes'));
	
	switch($CI->config->item('url_style'))
	{
		case "SEO":
			$segment_translations = $CI->config->item('segment_translations');
			foreach($uri as $id=>$segment)
			{
				$uri[$id] = isset($segment_translations[$lang][$segment]) ? $segment_translations[$lang][$segment] : $segment;
			}
			break;
		case "Path":
			array_unshift($uri, $lang_code);
			break;
		case "Query":
			$query .= $query ? '&lang='.$lang_code : '?lang='.$lang_code;
			break;
	}
	
	return $CI->config->site_url($uri).$query;
}

/**
 * Language Menu
 * Returns a unordered list of links to switch languages
 * @param	$class Class of the list 
 * @return	string $links The list of language links
 */
function language_menu($class = "") {
	$CI =& get_instance();
	$CI->load->config('language');
	$CI->load->helper('html');
	
	$languages = $CI->config->item('languages');
	$page = $CI->uri->uri_string() ? $CI->uri->uri_string() : $CI->router->default_controller;
	
	$links = array();
	foreach($languages as $lang => $name)
	{
		if($lang != $CI->config->item('language'))
		{
			$links[] = anchor(site_url($page, $lang), $name);
		}
	}
	
	return ul($links, array('class'=>$class));
}

/* End of file MY_url_helper.php */
/* Location: ./application/helpers/MY_url_helper.php */