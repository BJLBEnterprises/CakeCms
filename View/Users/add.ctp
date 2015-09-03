<?php
$pageTitle = __('Add User');
$this->Html->addCrumb(__('Users'), array('plugin' => 'cake_cms', 'controller' => 'users', 'action' => 'index'));
$this->Html->addCrumb($pageTitle);
?>
<div class="users form">
<?php echo $this->Form->create('User'); ?>
	<fieldset>
		<legend><?php echo __('Add User'); ?></legend>
	<?php
		echo $this->Form->input('User.username', array('type' => 'text', 'required' => true));
		echo $this->Form->input('User.password', array('label' => __('Password'), 'type' => 'password', 'required' => true));
		echo $this->Form->input('User.password_confirm', array('label' => __('Confirm Password'), 'type' => 'password', 'required' => true));
		echo $this->Form->input('User.first_name', array('type' => 'text', 'required' => true));
		echo $this->Form->input('User.last_name', array('type' => 'text', 'required' => true));
		echo $this->Form->input('User.email', array('type' => 'email', 'required' => true));
		echo $this->Form->input('User.role', array('type' => 'text'));
		echo $this->Form->input('User.group_id');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Users'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Groups'), array('controller' => 'groups', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Group'), array('controller' => 'groups', 'action' => 'add')); ?> </li>
	</ul>
</div>
