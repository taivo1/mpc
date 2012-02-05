<?php 
    $id = ($mode == "artist") ? "artists" : "albums";
?>
<ul id="<?php echo $id; ?>">
    <?php $i = 0; ?>
    <?php if(isset($items) && !empty($items) && is_array($items)): foreach($items as $key => $item): ?>
	<?php 
	    
		$class = ($i % 2) ? 'odd' : 'even';
		$class .= ' '.$mode;
	    
	?>
	<li class="<?php echo $class; ?>"><a href="<?php echo urlencode($item);?>"><?php echo $item; ?></a></li>
	
    <?php $i++; endforeach; endif; ?>
</ul>	
