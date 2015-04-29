<?php
global $post;
$meta_boxes = array();
$meta_boxes = apply_filters( 'bsf_meta_boxes', $meta_boxes );
foreach ( $meta_boxes as $meta_box ) {
    $my_box = new wprc_Meta_Box( $meta_box );
}

/**
 * Create meta boxes
 */
class wprc_Meta_Box {

    protected $_meta_box;

    function __construct( $meta_box ) {
        if ( !is_admin() ){
            return;
        }
        $this->_meta_box = $meta_box;
        global $pagenow;
        add_action( 'admin_menu', array(&$this, 'add') );
        add_action( 'save_post', array(&$this, 'save') );
    }

    // Add metaboxes
    function add() {
        $this->_meta_box['context'] = empty( $this->_meta_box['context'] ) ? 'normal' : $this->_meta_box['context'];
        $this->_meta_box['priority'] = empty( $this->_meta_box['priority'] ) ? 'high' : $this->_meta_box['priority'];
        $this->_meta_box['show_on'] = empty( $this->_meta_box['show_on'] ) ? array('key' => false, 'value' => false) : $this->_meta_box['show_on'];
        foreach ( $this->_meta_box['pages'] as $page ) {
            if ( apply_filters( 'bsf_show_on', true, $this->_meta_box ) )
                add_meta_box( $this->_meta_box['id'], $this->_meta_box['title'], array(&$this, 'show'), $page, $this->_meta_box['context'], $this->_meta_box['priority'] );
        }
    }

    // Show fields
    function show() {
        global $post;
        // Use nonce for verification
        echo '<input type="hidden" name="wp_meta_box_nonce" value="', wp_create_nonce( basename( __FILE__ ) ), '" />';
        echo '<table class="form-table bsf_metabox">';
        foreach ( $this->_meta_box['fields'] as $field ) {
            // Set up blank or default values for empty ones
            if ( !isset( $field['name'] ) )
                $field['name'] = '';
            if ( !isset( $field['desc'] ) )
                $field['desc'] = '';
            if ( !isset( $field['std'] ) )
                $field['std'] = '';
            if ( 'file' == $field['type'] && !isset( $field['allow'] ) )
                $field['allow'] = array('url', 'attachment');
            if ( 'file' == $field['type'] && !isset( $field['save_id'] ) )
                $field['save_id'] = false;
            if ( 'multicheck' == $field['type'] )
                $field['multiple'] = true;
            $meta = get_post_meta( $post->ID, $field['id'], 'multicheck' != $field['type'] /* If multicheck this can be multiple values */ );
            echo '<tr class="', $field['class'], '">';
            if ( $field['type'] == "title" ) {
                echo '<td colspan="2">';
            } else {
                if ( $this->_meta_box['show_names'] == true ) {
                    echo '<th style="width:18%"><label class="', $field['class'], '" for="', $field['id'], '">', $field['name'], '</label></th>';
                }
                echo '<td>';
            }

            switch ( $field['type'] ) {
                case 'text':
                    echo '<input class="', $field['class'], '" type="text" name="', $field['id'], '" id="', $field['id'], '" value="', '' !== $meta ? $meta : '', '" />', '<p class="bsf_metabox_description ', $field['class'], '">', $field['desc'], '</p>';
                    break;
                case 'text_medium':
                    echo '<input class="bsf_text_medium ', $field['class'], '" type="text" name="', $field['id'], '" id="', $field['id'], '" value="', '' !== $meta ? $meta : $field['std'], '" /><span class="bsf_metabox_description ', $field['class'], '">', $field['desc'], '</span>';
                    break;
                case 'date':
                    echo '<input class="bsf_text_medium ', $field['class'], '" type="text" name="', $field['id'], '" id="', $field['id'], '" value="', '' !== $meta ? $meta : $field['std'], '" />';
                    break;
                case 'select':
                    if ( empty( $meta ) && !empty( $field['std'] ) )
                        $meta = $field['std'];
                    echo '<select class="', $field['class'], '" name="', $field['id'], '" id="', $field['id'], '">';
                    foreach ( $field['options'] as $option ) {
                        echo '<option class="', $field['class'], '" value="', $option['value'], '"', $meta == $option['value'] ? ' selected="selected"' : '', '>', $option['name'], '</option>';
                    }
                    echo '</select>';
                    echo '<p class="bsf_metabox_description ', $field['class'], '">', $field['desc'], '</p>';
                    break;
                case 'radio':
                    if ( empty( $meta ) && !empty( $field['std'] ) )
                        $meta = $field['std'];
                    echo '<div class="', $field['class'], '"><ul>';
                    $i = 1;
                    foreach ( $field['options'] as $option ) {
                        if ( $field['class'] == "star review" || $field['class'] == "star product" || $field['class'] == "star software" )
                            $class = "star";
                        else
                            $class = $field['class'];
                        echo '<li class="', $field['class'], '">
                              <input class="', $class, '" type="radio" name="', $field['id'], '" id="', $field['id'], $i, '" value="', $option['value'], '"', $meta == $option['value'] ? ' checked="checked"' : '', ' /><label class="', $field['class'], '" for="', $field['id'], $i, '">', $option['name'] . '</label>				</li>';
                        $i++;
                    }
                    echo '</ul></div>';
                    echo '<p class="bsf_metabox_description ', $field['class'], '">', $field['desc'], '</p>';
                    break;
                case 'checkbox':
                    echo '<input type="checkbox" class="', $field['class'], '" name="', $field['id'], '" id="', $field['id'], '"', $meta ? ' checked="checked"' : '', ' />';
                    echo '<span class="bsf_metabox_description ', $field['class'], '">', $field['desc'], '</span>';
                    break;
                case 'title':
                    echo '<h5 class="bsf_metabox_title ', $field['class'], '">', $field['name'], '</h5>';
                    echo '<p class="bsf_metabox_description ', $field['class'], '">', $field['desc'], '</p>';
                    break;
                default:
                    do_action( 'bsf_render_' . $field['type'], $field, $meta );
            }
            echo '</td>', '</tr>';
        }
        echo '</table>';
    }

    // Save data from metabox
    function save( $post_id ) {
        // verify nonce
        if ( !isset( $_POST['wp_meta_box_nonce'] ) || !wp_verify_nonce( $_POST['wp_meta_box_nonce'], basename( __FILE__ ) ) ) {
            return $post_id;
        }
        // check autosave
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return $post_id;
        }
        // check permissions
        if ( 'page' == $_POST['post_type'] ) {
            if ( !current_user_can( 'edit_page', $post_id ) ) {
                return $post_id;
            }
        } elseif ( !current_user_can( 'edit_post', $post_id ) ) {
            return $post_id;
        }
        foreach ( $this->_meta_box['fields'] as $field ) {
            $name = $field['id'];
            if ( !isset( $field['multiple'] ) )
                $field['multiple'] = ( 'multicheck' == $field['type'] ) ? true : false;
            $old = get_post_meta( $post_id, $name, !$field['multiple'] /* If multicheck this can be multiple values */ );
            $new = isset( $_POST[$field['id']] ) ? $_POST[$field['id']] : null;
            if ( in_array( $field['type'], array('taxonomy_select', 'taxonomy_radio', 'taxonomy_multicheck') ) ) {
                $new = wp_set_object_terms( $post_id, $new, $field['taxonomy'] );
            }
            if ( ($field['type'] == 'textarea') || ($field['type'] == 'textarea_small') ) {
                $new = htmlspecialchars( $new );
            }
            if ( ($field['type'] == 'textarea_code' ) ) {
                $new = htmlspecialchars_decode( $new );
            }
            if ( $field['type'] == 'text_date_timestamp' ) {
                $new = strtotime( $new );
            }
            if ( $field['type'] == 'text_datetime_timestamp' ) {
                $string = $new['date'] . ' ' . $new['time'];
                $new = strtotime( $string );
            }
            $new = apply_filters( 'bsf_validate_' . $field['type'], $new, $post_id, $field );
            // validate meta value
            if ( isset( $field['validate_func'] ) ) {
                $ok = call_user_func( array('bsf_Meta_Box_Validate', $field['validate_func']), $new );
                if ( $ok === false ) { // pass away when meta value is invalid
                    continue;
                }
            } elseif ( $field['multiple'] ) {
                delete_post_meta( $post_id, $name );
                if ( !empty( $new ) ) {
                    foreach ( $new as $add_new ) {
                        add_post_meta( $post_id, $name, $add_new, false );
                    }
                }
            } elseif ( '' !== $new && $new != $old ) {
                update_post_meta( $post_id, $name, $new );
            } elseif ( '' == $new ) {
                delete_post_meta( $post_id, $name );
            }
            if ( 'file' == $field['type'] ) {
                $name = $field['id'] . "_id";
                $old = get_post_meta( $post_id, $name, !$field['multiple'] /* If multicheck this can be multiple values */ );
                if ( isset( $field['save_id'] ) && $field['save_id'] ) {
                    $new = isset( $_POST[$name] ) ? $_POST[$name] : null;
                } else {
                    $new = "";
                }
                if ( $new && $new != $old ) {
                    update_post_meta( $post_id, $name, $new );
                } elseif ( '' == $new && $old ) {
                    delete_post_meta( $post_id, $name, $old );
                }
            }
        }
    }

}

/**
 * Adding scripts and styles
 */
function wprc_scripts( $hook ) {
    global $wp_version;
    // only enqueue our scripts/styles on the proper pages
    if ( $hook == 'post.php' || $hook == 'post-new.php' || $hook == 'page-new.php' || $hook == 'page.php' ) {
        // scripts required for cmb
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
        wp_register_script( 'bsf-timepicker', REVIEW_META_BOX_URL . 'js/jquery.timePicker.min.js' );
        wp_register_script( 'bsf-scripts', REVIEW_META_BOX_URL . 'js/cmb.js', $bsf_script_array, '0.9.1' );
        wp_localize_script( 'bsf-scripts', 'bsf_ajax_data', array('ajax_nonce' => wp_create_nonce( 'ajax_nonce' ), 'post_id' => get_the_ID()) );
        wp_enqueue_script( 'bsf-timepicker' );
        wp_enqueue_script( 'bsf-scripts' );
        wp_register_style( 'bsf-styles', REVIEW_META_BOX_URL . 'admin/css/style.css', $bsf_style_array );
        wp_enqueue_style( 'bsf-styles' );
    }
}

add_action( 'admin_enqueue_scripts', 'wprc_scripts', 10 );