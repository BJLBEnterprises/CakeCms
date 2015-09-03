<?php

?>
<div class="authentication_toggle_block">
<?php if ($user = $this->Session->read('Auth.User')): ?>
	<div class="authenticated">
		<div class="right">
			<?php echo $this->Html->link(__('My Profile'), '/profile')?> |
 <?php echo $this->Html->link(__('Sign Out?'), '/sign_out')?>
		</div>
		<div class="users-name right">
			<?php echo __('Welcome, %1s', $user['first_name'] .' '. $user['last_name'])?> |
		</div>
	</div>
<?php else:?>
	<div class="unauthenticated">
		<ul>
			<li>
			  <?php if (Configure::read('CakeCms.sign_up_allowed')):?>
				<?php echo $this->Html->link(__('Sign In | Sign Up'), '/#')?>
        <?php else:?>
				<?php echo $this->Html->link(__('Sign In'), '/#')?>
        <?php endif?>
				<ul>
					<li>
						<?php echo $this->Form->create('User', array('url' => '/sign_in', 'id' => 'UserSignInForm'))?>
						<?php echo $this->Form->input('username', array('type' => 'text'))?>
						<?php echo $this->Form->input('password', array('type' => 'password'))?>
						<?php echo $this->Form->end(__('Sign In'))?>
						<div>
							<?php echo $this->Html->link(__('Recover Username'), '/recover_username')?> |
							<?php echo $this->Html->link(__('Reset Password'), '/reset_password')?>
						</div>
			      <?php if (Configure::read('CakeCms.sign_up_allowed')):?>
						<hr />
						<div>
							<?php echo $this->Html->link(__('Sign Up'), '/sign_up')?>
						</div>
            <?php endif?>
					</li>
				</ul>
			</li>
		</ul>
	</div>
<?php endif?>
</div>
