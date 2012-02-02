<?php
/*
 *  Copyright (C) 2009 Libelium Comunicaciones Distribuidas S.L.
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
 *  Author: Daniel Larraz
 */

function write_hostapd ($filepath, $input, $writepath='')
/* ------------------------------------------------------------------------ */
{
    global $base_plugin;

    if ($writepath=='')
    {
        $writepath=$base_plugin.'data/temp_hostapd';
    }
    $fp=fopen ($writepath,"w");

    foreach ($input as $key => $value)
    {
        fwrite ($fp, $key."=".$value."\n");
    }

    fclose ($fp);
    exec ('sudo mv '.$writepath.' '.$filepath);
    exec ('sudo chown root:root '.$filepath);
}
/* ------------------------------------------------------------------------ */

?>
