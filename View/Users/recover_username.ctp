<?php
$this->Html->css('CakeCms.cakeCms', array('block' => 'css'));
?>
<div class="users form no-border no-float center text-block">
<?php echo $this->Form->create('User'); ?>
	<fieldset>
		<legend><?php echo __('Recover Username'); ?></legend>
		<?php echo $this->Form->input('email', array('type' => 'email')); ?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>