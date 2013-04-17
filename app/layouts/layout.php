<?php
$keywords = isset($keywords_for_layout) ? $keywords_for_layout : 'PhIntuitive, framework, php, websites, development, Boutter, Loïc';
$description = isset($description_for_layout) ? $description_for_layout : 'PhIntuitive - Fast websites development php framework';
?>
<!DOCTYPE html>
<html>
	<head>
		<?php echo $this->Html->charset(); ?>
		<?php echo $this->Html->robots('index,follow'); ?>
		<?php echo $this->Html->description($description); ?>
		<?php echo $this->Html->keywords($keywords); ?>
		<title><?php echo isset($title_for_layout) ? $title_for_layout : 'PhIntuitive' ?></title>
		<?php echo $this->Html->css('style.css'); ?>
		<?php echo $this->Html->jQuery(); ?>
		<?php echo $this->Html->js('myscripts.js'); ?>
		<!--
		*	PhIntuitive - Fast websites development framework
		*	Copyright 2013, Boutter Loïc - http://loicboutter.fr
		*
		*	Licensed under The MIT License
		*	Redistributions of files must retain the above copyright notice.
		*
		*	@copyright Copyright 2013, Boutter Loïc - http://loicboutter.fr
		*	@author Boutter Loïc
		*	@since 2.0.0
		-->
	</head>
	
	<body>
		
		<header class="header">
			<h3><a href="<?php echo App::host() ?>">PhIntuitive</a></h3>
		</header>
		
		<section class="container">
			<h1>PhIntuitive</h1>

			<!--{{content_for_layout}}-->
			<?php echo $content_for_layout; ?>
			
		</section>
		
		<footer class="footer">
			<?php echo '<p>Page générée en '.round(microtime(true)-BEGIN_TIME, 5).'sec</p>' ?>
		</footer>
	</body>
</html>
