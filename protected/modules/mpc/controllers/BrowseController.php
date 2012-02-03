<?php

class BrowseController extends Controller
{
	public function actionIndex()
	{
	    if(isset($_POST['uri'])){
		$data = $this->module->mpd->GetDir($_POST['uri']);
		$this->render('browse',array('data'=>$data));
		Yii::app()->end();
	    }
	    $this->render('browse',array('data'=>$this->module->mpd->GetDir()));
	}
	
	public function actionAddToPlaylist()
	{
	    if(isset($_POST['uri'])){

		$this->module->mpd->PLAdd($_POST['uri']);
		$data = array('current'=>$this->module->mpd->currentsong,'status'=>$this->module->mpd->status);
		echo json_encode($data);
	    }
	}
	
	public function actionAddPlToPlaylist()
	{
	    if(isset($_POST['uri'])){
		
		$this->module->mpd->PLLoad($_POST['uri']);
		$data = array('current'=>$this->module->mpd->currentsong,'status'=>$this->module->mpd->status);
		echo json_encode($data);
	    }
	}
	
	public function actionPlay()
	{
	    if(isset($_POST['uri'],$_POST['type'])){
		
		$plLen = isset($this->module->mpd->status['playlistlength']) ? (int)$this->module->mpd->status['playlistlength'] : NULL;
		if($_POST['type'] == 'file'){
		    
		    $id = $this->module->mpd->PLAddTrack($_POST['uri']);
		    if($plLen > 0){
			$this->module->mpd->PLMoveTrack($id, -1);
			$this->module->mpd->Next();
		    }else{
			$this->module->mpd->Play();
		    }
		    
		    
		}else if($_POST['type'] == 'directory'){
		    $this->module->mpd->PLAdd($_POST['uri']);
		    $this->module->mpd->PlayPos($plLen);
		}else{
		    $this->module->mpd->PLLoad($_POST['uri']);
		    $this->module->mpd->PlayPos($plLen);
		}
		$data = array('current'=>$this->module->mpd->currentsong,'status'=>$this->module->mpd->status);
		echo json_encode($data);
	    }
	}
	
	public function actionAddNext()
	{
	    if(isset($_POST['uri'],$_POST['type'])){
		
		if($_POST['type'] == 'file'){
		    $id = $this->module->mpd->PLAddTrack($_POST['uri']);
		    
		    if(isset($this->module->mpd->status['playlistlength']) && (int)$this->module->mpd->status['playlistlength'] > 0){
			$this->module->mpd->PLMoveTrack($id, -1);
		    }
		}
		$data = array('current'=>$this->module->mpd->currentsong,'status'=>$this->module->mpd->status);
		echo json_encode($data);
	    }
	}
	
	public function actionUpdate()
	{
	    $id = 0;
	    if(isset($_POST['uri'])){		
		$id = $this->module->mpd->DBUpdate($_POST['uri']);
		
	    }else{
		$id = $this->module->mpd->DBUpdate();
	    }
	    $data = array('current'=>$this->module->mpd->currentsong,'status'=>$this->module->mpd->status, 'id'=>$id);
	    echo json_encode($data);
	}
	
	public function actionDeletePlaylist()
	{
	    if(isset($_POST['uri'])){
		$data = $this->module->mpd->PLRemove($_POST['uri']);
		echo json_encode($data);
		//$this->render('browse',array('data'=>$this->module->mpd->GetDir()));
	    }
	}
}
?>
