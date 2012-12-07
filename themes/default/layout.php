<?php echo $this->Html->doctype(); ?>

<html>
	<head>
		<?php echo $this->Html->charset(); ?>

		<title><?php echo isset($title_for_layout) ? $title_for_layout : 'PhIntuitive' ?></title>

		<?php echo $this->Html->css('style.css'); ?>

		<?php echo $this->Html->jQuery(); ?>

		<?php echo $this->Html->js('myscripts.js'); ?>

	</head>
	
	<body>
		
		<header class="header">
			<h3><a href="<?php echo App::host() ?>">PhIntuitive</a></h3>
		</header>
		
		<section class="container">
			<h1>PhIntuitive</h1>

			<?php echo $content_for_layout; ?>
			<!-- We can put: {{content_for_layout}} here if we want to use the layout cache system with wiews -->

			
		</section>
		
		<footer class="footer">
			<p><?php echo 'Copyright 2012 - Loïc Boutter' ?></p>
		</footer>
	</body>
</html>
