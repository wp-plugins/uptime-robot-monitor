<?php defined('ABSPATH') or die("No script kiddies please!");
/*
Plugin Name: Uptime Robot Plugin for Wordpress
Plugin URI: https://wordpress.org/plugins/uptime-robot-monitor/
Description: Show your uptime stats and logs from Uptime Robot on your wordpress pages, posts or in a widget.
Author: Niels Hoogenhout
Version: 1.5.1
Author URI: http://nielshoogenhout.eu
*/

   include(plugin_dir_path( __FILE__ )."ur-logs.php");
   include(plugin_dir_path( __FILE__ )."ur-status.php");

function uptime_robot_nh_version(){
	$plugin_data = get_plugin_data( __FILE__ );
	if(get_option('uptime_robot_nh_version') == ""){
		#wp_mail("info@nielshoogenhout.eu", "Install UR V".$plugin_data['Version']." ".get_bloginfo('name'), get_bloginfo('name').": ".get_bloginfo('wpurl'));
		update_option('uptime_robot_nh_version',$plugin_data['Version']);
		update_option('uptime_robot_nh_columns_status','1');
		update_option('uptime_robot_nh_columns_type','1');
		update_option('uptime_robot_nh_days_one','1');
		update_option('uptime_robot_nh_days_week','1');
		update_option('uptime_robot_nh_days_month','1');
		update_option('uptime_robot_nh_days_year','1');
		update_option('uptime_robot_nh_dashboard','1');
		update_option('uptime_robot_nh_dashboard2','1');
		update_option('uptime_robot_nh_dashboard_number','5');
	}elseif(get_option('uptime_robot_nh_version') != $plugin_data['Version']){
		#wp_mail("info@nielshoogenhout.eu", "Upgrade UR V".$plugin_data['Version']." ".get_bloginfo('name'), get_bloginfo('name').": ".get_bloginfo('wpurl'));
		update_option('uptime_robot_nh_version',$plugin_data['Version']);
		update_option('uptime_robot_nh_dashboard2','1');
	}
}

function uptime_robot_nh_delete() {

}

register_uninstall_hook( __FILE__, 'uptime_robot_nh_delete');

function register_shortcodes_uptime_robot(){
   add_shortcode('uptime-robot-nh', 'uptime_robot_nh_widget');
   add_shortcode('uptime-robot-logs-nh', 'dashboard_widget_uptime_robot');
}

function load_plugin_textdomain_uptime_robot() {
  load_plugin_textdomain( 'uptime-robot-nh', false, dirname(plugin_basename(__FILE__)) . '/languages/');
}

function menu_uptime_robot() {
	add_options_page( 'Uptime Robot Options', 'Uptime Robot', 'manage_options', 'uptime-robot-nh', 'uptime_robot_plugin_options' );
}

function uptime_robot_plugin_options() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}?>

	<script type="text/javascript">
	jQuery(document).ready(function($) {
	    $('a[ur-settings-page^=#ur-option]').click(function() { event.preventDefault();
	  	var div = $(this).attr('ur-settings-page');
 		$(".wp-ur-set").hide(); 
       		$(div).show();
	    });
	});
	</script><?

echo '<div style="position: fixed; right; top: 60px; right: 30px;"><form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="WTW7PHYKERFKY">
<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.paypalobjects.com/nl_NL/i/scr/pixel.gif" width="1" height="1">
</form></div>'; ?>

	<? echo '<div class="wrap"><h2>Uptime Robot Settings</h2>';
	?>
    <form method="post" action="options.php"> 
        <?php @settings_fields('uptime_robot_options_group'); ?>
        <?php @do_settings_fields('uptime_robot_options_group'); ?>


<a ur-settings-page="#ur-option-1" class="button button-primary"><?php _e('General', 'uptime-robot-nh'); ?></a> <a ur-settings-page="#ur-option-3" class="button button-primary"><?php _e('API-settings', 'uptime-robot-nh'); ?></a> <a ur-settings-page="#ur-option-2" class="button button-primary"><?php _e('Colors', 'uptime-robot-nh'); ?></a> <a ur-settings-page="#ur-option-4" class="button button-primary"><?php _e('Shortcodes', 'uptime-robot-nh'); ?></a> <a href="https://wordpress.org/support/plugin/uptime-robot-monitor" target="_blank" class="button button-primary"><?php _e('Support', 'uptime-robot-nh'); ?></a> <a href="http://nielshoogenhout.eu/support-contact/?utm_source=WP-Plugin&utm_medium=referral&utm_campaign=uptimerobot" target="_blank" class="button button-primary"><?php _e('Contact', 'uptime-robot-nh'); ?></a>

   <?php if(get_option('uptime_robot_nh_api') == ""){ ?>
<div>
   <?php }else{ ?>
<div id="ur-option-3" class="wp-ur-set" style="display: none;">
   <?php } ?>
	<h3><?php _e('API connection settings', 'uptime-robot-nh'); ?></h3>
        <table class="form-table">  
            <tr>
                <th scope="row"><label for="uptime_robot_nh_api">Uptime Robot API</label></th>
                <td><input type="text" size="50" name="uptime_robot_nh_api" id="uptime_robot_nh_api" value="<?php echo get_option('uptime_robot_nh_api') ?>" /></td>
		<td><span class="description"><?php _e('Copy you &#40Main&#41 API Key from the Uptime Robot settings page.', 'uptime-robot-nh'); ?></span></td>
            </tr>
            <tr>
                <th scope="row"><label for="uptime_robot_nh_monitors"><?php _e('Monitors', 'uptime-robot-nh'); ?></label></th>
                <td><input type="text" size="50" name="uptime_robot_nh_monitors" id="uptime_robot_nh_monitors" value="<?php echo get_option('uptime_robot_nh_monitors'); ?>" /></td>
		<td><span class="description"><?php _e('Copy your monitor ID\'s in this field seperated with a - (dash). You can find your monitor ID in your adressbar when the monitors dashboard is open.', 'uptime-robot-nh'); ?></span></td>
            </tr>
        </table>

        <?php @submit_button(); ?>
</div>
<div id="ur-option-1" class="wp-ur-set">
                <h3><?php _e('Dashboard widget settings', 'uptime-robot-nh'); ?></h3>
        <table class="form-table">
	    <tr><th scope="row"><?php _e('Show log widget', 'uptime-robot-nh'); ?></th>
		<td><input type="checkbox" name="uptime_robot_nh_dashboard" id="uptime_robot_nh_dashboard" value="1" <?php checked( '1', get_option( 'uptime_robot_nh_dashboard' ) ); ?> > </td>
                <th scope="row"><?php _e('Number of logs', 'uptime-robot-nh'); ?></th>
		<td><select name="uptime_robot_nh_dashboard_number" id="uptime_robot_nh_dashboard">
			<option value="1" <?php selected(get_option( 'uptime_robot_nh_dashboard_number'),'1'); ?> >1</option> 
			<option value="2" <?php selected(get_option( 'uptime_robot_nh_dashboard_number'),'2'); ?> >2</option> 
			<option value="3" <?php selected(get_option( 'uptime_robot_nh_dashboard_number'),'3'); ?> >3</option> 
			<option value="4" <?php selected(get_option( 'uptime_robot_nh_dashboard_number'),'4'); ?> >4</option> 
			<option value="5" <?php selected(get_option( 'uptime_robot_nh_dashboard_number'),'5'); ?> >5</option> 
			<option value="10" <?php selected(get_option( 'uptime_robot_nh_dashboard_number'),'10'); ?> >10</option> 
			<option value="15" <?php selected(get_option( 'uptime_robot_nh_dashboard_number'),'15'); ?> >15</option> 
			<option value="20" <?php selected(get_option( 'uptime_robot_nh_dashboard_number'),'20'); ?> >20</option> 
		</select><span class="description"> <?php _e('Only if dashboard log widget is active', 'uptime-robot-nh'); ?></span></td>
            </tr>
	    <tr><th scope="row"><?php _e('Show monitor widget', 'uptime-robot-nh'); ?></th>
		<td><input type="checkbox" name="uptime_robot_nh_dashboard2" id="uptime_robot_nh_dashboard2" value="1" <?php checked( '1', get_option( 'uptime_robot_nh_dashboard2' ) ); ?> > </td>
	    </tr>
        </table>

                <h3><?php _e('What columns would you like to show?', 'uptime-robot-nh'); ?></h3>
        <table class="form-table">
	    <tr><th scope="row"><?php _e('Status', 'uptime-robot-nh'); ?></th>
		<td><input type="checkbox" name="uptime_robot_nh_columns_status" id="uptime_robot_nh_columns_status" value="1" <?php checked( '1', get_option( 'uptime_robot_nh_columns_status' ) ); ?> > </td> 
                <th scope="row"><?php _e('Monitor', 'uptime-robot-nh'); ?> <?php _e('type', 'uptime-robot-nh'); ?></th>
		<td><input type="checkbox" name="uptime_robot_nh_columns_type" id="uptime_robot_nh_columns_type" value="1" <?php checked( '1', get_option( 'uptime_robot_nh_columns_type' ) ); ?> > </td>
            </tr>
	    <tr><th scope="row"><?php _e('Uptime Robot', 'uptime-robot-nh'); ?></th>
		<td><input type="checkbox" name="uptime_robot_nh_thanks_1" id="uptime_robot_nh_thanks_1" value="0" <?php checked( '0', get_option( 'uptime_robot_nh_thanks_1' ) ); ?> > <span class="description"> <?php _e('Realtime monitoring by <a href="http://uptimerobot.com/" target="_blank">Uptime Robot</a>', 'uptime-robot-nh'); ?></span></td> 
                <th scope="row"><?php _e('Wordpress Plugin', 'uptime-robot-nh'); ?> <?php _e('type', 'uptime-robot-nh'); ?></th>
		<td><input type="checkbox" name="uptime_robot_nh_thanks_2" id="uptime_robot_nh_thanks_2" value="0" <?php checked( '0', get_option( 'uptime_robot_nh_thanks_2' ) ); ?> > <span class="description"> <?php _e('Plugin by <a href="https://wordpress.org/plugins/uptime-robot-monitor/" target="_blank">Niels Hoogenhout.be</a>', 'uptime-robot-nh'); ?></span> </td>
            </tr>
        </table>
        <table class="form-table">
                <h3><?php _e('What time periodes would you like to show?', 'uptime-robot-nh'); ?></h3>
	    <tr><th scope="row"><?php _e('Today', 'uptime-robot-nh'); ?></th>
		<td><input type="checkbox" name="uptime_robot_nh_days_one" id="uptime_robot_nh_days_one" value="1" <?php checked( '1', get_option( 'uptime_robot_nh_days_one' ) ); ?> > </td> 
                <th scope="row"><?php _e('Last', 'uptime-robot-nh'); ?> <?php _e('7 days', 'uptime-robot-nh'); ?></th>
		<td><input type="checkbox" name="uptime_robot_nh_days_week" id="uptime_robot_nh_days_week" value="1"  <?php checked( '1', get_option( 'uptime_robot_nh_days_week' ) ); ?> > </td>
            </tr>
	    <tr><th scope="row"><?php _e('Last', 'uptime-robot-nh'); ?> <?php _e('30 days', 'uptime-robot-nh'); ?></th>
		<td><input type="checkbox" name="uptime_robot_nh_days_month" id="uptime_robot_nh_days_month" value="1"  <?php checked( '1', get_option( 'uptime_robot_nh_days_month' ) ); ?> > </td>
                <th scope="row"><?php _e('Last', 'uptime-robot-nh'); ?> <?php _e('year', 'uptime-robot-nh'); ?></th>
		<td><input type="checkbox" name="uptime_robot_nh_days_year" id="uptime_robot_nh_days_year" value="1"  <?php checked( '1', get_option( 'uptime_robot_nh_days_year' ) ); ?> > </td>
            </tr>
        </table>
        <?php @submit_button(); ?>
</div>

<div id="ur-option-2" class="wp-ur-set" style="display: none;">
<script type="text/javascript">
jQuery(document).ready(function($){
    $('.wp-color-picker-field').wpColorPicker();
});
</script>
                <h3><?php _e('Colors', 'uptime-robot-nh'); ?></h3>
        <table class="form-table">
	    <tr><th scope="row"><?php _e('Font', 'uptime-robot-nh'); ?></th>
		<td><input type="text" name="uptime_robot_nh_colors_font" value="<?php echo get_option('uptime_robot_nh_colors_font','#6B6B6B') ?>" class="wp-color-picker-field" /></td> 
            </tr>
	    <tr><th scope="row"><?php _e('Online', 'uptime-robot-nh'); ?></th>
		<td><input type="text" name="uptime_robot_nh_colors_online" value="<?php echo get_option('uptime_robot_nh_colors_online','#006600') ?>" class="wp-color-picker-field" /></td> 
                <th scope="row"><?php _e('Offline', 'uptime-robot-nh'); ?></th>
		<td><input type="text" name="uptime_robot_nh_colors_offline" value="<?php echo get_option('uptime_robot_nh_colors_offline','#FF0000') ?>" class="wp-color-picker-field" /></td> 
            </tr>
	    <tr><th scope="row">100%</th>
		<td><input type="text" name="uptime_robot_nh_colors_100" value="<?php echo get_option('uptime_robot_nh_colors_100','#006600') ?>" class="wp-color-picker-field" /></td> 
                <th scope="row">> 99.9%
		</th>
		<td><input type="text" name="uptime_robot_nh_colors_999" value="<?php echo get_option('uptime_robot_nh_colors_999','#009933') ?>" class="wp-color-picker-field" /></td> 
            </tr>
	    <tr><th scope="row">> 99.5%</th>
		<td><input type="text" name="uptime_robot_nh_colors_995" value="<?php echo get_option('uptime_robot_nh_colors_995','#FF8C00') ?>" class="wp-color-picker-field" /></td> 
                <th scope="row">< 99.5%</th>
		<td><input type="text" name="uptime_robot_nh_colors_0" value="<?php echo get_option('uptime_robot_nh_colors_0','#FF0000') ?>" class="wp-color-picker-field" /></td> 
            </tr>
        </table>
        <?php @submit_button(); ?>
</div>
<div id="ur-option-4" class="wp-ur-set" style="display: none;">
                <h3><?php _e('Shortcodes', 'uptime-robot-nh'); ?></h3>
 	<?php echo '<input type="text" size="25" value="[uptime-robot-nh]" onclick="this.select()"> '.__('Use this shortcode on any page or post to display your uptime robot stats.', 'uptime-robot-nh'); ?><br>
  	<?php echo '<br><input type="text" size="25" value="[uptime-robot-logs-nh]" onclick="this.select()"> '.__('Use this shortcode on any page or post to display your uptime robot logs.', 'uptime-robot--nh'); ?>
</div>
    </form>

<?php
	echo '</div>';
}

function register_setting_uptime_robot() {
	register_setting('uptime_robot_options_group', 'uptime_robot_nh_api'); 
	register_setting('uptime_robot_options_group', 'uptime_robot_nh_monitors'); 
	register_setting('uptime_robot_options_group', 'uptime_robot_nh_columns_status'); 
	register_setting('uptime_robot_options_group', 'uptime_robot_nh_columns_type'); 
	register_setting('uptime_robot_options_group', 'uptime_robot_nh_thanks_1'); 
	register_setting('uptime_robot_options_group', 'uptime_robot_nh_thanks_2'); 
	register_setting('uptime_robot_options_group', 'uptime_robot_nh_days_one'); 
	register_setting('uptime_robot_options_group', 'uptime_robot_nh_days_week'); 
	register_setting('uptime_robot_options_group', 'uptime_robot_nh_days_month'); 
	register_setting('uptime_robot_options_group', 'uptime_robot_nh_days_year'); 
	register_setting('uptime_robot_options_group', 'uptime_robot_nh_dashboard'); 
	register_setting('uptime_robot_options_group', 'uptime_robot_nh_dashboard2'); 
	register_setting('uptime_robot_options_group', 'uptime_robot_nh_dashboard_number'); 
	register_setting('uptime_robot_options_group', 'uptime_robot_nh_colors_font'); 
	register_setting('uptime_robot_options_group', 'uptime_robot_nh_colors_online'); 
	register_setting('uptime_robot_options_group', 'uptime_robot_nh_colors_offline'); 
	register_setting('uptime_robot_options_group', 'uptime_robot_nh_colors_100'); 
	register_setting('uptime_robot_options_group', 'uptime_robot_nh_colors_999'); 
	register_setting('uptime_robot_options_group', 'uptime_robot_nh_colors_995'); 
	register_setting('uptime_robot_options_group', 'uptime_robot_nh_colors_0'); 
	register_setting('uptime_robot_options_group', 'uptime_robot_nh_percentage_999'); 
	register_setting('uptime_robot_options_group', 'uptime_robot_nh_percentage_995'); 
	register_setting('uptime_robot_options_group', 'uptime_robot_nh_percentage_0'); 
}

function dashboard_uptime_robot() {
   if(get_option('uptime_robot_nh_dashboard') == 1){
	wp_add_dashboard_widget('dashboard-uptime-robot','Uptime Robot Log','dashboard_widget_uptime_robot');
   }
   if(get_option('uptime_robot_nh_dashboard2') == 1){
	wp_add_dashboard_widget('dashboard2-uptime-robot','Uptime Robot Stats','uptime_robot_nh_widget');
   }
}
 
function uptime_robot_nh_scripts() {

    wp_enqueue_script('jquery');
    wp_enqueue_script('jquery-ui-core');
    wp_enqueue_style( 'wp-color-picker' );
    wp_enqueue_script( 'wp-color-picker-script', plugins_url('script.js', __FILE__ ), array( 'wp-color-picker' ), false, true );
}
 
function uptime_robot_nh_api_notice() {
    ?>
    <div class="error">
        <p><?php _e( 'Please enter your main uptimerobot.com API-key on the settings page:', 'uptime-robot-nh' ); ?> <a href="options-general.php?page=uptime-robot-nh">Uptime Robot Plugin for Wordpress</a></p>
    </div>
    <?php
}

   if(get_option('uptime_robot_nh_api') == ""){
add_action( 'admin_notices', 'uptime_robot_nh_api_notice' );
   }

function uptime_robot_nh_action_links($links) { 
   $links[] = '<a href="'. get_admin_url(null, 'options-general.php?page=uptime-robot-nh') .'">Settings</a>';
   return $links;
}
add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'uptime_robot_nh_action_links' );
add_filter( 'network_admin_plugin_action_links_' . plugin_basename(__FILE__), 'uptime_robot_nh_action_links' );

add_action( 'init', 'register_shortcodes_uptime_robot');
add_action('plugins_loaded', 'init_sidebar_widget_uptime_robot_nh');
add_action('plugins_loaded', 'init_sidebar_dashboard_widget_uptime_robot');
add_action('plugins_loaded', 'load_plugin_textdomain_uptime_robot');
add_action( 'admin_init', 'register_setting_uptime_robot' );
add_action( 'admin_menu', 'menu_uptime_robot' );
add_action( 'admin_menu', 'uptime_robot_nh_version' );
add_action( 'admin_enqueue_scripts', 'uptime_robot_nh_scripts' );
add_action( 'wp_dashboard_setup', 'dashboard_uptime_robot');
?>