<div id="library">
    <div id="genre">
	<select name="genre" id="genre">
	    <option value="all"><?php echo Yii::t('library','All Genres'); ?></option>
	    <?php if(isset($genres) && !empty($genres) && is_Array($genres)): foreach($genres as $key => $genre): ?>
		<option value="<?php echo $genre; ?>"><?php echo $genre; ?></option>
	    <?php endforeach; endif; ?>
	</select>
    </div>
    <div class="col-left">
	<ul id="artists">
	    <?php if(isset($artists) && !empty($artists) && is_array($artists)): foreach($artists as $key => $artist): ?>

		<li class="artist"><a href="<?php echo urlencode($artist); ?>"><?php echo $artist; ?></a></li>

	    <?php endforeach; endif; ?>
	</ul>	
    </div>
    <div class="col-center"></div>
    <div class="col-right"></div>
    <div class="clear"></div>
</div>