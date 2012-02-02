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
    // This will make a select html tag with the name and optios defined.
    if (!empty($onclick_js))
    {
        $select='<select name="'.$name.'" id="'.$name.'" onclick="'.$onclick_js.'">';
    }
    else
    {
        $select='<select name="'.$name.'" id="'.$name.'" >';
    }

    foreach($options as $option)
    {
        if((string)$option==$selected_option)
        {
            $selected='selected="yes"';
        }
        else
        {
            $selected='';
        }
        $select.='<option value="'.$option.'" '.$selected.'>'.$option.'</option>';
    }
    $select.="</select>";

    return $select;
}
function make_select_detailed($name,$options,$selected_option="",$onclick_js="")
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
        if((string)$value==$selected_option)
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
function convert_to_table($data,$id)
{
    // Given an array it will convert the array to a table using each component as row
    // and space or tabs as column separator.
    $pattern_text = '/[\ \t]+/';
    $return='<table id="'.$id.'"><tbody>';
    foreach ($data as $line)
    {
        // Remove \n an not desired separators.
        $line=trim($line);
        $return.='<tr><td>'.preg_replace($pattern_text,'</td><td>',$line).'</td></tr>';
    }
    $return.='</tbody></table>';
    return $return;
}
?>