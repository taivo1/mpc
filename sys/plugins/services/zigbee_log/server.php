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

// Predefined variables:
// $section contains the section folder name.
// echo "section=".$section."<br>";
// $plugin contains the plugin folder name.
// echo "plugin=".$plugin."<br>";
// $section and $plugin can be used to make a link to this plugin by just reference
// echo "<a href=\"index.php?section=$section&plugin=$plugin\">This plugin</a>"."<br>";
// $base_plugin contains the path that must be used as start to includes for
// plugin includes that need the local path.
// example: include_once $base_plugin.'php/my_include.php';
// echo "base_plugin=".$base_plugin."<br>";
// $url_plugin contains the url base that must be used to include html items
// such as images.
// example: <img src="'.url_plugin.'images/my_image.png">
// echo "url_plugin=".$url_plugin."<br>";
// $API_core contains the path to the core API folder.
// example: include_once $API_core.'is_active.php';
// echo "API_core=".$API_core."<br>";

// Plugin server produced data will returned to the ajax call that made the
// request.
include_once $API_core.'complex_ajax_return_functions.php';
include_once $API_core.'json_api.php';
include_once $API_core.'form_fields_check.php';

function satinize_filename($filename)
{
    return preg_replace('/[^0-9a-z\.\_\-\/]/i','',$filename);
}
function remove_double_cuotes($data)
{
    return preg_replace('/[\"\n]/','',$data);
}
function load_configuration()
{
    global $base_plugin;
    $configuration=Array();
    if (file_exists($base_plugin.'data/zigbee_log_etc.conf'))
    {
        $file=file($base_plugin.'data/zigbee_log_etc.conf');
        foreach($file as $line)
        {
            $data=explode('=',$line);
            if (count($data)=='2')
            {
                $configuration[$data[0]]=remove_double_cuotes($data[1]);
            }
        }
    }
    return $configuration;
}

function save_configuration($configuration)
{
    global $base_plugin;
    $fp=fopen($base_plugin.'data/zigbee_log_etc.conf','w');
    $data_to_save='#!/bin/bash
exec="'.$configuration['exec'].'"
port="'.$configuration['port'].'"
path="'.$configuration['path'].'"
';
    fwrite($fp,$data_to_save);
    fclose($fp);
}


function make_init_script($filename)
{
    // Makes a bash script to load the zigbee log on file described by filename.
    
    $bash_script='#!/bin/bash
squidbeeGW S0 >> '.$filename.' &
    ';
}


if ($_POST['type']=="complex")
{
    // Faltaría modificar el script que se ejecuta al inicio con los nuevos valores y ejecutarlo.
    $filename=jsondecode($_POST['form_fields']);
    $fields_check_types = Array (        
        'filename'  => Array ('ms_path')
        );
    if(are_form_fields_valid ($filename, $fields_check_types))
        {
        $out='alert("Not valid filename provided\nData not saved!")';
        $filename['filename']=satinize_filename($filename['filename']);
        if (!empty($filename['filename'])&&($filename['filename']!='.')&&($filename['filename']!='..'))
        {
            $fp=fopen($base_plugin.'data/zigbee_log.conf','w');
            fwrite($fp,$filename['filename']);
            fclose($fp);
            $configuration=load_configuration();            
            $configuration['path']=$filename['filename'];
            save_configuration($configuration);
            exec('sudo cp '.$base_plugin.'data/zigbee_log_etc.conf /etc/zigbee_log.conf');
            $out='alert("Data saved");';
        }
        response_additem("script", $out);
        response_additem("value", $filename['filename'],'filename');
        }
    response_return();
}
elseif ($_POST['type']=="zigbee_log_off")
{
    exec('sudo killall -9 squidBeeGW');
    // Quitar los permisos de ejecución al script que corre automáticamente el servicio.
    $configuration=load_configuration();
    $configuration['exec']='false';
    save_configuration($configuration);
    exec('sudo cp '.$base_plugin.'data/zigbee_log_etc.conf /etc/zigbee_log.conf');
    $output='<label>Zigbee sniffer status</label><input type="button" value="Start log" onclick="start_zigbee_log(\''.$section.'\',\''.$plugin.'\')" />';
    response_additem("html", $output,'status');
    response_additem("script", 'alert("Zigbee log stopped.")');
    response_return();
}
elseif ($_POST['type']=="zigbee_log_on")
{
    // Dar permisos de ejecución al script que corre el servicio al iniciar
    $configuration=load_configuration();
    $configuration['exec']='true';
    save_configuration($configuration);
    exec('sudo cp '.$base_plugin.'data/zigbee_log_etc.conf /etc/zigbee_log.conf');
    // iniciar el servicio.
    exec('sudo /etc/init.d/zigbee_log.sh');
    // Change button
    $output='<label>Zigbee sniffer status</label><input type="button" value="Stop log" onclick="stop_zigbee_log(\''.$section.'\',\''.$plugin.'\')" />';
    response_additem("html", $output,'status');
    response_additem("script", 'alert("Zigbee log started.")');
    response_return();
}
elseif ($_POST['type']=="load_zigbee_data")
{
    if (file_exists($base_plugin.'data/zigbee_log.conf'))
    {
        $config=file($base_plugin.'data/zigbee_log.conf');
    }
    exec('tail '.$config[0],$zigbee_data_output);
    foreach($zigbee_data_output as $line)
    {
        $zigbee_log_data.=$line.'<br />';
    }
    if(empty($zigbee_log_data))
    {
        $zigbee_log_data='File does not exist';
    }
    response_additem("html", $zigbee_log_data ,'zigbee_log_content');
    response_return();
}
?>
