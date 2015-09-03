<?php
$pageTitle = __('Pages');
$this->Html->addCrumb($pageTitle);
?>
<div class="contents index">
	<h2><?= $pageTitle?></h2>
	<table cellpadding="0" cellspacing="0">
		<tr>
			<th><?= $this->Paginator->sort('id'); ?></th>
			<th><?= $this->Paginator->sort('action'); ?></th>
			<th><?= $this->Paginator->sort('title'); ?></th>
			<th><?= $this->Paginator->sort('description'); ?></th>
			<th><?= $this->Paginator->sort('keywords'); ?></th>
			<th><?= $this->Paginator->sort('version'); ?></th>
			<th><?= $this->Paginator->sort('published'); ?></th>
			<th><?= $this->Paginator->sort('created'); ?></th>
			<th><?= $this->Paginator->sort('modified'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
		</tr>
		<?php foreach ($pages as $page): ?>
		<tr>
			<td><?= h($page['Page']['id']); ?>&nbsp;</td>
			<td><?= h($page['Page']['action']); ?>&nbsp;</td>
			<td><?= h($page['Page']['title']); ?>&nbsp;</td>
			<td><?= h($page['Page']['description']); ?>&nbsp;</td>
			<td><?= h($page['Page']['keywords']); ?>&nbsp;</td>
			<td><?= h($page['Page']['version']); ?>&nbsp;</td>
			<td><?= h($page['Page']['published']); ?>&nbsp;</td>
			<td><?= h($page['Page']['created']); ?>&nbsp;</td>
			<td><?= h($page['Page']['modified']); ?>&nbsp;</td>
			<td class="actions">
        <?php if (!empty($page['Page']['published'])): ?>
				<?= $this->Form->postLink(null, array('action' => 'unpost', $page['Page']['id']), array('class' => 'glyphicon glyphicon-remove-sign red', 'title' => __('Unpost')), __('Are you sure you want to unpost %s?', $page['Page']['title'])); ?>
        <?php else: ?>
				<?= $this->Form->postLink(null, array('action' => 'post', $page['Page']['id']), array('class' => 'glyphicon glyphicon-ok-sign', 'title' => __('Post')), __('Are you sure you want to post %s?', $page['Page']['title'])); ?>
        <?php endif; ?>
				<?= $this->Html->link(null, array('action' => 'view', $page['Page']['id']), array('class' => 'glyphicon glyphicon-eye-open', 'title' => __('View'))); ?>
				<?= $this->Html->link(null, array('action' => 'edit', $page['Page']['id']), array('class' => 'glyphicon glyphicon-edit', 'title' => __('Edit'))); ?>
				<?= $this->Form->postLink(null, array('action' => 'delete', $page['Page']['id']), array('class' => 'glyphicon glyphicon-trash red', 'title' => __('Delete')), __('Are you sure you want to delete %s?', $page['Page']['title'])); ?>
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
	<h3><?= __('Actions')?></h3>
	<ul>
		<li><?= $this->Html->link(__('New Page'), array('plugin' => 'cake_cms', 'controller' => 'contents', 'action' => 'add'))?></li>
	</ul>
</div>
