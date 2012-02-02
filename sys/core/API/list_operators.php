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
function list_operators($readpath)
{
	unset($line);
	unset($values);
	unset($ini);	
	unset($filepath);
	unset($return);
	
	$return = array();
	$last_country='';
	$last_operator='';
	$start=true;
	$start2=true;
	// if file exists we parse actual values.
	if (file_exists($readpath))
	{
		$ini = file( $readpath );
		foreach($ini as $line){
			// with trim just take out unwanted characters
	        $line = trim( $line );
	        // if first character is a # we know is a comment and pass through
			if ( $line == '' || $line{0} == '#' )
			{
				continue;
			}
			$values= explode( '-', $line,2); // here we will have the 4 values of each line.
			$values[0]=trim($values[0]);		
			$values[1]=trim($values[1]);		
			if ($values[1]=="Back To Top")
			{			
				$last_country=$values[0];
				$start2=true;
				if ($start)
				{
					$return['list'].=$values[0];
					$start=false;					
				}
				else
				{
					$return['list'].='//'.$values[0];
				}
				$return['count']++;
			}
			else
			{
				switch($values[0])
				{
					case 'Operator:':
						$last_operator=$values[1];
						$return[$last_country]['count']++;
						if ($start2)
						{
							$return[$last_country]['list'].=$values[1];
							$start2=false;
						}
						else
						{
							$return[$last_country]['list'].='//'.$values[1];
						}					
						break;
					case 'GPRS APN:':
						$return[$last_country][$last_operator]['apn']=$values[1];
						break;
					case 'Username:':
						$return[$last_country][$last_operator]['username']=$values[1];
						break;
					case 'Password:':
						$return[$last_country][$last_operator]['password']=$values[1];
						break;
					case 'DNS:':
						$return[$last_country][$last_operator]['dns']=$values[1];
						break;
					
				}
				
			}		
		}
	return $return;
	}
}
?>