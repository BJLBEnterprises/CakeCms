<div class="finances form">
<?php echo $this->Form->create('Account'); ?>
	<fieldset>
		<legend><?php echo __('Edit Finance'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('name');
		echo $this->Form->input('alias');
		echo $this->Form->input('number');
		echo $this->Form->input('type');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('accounts.id')), null, __('Are you sure you want to delete # %s?', $this->Form->value('accounts.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Finances'), array('action' => 'index')); ?></li>
	</ul>
</div>
