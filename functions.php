<?php

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 */
function reiki_setup() {
    global $content_width;

    if (!isset($content_width)) {
        $content_width = 640;
    }

    load_theme_textdomain('reiki', get_template_directory() . '/languages');

    add_theme_support('automatic-feed-links');
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    
    set_post_thumbnail_size(890, 510, true);
    
    register_default_headers(array(
        'homepage-image' => array(
            'url'           => '%s/assets/images/home_page_header.jpg',
            'thumbnail_url' => '%s/assets/images/home_page_header.jpg',
            'description'   => __('Homepage Header Image', 'reiki'),
        ),
        'default-image'  => array(
            'url'           => '%s/assets/images/page_header.jpg',
            'thumbnail_url' => '%s/assets/images/page_header.jpg',
            'description'   => __('Default Header Image', 'reiki'),
        ),
    ));
    
    add_theme_support('custom-header', apply_filters('reiki_custom_header_args', array(
        'default-image' => get_template_directory_uri() . "/assets/images/page_header.jpg",
        'width'         => 1920,
        'height'        => 800,
        'flex-height'   => true,
        'flex-width'    => true,
        'header-text'   => false,
    )));
    
    add_theme_support('custom-logo', array('height' => 70));

    register_nav_menus(array(
        'primary' => __('Primary Menu', 'reiki'),
    ));
}
add_action('after_setup_theme', 'reiki_setup');


/**
* Add a pingback url auto-discovery header for singularly identifiable articles.
*/
function reiki_pingback_header() {
    if ( is_singular() && pings_open() ) {
        printf( '<link rel="pingback" href="%s">' . "\n", get_bloginfo( 'pingback_url' ) );
    }
}
add_action( 'wp_head', 'reiki_pingback_header' );

/**
 * Add customizer controls
 */
function reiki_customize_register_action($wp_customize) {
    $wp_customize->add_setting('reiki_homepage_header',
        array('sanitize_callback' => 'esc_url_raw', 'default' => get_template_directory_uri() . "/assets/images/home_page_header.jpg"));
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'reiki_homepage_header',
        array(
            'label'    => __('Home page header', 'reiki'),
            'section'  => 'header_image',
            'settings' => 'reiki_homepage_header',
            'priority' => 10,
        )));
}
add_action('customize_register', 'reiki_customize_register_action');



/**
 * Register sidebar
 */
function reiki_widgets_init() {
    register_sidebar(array(
        'name'          => __('Sidebar widget area', 'reiki'),
        'id'            => 'sidebar-1',
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h2 class="widgettitle">',
        'after_title'   => '</h2>',
    ));
}
add_action('widgets_init', 'reiki_widgets_init');


/**
 * Replaces "[...]" (appended to automatically generated excerpts) with ... and
 * a 'Read more' link.
 * @return string '... Read more'
 */
function reiki_excerpt_more($more) {
    return '&hellip; <a class="read-more" href="' . esc_url(get_permalink(get_the_ID())) . '">' . __('Read more', 'reiki') . '</a>';
}
add_filter('excerpt_more', 'reiki_excerpt_more');



/**
 * Gets logo as text or image, depending on user
 *
 * @param boolean $footer Use in footer
 * @return string Logo html
 */

 function reiki_logo($footer = false) {
     if (function_exists('has_custom_logo') && has_custom_logo()) {
        the_custom_logo();
     } elseif ($footer) {
        printf('<h2 class="footer-logo">%1$s</h2>', get_bloginfo('name'));
     } else {
        printf('<a class="text-logo" href="%1$s">%2$s</a>', esc_url(home_url('/')), get_bloginfo('name'));
     }
 }

/**
 * Enqueue scripts and styles.
 */
function reiki_scripts() {
    wp_enqueue_style('reiki_fonts', 'http://fonts.googleapis.com/css?family=Lato:300,400,700,900|Open+Sans:400,300,600,700|Source+Sans+Pro:200,normal,300,600,700');
    wp_enqueue_style('reiki_style', get_stylesheet_uri());
    wp_enqueue_style('reiki_font-awesome', get_template_directory_uri() . '/assets/font-awesome/font-awesome.min.css');
    wp_enqueue_script('reiki_ddmenu', get_template_directory_uri() . '/assets/js/drop_menu_selection.js', array('jquery-effects-slide'), false, true);
    wp_enqueue_script('comment-reply');
}
add_action('wp_enqueue_scripts', 'reiki_scripts');

/**
 * Footer copyright
 * @return string The footer copyright text.
 */
function reiki_copyright() {
    return '&copy;&nbsp;' . "&nbsp;" . date('Y') . '&nbsp;' . esc_html(get_bloginfo('name')) . '.&nbsp;' . __('Built using WordPress and Reiki Theme.', 'reiki');
}


/**
 * Menu fallback used for wp_nav_menu
 * @return string The wp_page_menu generated html
 */
function reiki_nomenu_cb() {
    return wp_page_menu(array(
        "menu_class" => 'fm2_drop_mainmenu',
        "menu_id"    => 'drop_mainmenu_container',
        'before'     => '<ul id="drop_mainmenu" class="fm2_drop_mainmenu">',
    ));
}

/**
 * The title to be used in header depending on the current post and template
 * @return string The title to be used in header
 */
function reiki_title() {
    $title = array(
        'title' => '',
    );

    if (is_404()) {
        $title['title'] = __('Page not found', 'reiki');
    } elseif (is_search()) {
        $title['title'] = sprintf(__('Search Results for &#8220;%s&#8221;', 'reiki'), get_search_query());
    } elseif (is_home()) {
        $title['title'] = __('Blog', 'reiki');
    } elseif (is_post_type_archive()) {
        $title['title'] = post_type_archive_title('', false);
    } elseif (is_tax()) {
        $title['title'] = single_term_title('', false);
    } elseif (is_singular()) {
        $title['title'] = single_post_title('', false);
    } elseif (is_category() || is_tag()) {
        $title['title'] = single_term_title('', false);
    } elseif (is_author() && $author = get_queried_object()) {
        $title['title'] = $author->display_name;
    } elseif (is_year()) {
        $title['title'] = get_the_date(_x('Y', 'yearly archives date format', 'reiki'));
    } elseif (is_month()) {
        $title['title'] = get_the_date(_x('F Y', 'monthly archives date format', 'reiki'));
    } elseif (is_day()) {
        $title['title'] = get_the_date();
    }

    return $title['title'];
}


/**
 * Current homepage header
 * @return string The escaped url of homepage header image
 */
function reiki_homepage_header() {
    return esc_url(get_theme_mod('reiki_homepage_header', get_template_directory_uri() . "/assets/images/home_page_header.jpg"));
}