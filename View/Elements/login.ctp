<?php echo $this->Form->create('User', array('action' => 'login'))?>
	<fieldset>
		<legend>Login</legend>
		<?php echo $this->Form->input('username', array('type' => 'text'))?>
		<?php echo $this->Form->input('password', array('type' => 'password'))?>
		<?php echo $this->Form->submit('Login')?>
		<div class="center">
			<?php if (Configure::read('CakeCms.sign_up_allowed')):?>
			<?php echo $this->Html->link(__('Sign Up'), array('plugin' => 'cake_cms', 'controller' => 'users', 'action' => 'sign_up'))?>
			|
			<?php endif?>
			<?php echo $this->Html->link(__('Reset Password'), array('plugin' => 'cake_cms', 'controller' => 'users', 'action' => 'reset_password'))?>
			|
			<?php echo $this->Html->link(__('Recover Username'), array('plugin' => 'cake_cms', 'controller' => 'users', 'action' => 'recover_username'))?>
		</div>
	</fieldset>
<?php echo $this->Form->end()?>
