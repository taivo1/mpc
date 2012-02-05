<?php 

    //Yii::app()->clientScript->registerCoreScript('jquery');
    $urlScript = Yii::app()->assetManager->publish(
	    Yii::getPathOfAlias('application.modules.mpc.js'),
	    true,
	    -1,
	    YII_DEBUG
    );
    $urlCss = Yii::app()->assetManager->publish(
	    Yii::getPathOfAlias('application.modules.mpc.css'),
	    true,
	    -1,
	    YII_DEBUG
    );

    Yii::app()->clientScript->registerScriptFile($urlScript.'/mpc.js', CClientScript::POS_HEAD);
    Yii::app()->clientScript->registerCssFile($urlCss.'/mpc.css');
    

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />
	<script type="text/javascript">
	    var mainUrl = "<?php echo Yii::app()->request->baseUrl; ?>";
	</script>
	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.js"></script>
	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.sparkle.min.js"></script>
	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.colResize.js"></script>
	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jqueryui/jquery.ui.core.min.js"></script>
	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jqueryui/jquery.ui.widget.min.js"></script>
	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jqueryui/jquery.ui.mouse.min.js"></script>
	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jqueryui/jquery.ui.slider.min.js"></script>
	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jqueryui/jquery.ui.sortable.min.js"></script>
	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jqueryui/jquery.ui.draggable.min.js"></script>
	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jqueryui/jquery.ui.resizable.min.js"></script>
	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jqueryui/jquery.ui.autocomplete.min.js"></script>
	
	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/slider.js"></script>
	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/ajax.js"></script>
	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/base.js"></script>
	
	<script type="text/javascript">
	    
	    $(document).ready(function(){
		
		Base.init();
		Mpc.init();
		
		// if base url we redirect to playlist 
		if(Ajax.hash.getHash() == '/') Ajax.hash.changeHash('/playlist');
		
		// Since the event is only triggered when the hash changes, we need to trigger
		// the event now, to handle the hash the page may have loaded with.
		$(window).trigger( 'hashchange' );
		
	    });
	    	    
	</script>
	<!-- blueprint CSS framework -->
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css" media="screen, projection" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css" media="print" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/volslider.css" media="all" />
	<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection" />
	<![endif]-->

	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />
	<!--[if IE 7]>
	    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/mpc-ie.css" />
	<![endif]-->
	
	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>

<body>

<div class="container" id="page">
    <div id="top">
    
	<div id="header">
		<div id="logo"><span><?php //echo CHtml::encode(Yii::app()->name); ?>&#222; &#186; &#186; &#186; &#186; &#186;</span></div>
		<div id="player">
		    <?php 
			$mpcModule = Yii::app()->getModule('mpc');
			$this->renderPartial('application.modules.mpc.views.player.control',array('mpd'=>$mpcModule->mpd));
		    ?>
		</div>
		<div class="clear"></div>
	</div><!-- header -->
<!--	<script type="text/javascript" src="<?php //echo Yii::app()->request->baseUrl; ?>/js/harmony.js?wdollarmergedin"></script>-->
	<div id="mainmenu">
	     <div class="menubar-left">	
		<?php $this->widget('zii.widgets.CMenu',array(
			'linkLabelWrapper'=>'span',
			'htmlOptions'=>array('id'=>'topmenu'),
			'items'=>array(
				
				array(
				    'label'=>'Playlist',
				    'url'=>Yii::app()->request->baseUrl.'/playlist',
				    'linkOptions'=>array('class'=>'ajax'),
				    'itemOptions'=>array('id'=>'menu-item-playlist')
				    ),
				array(
				    'label'=>'Browse',
				    'url'=>Yii::app()->request->baseUrl.'/browse',
				    'linkOptions'=>array('class'=>'ajax'),
				    'itemOptions'=>array('id'=>'menu-item-browse')
				    ),
				array(
				    'label'=>'Library',
				    'url'=>Yii::app()->request->baseUrl.'/library',
				    'linkOptions'=>array('class'=>'ajax'),
				    'itemOptions'=>array('id'=>'menu-item-library')
				    ),
				array(
				    'label'=>'Search',
				    'url'=>Yii::app()->request->baseUrl.'/search',
				    'linkOptions'=>array('class'=>'ajax'),
				    'itemOptions'=>array('id'=>'menu-item-search')
				    ),
//				array('label'=>'About', 'url'=>array('/site/page', 'view'=>'about')),
//				array('label'=>'Contact', 'url'=>array('/site/contact')),
//				array('label'=>'Login', 'url'=>array('/site/login'), 'visible'=>Yii::app()->user->isGuest),
//				array('label'=>'Logout ('.Yii::app()->user->name.')', 'url'=>array('/site/logout'), 'visible'=>!Yii::app()->user->isGuest)
			),
		)); ?>
	    </div>
	    <div class="menubar-right">	
		<div id="vol-value">
		    <?php if(isset($mpcModule->mpd->status['volume'])) echo $mpcModule->mpd->status['volume'];?>
		</div>
		<div id="volbar"></div>
		<div class="status-item"><a id="update-status" class="disabled" title="Database Update" href="<?php echo Yii::app()->request->baseUrl.'/mpc/player/u'; ?>"><span>U</span></a></div>
		<div class="status-item"><a id="single-status" title="Single Mode" href="<?php echo Yii::app()->request->baseUrl.'/mpc/player/single'; ?>"><span>S</span></a></div>
		<div class="status-item"><a id="repeat-status" title="Repeat Mode" href="<?php echo Yii::app()->request->baseUrl.'/mpc/player/repeat'; ?>"><span>R</span></a></div>
		<div class="status-item"><a id="consume-status" title="Consume Mode" href="<?php echo Yii::app()->request->baseUrl.'/mpc/player/consume'; ?>"><span>C</span></a></div>
		<div class="status-item"><a id="random-status" title="Random Mode" href="<?php echo Yii::app()->request->baseUrl.'/mpc/player/random'; ?>"><span>Rnd</span></a></div>
		<div class="clear"></div>
	    </div>
	    <div class="clear"></div>
	</div><!-- mainmenu -->
	<?php if(isset($this->breadcrumbs)):?>
		<?php $this->widget('zii.widgets.CBreadcrumbs', array(
			'links'=>$this->breadcrumbs,
		)); ?><!-- breadcrumbs -->
	<?php endif?>
    </div>
  
    <div id="content">
	<?php echo $content; ?>
    </div>
   
    <div class="clear"></div>

    <div id="footer">
	<?php if($mpcModule->debug): ?>
	<a href="#" id="show-log"><?php echo Yii::t('general','Show log entries'); ?></a>
	<a href="/mpc/log/clear" id="clear-log"><?php echo Yii::t('general','Clear log'); ?></a>
	<div id="log">
	    <?php
		$logData = array();
		if (Yii::app()->user->hasState('log')){
		    $logData = Yii::app()->user->getState('log');
		}    
		$this->renderPartial('application.modules.mpc.views.log.log',array('data'=>$logData));
	    ?>
	</div>
	<?php endif; ?>	
    </div><!-- footer -->

</div><!-- page -->    
</body>
</html>
