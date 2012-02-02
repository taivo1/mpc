<ul id="songs">
    <?php 
	
	if(isset($songs) && !empty($songs) && is_array($songs)){
	    
	    foreach($songs as $index => $item){ 
		
		if(!empty($item) && is_array($item)){ 
		    
		    foreach($item as $song){
			
			if(!empty($song) && is_array($song)){
			   
			$fileArray = array_reverse(explode('/', $song['file']));
			$title = isset($song['Title']) ? $song['Title'] : $fileArray[0];		    
			?>
			    <li class="file">
				<a href="<?php echo $song['file']; ?>"><?php echo $title; ?>
				    <span class="addnext"></span>
				    <span class="play"></span>
				</a>
			    </li>
			<?php  
			}
		    } 
		}
	    } 
	} 
     
	?>
</ul>
