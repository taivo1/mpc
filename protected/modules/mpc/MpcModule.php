<?php

class MpcModule extends CWebModule
{
	public $mpd = null;
	public $debug;
	public $ip;
	public $port;
	public $password = null;
	
	public function init()
	{
		// this method is called when the module is being created
		// you may place code here to customize the module or the application
		
		$this->defaultController = 'playlist';
		
		// import the module-level models and components
		$this->setImport(array(
			'mpc.models.*',
			'mpc.components.*',
		));
		$this->mpd = new mpd($this->ip, $this->port, $this->password, $this->debug);
	}

	public function beforeControllerAction($controller, $action)
	{
		if(parent::beforeControllerAction($controller, $action))
		{
			// this method is called before any module controller action is performed
			// you may place customized code here
			
			
			
		    
			return true;
		}
		else
			return false;
	}
	
	public function afterControllerAction($controller, $action)
	{
	    if($this->debug){
		if($controller->id != "log"){

		    $log = $this->mpd->log;

		    if (Yii::app()->user->hasState('log')){
			$logEntries = Yii::app()->user->getState('log');
			array_push($logEntries,$log);
			Yii::app()->user->setState('log',$logEntries);
		    }else{
			Yii::app()->user->setState('log',array($log));
		    }

		}
	    }
	    
	}
}
