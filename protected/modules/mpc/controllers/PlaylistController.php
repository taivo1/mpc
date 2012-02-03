<?php

class PlaylistController extends Controller
{
	/**
	 * 
	 */ 
	public function actionIndex()
	{
	    if(isset($_GET['idle']) && $_GET['idle'] == "true") $this->module->mpd->NoIdle();
	    $this->module->mpd->GetPlaylist();
	    $this->module->mpd->GetCurrentSong();
	    $this->render('playlist',array('mpd'=>$this->module->mpd));
	}
	
	/**
	 * 
	 * 
	 */
	public function actionRemove()
	{
	    if(isset($_POST['songid'])){
		/**
		 * if last item we clear the playlist instead
		 */
		if(isset($this->module->mpd->status['playlistlength']) && (int)$this->module->mpd->status['playlistlength'] > 1){
		    $this->module->mpd->PLRemoveTrack((int)$_POST['songid']);    
		}else{
		    $this->module->mpd->PLClear();
		}
		$this->render('playlist',array('mpd'=>$this->module->mpd));
	    }
	}
	
	/**
	 * 
	 * 
	 */
	public function actionClear()
	{
	    $this->module->mpd->PLClear();
	    $this->render('playlist',array('mpd'=>$this->module->mpd));
	}
	
	
	/**
	 * 
	 * 
	 */
	public function actionSave()
	{
	    if(isset($_POST['name']) && !isset($_POST['url'])){
		$this->module->mpd->PLSave($_POST['name']);
		
	    }
	    elseif(isset($_POST['name'],$_POST['url'])){
		$this->module->mpd->PLSaveStream($_POST['name'],$_POST['url']);
	    }
	    $this->render('playlist',array('mpd'=>$this->module->mpd));
	}
	
	
	/**
	 * 
	 * 
	 */
	public function actionShuffle()
	{
	    $this->module->mpd->PLShuffle();
	    $this->render('playlist',array('mpd'=>$this->module->mpd));
	}
	
	
	/**
	 * 
	 */
	public function actionMove()
	{
	    if(isset($_POST['trackid'],$_POST['position'])){
		if(isset($_POST['idle']) && $_POST['idle'] == "true") $this->module->mpd->NoIdle();
		$this->module->mpd->PLMoveTrack((int)$_POST['trackid'],(int)$_POST['position']);
		$this->module->mpd->GetPlaylist();
		$this->render('playlist',array('mpd'=>$this->module->mpd));
	    }
	}
}
?>
