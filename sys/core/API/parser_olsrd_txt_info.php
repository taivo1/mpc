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
function valid_ip($ip)
    {
        return preg_match( "/^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}$/", $ip);
    } 
function parse_olsrd_txt_info ($path) {	
	// In the ini var we put the file
	if (file_exists($path))
	{
    	$ini = file( $path );
	}
    // Check for an empty file
    	$neight=false;
    	$top=false;
    	$i=0;
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
		$values= explode( '	', $line,3);
		$values[0]=trim($values[0]);
		$values[1]=trim($values[1]);
		// We manage onlyl Topology and neigthbours right now.
    	if ($values[0]=='Table: HNA')
		{
			$neight=false;
			$top=false;
		}		
	    if ($top)
		{			
	    	// Para validar una ip.
			if (valid_ip($values[0])&&valid_ip($values[1]))
			{
				$result['top'][$i]['1']=$values[0];
				$result['top'][$i]['2']=$values[1];
				$i++;
				$result['num_top']=$i;
			}
			else
			{
				
			}
		}		
    	if ($values[0]=='Table: Topology')
		{
			$neight=false;
			$top=true;
			$i=0;
		}		
    	if ($neight)
		{			
	    	// Para validar una ip.
			if (valid_ip($values[0]))
			{
				$result['brothers'][$i]=$values[0];
				$i++;
				$result['num_brothers']=$i;
			}
			else
			{
				
			}
		}		
		if ($values[0]=='Table: Neighbors')
		{
			$neight=true;
		}
    }
    return $result;
}



/*
 * EXAMPLE OF THE OUTPUT
 * Array
(
    [brothers] => Array
        (
            [0] => 192.168.1.250            
            [1] => 192.168.1.129
            [2] => 192.168.1.110
            [3] => 192.168.1.251
            [4] => 192.168.1.129
            [5] => 192.168.1.110
            [6] => 192.168.1.251
            [7] => 192.168.1.129
            [8] => 192.168.1.110
            [9] => 192.168.1.251
            [10] => 192.168.1.129
            [11] => 192.168.1.110
            [12] => 192.168.1.251
            [13] => 192.168.1.129
            [14] => 192.168.1.110
            [15] => 192.168.1.251
            [16] => 192.168.1.129
            [17] => 192.168.1.110
            [18] => 192.168.1.251
            [19] => 192.168.1.129
            [20] => 192.168.1.110
            [21] => 192.168.1.251
        )
	[num_brothers] => 22

	[num_top] => 2
    [top] => Array
        (
            [0] => Array
                (
                    [1] => 192.168.1.250
                    [2] => 192.168.1.14
                )
            
            [1] => Array
                (
                    [1] => 192.168.1.14
                    [2] => 192.168.1.250
                )

        )

)
*/ 
?>