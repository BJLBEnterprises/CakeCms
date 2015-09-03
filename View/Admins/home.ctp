<?php
if (!$this->Session->read('Auth.User')) {
?>
<div class="canvas" style="height: 486px;">
  <div class="center text-block">
		<h1 style="font-size: xx-large;"><?= __('Welcome to Cake CMS')?></h1>
	</div>
	<div class="center text-block">
		<?php echo $this->element('CakeCms.login'); ?>
	</div>
</div>
	<?php
}
else {
?>
<div class="admins home">
	<div class="dashboard">
		<h2><?= __('Cake CMS: Dashboard')?></h2>
		<hr />
	</div>
	<div class="view">
		<h2></h2>
    <div class="row" style="background-color:#008000;color:#fff;">
      <div class="col-md-12">
        green(#008000)
      </div>
    </div>
    <div class="row" style="background-color:#0000ff;color:#fff;">
      <div class="col-md-12">
        blue(#0000ff)
      </div>
    </div>
    <div class="row" style="background-color:#b87333;color:#fff;">
      <div class="col-md-12">
        copper(#b87333)
      </div>
    </div>
    <div class="row" style="background-color:#ffd700;">
      <div class="col-md-12">
        gold(#ffd700)
      </div>
    </div>
    <div class="row" style="background-color:#ffc0cb;">
      <div class="col-md-12">
        pink(#ffc0cb)
      </div>
    </div>
    <div class="row" style="background-color:#ccffff;">
      <div class="col-md-12">
        pale plue(#ccffff)
      </div>
    </div>
	</div>
	<div class="actions">
		<h3><?= __('Actions')?></h3>
		<ul>
			<li><?= $this->Html->link(__('Pages'), array('plugin' => 'cake_cms', 'controller' => 'contents', 'action' => 'index', 'full_base' => true))?></li>
			<li><?= $this->Html->link(__('Posts'), array('plugin' => 'cake_cms', 'controller' => 'posts'))?></li>
			<li><hr /></li>
			<li><?= $this->Html->link(__('Leads'), array('plugin' => 'cake_cms', 'controller' => 'leads'))?></li>
			<li><?= $this->Html->link(__('Contacts'), array('plugin' => 'cake_cms', 'controller' => 'contacts'))?></li>
			<li><hr /></li>
			<li><?= $this->Html->link(__('Groups'), array('plugin' => 'cake_cms', 'controller' => 'groups'))?></li>
			<li><?= $this->Html->link(__('Users'), array('plugin' => 'cake_cms', 'controller' => 'users'))?></li>
		</ul>
	</div>
</div>
<?php
}
?>
