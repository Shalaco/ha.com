<div class="row-fluid">
	<div class="span6 give-me-space twoup" style="">
		<h5 class="cyan uppercase underline-grey"><?php echo do_shortcode(types_render_field("take-action-head", array('raw' =>
		 'true', 'output' => 'html'))); ?> </h5>
		<div class="span6 no-margin">
			<a href="<?php echo types_render_field(" quiz-cta1-link", array('raw'> 'true', 'output' => 'html')); ?>"><button class="btn unique crimson large-icon-download" type="button" style=""><span class="pull-right "><?php echo do_shortcode(types_render_field("quiz-cta1-copy", array('raw' =>
			 'true', 'output' => 'html'))); ?></span></button></a>
		</div>
		<div class="span6">
			<a href="<?php echo types_render_field(" quiz-cta2-link", array('raw'> 'true', 'output' => 'html')); ?>"><button class="btn unique crimson large-icon-phone" type="button" style=""><span class="pull-right "><?php echo do_shortcode(types_render_field("quiz-cta2-copy", array('raw' =>
			 'true', 'output' => 'html'))); ?></span></button></a>
		</div>
	</div>
	<div class="span6 give-me-space twoup" style="">
		<h5 class="cyan uppercase underline-grey"><?php echo(types_render_field("quiz-related-articles-head", array('raw' =>
		 'true', 'output' => 'html'))); ?></h5>
		<ul class="arrows">
			<?php if (types_render_field("quiz-link1", array())) : ?>
			<li><a href="<?php echo(types_render_field(" quiz-link1-url", array('raw'> 'true', 'output' => 'html'))); ?>"><?php echo(types_render_field("quiz-link1", array('raw' =>
			 'true', 'output' => 'html'))); ?></a></li>
			<?php endif; ?>
			<?php if (types_render_field("quiz-link2", array())) : ?>
			<li><a href="<?php echo(types_render_field(" quiz-link2-url", array('raw'> 'true', 'output' => 'html'))); ?>"><?php echo(types_render_field("quiz-link2", array('raw' =>
			 'true', 'output' => 'html'))); ?></a></li>
			<?php endif; ?>
			<?php if (types_render_field("quiz-link3", array())) : ?>
			<li><a href="<?php echo(types_render_field(" quiz-link3-url", array('raw'> 'true', 'output' => 'html'))); ?>"><?php echo(types_render_field("quiz-link3", array('raw' =>
			 'true', 'output' => 'html'))); ?></a></li>
			<?php endif; ?>
			<?php if (types_render_field("quiz-link4", array())) : ?>
			<li><a href="<?php echo(types_render_field(" quiz-link4-url", array('raw'> 'true', 'output' => 'html'))); ?>"><?php echo(types_render_field("quiz-link4", array('raw' =>
			 'true', 'output' => 'html'))); ?></a></li>
			<?php endif; ?>
		</ul>
		<div class="clearfix">
		</div>
	</div>
</div>