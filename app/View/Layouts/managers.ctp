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
		echo $this->Html->css('bootsrtap.customize.css?ver=20151211');
		echo $this->Html->css('signin.css');
		echo $this->Html->css('print.css', array('media'=>'print'));
		
		echo $this->Html->script('jquery-1.10.2.min.js');
		echo $this->Html->script('ie-emulation-modes-warning.js');
		echo $this->Html->script('common.js?ver=20160722');

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
	<header>
		<nav class="navbar navbar-inverse navbar-fixed-top">
			<div class="container">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a href="<?php echo $mainPageUrl;?>/" class="none_effect"><h1 class="colorWhite">Psynergy</h1></a>
				</div>
				<div id="navbar" class="collapse navbar-collapse">
					<ul class="nav navbar-nav">
						<li <?php if($current_page=='invoice') echo 'class="active"';?>>
							<a href="<?php echo $mainPageUrl;?>/Managers/Invoice">Invoice<br />(인보이스)</a>
						</li>
						
						<li <?php if($current_page=='schedule') echo 'class="active"';?>>
							<a href="<?php echo $mainPageUrl;?>/Managers/Schedule">Schedule<br />(스케쥴)</a>
						</li>
						
						<li <?php if($current_page=='payable') echo 'class="active"';?>>
							<a href="<?php echo $mainPageUrl;?>/Managers/Payable">Payable<br />(지출)</a>
						</li>
						
						<?php if(AuthComponent::user('mem_level')<=11){?>
						<li <?php if($current_page=='statistics') echo 'class="active"';?>>
							<a href="<?php echo $mainPageUrl;?>/Managers/Statistics">Invoice List<br />(전체 인보이스)</a>
						</li>
						<?php }?>
						
						<?php if(AuthComponent::user('mem_level')==10){?>
						<?php $arr_management=array('company', 'bank', 'customer', 'category', 'product');?>
						<li class="dropdown  <?php if(in_array($current_page, $arr_management)) echo 'active';?>">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Management<br />(사이트관리)<span class="caret"></span></a>
							<ul class="dropdown-menu" role="menu">
								<li><a href="<?php echo $mainPageUrl;?>/Managers/Company">Company(회사정보 관리)</a></li>
								<li><a href="<?php echo $mainPageUrl;?>/Managers/Bank">Bank(은행정보 관리)</a></li>
								<li><a href="<?php echo $mainPageUrl;?>/Managers/Customer">Customer(고객정보 관리)</a></li>
								<li><a href="<?php echo $mainPageUrl;?>/Managers/Category">Category(카테고리 관리)</a></li>
								<li><a href="<?php echo $mainPageUrl;?>/Managers/Product">Product(상품 관리)</a></li>
							</ul>
						</li>
						<?php }?>
					</ul>
					<ul class="nav navbar-nav navbar-right">
						<li><a href="#"><?php echo AuthComponent::user('mem_id');?></a></li>
						<li class="logout">
							<button type="button" class="btn btn-default btn-xs" onclick="location.href='<?php echo $mainPageUrl;?>/MainPage/logout';">Logout</button>
						</li>
					</ul>
					<?php if(AuthComponent::user('mem_level')==10 || AuthComponent::user('mem_level')==11){?>
					<ul class="nav navbar-nav navbar-right">
						<li class="dropdown  <?php if($current_page=='management') echo 'active';?>">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Other Systems<span class="caret"></span></a>
							<ul class="dropdown-menu" role="menu">
								<li><a href="/shop/">Shop</a></li>
								<li><a href="/internship/">Internship</a></li>
								<li><a href="/invoice/">invoice</a></li>
								<li><a href="/analytics/">Analytics</a></li>
								<li><a href="/vr/Managers/MainPage/index">VR</a></li>
							</ul>
						</li>
					</ul>
					<?php }?>
				</div>
			</div>
		</nav>
	</header>
	<div class="clearBoth"></div>
	<div class="container">
		<?php echo $this->fetch('content'); ?>
	</div>
	
	<!--<FOOTER>-->
	<footer class="footer">
		<div class="container">
			<h2 class="dpNone">FOOTER</h2>
			<p class="text-muted">Copyright ⓒ <?php echo $this->Html->link('Psynergy','http://psynergyc.com')?> all rights reserved</p>
		</div>
	</footer>
	<!--</FOOTER>-->
	
	<?php echo $this->Html->script('bootstrap.min.js'); ?>
	<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
	<?php echo $this->Html->script('ie10-viewport-bug-workaround.js'); ?>
</body>
</html>
