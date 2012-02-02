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
function is_active($interface)
{
	//THIS FUNCTION RETURNS TRUE IF THE INTERFACE IS UP AND FALSE OTHERWISE
	unset ($list);
	unset ($line);
	unset ($value);
	unset ($result);
	unset ($handle);
	unset ($start);
	
	if ($interface=='gprs')
	{
		$interface='ppp0';
	}
	
	$handle=exec('/sbin/ifconfig -s',$list); 
	$result=false;
	foreach ($list as $line)
	{
		$line=trim ($line);		
		$start=substr($line,0,4);
		$start=trim($start);		
		if ($interface == $start)
		{
			$result=true;
		}
	}
	if ($interface=='ppp0')
	{
		$interface='gprs';
	}	
	return $result;
}
?>