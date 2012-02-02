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

function generate_interface()
{
    global $url_plugin;
    global $base_plugin;
    global $section;
    global $plugin;
    
    
    $list='<div class="title">JOIN NETWORKS</div>';
    $list.='<div id="plugin_content">';
    $list.=generate_rules_maker();
    $list.='<div id="rules_container" class="border">';
    $list.=load_rules();
    $list.='</div></div>';
    
    return $list;
}

function load_rules()
{
    global $url_plugin;
    global $base_plugin;
    global $section;
    global $plugin;
    include_once $base_plugin.'php/load_rule.php';
    
    $output='<p id="rules_container_title">Joined networks</p>';
    $conf_file=$base_plugin.'data/join.conf';
    $rules=file($conf_file);    
    $rule_number='0';
    foreach($rules as $rule)
    {
        $join_array=explode('|',trim($rule));
        $output.=load_rule($join_array,$rule_number);
        $rule_number++;
    }
    
    return $output;
}

function generate_rules_maker()
{
    global $url_plugin;
    global $base_plugin;
    global $section;
    global $plugin;
    $output='<div class="rules_maker">';


    $output.='
        <div id="generator_table" class="border">
            <table  cellspacing="15"><tbody>
                <tr>
                    <td class="td_width">1. Select the first interface to join</td>
                    <td class="td_width">2. Choose the communication direction</td>
                    <td class="td_width">3. Select the second interface to join</td>
                </tr>
                <tr>
                    <td>
                        <img src="'.$url_plugin.'images/j_gprs.png" alt="ppp0" title="GPRS" class="litem click" />
                    </td>
                    <td rowspan="4">
                        <div id="arrow_main_container">
                            <div id="arrow_vert_center">
                                <img src="'.$url_plugin.'images/j_dcha.png" alt="Right" title="Right connection" class="direction click arrow"/>
                                <br />
                                <img src="'.$url_plugin.'images/j_flecha.png" alt="Bidirectional" title="Double connection" class="direction click arrow" />
                                <br />
                                <img src="'.$url_plugin.'images/j_izda.png" alt="Left" title="Left connection" class="click arrow" />
                            </div>
                        </div>
                    </td>
                    <td>
                        <img src="'.$url_plugin.'images/j_gprs.png" alt="ppp0" title="GPRS" class="ritem click" />
                    </td>
                </tr>
                <tr>
                    <td>
                        <img src="'.$url_plugin.'images/j_ethernet.png" alt="eth0" title="ethernet" class="litem click" />
                    </td>
                    <td>
                        <img src="'.$url_plugin.'images/j_ethernet.png" alt="eth0" title="ethernet" class="ritem click" />
                    </td>
                </tr>
                <tr>
                    <td>
                        <img src="'.$url_plugin.'images/j_wifi1.png" alt="ath0" title="wifi1" class="litem click" />
                    </td>
                    <td>
                        <img src="'.$url_plugin.'images/j_wifi1.png" alt="ath0" title="wifi1" class="ritem click" />
                    </td>
                </tr>
                <tr>
                    <td>
                        <img src="'.$url_plugin.'images/j_wifi2.png" alt="ath1" title="wifi2" class="litem click" />
                    </td>
                    <td>
                        <img src="'.$url_plugin.'images/j_wifi2.png" alt="ath1" title="wifi2" class="ritem click" />
                    </td>
                </tr>
            </tbody></table>            
        </div>
        <div class="aright">
            <input class="bsave" type="button" value="Save & apply" onclick="save_rule(\''.$section.'\',\''.$plugin.'\');">
        </div>
    ';

    $output.='</div>
            
';
    return $output;
}


?>