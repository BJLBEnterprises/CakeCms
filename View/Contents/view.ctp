<?php
$pageTitle = __('View %1s Page', $page['Page']['title']);
$this->Html->addCrumb(__('Pages'), array('plugin' => 'cake_cms', 'controller' => 'contents', 'action' => 'index'));
$this->Html->addCrumb($pageTitle);
?>
<div class="contents">
	<fieldset>
		<legend><?php echo $pageTitle?></legend>
    <dl class="dl-horizontal">
      <dt><?= __('Action')?></dt>
      <dd><?= $page['Page']['action']?></dd>
      <dt><?= __('Title')?></dt>
      <dd><?= $page['Page']['title']?></dd>
      <dt><?= __('Description')?></dt>
      <dd><?= $page['Page']['description']?></dd>
      <dt><?= __('Keywords')?></dt>
      <dd><?= $page['Page']['keywords']?></dd>
    </dl>
  </fieldset>
</div>
