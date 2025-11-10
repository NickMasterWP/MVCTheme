<?php /**
 * @var MarketingReportsController $table
 */
use MVCTheme\Core\MVCView;
use MVCTheme\MVCTheme;

?>
<style>
    .filter-fields {
        display: flex;
        gap: 20px;
    }
    .filter-container {
        margin-top: 20px;
        padding: 10px 20px 20px;
        background: #f9f9f9;
    }
</style>
<div class="filter-container">
    <form method="get" class="" action="<?= $table->getActionFilter();?>" >
        <input type="hidden" name="page" value="<?= $table->getPageName();?>">
        <div class="filter-fields">
            <?php foreach ($table->getFilterFields() as $field) { ?>
                <?= MVCTheme::printField($field, $_REQUEST[$field["name"]] ?? "");?>
            <?php } ?>
            <div class="filter-submit">
                <?= MVCView::adminPart("form/button", [
                    "name" => "submit",
                    "type" => "submit",
                    "label" =>  "",
                    "required" => false,
                    "value" => __("Filter","mvctheme")
                ]);?>
            </div>

        </div>
    </form>
</div>