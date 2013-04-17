<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title><?php echo isset($title_for_layout) ? $title_for_layout : 'PhIntuitive - 404 Not Found' ?></title>
		<link rel="stylesheet" href="http://localhost/themes/default/style.css">
	</head>
	
	<body>
		
		<header class="header">
			<h3><a href="<?php echo App::host() ?>">PhIntuitive</a></h3>
		</header>
		
		<div class="container" style="padding-top: 60px;">
			<h1>Erreur 404 - Page Non Trouvée</h1>
			<?php echo $content_for_layout ?>
		</div>

		<footer class="footer">
			<?php echo '<p>Page générée en '.round(microtime(true)-BEGIN_TIME, 5).'sec</p>' ?>
		</footer>
	</body>
</html>
