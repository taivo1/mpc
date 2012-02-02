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
function make_select($name,$options,$selected_option="",$onclick_js="")
{
    if ($onclick_js!='')
    {
        $select='<select name="'.$name.'" id="'.$name.'" onclick="'.$onclick_js.'">';
    }
    else
    {
        $select='<select name="'.$name.'" id="'.$name.'" >';
    }
    
    foreach($options as $value=>$option)
    {
        if($value==$selected_option)
        {
            $selected='selected="yes"';
        }
        else
        {
            $selected='';
        }
        $select.='<option value="'.$value.'" '.$selected.'>'.$option.'</option>';
    }
    $select.="</select>";

    return $select;
}
function make_interface()
{
    global $url_plugin;
    global $section;
    global $plugin;
    global $base_plugin;

    $old_values=parse_xbee_conf();
    //echo "<pre>".print_r($old_values,true)."</pre>";
    //flush();
    $values=get_xbee($old_values['port'],$old_values['atbd']);
    //echo "<pre>".print_r($values,true)."</pre>";

	$list='<div class="title">Zigbee</div>
            <form id="xbee_configuration">
                <div class="title2">Xbee PRO configuration</div>
                <div class="plugin_content">
                    <table><tbody>
                    <tr>
';/* This code make possible to select different ports for Xbee pro interface.
                    <td>
                    <label>Port:</label>
                    </td><td>';
    $options=array('S0'=>'S0','S1'=>'S1','USB0'=>'USB0','USB1'=>'USB1');
    $list.=make_select('port',$options,$values['port'],"");
    $list.='
                    </td></tr>
                    <tr>
 * 
 * */
    $list.='        <td>
                        <input type="hidden" name="port" value="S0">
                        <input type="hidden" name="old_speed" value="'.$values['atbd'].'">
                            <label>ATBD: XBee speed</label></td><td>';
                            unset($options);
                            $options=array('0'=>'1200bps','1'=>'2400bps','2'=>'4800bps','3'=>'9600bps','4'=>'19200bps','5'=>'38400bps','6'=>'57600bps','7'=>'115200bps');
                            $list.=make_select('atbd',$options,$values['atbd'] ,"");
                            $list.='
                    </td></tr>
                    <tr><td>
                        <label>ATID: Net identificator</label></td><td>
                        <input type="text" class="ms_hex" name="atid" id="atid" value="'.$values['atid'].'" />
                    </td><td>
                        <div id="atid_ms_cte"></div>
                    </td></tr><tr><td>
                        <label>ATCH: Channel</label></td><td>';
                        unset($options);
                        $options=array('b'=>'0x0B','c'=>'0x0C','d'=>'0x0D','e'=>'0x0E','f'=>'0x0F','10'=>'0x10','11'=>'0x11','12'=>'0x12','13'=>'0x13','14'=>'0x14','15'=>'0x15','16'=>'0x16','17'=>'0x17','18'=>'0x18');
                        $list.=make_select('atch',$options,$values['atch'] ,"");
                        $list.='
                    </td></tr><tr><td>
                        <label>ATMY: Module network address</label></td><td>
                        <input type="text" class="ms_hex" name="atmy" id="atmy" value="'.$values['atmy'].'" />
                    </td><td>
                        <div id="atmy_ms_cte"></div>
                    </td></tr><tr><td>
                        <label>ATNI: Node identifier</label></td><td>
                        <input type="text" name="atni" id="atni" value="'.$values['atni'].'" />
                    </td><td>
                        <div id="atni_ms_cte"></div>
                    </td></tr><tr><td>
                        <label>ATPL: Power Level</label></td><td>';
                        unset($options);
                        $options=array('0','1','2','3','4');
                        $list.=make_select('atpl',$options,$values['atpl'] ,"");
                        $list.='
                    </td></tr><tr><td>
                        <label>ATEE: Encrypted mode</label></td><td>';
                        unset($options);
                        $options=array('0'=>'Off','1'=>'On');
                        $list.=make_select('atee',$options,$values['atee'] ,"");
                        $list.='                        
                    </td></tr><tr><td>                        
                        <label>ATKY: Encrypt key</label></td><td>
                        <input type="text" name="atky" id="atky" value=""  />
                    </td></tr><tr><td>
                        <label>ATSH: Mac high</label></td><td>
                        <input type="text" name="atsh" id="atsh" disabled value="'.$values['atsh'].'" readonly />
                    </td></tr><tr><td>
                        <label>ATSL: Mac low</label></td><td>
                        <input type="text" name="atsl" id="atsl" disabled value="'.$values['atsl'].'" readonly />
                    </td></tr></tbody></table>
                </form>
                <div class="right_align">
                    <input class="bsave" type="button" value="Save & apply" onclick="complex_ajax_call(\'xbee_configuration\',\'output\',\''.$section.'\',\''.$plugin.'\',\'save\')"/>
                </div>
            </div>
            
            
            <div class="title2">Run your own commands</div>
                <div class="plugin_content">';
    
    
    /*
    $list.="<table><tbody><tr>";
    unset($options);
    $list.="<td><label>Port</label></td><td>";
    $options=array('S0'=>'S0','S1'=>'S1','USB0'=>'USB0','USB1'=>'USB1');
    $list.=make_select('port2',$options,$values['port'],"");
    unset($options);
    $list.="</td></tr><tr>";
    
    $list.="<td><label>Port speed</label></td><td>";
    $options=array('0'=>'1200bps','1'=>'2400bps','2'=>'4800bps','3'=>'9600bps','4'=>'19200bps','5'=>'38400bps','6'=>'57600bps','7'=>'115200bps');
    $list.=make_select('speed',$options,$values['atbd'],"");
     */
    $options=array('0'=>'1200bps','1'=>'2400bps','2'=>'4800bps','3'=>'9600bps','4'=>'19200bps','5'=>'38400bps','6'=>'57600bps','7'=>'115200bps');
    $list.='<div>
                <form id="run_custom_at">
                    <div>
                        <input type="hidden" id="port2" name="port2" value="S0" />
                        <input type="hidden" id="speed" name="speed" value="'.$values['atbd'].'" />
                        <label>Current XBee speed: '.$options[$values['atbd']].'</label>
                    </div>
                    <div id="own_at">
                        <div>
                            <input type="text" name="own_at_0" class="own_at" />
                        </div>
                    </div>
                </form>
                <div class="ss nll">
                    <input  type="button" onclick="reset_own_at()" value="Reset" />
                    <input  type="button" onclick="add_one_more_own_at()" value="Add command line" />
                </div>                
            </div>            
            <div class="right_align">
                <input type="button" class="bsave" value="Run commands" onclick="complex_ajax_call(\'run_custom_at\',\'output\',\''.$section.'\',\''.$plugin.'\',\'run_commands\')"/>
            </div>
            <div id="output"></div>
            <div>';
    return $list;
}
?>