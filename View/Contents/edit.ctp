<?php
$pageTitle = __('Edit %s Page', $page['Page']['title']);
$this->Html->addCrumb(__('Pages'), array('plugin' => 'cake_cms', 'controller' => 'contents', 'action' => 'index'));
$this->Html->addCrumb(__('View %1s Page', array($page['Page']['title'])), array('plugin' => 'cake_cms', 'controller' => 'contents', 'action' => 'view', $page['Page']['id']));
$this->Html->addCrumb($pageTitle);
?>
<div class="contents">
<?php echo $this->Form->create('CakeCms.Content'); ?>
	<fieldset>
		<legend><?php echo $pageTitle?></legend>
	<?php
		echo $this->Form->input('action', array('type' => 'text', 'value' => $page['Page']['action'], 'readonly' => true));
		echo $this->Form->input('title', array('type' => 'text', 'value' => $page['Page']['title']));
		echo $this->Form->input('content', array('type' => 'textarea', 'value' => $page['Page']['content']));
		echo $this->Form->input('description', array('type' => 'textarea', 'value' => $page['Page']['description']));
		echo $this->Form->input('keywords', array('type' => 'text', 'value' => $page['Page']['keywords']));
    echo $this->Form->input('version', array('type' => 'text', 'value' => $page['Page']['version'], 'readonly' => true));
	?>
  </fieldset>
<?php
echo $this->Form->input(__('Save draft'), array(
		'label' => false,
		'div' => false,
		'name' => 'draft',
		'type' => 'submit'));
echo $this->Form->input(__('Publish'), array(
		'label' => false,
		'div' => false,
		'type' => 'submit')); ?>
<?php echo $this->Form->end(); ?>
</div>
<?php $this->Html->scriptStart(array('inline' => false, 'block' => 'script_bottom'))?>
$('textarea#ContentContent').jqte();
<?php $this->Html->scriptEnd()?>
