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
function parse_xbee_conf()
{
    global $url_plugin;
    global $section;
    global $plugin;
    global $base_plugin;

    // Change this value to point where usually Xbee module is.
    $default_port='S0';

    $configuration=Array();

    if (file_exists($base_plugin.'data/xbee.conf'))
    {
        include $base_plugin.'data/xbee.conf';
    }
    else
    {
        $configuration['port']=$default_port;
    }
    return $configuration;
}
?>