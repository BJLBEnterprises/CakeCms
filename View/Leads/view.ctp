<?php
$pageTitle = __('View Lead');
$this->Html->addCrumb(__('Leads'), array('plugin' => 'cake_cms', 'controller' => 'leads', 'action' => 'index'));
$this->Html->addCrumb($pageTitle);
?>
<div class="leads view">
<h2><?php echo __('Lead'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($lead['Lead']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('First Name'); ?></dt>
		<dd>
			<?php echo h($lead['Lead']['first_name']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Last Name'); ?></dt>
		<dd>
			<?php echo h($lead['Lead']['last_name']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Email'); ?></dt>
		<dd>
			<?php echo h($lead['Lead']['email']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Phone 1'); ?></dt>
		<dd>
			<?php echo h($lead['Lead']['phone_1']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Phone 1 Type'); ?></dt>
		<dd>
			<?php echo h($lead['Lead']['phone_1_type']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Phone 2'); ?></dt>
		<dd>
			<?php echo h($lead['Lead']['phone_2']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Phone 2 Type'); ?></dt>
		<dd>
			<?php echo h($lead['Lead']['phone_2_type']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Phone 3'); ?></dt>
		<dd>
			<?php echo h($lead['Lead']['phone_3']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Phone 3 Type'); ?></dt>
		<dd>
			<?php echo h($lead['Lead']['phone_3_type']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Social Media 1'); ?></dt>
		<dd>
			<?php echo h($lead['Lead']['social_media_1']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Social Media 1 Type'); ?></dt>
		<dd>
			<?php echo h($lead['Lead']['social_media_1_type']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Social Media 2'); ?></dt>
		<dd>
			<?php echo h($lead['Lead']['social_media_2']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Social Media 2 Type'); ?></dt>
		<dd>
			<?php echo h($lead['Lead']['social_media_2_type']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Social Media 3'); ?></dt>
		<dd>
			<?php echo h($lead['Lead']['social_media_3']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Social Media 3 Type'); ?></dt>
		<dd>
			<?php echo h($lead['Lead']['social_media_3_type']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($lead['Lead']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified'); ?></dt>
		<dd>
			<?php echo h($lead['Lead']['modified']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Lead'), array('action' => 'edit', $lead['Lead']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Lead'), array('action' => 'delete', $lead['Lead']['id']), null, __('Are you sure you want to delete # %s?', $lead['Lead']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Leads'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Lead'), array('action' => 'add')); ?> </li>
	</ul>
</div>
