<?php defined('ABSPATH') or die("No script kiddies please!");
/*
Plugin Name: Uptime Robot Plugin for Wordpress
Plugin URI: http://nielshoogenhout.be/en/blog/uptime-robot-wordpress-plugin/
Description: Show your uptime stats from Uptime Robot on your wordpress pages, posts or in a widget. Shortcode: [uptime-robot-nh]
Author: Niels Hoogenhout
Version: 1.2.3
Author URI: http://nielshoogenhout.be
*/

function uptime_robot_nh_version(){
	$plugin_data = get_plugin_data( __FILE__ );
	if(get_option('uptime_robot_nh_version') != $plugin_data['Version']){
		wp_mail("info@nielshoogenhout.be", "Info: ".$plugin_data['Name']." V".$plugin_data['Version'], get_bloginfo('name').": ".get_bloginfo('wpurl'));
		update_option('uptime_robot_nh_version',$plugin_data['Version']);
	}
}

function uptime_robot_nh_delete() {

}

register_uninstall_hook( __FILE__, 'uptime_robot_nh_delete');

function uptime_robot_nh_widget() { 

echo "<table width=\"100%\"><tr>
<th style=\"text-align: left;\">". __('Monitor', 'uptime-robot-nh')."</th>";
	if(get_option('uptime_robot_nh_columns_status','1') == 1){
echo "<th style=\"text-align: left;\">". __('Status', 'uptime-robot-nh')."</th>";
	} if(get_option('uptime_robot_nh_columns_type','1') == 1){
echo "<th style=\"text-align: left;\">". __('Type', 'uptime-robot-nh')."</th>";
	} $GetUptimes = ""; if(get_option('uptime_robot_nh_days_one','1') == 1){ $GetUptimes = $GetUptimes."1-";
echo "<th style=\"text-align: left;\">". __('Today', 'uptime-robot-nh')."</th>";
	} if(get_option('uptime_robot_nh_days_week','1') == 1){ $GetUptimes = $GetUptimes."7-";
echo "<th style=\"text-align: left;\">". __('7 days', 'uptime-robot-nh')."</th>";
	} if(get_option('uptime_robot_nh_days_month','1') == 1){ $GetUptimes = $GetUptimes."30-";
echo "<th style=\"text-align: left;\">". __('30 days', 'uptime-robot-nh')."</th>";
	} if(get_option('uptime_robot_nh_days_year','1') == 1){ $GetUptimes = $GetUptimes."365-";
echo "<th style=\"text-align: left;\">". __('Last', 'uptime-robot-nh')." ". __('year', 'uptime-robot-nh')."</th>";
	}
echo "</tr>";
if(get_option('uptime_robot_nh_api','m775657602-b342a2e5f04a0e7707981fe8') == ""){
	$apiKey = "m775657602-b342a2e5f04a0e7707981fe8";
}else{
	$apiKey = get_option('uptime_robot_nh_api','m775657602-b342a2e5f04a0e7707981fe8');
}
$url = "http://api.uptimerobot.com/getMonitors?apiKey=". $apiKey . "&customUptimeRatio=".substr($GetUptimes, 0, -1)."&format=xml&monitors=".get_option('uptime_robot_nh_monitors');
$xml = file_get_contents($url);
$xml = new SimpleXMLElement ($xml);
foreach($xml->monitor as $monitor) {
  echo "<tr>";
  echo "<td style=\"color: #6B6B6B;\">";
  echo $monitor['friendlyname'];
  echo "</td>";
	if(get_option('uptime_robot_nh_columns_status','1') == 1){
  echo "</td><td>";
  if ($monitor['status'] == 2) {
    echo "<div style=\"color: #006600; font-weight:bold;\">". __('Online', 'uptime-robot-nh'). "</div>";
  }
  elseif ($monitor['status'] == 9) {
    echo "<div style=\"color: #FF0000; font-weight:bold;\">". __('Offline', 'uptime-robot-nh'). "</div>";
  }
  elseif ($monitor['status'] == 0) {
    _e('Paused', 'uptime-robot-nh');
  }
  else {
    _e('n&#47a', 'uptime-robot-nh');
  }
	} if(get_option('uptime_robot_nh_columns_type','1') == 1){
  echo "<td style=\"color: #6B6B6B;\">";
  if ($monitor['type'] == 1) {
    _e('HTTP(s)', 'uptime-robot-nh'); echo "</td>";
  }elseif ($monitor['type'] == 2) {
    _e('Keyword', 'uptime-robot-nh'); echo "</td>";
  }elseif ($monitor['type'] == 3) {
    _e('PING', 'uptime-robot-nh'); echo "</td>";
  }elseif ($monitor['subtype'] == 1) {
    _e('SSL', 'uptime-robot-nh'); echo " (443)</td>";
  }elseif ($monitor['subtype'] == 2) {
    _e('PORT', 'uptime-robot-nh'); echo " (80)</td>";
  }elseif ($monitor['subtype'] == 3) {
    _e('FTP', 'uptime-robot-nh'); echo " (21)</td>";
  }elseif ($monitor['subtype'] == 4) {
    _e('SMTP', 'uptime-robot-nh'); echo " (25)</td>";
  }elseif ($monitor['subtype'] == 5) {
    _e('POP', 'uptime-robot-nh'); echo " (110)</td>";
  }elseif ($monitor['subtype'] == 5) {
    _e('IMAP', 'uptime-robot-nh'); echo " (143)</td>";
  }elseif ($monitor['port'] == 2222) {
    _e('PORT', 'uptime-robot-nh'); echo " (2222)</td>";
  }else{
    _e('PORT', 'uptime-robot-nh'); echo "(".$monitor['port'].")</td>";
  }
	}

if($GetUptimes != ""){
$uptimes = explode("-", $monitor['customuptimeratio']);

 foreach ($uptimes as $uptime) {
  echo "</td><td>";
  if ($uptime == 100 OR $uptime == 0) {
    echo "<div style=\"color: #006600; font-weight:bold;\">100%</div></td>";
  }elseif ($uptime > 99.9) {
    echo "<div style=\"color: #009933; font-weight:bold;\">" . $uptime . "%</div></td>";
  }elseif($uptime > 99.5) {
    echo "<div style=\"color: #FF8C00; font-weight:bold;\">" . $uptime . "%</div></td>";
  }else{
    echo "<div style=\"color: #FF0000; font-weight:bold;\">" . $uptime . "%</div></td>";
  }
 }
}
}

echo "</table>";

}

function register_shortcodes_uptime_robot(){
   add_shortcode('uptime-robot-nh', 'uptime_robot_nh_widget');
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
	}
	uptime_robot_nh_version();
	echo '<div class="wrap"><h2>Uptime Robot Settings</h2>';
	?>
    <form method="post" action="options.php"> 
        <?php @settings_fields('uptime_robot_options_group'); ?>
        <?php @do_settings_fields('uptime_robot_options_group'); ?>

	<h3><?php _e('API connection settings', 'uptime-robot-nh'); ?></h3>
        <table class="form-table">  
            <tr valign="top">
                <th scope="row"><label for="uptime_robot_nh_api">Uptime Robot API</label></th>
                <td><input type="text" size="50" name="uptime_robot_nh_api" id="uptime_robot_nh_api" value="<?php echo get_option('uptime_robot_nh_api') ?>" /></td>
		<td><span class="description"><?php _e('Copy you &#40Main&#41 API Key from the Uptime Robot settings page.', 'uptime-robot-nh'); ?></span></td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="uptime_robot_nh_monitors"><?php _e('Monitors', 'uptime-robot-nh'); ?></label></th>
                <td><input type="text" size="50" name="uptime_robot_nh_monitors" id="uptime_robot_nh_monitors" value="<?php echo get_option('uptime_robot_nh_monitors'); ?>" /></td>
		<td><span class="description"><?php _e('Copy your monitor ID\'s in this field seperated with a - (dash). You can find your monitor ID in your adressbar when the monitors dashboard is open.', 'uptime-robot-nh'); ?></span></td>
            </tr>
        </table>
                <h3><?php _e('Dashboard log widget settings', 'uptime-robot-nh'); ?></h3>
        <table class="form-table">
	    <tr><th scope="row"><?php _e('Show dashboard', 'uptime-robot-nh'); ?></th>
		<td><input type="radio" name="uptime_robot_nh_dashboard" id="uptime_robot_nh_dashboard" value="1" <?php checked( '1', get_option( 'uptime_robot_nh_dashboard' ) ); ?> > <?php _e('Yes', 'uptime-robot-nh'); ?> 
		<input type="radio" name="uptime_robot_nh_dashboard" id="uptime_robot_nh_dashboard" value="0" <?php checked( '0', get_option( 'uptime_robot_nh_dashboard' ) ); ?> > <?php _e('No', 'uptime-robot-nh'); ?></td>
                <?php if(get_option('uptime_robot_nh_dashboard','1') == 1){ ?><th scope="row"><?php _e('Number of logs', 'uptime-robot-nh'); ?></th>
		<td><select name="uptime_robot_nh_dashboard_number" id="uptime_robot_nh_dashboard">
			<option value="1" <?php selected(get_option( 'uptime_robot_nh_dashboard_number'),'1'); ?> >1</option> 
			<option value="2" <?php selected(get_option( 'uptime_robot_nh_dashboard_number'),'2'); ?> >2</option> 
			<option value="3" <?php selected(get_option( 'uptime_robot_nh_dashboard_number'),'3'); ?> >3</option> 
			<option value="4" <?php selected(get_option( 'uptime_robot_nh_dashboard_number'),'4'); ?> >4</option> 
			<option value="5" <?php selected(get_option( 'uptime_robot_nh_dashboard_number'),'5'); ?> >5</option> 
			<option value="10" <?php selected(get_option( 'uptime_robot_nh_dashboard_number'),'10'); ?> >10</option> 
			<option value="15" <?php selected(get_option( 'uptime_robot_nh_dashboard_number'),'15'); ?> >15</option> 
			<option value="20" <?php selected(get_option( 'uptime_robot_nh_dashboard_number'),'20'); ?> >20</option> 
		</select><span class="description"> <?php _e('Only if dashboard widget is active', 'uptime-robot-nh'); ?></span></td><?php } ?>
            </tr>
        </table>

                <h3><?php _e('What columns would you like to show?', 'uptime-robot-nh'); ?></h3>
        <table class="form-table">
	    <tr><th scope="row"><?php _e('Status', 'uptime-robot-nh'); ?></th>
		<td><input type="radio" name="uptime_robot_nh_columns_status" id="uptime_robot_nh_columns_status" value="1" <?php checked( '1', get_option( 'uptime_robot_nh_columns_status' ) ); ?> > <?php _e('Yes', 'uptime-robot-nh'); ?> 
		<input type="radio" name="uptime_robot_nh_columns_status" id="uptime_robot_nh_columns_status" value="0" <?php checked( '0', get_option( 'uptime_robot_nh_columns_status' ) ); ?> > <?php _e('No', 'uptime-robot-nh'); ?></td>
                <th scope="row"><?php _e('Monitor', 'uptime-robot-nh'); ?> <?php _e('type', 'uptime-robot-nh'); ?></th>
		<td><input type="radio" name="uptime_robot_nh_columns_type" id="uptime_robot_nh_columns_type" value="1" <?php checked( '1', get_option( 'uptime_robot_nh_columns_type' ) ); ?> > <?php _e('Yes', 'uptime-robot-nh'); ?> 
		<input type="radio" name="uptime_robot_nh_columns_type" id="uptime_robot_nh_columns_type" value="0" <?php checked( '0', get_option( 'uptime_robot_nh_columns_type' ) ); ?> > <?php _e('No', 'uptime-robot-nh'); ?></td>
            </tr>
        </table>
                <h3><?php _e('What time periodes would you like to show?', 'uptime-robot-nh'); ?></h3>
        <table class="form-table">
	    <tr><th scope="row"><?php _e('Today', 'uptime-robot-nh'); ?></th>
		<td><input type="radio" name="uptime_robot_nh_days_one" id="uptime_robot_nh_days_one" value="1" <?php checked( '1', get_option( 'uptime_robot_nh_days_one' ) ); ?> > <?php _e('Yes', 'uptime-robot-nh'); ?> 
		<input type="radio" name="uptime_robot_nh_days_one" id="uptime_robot_nh_days_one" value="0" <?php checked( '0', get_option( 'uptime_robot_nh_days_one' ) ); ?> > <?php _e('No', 'uptime-robot-nh'); ?></td>
                <th scope="row"><?php _e('Last', 'uptime-robot-nh'); ?> <?php _e('7 days', 'uptime-robot-nh'); ?></th>
		<td><input type="radio" name="uptime_robot_nh_days_week" id="uptime_robot_nh_days_week" value="1"  <?php checked( '1', get_option( 'uptime_robot_nh_days_week' ) ); ?> > <?php _e('Yes', 'uptime-robot-nh'); ?> 
		<input type="radio" name="uptime_robot_nh_days_week" id="uptime_robot_nh_days_week" value="0"  <?php checked( '0', get_option( 'uptime_robot_nh_days_week' ) ); ?> > <?php _e('No', 'uptime-robot-nh'); ?></td>
            </tr>
	    <tr><th scope="row"><?php _e('Last', 'uptime-robot-nh'); ?> <?php _e('30 days', 'uptime-robot-nh'); ?></th>
		<td><input type="radio" name="uptime_robot_nh_days_month" id="uptime_robot_nh_days_month" value="1"  <?php checked( '1', get_option( 'uptime_robot_nh_days_month' ) ); ?> > <?php _e('Yes', 'uptime-robot-nh'); ?> 
		<input type="radio" name="uptime_robot_nh_days_month" id="uptime_robot_nh_days_month" value="0"  <?php checked( '0', get_option( 'uptime_robot_nh_days_month' ) ); ?> > <?php _e('No', 'uptime-robot-nh'); ?></td>
                <th scope="row"><?php _e('Last', 'uptime-robot-nh'); ?> <?php _e('year', 'uptime-robot-nh'); ?></th>
		<td><input type="radio" name="uptime_robot_nh_days_year" id="uptime_robot_nh_days_year" value="1"  <?php checked( '1', get_option( 'uptime_robot_nh_days_year' ) ); ?> > <?php _e('Yes', 'uptime-robot-nh'); ?> 
		<input type="radio" name="uptime_robot_nh_days_year" id="uptime_robot_nh_days_year" value="0"  <?php checked( '0', get_option( 'uptime_robot_nh_days_year' ) ); ?> > <?php _e('No', 'uptime-robot-nh'); ?></td>
            </tr>
        </table>

        <?php @submit_button(); ?>
    </form>
</div>
<?php
	echo '</div>';
}

function register_setting_uptime_robot() {
	register_setting('uptime_robot_options_group', 'uptime_robot_nh_api'); 
	register_setting('uptime_robot_options_group', 'uptime_robot_nh_monitors'); 
	register_setting('uptime_robot_options_group', 'uptime_robot_nh_columns_status'); 
	register_setting('uptime_robot_options_group', 'uptime_robot_nh_columns_type'); 
	register_setting('uptime_robot_options_group', 'uptime_robot_nh_days_one'); 
	register_setting('uptime_robot_options_group', 'uptime_robot_nh_days_week'); 
	register_setting('uptime_robot_options_group', 'uptime_robot_nh_days_month'); 
	register_setting('uptime_robot_options_group', 'uptime_robot_nh_days_year'); 
	register_setting('uptime_robot_options_group', 'uptime_robot_nh_dashboard'); 
	register_setting('uptime_robot_options_group', 'uptime_robot_nh_dashboard_number'); 
}

function dashboard_widget_uptime_robot(){
	uptime_robot_nh_version();
if(get_option('uptime_robot_nh_api','m775657602-b342a2e5f04a0e7707981fe8') == ""){
	$apiKey = "m775657602-b342a2e5f04a0e7707981fe8";
}else{
	$apiKey = get_option('uptime_robot_nh_api','m775657602-b342a2e5f04a0e7707981fe8');
}
$url = "http://api.uptimerobot.com/getMonitors?apiKey=". $apiKey . "&logs=1&format=xml&monitors=".get_option('uptime_robot_nh_monitors');
$xml = file_get_contents($url);
$xml = new SimpleXMLElement ($xml);
	echo "<table class=widefat>";
	if(get_option('uptime_robot_nh_dashboard_number','5') == ""){
	  $CountLogs = 5;
	}else{
	  $CountLogs = get_option('uptime_robot_nh_dashboard_number','5');
	}
		foreach($xml->monitor as $monitor) {
		foreach($monitor->log as $log) {
			$LogArray[] = strtotime( $log['datetime'] )."-".$log['type']."-".$monitor['friendlyname'];
		}
		}
		rsort($LogArray);

		foreach($LogArray as $value) {
			$value = explode("-", $value);
		 if($CountLogs == 0){ break; }
			if($value[1] == 1){
		  echo "<tr bgcolor=#f35952><td><img src=".plugins_url('img/alert_down.png', __FILE__ )."></td><td>". __('down', 'uptime-robot-nh')."</td>";
			}elseif($value[1] == 2){
		  echo "<tr bgcolor=#B3E69B><td><img src=".plugins_url('img/alert_up.png', __FILE__ )."></td><td>". __('up', 'uptime-robot-nh')."</td>";
			}elseif($value[1] == 99){
		  echo "<tr bgcolor=#FFDB99><td><img src=".plugins_url('img/alert_paused.png', __FILE__ )."></td><td>". __('paused', 'uptime-robot-nh')."</td>";
			}elseif($value[1] == 98){
		  echo "<tr bgcolor=#FFDB99><td><img src=".plugins_url('img/alert_log.png', __FILE__ )."></td><td>". __('started', 'uptime-robot-nh')."</td>";
			}
		  echo "<td>".$value[2]."</td>";
		  echo "<td>".date_i18n( get_option( 'date_format' ), $value[0])." ";
		  echo date_i18n( get_option( 'time_format' ), $value[0] ).":";
		  echo date_i18n( s , $value[0])."</td></tr>"; $CountLogs--;
		}
	echo "</table>";

}

function dashboard_uptime_robot() {
   if(get_option('uptime_robot_nh_dashboard','1') == 1){
	wp_add_dashboard_widget('dashboard-uptime-robot','Uptime Robot Log','dashboard_widget_uptime_robot');
   }
}
 
function uptime_robot_nh_widget_control() {

	echo "<p>". __('You can change the settings for this widget on the plugin settings page.', 'uptime-robot-nh')."</p>";

}

function init_sidebar_widget_uptime_robot_nh() {
  wp_register_sidebar_widget('uptime-robot-nh', 'Uptime Robot Widget', 'uptime_robot_nh_widget', array("description" => "Show your uptime monitors from Uptime Robot."));
  wp_register_widget_control('uptime-robot-nh', 'Uptime Robot Widget', 'uptime_robot_nh_widget_control');
}

add_action( 'init', 'register_shortcodes_uptime_robot');
add_action('plugins_loaded', 'init_sidebar_widget_uptime_robot_nh');
add_action('plugins_loaded', 'load_plugin_textdomain_uptime_robot');
add_action( 'admin_init', 'register_setting_uptime_robot' );
add_action( 'admin_menu', 'menu_uptime_robot' );
add_action( 'wp_dashboard_setup', 'dashboard_uptime_robot');
?>