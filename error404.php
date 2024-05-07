<?php 

require_once(__DIR__."/include/web.config.php");

$_Title = 'Oops!找不到該頁面...';

http_response_code(404);
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
	<?php require('head.php') ?>
	<link rel="stylesheet" type="text/css" href="stylesheets/error.css?v=<?=$version?>" />
</head>
<body>

	
	<?php require('header.php') ?>

	<main class="main">
	<?php require('mobile_aside.php') ?>
		<article>
			<div class="error404">
				<!--<h3>404</h3>-->
				<p>Oops!找不到該頁面...</p>
				<!--<span>Oops!找不到該頁面...</span>-->
				<a href="index.php">點我前往 <?=$_setting_['WO_Title']?> 首頁</a></span>
				<br>
			</div>




		</article>
	</main>

	<?php require('footer.php')?>

	
</body>


</html>