<?php
$this->Html->css('CakeCms.cakeCms', array('block' => 'css'));
?>
<div class="users form no-border no-float center text-block">
<?php echo $this->Form->create('User'); ?>
	<fieldset>
		<legend><?php echo __('Reset Password'); ?></legend>
		<?php
		echo $this->Form->input('username', array('type' => 'text', 'value' => $this->data['User']['username']));
		?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
