<?php
/*
  Plugin Name: WP Review Schema Free
  Plugin URI: https://wpdeveloper.net/free-plugin/wp-review-schema/
  Author: WPDeveloper.net
  Author URI: http://wpdeveloper.net
  Description: The Ultimate Review Schema for WordPress. Improve your CTR & SEO Rankings. Add Review Schema And Look Good in Google.
  Version: 1.0.0
  Text Domain: wp-review-schema
  License: GPL2
  Min WP Version: 3.0
  Max WP Version: 4.2.2
 */

define("WPRS_PLUGIN_URL",plugins_url("",__FILE__ ));#without trailing slash (/)

  if ( !defined( 'REVIEW_META_BOX_URL' ) ) define( 'REVIEW_META_BOX_URL', plugin_dir_url( __FILE__ ) );

  if ( !class_exists( "WPReviewSchema" ) ) {

    class WPReviewSchema {

        public function __construct() {
            register_activation_hook( __FILE__, array($this, 'register_review_settings') );

            add_action( 'admin_head', array($this, 'star_icons') );
            add_action( 'admin_menu', array($this, 'register_custom_menu_page') );
            add_action( 'admin_init', array($this, 'set_styles') );
            add_action( 'admin_init', array($this, 'bsf_color_scripts') );
            add_action( 'admin_bar_menu', array($this, "review_schema_admin_bar"), 100 );
            add_filter( 'plugins_loaded', array($this, 'review_schema_translation') );
            add_action( 'admin_enqueue_scripts', array($this, 'post_enqueue') );
            add_action( 'admin_enqueue_scripts', array($this, 'post_new_enqueue') );
            $plugin = plugin_basename( __FILE__ );
            add_filter( "plugin_action_links_$plugin", array($this, 'review_schema_settings_link') );
            add_action( 'wp_ajax_bsf_submit_request', array($this, 'submit_request') );
            add_action( 'wp_ajax_bsf_submit_color', array($this, 'submit_color') );
        }

        public function review_schema_translation() {
            load_plugin_textdomain( 'wp-review-schema', false, basename( dirname( __FILE__ ) ) . '/lang/' );
        }

        public function review_schema_admin_bar() {
            global $wp_admin_bar;
            $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
            if ( !is_super_admin() || !is_admin_bar_showing() ) return;
            if ( !is_admin() ) {
                $wp_admin_bar->add_menu( array(
                    'id' => 'arcomsrs',
                    'title' => 'Test Rich Snippets',
                    'href' => 'http://www.google.com/webmasters/tools/richsnippets?q=' . $actual_link,
                    'meta' => array('target' => '_blank'),
                    ) );
            }
        }

        public function register_custom_menu_page() {
            require_once(plugin_dir_path( __FILE__ ) . 'admin/index.php');
            $page = add_menu_page( 'Review Schema Dashboard', 'Review Schema', 'administrator', 'review_schema_dashboard', 'review_schema_dashboard', 'div' );
            //Call the function to print the stylesheets and javascripts in only this plugins admin area
            add_action( 'admin_print_styles-' . $page, 'review_schema_admin_styles' );
            add_action( 'admin_print_scripts-' . $page, array($this, 'iris_enqueue_scripts') );
        }

        public function review_schema_settings_link( $links ) {
            $settings_link = '<a href="admin.php?page=review_schema_dashboard">Settings</a>';
            array_unshift( $links, $settings_link );
            return $links;
        }

        //print the star rating style on post edit page
        public function post_enqueue( $hook ) {
            if ( 'post.php' != $hook ) return;
            wp_enqueue_script( 'jquery' );
            wp_enqueue_script( 'bsf_jquery_star' );
            wp_enqueue_script( 'bsf_toggle' );
            wp_enqueue_style( 'star_style' );
            wp_register_script( 'bsf-scripts', REVIEW_META_BOX_URL . 'js/cmb.js', '', '0.9.1' );
            wp_enqueue_script( 'bsf-scripts' );
            wp_register_script( 'bsf-scripts-media', REVIEW_META_BOX_URL . 'js/media.js', '', '1.0' );
            wp_enqueue_script( 'bsf-scripts-media' );
            wp_enqueue_script( 'jquery-ui-datepicker' );
            if ( !function_exists( 'vc_map' ) ) wp_enqueue_style( 'jquery-style', REVIEW_META_BOX_URL . 'css/jquery-ui.css' );
        }

        public function post_new_enqueue( $hook ) {
            if ( 'post-new.php' != $hook ) return;
            wp_enqueue_script( 'jquery' );
            wp_enqueue_script( 'bsf_jquery_star' );
            wp_enqueue_script( 'bsf_toggle' );
            wp_enqueue_style( 'star_style' );
            wp_register_script( 'bsf-scripts', REVIEW_META_BOX_URL . 'js/cmb.js', '', '0.9.1' );
            wp_enqueue_script( 'bsf-scripts' );
            wp_register_script( 'bsf-scripts-media', REVIEW_META_BOX_URL . 'js/media.js', '', '1.0' );
            wp_enqueue_script( 'bsf-scripts-media' );
            wp_enqueue_script( 'jquery-ui-datepicker' );
            if ( !function_exists( 'vc_map' ) ) wp_enqueue_style( 'jquery-style', REVIEW_META_BOX_URL . 'css/jquery-ui.css' );
        }

        public function set_styles() {
            wp_register_style( 'star_style', plugins_url( '/css/jquery.rating.css', __FILE__ ) );
            wp_register_style( 'meta_style', plugins_url( 'admin/css/style.css', __FILE__ ) );
            wp_register_style( 'wprc_admin_style', plugins_url( 'admin/css/admin.css', __FILE__ ) );
            wp_register_script( 'bsf_jquery_star', plugins_url( '/js/jquery.rating.min.js', __FILE__ ) );
        }

        // Define icon styles for the custom post type
        public function star_icons() {
            ?>
            <style>
                #toplevel_page_review_schema_dashboard .wp-menu-image {
                    background: url(<?php echo plugins_url( '/images/star.png', __FILE__ ); ?>) no-repeat !important;
                }
                #toplevel_page_review_schema_dashboard:hover .wp-menu-image, #toplevel_page_review_schema_dashboard.wp-has-current-submenu .wp-menu-image {
                    background: url(<?php echo plugins_url( '/images/star.png', __FILE__ ); ?>) no-repeat 0 -32px !important;
                }
                #toplevel_page_review_schema_dashboard .current .wp-menu-image, #toplevel_page_review_schema_dashboard.wp-has-current-submenu .wp-menu-image {
                    background: url(<?php echo plugins_url( '/images/star.png', __FILE__ ); ?>) no-repeat 0 -32px !important;
                }
                #star-icons-32.icon32 {background: url(<?php echo plugins_url( '/images/gray-32.png', __FILE__ ); ?>) no-repeat;}
            </style>
            <?php
        }

        public function register_review_settings() {
            $review_opt = array(
                'review_title' => __( 'Summary', 'rich-snippets' ),
                'item_reviewer' => __( 'Reviewer', 'rich-snippets' ),
                'review_date' => __( 'Review Date', 'rich-snippets' ),
                'item_name' => __( 'Reviewed Item', 'rich-snippets' ),
                'item_rating' => __( 'Author Rating', 'rich-snippets' )
                );
            add_option( 'bsf_review', $review_opt );
        }

        public function submit_request() {
            $to = "WPDeveloper.net <info@wpdeveloper.net>";
            $from = sanitize_email($_POST['email']);
            $site = esc_url($_POST['site_url']);
            $sub = sanitize_text_field($_POST['subject']);
            $message = sanitize_text_field($_POST['message']);
            $name = sanitize_text_field($_POST['name']);
            $post_url = esc_url($_POST['post_url']);

            if ( $sub == "question" ) {
                $subject = "[WPReviewSchema] New question received from " . $name;
            } else if ( $sub == "bug" ) {
                $subject = "[WPReviewSchema] New bug found by " . $name;
            } else if ( $sub == "help" ) {
                $subject = "[WPReviewSchema] New help request received from " . $name;
            } else if ( $sub == "professional" ) {
                $subject = "[WPReviewSchema] New service quote request received from " . $name;
            } else if ( $sub == "contribute" ) {
                $subject = "[WPReviewSchema] New development contribution request by " . $name;
            } else if ( $sub == "other" ) {
                $subject = "[WPReviewSchema] New contact request received from " . $name;
            }

            $html = '
            <html>
            <head><title>ARCom Review Schema Free</title></head>
            <body>
                <table width="100%" cellpadding="10" cellspacing="10">
                    <tr>
                        <th colspan="2"> ARCom Review Schema Free Support</th>
                    </tr>
                    <tr>
                        <td width="22%"> Name : </td>
                        <td width="78%"> <strong>' . $name . ' </strong></td>
                    </tr>
                    <tr>
                        <td> Email : </td>
                        <td> <strong>' . $from . ' </strong></td>
                    </tr>
                    <tr>
                        <td> Website : </td>
                        <td> <strong>' . $site . ' </strong></td>
                    </tr>
                    <tr>
                        <td> Ref. Post URL : </td>
                        <td> <strong>' . $post_url . ' </strong></td>
                    </tr>
                    <tr>
                        <td colspan="2"> Message : </td>
                    </tr>
                    <tr>
                        <td colspan="2"> ' . $message . ' </td>
                    </tr>
                </table>
            </body>
            </html>';
            $headers = 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
            $headers .= 'From:' . $name . '<' . $from . '>' . "\r\n";
            $headers .= 'Cc: Tapan Kumer Das <tapan29bd@gmail.com>' . "\r\n";
            echo mail( $to, $subject, $html, $headers ) ? "Thank you!" : "Something went wrong!";

            die();
        }

        public function submit_color() {
            $snippet_box_bg = $_POST['snippet_box_bg'];
            $snippet_title_bg = $_POST['snippet_title_bg'];
            $border_color = $_POST['snippet_border'];
            $title_color = $_POST['snippet_title_color'];
            $box_color = $_POST['snippet_box_color'];
            $color_opt = array(
                'snippet_box_bg' => $snippet_box_bg,
                'snippet_title_bg' => $snippet_title_bg,
                'snippet_border' => $border_color,
                'snippet_title_color' => $title_color,
                'snippet_box_color' => $box_color,
                );
            echo update_option( 'bsf_custom', $color_opt ) ? 'Settings saved !' : 'Error occured. Satings were not saved !';

            die();
        }

        public function iris_enqueue_scripts() {
            wp_enqueue_script( 'wp-color-picker' );
            wp_enqueue_script( 'cp_custom', plugins_url( 'js/cp-script.min.js', __FILE__ ), array('jquery', 'wp-color-picker'), '1.1', true );
            wp_enqueue_style( 'wp-color-picker' );
            wp_enqueue_style( 'jquery-style', REVIEW_META_BOX_URL . 'css/jquery-ui.css' );
        }

        public function bsf_color_scripts() {
            global $wp_version;
            $bsf_script_array = array('jquery', 'jquery-ui-core', 'jquery-ui-datepicker', 'media-upload', 'thickbox');

            // styles required for cmb
            $bsf_style_array = array('thickbox');

            // if we're 3.5 or later, user wp-color-picker
            if ( 3.5 <= $wp_version ) {
                $bsf_script_array[] = 'wp-color-picker';
                $bsf_style_array[] = 'wp-color-picker';
            } else {
                // otherwise use the older 'farbtastic'
                $bsf_script_array[] = 'farbtastic';
                $bsf_style_array[] = 'farbtastic';
            }
        }

    }

}

require_once(plugin_dir_path( __FILE__ ) . 'functions.php');
add_filter( 'bsf_meta_boxes', 'bsf_metaboxes', 10, 2 );

if ( class_exists( "WPReviewSchema" ) ) {
    $WPReviewSchema = new WPReviewSchema();
}
?>