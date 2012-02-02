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
function make_mac_filter($interface,$selected)
{
    global $url_plugin;
    global $section;
    global $plugin;
    global $base_plugin;

	
	$path=$base_plugin.'data/interfaces_plus_'.$interface; // here is the path of our interfaces extension for mac control

	if (file_exists($path))
	{
		$ini = file( $path );
	}

	$line = trim( $ini[0] );
	$values= explode( ' ', $line,4);
	$values[1]=trim($values[1]);
	$values[2]=trim($values[2]);
	$values[3]=trim($values[3]);

	// now we just gonna generate the html code to show the mac listed in our extensión file

	// the file structure is as shown:
	// 1st line the mode of the list (white/black list)
	// next lines just "up iwpriv interface addmac mac"

	// so we need to know the interface we are making the list for
	$result.= '
        <form id="mac_filter_'.$interface.'" onsubmit="return false;" >
        <div class="title2">MAC filter</div>
        <div class="plugin_content">            
            <input id="mac_filter_check_'.$interface.'" name="mac_filter_check_'.$interface.'" type="checkbox"';
	if ($selected=='yes')
	{
		$result.=' checked';
	}
	$result.=' onchange="check_conditions();"  >Mac Filter</legend>';
	$result.='<div id="mac_filter_hide_'.$interface.'">';
	$result.='<table cellpadding="0" cellspacing="5"><tbody>';
	$result.='<tr><td>Mac list type</td><td>';
	$result.='<select name="mac_list_type_'.$interface.'" id="mac_list_type_'.$interface.'"';
	if (($values[2]=='maccmd')&&($values[3]=='1'))
	{
		$result.='<option value="1" selected="yes">'.Whitelist.'</option>';
		$result.='<option value="2">'.Blacklist.'</option>';
		$tip=1;
	}
	else
	{
		$result.='<option value="1">'.Whitelist.'</option>';
		$result.='<option value="2" selected="yes">'.Blacklist.'</option>';
		$tip=2;
	}
	$result.='</select> ';
	
    $result.='    </td>
                  <td>
                    <input type="button" name="mac_filter_change_but_'.$interface.'" value="Apply" onclick="complex_ajax_call(\'mac_filter_'.$interface.'\',\'output\',\''.$section.'\',\''.$plugin.'\',\'modify_mac\',\''.$interface.'\');"/>
                  </td>
              </tr>';
	$result.='<tr><td><select name="mac_list_'.$interface.'" id="mac_list_'.$interface.'">';
    if (!empty($ini))
    {
        foreach ($ini as $line)
        {
            $line = trim( $line );
            if ( $line == '' || $line{0} == '#' )
                {
                    continue;
                }
            $values= explode( ' ', $line,4);
            $values[1]=trim($values[1]);
            $values[2]=trim($values[2]);
            $values[3]=trim($values[3]);
            if (($values[1]==$interface)&&($values[2]=='addmac'))
            {
                $result.="<option value='".$values[3]."'>".$values[3]."</option>";
            }
        }
    }
	$result.='</select></td><td><input type="button" id="int_save_but_'.$interface.'" style="cursor: pointer;" value="Delete" onclick="complex_ajax_call(\'mac_filter_'.$interface.'\',\'output\',\''.$section.'\',\''.$plugin.'\',\'del_mac\',\''.$interface.'\');" />';
	$result.='</td></tr>';
	$result.="<tr><td>";
	$result.='</td><td><input type="button" name="mac_filter_add_but_'.$interface.'" value="Add MAC" onclick="show_mac()"/></td>';
    $result.='<td><input type=\"text\" name="mac_filter_add_'.$interface.'" id="mac_filter_add_'.$interface.'" maxlength="17"></td></tr>';
    $result.='<tr><td colspan="2"></td><td><div id="mac_filter_add_'.$interface.'_ms_cte"></div></td></tr>
              <tr><td></td><td></td><td><input type="button" id="add_mac_filter_ok" value="Ok" onclick="add_mac();"/></td></tr>';
	$result.='</tbody></table></div></div></form>';
	return $result;
}

?>