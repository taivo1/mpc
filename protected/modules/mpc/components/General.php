<?php

class General
{
    /**
     * Converts song length from seconds to Minutes + seconds
     * @param integer $seconds 
     */
    public static function calculateTime($seconds)
    {
	
	$minutes = floor($seconds/60);
	$secondsleft = $seconds%60;
	if($minutes<10) $minutes = "0" . $minutes;
	if($secondsleft<10) $secondsleft = "0" . $secondsleft;
	

	return $minutes.":".$secondsleft;
    }
}
?>
