<ul id="artists">
    <?php $i = 0; ?>
    <?php if(isset($artists) && !empty($artists) && is_array($artists)): foreach($artists as $key => $artist): ?>
	<?php 
	    
		$class = ($i % 2) ? 'odd' : 'even';
	    
	?>
	<li class="artist <?php echo $class; ?>"><a href="<?php echo urlencode($artist);?>"><?php echo $artist; ?></a></li>
	
    <?php $i++; endforeach; endif; ?>
</ul>	
