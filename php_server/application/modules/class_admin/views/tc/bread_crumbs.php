<ul class="breadcrumb">
	<?php for ( $i=1; $i<=$info[0]->stage;$i++ ) : ?>
		
		<?php if ( $i <= 6) : ?>
			
			<?php if ( $page != $i) : ?>
				<li><?php echo anchor("class_admin/tc/stage$i/".$info[0]->class_id.'/'.$info[0]->session_id.'/'.$info[0]->prog_name , "Step $i") ?>  </li>
			<?php else: ?>
				<li class="active">Step <?php echo $i?>  </li>
			<?php endif; ?>
			
		<?php else: if($i == 7) : ?>
				<?php if ( $page != $i) : ?>
				<li><?php echo anchor("class_admin/tc/review/".$info[0]->class_id.'/'.$info[0]->session_id.'/'.$info[0]->prog_name , "Review") ?> </li>
				<?php else: ?>
					<li class="active">Review</li>
				<?php endif; ?>
			<?php endif ?>
		<?php endif ?>	
			
	<?php endfor;?>
</ul>