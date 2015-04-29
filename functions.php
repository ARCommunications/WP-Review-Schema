<?php

add_action( 'init', 'wprc_initialize_review_meta_boxes', 9999 );
/**
 * Initialize the metabox class.
 */
/* FUNCTION to check for posts having snippets */
add_action( 'wp_head', 'wprc_check_snippet_existence', '', 7 );

function wprc_check_snippet_existence() {
    add_action( 'wp_head', 'wprc_frontend_style' );
    add_action( 'wp_enqueue_scripts', 'wprc_enque' );
}

function wprc_enque() {
    wp_enqueue_style( 'rating_style', plugin_dir_url( __FILE__ ) . 'css/jquery.rating.css' );
    wp_enqueue_script( 'jquery_rating', plugin_dir_url( __FILE__ ) . 'js/jquery.rating.min.js', array('jquery') );
}

function wprc_frontend_style() {
    wp_register_style( 'review_style', plugins_url( '/css/style.css', __FILE__ ) );
    wp_enqueue_style( 'review_style' );
}

function wprc_initialize_review_meta_boxes() {
    if ( !class_exists( 'bsf_Meta_Box' ) ) {
        require_once plugin_dir_path( __FILE__ ) . 'init.php';
    }
}

//Function to display the ultimate schema output below the content
function wprc_display_rich_snippet( $content ) {
    global $post;

    $args_color = get_option( 'bsf_custom' );
    $id = $post->ID;
    $type = get_post_meta( $id, '_bsf_post_type', true );

    $args_review = get_option( 'bsf_review' );
    //$args_default = get_option( 'bsf_default' );
    $item = get_post_meta( $post->ID, '_bsf_item_name', true );
    $rating = get_post_meta( $post->ID, '_bsf_rating', true );
    $reviewer = get_post_meta( $post->ID, '_bsf_item_reviewer', true );
    $review_date = get_post_meta( $post->ID, '_bsf_item_date', true );
    $post_date = get_the_date( 'Y-m-d', $post->ID );

    $review = $content;
    $review .= '<div id="snippet-box" style="background:' . $args_color["snippet_box_bg"] . '; color:' . $args_color["snippet_box_color"] . '; border:1px solid ' . $args_color["snippet_border"] . ';">';

    if ( $args_review['review_title'] != "" ) {
        $review .= '<div class="snippet-title" style="background:' . $args_color["snippet_title_bg"] . '; color:' . $args_color["snippet_title_color"] . '; border-bottom:1px solid ' . $args_color["snippet_border"] . ';">' . $args_review['review_title'] . '</div>';
    }else{
        $review .= '<div class="snippet-title" style="background:' . $args_color["snippet_title_bg"] . '; color:' . $args_color["snippet_title_color"] . '; border-bottom:1px solid ' . $args_color["snippet_border"] . ';">Review Details</div>';
    }
    $review .= '<div class="snippet-markup" itemscope itemtype="http://data-vocabulary.org/Review">'; //Open Review Snippet Div
    if ( trim( $reviewer ) != "" ) {
        if ( $args_review['item_reviewer'] != "" ) {
            $review .= "<div class='snippet-label'>" . $args_review['item_reviewer'] . "</div>";
        }else{
            $review .= "<div class='snippet-label'>Reviewer</div>";
        }
        $review .= " <div class='snippet-data'><span itemprop='reviewer'>" . $reviewer . "</span></div>";
    }
    if ( trim( $review_date ) != "" ) {
        if ( $args_review['review_date'] != "" ) {
            $review .= "<div class='snippet-label'>" . $args_review['review_date'] . "</div>";
        }else{
            $review .= "<div class='snippet-label'>Review Date</div>";
        }
        $review .= "<div class='snippet-data'> <time itemprop='dtreviewed' datetime='" . $review_date . "'>" . $review_date . "</time></div>";
    }
    if ( trim( $item ) != "" ) {
        if ( $args_review['item_name'] != "" ) {
            $review .= "<div class='snippet-label'>" . $args_review['item_name'] . "</div>";
        }else{
            $review .= "<div class='snippet-label'>Review Item</div>";
        }
        $review .= "<div class='snippet-data'> <span itemprop='itemreviewed'>" . $item . "</span></div>";
    }
    if ( trim( $rating ) != "" ) {
        if ( $args_review['item_rating'] != "" ) {
            $review .= "<div class='snippet-label'>" . $args_review['item_rating'] . "</div>";
        }else{
            $review .= "<div class='snippet-label'>Rating</div>";
        }
        $review .= "<div class='snippet-data'> <span class='rating-value' itemprop='rating'>" . $rating . "</span><span class='star-img'>";
        for ( $i = 1; $i <= ceil( $rating ); $i++ ) {
            $review .= '<img src="' . plugin_dir_url( __FILE__ ) . 'images/1star.png">';
        }
        for ( $j = 0; $j <= 5 - ceil( $rating ); $j++ ) {
            if ( $j ) {
                $review .= '<img src="' . plugin_dir_url( __FILE__ ) . 'images/gray.png">';
            }
        }
        $review .= '</span></div>';
    }

    $url = get_permalink( $id );
    $excerpt = wp_trim_words( $post->post_content );

    $review .= "<div style='display:none'>"; //Open Review hidden snippet div
    $review .= '<span itemprop="description">' . $excerpt . '</span>';
    //Meta content
    $review .= '<meta content="' . $post->post_title . '" property="og:title" />';
    $review .= '<meta content="website" property="og:type" />';
    $review .= '<meta content="' . $url . '" property="og:url" />';
    $review .= '<meta content="' . $excerpt . '" property="og:description" />';
    $review .= '<meta content="' . get_bloginfo() . '" property="og:site_name" />';
    //Meta Content
    $review .= '</div>'; //Close Review hidden snippet div
    $review .= '</div>'; //Close review snippet (class="snippet-markup")

    $review .= "<div style='display:none'>"; //Open hidden snippet div

    $review .= '<div itemscope itemprop="aggregateRating" itemtype="http://schema.org/AggregateRating">'; //Open Rating div
    $c_rating = !empty( $rating ) ? $rating : 5;
    $review .= '<meta itemprop="ratingValue" content="' . $c_rating . '" />'; //RagingValue
    $review .= '<meta itemprop="ratingCount" content="1" />'; //RagingValue
    $review .= '<meta itemprop="bestRating" content="' . $c_rating . '" />'; //RagingValue
    $review .= '</div>'; //Close Rating div
    $review .= '</div>'; //Close hidden snippet div

    $review .= "</div><div style='clear:both;'></div>"; //Close main snippet box (id="snippet-box") and clear div

    return ( is_single() || is_page() ) ? $review : $content;
}

//Filter the content and return with rich snippet output
add_filter( 'the_content', 'wprc_display_rich_snippet', 100 );

function bsf_metaboxes( array $meta_boxes ) {
    // Start with an underscore to hide fields from custom fields list
    $prefix = '_bsf_';
    $post_types = get_post_types( '', 'names' );

    $meta_boxes[] = array(
        'id' => 'review_metabox',
        'title' => __( 'Configure Rich Snippet', 'ultimate-schema' ),
        'pages' => $post_types, //array( 'post','page' ), // Custom Post types
        'context' => 'normal',
        'priority' => 'high',
        'show_names' => true, // Show field names on the left
        'fields' => array(
            // Meta Settings for Item Review
            array(
                'name' => __( 'Rich Snippets - Item Review', 'ultimate-schema' ),
                'desc' => __( 'Please provide the following information.', 'ultimate-schema' ),
                'id' => $prefix . 'review_title',
                'class' => 'review',
                'type' => 'title',
            ),
            array(
                'name' => __( 'Reviewer&rsquo;s Name ', 'ultimate-schema' ),
                'desc' => __( 'Enter the name of Item Reviewer or The Post Author.', 'ultimate-schema' ),
                'id' => $prefix . 'item_reviewer',
                'class' => 'review',
                'type' => 'text_medium',
                'std' => '',
            ),
            array(
                'name' => __( 'Item to be reviewed', 'ultimate-schema' ),
                'desc' => __( 'Enter the name of the item, you are writing review about.', 'ultimate-schema' ),
                'id' => $prefix . 'item_name',
                'class' => 'review',
                'type' => 'text',
                'std' => '',
            ),
            array(
                'name' => __( 'Review Date', 'ultimate-schema' ),
                'desc' => __( '', 'ultimate-schema' ),
                'id' => $prefix . 'item_date',
                'class' => 'schema-datepicker review',
                'type' => 'date',
            ),
            array(
                'name' => __( 'Your Rating', 'ultimate-schema' ),
                'desc' => __( '&nbsp;&nbsp;Rate this item (1-5)', 'ultimate-schema' ),
                'id' => $prefix . 'rating',
                'class' => 'star review',
                'type' => 'radio',
                'options' => array(
                    array('name' => __( '', 'ultimate-schema' ), 'value' => '1',),
                    array('name' => __( '', 'ultimate-schema' ), 'value' => '2',),
                    array('name' => __( '', 'ultimate-schema' ), 'value' => '3',),
                    array('name' => __( '', 'ultimate-schema' ), 'value' => '4',),
                    array('name' => __( '', 'ultimate-schema' ), 'value' => '5',),
                ),
                'std' => 0,
            ),
        ),
    );
    return $meta_boxes;
}