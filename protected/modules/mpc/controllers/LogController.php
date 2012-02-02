<?php

class LogController extends Controller
{
    public function actionIndex()
    {
	$logData = array();
	if (Yii::app()->user->hasState('log')){
	    $logData = Yii::app()->user->getState('log');	   
	}
	$this->render('log',array('data'=>$logData));
    }
    
    public function actionClear()
    {
	if (Yii::app()->user->hasState('log')){
	    Yii::app()->user->setState('log',array());	   
	}
	$this->render('log',array('data'=>array()));
    }
}
?>
