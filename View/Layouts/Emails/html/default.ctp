<?php
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN">
<html>
<head>
	<title><?php echo $title_for_layout; ?></title>
</head>
<body>
	<?php echo $this->fetch('content'); ?>

	<p><?php echo __('This email was sent to you because your email address was provided to BJLB Enterprises. If you did not sign up with BJLB Enterprises, please forward this email to <a href="%1s" alt="%2s" title="%3s">%4s</a> and we will remove this account from our system.', array('mailto:fraud@bjlbenterprises.com', 'fraud@bjlbenterprises.com', 'fraud@bjlbenterprises.com', 'fraud@bjlbenterprises.com'))?></p>
</body>
</html>