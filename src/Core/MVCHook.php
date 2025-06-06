<?php

class MVCHook {

    static function run() {
        $args = func_get_args();
        return static::exec(...$args);
    }

}