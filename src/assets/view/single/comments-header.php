
<style>
    .fc-comments-header {
        background: #E8F9FF;
    }
    .fc-comments-header-container {
        width: 730px;
        max-width: 100%;
        margin: 0px auto;
        padding: 40px 15px 0px;
    }
    .fc-comments-count-block {
        border-bottom: 1px solid #ccc;
    }
    .fc-comments-count {
        font-size: 14px;
        display: flex;
        align-items: center;
        padding-bottom: 40px;
    }
    .fc-comments-count span {
        color: #3aaa35;
        font-size: 36px;
        font-weight: 700;
        padding-right: 10px;
    }
</style>
<div class="fc-comments-header">
    <div class="fc-comments-header-container">
        <div class="fc-comments-count-block">
            <div class="fc-comments-count">
                <span><?= $approved_count;?></span>
                <?= Utils::get_padej($approved_count, [" комментариев"," комментарий"," комментария"]);?>
            </div>
        </div>
    </div>
</div>