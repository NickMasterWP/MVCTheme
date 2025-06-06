<?php /**
 * @var MarketingReportsController $table
 */
global $MVCTheme;
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
                <?= $MVCTheme::printField($field, $_REQUEST[$field["name"]] ?? "");?>
            <?php } ?>
            <?= View::adminPart("form/button", [
                "name" => "submit",
                "type" => "submit",
                "label" => "",
                "required" => false,
                "value" => "Фильтр"
            ]);?>
        </div>
    </form>
</div>