<?php

class LibraryController extends Controller
{
    public function actionIndex()
    {
	$genres = $this->module->mpd->GetGenres();
	$artists = $this->module->mpd->GetArtists();
	$this->render('library',array('artists'=>$artists, 'genres'=>$genres));
    }
    
    public function actionArtists()
    {
	if(isset($_POST['genre'])){
	    $genre = ($_POST['genre'] != "all") ? $_POST['genre'] : null;
	    if($genre){
		$response = $this->module->mpd->Search(mpd::MPD_SEARCH_GENRE, $genre);
		$artists = array();
		$arr = array();
		if(isset($response['files'])){
		    foreach($response['files'] as $item){
			if(isset($item["Artist"])) $arr[] = $item["Artist"];
		    }
		}
		$artists = array_unique($arr);
	    }else{
		$artists = $this->module->mpd->GetArtists();
	    }
	    $this->render('artists',array('artists'=>$artists));
	}
    }
    
    public function actionAlbums()
    {
	if(isset($_POST['artist'])){
	    
	    $albums = $this->module->mpd->GetAlbums($_POST['artist']);
	    $this->render('albums',array('albums'=>$albums));
	}
    }
    
    
    public function actionFind()
    {
	if(isset($_POST['album'])){
	    
	    $songs = $this->module->mpd->Search(mpd::MPD_SEARCH_ALBUM, $_POST['album']);
	    $this->render('album',array('songs'=>$songs));
	
	    
	}else if(isset($_POST['artist'])){
	    
	    $songs = $this->module->mpd->Search(mpd::MPD_SEARCH_ARTIST, $_POST['artist']);
	    $this->render('album',array('songs'=>$songs));
	    
	}
    }
    
}
?>
