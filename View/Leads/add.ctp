<?php
$pageTitle = __('Add Lead');
$this->Html->addCrumb(__('Leads'), array('plugin' => 'cake_cms', 'controller' => 'leads', 'action' => 'index'));
$this->Html->addCrumb($pageTitle);
?>
<div class="leads form">
<?php echo $this->Form->create('Lead'); ?>
	<fieldset>
		<legend><?php echo __('Add Lead'); ?></legend>
	<?php
		echo $this->Form->input('first_name');
		echo $this->Form->input('last_name');
		echo $this->Form->input('email');
		echo $this->Form->input('phone_1');
		echo $this->Form->input('phone_1_type');
		echo $this->Form->input('phone_2');
		echo $this->Form->input('phone_2_type');
		echo $this->Form->input('phone_3');
		echo $this->Form->input('phone_3_type');
		echo $this->Form->input('social_media_1');
		echo $this->Form->input('social_media_1_type');
		echo $this->Form->input('social_media_2');
		echo $this->Form->input('social_media_2_type');
		echo $this->Form->input('social_media_3');
		echo $this->Form->input('social_media_3_type');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Leads'), array('action' => 'index')); ?></li>
	</ul>
</div>
