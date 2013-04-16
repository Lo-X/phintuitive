<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title><?php echo isset($title_for_layout) ? $title_for_layout : 'PhIntuitive' ?></title>
		<link rel="stylesheet" href="http://localhost/themes/default/style.css">
	</head>
	
	<body>
		
		<header>
			<h3><a href="<?php echo HOST ?>">PhIntuitive</a></h3>
			<ul class="nav">
				<?php echo isset($menu_for_layout) ? $menu_for_layout : '' ?>
			</ul>
		</header>
		
		<div class="container" style="padding-top: 60px;">
			<h1>Erreur 404 - Page Non Trouvée</h1>
			<?php echo $content_for_layout ?>
		</div>
	</body>
</html>
