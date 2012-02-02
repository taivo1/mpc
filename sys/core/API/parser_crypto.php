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
function parse_crypto ()
{
	// We will check the status of the encripted partitions
	exec ('ls /dev/mapper',$list);
	
	foreach ($list as $item)
	{
		if ($item=='user')
		{
			$ret['user']='on';	
		}
		if ($item=='lib')
		{
			$ret['lib']='on';
		}
	}
	return $ret;
}
?>