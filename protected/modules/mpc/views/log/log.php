<?php if(!empty($data)): ?>

    <?php foreach($data as $block): ?>
	<div class="log-block">
	    <?php foreach($block as $key => $value): ?>

		<p class="<?php echo $value['type']; ?>">
		    <span class="entry-nr"><?php echo $key; ?></span>
		    <span class="type"><?php echo $value['type']; ?></span>
		    <span class="method"><?php echo $value['method']; ?></span>
		    <span class="message"><?php echo $value['message']; ?></span>
		</p>

	    <?php endforeach; ?>
	</div>    
    <?php endforeach; ?>   


<?php endif; ?>


