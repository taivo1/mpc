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
// This file just implements a parser for meshlium /etc/wvdial.conf files
function parse_wvdial ( $filepath ) {
	unset ($ini);	
	unset ($return);
	unset ($line);
	if (file_exists($filepath))
	{
		$ini = file($filepath);
	    // Check for an empty file
	    if ( count( $ini ) == 0 ) { return array(); }
		//Make an array with the values in /etc/hosts
	    $values = array();
	    $return = array();
	        
		// Start parser
		// for each line we are going to parse the file
	    foreach( $ini as $line ){
			// with trim just take out unwanted characters
	        $line = trim( $line );
	        // if first character is a # we know is a comment and pass through
			if ( $line == '' || $line{0} == '#' ) 
			{ 
				if (substr($line,1,7)=='country')
				 $return['country']=substr($line,9);
				if (substr($line,1,8)=='operator')
				 $return['operator']=substr($line,10);
				continue; 
			}
	        // We will have as many rows of data as lines with data in /etc/wvdial.conf
			$linea= explode( '=', $line,2);
			$linea[0]= trim($linea[0]);
			$linea[1]= trim($linea[1]);
			if ($linea[1])
			{
				switch ($linea[0])
				{
					case 'Init1':
						$linea=explode( '=', $linea[1],2);
                        $linea=preg_replace('/\"/','',$linea);
						$return['pin']=$linea[1];
						break;
					case 'Init4':
						//$linea=explode( ',', $linea[1],3);
						$return['init2']=$linea[1];
						break;
					case 'Password':
						$return['password']=$linea[1];
						break;
					case 'Phone':
						$return['phone']=$linea[1];
						break;
					case 'Username':
						$return['username']=$linea[1];
						break;
					case 'Dial Command':
						$return['dial']=trim($linea[1]);
						break;
						
					default:
						break;					
						
				}
			}
			
			
			   
	    }	    
	}
    return $return;
}
?>