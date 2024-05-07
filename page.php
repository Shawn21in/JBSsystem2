<section class="sectionPagination">
			<nav aria-label="PageNavigation">
				<ul class="pagination">
					<li class="pageItem">
						<a class="page-link" href="<?=$Pages_Data['Page_Url'].$Pages_Data['Page_Pre']?>" aria-label="Previous">
							<span aria-hidden="true">&laquo;</span>
						</a>
					</li>
          <?php for( $i = $Pages_Data['Pstart']; $i <= $Pages_Data['Pend']; $i++ ){ ?>
              <li class="pageItem"><a class="pageLink <?=$i==$Pages_Data['Pages']?"pageLinkActive":""?>" href="<?=$Pages_Data['Page_Url'].$i?>"><?=$i?></a></li>
          <?php } ?>
					<li class="pageItem">
						<a class="page-link" href="<?=$Pages_Data['Page_Url'].$Pages_Data['Page_Next']?>" aria-label="Next">
							<span aria-hidden="true">&raquo;</span>
						</a>
					</li>
				</ul>
			</nav>
		</section>