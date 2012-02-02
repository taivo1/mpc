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
$base_dir=dirname($_SERVER['SCRIPT_FILENAME']).'/';
// First is important to know what server should be displayed.
// To archieve that, parse the $_POST is needed.
$error=false;
if (!empty($_POST)) // This check is performed too in index.php but we do it again to avoid direct invocation.
{
    // Check for section or plugin that must be shown.
    // Strip non allowed characters.
    $section=preg_replace("/[^a-zA-Z0-9_]/", "", $_POST['section']);
    if (!empty($section))
    {
        $base_section=$base_dir.'plugins/'.$section.'/';
        // Strip non allowed characters.
        $plugin=preg_replace("/[^a-zA-Z0-9_]/", "", $_POST['plugin']);
        if (!empty($plugin)) // Plugin is defined
        {
            // Check the configuration file and  test the server script.
            // Send to the client the content of the main div.
            $base_plugin=$base_section.$plugin.'/';

            if(file_exists($base_plugin.'configuration.php'))
            {
                include $base_plugin.'configuration.php';
                if (($type=="PLUGIN")&&(file_exists($base_plugin.$plugin_main_file))&&(file_exists($base_plugin.$plugin_server_file)))
                {
                    $url_plugin='plugins/'.$section.'/'.$plugin.'/';
                    $API_core='core/API/';
                    $main_display_content='core/functions/load_plugin.php';
                    include $base_plugin.$plugin_server_file;
                }
            }
        }
    }
}
?>