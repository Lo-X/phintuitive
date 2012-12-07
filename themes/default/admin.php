<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title><?php echo isset($title_for_layout) ? $title_for_layout : 'Administration' ?></title>
		
		<link rel="stylesheet" href="<?php echo HOST ?>/themes/default/admin-style.css">
		<link rel="stylesheet" href="<?php echo HOST ?>/themes/default/jquery.ui.css">
		<!--<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js"></script>-->
		<script type="text/javascript" src="<?php echo HOST ?>/themes/js/jquery.js"></script>
		<script type="text/javascript" src="<?php echo HOST ?>/themes/js/jquery.ui.js"></script>
		<script type="text/javascript" src="<?php echo HOST ?>/themes/js/myscripts.js"></script>
	</head>
	
	<body>
		
		<header class="header">
			<p>
				<img src="<?php echo HOST.'/'.THEME_DIR.'/images/settings.png' ?>" alt="" align="right" width="35" height="35" /> 
				Bonjour, <a href="#"><?php echo $this->Session->get('login') ?></a> | 
				<a href="<?php echo HOST ?>">Retour au site</a> |
				<a href="<?php echo Router::url('users/logout') ?>">Déconnexion</a>
			</p>
		</header>

		<?php echo phi_menu('adminpanel') ?>
			
		<section class="container">
			<?php
			$this->Session->flash();
			echo $content_for_layout 
			?>
		</section>
		
		<footer class="footer">
			
		</footer>
	</body>
	<script type="text/javascript">
	jQuery(function($){
		$('.tag a').click(function() {
			$.get($(this).attr('href'));
			$(this).parent().fadeOut();
			return false;
		});
		$('h3 .close a').each(function() {
			$(this).click(function() {
				$(this).parent().parent().parent().find('.slideable').slideToggle();
				return false;
			})
		});
		$('.alert .close a').each(function() {
			$(this).click(function() {
				$(this).parent().parent().fadeOut();
				return false;
			})
		});
	});
	</script>
	<script type="text/javascript" src="<?php echo HOST ?>/themes/<?php echo JS_DIR ?>/tinymce/tiny_mce.js"></script>
	<script type="text/javascript">
	tinyMCE.init({
        // General options
        mode : "textareas",
        theme : "advanced",
        plugins : "autolink,lists,spellchecker,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",

        // Theme options
	   theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,bullist,numlist,|,cut,copy,paste,|,undo,redo,|,link,unlink,image,code,|,hr,sub,sup,charmap,pagebreak,|,fullscreen",
        theme_advanced_buttons2 : "",
        theme_advanced_buttons3 : "",
        theme_advanced_buttons4 : "",
        theme_advanced_toolbar_location : "top",
        theme_advanced_toolbar_align : "left",
        theme_advanced_statusbar_location : false,
        theme_advanced_resizing : true,

        // Skin options
        skin : "o2k7",
        skin_variant : "silver",

        file_browser_callback : 'fileBrowser',
	   relative_urls : false
	});
	function fileBrowser(field_name, url, type, win) {
		tinyMCE.activeEditor.windowManager.open({
			file : '<?php echo Router::url('admin/medias/') ?>',
			title : 'Galerie',
			width : 420,
			height: 400,
			resizable : 'yes',
			inline : 'yes',
			close_previous : 'no'
		}, {
			window : win,
			input : field_name
		});
		return false;
	}
	</script>
</html>