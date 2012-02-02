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
 *  Author: Octavio Benedi Sanchez
 */ 
function parse_remote_server ($path) {	
	// In the ini var we put the file
	if (file_exists($path))
	{
		$ini = file( $path );
	}
	else
	{
		$ini=Array();
	}    
    // Check for an empty file
    if ( count( $ini ) == 0 ) { return array(); }
    $result= array();
    foreach( $ini as $line ){
		// with trim just take out unwanted characters
        $line = trim( $line );
        // if first character is a # we know is a comment and pass through
		if ( $line == '' || $line{0} == '#' ) 
		{ 
			continue; 
		}
		$values= explode( '=', $line,2);
		$values[0]=trim($values[0]);
		$values[1]=trim($values[1]);
		$result[$values[0]]=$values[1];
    }
    //return $values;
    return $result;
}
?>
