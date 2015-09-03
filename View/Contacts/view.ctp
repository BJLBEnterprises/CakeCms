<?php
$pageTitle = __('View Contact');
$this->Html->addCrumb(__('Contacts'), array('plugin' => 'cake_cms', 'controller' => 'contacts', 'action' => 'index'));
$this->Html->addCrumb($pageTitle);
?>
<div class="contacts view">
<h2><?php echo __('Contact'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($contact['Contact']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('First Name'); ?></dt>
		<dd>
			<?php echo h($contact['Contact']['first_name']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Last Name'); ?></dt>
		<dd>
			<?php echo h($contact['Contact']['last_name']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Email'); ?></dt>
		<dd>
			<?php echo h($contact['Contact']['email']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Phone 1'); ?></dt>
		<dd>
			<?php echo h($contact['Contact']['phone_1']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Phone 1 Type'); ?></dt>
		<dd>
			<?php echo h($contact['Contact']['phone_1_type']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Phone 2'); ?></dt>
		<dd>
			<?php echo h($contact['Contact']['phone_2']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Phone 2 Type'); ?></dt>
		<dd>
			<?php echo h($contact['Contact']['phone_2_type']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Phone 3'); ?></dt>
		<dd>
			<?php echo h($contact['Contact']['phone_3']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Phone 3 Type'); ?></dt>
		<dd>
			<?php echo h($contact['Contact']['phone_3_type']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Social Media 1'); ?></dt>
		<dd>
			<?php echo h($contact['Contact']['social_media_1']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Social Media 1 Type'); ?></dt>
		<dd>
			<?php echo h($contact['Contact']['social_media_1_type']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Social Media 2'); ?></dt>
		<dd>
			<?php echo h($contact['Contact']['social_media_2']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Social Media 2 Type'); ?></dt>
		<dd>
			<?php echo h($contact['Contact']['social_media_2_type']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Social Media 3'); ?></dt>
		<dd>
			<?php echo h($contact['Contact']['social_media_3']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Social Media 3 Type'); ?></dt>
		<dd>
			<?php echo h($contact['Contact']['social_media_3_type']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($contact['Contact']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified'); ?></dt>
		<dd>
			<?php echo h($contact['Contact']['modified']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Contact'), array('action' => 'edit', $contact['Contact']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Contact'), array('action' => 'delete', $contact['Contact']['id']), null, __('Are you sure you want to delete # %s?', $contact['Contact']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Contacts'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Contact'), array('action' => 'add')); ?> </li>
	</ul>
</div>
