<ul class="albums">
    <li class="album first"><a class="all" href="#"><?php echo Yii::t('library','All'); ?></a></li>
    <?php if(isset($albums) && !empty($albums) && is_array($albums)): foreach($albums as $key => $item): ?>
	    <?php if($item != ""): ?>
		<li class="album"><a href="<?php echo urlencode($item); ?>"><?php echo $item; ?></a></li>
	    <?php endif; ?>
    <?php endforeach; else: ?>
<!--	<li class="album"><a href="#"><?php //echo 'No album defined'; ?></a></li>-->
    <?php endif; ?>
	
</ul>
