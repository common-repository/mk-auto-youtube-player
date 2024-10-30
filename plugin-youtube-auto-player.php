<?php
/*
Plugin Name: MK - Auto Youtube Player
Plugin URI: http://mkplugins.com
Description: This will allow you to play youtube videos. Just add shortcode - [youtube-auto-id]  and qry string for your youtube URL ID (?uid=XXXXX). i.e. if the youtube url is "<a href="https://www.youtube.com/watch?v=IzZtMQWIFe8">https://www.youtube.com/watch?v=IzZtMQWIFe8</a>" , ?uid=IzZtMQWIFe8 and by default the plugin will <a href="http://mkplugins.com">MK Auto Youtube Player</a> will hide all controls. But if for some reason you want to show controls (play, pause, fastforward and so on), just add "us=yes". So, your query string will be - ?uc=yes&uid=HLzmXEeu0vU
Author: Mark Kumar
Version: 2014.11.10
Author URI: http://mkplugins.com
 */
 
//load up jQuery
wp_enqueue_script("jquery");

//for short code 
add_shortcode("youtube-auto-id","youtube_auto_id_optin_box");
//fucntion that runs the short code
//i.e. [youtube-auto-id]
function youtube_auto_id_optin_box( $atts, $content) 
{
    global $wpdb;         

    //get youtube url id
    $get_video_id = $_GET['uid'];
    $get_video_control = $_GET['uc'];
    
    
    if (isset($get_video_id))
    { 
        ob_start();
        youtube_video_id($get_video_id,$get_video_control); 
        $output_string = ob_get_contents();
        ob_end_clean();    
        return $output_string;    
    }	
}



function youtube_video_id($get_file_id,$get_video_control)
{
    if($get_video_control == 'yes')
    {
        $get_video_control = 1;
    }
    else
    {
        $get_video_control =0;
    }
   	
	$url = parse_url(get_site_url());

	if($url['scheme'] == 'https')
	{
	   $url = 'https://www.youtube.com/watch?v='.$get_file_id;
	}
	else
	{
		$url = 'http://www.youtube.com/watch?v='.$get_file_id;
	}
	
    $embed_args = array(
    'width' => '853',
    'height' => '480',
    'modestbranding' => '1',
    'autoplay' => '1',
    'controls' => $get_video_control ,
    'fs' => '0' ,
    'iv_load_policy' => '3',
    'rel' => '0',
    'showinfo' => '0',
    'disablekb'=>'1',
    );
    $strIDStructure = '<div align="center" style="padding: 10px;" id="'. rand(100,500).'">'.wp_oembed_get( $url , $embed_args ).'</div>';
    

    
    echo do_shortcode($strIDStructure);
}

// Filter video output... needed in order the wp_oembed_get to work.
add_filter('oembed_result','lc_oembed_result', 10, 3);

function lc_oembed_result($html, $url, $args) 
{
 
    // $args includes custom argument
 
    $newargs = $args;
    // get rid of discover=true argument
    array_pop( $newargs );
 
    $parameters = http_build_query( $newargs );
 
    // Modify video parameters
    $html = str_replace( '?feature=oembed', '?feature=oembed'.'&amp;'.$parameters, $html );
 
    return $html;
}


?>
