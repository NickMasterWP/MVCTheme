<?php
/**
 * @var string $type
 * @var string $name
 * @var string $label
 * @var bool $required
 * @var string $value
 * @var string $class
 * @var string $placeholder
 * @var string $disabled
 */
?>
<?php

echo View::form("input", ["type" => "number",
    "name" => $name ?? "",
    "label" => $label ?? "",
    "required" => $required ?? "",
    "value" => $value ?? "",
    "class" => $class ?? "",
    "placeholder" => $placeholder ?? "",
    "disabled" => $disabled ?? ""
]);

?>