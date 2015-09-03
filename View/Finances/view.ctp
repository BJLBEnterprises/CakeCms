<div class="finances view">
<h2><?php echo __('Finance'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($account['Account']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Name'); ?></dt>
		<dd>
			<?php echo h($account['Account']['name']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Alias'); ?></dt>
		<dd>
			<?php echo h($account['Account']['alias']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Number'); ?></dt>
		<dd>
			<?php echo h($account['Account']['number']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Type'); ?></dt>
		<dd>
			<?php echo h($account['Account']['type']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($account['Account']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified'); ?></dt>
		<dd>
			<?php echo h($account['Account']['modified']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Finance'), array('action' => 'edit', $account['Account']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Finance'), array('action' => 'delete', $account['Account']['id']), null, __('Are you sure you want to delete # %s?', $account['Account']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Finances'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Finance'), array('action' => 'add')); ?> </li>
	</ul>
</div>
