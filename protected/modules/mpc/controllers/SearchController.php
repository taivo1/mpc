<?php

class SearchController extends Controller
{
	public function actionIndex()
	{	    
	    $results = array();
	    $this->render('search',array('results'=>$results));
	}
	
	public function actionFind()
	{
	    if(isset($_POST['search'], $_POST['type']))
	    {
		$results = array('asdasda'=>'sdfsdfsd');
		
		$results = $this->module->mpd->Search($_POST['type'],$_POST['search']);
		
		$this->render('results',array('results'=>$results));
	    }
	}
	
}
?>
