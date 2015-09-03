<?php
$this->Html->css('CakeCms.cakeCms', array('block' => 'css'));
$this->Html->script('CakeCms.cakeCms_password_strength', array('inline' => false, 'block' => 'script_bottom'));
?>
<div class="users form no-border no-float center text-block">
<?php echo $this->Form->create('User'); ?>
	<fieldset>
		<legend><?php echo __('Reset Password'); ?></legend>
		<?php
		echo $this->Form->hidden('key', array('value' => $key));
		echo $this->Form->input('password', array('label' => __('Password')));
		echo $this->Form->input('password_confirm', array('label' => __('Confirm Password'), 'type' => 'password'));
		?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
