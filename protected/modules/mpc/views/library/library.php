<div id="library">
    <div id="select-genre" class="ui-widget">
	<label for="genre">Genre: </label>
	<input id="genre" value="All"/>
	<div id="autocomplete-menu"></div>
<!--	<a href="#" id="show-genres">select</a>-->
    </div>
    <div id="library-type" class="col-btn section-menu">
	<a id="view-artists" style="display: none;" href="#"><span><?php echo Yii::t('library','Artists & albums');?></span></a>
	<a id="view-albums"  href="#"><span><?php echo Yii::t('library','Albums');?></span></a>
    </div>	
    <div class="col-left">
	<ul id="artists">
	    <?php if(isset($artists) && !empty($artists) && is_array($artists)): foreach($artists as $key => $artist): ?>
		<?php $class = ($key % 2) ? 'odd' : 'even';?>
		<li class="artist <?php echo $class; ?>"><a href="<?php echo urlencode($artist); ?>"><?php echo $artist; ?></a></li>

	    <?php endforeach; endif; ?>
	</ul>
	<div id="search-box-wrap">
	    <a href="#" id="search-box-handle"><?php echo Yii::t('library','S'); ?></a>
	    <div id="search-box" style="display: none;">
		<input type="text" id="library-search" placeholder="<?php echo Yii::t('library','Artist/Album name'); ?>"/>
	    </div>
	</div>    
    </div>
    <div class="col-right">
	<ul id="songs">
	    
	</ul>
	<div id="album-info-wrap">
	    <a href="#" id="album-info-handle"><?php echo Yii::t('library','Album info'); ?></a>
	    <div id="album-info" style="display: none;">
		
	    </div>
	</div>
    </div>
    <div class="clear"></div>
</div>