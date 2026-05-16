<?php
function autoload($className){
    include_once __DIR__."/../class/{$className}.php";
}
spl_autoload_register('autoload');