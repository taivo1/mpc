
<?php if($data): ?>
	<ul class="folder">
	
	<?php foreach($data as $type => $items): ?>
	    
	    <?php foreach($items as $key => $item): ?>
		
		<?php 
		    switch($type){
			case 'directories':
			    $class = 'directory closed';
			    $uri = $item;
			    $pathArray = array_reverse(explode('/', $item));
			    $name = $pathArray[0];			    
			    $name .= (count($pathArray) > 1) ? '<span class="addfolder"></span>' : '';
			    $name .= '<span class="update" style="display: none;"></span>';
			    $name .= '<div class="clear"></div>';
			    break;
			case 'playlists':
			    $class = 'playlist';
			    $uri = $item;
			    $pathArray = array_reverse(explode('/', $item));
			    $name = $pathArray[0];
			    $name .= (count($pathArray) == 1) ? '<span class="delete"></span>' : '';
			    //$name .= '<span class="addnext"></span>';
			    $name .= '<span class="play"></span>';
			    $name .= '<div class="clear"></div>';
			    break;
			default:
			    $class = 'file'; 
			    $uri = $item['file'];
			    $pathArray = array_reverse(explode('/', $item['file']));
			    $name = '<span class="name">';
			    $name .= isset($item['Artist']) ? $item['Artist'].' - ' : '';
			    $name .= isset($item['Album']) ? $item['Album'].' - ' : '';
			    $name .= isset($item['Title']) ? (($item['Title'] == $item['file']) ? $pathArray[0] : $item['Title']) : $pathArray[0];
			    $name .= '</span>';
			    $name .= '<span class="addnext"></span>';
			    $name .= '<span class="play"></span>';
			    $name .= isset($item['Date']) ? '<span class="meta">'.$item['Date'].'</span>' : '';
			    $name .= isset($item['Genre']) ? '<span class="meta">'.$item['Genre'].'</span>' : '';			    
			    $name .= '<div class="clear"></div>';
			    
			    //$name = print_r($item,true); 
		    }
		?>
	    
		<li class="<?php echo $class; ?>">
		    
		    <a href="<?php echo urlencode($uri); ?>">
			<div><span><?php echo $name;?></span></div>
		    </a>
		</li>
	    
	    <?php endforeach; ?>
	    
	<?php endforeach;?>
	</ul>
<?php endif; ?>