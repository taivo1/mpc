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
function is_section($container_folder)
{
    if (is_dir($container_folder))
    {
        //Check for a configuration file.
        if(file_exists($container_folder.'/configuration.php'))
        {
            include $container_folder.'/configuration.php';
            if ($type=="SELECTOR")
            {
                return true;
            }
        }
    }
    return false;
}

function is_plugin($container_folder)
{
    // just looking for directories other than . and ..
    if (is_dir($container_folder))
    {
        //Check for a configuration file.
        if(file_exists($container_folder.'/configuration.php'))
        {
            include $container_folder.'/configuration.php';
            if (($type=="PLUGIN")&&(file_exists($container_folder.'/'.$plugin_main_file))&&(file_exists($container_folder.'/'.$plugin_server_file)))
            {
                return true;
            }
        }
    }
    return false;
}
?>