<?php
require 'app/core/model.php';
require 'app/core/view.php';
require 'app/core/controller.php';
require 'app/core/route.php';
require 'app/core/mvcexception.php';
require 'app/config/db_config.php';

try{
    Route::start(); // запускаем маршрутизатор
}
catch (MVCException $e) {
    Route::ErrorPage($e->getMessage());
}
catch (PDOException $e2) {
    Route::ErrorPage($e2->getMessage());
}
catch (TemplateException $e3) {
    Route::ErrorPage($e3->getMessage());
}
