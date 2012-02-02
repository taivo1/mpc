<?php
/**
 *  Sorting algorithms
 * 
 */
class Sort extends Mpd
{
    public $sort_array;
    public $filenames_only;
    
    
    public function msort($a,$b) {
	    $i=0;
	    $ret = 0;
	    while($this->filenames_only!="yes" && $i<4 && $ret==0) {
		    if(!isset($a[$this->sort_array[$i]])) {
			    if(isset($b[$this->sort_array[$i]])) {
				    $ret = -1;
			    }
		    }
		    else if(!isset($b[$this->sort_array[$i]])) {
			    $ret = 1;
		    }
		    else if(strcmp($this->sort_array[$i],"Track")==0) {
			    $ret = strnatcmp($a[$this->sort_array[$i]],$b[$this->sort_array[$i]]);
		    }
		    else {
			    $ret = strcasecmp($a[$this->sort_array[$i]],$b[$this->sort_array[$i]]);
		    }
		    $i++;
	    }
	    if($ret==0)
		    $ret = strcasecmp($a["file"],$b["file"]);
	    return $ret;
    }

    public function picksort($pick) {

	    if(0==strcmp($pick,$this->sort_array[0])) {
		    return "$this->sort_array[0],$this->sort_array[1],$this->sort_array[2],$this->sort_array[3]";
	    }
	    else if(0==strcmp($pick,$this->sort_array[1])) {
		    return "$pick,$this->sort_array[0],$this->sort_array[2],$this->sort_array[3]";
	    }
	    else if(0==strcmp($pick,$this->sort_array[2])) {
		    return "$pick,$this->sort_array[0],$this->sort_array[1],$this->sort_array[3]";
	    }
	    else if(0==strcmp($pick,$this->sort_array[3])) {
		    return "$pick,$this->sort_array[0],$this->sort_array[1],$this->sort_array[2]";
	    }
    }
    
}
?>
