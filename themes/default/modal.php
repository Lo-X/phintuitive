<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title><?php echo isset($title_for_layout) ? $title_for_layout : 'PhIntuitive' ?></title>
		<link rel="stylesheet" href="<?php echo HOST ?>/themes/default/style.css">
	</head>
	
	<body>	
		<section class="container">
			<?php echo $content_for_layout ?>
		</section>
	</body>
</html>