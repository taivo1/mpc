<ul id="albums">
    <?php if(isset($albums) && !empty($albums) && is_array($albums)): foreach($albums as $key => $item): ?>
	<?php $album = ($item != "") ? $item : 'No album defined'; ?>
	<li class="album"><a href="#"><?php echo $album; ?></a></li>
	
    <?php endforeach; else: ?>
	<li class="album"><a href="#"><?php echo 'No album defined'; ?></a></li>
    <?php endif; ?>
	
</ul>
