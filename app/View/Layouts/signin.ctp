<?php
/**
 *
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.View.Layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
$cakeDescription = __d('cake_dev', 'Psynergy INVOICE SYSTEM');
?>
<!DOCTYPE html>
<html>
<head>
	<?php echo $this->Html->charset(); ?>
	<title>
		<?php echo $cakeDescription ?>:
		<?php echo $title_for_layout; ?>
	</title>
	<?php
		echo $this->Html->meta('icon');

		echo $this->Html->css('common.css');
		echo $this->Html->css('bootstrap.min.css');
		echo $this->Html->css('signin.css');
		
		echo $this->Html->script('jquery-1.10.2.min.js');
		echo $this->Html->script('ie-emulation-modes-warning.js');

		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->fetch('script');
	?>

	<!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
	<!--[if lt IE 9]><script src="/js/ie8-responsive-file-warning.js"></script><![endif]-->

	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
	  <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
	  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->

	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
</head>
<body>
	<header class="container">
		<!--<LOGO>-->
		<h1>Psynergy Invoice system</h1>
		<hr />
		<!--</LOGO>-->
	</header>
	
	<div id="container">
		<?php echo $this->fetch('content'); ?>
		
		<!--<FOOTER>-->
		<footer class="container">
			<h2 class="dpNone">FOOTER</h2>
			<p>
				Copyright ⓒ <?php echo $this->Html->link('Psynergy','http://psynergyc.com')?> all rights reserved
			</p>
		</footer>
		<hr />			
		<!--</FOOTER>-->
	</div>
	
	<?php echo $this->Html->script('bootstrap.min.js'); ?>
	<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
	<?php echo $this->Html->script('ie10-viewport-bug-workaround.js'); ?>
</body>
</html>
