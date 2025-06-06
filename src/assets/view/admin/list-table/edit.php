<?php /**
 * @var MVCListTableAdmin $table
 * @var string $id
 */?>
<style>
    .list-item-edit {
        padding-top: 20px;
    }
</style>
<div class="list-item-edit">
    <div class="postbox">
        <div class="inside">
            <?= $table->editForm($id);?>
        </div>
    </div>
</div>

