<?php

class PlayerController extends Controller
{
    
	public function actionPause()
	{
	    echo json_encode($this->module->mpd->Pause());
	}
	
	public function actionPlay()
	{
	    if(isset($_POST['id'])){
		$this->module->mpd->Play($_POST['id']);
	    }else{
		$this->module->mpd->Play();
	    }
	    $this->module->mpd->GetPlaylist();
	    $data = array('current'=>$this->module->mpd->currentsong,'status'=>$this->module->mpd->status, 'playlist'=>$this->module->mpd->playlist);
	    echo json_encode($data);
	}
	public function actionStop()
	{
	     $this->module->mpd->Stop();
	     $data = array('current'=>$this->module->mpd->currentsong,'status'=>$this->module->mpd->status);
	     echo json_encode($data);
	}
	public function actionNext()
	{
	    $plLen = isset($this->module->mpd->status['playlistlength']) ? (int)$this->module->mpd->status['playlistlength'] : 0;
	    $curSong = isset($this->module->mpd->status['song']) ? $this->module->mpd->status['song'] : null;
	    if($plLen > 0 && $curSong){
		$this->module->mpd->Next();
	    }elseif($plLen > 0){
		$this->module->mpd->PlayPos(0);
	    }
	    $data = array('current'=>$this->module->mpd->currentsong,'status'=>$this->module->mpd->status);
	    
	    echo json_encode($data);
	}
	public function actionPrev()
	{
	    $plLen = isset($this->module->mpd->status['playlistlength']) ? (int)$this->module->mpd->status['playlistlength'] : 0;
	    $curSong = isset($this->module->mpd->status['song']) ? $this->module->mpd->status['song'] : null;
	    if($plLen > 0 && $curSong){
		$this->module->mpd->Previous();
	    }elseif($plLen > 0){
		$this->module->mpd->PlayPos(0);
	    }
	    $data = array('current'=>$this->module->mpd->currentsong,'status'=>$this->module->mpd->status);
	    echo json_encode($data);
	}
	
	
	public function actionSeek()
	{
	    if(isset($_POST['pos'])){
		$pos = (float)$_POST['pos'];
		$this->module->mpd->SeekTo($pos);
		$data = array('current'=>$this->module->mpd->currentsong,'status'=>$this->module->mpd->status);
		echo json_encode($data);
	    }
	    
	}
	
	
	public function actionStatus()
	{
	    if(isset($_POST['idle']) && $_POST['idle'] == "true") $this->module->mpd->NoIdle();
	    //$this->module->mpd->GetStatus();
	    $this->module->mpd->GetCurrentSong();
	    $data = array('current'=>$this->module->mpd->currentsong,'status'=>$this->module->mpd->status);
	    echo json_encode($data);
	}
	
	
	public function actionIdle()
	{
	    if(isset($_POST['action'])){
		
		if($_POST['action'] == 'true'){
		    $response = $this->module->mpd->Idle();
		}
		elseif($_POST['action'] == 'false'){
		    $response = $this->module->mpd->NoIdle();
		}
		echo json_encode($response);
	    }	    
	}
	
	
	public function actionChangeVolume()
	{
	    if(isset($_POST['vol'])){
		
		$response = $this->module->mpd->SetVolume((int)$_POST['vol']);
		echo json_encode($response);
	    }
	}
	
	
	public function actionSingle()
	{
	    if(isset($_POST['state'])){
		$this->module->mpd->SetSingle((int)$_POST['state']);
		$data = array('current'=>$this->module->mpd->currentsong,'status'=>$this->module->mpd->status);
		echo json_encode($data);
	    }
	}
	public function actionRepeat()
	{
	    if(isset($_POST['state'])){
		$r = $this->module->mpd->SetRepeat((int)$_POST['state']);
		$data = array('current'=>$this->module->mpd->currentsong,'status'=>$this->module->mpd->status,'r'=>$r);
		echo json_encode($data);
	    }
	}
	public function actionConsume()
	{
	    if(isset($_POST['state'])){
		$this->module->mpd->SetConsume((int)$_POST['state']);
		$data = array('current'=>$this->module->mpd->currentsong,'status'=>$this->module->mpd->status);
		echo json_encode($data);
	    }
	}
	public function actionRandom()
	{
	    if(isset($_POST['state'])){
		$this->module->mpd->SetRandom((int)$_POST['state']);
		$data = array('current'=>$this->module->mpd->currentsong,'status'=>$this->module->mpd->status);
		echo json_encode($data);
	    }
	}
	public function actionTest()
	{
	    $r = $this->module->mpd->SetRepeat("1");
	    var_dump($r);
	    
	    
	}
    
    
}
?>
