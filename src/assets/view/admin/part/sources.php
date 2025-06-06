<?php

if (isset($scripts) && is_array($scripts) && count($scripts) > 0) {
	foreach($scripts as $script) {
		if (substr($script,0,4) != "http") {
			$script = CATALOG_FURNITURE_URL . $script;
		}
		echo "
<script type='text/javascript' src='$script'></script>";
	}
}


if (isset($styles) && is_array($styles) && count($styles) > 0) {
	foreach($styles as $style) {
		if (substr($style,0,4) != "http") {
			$style = CATALOG_FURNITURE_URL . $style;
		}
		echo "
<link rel='stylesheet' id='fancybox-css'  href='$style' type='text/css' media='all' />";
	}
}