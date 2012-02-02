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
function make_interface()
{
    global $url_plugin;
    global $base_plugin;
    global $section;
    global $plugin;
    
    
    if(file_exists($base_plugin.'data/google_key.value'))
    {
        $key=file($base_plugin.'data/google_key.value');
    }
    $list='<div class="title">GPS</div>
            <div class="title2">Google key</div>
            <div class="plugin_content">
                <input type="text" value="'.trim($key[0]).'" id="google_key" name="google_key">
                <input type="button" class="bsave" onclick="save_google_key(\''.$section.'\',\''.$plugin.'\',\'output\');" value="Save">
            </div>
            <div id="output"></div>
            ';
    $list.='
            <div class="title2">GPS NMEA Info</div>
            <div class="plugin_content">';
    $list.='<button type="button" id="NMEA_info" onclick="show_nmea(\''.$section.'\',\''.$plugin.'\',\'NMEA_info_output\');">Show NMEA data</button>';
    $list.="<div id='NMEA_info_output' class='nmea'></div>";
    $list.="</div>";
    $list.='<div class="title2">Map geolocation</div>
            <div class="plugin_content">';
    $list.='<button type="button" id="GPS_map_info" onclick="$(\'#map\').addClass(\'map2\');draw_center(\''.$section.'\',\''.$plugin.'\',\'map\');">Show map</button>';
    
    $list.='<div id="map" class="map"></div></div>';
    //$list.='<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=ABQIAAAAxn-X4TVGr_dB3O7qL3vTWBT2yXp_ZAY8_ufC3CFXhHIE1NvwkxQccHTstjr5hNv-96qkCEjZLlXVVg" type="text/javascript"></script>';
    $list.='<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key='.trim($key[0]).'" type="text/javascript"></script>';
	return $list;
}

?>