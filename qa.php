<?php 
require_once(__DIR__.'/include/web.config.php');

$Input 	= GDC( $_GET['c'] , 'qa');

$_QA_ID = $Input['qaid'];




if( empty($_QA_ID) ) {
	
	$_QA_ID = key($_QA_aside['Data']);
}

$_html 			= $CM->GET_QA_DATA( $_QA_ID );

//-----------------------------SEO-------------------------------------------
$_setting_['WO_Keywords'] 		.= GET_HEAD_KD( $_html , 'K' );
$_setting_['WO_Description'] 	.= GET_HEAD_KD( $_html );
//---------------------------------------------------------------------------

$_Title 	= $_QA_aside['Data'][$_QA_ID];
?>
<!DOCTYPE html>
<html lang="zh-Hant-TW">
	<head>
		<?php require('head.php') ?>
		<link rel="stylesheet" type="text/css" href="stylesheets/layout.css?v=<?=$version?>" />
		<script type="text/javascript" src="js/hc-sticky.js"></script>
	</head>



	<body>
		<?php require('mobile_aside.php') ?>
		<div class="Wrapper">
			<div class="Wrapper__mask"></div>
			<?php require('header.php') ?>
			<article class="mainbody container">
				
				<aside class="aside">
					<ul class="aside__cat">
						<?php foreach( $_QA_aside['Data'] as $key => $val ){ ?>
							<li class="aside__cat__item <?=$_QA_ID==$key?'current':''?>"><a href="qa.php?c=<?=OEncrypt('qaid='.$key, 'qa')?>"><?=$val?></a></li>
						<?php } ?>
						
					</ul>
					
				</aside>

				<section class="main">
					<div class="bread">
						<ol class="breadcrumb" itemscope itemtype="https://schema.org/BreadcrumbList">
						  <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
						    <!-- Method 1 (preferred) -->
						    <a itemprop="item" href="index.php">
						        <span itemprop="name">首頁</span></a>
						    <meta itemprop="position" content="1" />
						  </li>
						  > 
						  <li itemprop="itemListElement" itemscope
						      itemtype="https://schema.org/ListItem">
						    <!-- Method 2 -->
						    <a itemscope itemtype="https://schema.org/WebPage"
						       itemprop="item" itemid="https://example.com/books/sciencefiction"
						        href="qa.php">
						      <h2 itemprop="name">購物須知</h2></a>
						    <meta itemprop="position" content="2" />
						  </li>
						   > 
						  <li itemprop="itemListElement" itemscope
						      itemtype="https://schema.org/ListItem">
						    <!-- Method 2 -->
						    <a itemscope itemtype="https://schema.org/WebPage"
						       itemprop="item" itemid="https://example.com/books/sciencefiction"
						       href="qa.php?c=<?=OEncrypt('qaid='.$_QA_ID , 'qa')?>">
						      <h1 itemprop="name"><?=$_QA_aside['Data'][$_QA_ID]?></h1></a>
						    <meta itemprop="position" content="3" />
						  </li>
						</ol>
					
					</div>
					<br><br>
					
					<div class="textcontent">
						<?=TurnSymbol($_html['Data']['QA_Content'])?>
					</div>
					
					<br><br><br>
					<div class="btnlist btnlist--center">
						<button class="btnstyle01  btnstyle01--black" onclick="history.back()" >回上頁</button>
					</div>
					<br><br>
					

				</section>



			</article>

			<?php require('footer.php') ?>
		</div>
		
	</body>
	<script>
	    "use strict";
	    var Sticky = new hcSticky('.aside__cat', {
	      stickTo: '.mainbody',
	      top: 70,
	      queries: {
	        980: {
	          disable: true
	        }
	      }
	    });
	 </script>

</html>