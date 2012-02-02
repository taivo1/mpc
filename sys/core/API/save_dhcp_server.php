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
function save_dhcp_server($interface, $initial, $last, $time,$action)
{
	$filepath='/etc/dnsmasq.more.conf';
	$filewrite='/tmp/dnsmasq.more.conf';
	$fp=fopen($filewrite,"w");
	$ok=false;
	// if file exists we parse actual values.
	if (file_exists($filepath))
	{
		$ini = file( $filepath );
		foreach( $ini as $line ){
			// with trim just take out unwanted characters
	        $line = trim( $line );
	        // if first character is a # we know is a comment and pass through
			if ( $line == '' || $line{0} == '#' )
			{
				continue;
			}
			$values= explode( ',', $line,4); // here we will have the 4 values of each line.
			$valules[0]=trim($values[0]);
			if (substr($values[0],11)==$interface)
			{
				$ok=true; // we need to know if the interface has been writed.
				if ($action=='save')
				{
					fwrite($fp, "dhcp-range=".$interface.",".$initial.",".$last.",".$time."h\n");
				}
				else
				{
					continue;
				}
			}
			else
			{
				// if not a comment, not the actual interface, we bypass the line
				fwrite($fp, $line."\n");
			}
		}
		if (($ok==false)&&($action=='save'))
		{
			// if the interface haven't been wroten, just put it in.
			fwrite($fp, "dhcp-range=".$interface.",".$initial.",".$last.",".$time."h\n");
		}
	}	
	else
	{
		// if file didn't exists we generate it
		fwrite($fp, "dhcp-range=".$interface.",".$initial.",".$last.",".$time."h\n");
		fwrite($fp,"dhcp-leasefile=/var/tmp/dnsmasq.leases");
	}
	exec('sudo '.EXEC_PATH.'move '.$filewrite." ".$filepath,$aux);
	exec('sudo '.EXEC_PATH.'dhcp_server',$aux);
    exec('rm '.$filewrite);
}
?>