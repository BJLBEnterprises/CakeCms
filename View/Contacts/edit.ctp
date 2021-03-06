<?php
$pageTitle = __('Edit Contact');
$this->Html->addCrumb(__('Contacts'), array('plugin' => 'cake_cms', 'controller' => 'contacts', 'action' => 'index'));
$this->Html->addCrumb(__('View Contact'), array('plugin' => 'cake_cms', 'controller' => 'contacts', 'action' => 'view', $contact['Contact']['id']));
$this->Html->addCrumb($pageTitle);
?>
<div class="contacts form">
<?php echo $this->Form->create('Contact'); ?>
	<fieldset>
		<legend><?php echo __('Edit Contact'); ?></legend>
	<?php
		echo $this->Form->input('id');
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

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('Contact.id')), null, __('Are you sure you want to delete # %s?', $this->Form->value('Contact.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Contacts'), array('action' => 'index')); ?></li>
	</ul>
</div>
