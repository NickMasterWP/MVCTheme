<?php
/**
* @var array $tabs
 * @var string $html
 */
?>
<style>
    .tabs {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-direction: column;
        background: #fff;
        padding: 30px 0px 20px;
        border-bottom: 1px solid  var(--silver);
        border-radius: var(--border-radius-form-el);
        width: 100%;
        gap: 30px;
    }
    .tab {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        width: 100%;
        border-top: 3px solid var(--silver);
        position: relative;
    }
    .tab-circle {
        width: 16px;
        height: 16px;
        background: var(--silver);
        border-radius: 50%;
        transform: translateY(-9px);
    }
    .tab-current {
        cursor: pointer
    }
    .tab-active {
        font-weight: 600;
        cursor: pointer
    }
    .tab-current:before {
        border-top: 3px solid var(--premium);
        width: 50%;
        position: absolute;
        content: " ";
        display: block;
        left: 0px;
        top: -3px;
    }
    .tab-current .tab-circle {
        background: var(--premium);
    }
    .tab-success {
        border-color: var(--premium);
        cursor: pointer;
    }
    .tab-success  .tab-circle {
        background: var(--premium);
    }
    .tab-label {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        padding: 0px 10px;
    }

    @media screen and (min-width: 1280px){

        .tabs {
            flex-direction: row;
            gap: 0px;
        }
    }
</style>
<div class="tabs">
    <?php foreach ($tabs as $tab) { ?>
        <div class="tab <?= $tab['success'] ? "tab-success js-click-ajax-html" : "" ?> <?= $tab['current'] ? "tab-current js-click-ajax-html" : "" ?> <?= $tab['active'] ? "tab-active js-click-ajax-html" : "" ?> "
             data-action="<?= $tab['action'];?>" data-html="<?= $html;?>" data-params="<?= isset($tab['params']) ? $tab['params'] : "";?>">
            <div class="tab-circle"></div>
            <div class="tab-label"><?= $tab['label'];?></div>
        </div>
    <?php } ?>
</div>