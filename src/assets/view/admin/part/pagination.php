<style>

    /* Pagination */
    .cf-button-pagination {
        border: 0;
        -webkit-border-radius: var(--btn-radius);;
        border-radius: var(--btn-radius);
        background: transparent;
        min-width: 250px;
        padding: 0 19px;
        display: inline-block;
        border: 1px solid var(--main-button);
        -webkit-transition: all 0.3s ease-in;
        -o-transition: all 0.3s ease-in;
        transition: all 0.3s ease-in;
        font-weight: normal;
        font-size: 12px;
        line-height: 14px;
        text-align: center;
        text-transform: uppercase;
        height: 40px;
        line-height: 40px;
        color: var(--main-button);
        cursor: pointer;
    }
    .cf-button-pagination:hover {
        border: 1px solid var(--main-button-hover);
        background: var(--main-button);
        color: var(--main-button-color);
    }
    .cf-more-load {
        display: flex;
        align-items: center;
        justify-content: center;

    }

    .cf-pagination-list {
        list-style: none;
        display: -webkit-box;
        display: -webkit-flex;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-align: center;
        -webkit-align-items: center;
        -ms-flex-align: center;
        align-items: center;
    }
    .cf-box-pagination {
        margin: 9px 0px 9px 0px;
        display: -webkit-box;
        display: -webkit-flex;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-align: center;
        -webkit-align-items: center;
        -ms-flex-align: center;
        align-items: center;
        -webkit-box-pack: justify;
        -webkit-justify-content: space-between;
        -ms-flex-pack: justify;
        justify-content: space-between;
    }
    .cf-pagination-list__button {
        cursor: pointer;
    }
    .cf-pagination-list__item {
        padding: 0 7px 0px 0px;
    }
    .cf-pagination-list__button:hover {
        background: var(--main-button);
        color: var(--main-button-color);
    }
    .cf-box-pagination-info {

        margin-left: 0px;
        display: -webkit-box;
        display: -webkit-flex;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-align: center;
        -webkit-align-items: center;
        -ms-flex-align: center;
        align-items: center;
    }
    .cf-pagination-count-items {
        color: #868993;
        margin-right: 33px;
        position: relative;
    }
    .cf-pagination-count-items:after {
        content: '';
        display: block;
        height: 100%;
        top: 0px;
        background: #868993;
        position: absolute;
        right: -17px;
        width: 1px;
    }
    .cf-pagination-sort__group {
        display: -webkit-box;
        display: -webkit-flex;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-align: center;
        -webkit-align-items: center;
        -ms-flex-align: center;
        align-items: center;
    }
    .cf-pagination-sort__group__label {
        margin-right: 12px;
        line-height: 16px;
        color: #868993;
    }
    .cf-pagination-limit {
        width: auto;
        -webkit-flex-shrink: 0;
        -ms-flex-negative: 0;
        flex-shrink: 0;
        color: #868993;
        border: 0px;
    }


    @media (max-width: 480px) {
        .cf-box-pagination {
            flex-direction: column-reverse;
        }
        .cf-box-pagination-info {
            margin-bottom: 9px;
            margin-top: 9px;
        }
    }
    /* end pagination */
</style>
<div class="cf-pagination-box js-pagination-box" data-action="<?= $action;?>" data-params="<?= $params;?>" data-container="<?= $container;?>">

	<?php if ( $show_more && $total_pages > 1 && $page_current != $total_pages) { ?>
		<div class="cf-more-load"><div class="cf-button-pagination js-cf-pagination-more" data-page="<?= $page_current+1;?>">Показать еще</div></div>
	<?php } ?>

	<a class="cf-box-pagination">
		<ul class="cf-pagination-list">
			<?php if ($total_pages > 1 )  {
                if ($page_current  != 1) {?>
					<li class="cf-pagination-list__item ">
						<a href="<?= $url;?>&pnum=<?= $page_current-1;?>" class="cf-button cf-pagination-list__button js-cf-pagination-page"  data-page="<?= $page_current-1;?>">
                            <div class="cf-pagination-prev"><</div>
                        </a>
					</li>
			<?php } ?>


			<li class="cf-pagination-list__item ">
				<a href="<?= $url;?>&pnum=1" class="cf-button cf-pagination-list__button <?=  (1 == $page_current ? "button" : "") ?> js-cf-pagination-page" data-page="1">1</a>
			</li>

			<?php  if ($page_current >= 5 ) { ?>
			<li class="cf-pagination-list__item ">
				...
			</li>
			<?php  } ?>

			<?php  if ( $total_pages >  2 ) {  ?>

				<?php  $start = $page_current - 2 > 2 ? $page_current - 2 : 2 ?>
				<?php  $end = $page_current + 2 < $total_pages - 1 ? $page_current + 2 : $total_pages - 1 ?>


				<?php  for(  $i = $start; $i <= $end; $i++){ ?>

				<li class="cf-pagination-list__item  ">
					<a href="<?= $url;?>&pnum=<?= $i;?>" class="cf-button cf-pagination-list__button <?=  ($i == $page_current ? "button" : "") ?> js-cf-pagination-page" data-page="<?= $i;?>"><?= $i;?></a>
				</li>

				<?php  } ?>

			<?php  } ?>



			<?php  if ($total_pages > 5 and $page_current + 3 <  $total_pages) {  ?>
			<li class="cf-pagination-list__item ">
				...
			</li>
			<?php  } ?>


			<li class="cf-pagination-list__item  ">
				<a href="<?= $url;?>&pnum=<?= $total_pages;?>" class="cf-button cf-pagination-list__button <?=  ($total_pages == $page_current ? "button" : "") ?> js-cf-pagination-page" data-page="<?= $total_pages;?>"><?= $total_pages;?></a>
			</li>


			<?php  if ($page_current != $total_pages) { ?>

			<li class="cf-pagination-list__item ">
                <a href="<?= $url;?>&pnum=<?= $page_current+1;?>" class="cf-button cf-pagination-list__button js-cf-pagination-page" data-page="<?= $page_current+1;?>"><div class="cf-pagination-next">></div></a>
			</li>

			<?php  } ?>


		</ul>

		<?php  if ($total_items > 0 ) { ?>
		<?php       $items_start = ($limit * ($page_current - 1)) + 1; ?>
		<?php       $items_end = ($limit * ($page_current-1)) + $limit;
				} ?>
		<?php  if ($items_end > $total_items) {  ?>
		<?php       $items_end = $total_items; ?>
		<?php  } ?>

		<?php  if (isset($show_count) && $show_count) { ?>
		<div class="cf-box-pagination-info">
			<?php  if ( $limit ) { ?>
			<div class="cf-pagination-count-items">Показано <span><?= $items_start;?> - <?= $items_end;?> </span> из <span><?= $total_items;?></span></div>
			<?php  } ?>
			<div class="cf-pagination-sort__group">
				<div  class="cf-pagination-sort__group__label">На странице: <?= $limit;?></div>
				<?php /*
				<select class="cf-pagination-limit js-pagination-limit">
					<option <?= $limit == "12" ? "selected" : "" ?> value="12">12</option>
					<option <?= $limit == "24" ? "selected" : "" ?> value="24">24</option>
					<option <?= $limit == "48" ? "selected" : "" ?> value="48">48</option>
					<option <?= $limit == "102" ? "selected" : "" ?> value="102">102</option>
				</select>
            */?>
			</div>
		</div>

		<?php  } ?>

	<?php  } ?>
	</div>
</div>