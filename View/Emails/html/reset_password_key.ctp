<?php

?>
<p><?php echo __('Hello %1s %2s,', array($first_name, $last_name))?></p>
<p><?php echo __('We recently received a request to reset the password associated to username <b>%1s</b> on <a href="http://bjlbenterprises.com">bjlbenterprises.com</a>.', array($username))?></p>
<p><?php echo __('To complete the password reset process click on the <a href="%1s">link</a> and you will be able to reset your password.', array('http://'. $host .'/reset_password?key='. $key))?></p>
<p><?php echo __('If you did not request your password to be reset, you may ignore this email. But for your own protection, we suggest you sign in and change your password at some time in the future.')?></p>
<p><?php echo __('Thank you.')?></p>

