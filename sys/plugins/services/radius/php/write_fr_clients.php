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

function write_fr_clients ( $filepath, $clients, $servers, $writepath='')
/* ------------------------------------------------------------------------ */
{
    global $base_plugin;

    if ($writepath=='')
    {
        $writepath=$base_plugin.'data/temp_fr_clients';
    }
    $fp=fopen($writepath,"w");

    /* Accounting clients */
    fwrite( $fp, "clients all_clients {\n" );
    foreach ($clients as $name => $data)
    {
        write_client ($fp, $name, $data);
    }
    fwrite( $fp, "}\n\n" );

    foreach (array_keys($servers) as $servername)
    {
        fwrite( $fp, "clients ".$servername." {\n" );

        foreach ($clients as $name => $data)
        {
            if ( in_array ($servername, $data['auth_servers']) )
            {
                write_client ($fp, $name, $data);
            }
        }

        fwrite( $fp, "}\n\n" );
    }

    fclose($fp);
    exec('sudo mv '.$writepath.' '.$filepath);
    exec('sudo chown root:freerad '.$filepath);
}
/* ------------------------------------------------------------------------ */

function write_client ( $fp, $name, $data )
/* ------------------------------------------------------------------------ */
{
    fwrite( $fp, "       client ".$data['addr']." {\n" );
    fwrite( $fp, "              secret = ".$data['pass']."\n" );
    fwrite( $fp, "              shortname = ".$name."\n" );
    fwrite( $fp, "       }\n" );
}
/* ------------------------------------------------------------------------ */

?>
