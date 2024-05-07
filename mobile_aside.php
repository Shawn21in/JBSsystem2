<aside class="mobile_aside">
	<button class="mobile_btn mobile_btn--aside">
		<div id="nav-icon4"> <span></span> <span></span> <span></span> </div>
	</button>

	<div class="searchbox searchbox--mobile">
		<input type="text" placeholder="搜尋商品" id="search_key_mobile">
		<button class="style01 search_btn_mobile"><i class="fas fa-search"></i></button>
	</div>
	<div class="linkbox linkbox--mobile">
		<a href="">訂單查詢</a>
		<?php if( !$_Login ){ ?>
			<a href="login.php">登入</a>
		<?php }else{ ?>
			<a href="javascript:void(0)" onclick="logout()">登出</a>
		<?php } ?>
	</div>


	<ul class="mnav">
		<li class="mnav__item"><a href="javascript:void(0)">會員中心</a>
			<ul class="mnav__item__list">
				<li><a href="member.php">會員資料修改</a></li>
				<li><a href="order.php">訂單查詢</a></li>
			</ul>
		</li>
		<li class="mnav__item"><a href="product.php">所有商品</a>
			<ul class="mnav__item__list">
				<li class="<?=(empty($_PCID)&&$Input['mode']!='hot'&&(PHP_NAME=='product'?'current':''||PHP_NAME=='productin'))?'current':''?>"><a href="product.php">所有商品</a></li>
				<li class="<?=$Input['mode']=='hot'?'current':''?>"><a href="product.php?c=<?=OEncrypt('mode=hot' , 'products')?>">人氣商品推薦</a></li>
				<?php foreach( $_ProAside['Data'] as $LV1_KEY => $LV1_Data ){ ?>
					<li><a href=""><?=$LV1_Data['name']?></a>
						<ul class="mnav__item__list">
							<?php foreach( $LV1_Data['NextLv'] as $LV2_KEY => $LV2_Data ){ ?>
								<li class="<?=$_PCID==$LV2_KEY?'current':''?>"><a href="product.php?c=<?=OEncrypt('pcid='.$LV2_KEY , 'products')?>"><?=$LV2_Data['name']?></a></li>
							<?php } ?>
						</ul>
					</li>
				<?php } ?>
			</ul>

		</li>
		<li class="mnav__item"><a href="about.php">品牌故事</a></li>
		<li class="mnav__item"><a href="news.php">最新消息</a>
			<ul class="mnav__item__list">
				<?php foreach( $_Aside['Data'] as $key => $val ) { ?>
					<li class="<?=(PHP_NAME == 'news'&&$NewsC_ID == $key)?'current':''?>"><a href="news.php?c=<?=OEncrypt('ncid='.$key , 'news')?>"><?=$val?></a></li>
				<?php } ?>
			</ul>
		</li>
		<li class="mnav__item"><a href="contact.php">聯絡我們</a></li>
		<li class="mnav__item"><a href="qa.php">購物須知</a>
			<ul class="mnav__item__list">
				<?php foreach( $_QA_aside['Data'] as $key => $val ){ ?>		
				<li class="<?=$_QA_ID==$key?'current':''?>"><a href="qa.php?c=<?=OEncrypt('qaid='.$key , 'qa')?>"><?=$val?></a></li>
				<?php } ?>

			</ul>
		</li>

	
</aside>