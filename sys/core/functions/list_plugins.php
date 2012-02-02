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
function list_plugins($container_folder)
{
    $plugins = array();
    //$plugins['base']=$container_folder;
    $ignore_dirs = array(".", "..");
    // container folder is the folder that will be processed.
    $files=scandir($container_folder);
    //echo "<pre>".print_r($files,true)."</pre>";
    $i=0;
    foreach ($files as $file)
    {
        // just looking for directories other than . and ..
        if ((is_dir($container_folder.'/'.$file))&&(!in_array($file,$ignore_dirs)))
        {
            // echo "$file lo es";
            //Check for a configuration file.
            if(file_exists($container_folder.'/'.$file.'/configuration.php'))
            {
                include $container_folder.'/'.$file.'/configuration.php';
                if (($type=="PLUGIN")&&(file_exists($container_folder.'/'.$file.'/'.$plugin_main_file))&&(file_exists($container_folder.'/'.$file.'/'.$plugin_server_file)))
                {
                    $plugins[$i]['name']=$plugin_name;
                    $plugins[$i]['version']=$plugin_version;
                    $plugins[$i]['author']=$plugin_author;
                    $plugins[$i]['description']=$plugin_description;
                    $plugins[$i]['main_file']=$plugin_main_file;
                    $plugins[$i]['server_file']=$plugin_server_file;
                    $plugins[$i]['icon']=$plugin_icon;
                    $plugins[$i]['icon_hv']=$plugin_icon_selected;
                    $plugins[$i]['folder']=$file;
                }
            }
            $i++;
        }
        else
        {
            //echo "$file no lo es";
        }
    }
    return $plugins;
}

if ((!$initial_index_page)&&(is_section($base_section)))
{
    $core_plugins_menu='<div id="plugin_navbar">
    <div id="plugin_navbar_menu">
            <ul>';

    foreach (list_plugins($base_section) as $itm)
    {
        /*
        $core_plugins_menu.="<li>
                    <h3><a href=\"index.php?section=$section&plugin=".$itm['folder']."\">".$itm['name']."</a></h3>
                    <p>version : ".$itm['version']."</p>
                    <p>author : ".$itm['author']."</p>
                    <p>description : ".$itm['description']."</p>
                    <p>main file name: ".$itm['main_file']."</p>
                    <p>server file name: ".$itm['server_file']."</p>
                    <p>icon path: ".$itm['icon']."</p>
                </li>";
         *
         */
        if ($plugin!=$itm['folder'])
        {
            $core_plugins_menu.='<li>
                    <a href="index.php?section='.$section.'&plugin='.$itm['folder'].'">
                        <img id="plugin_navbar_slider_image_'.$i.'" src="plugins/'.$section.'/'.$itm['folder'].'/'.$itm['icon'].'" alt="'.$itm['name'].'" title="'.$itm['description'].'" />
                    </a>
                </li>';
        }
        else
        {
            // Highlight selected section.
            $core_plugins_menu.='<li>
                    <a href="index.php?section='.$section.'&plugin='.$itm['folder'].'">
                       <img id="plugin_navbar_slider_image_'.$i.'" src="plugins/'.$section.'/'.$itm['folder'].'/'.$itm['icon_hv'].'" alt="'.$itm['name'].'" title="'.$itm['description'].'" />
                    </a>
                </li>';
        }
        $i++;
    }

    $core_plugins_menu.=' </ul></div><div id="router_logo"></div>
</div>';
}
else
{
    // Load a default plugin list or leave it void.
    $core_plugins_menu.='<div id="initial_page"><img src="'.$base_url.'core/images/inicio.png" alt="Image for initial screen" /></div>';
}
//echo "<pre>".print_r(list_plugins($base_section),true)."</pre>";
?>
