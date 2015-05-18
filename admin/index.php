<?php
// Start object buffering to supress warning messages
ob_start();
if ( is_admin() ) {
    add_action( 'admin_footer', 'wprc_add_footer_script' );
}

//enqueues the scripts and styles in admin dashboard
function review_schema_admin_styles() {
    wp_enqueue_style( 'star_style' );
    wp_enqueue_style( 'meta_style' );
    wp_enqueue_script( 'jquery-ui-core' );
    wp_enqueue_script( 'jquery-ui-widget' );
    wp_enqueue_script( 'jquery-ui-mouse' );
    wp_enqueue_script( 'jquery-ui-accordion' );
    wp_enqueue_script( 'jquery-ui-autocomplete' );
    wp_enqueue_script( 'jquery-ui-slider' );
    wp_enqueue_script( 'jquery-ui-tabs' );
    wp_enqueue_script( 'jquery-ui-sortable' );
    wp_enqueue_script( 'jquery-ui-draggable' );
    wp_enqueue_script( 'jquery-ui-droppable' );
    wp_enqueue_script( 'jquery-ui-datepicker' );
    wp_enqueue_script( 'jquery-ui-resize' );
    wp_enqueue_script( 'jquery-ui-dialog' );
    wp_enqueue_script( 'jquery-ui-button' );
    wp_enqueue_script('jquery_ui');
    wp_enqueue_script( 'bsf_jquery_star' );
}

function wprc_add_the_script() {
    wp_enqueue_script( 'postbox' );
    wp_enqueue_style( 'wprc_admin_style' );
}
add_action( 'admin_print_scripts', 'wprc_add_the_script' );

//The Main Admin Dashboard for Review Schema
function review_schema_dashboard() {
    $plugins_url = plugins_url();
    $args_review = get_option( 'bsf_review' );
    $default = get_option( 'bsf_default' );
    $args_color = get_option( 'bsf_custom' );
    ?>
    <div class="wrap">
        <div id="star-icons-32" class="icon32"></div><h2>WP Review Schema Free - Dashboard</h2>
        <div class="clear"></div>
        <div class="postbox" style=" width: 36%; float: right; ">
        <center><a target="_blank" href="https://wpdeveloper.net/"><img src="<?php echo WPRS_PLUGIN_URL."/wpdeveloper-logo-2.png" ?>" alt="WPDeveloper" /></a>
</center>
            <h3 class="get_in_touch"><p>Get in touch with the <a href="https://wpdeveloper.net/" target="_blank"><b>WPDeveloper.net</b></a></p></h3>
            <div class="inside">
                <form name="support" id="support_form" action="" method="post" onsubmit="return false;">
                    <p>Just fill out the form below and your message will be emailed to the Plugin Developers.</p>
                    <table class="bsf_metabox" > <input type="hidden" name="site_url" value="<?php echo site_url(); ?>" />
                        <tr>
                            <td><label for="name"><strong>Your Name:<span style="color:red;"> *</span></strong> </label></td>
                            <td><input required="required" type="text" class="bsf_text_medium" name="name" /></td>
                        </tr>
                        <tr>
                            <td><label for="email"><strong>Your Email:<span style="color:red;"> *</span></strong> </label></td>
                            <td><input required="required" type="text" class="bsf_text_medium" name="email" /></td>
                        </tr>
                        <tr>
                            <td><label for="post_url"><strong>Reference URL:<span style="color:red;"> *</span></strong> </label></td>
                            <td><input required="required" type="text" class="bsf_text_medium" name="post_url" /></td>
                        </tr>
                        <tr>
                            <td><label for="subject"><strong>Subject:</strong> </label></td>
                            <td>
                                <select class="select_full" name="subject">
                                    <option value="question"> I have a question </option>
                                    <option value="bug"> I found a bug </option>
                                    <option value="help"> I need help </option>
                                    <option value="professional">  I need professional service </option>
                                    <option value="contribute"> I want to contribute my code</option>
                                    <option value="other">  Other </option>
                                </select>
                            </td>
                            <td></td>
                        </tr>
                        <tr>
                            <td class="bsf_label"><label for="message"><strong>Your Query in Brief:</strong> </label></td>
                            <td rowspan="4"><textarea class="bsf_textarea_small" name="message"></textarea> </td>
                        </tr>
                        <tr></tr>
                        <tr></tr>
                        <tr></tr>
                        <tr>
                            <td></td>
                            <td><input id="submit_request" class="button-primary" type="submit" value="Submit Request" /> <span id="status"></span></td>
                        </tr>
                    </table>
                </form>
            </div>
        </div>
        <div id="tab-container" class="tab-container">
            <ul class="etabs">
                <li class="tab"><a href="#tab-1">Default Value</a></li>
                <li class="tab"><a href="#tab-2">Configuration</a></li>
                <li class="tab"><a href="#tab-3">Customization</a></li>
                <li class="tab"><a href="#tab-4">FAQs</a></li>
            </ul>

            <div class="clear"></div>
            <div class="panel-container">

                <!-- Tab 1-->
                <div id="tab-1">
                    <div id="poststuff">
                        <div id="postbox-container-1" class="postbox-container">
                            <div class="meta-box-sortables ui-sortable">
                                <div class="postbox ">
                                    <div class="handlediv" title="Click to toggle"><br></div>
                                    <h3 class="hndle"><span>Default Value for Item Review (<a href="https://wpdeveloper.net/go/WPRS" target="_blank"><b>available as Pro version</b></a>)</span></h3>
                                    <div class="inside">
                                        <div class="table">
                                            <form id="bsf_review_form" method="post">
                                                <table class="bsf_metabox">
                                                    <tbody>
                                                        <tr>
                                                            <th align="right" width="50%">Reviewer Name:</th>
                                                            <td>
                                                                <input class="bsf_text_medium" type="text" name="default[reviewer_name]" disabled="disabled" value="<?php echo $default['reviewer_name']; ?>"/><br>
                                                                (<a href="https://wpdeveloper.net/go/WPRS" target="_blank"><b>available as Pro version</b></a>)
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th align="right">Item Name:</th>
                                                            <td>
                                                                <input class="bsf_text_medium" type="text" name="default[item_name]" disabled="disabled" value="<?php echo $default["item_name"]; ?>"/><br>
                                                                (<a href="hhttps://wpdeveloper.net/go/WPRS" target="_blank"><b>available as Pro version</b></a>)
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th align="right">Item Ratings:</th>
                                                            <td>
                                                                <input class="bsf_text_medium" type="text" name="default[item_rating]" disabled="disabled" value="<?php echo $default["item_rating"]; ?>"/><br>
                                                                (<a href="https://wpdeveloper.net/go/WPRS" target="_blank"><b>available as Pro version</b></a>)
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th align="right">Image Selection:</th>
                                                            <td>
                                                                <input <?php echo isset( $default['use_image_from'] ) && $default['use_image_from'] == 'featured_image' ? 'checked' : (!isset( $default['use_image_from'] ) ? 'checked' : '' ) ?> type="radio" name="default[use_image_from]" disabled="disabled" id="use_image_from_1" onclick="hide_custom_field_row()" value="featured_image" /> <label> Featured image </label><br />
                                                                <input <?php echo isset( $default['use_image_from'] ) && $default['use_image_from'] == 'first_image' ? 'checked' : '' ?> type="radio" name="default[use_image_from]" disabled="disabled" id="use_image_from_2" onclick="hide_custom_field_row()"  value="first_image" /> <label>First image from content</label><br />
                                                                <input <?php echo isset( $default['use_image_from'] ) && $default['use_image_from'] == 'custom_field' ? 'checked' : '' ?> type="radio" name="default[use_image_from]" disabled="disabled" id="use_image_from_3" onclick="show_custom_field_row()" value="custom_field" /> <label> From a custom field </label><br>
                                                                (<a href="https://wpdeveloper.net/go/WPRS" target="_blank"><b>available as Pro version</b></a>)
                                                            </td>
                                                        </tr>
                                                        <tr id="image_custom_field" <?php if ( !isset( $default['use_image_from'] ) || $default['use_image_from'] != 'custom_field' ) { ?>style="display: none;"<?php } ?>>
                                                            <th align="right">Image Custom Field Name:</th>
                                                            <td>
                                                                <input type="text" name="default[image_custom_field]" disabled="disabled" value="<?php echo isset( $default['image_custom_field'] ) && $default['image_custom_field'] != '' ? $default['image_custom_field'] : '' ?>" size="20" />
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th align="right">Price:</th>
                                                            <td>
                                                                <input <?php echo isset( $default['price'] ) && $default['price'] == 'fixed_price' ? 'checked' : (!isset( $default['price'] ) ? 'checked' : '' ) ?> type="radio" name="default[price]" disabled="disabled" onclick="hide_custom_price_row()" value="fixed_price" /> <label> Default price</label><br />
                                                                <input <?php echo isset( $default['price'] ) && $default['price'] == 'custom_price' ? 'checked' : '' ?> type="radio" name="default[price]" disabled="disabled" onclick="hide_fixed_price_row()" value="custom_price" /> <label> From a custom field</label><br>
                                                                (<a href="https://wpdeveloper.net/go/WPRS" target="_blank"><b>available as Pro version</b></a>)
                                                            </td>
                                                        </tr>
                                                        <tr id="fixed_price" <?php if ( isset( $default['price'] ) && $default['price'] != 'fixed_price' ) { ?>style="display: none;"<?php } ?>>
                                                            <th align="right">Fixed Price:</th>
                                                            <td>
                                                                <input type="text" name="default[price_default]" disabled="disabled" value="<?php echo isset( $default['price_default'] ) && $default['price_default'] != '' ? $default['price_default'] : '' ?>" size="20" /><br>
                                                                (<a href="https://wpdeveloper.net/go/WPRS" target="_blank"><b>available as Pro version</b></a>)
                                                            </td>
                                                        </tr>
                                                        <tr id="custom_price" <?php if ( !isset( $default['price'] ) || $default['price'] != 'custom_price' ) { ?>style="display: none;"<?php } ?>>
                                                            <th align="right">Custom Price Meta Field:</th>
                                                            <td>
                                                                <input type="text" name="default[price_custom]" disabled="disabled" value="<?php echo isset( $default['price_custom'] ) && $default['price_custom'] != '' ? $default['price_custom'] : '' ?>" size="20" />
                                                            </td>
                                                        </tr>
                                                        <tr><td colspan="2"></td></tr>
                                                        <tr>
                                                            <td></td>
                                                            <td>
                                                                <input type="submit" class="button-primary" name="default_submit" value="Update"/>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tab 2-->
                <div id="tab-2">
                    <div id="poststuff">
                        <div id="postbox-container-2" class="postbox-container">
                            <div class="meta-box-sortables ui-sortable">
                                <div class="postbox ">
                                    <div class="handlediv" title="Click to toggle"><br></div>
                                    <h3 class="hndle"><span>Item Review</span></h3>
                                    <div class="inside">
                                        <div class="table">
                                            <p>Strings to be displayed on frontend for <strong>Item Review Rich Snippets &mdash;</strong></p>
                                            <form id="bsf_review_form" method="post">
                                                <table class="bsf_metabox">
                                                    <tbody>
                                                        <tr>
                                                            <td align="right"><strong><label>Rich Snippet Title:</label></strong></td>
                                                            <td><input class="bsf_text_medium" type="text" name="review_title" value="<?php echo $args_review["review_title"]; ?>"/></td>
                                                        </tr>
                                                        <tr>
                                                            <td align="right"><strong><label>Reviewer:</label></strong></td>
                                                            <td><input class="bsf_text_medium" type="text" name="item_reviewer" value="<?php echo $args_review["item_reviewer"]; ?>"/></td>
                                                        </tr>
                                                        <tr>
                                                            <td align="right"><strong><label>Review Date:</label></strong></td>
                                                            <td><input class="bsf_text_medium" type="text" name="review_date" value="<?php echo $args_review["review_date"]; ?>"/></td>
                                                        </tr>
                                                        <tr>
                                                            <td align="right"><strong><label>Item Name:</label></strong></td>
                                                            <td><input class="bsf_text_medium" type="text" name="item_name" value="<?php echo $args_review["item_name"]; ?>"/></td>
                                                        </tr>
                                                        <tr>
                                                            <td align="right"><strong><label>Item Ratings:</label></strong></td>
                                                            <td><input class="bsf_text_medium" type="text" name="item_rating" value="<?php echo $args_review["item_rating"]; ?>"/></td>
                                                        </tr>
                                                        <tr><td colspan="2"></td></tr>
                                                        <tr>
                                                            <td></td>
                                                            <td><input type="submit" class="button-primary" name="item_submit" value="Update"/></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tab 3-->
                <div id="tab-3">
                    <div id="poststuff">
                        <div id="postbox-container-3" class="postbox-container">
                            <div class="meta-box-sortables ui-sortable">
                                <div class="postbox">
                                    <div class="handlediv" title="Click to toggle"><br></div>
                                    <h3 class="hndle"><span>Customize the look and feel of rich snippet box</span></h3>
                                    <div class="inside">
                                        <form id="bsf_css_editor" method="post" onsubmit="return false;" action="">
                                            <table class="bsf_metabox">
                                                <tr>
                                                    <th> <label for="snippet_box_bg">Box Background</label> </th>
                                                    <td> <input type="text" name="snippet_box_bg" id="snippet_box_bg" value="<?php echo $args_color["snippet_box_bg"]; ?>"  class="snippet_box_bg" /> </td>
                                                </tr>
                                                <tr>
                                                    <th> <label for="snippet_title_bg">Title Background</label> </th>
                                                    <td> <input type="text" name="snippet_title_bg" id="snippet_title_bg" value="<?php echo $args_color["snippet_title_bg"]; ?>"  class="snippet_title_bg" /> </td>
                                                </tr>
                                                <tr>
                                                    <th> <label for="snippet_border">Border Color</label> </th>
                                                    <td> <input type="text" name="snippet_border" id="snippet_border" value="<?php echo $args_color["snippet_border"]; ?>"  class="snippet_border" /> </td>
                                                </tr>
                                                <tr>
                                                    <th> <label for="snippet_title_color">Title Color</label> </th>
                                                    <td> <input type="text" name="snippet_title_color" id="snippet_title_color" value="<?php echo $args_color["snippet_title_color"]; ?>"  class="snippet_title_color" /> </td>
                                                </tr>
                                                <tr>
                                                    <th> <label for="snippet_box_color">Snippet Text Color</label> </th>
                                                    <td> <input type="text" name="snippet_box_color" id="snippet_box_color" value="<?php echo $args_color["snippet_box_color"]; ?>"  class="snippet_box_color" /> </td>
                                                </tr>
                                                <tr>
                                                    <td></td>
                                                    <td><input id="submit_colors" class="button-primary" type="submit" value="Update Colors" /></td>
                                                </tr>
                                            </table>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tab 4-->
                <div id="tab-4">
                    <div id="poststuff">
                        <div id="postbox-container-4" class="postbox-container">
                            <div class="meta-box-sortables ui-sortable">
                                <div class="postbox">
                                    <div class="handlediv" title="Click to toggle"><br></div>
                                    <h3 class="hndle"><span>Plugins FAQs</span></h3>
                                    <div class="inside">
                                        <ol>
                                            <li><strong>Where I can see preview of my search results?</strong></li>
                                            <p>Here: <a href="http://www.google.com/webmasters/tools/richsnippets">http://www.google.com/webmasters/tools/richsnippets</a></p>
                                            <li><strong>Do I have to fill in all the details?</strong></li>
                                            <p>No. Though some fields are mandatory and required to by Google in order to display rich snippet.</p>
                                            <li><strong>Why does the plugin create extra content at the end of my page / post? Can I simply hide / customise it? It's messing my design!</strong></li>
                                            <p>We understand you don't like the content that gets displayed on your page / post. However as per the strong recommendation of Google, the MicroData should be clearly visible to the user.</p>
                                            <p>Here is a reference link of what Google says. <a href="https://sites.google.com/site/webmasterhelpforum/en/faq-rich-snippets#display"> https://sites.google.com/site/webmasterhelpforum/en/faq-rich-snippets#display</a></p>
                                            
                                            <li><strong>How does this plugin work with other plugins like WordPress SEO, wooCommerce, etc?</strong></li>
                                            <p>Well, the plugin works perfectly with most of the other plugins as the only thing "WP Review Schema" does is - it give you power to add Rich Snippets MicroData in your pages and posts easily. <br><br>If you find any it conflicting with any other plugin, please do not hesitate to report an issue.</p>
                                            <li><strong>How much time does it take to show up rich snippets for my search results? My search results are still not coming up with rich snippets.</strong></li>
                                            <p>Most probably rich snippets are displayed in for you search results as soon as search engines crawl the MicroData the plugin has created. However it's totally upto search engines to display rich snippets for your search result (which mostly depends on your website authority)</p>
                                            <p>If rich snippets are not appearing in your search results yet, most probably they will start appearing soon as Google / other search engines finds your website more authoritative.</p>
                                            <p>Meanwhile - you can validate and see preview of your rich snippets on <a href="http://www.google.com/webmasters/tools/richsnippets">[Google Structured Data Testing Tool here]</a> .</p>

                                            <li><strong>I don't see the feature I want. How can I get it?</strong></li>
                                            <p>[Get in touch] with us to ask if this feature is in our development roadmap. If it is not in our roadmap, and if you still think this feature would make the plugin to better, we have a couple of options for you -</p>
                                            <ol>
                                                <li>Code the new feature if you are a developer and submit your code. If we include this feature in our releases, credits will be given to you.</li>
                                                <li>Offer a sponsorship to get this feature done for all plugin users OR request a professional customisation service.</li>
                                            </ol>
                                            <li><strong>Is Google Authorship part of your plugin as well?</strong></li>
                                            <p>Unfortunately, not at the moment. Though this is definitely in our roadmap and the development will complete soon. Stay tuned!</p>
                                        </ol>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
    <?php $adminPath = plugin_dir_url(__FILE__); ?>
    <script src="<?php echo $adminPath . 'js/jquery.easytabs.min.js'; ?>"></script>
    <script src="<?php echo $adminPath . 'js/jquery.hashchange.min.js'; ?>"></script>
    <script language="javascript">
        jQuery("#tab-container").easytabs();
        jQuery("#postbox-container-1").css({"width": "85%", "padding-right": "2%"});
        jQuery("#postbox-container-2").css({"width": "85%", "padding-right": "2%"});
        jQuery("#postbox-container-3").css({"width": "85%", "padding-right": "2%"});
        jQuery("#postbox-container-4").css({"width": "85%", "padding-right": "2%"});
        jQuery(".postbox h3").click(function () {
            jQuery(jQuery(this).parent().get(0)).toggleClass("closed");
        });
        jQuery(".handlediv").click(function () {
            jQuery(jQuery(this).parent().get(0)).toggleClass("closed");
        });
    </script>
    <?php
}

// Update options for review box name
if ( isset( $_POST['item_submit'] ) ) {
    foreach ( array('review_title', 'item_reviewer', 'review_date', 'item_name', 'item_rating') as $option ) {
        if ( isset( $_POST[$option] ) ) {
            $args[$option] = $_POST[$option];
        }
    }
    $status = update_option( 'bsf_review', $args );
    displayStatus( $status );
}

// Update options for default value
if ( isset( $_POST['default_submit'] ) ) {
    $status = update_option( 'bsf_default', $_POST['default'] );
    displayStatus( $status );
}

// Show message
function displayStatus( $status ) {
    if ( $status ) {
        echo '<div class="updated"><p>' . __( 'Success! Your changes were successfully saved!', 'ultimate-schema' ) . '</p></div>';
    } else {
        echo '<div class="error"><p>' . __( 'Sorry, Your changes are not saved!', 'ultimate-schema' ) . '</p></div>';
    }
}

if ( isset( $_GET['action'] ) ) {
    if ( $_GET['action'] == 'reset' ) {
        $option_to_reset = $_GET['options'];
        if ( $option_to_reset == 'review' ) delete_option( 'bsf_review' );

        if ( $option_to_reset == 'color' ) delete_option( 'bsf_custom' );

        wprc_reset_options( $option_to_reset );
    }
}

function wprc_reset_options( $option_to_reset ) {
    //require_once(dirname( __FILE__ ) . '/../settings.php');
    if ( $option_to_reset == 'review' ) add_review_option();

    if ( $option_to_reset == 'color' ) add_color_option();

    header( "location:?page=ultimate_schema_dashboard" );
}

function wprc_add_footer_script() {
    $admin = get_current_screen();
    if ( $admin->parent_base != 'review_schema_dashboard' && $admin->parent_base != 'edit' ) {
        return;
    }
    ?>
    <script type="text/javascript">
        jQuery(document).ready(function () {
            jQuery("#submit_colors").click(function ()
            {
                var data = jQuery("#bsf_css_editor").serialize();
                var form_data = "action=bsf_submit_color&" + data;
                //alert(form_data);
                jQuery.post(ajaxurl, form_data,
                    function (response) {
                        alert(response);
                    }
                    );
            });
            jQuery("#support_form").submit(function ()
            {
                var data = jQuery("#support_form").serialize();
                var form_data = "action=bsf_submit_request&" + data;
                // alert(form_data);
                jQuery.post(ajaxurl, form_data,
                    function (response) {
                        alert(response);
                        jQuery("#support_form .bsf_text_medium, #support_form .bsf_textarea_small").val("");
                    }
                    );
            });

            jQuery('.schema-datepicker').datepicker();
        });
        function show_custom_field_row() {
            document.getElementById('image_custom_field').style.display = '';
        }
        function hide_custom_field_row() {
            document.getElementById('image_custom_field').style.display = 'none';
        }
        function hide_custom_price_row() {
            document.getElementById('custom_price').style.display = 'none';
            document.getElementById('fixed_price').style.display = '';
        }
        function hide_fixed_price_row() {
            document.getElementById('fixed_price').style.display = 'none';
            document.getElementById('custom_price').style.display = '';
        }
    </script>
    <?php
}
?>