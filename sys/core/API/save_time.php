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
function save_time($post)
{	
	$write_path='data/gps.conf';
	
	unset ($aux);
	unset ($aux1);
	unset ($input);	
	$Response= new xajaxResponse();
	
	$fp=fopen($write_path,"w");
	
	if ($post['actualize_time_gps']=='on')
	{
		fwrite($fp,"on");	
	}	
	
	fclose($fp);
	unset($fp);
	$write_path='data/ntp.conf';
	$fp=fopen($write_path,"w");
	
	if ($post['actualize_time_ntp']=='on')
	{
		fwrite($fp,"on");
		exec('sudo '.EXEC_PATH.'activate_ntp.sh 2>&1 >/dev/null');
	}	
	else
	{
		exec('sudo '.EXEC_PATH.'deactivate_ntp.sh 2>&1 >/dev/null');
	}
	
	fclose($fp);
	$Response->script("stop_alert('".MESSAGE_AFTER_SAVING."')");
	return $Response;
	
}
?>