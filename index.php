<html>
	<head>
		<title>Instagtam Backup Tool</title>
		<link href="css/main.css" type="text/css" rel="stylesheet"/>
		<script src="js/jQuery.js" type="text/javascript"></script>
		<?php
			session_start();
			require_once('classes/main.php');
		?>
	</head>
	<body>
		<div class="dummy">
			<div align="center" class="logo"></div>
			<div align="center" class="logo-text"></div>
			<div align="center" class="intro">Backup your <b>Instagram.</b></div>
		</div>
		<?php
			if(isset($_GET['code'])) {
				$currentUser = new Backup($_GET['code']);
				$currentUser->requestAccessToken();
				$currentUser->getThumbnails();
				require_once('includes/load-data.php');
				$se = serialize($currentUser);
				$_SESSION['Object'] = $se;
			}
			else {
				require_once('includes/load-login.php');
			}
		?>
	</body>
</html>