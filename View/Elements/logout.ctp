<?php
?>
<div id="logout">
  <?php echo $this->Html->link('Logout',
    array('controller' => 'users', 'action' => 'logout'),
    array('classes' => '', 'styles' => '')); ?>
</div>
