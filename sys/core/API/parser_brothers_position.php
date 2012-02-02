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
function parse_brothers_position()
{
	unset($brothers);
	unset ($file);
	unset ($files);
	unset ($path);
	unset ($i);
	unset($aux);
	$i=0;
	$path=BASE_PATH.'/data/adquired_data';
	if (file_exists($path))
	{
		$files = scandir($path);		
		foreach ($files as $file)
		{
			if (is_dir($path.'/'.$file) && ($file[0]!="."))
			{
				if (file_exists($path.'/'.$file.'/.last'))
				{
					$ini=file($path.'/'.$file.'/.last');
					
					$aux=explode('=',$ini[0],2);
					$brothers[$i]['id']=$file;
					if (trim($aux[1])==1)
					{
						unset ($aux);
						$aux=explode('=',$ini[1],2);
						$brothers[$i]['lat']=trim($aux[1]);
						unset ($aux);
						$aux=explode('=',$ini[2],2);
						$brothers[$i]['long']=trim($aux[1]);
						unset ($aux);
						$aux=explode('=',$ini[29],2);
						$brothers[$i]['ath0_ip']=trim($aux[1]);
						unset ($aux);
						$aux=explode('=',$ini[35],2);
						$brothers[$i]['ath1_ip']=trim($aux[1]);
						unset ($aux);
						$aux=explode('=',$ini[88],2);
						$brothers[$i]['brothers']=trim($aux[1]);
					}
					else
					{
						$brothers[$i]['lat']='n/a';
						$brothers[$i]['long']='n/a';
					}
					$i++;
					$brothers['num']=$i;
				}
			}
		}
	}
	return $brothers;
}
//$ret=parse_brothers_position();
//echo '<pre>'.print_r($ret,true).'</pre>';

/*
 * Example output
Array
(
    [0] => Array
        (
            [id] => 192.168.1.64
            [lat] => 41.6815
            [long] => -0.8864
            [brothers] => 
        )

    [num] => 3
    [1] => Array
        (
            [id] => el-unico-el-original----yo
            [lat] => 41.6823
            [long] => -0.8878
            [brothers] => 192.168.1.251
        )

    [2] => Array
        (
            [id] => meshlium2
            [lat] => 41.6823
            [long] => -0.8868
            [brothers] => 
        )

)
 */
?>