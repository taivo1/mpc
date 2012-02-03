<?php



?>
<a href="<?php echo Yii::app()->request->baseUrl; ?>/mpc/player/pause" id="pause" class="player"><span>Pause</span></a>
<a href="<?php echo Yii::app()->request->baseUrl; ?>/mpc/player/play" id="play" class="player"><span>Play</span></a>
<a href="<?php echo Yii::app()->request->baseUrl; ?>/mpc/player/stop" id="stop" class="player"><span>Stop</span></a>
<a href="<?php echo Yii::app()->request->baseUrl; ?>/mpc/player/prev" id="prev" class="player"><span>Prev</span></a>
<a href="<?php echo Yii::app()->request->baseUrl; ?>/mpc/player/next" id="next" class="player"><span>Next</span></a>

<div id="cursong"></div>
<div class="clear"></div>
<div id="slide">
    <div id="pos"></div>
</div>
<!--  <a id="idle">idle</a>  -->
    
