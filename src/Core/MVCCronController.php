<?php
 
class  MVCCronController
{

    public function run() {
         global $MVCTheme;
        $MVCTheme->error("Не настроен метод run для выполнения крона ");
    }

}