<?php
/*
 * Copyright (C) 2008 Libelium Comunicaciones Distribuidas S.L.
 *
 * This file is part of Meshlium Manager System.
 * Meshlium Manager System will be released as free software; until then you cannot redistribute it
 * without express permission by libelium. 
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * 
 *
 * Version 0.1.0 
 *  Author: Daniel Larraz
 */

function parse_hostapd ( $filepath )
/* ------------------------------------------------------------------------ */
{
    $conf_data = array();

    $conf_file = file( $filepath );
    foreach( $conf_file as $line )
    {
        $line = trim( $line );

        if ( $line != '' && $line[0] != '#' )
	{
            $data = explode('=', $line, 2);
            $conf_data[trim( $data[0] )] = trim( strtok($data[1], '#') );
        }
    }

    return $conf_data;
}
/* ------------------------------------------------------------------------ */

?>
