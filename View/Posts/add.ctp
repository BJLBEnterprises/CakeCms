<?php
$pageTitle = __('Add Post');
$this->Html->addCrumb(__('Posts'), array('plugin' => 'cake_cms', 'controller' => 'posts', 'action' => 'index'));
$this->Html->addCrumb($pageTitle);
?>
<div class="posts form">
<?php echo $this->Form->create('Post'); ?>
	<fieldset>
		<legend><?php echo __('Add Post'); ?></legend>
	<?php
		echo $this->Form->input('author_id');
		echo $this->Form->input('title');
		echo $this->Form->input('body');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Posts'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Users'), array('controller' => 'users', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Author'), array('controller' => 'users', 'action' => 'add')); ?> </li>
	</ul>
</div>
