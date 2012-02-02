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
function hciconfig_parser()
{
	unset($line);
	unset($list);
	unset($data);
	unset($response);
	unset($actual);
	$respone=Array();	
	
	exec('sudo '.EXEC_PATH.'hciconfig 2>&1',$list);
	foreach ($list as $line)
	{
		$data=explode(':',trim($line),2);
		if ($data[0]=='')
		{
			continue;
		}
		if ((substr_compare($data[0],'hci',0, 3))==0)  // comparation case sensitive to work.
		{ 
			$response['num']+=1;
			$response['name_list'].=$data[0].' ';
			$actual=$data[0];
		}
		else
		{
			switch (substr($data[0],0,2))
			{
				case 'BD':
					$response[$actual]['address']=trim(substr($data[1],0,18));
					break;
				case 'Na':
					$response[$actual]['name']=trim($data[1]);
					break;
				case 'UP':
					$response[$actual]['state']='up';
					break;
				case 'DO':
					$response[$actual]['state']='down';
					break;
			}
		}
	}
	return $response;
}
?>