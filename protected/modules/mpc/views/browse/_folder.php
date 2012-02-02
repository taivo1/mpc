<ul>
	<?php if($data): foreach($data as $type => $items): ?>
	    
	    <?php foreach($items as $item): ?>

		<?php 
		    switch($type){
			case 'directories':
			    $class = 'directory closed';
			    $uri = $item;
			    $pathArray = array_reverse(explode('/', $item));
			    $name = $pathArray[0];
			    break;
			case 'playlists':
			    $class = 'playlist';
			    $uri = $item;
			    $pathArray = array_reverse(explode('/', $item));
			    $name = $pathArray[0];
			    break;
			default:
			    $class = 'file'; 
			    $uri = $item;
			    $name = $item;    
		    }
		?>
	    
		<li class="<?php echo $class; ?>">
		    
		    <a href="<?php echo $uri; ?>">
			<span><?php echo $name;?></span>
		    </a>
		</li>
	    
	    <?php endforeach; ?>
	    
	<?php endforeach; endif;?>
</ul>