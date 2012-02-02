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
function save_remote_server($post,$write_path='')
{
    global $base_plugin;
    if ($write_path=='')
    {
        $write_path=$base_plugin.'data/remote_server.conf';
    }
	$fp=fopen($write_path,"w");
	$cron=fopen('/tmp/crontab_mesh.tmp',"w");
    if (is_ip($post['remote_send_ip']))
    {
        fwrite($fp,"remote_send_ip=".$post['remote_send_ip']."\n");
    }
	if ($post['remote_send_server_int_m']>'59')
	{
		$post['remote_send_server_int_m']='59';
	}
	if ($post['remote_send_server_int_m']<'1')
	{
		$post['remote_send_server_int_m']='1';
	}	
	
	if ($post['remote_to_server']=='on')
	{
		fwrite($fp,"remote_to_server=on\n");
		fwrite($cron,'*/'.$post['remote_send_server_int_m']." * * * * ".EXEC_PATH."info_to_server.sh\n");
	}
	else
	{
		fwrite($fp,"remote_to_server=off\n");
	}
	
	/*
	if ($post['remote_send_server_int_h']>'23')
	{
		$post['remote_send_server_int_h']='23';
	}
	if ($post['remote_send_server_int_h']<'0')
	{
		$post['remote_send_server_int_h']='0';
	}
	*/
	
	fwrite($fp,"remote_send_server_int_m=".$post['remote_send_server_int_m']."\n");
	//fwrite($fp,"remote_send_server_int_h=".$post['remote_send_server_int_h']."\n");
	
	if ($post['remote_send_brother_int_m']>'59')
	{
		$post['remote_send_brother_int_m']='59';
	}
	if ($post['remote_send_brother_int_m']<'1')
	{
		$post['remote_send_brother_int_m']='1';
	}
	
	if ($post['remote_to_brother']=='on')
	{
		fwrite($fp,"remote_to_brother=on\n");
		fwrite($cron,'*/'.$post['remote_send_brother_int_m']." * * * * ".EXEC_PATH."info_to_brothers.sh\n");
	}
	else
	{
		fwrite($fp,"remote_to_brother=off\n");
	}
	
	/*
	if ($post['remote_send_brother_int_h']>'23')
	{
		$post['remote_send_brother_int_h']='23';
	}
	if ($post['remote_send_brother_int_h']<'0')
	{
		$post['remote_send_brother_int_h']='0';
	}
	*/
	
	fwrite($fp,"remote_send_brother_int_m=".$post['remote_send_brother_int_m']."\n");
	//fwrite($fp,"remote_send_brother_int_h=".$post['remote_send_brother_int_h']."\n");
		
	if ($post['remote_info_to_send']=='on')
	{
		fwrite($fp,"remote_info_to_send=on\n");
	}
	else
	{
		fwrite($fp,"remote_info_to_send=off\n");
	}
	
	if ($post['remote_checklist_server_gps']=='on')
	{
		fwrite($fp,"remote_checklist_server_gps=on\n");
	}
	else
	{
		fwrite($fp,"remote_checklist_server_gps=off\n");
	}
	
	if ($post['remote_checklist_brother_gps']=='on')
	{
		fwrite($fp,"remote_checklist_brother_gps=on\n");
	}
	else
	{
		fwrite($fp,"remote_checklist_brother_gps=off\n");
	}
	
	if ($post['remote_checklist_server_version']=='on')
	{
		fwrite($fp,"remote_checklist_server_version=on\n");
	}
	else
	{
		fwrite($fp,"remote_checklist_server_version=off\n");
	}
		
	if ($post['remote_checklist_brother_version']=='on')
	{
		fwrite($fp,"remote_checklist_brother_version=on\n");
	}
	else
	{
		fwrite($fp,"remote_checklist_brother_version=off\n");
	}
	
	
	if ($post['remote_checklist_server_key']=='on')
	{
		fwrite($fp,"remote_checklist_server_key=on\n");
	}
	else
	{
		fwrite($fp,"remote_checklist_server_key=off\n");
	}
		
	if ($post['remote_checklist_brother_key']=='on')
	{
		fwrite($fp,"remote_checklist_brother_key=on\n");
	}
	else
	{
		fwrite($fp,"remote_checklist_brother_key=off\n");
	}
	
	
	if ($post['remote_checklist_server_remote_server']=='on')
	{
		fwrite($fp,"remote_checklist_server_remote_server=on\n");
	}
	else
	{
		fwrite($fp,"remote_checklist_server_remote_server=off\n");
	}
		
	if ($post['remote_checklist_brother_remote_server']=='on')
	{
		fwrite($fp,"remote_checklist_brother_remote_server=on\n");
	}
	else
	{
		fwrite($fp,"remote_checklist_brother_remote_server=off\n");
	}
	
	
	if ($post['remote_checklist_server_cypher']=='on')
	{
		fwrite($fp,"remote_checklist_server_cypher=on\n");
	}
	else
	{
		fwrite($fp,"remote_checklist_server_cypher=off\n");
	}
		
	if ($post['remote_checklist_brother_cypher']=='on')
	{
		fwrite($fp,"remote_checklist_brother_cypher=on\n");
	}
	else
	{
		fwrite($fp,"remote_checklist_brother_cypher=off\n");
	}
	
	
	if ($post['remote_checklist_server_interfaces_eth']=='on')
	{
		fwrite($fp,"remote_checklist_server_interfaces_eth=on\n");
	}
	else
	{
		fwrite($fp,"remote_checklist_server_interfaces_eth=off\n");
	}
		
	if ($post['remote_checklist_brother_interfaces_eth']=='on')
	{
		fwrite($fp,"remote_checklist_brother_interfaces_eth=on\n");
	}
	else
	{
		fwrite($fp,"remote_checklist_brother_interfaces_eth=off\n");
	}
	
	
	if ($post['remote_checklist_server_interfaces_wifi']=='on')
	{
		fwrite($fp,"remote_checklist_server_interfaces_wifi=on\n");
	}
	else
	{
		fwrite($fp,"remote_checklist_server_interfaces_wifi=off\n");
	}
		
	if ($post['remote_checklist_brother_interfaces_wifi']=='on')
	{
		fwrite($fp,"remote_checklist_brother_interfaces_wifi=on\n");
	}
	else
	{
		fwrite($fp,"remote_checklist_brother_interfaces_wifi=off\n");
	}
	
	
	if ($post['remote_checklist_server_nets']=='on')
	{
		fwrite($fp,"remote_checklist_server_nets=on\n");
	}
	else
	{
		fwrite($fp,"remote_checklist_server_nets=off\n");
	}
		
	if ($post['remote_checklist_brother_nets']=='on')
	{
		fwrite($fp,"remote_checklist_brother_nets=on\n");
	}
	else
	{
		fwrite($fp,"remote_checklist_brother_nets=off\n");
	}
	
	
	if ($post['remote_checklist_server_cell']=='on')
	{
		fwrite($fp,"remote_checklist_server_cell=on\n");
	}
	else
	{
		fwrite($fp,"remote_checklist_server_cell=off\n");
	}
		
	if ($post['remote_checklist_brother_cell']=='on')
	{
		fwrite($fp,"remote_checklist_brother_cell=on\n");
	}
	else
	{
		fwrite($fp,"remote_checklist_brother_cell=off\n");
	}
	
	
	if ($post['remote_checklist_server_clients']=='on')
	{
		fwrite($fp,"remote_checklist_server_clients=on\n");
	}
	else
	{
		fwrite($fp,"remote_checklist_server_clients=off\n");
	}
		
	if ($post['remote_checklist_brother_clients']=='on')
	{
		fwrite($fp,"remote_checklist_brother_clients=on\n");
	}
	else
	{
		fwrite($fp,"remote_checklist_brother_clients=off\n");
	}
	
	if ($post['remote_checklist_server_interfaces_gprs']=='on')
	{
		fwrite($fp,"remote_checklist_server_interfaces_gprs=on\n");
	}
	else
	{
		fwrite($fp,"remote_checklist_server_interfaces_gprs=off\n");
	}
	
	if ($post['remote_checklist_brother_interfaces_gprs']=='on')
	{
		fwrite($fp,"remote_checklist_brother_interfaces_gprs=on\n");
	}
	else
	{
		fwrite($fp,"remote_checklist_brother_interfaces_gprs=off\n");
	}
	
	if ($post['remote_checklist_server_interfaces_bluetooth']=='on')
	{
		fwrite($fp,"remote_checklist_server_interfaces_bluetooth=on\n");
	}
	else
	{
		fwrite($fp,"remote_checklist_server_interfaces_bluetooth=off\n");
	}
	
	if ($post['remote_checklist_brother_interfaces_bluetooth']=='on')
	{
		fwrite($fp,"remote_checklist_brother_interfaces_bluetooth=on\n");
	}
	else
	{
		fwrite($fp,"remote_checklist_brother_interfaces_bluetooth=off\n");
	}
	
	if ($post['remote_checklist_server_interfaces_zigbee']=='on')
	{
		fwrite($fp,"remote_checklist_server_interfaces_zigbee=on\n");
	}
	else
	{
		fwrite($fp,"remote_checklist_server_interfaces_zigbee=off\n");
	}
	
	if ($post['remote_checklist_brother_interfaces_zigbee']=='on')
	{
		fwrite($fp,"remote_checklist_brother_interfaces_zigbee=on\n");
	}
	else
	{
		fwrite($fp,"remote_checklist_brother_interfaces_zigbee=off\n");
	}
	
	if ($post['remote_checklist_server_interconection']=='on')
	{
		fwrite($fp,"remote_checklist_server_interconection=on\n");
	}
	else
	{
		fwrite($fp,"remote_checklist_server_interconection=off\n");
	}
	
	if ($post['remote_checklist_brother_interconection']=='on')
	{
		fwrite($fp,"remote_checklist_brother_interconection=on\n");
	}
	else
	{
		fwrite($fp,"remote_checklist_brother_interconection=off\n");
	}
	
	if ($post['remote_checklist_server_band']=='on')
	{
		fwrite($fp,"remote_checklist_server_band=on\n");
	}
	else
	{
		fwrite($fp,"remote_checklist_server_band=off\n");
	}
	
	if ($post['remote_checklist_brother_band']=='on')
	{
		fwrite($fp,"remote_checklist_brother_band=on\n");
	}
	else
	{
		fwrite($fp,"remote_checklist_brother_band=off\n");
	}
	
	if ($post['remote_checklist_server_mesh']=='on')
	{
		fwrite($fp,"remote_checklist_server_mesh=on\n");
	}
	else
	{
		fwrite($fp,"remote_checklist_server_mesh=off\n");
	}
	
	
	if ($post['remote_checklist_brother_mesh']=='on')
	{
		fwrite($fp,"remote_checklist_brother_mesh=on\n");
	}
	else
	{
		fwrite($fp,"remote_checklist_brother_mesh=off\n");
	}
	
	
	if ($post['remote_checklist_server_mesh_brothers']=='on')
	{
		fwrite($fp,"remote_checklist_server_mesh_brothers=on\n");
	}
	else
	{
		fwrite($fp,"remote_checklist_server_mesh_brothers=off\n");
	}
	
	if ($post['remote_checklist_brother_mesh_brothers']=='on')
	{
		fwrite($fp,"remote_checklist_brother_mesh_brothers=on\n");
	}
	else
	{
		fwrite($fp,"remote_checklist_brother_mesh_brothers=off\n");
	}
	
	fclose($fp);
	fclose($cron);
	//exec('crontab /tmp/crontab_mesh.tmp');
	//exec('rm /tmp/crontab_mesh.tmp');
}
?>