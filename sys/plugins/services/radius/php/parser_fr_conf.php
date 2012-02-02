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

function parse_fr_conf ( $filepath )
/* ------------------------------------------------------------------------ */
{
    $conf_file = fopen( $filepath, "r" );
    $conf_data = parse_section ( $conf_file );
    fclose( $conf_file );
    return $conf_data;
}
/* ------------------------------------------------------------------------ */

function parse_section( $conf_file )
/* ------------------------------------------------------------------------ */
{
    $section = array();

    while ( $line = fgets( $conf_file ) )
    {
        $line = trim( $line );

        if ($line != '' && $line[0] != '#')
        {
            if (strpos($line, '{') !== false) {
                $section[trim( strtok( $line, '{' ) )] = parse_section( $conf_file );
            } elseif ($line == '}') {
                return $section;
            } else {
                $data = explode (' = ', $line, 2);
                if ( count ($data) == 2 ) {
                    $section[ trim( $data[0] ) ] = trim  ( strtok( $data[1], '#' ) );
                } else {
                    $section[$line] = '';
                }
            }
        }
    }
    return $section;
}
/* ------------------------------------------------------------------------ */

?>
