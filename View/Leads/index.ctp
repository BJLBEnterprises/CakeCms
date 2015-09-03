<?php
$pageTitle = __('Leads');
$this->Html->addCrumb($pageTitle);
?>
<div class="leads index">
	<h2><?php echo $pageTitle?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('first_name'); ?></th>
			<th><?php echo $this->Paginator->sort('last_name'); ?></th>
			<th><?php echo $this->Paginator->sort('email'); ?></th>
			<th><?php echo $this->Paginator->sort('phone_1'); ?></th>
			<th><?php echo $this->Paginator->sort('phone_1_type'); ?></th>
			<th><?php echo $this->Paginator->sort('phone_2'); ?></th>
			<th><?php echo $this->Paginator->sort('phone_2_type'); ?></th>
			<th><?php echo $this->Paginator->sort('phone_3'); ?></th>
			<th><?php echo $this->Paginator->sort('phone_3_type'); ?></th>
			<th><?php echo $this->Paginator->sort('social_media_1'); ?></th>
			<th><?php echo $this->Paginator->sort('social_media_1_type'); ?></th>
			<th><?php echo $this->Paginator->sort('social_media_2'); ?></th>
			<th><?php echo $this->Paginator->sort('social_media_2_type'); ?></th>
			<th><?php echo $this->Paginator->sort('social_media_3'); ?></th>
			<th><?php echo $this->Paginator->sort('social_media_3_type'); ?></th>
			<th><?php echo $this->Paginator->sort('created'); ?></th>
			<th><?php echo $this->Paginator->sort('modified'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($leads as $lead): ?>
	<tr>
		<td><?php echo h($lead['Lead']['id']); ?>&nbsp;</td>
		<td><?php echo h($lead['Lead']['first_name']); ?>&nbsp;</td>
		<td><?php echo h($lead['Lead']['last_name']); ?>&nbsp;</td>
		<td><?php echo h($lead['Lead']['email']); ?>&nbsp;</td>
		<td><?php echo h($lead['Lead']['phone_1']); ?>&nbsp;</td>
		<td><?php echo h($lead['Lead']['phone_1_type']); ?>&nbsp;</td>
		<td><?php echo h($lead['Lead']['phone_2']); ?>&nbsp;</td>
		<td><?php echo h($lead['Lead']['phone_2_type']); ?>&nbsp;</td>
		<td><?php echo h($lead['Lead']['phone_3']); ?>&nbsp;</td>
		<td><?php echo h($lead['Lead']['phone_3_type']); ?>&nbsp;</td>
		<td><?php echo h($lead['Lead']['social_media_1']); ?>&nbsp;</td>
		<td><?php echo h($lead['Lead']['social_media_1_type']); ?>&nbsp;</td>
		<td><?php echo h($lead['Lead']['social_media_2']); ?>&nbsp;</td>
		<td><?php echo h($lead['Lead']['social_media_2_type']); ?>&nbsp;</td>
		<td><?php echo h($lead['Lead']['social_media_3']); ?>&nbsp;</td>
		<td><?php echo h($lead['Lead']['social_media_3_type']); ?>&nbsp;</td>
		<td><?php echo h($lead['Lead']['created']); ?>&nbsp;</td>
		<td><?php echo h($lead['Lead']['modified']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $lead['Lead']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $lead['Lead']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $lead['Lead']['id']), null, __('Are you sure you want to delete # %s?', $lead['Lead']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
	));
	?>	</p>
	<div class="paging">
	<?php
		echo $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'));
		echo $this->Paginator->numbers(array('separator' => ''));
		echo $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'));
	?>
	</div>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('New Lead'), array('action' => 'add')); ?></li>
	</ul>
</div>
