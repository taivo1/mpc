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
function set_fresnel($interface,$distance)
{
    global $section;
    global $plugin;
    global $base_plugin;
	
	if (is_numeric($distance))
	{
		$dist=$distance*1000;
		exec('sudo '.EXEC_PATH.'change_dist '.$interface.' '.$dist.' 2>&1 >/dev/null');
		
		exec('/sbin/sysctl dev.wifi'.$interface.'.acktimeout 2>/dev/null',$ret);
		$ret[0]=trim($ret[0]);
	    if ($ret[0][0]=='d'){
	    		$ret2=explode('=',trim($ret[0]));
	    		$ret2[1]=trim($ret2[1]);
	            $save=$ret2[1];
	    }
	    
		unset($ret);
		unset($ret2);
		exec('/sbin/sysctl dev.wifi'.$interface.'.ctstimeout 2>/dev/null',$ret);
		$ret[0]=trim($ret[0]);
	    if ($ret[0][0]=='d'){
	    		$ret2=explode('=',trim($ret[0]));
	    		$ret2[1]=trim($ret2[1]);
	            $save.=' '.$ret2[1];
	    }
	    
		unset($ret);
		unset($ret2);
		exec('/sbin/sysctl dev.wifi'.$interface.'.slottime 2>/dev/null',$ret);
		$ret[0]=trim($ret[0]);
	    if ($ret[0][0]=='d'){
	    		$ret2=explode('=',trim($ret[0]));
	    		$ret2[1]=trim($ret2[1]);
	            $save.=' '.$ret2[1];
	    }
		
		$temp_file=fopen($base_plugin.'data/fresnel_'.$interface.'.conf',"w");
		fwrite($temp_file,$save);
		fclose($temp_file);
    }
}
?>