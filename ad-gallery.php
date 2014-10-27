<?php
/*
 * Plugin Name: Ad Gallery
 * Plugin URI:
 * Description: Converts the default wordpress gallery to a gallery with an ad
 * Version: 0.1
 * Author: Radi Varbanov
 * Author URI:
 * License: GPL2
 *
 * Copyright 2014  Radi Varbanov  (email : radi.varbanov@gmail.com)
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2, as
 * published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

$all_settings = array();




//remove_shortcode('gallery', 'gallery_shortcode');
//add_shortcode('gallery', 'adg_gallery_shortcode');
add_filter('post_gallery', 'adg_gallery_shortcode', 99, 2);

/**
 * Ad Gallery Shortcode
 */

function adg_gallery_shortcode($output, $attr) {
    global $all_settings;

    if (empty($attr['type']) || $attr['type'] != 'adg') {
        // returning nothing will make gallery_shortcode take over
        //return 'in if from adg: '.$attr['type'];
        return $output;
    } else {
    //    return 'else from adg: '.$attr['type'];
    }



    /* code below copied from default gallery_shortcode (wp-includes/media.php) */
    /* BEGIN ------------------- */

    $post = get_post();

    static $instance = 0;
    $instance++;

    if (!empty($attr['ids'])) {
        // 'ids' is explicitly ordered, unless you specify otherwise.
        if (empty($attr['orderby']))
            $attr['orderby'] = 'post__in';
        $attr['include'] = $attr['ids'];
    }

    // Allow plugins/themes to override the default gallery template. // NOT NEEDED ofcourse
    // $output = apply_filters('post_gallery', '', $attr);
    // if ( $output != '' ) return $output;

    // We're trusting author input, so let's at least make sure it looks like a valid orderby statement
    if (isset($attr['orderby'])) {
        $attr['orderby'] = sanitize_sql_orderby($attr['orderby']);
        if (!$attr['orderby'])
            unset($attr['orderby']);
    }


    /* ------------------------- END */


    // image_size is deprecated, but if it is set and size is empty => size = image_size.
    if (!empty($attr['image_size']) && empty($attr['size'])) {
        $attr['size'] = $attr['image_size'];
    }

    $settings_arr = shortcode_atts(array(
        'type'              => 'adg',
        'class'             => '',
        'rel'               => 'adg',
        'ids'               => '',
        'margin'            => 2,
        'scale'             => 1.1,
        'maxrowheight'      => 200,
        'intime'            => 100,
        'outtime'           => 100,
        'captions'          => 'title',

        // default params  that can be inherited from gallery_shortcode
        'order'             => 'ASC',
        'orderby'           => 'menu_order ID',
        //'id'              => $post->ID, // from old code
        'id'                => $post ? $post->ID : 0,
        'itemtag'           => 'dl',
        'icontag'           => 'dt',
        'captiontag'        => 'dd',
        'descriptiontag'    => 'dd',
        'columns'           => 1,
        //'size'            => 'thumbnail',
        //'size'            => 'medium', // default changed from thumbnail to medium, because that makes more sense
        'size'              => 'large', // default changed from thumbnail to large, because that makes more sense
        'include'           => '',
        'exclude'           => '',
        'link'              => ''




    ), $attr);

    extract($settings_arr);




    /* code below copied from default gallery_shortcode (wp-includes/media.php) */
    /* BEGIN ------------------- */

    $id = intval($id);
    if ('RAND' == $order)
        $orderby = 'none';

    if (!empty($include)) {
        $_attachments = get_posts(array('include' => $include, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby));

        $attachments = array();
        foreach ($_attachments as $key => $val) {
            $attachments[$val->ID] = $_attachments[$key];
        }
    } elseif (!empty($exclude)) {
        $attachments = get_children(array('post_parent' => $id, 'exclude' => $exclude, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby));
    } else {
        $attachments = get_children(array('post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby));
    }

    if (empty($attachments))
        return '';

    if (is_feed()) {
        $output = "\n";
        foreach ($attachments as $att_id => $attachment)
            $output .= wp_get_attachment_link($att_id, $size, true)."\n";
        return $output;
    }


    $itemtag = tag_escape($itemtag);
    $captiontag = tag_escape($captiontag);
    $descriptiontag = tag_escape($descriptiontag);
    $icontag = tag_escape($icontag);
    $valid_tags = wp_kses_allowed_html( 'post' );
    if (!isset($valid_tags[$itemtag]))
        $itemtag = 'dl';
    if (!isset($valid_tags[$captiontag]))
        $captiontag = 'dd';
    if (!isset($valid_tags[$descriptiontag]))
        $descriptiontag = 'dd';
    if (!isset($valid_tags[$icontag]))
        $icontag = 'dt';

    /* ------------------------- END */



    $all_settings[] = $settings_arr;

    ob_start();
?>
    <div class="adg-gallery">
        <div class="adg-gallery-nav">
            <div class="prev"></div>
            <div class="next"></div>
        </div>
        <div class="adg-gallery-slide <?php echo $class ?>" adg_id="<?php echo count($all_settings) ?>">

<?php
    foreach ($attachments as $id => $attachment) {
        $info = wp_get_attachment_image_src($id, 'large');
        $link = $info[0];
        $title = $captions == 'off' ? '' : get_post_field('post_excerpt', $id);
        $title_esc = htmlentities($title, ENT_COMPAT, 'UTF-8');
        $alt = get_post_meta($id, '_wp_attachment_image_alt', true);
        if (empty($alt)) {
            $alt = $attachment->post_title;
        }
        //$image_output = wp_get_attachment_link( $id, $size, true, false );
        $attr = array(
            //'src'    => $src,
            'class'    => "attachment-$size",
            'alt'   => trim(strip_tags($alt))
        );
        $image_output = wp_get_attachment_image($id, $size, false, $attr);

        $orientation = '';
        if (isset($image_meta['height'], $image_meta['width']))
            $orientation = ($image_meta['height'] > $image_meta['width']) ? 'portrait' : 'landscape';

        //$output = "<div>";
        $output = "<{$itemtag} class='gallery-item adg-active-image'>";

        //$output .= $image_output;
        $output .= "
            <{$icontag} class='gallery-icon {$orientation}'>
                $image_output
            </{$icontag}>";

        if ($captiontag && trim($attachment->post_excerpt)) {
            $output .= "
                <{$captiontag} class='wp-caption-text gallery-caption'>
                " . wptexturize($attachment->post_excerpt) . "
                </{$captiontag}>";
        }
        if ($descriptiontag && trim($attachment->post_content)) {
            $output .= "
                <{$descriptiontag} class='wp-description-text gallery-description'>
                " . wptexturize($attachment->post_content) . "
                </{$descriptiontag}>";
        }

        //echo "<a rel=\"$rel\" href=\"$link\" title=\"$title_esc\">$img</a>";
        //echo wp_get_attachment_link($id, 'medium', true);
        //echo $link;

        //$output .= "</div>";
        $output .= "</{$itemtag}>";

        echo $output;
    }
?>
        </div><!-- .adg-gallery-slide -->
        <div class="adg-gallery-content">
            <div class="adg-img-content">
                <span class="adg-img-counter"></span>
                <h1 class="adg-img-title"></h1>
                <p class="adg-img-content-p"></p>
            </div>
            <?php if(get_option('ad_gallery_ad_status')) {?>
            <div class="adg-ad-300x250">
                <?php echo get_option('ad_gallery_ad_script');?>
            </div>
            <?php } ?>
        </div>
    </div>
    <div style="clear:both"></div>
<?php
    //possible solution to get rid of p-tags.
    //remove_filter( 'the_content', 'wpautop' );
    //add_filter( 'the_content', 'wpautop' , 12);
    // return ob_get_clean();

    // better solution to get rid of p-tags: [raw][/raw] : removed again, seemed to mess things up in some wordpress sites.
    return do_shortcode(ob_get_clean());
}

add_action('wp_footer', 'adg_register_scripts');

/**
 * Register Scripts for the Ad Gallery
 */

function adg_register_scripts() {
    global $all_settings;

    if (count($all_settings) == 0) {
        return;
    }

    // enqueue css
    wp_register_style('adg-style', plugins_url('css/style.css', __FILE__, array(), '2014-10-20'));
    wp_enqueue_style('adg-style');

    // enqueue scripts
    //wp_enqueue_script('adg-imagesloaded', plugins_url( 'js/jquery.imagesloaded.min.js' , __FILE__ ), array('jquery'), '1', false );
    //wp_enqueue_script('adg-gallerygrid', plugins_url( 'js/jquery.gallerygrid.js' , __FILE__ ), array('adg-imagesloaded'), '1.3.1', false );
    wp_enqueue_script('jquery');
    wp_enqueue_script( $handle, $src, $deps, $ver, $in_footer );
    wp_enqueue_script('adg-main', plugins_url( 'js/main.js' , __FILE__ ), array('jquery'), '2014-10-20');

    // pass shortcode parameters to main script also
    wp_localize_script('adg-main', 'adg_params', $all_settings);
}


add_action( 'admin_init', 'ad_gallery_settings_api_init' );

/**
 *
 * ad_gallery_settings_api_init
 *
 */

function ad_gallery_settings_api_init() {
    // Add the section to reading settings so we can add our
    // fields to it
    add_settings_section(
        'ad_gallery_setting_section',
        'Ad Gallery',
        'ad_gallery_setting_section_callback_function',
        'media'
    );

    // Add the field with the names and function to use for our new
    // settings, put it in our new section
    add_settings_field(
        'ad_gallery_ad_status',
        'Ad On/Off',
        'ad_gallery_status_setting_callback_function',
        'media',
        'ad_gallery_setting_section'
    );

    // Add the field with the names and function to use for our new
    // settings, put it in our new section
    add_settings_field(
        'ad_gallery_ad_script',
        'Ad Script',
        'ad_gallery_setting_callback_function',
        'media',
        'ad_gallery_setting_section'
    );

    // Register our setting so that $_POST handling is done for us and
    // our callback function just has to echo the <input>
    register_setting( 'media', 'ad_gallery_ad_status' );
    register_setting( 'media', 'ad_gallery_ad_script' );
}


/**
 * Settings section callback function
 *
 * This function is needed if we added a new section. This function
 * will be run at the start of our section
 */

function ad_gallery_setting_section_callback_function() {
    echo '<p>Insert your ad script in here:</p>';
}

/**
 * Callback function for our example setting
 *
 * Creates a textarea to hold the ad
 */

function ad_gallery_status_setting_callback_function() {
    $ad_gallery_ad_status = get_option('ad_gallery_ad_status');
    echo '<input name="ad_gallery_ad_status" type="checkbox" id="ad_gallery_ad_status"  value="1" '.checked(1, $ad_gallery_ad_status, false).' />';
    echo '<label for="ad_gallery_ad_status">Check the box to activate the ad</label>';
}

/**
 * Callback function for our example setting
 *
 * Creates a textarea to hold the ad
 */

function ad_gallery_setting_callback_function() {
    $ad_gallery_ad_script = get_option('ad_gallery_ad_script');
    echo '<textarea name="ad_gallery_ad_script" id="gv_thumbnails_insert_into_excerpt" class="code"  cols="120" rows="10">';
    echo  $ad_gallery_ad_script;
    echo '</textarea>';
}



add_filter('plugin_action_links_'.plugin_basename(__FILE__), 'ad_gallery_settings_link' );

/**
 * Add settings link on plugin page
 */

function ad_gallery_settings_link($links) {
  $settings_link = '<a href="options-media.php">Settings</a>';
  array_unshift($links, $settings_link);
  return $links;
}