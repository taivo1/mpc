<?php 

$this->pageTitle=Yii::app()->name;
$this->breadcrumbs=array('Playlist');

?>
<div id="playlist-container">
<ul class="list" id="playlist">
	
    <li class="playlist-head">
	
	 
		<div class="nr resize col"><span><?php echo Yii::t('playlist','No'); ?></span></div>
		<div class="title resize col"><span><?php echo Yii::t('playlist','Name'); ?></span></div>
		<div class="artist resize col"><span><?php echo Yii::t('playlist','Artist'); ?></span></div>
		<div class="date resize col"><span><?php echo Yii::t('playlist','Date'); ?></span></div>
		<div class="genre resize col"><span><?php echo Yii::t('playlist','Genre'); ?></span></div>
		<div class="time resize col"><span><?php echo Yii::t('playlist','Length'); ?></span></div>
		<div class="action col"></div>
	
    </li>
	
	
	<?php if($mpd->playlist): foreach($mpd->playlist as $key => $item): ?>
	    
	    <?php
	    
		$stream = isset($item['Name']) ? (is_string(filter_var($item['file'], FILTER_VALIDATE_URL))) : FALSE;
		$showAction = true;
		$class = 'playlist-item sortable ui-state-default ';
		$class .= (($key + 1) % 2) ? 'even ' : 'odd ';
		$title = '';
		$artist = '';
		$current = false;
		
		if(isset($mpd->status['song']) && $mpd->status['song'] == $key){
		    $class .= 'current';
		    $current = true;
		    $showAction = false;
		}
		if($stream) $class .= ' stream-item';
	    ?>
	    
	    <li id="song-<?php echo $item['Id']; ?>" class="<?php echo $class; ?>">
		<div class="nr col"><?php echo $key + 1; ?></div>
		<div class="title resize col">
				<p>
				    <?php
					if($item['file'] == $item['Title'] && !$stream){
					    $pathArray = array_reverse(explode('/', $item['file']));
					    $title = $pathArray[0];
					}elseif($stream){
					    $title = (isset($mpd->currentsong['Title']) && $current) ? $mpd->currentsong['Title'] : $item['Title']; 
					}else $title = $item['Title'];

					echo $title;
				    ?>
				</p>
			
		</div>
		<div class="artist resize col">
				<p>
				    <?php 
					if(!$stream){
					    $artist = $item['Artist'];
					}else{
					    $artist = isset($item['Name']) ? $item['Name'] : '';
					}
					echo $artist;
				    ?>
				</p>
			
		</div>
		<div class="date resize col">
				<p>
				    <?php 
					$date = '';
					if(isset($item['Date'])){
					    $date = $item['Date'];
					    echo substr($date, 0, 4); 
					}
				    ?>
				</p>
			
		</div>
		<div class="genre resize col">
				<p>
				    <?php echo $item['Genre']; ?>
				</p>
			
		</div>
		<div class="time col">
				<p>
				    <?php if(isset($item['Time'])) echo General::calculateTime($item['Time']); ?>
				</p>
		</div>
		<div class="action col">
				<span>
				    <a <?php if(!$showAction) echo 'style="display: none;"';?> href="#" class="remove"></a>
				</span>
		</div>
		<div class="clear"></div>
		
		
		
		<div class="track-details" <?php if($current) echo 'style="display: block;"'; ?>>
		    <div class="cover-art"></div>
		    <div class="metainfo">
			<table class="song-meta">

			    <tr>
				<td><?php echo Yii::t('playlist','Album:');?></td><td><?php if(isset($item['Album'])) echo $item['Album']; ?></td>
				<td>Rating:</td>
			    </tr>
			    <tr><td><?php echo Yii::t('playlist','Label:');?></td><td colspan="2">lkjdlajsldk</td></tr>
			    <tr><td><?php echo Yii::t('playlist','Tags:');?></td><td></td><td><a class="add-tag">Add a tag</a></td></tr>
			    <tr style="display: none;">
				<td><input type="text" name="tag-field" value="" placeholder="<?php echo Yii::t('playlist','Enter a new tag');?>"/></td>
				<td><a class="save-tag"><?php echo Yii::t('playlist','Save');?></a></td>
			    </tr>
			</table>
			<p class="description">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</p>
		    </div>
		    <div class="clear"></div>
		</div>
	    </li>


	<?php endforeach; else: ?>
<!-- In case we have empty playlist  -->
	    <li class="playlist-message">
		<h3><?php //echo Yii::t('playlist','Playlist empty'); ?></h3>
	    </li>
	    
	<?php endif; ?>
	    
</ul>
    <div id="playlist-actions">
	<?php $class = ($mpd->playlist) ? '' : 'disabled';?>
	<div class="row">
	    <a href="#" class="clear <?php echo $class; ?>"><?php echo Yii::t('playlist','Clear Playlist'); ?></a>
	    <a href="#" class="shuffle <?php echo $class; ?>"><?php echo Yii::t('playlist','Shuffle Playlist'); ?></a>
	</div>
	<div id="save-playlist">
	    
	    <input type="text" id="playlist-name" placeholder="<?php echo Yii::t('playlist','Type your playlist name here'); ?>" name="playlist-name" value=""/>
	    
	    <span class="or"><?php echo Yii::t('playlist','or'); ?></span>
	    <input type="text" style="display: none;" id="stream-url" placeholder="<?php echo Yii::t('playlist','Type your streaming URL here'); ?>" name="stream-url" value=""/>
	    <a href="#" class="stream"><?php echo Yii::t('playlist','Add stream URL to playlist'); ?></a>
	    <a href="#" class="cancel" style="display: none;"><?php echo Yii::t('playlist','Cancel'); ?></a>
	    
	    <a href="#" class="save"><?php echo Yii::t('playlist','Save Playlist'); ?></a>
	    <div class="spinner-container">
		<div class="spinner">
		    <img src="<?php echo Yii::app()->request->baseUrl; ?>/images/ajax-loader.gif"/>
		</div>
	    </div>
	    <div class="clear"></div>
	</div>
    </div>
<!--    <pre>
	//<?php
//	    if(!empty($this->mpd->log)){
//		foreach($this->mpd->log as $item){
//		    echo $item['type'].': '.$item['message'].'<br />';
//		}
//	    }
//	?>
    </pre>-->
</div>	