<?php

/*
=====================================================
   Linssen Excerpt
   by: Wil Linssen
   http://wil-linssen.com/
=====================================================
  This software is based upon and derived from
  EllisLab ExpressionEngine software protected under
  copyright dated 2004 - 2007. Please see
  www.expressionengine.com/docs/license.html
=====================================================
  File: pi.linssen_columnify.php
-----------------------------------------------------
  Purpose: Linssen Columnify plugin
=====================================================

*/


$plugin_info = array(
	'pi_name'			=> 'Linssen Excerpt',
	'pi_version'		=> '1.0',
	'pi_author'			=> 'Wil Linssen',
	'pi_author_url'		=> 'http://wil-linssen.com/',
	'pi_description'	=> 'Will return the excerpt (specified for search) for any entry',
	'pi_usage'			=> Linssen_excerpt::usage()
);


class Linssen_excerpt {

  var $return_data;
  
	/**
	* ---------------------------------------------------------------------------------------------------------
	* Linsen Excerpt
	*
	* ---------------------------------------------------------------------------------------------------------
	*/
  function Linssen_excerpt()
  {
    global $TMPL, $FNS, $DB;
    
    // Include & instantiate the extra classees we need
    if ( ! class_exists('Weblog')) require PATH_MOD.'/weblog/mod.weblog'.EXT;
    if ( ! class_exists('Typography')) require PATH_CORE.'core.typography'.EXT;
  
    $weblog = new Weblog;
    $weblog->TYPE = new Typography;
    $weblog->TYPE->convert_curly = FALSE;
    $weblog->TYPE->encode_email = FALSE;
    
    // Pick out the parameters
    $entry_id = $TMPL->fetch_param('entry_id');
    $word_limit = ( ! $TMPL->fetch_param('word_limit')) ? 50 :  $TMPL->fetch_param('word_limit');
    $trailer = ( ! $TMPL->fetch_param('trailer')) ? '...' :  $TMPL->fetch_param('trailer');
    
    $query = $DB->query("SELECT * FROM `exp_weblog_data`,`exp_weblogs` WHERE
      `exp_weblog_data`.`entry_id` = ".$entry_id." AND
      `exp_weblogs`.`weblog_id` = `exp_weblog_data`.`weblog_id`");
      
    $format = ( ! isset($query->row['field_ft_'.$query->row['search_excerpt']])) ? 'xhtml' : $query->row['field_ft_'.$query->row['search_excerpt']];
	
    $full_text = $weblog->TYPE->parse_type(strip_tags($query->row['field_id_'.$query->row['search_excerpt']]), 
			array(
					'text_format'   => $format,
					'html_format'   => 'safe',
					'auto_links'    => 'y',
					'allow_img_url' => 'n'
			));												    
		$excerpt = strip_tags($full_text);
		$excerpt = trim(preg_replace("/(\015\012)|(\015)|(\012)/", " ", $excerpt));    
		$excerpt = $FNS->word_limiter($excerpt, $word_limit);
    
    $this->return_data = $excerpt.$trailer;
 		
  }
  /* END */
  
  /**
  * ---------------------------------------------------------------------------------------------------------
  * Plugin usage
  *
  * ---------------------------------------------------------------------------------------------------------
  */

  // This function describes how the plugin is used.
  //  Make sure and use output buffering

  function usage()
  {
  ob_start(); 
?>
Just pass the entry id for which you require an excerpt and the plugin will return it

{exp:linssen_excerpt entry_id="14"}

You may of course use an EE variable:

{exp:weblog:entries weblog="weblog"}
  {exp:linssen_excerpt}
{/exp:weblog:entries}

The "trailer" parameter sets the string to appear after the excerpt (defaults to "...")

The "word_limit" parameter sets how many words to show in your excerpt

{exp:linssen_excerpt entry_id="{entry_id}" word_limit="10" trailer="... read more"}

Would show

"Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer fringilla... read more"
<?php

  $buffer = ob_get_contents();

  ob_end_clean(); 

  return $buffer;
  }
  /* END */

}
// END CLASS
?>