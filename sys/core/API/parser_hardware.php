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
function parse_hardware()
{
	unset($dmesg);
	unset ($ret);
	unset($usb);
	unset($usb_tty);
	$eth0_write=true;
	$wifi0_write=true;
	$wifi1_write=true;
	$usb=false;
	$ret=Array();
	exec('sudo '.EXEC_PATH.'gpsactive',$gps_bef);
	exec('dmesg',$dmesg);
	if (!file_exists(BASE_PATH.'/data/hwd.conf'))
	{
		$fp=fopen(BASE_PATH.'/data/hwd.conf',"w");
		foreach ($dmesg as $line)
		{
			$data=explode(':',$line,2);
			$data[0]=trim($data[0]);
			$data[1]=trim($data[1]);
			
			if ($data[0]=='eth0')
			{
				$ret[$data[0]]='on';
				if ($eth0_write){
				fwrite($fp,"eth0=on\n");
				$eth0_write=false;
				}
			}
			
			if ($data[0]=='wifi0')
			{
				$ret[$data[0]]='on';
				if ($wifi0_write){
				fwrite($fp,"wifi0=on\n");
				$wifi0_write=false;
				}
				$data[1]=substr($data[1],0,3);			
				if ($data[1]=='11a')
				{
					$ret[$data[0].'_11a']='on';
					fwrite($fp,"wifi0_11a=on\n");				
				}
				if ($data[1]=='11b')
				{
					$ret[$data[0].'_11b']='on';
					fwrite($fp,"wifi0_11b=on\n");
				}
				if ($data[1]=='11g')
				{
					$ret[$data[0].'_11g']='on';
					fwrite($fp,"wifi0_11g=on\n");				
				}
			}
			
			if ($data[0]=='wifi1')
			{
				$ret[$data[0]]='on';
				if ($wifi1_write){
				fwrite($fp,"wifi1=on\n");
				$wifi1_write=false;
				}				
				$data[1]=substr($data[1],0,3);
				if ($data[1]=='11a')
				{
					$ret[$data[0].'_11a']='on';
					fwrite($fp,"wifi1_11a=on\n");				
				}
				if ($data[1]=='11b')
				{
					$ret[$data[0].'_11b']='on';
					fwrite($fp,"wifi1_11b=on\n");
				}
				if ($data[1]=='11g')
				{
					$ret[$data[0].'_11g']='on';
					fwrite($fp,"wifi1_11g=on\n");				
				}
			}
			
			if ($data[0]=='drivers/usb/serial/ftdi_sio.c')
			{
				if ($data[1]=='Detected FT8U232AM')
				{
					$ret['gprs']='on';
					fwrite($fp,"gprs=on\n");				
					$usb='gprs';					
				}
				elseif (substr($data[1],0,-2)=='Detected FT232')
				{
					$usb='gps-zigbee';
				}
			}
			//usb 2-2: FTDI USB Serial Device converter now attached to ttyUSB1 
			elseif (substr($data[0],0,3)=='usb')
			{
				$usb_tty=explode('ttyUSB',$data[1],2);
				if ($usb=='gprs')
				{
					$ret['gprs_usb']='ttyUSB'.$usb_tty[1];
					$ret['ttyUSB'.$usb_tty[1]]='gprs';
					fwrite($fp,"gprs_usb=ttyUSB".$usb_tty[1]."\n");
					$usb=false;
					unset($save_aux);
					$save_aux=fopen(BASE_PATH.'/data/gprs.usb',"w");
					fwrite($save_aux,'/dev/ttyUSB'.$usb_tty[1]);
					fclose($save_aux);
				}
				elseif ($usb=='gps-zigbee')
				{
					unset($aux);
					//echo 'sudo '.EXEC_PATH.'head_tty '.$usb_tty[1].' 2>&1';
					//exec('sudo '.EXEC_PATH.'head_tty '.$usb_tty[1].' 2>&1',$aux);
					//exec('sudo '.EXEC_PATH.'check_usb '.$usb_tty[1].' /tmp/check_hwd.meshlium');
					if (file_exists('/tmp/check_hwd.meshlium'))
					{
						exec('rm /tmp/check_hwd.meshlium');
						//$aux=file('/tmp/check_hwd.meshlium');
					}					
					exec('sudo '.EXEC_PATH.'check_usb '.$usb_tty[1].' /tmp/check_hwd.meshlium');
					$aux=file('/tmp/check_hwd.meshlium');
				
					//echo '<pre>'.print_r($aux,true).'</pre>';
					if (substr($aux[1],0,3)=='$GP')
					{
						$ret['gps']='on';
						$ret['gps_usb']='ttyUSB'.$usb_tty[1];					
						$ret['ttyUSB'.$usb_tty[1]]='gps';
						fwrite($fp,"gps=on\n");
						fwrite($fp,"gps_usb=ttyUSB".$usb_tty[1]."\n");
						unset($save_aux);
						$save_aux=fopen(BASE_PATH.'/data/gps.usb',"w");
						fwrite($save_aux,'/dev/ttyUSB'.$usb_tty[1]);
						fclose($save_aux);
						
					}	
					else
					{
						$ret['zigbee']='on';
						$ret['zigbee_usb']='ttyUSB'.$usb_tty[1];
						$ret['ttyUSB'.$usb_tty[1]]='gps';
						fwrite($fp,"zigbee=on\n");
						fwrite($fp,"zigbee_usb=ttyUSB".$usb_tty[1]."\n");
						unset($save_aux);
						$save_aux=fopen(BASE_PATH.'/data/zigbee.usb',"w");
						//fwrite($save_aux,'/dev/ttyUSB'.$usb_tty[1]);
						// Because of squidBeeGW we need only to save the number of the /dev/ttyUSB here.
						fwrite($save_aux,$usb_tty[1]);
						fclose($save_aux);
					}
					$usb=false;				
				}				
			}
		}
		fclose($fp);
	}
	else
	{
		$aux=file(BASE_PATH.'/data/hwd.conf');
		foreach ($aux as $line)
		{
			$data=explode('=',$line,2);
			$ret[trim($data[0])]=trim($data[1]);
		}
	}
	
	if ($gps_bef[0][0]==1)
	{
		$handle=exec('sudo '.EXEC_PATH.'gpson '.$aux['gps_usb'].' 2>&1',$list);
	}
	
	return $ret;
}
//$retorno=parse_hardware();
//echo '<pre>'.print_r($retorno,true).'</pre>';
?>