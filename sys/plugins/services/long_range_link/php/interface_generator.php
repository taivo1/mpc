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

function make_input($interface)
{
    global $url_plugin;
    global $section;
    global $plugin;

    // Detect current values for acktimeout, slottime and ctstimeout.
    $acktimeout='';
    exec('/sbin/sysctl dev.wifi'.substr($interface,3,1).'.acktimeout 2>/dev/null',$ret);
    $ret[0]=trim($ret[0]);
    if ($ret[0][0]=='d'){
            $ret2=explode('=',trim($ret[0]));
            $ret2[1]=trim($ret2[1]);
            $acktimeout=$ret2[1];
    }
    unset($ret);
    unset($ret2);
    $ctstimeout='';
    exec('/sbin/sysctl dev.wifi'.substr($interface,3,1).'.ctstimeout 2>/dev/null',$ret);
    $ret[0]=trim($ret[0]);
    if ($ret[0][0]=='d'){
            $ret2=explode('=',trim($ret[0]));
            $ret2[1]=trim($ret2[1]);
            $ctstimeout=$ret2[1];
    }
    unset($ret);
    unset($ret2);
    $slottime='';
    exec('/sbin/sysctl dev.wifi'.substr($interface,3,1).'.slottime 2>/dev/null',$ret);
    $ret[0]=trim($ret[0]);
    if ($ret[0][0]=='d'){
            $ret2=explode('=',trim($ret[0]));
            $ret2[1]=trim($ret2[1]);
            $slottime=$ret2[1];
    }

    $long_range_link_data=parse_long_range_link();
    if($long_range_link_data[$interface]['permanent_changes']=='1')
    {
        $checked='checked';
    }
    $list='<div class="title2">Long range link configuration</div>
            <div class="plugin_content">
            <form id="long_range_link">
            <div>
            <input id="permanent_changes" type="checkbox" '.$checked.' name="permanent_changes"/>
            <label>Make this changes permanents in system</label>
            </div>
            ';

    // Con divs.

    $options=array('Auto','Manual');
    $list.='<div id="select_input_method" class="nl ss">
    <label>Select input method</label>';
    $list.=make_select('input_method',$options,$long_range_link_data[$interface]['input_method']);
    $list.='</div>';
    $list.='<div id="distance" class="nl">                
                <table><tbody>
                    <tr>
                        <td class="table_label nl">
                            Distance (Km)
                        </td>
                        <td>
                            <input type="text" class="ms_numerical" id="distance_value" name="distance_value" value="'.$long_range_link_data[$interface]['distance_value'].'" />
                       </td>
                   </tr>
                    <tr>
                        <td></td>
                        <td><div id="distance_value_ms_cte"></div></td>
                    </tr>
               </tbody></table>
            </div>';
    $list.='<div id="direct_values" class="nl">
                    <table><tbody>
                    <tr>
                        <td class="table_label nl">
                            ACKTIMEOUT
                        </td>
                        <td>
                            <input type="text" class="ms_numerical" name="acktimeout" id="acktimeout" value="'.$acktiemout.'" / >
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td><div id="acktimeout_ms_cte"></div></td>
                    </tr>
                    <tr>
                        <td class="nl">
                            <span>CTSTIMEOUT</span>
                        </td>
                        <td>
                            <input type="text" class="ms_numerical" name="ctstimeout" id="ctstimeout" value="'.$ctstimeout.'" / >
                        </td>
                    </tr>
                    <tr>    
                        <td></td>
                        <td><div id="ctstimeout_ms_cte"></div></td>
                    </tr>
                    <tr>
                        <td class="nl">
                            <span>SLOTTIME</span>
                        </td>
                        <td>
                            <input type="text" class="ms_numerical" name="slottime" id="slottime" value="'.$slottime.'" / >
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td><div id="slottime_ms_cte"></div></td>
                    </tr>
                    </tbody></table>
                
            </div><div class="right_align">
                <input class="bsave" type="button" id="interface_info_'.$interface.'" type="button" onclick="save()" value="Save and apply" />';
    $list.="</div>";
    $list.='</form></div>';
    return $list;
}
function make_interface($interface)
{
    global $url_plugin;
    global $section;
    global $plugin;    
    $list.=make_input($interface);
    return $list;
}
function select_interface()
{
    global $section;
    global $plugin;

    $options=array(' ','ath0','ath1');
    $list='
        <div class="title">Long range link</div>
        <div class="title2">Interface configuration</div>
            <div class="plugin_content">
            <label class="btext">Select interface </label>';
    $list.=make_select('interface_selector',$options,' ');
    $list.='<input type="hidden" id="plugin" value="'.$plugin.'" />
            <input type="hidden" id="section" value="'.$section.'" />
            </div>';
    $list.='<div id="interface">';
    $list.='</div>';
    return $list;
}
?>