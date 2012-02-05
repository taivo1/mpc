<ul id="artists">
    <?php if(isset($artists) && !empty($artists) && is_array($artists)): foreach($artists as $key => $artist): ?>

	<li class="artist"><a href="<?php echo urlencode($artist);?>"><?php echo $artist; ?></a></li>

    <?php endforeach; endif; ?>
</ul>	
