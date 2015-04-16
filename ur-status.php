<?php defined('ABSPATH') or die("No script kiddies please!");

function uptime_robot_nh_widget() {

echo "<div class='table-responsive'><table width=\"100%\" class=\"table table-hover\"><tr>
<th style=\"text-align: left;\">". __('Monitor', 'uptime-robot-nh')."</th>";
	if(get_option('uptime_robot_nh_columns_status') == 1){
echo "<th style=\"text-align: left;\">". __('Status', 'uptime-robot-nh')."</th>";
	} if(get_option('uptime_robot_nh_columns_type') == 1){
echo "<th style=\"text-align: left;\">". __('Type', 'uptime-robot-nh')."</th>";
	} $GetUptimes = ""; if(get_option('uptime_robot_nh_days_one') == 1){ $GetUptimes = $GetUptimes."1-";
echo "<th style=\"text-align: left;\">". __('Today', 'uptime-robot-nh')."</th>";
	} if(get_option('uptime_robot_nh_days_week') == 1){ $GetUptimes = $GetUptimes."7-";
echo "<th style=\"text-align: left;\">". __('7 days', 'uptime-robot-nh')."</th>";
	} if(get_option('uptime_robot_nh_days_month') == 1){ $GetUptimes = $GetUptimes."30-";
echo "<th style=\"text-align: left;\">". __('30 days', 'uptime-robot-nh')."</th>";
	} if(get_option('uptime_robot_nh_days_year') == 1){ $GetUptimes = $GetUptimes."365-";
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
  echo "<td style=\"color: ".get_option('uptime_robot_nh_colors_font','#6B6B6B').";\">";
  echo $monitor['friendlyname'];
  echo "</td>";
	if(get_option('uptime_robot_nh_columns_status') == 1){
  echo "</td><td>";
  if ($monitor['status'] == 2) {
    echo "<div style=\"color: ".get_option('uptime_robot_nh_colors_online','#006600')."; font-weight:bold;\">". __('Online', 'uptime-robot-nh'). "</div>";
  }
  elseif ($monitor['status'] == 9) {
    echo "<div style=\"color: ".get_option('uptime_robot_nh_colors_offline','#FF0000')."; font-weight:bold;\">". __('Offline', 'uptime-robot-nh'). "</div>";
  }
  elseif ($monitor['status'] == 0) {
    _e('Paused', 'uptime-robot-nh');
  }
  else {
    _e('n&#47a', 'uptime-robot-nh');
  }
	} if(get_option('uptime_robot_nh_columns_type') == 1){
  echo "<td style=\"color: ".get_option('uptime_robot_nh_colors_font','#6B6B6B').";\">";
  if ($monitor['type'] == 1) {
    _e('HTTP(s)', 'uptime-robot-nh'); echo "</td>";
  }elseif ($monitor['type'] == 2) {
    _e('Keyword', 'uptime-robot-nh'); echo "</td>";
  }elseif ($monitor['type'] == 3) {
    _e('PING', 'uptime-robot-nh'); echo "</td>";
  }elseif ($monitor['subtype'] == 1) {
    _e('SSL', 'uptime-robot-nh'); echo " (80)</td>";
  }elseif ($monitor['subtype'] == 2) {
    _e('PORT', 'uptime-robot-nh'); echo " (443)</td>";
  }elseif ($monitor['subtype'] == 3) {
    _e('FTP', 'uptime-robot-nh'); echo " (21)</td>";
  }elseif ($monitor['subtype'] == 4) {
    _e('MAIL', 'uptime-robot-nh'); echo " (25)</td>";
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
  if ($uptime == 100) {
    echo "<div style=\"color: ".get_option('uptime_robot_nh_colors_100','#006600')."; font-weight:bold;\">100%</div></td>";
  }elseif ($uptime > 99.9) {
    echo "<div style=\"color: ".get_option('uptime_robot_nh_colors_999','#009933')."; font-weight:bold;\">" . $uptime . "%</div></td>";
  }elseif($uptime > 99.5) {
    echo "<div style=\"color: ".get_option('uptime_robot_nh_colors_995','#FF8C00')."; font-weight:bold;\">" . $uptime . "%</div></td>";
  }else{
    echo "<div style=\"color: ".get_option('uptime_robot_nh_colors_0','#FF0000')."; font-weight:bold;\">" . $uptime . "%</div></td>";
  }
 }
}
}

echo "</table></div>";

 echo '<p style="text-align: right;">'; 
if(get_option('uptime_robot_nh_thanks_1') == "0"){ _e('Realtime monitoring by <a href="http://uptimerobot.com/" target="_blank" title="Uptime Robot Free Website Monitor Service">Uptime Robot</a>', 'uptime-robot-nh'); }
if(get_option('uptime_robot_nh_thanks_2') == "0"){ _e(' Plugin by <a href="https://wordpress.org/plugins/uptime-robot-monitor/" target="_blank" title="Show your uptime stats and logs from Uptime Robot on your wordpress pages, posts or in a widget.">Niels Hoogenhout.nl</a>', 'uptime-robot-nh'); }
 echo '</p>'; 


}
 
function uptime_robot_nh_widget_control() {

	echo "<p>". __('You can change the settings for this widget on the plugin', 'uptime-robot-nh')." <a href='options-general.php?page=uptime-robot-nh'>". __('settings page', 'uptime-robot-nh')."</a>.</p>";

}

function init_sidebar_widget_uptime_robot_nh() {
  wp_register_sidebar_widget('uptime-robot-nh', 'Uptime Robot Widget', 'uptime_robot_nh_widget', array("description" => "Show your uptime monitors from Uptime Robot."));
  wp_register_widget_control('uptime-robot-nh', 'Uptime Robot Widget', 'uptime_robot_nh_widget_control');
}

?>