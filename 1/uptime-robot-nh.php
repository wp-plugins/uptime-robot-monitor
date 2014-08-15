<?php defined('ABSPATH') or die("No script kiddies please!");
/*
Plugin Name: Uptime Robot Plugin for Wordpress
Plugin URI: http://nielshoogenhout.be
Description: Uptime Robot Plugin for Wordpress let's you show you uptime stats from uptimerobot.com on your wordpress pages.
Author: Niels Hoogenhout
Version: 1.0.0
Author URI: http://nielshoogenhout.be
*/

function uptime_robot_nh_delete() {

}

register_uninstall_hook( __FILE__, 'uptime_robot_nh_delete');

function uptime_robot_nh_widget() {
echo "<table width=\"100%\"><tr>
<th style=\"font-size: 13px; font-size: 13px; text-align: left;\">". __('Monitor', 'uptime-robot-nh')."</th>
<th style=\"font-size: 13px; font-size: 13px; text-align: left;\">". __('Status', 'uptime-robot-nh')."</th>
<th style=\"font-size: 13px; font-size: 13px; text-align: left;\">". __('Type', 'uptime-robot-nh')."</th>
<th style=\"font-size: 13px; font-size: 13px; text-align: left;\">". __('Today', 'uptime-robot-nh')."</th>
<th style=\"font-size: 13px; font-size: 13px; text-align: left;\">". __('7 days', 'uptime-robot-nh')."</th>
<th style=\"font-size: 13px; font-size: 13px; text-align: left;\">". __('30 days', 'uptime-robot-nh')."</th>
<th style=\"font-size: 13px; font-size: 13px; text-align: left;\">". __('Last year', 'uptime-robot-nh')."</th>
</tr>";
$url = "http://api.uptimerobot.com/getMonitors?apiKey=". get_option('uptime_robot_nh_api','m775657602-b342a2e5f04a0e7707981fe8') . "&customUptimeRatio=1-7-30-365&logs=1&format=xml&monitors=".get_option('uptime_robot_nh_monitors','775657602');
$xml = file_get_contents($url);
$xml = new SimpleXMLElement ($xml);
foreach($xml->monitor as $monitor) {
  echo "<tr>";
  echo "<td style=\"font-size: 13px; font-family: 'Open Sans', sans-serif; color: #6B6B6B;\">";
  echo $monitor['friendlyname'];
  echo "</td>";

  echo "</td><td>";
  if ($monitor['status'] == 2) {
    echo "<div style=\"font-size: 13px; font-family: 'Open Sans', sans-serif; color: #006600; font-weight:bold;\">". __('Online', 'uptime-robot-nh'). "</div>";
  }
  elseif ($monitor['status'] == 9) {
    echo "<div style=\"font-size: 13px; font-family: 'Open Sans', sans-serif; color: #FF0000; font-weight:bold;\">". __('Offline', 'uptime-robot-nh'). "</div>";
  }
  else {
    _e('Not Available', 'uptime-robot-nh');
  }

  echo "<td style=\"font-size: 13px; text-align: left; font-family: 'Open Sans', sans-serif; color: #6B6B6B;\">";
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

$uptimes = explode("-", $monitor['customuptimeratio']);

  echo "</td><td>";
  if ($uptimes[0] == 100 OR $uptimes[0] == 0) {
    echo "<div style=\"font-size: 13px; font-family: 'Open Sans', sans-serif; color: #006600; font-weight:bold;\">100%</div></td>";
  }elseif ($uptimes[0] > 99.9) {
    echo "<div style=\"font-size: 13px; font-family: 'Open Sans', sans-serif; color: #009933; font-weight:bold;\">" . $uptimes[0] . "%</div></td>";
  }elseif($uptimes[0] > 99.5) {
    echo "<div style=\"font-size: 13px; font-family: 'Open Sans', sans-serif; color: #FF8C00; font-weight:bold;\">" . $uptimes[0] . "%</div></td>";
  }else{
    echo "<div style=\"font-size: 13px; font-family: 'Open Sans', sans-serif; color: #FF0000; font-weight:bold;\">" . $uptimes[0] . "%</div></td>";
  }

  echo "</td><td>";
  if ($uptimes[1] == 100 OR $uptimes[1] == 0) {
    echo "<div style=\"font-size: 13px; font-family: 'Open Sans', sans-serif; color: #006600; font-weight:bold;\">100%</div></td>";
  }elseif ($uptimes[1] > 99.9) {
    echo "<div style=\"font-size: 13px; font-family: 'Open Sans', sans-serif; color: #009933; font-weight:bold;\">" . $uptimes[1] . "%</div></td>";
  }elseif($uptimes[1] > 99.5) {
    echo "<div style=\"font-size: 13px; font-family: 'Open Sans', sans-serif; color: #FF8C00; font-weight:bold;\">" . $uptimes[1] . "%</div></td>";
  }else{
    echo "<div style=\"font-size: 13px; font-family: 'Open Sans', sans-serif; color: #FF0000; font-weight:bold;\">" . $uptimes[1] . "%</div></td>";
  }

  echo "</td><td>";
  if ($uptimes[2] == 100 OR $uptimes[2] == 0) {
    echo "<div style=\"font-size: 13px; font-family: 'Open Sans', sans-serif; color: #006600; font-weight:bold;\">100%</div></td>";
  }elseif ($uptimes[2] > 99.9) {
    echo "<div style=\"font-size: 13px; font-family: 'Open Sans', sans-serif; color: #009933; font-weight:bold;\">" . $uptimes[2] . "%</div></td>";
  }elseif ($uptimes[2] > 99.5) {
    echo "<div style=\"font-size: 13px; font-family: 'Open Sans', sans-serif; color: #FF8C00; font-weight:bold;\">" . $uptimes[2] . "%</div></td>";
  }else{
    echo "<div style=\"font-size: 13px; font-family: 'Open Sans', sans-serif; color: #FF0000; font-weight:bold;\">" . $uptimes[2] . "%</div></td>";
  }

  echo "</td><td>";
  if ($uptimes[3] == 100 OR $uptimes[3] == 0) {
    echo "<div style=\"font-size: 13px; font-family: 'Open Sans', sans-serif; color: #006600; font-weight:bold;\">100%</div></td></tr>";
  }elseif ($uptimes[3] > 99.9) {
    echo "<div style=\"font-size: 13px; font-family: 'Open Sans', sans-serif; color: #009933; font-weight:bold;\">" . $uptimes[3] . "%</div></td></tr>";
  }elseif($uptimes[3] > 99.5) {
    echo "<div style=\"font-size: 13px; font-family: 'Open Sans', sans-serif; color: #FF8C00; font-weight:bold;\">" . $uptimes[3] . "%</div></td></tr>";
  }else{
    echo "<div style=\"font-size: 13px; font-family: 'Open Sans', sans-serif; color: #FF0000; font-weight:bold;\">" . $uptimes[3] . "%</div></td></tr>";
  }

}

echo "</table>";

}

function init_sidebar_widget_uptime_robot() {
  wp_register_sidebar_widget('uptime-robot-nh', 'Direct Admin Widget', 'uptime_robot_nh_widget', array("description" => "Geef je uptime gegevens van uptimerobot.com weer."));
  wp_register_widget_control('uptime-robot-nh', 'Direct Admin Widget', 'uptime_robot_nh_control');
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
	echo '<div class="wrap"><h2>Uptime Robot Settings</h2>';
	?>
    <form method="post" action="options.php"> 
        <?php @settings_fields('uptime_robot_options_group'); ?>
        <?php @do_settings_fields('uptime_robot_options_group'); ?>

        <table class="form-table">  
            <tr valign="top">
                <th scope="row"><label for="uptime_robot_nh_api">Uptime Robot API</label></th>
                <td><input type="text" name="uptime_robot_nh_api" id="uptime_robot_nh_api" value="<?php echo get_option('uptime_robot_nh_api') ?>" /></td>
		<td><?php _e('Copy you Main API Key from the Uptime Robot settings page.', 'uptime-robot-nh'); ?></td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="uptime_robot_nh_monitors"><?php _e('Monitors', 'uptime-robot-nh'); ?></label></th>
                <td><input type="text" name="uptime_robot_nh_monitors" id="uptime_robot_nh_monitors" value="<?php echo get_option('uptime_robot_nh_monitors'); ?>" /></td>

		<td><?php _e('Copy your monitor ID\'s in this field seperated with a - (dash). You can find your monitor ID in your adressbar when the monitors dashboard is open.', 'uptime-robot-nh'); ?></td>
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
} 

add_action( 'init', array($this, 'load_plugin_textdomain') );
add_action( 'init', 'register_shortcodes_uptime_robot');
add_action('plugins_loaded', 'init_sidebar_widget_uptime_robot');
add_action('plugins_loaded', 'load_plugin_textdomain_uptime_robot');
add_action( 'admin_init', 'register_setting_uptime_robot' );
add_action( 'admin_menu', 'menu_uptime_robot' );

?>