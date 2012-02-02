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
function addInputGprs()
{
    global $section;
    global $plugin;
    global $url_plugin;
    global $operators_file_path;
    global $base_plugin;

	$data=list_operators($operators_file_path);
    
    $path='/etc/wvdial.conf';
    $path=$base_plugin.'data/wvdial.conf';
	$known_operator=parse_wvdial($path);    
    $list='<div class="title">GPRS</div>';
    //$list.='<pre>'.print_r($data,true).'</pre>';
    //$list.='<pre>'.print_r($known_operator,true).'</pre>';
    $list.='<div id="plugin_content">';
	$list.='<form id="gprs" name="gprs"><table style="text-align: left;" border="0" cellpadding="2" cellspacing="2"><tbody><tr>';
	$list.="<td colspan='3' rowspan='1'>";
	$list.="<p class='advice'>Connectivity information from operators provided without warranty.</p>";
	$list.="</td></tr>";
	$list.="<tr><td>Select country</td><td>";
	$list.=add_countries($data,$known_operator['country']);
	$list.="</td></tr>";
	$list.="<tr><td>Choose operator</td><td>";
	$list.="<div id='add_operators'>";
	$list.=add_operators($data,$known_operator['country'],$known_operator['operator']);
	$list.="</div>";
	$list.="</td></tr>";
    $list.="<tr><td></td><td class='ss'>";
    $list.='<span class="ref" onclick="allow_edit();">Click here</span> to edit';
    $list.="</td></tr>";
	$list.="<tr><td>Card PIN</td><td><input type='text' name='PIN' id='PIN' class='readonly ms_numerical' value='".$known_operator['pin']."' readonly /></td><td class='advice'>Leave it empty for no PIN</td>";
	$list.="</tr><tr>";
    $list.='<td></td><td><div id="PIN_ms_cte"></div></td>';
    $list.="</tr><tr>";
    $list.="<tr><td>Username</td><td> <input type='text' name='username' id='username' class='readonly' value='".$known_operator['username']."' readonly /></td><td class='advice'>Should be provided by your operator.";
    $list.="</td></tr>";
    $list.='<td></td><td><div id="username_ms_cte"></div></td>';
    $list.="</tr><tr>";
    $list.="<tr><td>Password</td><td><input type='text' name='password' id='password' class='readonly' value='".$known_operator['password']."' readonly /></td><td class='advice'>Should be provided by your operator.";
    $list.="</td></tr>";
    $list.='<td></td><td><div id="password_ms_cte"></div></td>';
    $list.="</tr><tr>";
    $list.="<tr><td>Phone</td><td><input type='text' name='phone' id='phone' class='readonly ms_mandatory' value='".$known_operator['phone']."' readonly /></td><td class='advice'>Should be provided by your operator.";
    $list.="</td></tr>";
    $list.='<td></td><td><div id="phone_ms_cte"></div></td>';
    $list.="</tr><tr>";
    $list.="<tr><td>Init</td><td><input type='text' name='init1' id='init1' class='readonly ms_mandatory' value='".$known_operator['init2']."' readonly /></td><td class='advice'>If more than one init is required you should edit wvdial.conf manually .";
    $list.="</td></tr>";
    $list.='<td></td><td><div id="init1_ms_cte"></div></td>';
    $list.="</tr><tr>";
    $list.="<tr><td>Dial</td><td><select id='dial' name='dial' class='readonly' readonly />";
	if ($known_operator['dial'])
	{
		if ($known_operator['dial']=='ATD')
		{
			$list.="<option value='atd' selected='yes'>ATD</option>";
    		$list.="<option value='atdt'>ATDT</option>";
		}
		else
		{
			$list.="<option value='atd'>ATD</option>";
    		$list.="<option value='atdt' selected='yes'>ATDT</option>";
		}
	}
	else
	{
    	$list.="<option value='atd'>ATD</option>";
    	$list.="<option value='atdt'>ATDT</option>";
	}
    $list.="</select></td><td class='advice'>Should be provided by your operator.";
    $list.="</td></tr>";
	$list.="</tbody></table></form>";
    $list.='<div class="right_align">';
    $list.='<input type="button" class="bsave" onclick="complex_ajax_call(\'gprs\',\'save\',\''.$section.'\',\''.$plugin.'\',\'output\')" value="save">';
    $list.='<input type="button" class="bsave" onclick="complex_ajax_call(\'gprs\',\'save_restart\',\''.$section.'\',\''.$plugin.'\',\'output\')" value="save & Apply">';
    $list.='</div></div>';
	return $list;
}

function add_countries($data,$known_country='')
{
    global $section;
    global $plugin;
	//$list.="<select name=country_list id=country_list onchange=\"refresh_gprs('country'); check_opt('configure','gprs')\">";
    $list.="<select name='country_list' id='country_list' onchange=\"complex_ajax_call('gprs','country','$section','$plugin','output')\">";
	$list.='<option value="other">Other</option>';
    $country=explode('//',$data['list']);
    
	foreach($country as $i)
	{
		$list.='<option value="'.$i.'"';
        if ($known_country==$i)
            {
                $list.='selected="yes" ';
            }
        $list.='>'.$i.'</option>';
	}
	$list.='</select>';
	return $list;
}

function add_operators($data,$country='',$known_operator='')
{
    global $section;
    global $plugin;
	$list.="<select name='country_operators' id='country_operators' onchange=\"complex_ajax_call('gprs','operator','$section','$plugin','output')\">";
    $list.='<option value="other">Other</option>';
	if ($country!='')
		{
		$country_ops=explode('//',$data[$country]['list']);
		foreach($country_ops as $i)
		{
			$list.='<option value="'.$i.'" ';
            if ($known_operator==$i)
            {
                $list.='selected="yes" ';
            }
            $list.='>'.$i.'</option>';
		}
	}
	$list.='</select>';
	return $list;
}
?>