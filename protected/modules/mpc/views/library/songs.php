

    <?php 
	
	if(isset($songs) && !empty($songs) && is_array($songs)){
	    $i = 0;
	    foreach($songs as $index => $song){ 
			
		if(!empty($song) && is_array($song)){

		    $fileArray = array_reverse(explode('/', $song['file']));
		    $title = isset($song['Title']) ? $song['Title'] : $fileArray[0];
		    $class = ($i % 2) ? 'odd' : 'even';			
		    ?>
			<li class="file <?php echo $class; ?>" data-type="file">
			    <a href="<?php echo urlencode($song['file']); ?>" class="disabled"><?php echo $title; ?>
				<span class="addnext"></span>
				<span class="play"></span>
			    </a>
			</li>
		    <?php  
		}
		$i++;
	    } 
	} 
     
	?>
