<?php
$pageTitle = __('My Profile');
$this->Html->addCrumb($pageTitle);
?>
<div class="row">
  <div class="col-md-12">
    <h2><?= $pageTitle?></h2>
    <dl class="dl-horizontal">
      <dt><?= __('First Name')?></dt>
      <dd><?= $user['first_name']?></dd>
      <dt><?= __('Last Name')?></dt>
      <dd><?= $user['last_name']?></dd>
      <dt><?= __('Email')?></dt>
      <dd><?= $user['email']?></dd>
      <dt><?= __('Username')?></dt>
      <dd><?= $user['username']?></dd>
      <dt><?= __('Password')?></dt>
      <dd><?= $this->Html->link(__('Reset Password'), array('plugin' => 'cake_cms',
                                                           'controller' => 'users',
                                                           'action' => 'reset_password',
                                                           '?' => array('key' => $key)))?></dd>
    </dl>
  </div>
</div>

