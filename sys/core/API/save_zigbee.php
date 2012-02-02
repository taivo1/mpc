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
function save_zigbee($post)
{	
	$write_path='data/zigbee.conf';
	$write_path2='data/zigbee.meshlium.conf';
	unset ($aux);
	unset ($aux1);
	unset ($input);	
	$Response= new xajaxResponse();
	
	$fp=fopen($write_path,"w");
	$fp2=fopen($write_path2,"w");	
	
	if ($post['zigbee_filename']!='')
	{
		$post['zigbee_filename']=str_replace(' ','_',$post['zigbee_filename']);
		
		fwrite($fp2,$post['zigbee_filename']."\n");
		fwrite($fp2,$post['cypher']."\n");
		
		if ($post['cypher']=='on')
		{
			fwrite($fp,'/mnt/user/'.$post['zigbee_filename']."\n");
		}
		else
		{
			fwrite($fp,BASE_PATH.'/data/zigbee/'.$post['zigbee_filename']."\n");
		}	
	}
	else
	{
		if ($post['cypher']=='on')
		{
			fwrite($fp,"/mnt/user/zigbee\n");
		}
		else
		{
			fwrite($fp,BASE_PATH.'/data/zigbee/zigbee\n');
		}
	}	
	fclose($fp);
	fclose($fp2);
	$Response->script("stop_alert('".MESSAGE_AFTER_SAVING."')");
	$Response->script("change_me('interfaces','zigbee_net')");
	return $Response;
	
}
?>