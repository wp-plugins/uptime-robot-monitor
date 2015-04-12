<?php defined('ABSPATH') or die("No script kiddies please!");

function dashboard_widget_uptime_robot(){

if(get_option('uptime_robot_nh_api','m775657602-b342a2e5f04a0e7707981fe8') == ""){
	$apiKey = "m775657602-b342a2e5f04a0e7707981fe8";
}else{
	$apiKey = get_option('uptime_robot_nh_api','m775657602-b342a2e5f04a0e7707981fe8');
}
$url = "http://api.uptimerobot.com/getMonitors?apiKey=". $apiKey . "&logs=1&format=xml&monitors=".get_option('uptime_robot_nh_monitors');
$xml = file_get_contents($url);
$xml = new SimpleXMLElement ($xml);
		
	echo '<table class="table table-hover" width="100%">';
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
		  echo "<tr><td><img src=".plugins_url('img/alert_down.png', __FILE__ )."></td><td style=\"color: ".get_option('uptime_robot_nh_colors_0','#FF0000')."; font-weight:bold;\">". __('down', 'uptime-robot-nh')."</td>";
			}elseif($value[1] == 2){
		  echo "<tr><td><img src=".plugins_url('img/alert_up.png', __FILE__ )."></td><td style=\"color: ".get_option('uptime_robot_nh_colors_100','#006600')."; font-weight:bold;\">". __('up', 'uptime-robot-nh')."</td>";
			}elseif($value[1] == 99){
		  echo "<tr><td><img src=".plugins_url('img/alert_paused.png', __FILE__ )."></td><td style=\"color: ".get_option('uptime_robot_nh_colors_995','#FF8C00')."; font-weight:bold;\">". __('paused', 'uptime-robot-nh')."</td>";
			}elseif($value[1] == 98){
		  echo "<tr><td><img src=".plugins_url('img/alert_log.png', __FILE__ )."></td><td style=\"color: ".get_option('uptime_robot_nh_colors_100','#006600')."; font-weight:bold;\">". __('started', 'uptime-robot-nh')."</td>";
			}
		  echo "<td border=0>".$value[2]."</td>";
		  echo "<td border=0>".date_i18n( get_option( 'date_format' ), $value[0])." ";
		  echo date_i18n( get_option( 'time_format' ), $value[0] ).":";
		  echo date_i18n( 's' , $value[0])."</td></tr>"; $CountLogs--;
		}
	echo "</table>";

}
 
function dashboard_widget_uptime_robot_control() {

	echo "<p>". __('You can change the settings for this widget on the plugin', 'uptime-robot-nh')." <a href='options-general.php?page=uptime-robot-nh'>". __('settings page', 'uptime-robot-nh')."</a>.</p>";

}

function init_sidebar_dashboard_widget_uptime_robot() {
  wp_register_sidebar_widget('uptime-robot-logs-nh', 'Uptime Robot Log Widget', 'dashboard_widget_uptime_robot', array("description" => "Show your uptime monitors logs from Uptime Robot."));
  wp_register_widget_control('uptime-robot-logs-nh', 'Uptime Robot Log Widget', 'dashboard_widget_uptime_robot_control');
}

?>