<div class="finances index">
	<h2><?php echo __('Finances'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('name'); ?></th>
			<th><?php echo $this->Paginator->sort('alias'); ?></th>
			<th><?php echo $this->Paginator->sort('number'); ?></th>
			<th><?php echo $this->Paginator->sort('type'); ?></th>
			<th><?php echo $this->Paginator->sort('created'); ?></th>
			<th><?php echo $this->Paginator->sort('modified'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($finances as $account): ?>
	<tr>
		<td><?php echo h($account['Account']['id']); ?>&nbsp;</td>
		<td><?php echo h($account['Account']['name']); ?>&nbsp;</td>
		<td><?php echo h($account['Account']['alias']); ?>&nbsp;</td>
		<td><?php echo h($account['Account']['number']); ?>&nbsp;</td>
		<td><?php echo h($account['Account']['type']); ?>&nbsp;</td>
		<td><?php echo h($account['Account']['created']); ?>&nbsp;</td>
		<td><?php echo h($account['Account']['modified']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $account['Account']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $account['Account']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $account['Account']['id']), null, __('Are you sure you want to delete # %s?', $account['Account']['id'])); ?>
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
		<li><?php echo $this->Html->link(__('New Finance'), array('action' => 'add')); ?></li>
	</ul>
</div>
