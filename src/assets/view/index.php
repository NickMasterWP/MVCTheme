<!doctype html>
<html prefix="og: http://ogp.me/ns#" lang="ru">
<head>
    <meta charset="UTF-8">
    <title><?= $title;?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head();?>
</head>
<body <?php  body_class();?>>

<body <?php  body_class();?>>

    <div id="main-wrapper" class="main-wrapper">

        <?= View::layout("header", ["menu" => $menu, "setting" => $setting ] );?>

        <?= $content;?>

        <?php  echo View::layout("footer", ["menu" => $menu, "setting" => $setting ] );?>

    </div>

	<?php wp_footer();?>

</body> 
</html>