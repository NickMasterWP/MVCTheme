<?php /**
* @var MarketingReportsController $table
 * @var bool $hasAddButton
 * @var bool $hasSearch
 * @var bool $hasFilter
 */?>
<div class="wrap">
    <h1 class="wp-heading-inline"><?= $table->title() ?? "" ?></h1>
    <?php if ($hasAddButton ) {?>
        <a href="<?php echo admin_url('admin.php?page='. ($page ?? "") .'&action=add'); ?>" class="page-title-action"><?= $addTitle ?? "Добавить";?></a>
    <?php } ?>

    <?php if ($hasFilter) { ?>
        <?= View::admin("list-table/filter", ["table" => $table]);?>
    <?php } ?>

    <form method="post">
        <?php
        if ($hasSearch) {
            $table->search_box( $addTitle ?? "Поиск", 'search_id');
        }
        //var_dump($table->get_columns());
        $table->display();
        ?>
    </form>
</div>