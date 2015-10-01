<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
	function compress() 
	{
        $CI =& get_instance();
        $raw_html = $CI->output->get_output();
        $search = array('/[\r\n\t]+/','/\>[^\S ]+/s', '/[^\S ]+\</s', '/(\s)+/s',);
        $replace = array(' ','>','<','\\1',);
      	$compressed_html = preg_replace($search, $replace, $raw_html);
        $CI->output->set_output($compressed_html);
        $CI->output->_display();
	}