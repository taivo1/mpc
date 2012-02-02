<?php
/** 
 * 
 * Taivo Kuusik (taivok@gmail.com) 01/2012
 * Version mpd.class.php-1.4
 * - Updated to php 5 standards
 * - unneccesary global vars and functions removed
 * - logging functionality made more general (all log messages are now in one array for easy access)
 * - private and public methods separated
 * - for faster query response removed playlist updates with every request 
 * - removed GetDirTest ( same as GetDir )
 * - removed sort functions (lack of documentation and implemented unproperly)
 * - modified Play() function. Now can play song by songid
 * 
 * 
 * Sven Ginka (sven.ginka@gmail.com) 03/2010
 * Version mpd.class.php-1.3
 * - take over from Hendrik Stoetter
 * - removed "split()" as this function is marked depracted
 * - added property "xfade" (used by IPodMp, phpMp+)
 * - added property "bitrate" (used by phpMp+)
 * - added define "MPD_SEARCH_FILENAME"
 * - included sorting algorithm "msort"
 * - added function validateFile() for guessing a title if no ID3 data is given 
 * 
 * Hendrik Stoetter 03/2008
 * - this a lightly modified version of mod.class Version 1.2.
 * - fixed some bugs and added some new functions
 * - Changes:
 * 		GetDir($url) -> GetDir(url,$sort)
 * 		var $stats
 * 
 *  Benjamin Carlisle 05/05/2004
 * 
 *  mpd.class.php - PHP Object Interface to the MPD Music Player Daemon
 *  Version 1.2, Released 05/05/2004
 *  Copyright (C) 2003-2004  Benjamin Carlisle (bcarlisle@24oz.com)
 *  http://mpd.24oz.com/ | http://www.musicpd.org/
 *
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *
 */ 


class mpd
{      
        /**
	 * We create common command definitions for MPD to use
	 * MPD command reference at http://www.musicpd.org/doc/protocol/index.html
	 */
	const MPD_CMD_CURSONG =	    "currentsong"; 
	const MPD_CMD_STATUS =	    "status";
	const MPD_CMD_STATISTICS =  "stats";
	const MPD_CMD_VOLUME =	    "volume";
	const MPD_CMD_SETVOL =	    "setvol";
	const MPD_CMD_PLAY =	    "play";
	const MPD_CMD_PLAYID =	    "playid";
	const MPD_CMD_STOP =	    "stop";
	const MPD_CMD_PAUSE =       "pause";
	const MPD_CMD_NEXT =        "next";
	const MPD_CMD_PREV =        "previous";
	const MPD_CMD_PLLIST =      "playlistinfo";
	const MPD_CMD_PLADD =       "add";
	const MPD_CMD_PLADDID =     "addid";
	const MPD_CMD_PLADDURI =    "playlistadd";
	const MPD_CMD_PLREMOVE =    "deleteid";
	const MPD_CMD_PLCLEAR =     "clear";
	const MPD_CMD_PLSHUFFLE =   "shuffle";
	const MPD_CMD_PLLOAD =      "load";
	const MPD_CMD_PLSAVE =      "save";
	const MPD_CMD_KILL =        "kill";
	const MPD_CMD_UPDATE =      "update";
	const MPD_CMD_REPEAT =      "repeat";
	const MPD_CMD_LSDIR =       "lsinfo";
	const MPD_CMD_SEARCH =      "search";
	const MPD_CMD_START_BULK =  "command_list_begin";
	const MPD_CMD_END_BULK =    "command_list_end";
	const MPD_CMD_FIND =        "find";
	const MPD_CMD_RANDOM =      "random";
	const MPD_CMD_SEEK =        "seek";
	const MPD_CMD_PLSWAPTRACK = "swapid";
	const MPD_CMD_PLMOVETRACK = "moveid";
	const MPD_CMD_PLDELETE =    "rm";
	const MPD_CMD_PASSWORD =    "password";
	const MPD_CMD_TABLE =       "list";
	const MPD_CMD_PLMOVE =      "move";
	const MPD_CMD_IDLE =	    "idle";
	const MPD_CMD_NOIDLE =	    "noidle";

	// Predefined MPD Response messages
	const MPD_RESPONSE_ERR =    "ACK";
	const MPD_RESPONSE_OK =	    "OK";

	// MPD State Constants
	const MPD_STATE_PLAYING =   "play";
	const MPD_STATE_STOPPED =   "stop";
	const MPD_STATE_PAUSED =    "pause";

	// MPD Searching Constants
	const MPD_SEARCH_ARTIST =   "artist";
	const MPD_SEARCH_GENRE =    "genre";
	const MPD_SEARCH_TITLE =    "title";
	const MPD_SEARCH_ALBUM =    "album";
	const MPD_SEARCH_ANY =	    "any";
	const MPD_SEARCH_FILENAME = "filename"; 

	// MPD Cache Tables
	const MPD_TBL_ARTIST =	    "artist";
	const MPD_TBL_ALBUM =	    "album";


	// TCP/Connection variables
	public $host;
	public $port;
	public $password;
	public $mpd_sock   = NULL;
	public $connected  = FALSE;
	public $mpd_version    = "(unknown)";
	
	
	public $currentsong = array();	    // song data currently playing
	public $status = array();	    // mpd status params
	public $stats = array();	    // mpd statistics params
	public $playlist = array();	    // playlist array
	public $playlist_count;		    // playlist array length	
	public $command_queue;		    // The list of commands for bulk command sending

	// Misc Other Vars	
	public $mpd_class_version = "1.4";
	
	public $debug = FALSE;		    // Set to TRUE to turn extended debugging on.
	public $log = array();		    // array holding log messages
	public $errStr = NULL;		    // Used for maintaining information about the last error message

	
        /** 
	 * Command compatibility tables
	 */ 
	private $COMPATIBILITY_MIN_TBL = array(
		self::MPD_CMD_SEEK => "0.9.1",
		self::MPD_CMD_PLMOVE => "0.9.1",
		self::MPD_CMD_RANDOM => "0.9.1",
		self::MPD_CMD_PLSWAPTRACK => "0.9.1",
		self::MPD_CMD_PLMOVETRACK => "0.9.1",
		self::MPD_CMD_PASSWORD	=> "0.10.0",
		self::MPD_CMD_SETVOL => "0.10.0"       
	);

	private $COMPATIBILITY_MAX_TBL = array(
		self::MPD_CMD_VOLUME => "0.10.0"
	);

	
/*********************************************************/
/***************** BEGIN OBJECT METHODS ******************/
/*********************************************************/

        /** 
	 * Builds the MPD object, connects to the server, and refreshes all local object properties.
	 * @param string $srv - mpd server address
	 * @param string $port - mpd server port nr.
	 * @param string $pwd - mpd server password (optional)
	 * @param boolean $debug - set true to enable debug mode (optional)
	 */
	function __construct($srv,$port,$pwd=NULL, $debug=FALSE)
	{
	    
	    $this->host = $srv;
	    $this->port = $port;
	    $this->password = $pwd;
	    $this->debug = $debug;
        
	    $resp = $this->Connect();
	    
	    if( is_null($resp) ){
		$this->_addErr(__METHOD__,"Could not connect");
		return;
	    }else{
		$this->_addLog(__METHOD__,"Connected");
		
		list ( $this->mpd_version ) = sscanf($resp, self::MPD_RESPONSE_OK . " MPD %s\n");
		
		if( ! is_null($pwd) ) {
		    
		    if( is_null($this->SendCommand(self::MPD_CMD_PASSWORD,$pwd)) ){
			$this->connected = FALSE;
			$this->_addErr(__METHOD__,"Bad password");
			return;  // bad password or command
		    }
		    
		    if( is_null($this->GetStatus()) ){ // no read access -- might as well be disconnected!
			$this->connected = FALSE;
			$this->_addErr(__METHOD__,"Password supplied does not have read access");
			return;
		    }
		}else{
		       
		    
		    if ( is_null($this->GetStatus()) ) { // no read access -- might as well be disconnected!
			$this->connected = FALSE;
			$this->_addErr(__METHOD__,"Password required to access server");
			return; 
		    }
		}
	    }
	}
	
	
        /**  
	 * Connects to the MPD server. 
	 * 
	 * NOTE: This is called automatically upon object instantiation; you should not need to call this directly.
	 */
	public function Connect() {
		$this->_addLog(__METHOD__,"host: '".$this->host."', port: '".$this->port."'");
		$this->mpd_sock = fsockopen($this->host,$this->port,$errNo,$errStr,10);
		if (!$this->mpd_sock) {
			$this->_addErr(__METHOD__,"Socket Error: $errStr ($errNo)");
			return NULL;
		} else {
			$counter=0;
			while(!feof($this->mpd_sock)) {
				$counter++;
				if ($counter > 10){
					$this->_addErr(__METHOD__,"No file end");
					return NULL;
				}
				$response =  fgets($this->mpd_sock,1024);
				$this->_addLog(__METHOD__,$response);
								
				if (strncmp(self::MPD_RESPONSE_OK,$response,strlen(self::MPD_RESPONSE_OK)) == 0) {
					$this->connected = TRUE;					
					return $response;
				}
				if (strncmp(self::MPD_RESPONSE_ERR,$response,strlen(self::MPD_RESPONSE_ERR)) == 0) {
					// close socket
					fclose($this->mpd_sock);
					$this->_addErr(__METHOD__,"Server responded with: $response");
					return NULL;
				}

			}
			// close socket
			fclose($this->mpd_sock);
			// Generic response
			$this->_addErr(__METHOD__,"Connection not available");
			return NULL;
		}
	}

        /** 
         * SendCommand()
	 * 
	 * Sends a generic command to the MPD server. Several command constants are pre-defined for 
	 * use (see MPD_CMD_* constant definitions above). 
	 */
	public function SendCommand($cmdStr,$arg1 = "",$arg2 = "")
	{
		$this->_addLog(__METHOD__,"cmd: '".$cmdStr."', args: '".$arg1."', '".$arg2."'");

		// Clear out the error String
		$this->errStr = NULL;
		$respStr = "";

		if ( !$this->connected ) {
			$this->_addErr(__METHOD__,"Not connected");
		}else{

			// Check the command compatibility:
			if ( !$this->_checkCompatibility($cmdStr) ) {
				return NULL;
			}

			if (strlen($arg1) > 0) $cmdStr .= " \"$arg1\"";
			if (strlen($arg2) > 0) $cmdStr .= " \"$arg2\"";
			fputs($this->mpd_sock,"$cmdStr\n");
			while(!feof($this->mpd_sock)) {
				$response = fgets($this->mpd_sock,1024);
				//$this->_addLog($response);
				
				// An OK signals the end of transmission -- we'll ignore it
				if (strncmp(self::MPD_RESPONSE_OK,$response,strlen(self::MPD_RESPONSE_OK)) == 0) {
					break;
				}

				// An ERR signals the end of transmission with an error! Let's grab the single-line message.
				if (strncmp(self::MPD_RESPONSE_ERR,$response,strlen(self::MPD_RESPONSE_ERR)) == 0) {
					list ( $junk, $errTmp ) = strtok(self::MPD_RESPONSE_ERR . " ",$response );
					$this->_addErr(__METHOD__,' >>> '.strtok($errTmp,"\n") );
					$this->_addErr(__METHOD__,' >>> '.$response);
					return NULL;
				}

				// Build the response string
				$respStr .= $response;
			}
			//$this->_addLog(__METHOD__,"response: '".$respStr."'");
		}
		return $respStr;
	}

        /** 
         * QueueCommand() 
	 *
	 * Queues a generic command for later sending to the MPD server. The CommandQueue can hold 
	 * as many commands as needed, and are sent all at once, in the order they are queued, using 
	 * the SendCommandQueue() method. The syntax for queueing commands is identical to SendCommand(). 
	 */
	public function QueueCommand($cmdStr,$arg1 = "",$arg2 = "")
	{
		$this->_addLog(__METHOD__,"cmd: '".$cmdStr."', args: '".$arg1."', '".$arg2."'");
		if ( !$this->connected ){
			$this->_addErr(__METHOD__,"Not connected");
			return NULL;
		}else{
			if ( strlen($this->command_queue) == 0 ) {
				$this->command_queue = self::MPD_CMD_START_BULK . "\n";
			}
			if (strlen($arg1) > 0) $cmdStr .= " \"$arg1\"";
			if (strlen($arg2) > 0) $cmdStr .= " \"$arg2\"";

			$this->command_queue .= $cmdStr ."\n";

			$this->_addLog(__METHOD__,"return");
		}
		return TRUE;
	}

        /** 
         * SendCommandQueue() 
	 *
	 * Sends all commands in the Command Queue to the MPD server. See also QueueCommand().
	 */
	public function SendCommandQueue()
	{
		$this->_addLog(__METHOD__,"send");
		if ( !$this->connected ){
			$this->_addErr(__METHOD__,"Not connected");
			return NULL;
		}else{
			$this->command_queue .= self::MPD_CMD_END_BULK . "\n";
			if ( is_null($respStr = $this->SendCommand($this->command_queue)) ){
			    return NULL;
			}else{
			    $this->command_queue = NULL;
			    $this->_addLog(__METHOD__,"response: '".$respStr."'");
			}
		}
		return $respStr;
	}
	
	/**
	 * 
	 * 
	 */
	public function Idle()
	{
		$this->_addLog(__METHOD__,"send");
		if ( ! is_null($resp = $this->SendCommand(self::MPD_CMD_IDLE))){
			
			$resp=trim($resp);
			list($element, $value) = explode(": ",$resp);    
		}else{
			$this->_addErr(__METHOD__,"No response");
			return NULL;		
		}    
		$this->_addLog(__METHOD__,"response: '".$resp."'");
		return $resp;
	}
	/**
	 * 
	 * 
	 */
	public function NoIdle()
	{
		$this->_addLog(__METHOD__,"send");
		if ( ! is_null($resp = $this->SendCommand(self::MPD_CMD_NOIDLE))){
			
			$resp=trim($resp);
			list($element, $value) = explode(": ",$resp);    
		}else{
			$this->_addErr(__METHOD__,"No response");
			return NULL;		
		}    
		$this->_addLog(__METHOD__,"response: '".$resp."'");
		return $resp;
	}
	
	
        /** 
         * AdjustVolume() 
	 *
	 * Adjusts the mixer volume on the MPD by <modifier>, which can be a positive (volume increase),
	 * or negative (volume decrease) value. 
	 */
	public function AdjustVolume($modifier)
	{
		$this->_addLog(__METHOD__,"modifier: '".$modifier."'");
		if ( !is_numeric($modifier) ) {
			$this->_addErr(__METHOD__,"Argument 1 must be a numeric value");
			return NULL;
		}

		if(empty($this->status))$this->GetStatus();
		$newVol = $this->status['volume'] + $modifier;
		$ret = $this->SetVolume($newVol);

		$this->_addLog(__METHOD__,"response: '".$ret."'");
		return $ret;
	}

        /** 
         * SetVolume() 
	 *
	 * Sets the mixer volume to <newVol>, which should be between 1 - 100.
	 */
	public function SetVolume($newVol)
	{
		$this->_addLog(__METHOD__,"vol: '".$newVol."'");
		if ( ! is_numeric($newVol) ) {
			$this->_addErr(__METHOD__,"Argument 1 must be a numeric value");
			return NULL;
		}

		// Forcibly prevent out of range errors
		if ( $newVol < 0 )   $newVol = 0;
		if ( $newVol > 100 ) $newVol = 100;

		// If we're not compatible with SETVOL, we'll try adjusting using VOLUME
		if ( $this->_checkCompatibility(self::MPD_CMD_SETVOL) ){
		    if ( ! is_null($ret = $this->SendCommand(self::MPD_CMD_SETVOL,$newVol))) $this->status['volume'] = $newVol;
		}else{
			$this->GetStatus();     // Get the latest volume
			if( !$this->status['volume'] ){
			    return NULL;
			}else{
			    $modifier = ( $newVol - $this->status['volume'] );
			    if( !is_null($ret = $this->SendCommand(self::MPD_CMD_VOLUME,$modifier))) $this->status['volume'] = $newVol;
			}
		}
		$this->_addLog(__METHOD__,"response: '".$ret."'");
		return $ret;
	}
	

	
        /** 
         * GetDir() 
	 * 
	 * Retrieves a database directory listing of the <dir> directory and places the results into
	 * a multidimensional array. If no directory is specified, the directory listing is at the 
	 * base of the MPD music path. 
	 */
	public function GetDir($dir = "",$sort = "")
	{
		
		$this->_addLog(__METHOD__,"dir: '".$dir."' sort: '".$sort."'");
		$resp = $this->SendCommand(self::MPD_CMD_LSDIR,$dir);
		
		$listArray = $this->_parseFileListResponse($resp);

		if ($listArray==null){
			$this->_addErr( __METHOD__,"Music dir empty!");
			return null;
		}
		$this->_addLog(__METHOD__,"response: '".$listArray."'",$listArray);
		return $listArray;
	}

	
        /** 
         * PLAdd() 
	 * 
	 * Adds each track listed in a single-dimensional <trackArray>, which contains filenames 
	 * of tracks to add, to the end of the playlist. This is used to add many, many tracks to 
	 * the playlist in one swoop.
	 */
	public function PLAddBulk($trackArray)
	{
		$this->_addLog(__METHOD__,"trackArray: '".$trackArray."'");
		$num_files = count($trackArray);
		for ( $i = 0; $i < $num_files; $i++ ) {
			$this->QueueCommand(self::MPD_CMD_PLADD,$trackArray[$i]);
		}
		$resp = $this->SendCommandQueue();
		$this->GetStatus();
		$this->_addLog(__METHOD__,"response: '".$resp."'");
		return $resp;
	}

        /** 
         * PLAdd() 
	 * 
	 * Adds the file <file> to the end of the playlist. <file> must be a track in the MPD database. 
	 */
	public function PLAddTrack($fileName) {
		$this->_addLog(__METHOD__,"filename: '".$fileName."'");
		$value = NULL;
		if ( ! is_null($resp = $this->SendCommand(self::MPD_CMD_PLADDID,$fileName))){
		    $this->GetStatus();
		    $resp=trim($resp);
			list($element, $value) = explode(": ",$resp);
			
		}else{
		    $this->_addErr(__METHOD__,"No response");
		}
		$this->_addLog(__METHOD__,"response: '".$value."'");
		return (int)$value;
	}
	
	
	public function PLAdd($fileName) {
		$this->_addLog(__METHOD__,"filename: '".$fileName."'");
		$value = NULL;
		if ( ! is_null($resp = $this->SendCommand(self::MPD_CMD_PLADD,$fileName))){
		    $this->GetStatus();
			
		}else{
		    $this->_addErr(__METHOD__,"No response");
		}
		$this->_addLog(__METHOD__,"response: '".$resp."'");
		return $resp;
		
	}
	

        /** 
         * PLMoveTrack() 
	 * 
	 * Moves track number <origPos> to position <newPos> in the playlist. This is used to reorder 
	 * the songs in the playlist.
	 */
	public function PLMoveTrack($spos, $epos)
	{
		$this->_addLog(__METHOD__,"spos: '".$spos."', epos: '".$epos."'");
		if(!is_numeric($spos)){
		    $this->_addErr(__METHOD__,'Argument 1 must be a numeric vlue');
		    return NULL;
		}else if(!is_numeric($epos)){
		    $this->_addErr(__METHOD__,'Argument 2 must be a numeric vlue');
		    return NULL;
		}
		
		if ( ! is_null($resp = $this->SendCommand(self::MPD_CMD_PLMOVETRACK,$spos,$epos))){
		    $this->GetStatus();
		}
		
		$this->_addLog(__METHOD__,"response: '".$resp."'");
		return $resp;
	}
	
	
	/** 
         * PLMoveTrack() 
	 * 
	 * Moves track number <origPos> to position <newPos> in the playlist. This is used to reorder 
	 * the songs in the playlist.
	 */
	public function PLSwapTrack($sid,$eid)
	{
		$this->_addLog(__METHOD__,"sid: '".$sid."', eid: '".$eid."'");
//		if(!is_numeric($sid)){
//		    $this->_addErr(__METHOD__,'Argument 1 must be a numeric vlue');
//		    return NULL;
//		}else if(!is_numeric($eid)){
//		    $this->_addErr(__METHOD__,'Argument 2 must be a numeric vlue');
//		    return NULL;
//		}
		
		if ( ! is_null($resp = $this->SendCommand(self::MPD_CMD_PLSWAPTRACK,$sid,$eid))){
		    $this->GetStatus();
		    $this->GetPlaylist();
		}
		
		$this->_addLog(__METHOD__,"response: '".$resp."'");
		return $resp;
	}
	

        /** 
         * PLShuffle() 
	 * 
	 * Randomly reorders the songs in the playlist.
	 */
	public function PLShuffle()
	{
		$this->_addLog(__METHOD__,"send");
		if ( ! is_null($resp = $this->SendCommand(self::MPD_CMD_PLSHUFFLE))){
		    $this->GetStatus();
		    $this->GetPlaylist();
		}
		$this->_addLog(__METHOD__,"response: '".$resp."'");
		return $resp;
	}

        /** 
         * PLLoad() 
	 * 
	 * Retrieves the playlist from <file>.m3u and loads it into the current playlist. 
	 */
	public function PLLoad($file)
	{
		$this->_addLog(__METHOD__,"file: '".$file."'");
		if ( ! is_null($resp = $this->SendCommand(self::MPD_CMD_PLLOAD,$file))) $this->GetStatus();
		$this->_addLog(__METHOD__,"response: '".$resp."'");
		return $resp;
	}

        /** 
         * PLSave() 
	 * 
	 * Saves the playlist to <file>.m3u for later retrieval. The file is saved in the MPD playlist
	 * directory.
	 */
	public function PLSave($file)
	{
		$this->_addLog(__METHOD__,"file: '".$file."'");
		$resp = $this->SendCommand(self::MPD_CMD_PLSAVE,$file);
		$this->_addLog(__METHOD__,"response: '".$resp."'");
		return $resp;
	}
	/**
	 * Saves playlist containing url
	 * @param string $fileName
	 * @param string $uri
	 * @return  
	 */
	public function PLSaveStream($fileName,$uri) {
		$this->_addLog(__METHOD__,"filename: '".$fileName."', uri: '".$uri."'");
		$resp = $this->SendCommand(self::MPD_CMD_PLADDURI,$fileName,$uri);
		$this->_addLog(__METHOD__,"response: '".$resp."'");
		return $resp;
		
	}

        /** 
         * PLClear() 
	 * 
	 * Empties the playlist.
	 */
	public function PLClear()
	{
		$this->_addLog(__METHOD__,"send");
		if ( ! is_null($resp = $this->SendCommand(self::MPD_CMD_PLCLEAR))){
		    $this->GetStatus();
		    $this->playlist = null;
		}
		$this->_addLog(__METHOD__,"response: '".$resp."'");
		return $resp;
	}

        /** 
         * PLRemove() 
	 * 
	 * Removes track <id> from the playlist.
	 */
	public function PLRemoveTrack($id)
	{
		$this->_addLog(__METHOD__,"id: '".$id."'");
		if ( ! is_numeric($id) ) {
			$this->_addErr(__METHOD__,"Argument 1 must be a numeric value");
			return NULL;
		}
		if ( ! is_null($resp = $this->SendCommand(self::MPD_CMD_PLREMOVE,$id))){
		    $this->GetStatus();
		    $this->GetPlaylist();
		}
		$this->_addLog(__METHOD__,"response: '".$resp."'");
		return $resp;
	}
	
	/**
	 * Deletes playlist from playlist dir.
	 * @param string $name - playlist name  
	 */
	public function PLRemove($name)
	{
		$this->_addLog(__METHOD__,"name: '".$name."'");
		if ( ! is_string($name) ) {
			$this->_addErr(__METHOD__,"Argument 1 must be a string value");
			return NULL;
		}
		if ( ! is_null($resp = $this->SendCommand(self::MPD_CMD_PLDELETE,$name))){
		    $this->GetStatus();
		}
		$this->_addLog(__METHOD__,"response: '".$resp."'");
		return $resp;
	}

        /** 
         * SetRepeat() 
	 * 
	 * Enables 'loop' mode -- tells MPD continually loop the playlist. The <repVal> parameter 
	 * is either 1 (on) or 0 (off).
	 */
	public function SetRepeat($repVal)
	{
		$this->_addLog(__METHOD__,"repeatValue: '".$repVal."'");
		$rpt = $this->SendCommand(self::MPD_CMD_REPEAT,$repVal);
		$this->status['repeat'] = $repVal;
		$this->_addLog(__METHOD__,"response: '".$rpt."'");
		return $rpt;
	}

        /** 
         * SetRandom() 
	 * 
	 * Enables 'randomize' mode -- tells MPD to play songs in the playlist in random order. The
	 * <rndVal> parameter is either 1 (on) or 0 (off).
	 */
	public function SetRandom($rndVal)
	{
		$this->_addLog(__METHOD__,"randomVal: '".$rndVal."'");
		$resp = $this->SendCommand(self::MPD_CMD_RANDOM,$rndVal);
		$this->status['random'] = $rndVal;
		$this->_addLog(__METHOD__,"response: '".$resp."'");
		return $resp;
	}

        /** 
         * Shutdown() 
	 * 
	 * Shuts down the MPD server (aka sends the KILL command). This closes the current connection, 
	 * and prevents future communication with the server. 
	 */
	public function Shutdown()
	{
		$this->_addLog(__METHOD__,"send");
		$resp = $this->SendCommand(self::MPD_CMD_SHUTDOWN);

		$this->connected = FALSE;
		unset($this->mpd_version);
		unset($this->errStr);
		unset($this->mpd_sock);

		$this->_addLog(__METHOD__,"response: '".$resp."'");
		return $resp;
	}

        /** 
         * DBRefresh() 
	 * 
	 * Tells MPD to rescan the music directory for new tracks, and to refresh the Database. Tracks 
	 * cannot be played unless they are in the MPD database.
	 */
	public function DBUpdate($uri=null)
	{
		$this->_addLog(__METHOD__,"uri: '".$uri."'");
		//return 'k';
		$value = NULL;
		if( !is_null($resp = $this->SendCommand(self::MPD_CMD_UPDATE,$uri))){
		    $resp=trim($resp);
		    list($element, $value) = explode(": ",$resp);
		    // Update local variables
		    $this->GetStatus();
		}else{
		    $this->_addErr(__METHOD__,'No response!');
		    return NULL;
		}  
		$this->_addLog(__METHOD__,"response: '".$value."'");
		return $value;
	}

        /** 
         * Play() 
	 * 
	 * Begins playing the songs in the MPD playlist. 
	 */
	public function Play($songId=NULL)
	{
		$this->_addLog(__METHOD__,"songId: '".$songId."'");
		if(is_null($songId)){
		    $rpt = $this->SendCommand(self::MPD_CMD_PLAY); 
		}else{
		    $rpt = $this->SendCommand(self::MPD_CMD_PLAYID,(int)$songId); 
		}
		
		if (!is_null($rpt)){
		    $this->GetStatus();
		    $this->GetCurrentSong();
		} 
		
		$this->_addLog(__METHOD__,"response: '".$rpt."'");
		return $rpt;
	}
	
	
	/** 
         * PlayPos() 
	 * 
	 * Plays the song in the MPD playlist position. 
	 */
	public function PlayPos($songPos=NULL)
	{
		$this->_addLog(__METHOD__,"pos: '".$songPos."'");
		if(is_null($songPos)){
		    
		}else{
		    $rpt = $this->SendCommand(self::MPD_CMD_PLAY, (int)$songPos); 
		    
		}
		if (!is_null($rpt)){
		    $this->GetStatus();
		    $this->GetCurrentSong();
		} 
		
		$this->_addLog(__METHOD__,"response: '".$rpt."'");
		return $rpt;
	}

        /** 
         * Stop() 
	 * 
	 * Stops playing the MPD. 
	 */
	public function Stop()
	{
		$this->_addLog(__METHOD__,"send");
		if ( ! is_null($rpt = $this->SendCommand(self::MPD_CMD_STOP) )) $this->GetStatus();
		$this->_addLog(__METHOD__,"response: '".$rpt."'");
		return $rpt;
	}

        /** 
         * Pause() 
	 * 
	 * Toggles pausing on the MPD. Calling it once will pause the player, calling it again
	 * will unpause. 
	 */
	public function Pause()
	{
		$this->_addLog(__METHOD__,"send");
		if ( ! is_null($rpt = $this->SendCommand(self::MPD_CMD_PAUSE) )) $this->GetStatus();
		$this->_addLog(__METHOD__,"response: '".$rpt."'");
		return $rpt;
	}
	
        /** 
         * SeekTo() 
	 * 
	 * Skips directly to the <idx> song in the MPD playlist. 
	 */
	public function SkipTo($idx)
	{ 
		$this->_addLog(__METHOD__,"idx: '".$idx."'");
		if ( ! is_numeric($idx) ) {
			$this->_addErr(__METHOD__,"Argument 1 must be a numeric value");
			return NULL;
		}
		if ( ! is_null($rpt = $this->SendCommand(self::MPD_CMD_PLAY,$idx))) $this->GetStatus();
		$this->_addLog(__METHOD__,"response: '".$rpt."'");
		return $idx;
	}

        /** 
         * SeekTo() 
	 * 
	 * Skips directly to a given position within a track in the MPD playlist. The <pos> argument,
	 * given in seconds, is the track position to locate. The <track> argument, if supplied is
	 * the track number in the playlist. If <track> is not specified, the current track is assumed.
	 */
	public function SeekTo($pos, $track = -1)
	{ 
		$this->_addLog(__METHOD__,"pos: '".$pos."', track: '".$track."'");
		if ( ! is_numeric($pos) ) {
			$this->_addErr(__METHOD__,"Argument 1 must be a numeric value");
			return NULL;
		}
		if ( ! is_numeric($track) ) {
			$this->_addErr(__METHOD__,"Argument 2 must be a numeric value");
			return NULL;
		}
		if ( $track == -1 ) { 
			$track = isset($this->status['song']) ? $this->status['song'] : -1;
		} 
		
		if ( ! is_null($rpt = $this->SendCommand(self::MPD_CMD_SEEK,$track,$pos))){
		    $this->GetStatus();
		    $this->GetCurrentSong();
		}
		$this->_addLog(__METHOD__,"response: '".$pos."'");
		return $pos;
	}

        /** 
         * Next() 
	 * 
	 * Skips to the next song in the MPD playlist. If not playing, returns an error. 
	 */
	public function Next()
	{
		$this->_addLog(__METHOD__,"send");
		if ( !is_null($rpt = $this->SendCommand(self::MPD_CMD_NEXT)) ){
		    $this->GetStatus();
		    $this->GetCurrentSong();
		}    
		$this->_addLog(__METHOD__,"response: '".$rpt."'");		
		return $rpt;
	}

        /** 
         * Previous() 
	 * 
	 * Skips to the previous song in the MPD playlist. If not playing, returns an error. 
	 */
	public function Previous()
	{
		$this->_addLog(__METHOD__,"send");
		if ( !is_null($rpt = $this->SendCommand(self::MPD_CMD_PREV)) ){
		    $this->GetStatus();
		    $this->GetCurrentSong();
		}
		$this->_addLog(__METHOD__,"response: '".$rpt."'");
		return $rpt;
	}
	
        /** 
         * Search() 
	 * 
	 * Searches the MPD database. The search <type> should be one of the following: 
	 * MPD_SEARCH_ARTIST, MPD_SEARCH_TITLE, MPD_SEARCH_ALBUM
	 * The search <string> is a case-insensitive locator string. Anything that contains 
	 * <string> will be returned in the results. 
	 */
	public function Search($type,$string)
	{
		$this->_addLog(__METHOD__,"type: '".$type."', string: '".$string."'");
		if ($type != self::MPD_SEARCH_ARTIST and
		    $type != self::MPD_SEARCH_ALBUM and
		    $type != self::MPD_SEARCH_ANY and
		    $type != self::MPD_SEARCH_GENRE and
		    $type != self::MPD_SEARCH_TITLE ) {
			$this->_addErr(__METHOD__,"Invalid search type");
			return NULL;
		} else {
			if ( is_null($resp = $this->SendCommand(self::MPD_CMD_SEARCH,$type,$string)))	return NULL;
			$searchlist = $this->_parseFileListResponse($resp);
		}
		$this->_addLog(__METHOD__,"response: '".$searchlist."'",$searchlist);
		return $searchlist;
	}

        /** 
         * Find() 
	 * 
	 * Find() looks for exact matches in the MPD database. The find <type> should be one of 
	 * the following: 
	 * MPD_SEARCH_ARTIST, MPD_SEARCH_TITLE, MPD_SEARCH_ALBUM
	 * The find <string> is a case-insensitive locator string. Anything that exactly matches 
	 * <string> will be returned in the results. 
	 */
	public function Find($type,$string)
	{
		$this->_addLog(__METHOD__,"type: '".$type."', string: '".$string."'");
		if ($type != self::MPD_SEARCH_ARTIST and
		    $type != self::MPD_SEARCH_ALBUM and
		    $type != self::MPD_SEARCH_TITLE ) {
		    $this->_addErr(__METHOD__,"Invalid find type");
			return NULL;
		} else {
			if ( is_null($resp = $this->SendCommand(self::MPD_CMD_FIND,$type,$string)))	return NULL;
			$searchlist = $this->_parseFileListResponse($resp);
		}
		$this->_addLog(__METHOD__,"response: '".$searchlist."'",$searchlist);
		return $searchlist;
	}

        /** 
         * Disconnect() 
	 * 
	 * Closes the connection to the MPD server.
	 */
	public function Disconnect()
	{
		$this->_addLog(__METHOD__,"send");
		fclose($this->mpd_sock);

		$this->connected = FALSE;
		unset($this->mpd_version);
		unset($this->errStr);
		unset($this->mpd_sock);
		$this->_addLog(__METHOD__,"Disconnected");
	}
	
	/**
	 *  Updates object property currentsong value
	 *  
	 *  @return boolean
	 */
	public function GetCurrentSong()
	{
		$this->_addLog(__METHOD__,"send");
		$resp = $this->SendCommand(self::MPD_CMD_CURSONG);
		
		if (empty($resp)){
		    $this->_addErr(__METHOD__,"No response from server");
		    return NULL;
		}else{
			
			$resp = trim($resp);

			$respLine = explode("\n", $resp );
			foreach ( $respLine as $line ) {
				list($element, $value) = explode(": ",$line);
				$this->currentsong[$element] = $value;
			}
		}
		
		$this->_addLog(__METHOD__,"return");
		return true;
	}
	
	
	
	public function GetGenres()
	{
		$this->_addLog(__METHOD__,"send");
		if ( is_null($resp = $this->SendCommand(self::MPD_CMD_TABLE, self::MPD_SEARCH_GENRE))){
		    return NULL;
		}
		$genreArray = array();
		$genreLine = strtok($resp,"\n");
		$genreName = "";
		$genreCounter = -1;
		
		while ( $genreLine ) {
		    list ( $element, $value ) = explode(": ",$genreLine);
		    if ( $element == "Genre" ) {
			$genreCounter++;
			$genreName = $value;
			$genreArray[$genreCounter] = $genreName;
		    }

		    $genreLine = strtok("\n");
		}
		$this->_addLog(__METHOD__,"response: '".$genreArray."'",$genreArray);
		$genres = array_unique($genreArray);
		sort($genres);
		return $genres;
	}
	
        /** 
         * GetArtists() 
	 * 
	 * Returns the list of artists in the database in an associative array.
	 */
	public function GetArtists()
	{
		$this->_addLog(__METHOD__,"send");
		if ( is_null($resp = $this->SendCommand(self::MPD_CMD_TABLE, self::MPD_TBL_ARTIST)))	return NULL;
		
		$arArray = array();
		$arLine = strtok($resp,"\n");
		$arName = "";
		$arCounter = -1;
		
		while ( $arLine ) {
		    list ( $element, $value ) = explode(": ",$arLine);
		    if ( $element == "Artist" ) {
			$arCounter++;
			$arName = $value;
			$arArray[$arCounter] = $arName;
		    }

		    $arLine = strtok("\n");
		}
		$this->_addLog(__METHOD__,"response: '".$arArray."'",$arArray);
		return $arArray;
	}

        /** 
         * GetAlbums() 
         * 
	 * Returns the list of albums in the database in an associative array. 
	 * Optional parameter is an artist Name which will list all albums by a particular artist.
	 */
	public function GetAlbums( $ar = NULL)
	{
		$this->_addLog(__METHOD__,"artist: '".$ar."'");
		if ( is_null($resp = $this->SendCommand(self::MPD_CMD_TABLE, self::MPD_TBL_ALBUM, $ar )))	return NULL;
		
		$alArray = array();
		$alLine = strtok($resp,"\n");
		$alName = "";
		$alCounter = -1;
		
		while ( $alLine ) {
		    list ( $element, $value ) = explode(": ",$alLine);
		    if ( $element == "Album" ) {
			$alCounter++;
			$alName = $value;
			$alArray[$alCounter] = $alName;
		    }

		    $alLine = strtok("\n");
		}
		$this->_addLog(__METHOD__,"response: '".$alArray."'",$alArray);
		return $alArray;
	}
	
	

	
	/**
	 * Retrieves the 'statistics' variables from the server and updates stats array.
	 * @return boolean
	 */
	public function GetStatistics()
	{
		$this->_addLog(__METHOD__,"send");
		$statStr = $this->SendCommand(self::MPD_CMD_STATISTICS);
		if(!$statStr){
			$this->_addErr(__METHOD__,"No response");
			return NULL;
		}else{
			$statStr=trim($statStr);
			$statLine = explode( "\n", $statStr );
			foreach ( $statLine as $line ) {
				list ( $element, $value ) = explode(": ",$line);
				$this->stats[$element] = $value;
			}
			
			$this->_addLog(__METHOD__,"return");
			return true;
		}
	}
	
	/**
	 * Retrieves the 'status' variables from the server and updates status array.
	 * @return boolean
	 */
	public function GetStatus()
	{
		$this->_addLog(__METHOD__,"send");
		$statusStr = $this->SendCommand(self::MPD_CMD_STATUS);
		if(!$statusStr){
			$this->_addErr(__METHOD__,"No response");
			return NULL;
		}else{

			$statusStr=trim($statusStr);
			$statusLine = explode("\n", $statusStr );
			foreach ( $statusLine as $line ) {
				list($element, $value) = explode(": ",$line);
				$this->status[$element] = $value;
			}
			
			
			$this->_addLog(__METHOD__,"return");
			return true;
		}
	}
	
	/**
	 * Retrieves the playlist from the server and updates our playlist array and playlist_count.
	 * @return boolean 
	 */
	public function GetPlaylist()
	{
		$this->_addLog(__METHOD__,"send");
		$plStr = $this->SendCommand(self::MPD_CMD_PLLIST);
   		$array = $this->_parseFileListResponse($plStr);
   		$playlist = $array['files'];
	   	$this->playlist_count = count($playlist);
	   	$this->playlist = array();
	   	if (sizeof($playlist)>0){
			foreach ($playlist as $item ){
				$this->playlist[$item['Pos']]=$item;
			}
	   	}
		$this->_addLog(__METHOD__,"return");
		return true;
	}
	
	

/*******************************************************************************/
/***************************** INTERNAL FUNCTIONS ******************************/
/*******************************************************************************/
	

        /**
	 * @param string @method - function  name
	 * @param type $text - log message
	 * @param string $arr - log message array response
	 */
	private function _addLog($method="undefined",$text=NULL,$arr=array())
	{
	    return $this->_logEntry('log',$method,$text,$arr);
	}
	
        /**
	 * @param string @method - function name
	 * @param string $err - error message
	 * @param string $arr - error message array response
	 */
	private function _addErr($method="undefined",$err=NULL,$arr=array())
	{
	    return $this->_logEntry('error',$method,$err,$arr);
	}
	
        /**
	 *
	 * @param string $type - log type ('error','log')
	 * @param string $method - function name
	 * @param string $message - log message
	 * @param string $arr - log message array response
	 */
	private function _logEntry($type,$method,$message,$arr)
	{
	    if($this->debug) 
		$this->log[] = array(
				    'type'=>$type,
				    'method'=>$method,
				    'message'=>$message,
				    'response_array'=>$arr
				    );
	}
	
        /** 
	 * _computeVersionValue()
	 *
	 * Computes a compatibility value from a version string
	 *
	 */
	private function _computeVersionValue($verStr)
	{
		    list ($ver_maj, $ver_min, $ver_rel ) = explode(".",$verStr);
		    return ( 100 * $ver_maj ) + ( 10 * $ver_min ) + ( $ver_rel );
	}

        /**
	 *  _checkCompatibility() 
	 *
	 * Check MPD command compatibility against our internal table. If there is no version 
	 * listed in the table, allow it by default.
	 */
	private function _checkCompatibility($cmd)
	{
	    // Check minimum compatibility
	    if (isset($this->COMPATIBILITY_MIN_TBL[$cmd])){
		    $req_ver_low = $this->COMPATIBILITY_MIN_TBL[$cmd];
	    } else {
		    $req_ver_low = "0.9.1";
	    }
	    // check max compatibility
	    if (isset($this->COMPATIBILITY_MAX_TBL[$cmd])){
		    $req_ver_hi = $this->COMPATIBILITY_MAX_TBL[$cmd];	
	    } else {
		    $req_ver_hi = "0.20.0";
	    }

	    $mpd_ver = $this->_computeVersionValue($this->mpd_version);

	    if ( $req_ver_low ) {
		    $req_ver = $this->_computeVersionValue($req_ver_low);

		    if ( $mpd_ver < $req_ver ) {
			    $this->_addErr(__METHOD__,"Command '$cmd' is not compatible with this version of MPD, version ".$req_ver_low." required");
			    return FALSE;
		    }
	    }

	    // Check maximum compatibility -- this will check for deprecations
	    if ( $req_ver_hi ) {
		$req_ver = $this->_computeVersionValue($req_ver_hi);

		    if ( $mpd_ver > $req_ver ) {
			    $this->_addErr(__METHOD__,"Command '$cmd' has been deprecated in this version of MPD.");
			    return FALSE;
		    }
	    }

	    return TRUE;
	}

        /**
	 * checks the file entry and complete it if necesarry
	 * checked fields are 'Artist', 'Genre' and 'Title' 
	 *
	 */
	private function _validateFile( $fileItem )
	{

		$filename = $fileItem['file'];

		if (!isset($fileItem['Artist'])){ $fileItem['Artist']=null; }
		if (!isset($fileItem['Genre'])){ $fileItem['Genre']=null; }
		
		// special conversion for streams 				
		if (stripos($filename, 'http' )!==false){
			if (!isset($fileItem['Title'])) $title = ''; else $title=$fileItem['Title'];
			if (!isset($fileItem['Name'])) $name = ''; else $name=$fileItem['Name'];
			if (!isset($fileItem['Artist'])) $artist = ''; else $artist=$fileItem['Artist'];
			
			if (strlen($title.$name.$artist)==0){
				$fileItem['Title'] = $filename;
			} else {
				$fileItem['Title'] = 'stream://'.$title.' '.$name.' '.$artist;	
			}
			
		}
				 				
		if (!isset($fileItem['Title'])){ 
			$file_parts = explode('/', $filename);
			$fileItem['Title'] = $filename;
		}
				
		return $fileItem;		
	}
	
        /**
	 * take the response of mpd and split it up into
	 * items of types 'file', 'directory' and 'playlist' 
	 * 
	 */
	private function _extractItems( $resp )
	{
	
		if ( $resp == null ) {
			$this->_addLog(__METHOD__,"Empty file list");
			return NULL;
		} 
		
		// strip unwanted chars
		$resp = trim($resp);
		// split up into lines 
		$lineList = explode("\n", $resp );
		
		$array = array();
		
		$item=null;
		foreach ($lineList as $line ){
			list ( $element, $value ) = explode(": ",$line);

			
			// if one of the key words come up, store the item
			if (($element == "directory") or ($element=="playlist") or ($element=="file")){
				if ($item){
					$array[] = $item;
				}
				$item = array();
			}
			$item[$element] = $value;								
		}
		// check if there is a last item to store
		if (sizeof($item)>0){
			$array[] = $item;
		}
		
		return $array;			
	}
	
	
        /** 
         * _parseFileListResponse() 
	 * 
	 * Builds a multidimensional array with MPD response lists.
	 *
	 * NOTE: This function is used internally within the class. It should not be used.
	 */
	private function _parseFileListResponse($resp)
	{
		
		
		$valuesArray = $this->_extractItems( $resp );
		
		
		if ($valuesArray == null ){
			return null;
		}

		//1. create empty arrays
		$directoriesArray = array();
		$filesArray = array();
		$playlistsArray = array();
		

		//2. sort the items 		
		foreach ( $valuesArray as $item ) {
			
			if (isset($item['file'])){
				$filesArray[] = $this->_validateFile($item);
			} else if (isset($item['directory'])){
				$directoriesArray[] = $item['directory'];
			} else if (isset($item['playlist'])){
				$playlistsArray[] = $item['playlist'];	
			} else {
				$this->_addErr(__METHOD__,"Should not enter this");
			}
		} 
		
		//3. create a combined list of items		
		$returnArray = array(
				    "directories"=>$directoriesArray,
				    "playlists"=>$playlistsArray,
				    "files"=>$filesArray
				    );
		
		$this->_addLog(__METHOD__,"response: '".$valuesArray."'",$valuesArray);
		return $returnArray;
	}
	

	
	

	
}   // ---------------------------- end of class ------------------------------



?>

