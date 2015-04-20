<?php
/**

Plugin Name: iRecharge Widget
Plugin URI: http://irecharge.com.ng/
Description: This widget enables your website visitors to recharge their mobile lines. All major telecommunication networks in Nigeria are supported. To get started: 1) Click the "Activate" link to the left of this description, 3) Go through the Settings and select 'iRecharge Vendor Set' to set your Vendor ID, this is displayed on your profile at irecharge.com.ng/profile 4) . Simply go to Appearance , Select Widget and Add the recharge Widget to your Widget area.
Author: Shedu Idris ,IST Nigeria
Version: 1.0
Author URI: http://irecharge.com.ng
License: GPLv2
 */


if($_POST['ist_irecharge_hidden'] == 'Y') {
    //Form data sent

    $ir_vendor_id = $_POST['irecharge_dashboard_vendorid'];

    // validating vendor id
    if ( $ir_vendor_id == null || strlen( $ir_vendor_id ) < 6  )  {
        wp_die( __( 'Please Enter a Valid Vendor ID' ) );
    }
    else update_option('irecharge_dashboard_vendorid', $ir_vendor_id);
    ?>

    <div class="updated"><p><strong><?php _e('Options saved.' ); ?></strong></p></div>
<?php
} else {
    //Normal page display
    $ir_vendor_id = get_option('irecharge_dashboard_vendorid');
}




add_action( 'widgets_init', function(){
    register_widget( 'iRecharge' );

});


    add_action('wp', 'irecharge_ext_init_2');
    add_action('admin_menu', 'ist_irecharge_admin_actions_2');
    add_action('admin_init', 'ist_irecharge_init_2');
    add_action('init', 'ist_ir_register_shortcodes_2');



/**
 * Adds My_Widget widget.
 */
class Irecharge extends WP_Widget {

    /**
     * Register widget with WordPress.
     */
    function __construct() {
        parent::__construct(
            'iRecharge', // Base ID
            __('iRecharge', 'irecharge.com.ng'), // Name
            array( 'description' => __( 'Instant top up ', 'irecharge.com.ng' ), ) // Args
        );
    }

    // the content area
    public function widget( $args, $instance ) {

        //Connect to the  updated option
        $ir_vendor_id = get_option('irecharge_dashboard_vendorid');

        if($ir_vendor_id){ //if we get something, then display the widget

            // now we add images locally
            $logo = plugin_dir_url( __FILE__ ) .'/img/logo.png';
            $mtn = plugin_dir_url( __FILE__ ) .'/img/mtn.png';
            $glo = plugin_dir_url( __FILE__ ) .'/img/glo.jpg';
            $etisalat = plugin_dir_url( __FILE__ ) .'/img/etisalat.jpg';
            $airtel = plugin_dir_url( __FILE__ ) .'/img/airtel.png';
            $visaphone = plugin_dir_url( __FILE__ ) .'/img/visafone.png';

            echo "<form action='' method=POST id='topup_form8975'>";
            echo "<div id=\"topup_formxyz\"><div id=\"left12345\"><img src=\"$logo\" width=\"36\" height=\"36\" alt=\"irecharge\" align=\"absmiddle\"> iRecharge</div>	<div id=\"right12345\"><img src=\"$mtn\" alt=\"mtn\"><img src=\"$airtel\" alt=\"airtel\"><img src=\"$visaphone\" alt=\"visafone\"><img src=\"$glo\" alt=\"glo\"><img src=\"$etisalat\" alt=\"etisalat\"></div><div style=\"clear:both; line-height:0; height:0; overflow:hidden\"></div></div><span id=\"topup_display_message123\">Instantly recharge your mobile phone. Major networks supported.</span>";
            echo "<input type='hidden' name='vendorId' id='vendorId' value='$ir_vendor_id'>";
            echo "<br><select required name='selected_network' id='selected_network' style='width: 100% !important;'><option value=''>Select a network</option><option value='MTN'>MTN</option><option value='Glo'>GLO</option><option value='Etisalat'>Etisalat</option><option value='Visafone'>Visafone</option><option value='Airtel'>Airtel</option></select></br>";
            echo "<br><input type='number' name='vtu_amount' id='vtu_amount' value='' placeholder='Enter amount' autocomplete='off' required></br>";
            echo "<br><input type='phone' name='vtu_number' id='vtu_number' value='' placeholder='Enter phone number' autocomplete='off' minlength='11' required></br>";
            echo "<br><input type='email' name='vtu_email' id='vtu_email' value='' size = '30' placeholder='Email address to send receipt' autocomplete='off' required></br>";
            echo "<br><input type='button' value='Send' id='irecharge_i_button'></br>";
            echo "</form>";
            echo "<div style=\"border-top:solid thin #eee; padding:10px 0 10px 0; margin-top:10px; font-size:0.8em; text-align:left;\" id=\"top_up_support123\">For support or enquiries<a href=\"http://www.facebook.com/irechargeng\" target=\"_blank\"><img src=\"http://irecharge.com.ng/img/facebook_circle.png\" alt=\"call\" align=\"absmiddle\"></a><a href=\"https://twitter.com/@i_recharge\" target=\"_blank\"><img src=\"http://irecharge.com.ng/img/twitter_circle.png\" alt=\"call\" align=\"absmiddle\"></a> <br/> <br/><img src=\"http://irecharge.com.ng/img/call.png\" alt=\"call\" align=\"absmiddle\"> 0700-isupport (0700-47877678)<br/><a href=\"mailto:support@irecharge.com.ng\"><img src=\"http://irecharge.com.ng/img/email.png\" alt=\"call\" align=\"absmiddle\"> support@irecharge.com.ng</a></div>";

        }
        else{ //else return null
            return 'null';
        }


    }

    /**
     * Back-end widget form.
     *
     * @see WP_Widget::form()
     *
     * @param array $instance Previously saved values from database.
     */
    public function form( $instance ) {
        if ( isset( $instance[ 'title' ] ) ) {
            $title = $instance[ 'title' ];
        }
        else {
            $title = __( 'New title', 'iRecharge' );
        }
        ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>
    <?php
    }

    /**
     * Sanitize widget form values as they are saved.
     *
     * @see WP_Widget::update()
     *
     * @param array $new_instance Values just sent to be saved.
     * @param array $old_instance Previously saved values from database.
     *
     * @return array Updated safe values to be saved.
     */
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';

        return $instance;
    }


} // end class widget


//function to add admin sublevel menu
function ist_irecharge_admin_actions_2() {
    add_options_page("iRecharge Vendor Set Widget", "iRecharge Vendor Set Widget", "manage_options", "iRecharge-Vendor-Set Widget", "ist_irecharge_admin2");
}
//function to initialize the settings fields
function ist_irecharge_init_2()
{
    register_setting('irecharge_field_group', 'irecharge_vendor_id');
}

//function to display form when the settings sublink is clicked on
function ist_irecharge_admin_2()
{

    if ( !current_user_can( 'manage_options' ) )  {
        wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
    }
    //include('/ist_irecharge_admin.php');
    ?>
    <div class='wrap'>
        <?php screen_icon(); ?>
        <h2>iRecharge Vendor Settings</h2><hr/>
        <form action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>" method="post">
            <?php settings_fields('irecharge_field_group'); ?>
            <?php @do_settings_fields('irecharge_field_group'); ?>
            <table class="form-table">
                <tr valid="top">
                    <th scope="row"><label for="irecharge_dashboard_vendorid">iRecharge Vendor ID</label></th>
                    <td>
                        <input type="hidden" name="ist_irecharge_hidden" value="Y">
                        <input type="text" name="irecharge_dashboard_vendorid" id="dashboard_vendorid" value="<?php echo get_option('irecharge_dashboard_vendorid'); ?>" />
                        <br/><small>Enter your iRecharge Vendor ID</small>
                    </td>
                </tr>
            </table><?php @submit_button(); ?>
        </form>
    </div>
<?php

}

//function to add shortcode
function ist_ir_register_shortcodes_2() {
    add_shortcode('irecharge','widget');
}

function irecharge_ext_init_2()
{
    wp_register_script('irecharge_pluginscript.js',plugins_url('/js/irecharge_pluginscript.js',_FILE_), array('jquery'));
    wp_register_script('irecharge_jquerycookie.js',plugins_url('/js/jquery_cookie.js',_FILE_), array('jquery'));
    wp_register_style( 'irecharge_pluginstyle.css', plugins_url('/css/irecharge_pluginstyle.css', _FILE_ ), array());

    wp_enqueue_script('jquery');
    wp_enqueue_script('irecharge_jquerycookie.js');
    wp_enqueue_script('irecharge_pluginscript.js');
    wp_enqueue_style('irecharge_pluginstyle.css');
}
