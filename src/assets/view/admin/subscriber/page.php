<div class="cf-container">

    <?= View::admin_part( "header",[
        "title" => $title,
        "btn" => "",
        "action" => ""
    ] );?>

    <?= View::admin_part( "tabs",  ["tabs" => [
            [
                "name" => "Подписчики",
                "link" => get_admin_url( null, "admin.php?page=fc-subscriber"),
                "internalname" => "index"
            ],
            [
                "name" => "Подтвердил подписку",
                "link" => get_admin_url( null, "admin.php?page=fc-subscriber&tab=confirmed"),
                "internalname" => "confirmed"
            ],
            [
                "name" => "Платные подписчики",
                "link" => get_admin_url( null, "admin.php?page=fc-subscriber&tab=paid"),
                "internalname" => "paid"
            ],
            [
                "name" => "Пользователи",
                "link" => get_admin_url( null, "admin.php?page=fc-subscriber&tab=userid"),
                "internalname" => "userid"
            ]
        ]
    ] );
    ?>


    <?= View::admin_part( "table_list", [
        "columns" => $columns,
        "items" => $items,
        "action_edit" => $action,
        "table" => $table,
        "action_delete" => "cf-delete-item",
        "form_size" => ( isset($form_size) ? $form_size : "midi")
    ] );?>

    <?= View::admin_part( "pagination", [
        "show_more" => false,
        "url" => "admin.php?page=fc-subscriber",
        "action" => $action_pagination,
        "params" => "",
        "container" => "cf-the-list",
        "total_pages" => $pagination["total_pages"],
        "total_items" => $pagination["total_items"] ,
        "limit" => $pagination["limit"] ,
        "page_current" => $pagination["page_current"] ,
        "show_count" => true
    ] );
    ?>

</div>


<?= View::admin_part( "popup");?>

<?= View::admin_part( "sources", ["scripts" => $scripts, "styles" => $styles ]);?>

