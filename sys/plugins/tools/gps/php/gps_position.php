<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
function get_pos()
{
	$center=Array();
    exec('ps -e |grep -i gpsd |wc -l',$return);
    if ($return[0]=='1')
    {
        exec('gpspipe -r -n 7',$ini);
    }
    else
    {
        $ini=Array();
    }
    foreach ($ini as $line)
    {
        $response=parse_NMEA($line);
        if (!empty($response['type'])) // ONLY SHOW RECOGNIZED CHAINS
        {
            switch ($response['type'])
            {
                case 'GPGGA':
                    if ($response['numsat']>=0)
                    {
                        $center['lat']=$response['lat-google'];
                        $center['lng']=$response['long-google'];
                        break 2;
                    }
                    else
                    {
                        break;
                    }
            }
        }
    }
    return $center;
}
function get_nmea()
{
	exec('ps -e |grep -i gpsd |wc -l',$return);
    if ($return[0]=='1')
    {
        exec('gpspipe -r -n 7',$ini);
        $out.="<p>";
        foreach ($ini as $line)
        {
            $out.=$line."<br>";
        }
        $out.="</p>";
    }
    else
    {
        $out="<p>gpsd not active.</p>";
    }
    return $out;
}

/*
include_once '../../../../core/API/parser_NMEA.php';
print_r(get_pos());
  */
?>