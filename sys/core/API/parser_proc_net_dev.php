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
function proc_net_dev()
	{
	// THIS FUNCTION RETURNS THE INTERFACES INFO TAKEN FROM /PROC/NET/DEV
	// TO KNOW THE STRCUTURE OF THE DATA, CALL THIS FUNCTION AND MAKE A print_r($result);
	unset ($list);
	unset ($line);
	unset ($value);
	unset ($start);
	unset ($result);
	unset ($handle);
	
	$handle=exec('cat /proc/net/dev',$list); 
	
	foreach ($list as $line)
	{
		$line=trim ($line);
		
		$start=substr($line,0,2);
		switch ($start)
		{
			case 'lo':
				$value[0]=explode(':',$line,2);
				for ($i=0;$i<17;$i++)
				{		
					$value[$i][1]=trim ($value[$i][1]);			
					$value[$i+1]=explode(' ',$value[$i][1],2);
					$result[$value[0][0]][$i]=$value[$i][0];
				}			
				
				break;
				
			case 'et':
				$value[0]=explode(':',$line,2);
				for ($i=0;$i<17;$i++)
				{		
					$value[$i][1]=trim ($value[$i][1]);			
					$value[$i+1]=explode(' ',$value[$i][1],2);
					$result[$value[0][0]][$i]=$value[$i][0];
				}
				break;
				
			case 'at':
				$value[0]=explode(':',$line,2);
				for ($i=0;$i<17;$i++)
				{		
					$value[$i][1]=trim ($value[$i][1]);			
					$value[$i+1]=explode(' ',$value[$i][1],2);
					$result[$value[0][0]][$i]=$value[$i][0];
				}
				break;
			case 'pp':
				$value[0]=explode(':',$line,2);
				for ($i=0;$i<17;$i++)
				{		
					$value[$i][1]=trim ($value[$i][1]);			
					$value[$i+1]=explode(' ',$value[$i][1],2);
					$result[$value[0][0]][$i]=$value[$i][0];
				}
				break; 
		}	
		
	}
	return $result;
}
?>