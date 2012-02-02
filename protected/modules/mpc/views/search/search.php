<div id="search">
    
    <form id="searchform">
	<div>
	    <input type="text" name="search" id="search" placeholder="<?php echo Yii::t('search','Type your keyword here!'); ?>"/>
	    <label for="keyword-type"><?php echo Yii::t('search','Type:'); ?></label>
	    <select id="keyword-type" name="type">
		<option value="<?php echo mpd::MPD_SEARCH_ANY; ?>"><?php echo Yii::t('search','All'); ?></option>
		<option value="<?php echo mpd::MPD_SEARCH_TITLE; ?>"><?php echo Yii::t('search','Title'); ?></option>
		<option value="<?php echo mpd::MPD_SEARCH_ARTIST; ?>"><?php echo Yii::t('search','Artist'); ?></option>
		<option value="<?php echo mpd::MPD_SEARCH_ALBUM; ?>"><?php echo Yii::t('search','Album'); ?></option>
		<option value="<?php echo mpd::MPD_SEARCH_GENRE; ?>"><?php echo Yii::t('search','Genre'); ?></option>
		<option value="tag"><?php echo Yii::t('search','Tag'); ?></option>
		<option value="rating"><?php echo Yii::t('search','Rating'); ?></option>

	    </select>    
	    <input type="submit" name="submit" value="<?php echo Yii::t('search','Search'); ?>" />
	</div>
    </form>
    <div class="placeholder"></div>
    <div id="search-results"></div>	
    
</div>
