<?php
/*
 *  Copyright (C) 2008 Libelium Comunicaciones Distribuidas S.L.
 *  http://www.libelium.com
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 *  Version 0.1
 *  Author: Octavio Benedi Sanchez
 */


// Those variables will help you to load css, javascript and some page information
//
// $_main_title This variable will load the page title.
$_main_title="Music Played Daemon";

// You can define an array with the css files you want to load. The css must be
// on the plugin css folder.
// $_plugin_css=Array('plugin_1.css','plugin_2.css');
// Will load files
// plugins/section_name/plugin_name/css/plugin1.css
// plugins/section_name/plugin_name/css/plugin1.css
$_plugin_css=Array("basic.css");

// You can define an array with the javascript files you want to load.
// javascript files must be under the plugin javascript folder
// $_plugin_javascript=Array('plugin_1.js','plugin_2.js');
// Will load files
// plugins/section_name/plugin_name/javascript/plugin1.js
// plugins/section_name/plugin_name/javascript/plugin1.js
//$_plugin_javascript=Array("jquery-1.3.2.min.js","ajax.js");
$_plugin_javascript=Array("jquery-1.3.2.min.js","jquery.json-1.3.min.js","ajax.js","json_encode.js");


// Predefined variables:
// $section contains the section folder name.
// $plugin contains the plugin folder name.
// $section and $plugin can be used to make a link to this plugin by just reference
// $html="<a href=\"index.php?section=$section&plugin=$plugin\">This plugin</a>";
// $base_plugin contains the path that must be used as start to includes for
// plugin includes that need the local path.
// example: include_once $base_plugin.'php/my_include.php';
// $url_plugin contains the url base that must be used to include html items
// such as images.
// example: <img src="'.url_plugin.'images/my_image.png">
// $API_core contains the path to the core API folder.
// example: include_once $API_core.'is_active.php';

// Plugin produced data will be output between a <div> structure.
// <div>
//      Plugin output will be here.
// </div>

// Once plugin is finished core will check $html variable and output its content if any is stored.
// Is better to use $html variable to avoid direct call of the plugin from browsers.

//exec ('mpc play', $cur_play);
exec ('mpd --version', $mpd_version);
exec ('sudo cat /etc/mpd.conf',$mpdconf_file);
foreach($mpdconf_file as $line)
{
    $mpdconf.=$line."\n";
}


$html='
<div class="title">Music Player Daemon</div>
<div class="title2">Now Playing</div>
<script type="text/javascript">
var t = 0;
function addEvent(obj, evType, fn){ 
 if (obj.addEventListener){ 
   obj.addEventListener(evType, fn, false); 
   return true; 
 } else if (obj.attachEvent){ 
   var r = obj.attachEvent("on"+evType, fn); 
   return r; 
 } else { 
   return false; 
 } 
}
function info_timer()
{
	simple_ajax_call(\'info\',\'\',\'now_playing\',\''.$section.'\',\''.$plugin.'\');
	var t=setTimeout("info_timer()",1000);
}
addEvent(window, "load", info_timer);

function status_timer()
{
	simple_ajax_call(\'status\',\'\',\'status\',\''.$section.'\',\''.$plugin.'\');
	var t=setTimeout("status_timer()",5000);
}
addEvent(window, "load", status_timer);

function mpc_play()
{
	simple_ajax_call(\'play\',\'\',\'now_playing\',\''.$section.'\',\''.$plugin.'\');
}

function mpc_pause()
{
	simple_ajax_call(\'pause\',\'\',\'now_playing\',\''.$section.'\',\''.$plugin.'\');
}

function mpc_prev()
{
	simple_ajax_call(\'prev\',\'\',\'now_playing\',\''.$section.'\',\''.$plugin.'\');
}

function mpc_next()
{
	simple_ajax_call(\'next\',\'\',\'now_playing\',\''.$section.'\',\''.$plugin.'\');
}

function mpc_stop()
{
	simple_ajax_call(\'stop\',\'\',\'now_playing\',\''.$section.'\',\''.$plugin.'\');
}

function mpc_louder()
{
	simple_ajax_call(\'louder\',\'\',\'now_playing\',\''.$section.'\',\''.$plugin.'\');
}

function mpc_softer()
{
	simple_ajax_call(\'softer\',\'\',\'now_playing\',\''.$section.'\',\''.$plugin.'\');
}

function mpc_repeat()
{
	simple_ajax_call(\'repeat\',\'\',\'now_playing\',\''.$section.'\',\''.$plugin.'\');
}

function mpc_shuffle()
{
	simple_ajax_call(\'shuffle\',\'\',\'now_playing\',\''.$section.'\',\''.$plugin.'\');
}

function mpc_random()
{
	simple_ajax_call(\'random\',\'\',\'now_playing\',\''.$section.'\',\''.$plugin.'\');
}

function mpc_update()
{
	simple_ajax_call(\'update\',\'\',\'now_playing\',\''.$section.'\',\''.$plugin.'\');
}

function mpd_start()
{
	simple_ajax_call(\'mpd_start\',\'\',\'status\',\''.$section.'\',\''.$plugin.'\');
}

function mpd_stop()
{
	simple_ajax_call(\'mpd_stop\',\'\',\'status\',\''.$section.'\',\''.$plugin.'\');
}

function mpd_restart()
{
	simple_ajax_call(\'mpd_restart\',\'\',\'status\',\''.$section.'\',\''.$plugin.'\');
}


</script>
<div class="plugin_content">
<pre><div id="now_playing"></div></pre>
</div>
<div class="title2">
<table><tr>
<td class="media_button" onclick="mpc_prev()">
<img src="'.$url_plugin.'images/media-skip-backward.png" title="Previous"/>
</td>
<td class="media_button" onclick="mpc_stop()">
<img src="'.$url_plugin.'images/media-playback-stop.png" title="Stop"/>
</td>
<td class="media_button" onclick="mpc_play()">
<img src="'.$url_plugin.'images/media-playback-start.png" title="Play"/> 
</td>
<td class="media_button" onclick="mpc_pause()">
<img src="'.$url_plugin.'images/media-playback-pause.png" title="Pause"/>
</td>
<td class="media_button" onclick="mpc_next()">
<img src="'.$url_plugin.'images/media-skip-forward.png" title="Next"/>
</td>
<td>&nbsp;&nbsp;&nbsp;</td>
<td class="media_button" onclick="mpc_repeat()">
<img src="'.$url_plugin.'images/media-playlist-repeat.png" title="Repeat on/off"/>
</td>
<td class="media_button" onclick="mpc_random()">
<img src="'.$url_plugin.'images/media-playlist-shuffle.png" title="Shuffle on/off"/>
</td>
<td>&nbsp;&nbsp;&nbsp;</td>
<td class="media_button" onclick="mpc_softer()">
<img src="'.$url_plugin.'images/audio-volume-low.png" title="Volume -"/>
</td>
<td class="media_button" onclick="mpc_louder()">
<img src="'.$url_plugin.'images/audio-volume-high.png" title="Volume +"/>
</td>
<td class="media_button_long" onclick="mpc_update()">
Update Database
</td>
</tr>
</table>
</div>
<div class="title2">Service Status</div>
<div class="plugin_content"><pre>
<div id="status"></div></pre></div>
<div class="title2">
<table></tr>
<td class="media_button_medium" onclick="mpd_start()">
Start Service</td>
<td class="media_button_medium" onclick="mpd_stop()">
Stop Service</td>
<td class="media_button_medium" onclick="mpd_restart()">
Restart Service</td>
</tr></table>
</div>

<div class="title2">MPD Configuration File</div>
<div class="plugin_content">
<form name="mpdconf_editor" id="mpdconf_editor" onsubmit="return false;" >
<table><tbody>
<tr><td><label>Edit /etc/mpd.conf</label></td></tr>
<tr><td>
<textarea id="mpdconf" name="mpdconf" class="editor">' . $mpdconf . '</textarea>
</td></tr></tbody></table>
</form>
<input type="button" 
	class="bsave" 
	value="Save and apply" 
	onclick="complex_ajax_call(\'mpdconf_editor\',\'output\',\''.$section.'\',\''.$plugin.'\')" />
</div>

<div class="title2">Version</div>
<div class="plugin_content"><pre>
'.implode('<br/>', $mpd_version).'</pre></div>


';
// $html will be printed by core if $html is defined. But you can uncomment following
// lines if you know what you are doing.
//echo $html;
// unset($html);
?>
