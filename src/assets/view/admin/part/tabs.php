<style>

    .fc-widget {
        margin-bottom: 20px;
        background-color: #fff;
        -webkit-box-shadow: 0 0 0 1px rgb(0 0 0 / 2%), 0 1px 4px #cfdee5;
        box-shadow: 0 0 0 1px rgb(0 0 0 / 2%), 0 1px 4px #cfdee5;
        margin-right: 20px;
        display: flex;
        gap: 1rem;
        align-items: center;
        justify-content: flex-start;
    }
    .cf-btn-tab, .cf-btn-tab:focus, .cf-btn-tab:hover  {
        border: none;
        -webkit-box-shadow: none;
        box-shadow: none;
        border-radius: 1.5px;
        fill: currentColor;
        line-height: 1;
        font-weight: 600;
        -webkit-transition: color .1s linear, background-color .1s linear, border-color .1s linear, -webkit-box-shadow .1s linear;
        transition: color .1s linear, background-color .1s linear, border-color .1s linear, -webkit-box-shadow .1s linear;
        transition: color .1s linear, background-color .1s linear, border-color .1s linear, box-shadow .1s linear;
        transition: color .1s linear, background-color .1s linear, border-color .1s linear, box-shadow .1s linear, -webkit-box-shadow .1s linear;
        display: inline-block;
        font-weight: 400;
        text-align: center;
        vertical-align: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
        border: 1px solid transparent;
        padding: 0.375rem 0.75rem;
        font-size: 1rem;
        line-height: 1.5;
        border-radius: 0.25rem;
        text-decoration: none;
    }

    /* end tabs */
</style>
<div class="fc-widget fc-widget-tabs " id="">
	<?php $tab_active = ( !empty($_GET["tab"]) ? $_GET["tab"] : ( !empty( $args["tabs"][0]["internalname"]) ? $args["tabs"][0]["internalname"] : "" ) ); ?>

	<?php foreach($args["tabs"] as $index => $tab) {?>  
					<a href="<?= $tab["link"];?>" class="cf-btn-tab  <?= ( !empty( $tab["internalname"] ) && $tab_active == $tab["internalname"] ?  "button-primary" : "" );?>"><?= $tab["name"];?></a>
	<?php } ?>
</div>