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
function parse_list_scan ( $interface ) {
    $last_cell=array();
	$i=0;
	exec("/sbin/iwlist ".$interface." scan",$retry);

	foreach ($retry as $line)
	{
		$line=trim ($line);
		$start=substr($line,0,4);

		if ($start=='Cell')
		{
			$i++;
			$last_cell[$i]['id']='Cell '.$i;
			$data=explode(":",$line,2);
			$last_cell[$i]['mac']=trim($data[1]);
		}
		if ($start=='ESSI')
		{
			$data=explode(":",$line,2);
			$last_cell[$i]['essid']=trim($data[1]);
		}
		if ($start=='Mode')
		{
			$data=explode(":",$line,2);
			$last_cell[$i]['mode']=strtolower(trim($data[1]));
		}
		if ($start=='Freq')
		{
			$data=explode("(",$line,2);
			$last_cell[$i]['channel']=trim(substr($data[1],8,-1));
		}
		if ($start=='Qual')
		{
			$data=explode("=",$line,2);
			$last_cell[$i]['quality']=trim(substr($data[1],0,2));
			if ($last_cell[$i]['quality'][1]=='/')
			{
				$last_cell[$i]['quality']=trim(substr($last_cell[$i]['quality'],0,1));
			}
		}
		if ($start=='Encr')
		{
			$data=explode(":",$line,2);
			$last_cell[$i]['encr']=trim($data[1]);
		}
	}
    return $last_cell;
}
?>