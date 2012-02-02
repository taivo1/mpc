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

function make_input($interface,$entries)
{
    global $section;
    global $plugin;
    // THE ENTRIES FOR THE DHCP SERVER
    $readonly_css='';
    $list="<div class=\"title2\">DHCP for $interface</div>
            <div class=\"plugin_content\">
            <form id=\"dhcp_configuration\" name=\"dhcp_configuration\">
            <table><tbody>";
        $list.='<tr class="border"><td>';
        $list.="<label for=\"dhcp_server_$interface\">Enable dhcp for $interface?</label>";
        $list.='</td><td>';
        $list.="<input name=\"dhcp_server_$interface\" id=\"dhcp_server_$interface\" type=\"checkbox\"";
        if (!empty($entries[$interface]))
        {
            $list.=" checked";
        }
        else
        {
            $readonly_css='readonly';
        }
        $list.=" onclick=\"make_readonly_fields()\"/>";
        $list.='</td></tr><tr><td>';
        $list.="<label for=\"dhcp_start_$interface\">DHCP start ip address</label>";
        $list.='</td><td>';
        $list.="<input type=\"text\" class=\"ms_mandatory ms_ip $readonly_css\" name=\"dhcp_start_$interface\" id=\"dhcp_start_$interface\" ";
        if ($entries[$interface]['start'])
        {
            $list.=" value=\"".$entries[$interface]['start'];
        }
        $list.="\" $readonly_css />";
        $list.='</td></tr>
                    <td></td><td><div id="dhcp_start_'.$interface.'_ms_cte"></div></td>
                </tr>
                <tr><td>';
        $list.="<label for=\"dhcp_end_$interface\">DHCP end ip address</label>";
        $list.='</td><td>';
        $list.="<input type=\"text\" class=\"ms_mandatory ms_ip $readonly_css\" name=\"dhcp_end_$interface\" id=\"dhcp_end_$interface\" ";
        if ($entries[$interface]['end'])
        {
            $list.=" value=\"".$entries[$interface]['end'];
        }
        $list.="\" $readonly_css />";
        $list.='</td></tr>
                    <td></td><td><div id="dhcp_end_'.$interface.'_ms_cte"></div></td>
                </tr>
                <tr><td>';
        $list.="<label for=\"dhcp_expire_$interface\">DHCP expire time</label>";
        $list.='</td><td>';
        $list.="<input type=\"text\" class=\"ms_mandatory ms_numerical $readonly_css\" name=\"dhcp_expire_$interface\" id=\"dhcp_expire_$interface\" ";
        if ($entries[$interface]['expiration'])
        {
            $list.=" value=\"".$entries[$interface]['expiration'];
        }
        else
        {
            $list.=" value=\"";
        }
        $list.="\" $readonly_css />hours";
        $list.='</td></tr>
                    <td></td><td><div id="dhcp_expire_'.$interface.'_ms_cte"></div>';
    $list.="</td></tr></tbody></table></form>            
            <input class=\"bsave right_align\" type=\"button\" onclick=\"complex_ajax_call('dhcp_configuration','interface','$section','$plugin','save');\" value=\"Save & Apply\" />
            <input class=\"bsave right_align\" type=\"button\" onclick=\"complex_ajax_call('dhcp_configuration','interface','$section','$plugin','save_restart');\" value=\"Save\" />
        </div>";
    return $list;
}

function make_interface()
{
    global $url_plugin;
    global $section;
    global $plugin;
    $options=array(' ','eth0','ath0','ath1');
    $list='
        <div class="title">DHCP</div>
        <div class="title2">Interface configuration</div>
            <div class="plugin_content">
            <label class="btext">Select interface </label>';
    $list.=     make_select('interface_selector',$options,' ');
    $list.='
            <input type="hidden" id="plugin" value="'.$plugin.'" />
            <input type="hidden" id="section" value="'.$section.'" />
            </div>';    
    $list.='<div id="interface">';
    $list.='</div>';    
    return $list;
}
?>