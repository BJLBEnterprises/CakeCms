<?php
$this->Html->css('CakeCms.cakeCms', array('inline' => false, 'block' => 'css'));
$this->Html->script('CakeCms.cakeCms_password_strength', array('inline' => false, 'block' => 'script_bottom'));
?>
<div class="users form no-border no-float center text-block">
<?php echo $this->Form->create('User'); ?>
	<fieldset>
		<legend><?php echo __('Sign Up'); ?></legend>
		<?php
		echo $this->Form->input('username', array('type' => 'text', 'value' => $this->data['User']['username']));
		echo $this->Form->input('password', array('label' => __('Password'), 'value' => ''));
		echo $this->Form->input('password_confirm', array('label' => __('Confirm Password'), 'type' => 'password', 'value' => ''));
		echo $this->Form->input('first_name', array('type' => 'text', 'value' => $this->data['User']['first_name']));
		echo $this->Form->input('last_name', array('type' => 'text', 'value' => $this->data['User']['last_name']));
		echo $this->Form->input('email', array('value' => $this->data['User']['email']));
		?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
