<?php

define ('DEFAULT_CONTROLLER', 'main');
define ('DEFAULT_ACTION', 'index');

class Route {
    public static function start() {
            // контроллер и действие по умолчанию
        $controllerClassName = DEFAULT_CONTROLLER;
        $action = DEFAULT_ACTION;

        $request = substr($_SERVER['REQUEST_URI'], 1);
        $routes = preg_split('/\//', $request, -1, PREG_SPLIT_NO_EMPTY);
        unset($request);

        if (!empty($routes[0])) {
            $controllerClassName = $routes[0];
        }
        if (!empty($routes[1]) ) {
            $action = $routes[1];
        }

            // добавляем префиксы
        $modelClassName = 'Model'.ucfirst($controllerClassName);
        $controllerClassName = 'Controller'.ucfirst($controllerClassName);
        $action = 'action_'.$action;

        // подцепляем файл с классом модели (файла модели может и не быть)
        $model_path = "app/models/".strtolower($modelClassName).'.php';

        if(file_exists($model_path)) {
            include $model_path;
            unset($model_path);
        }
            // подцепляем файл с классом контроллера
        $controller_path = 'app/controllers/'.strtolower($controllerClassName).'.php';
        if(file_exists($controller_path)) {
            include $controller_path;
        }
        else {
            throw new MVCException(E_CONTROLLER_FILE_DOESNT_EXIST.': '.$controller_path);
        }

        try {   // создаем контроллер
            $controller = new $controllerClassName;
            $p = self::getParams($routes);

            if ($p !== false)
                $controller->addParams($p);

            if(method_exists($controller, $action)) {
                    $controller->$action();
            }
            else {
                throw new MVCException(E_INCORRECT_ACTION);
            }
        }catch (PDOException $e1) {
            throw $e1;
        }catch (MVCException $e2) {
            throw $e2;
        }catch (TemplateException $e3) {
            throw $e3;
        }
    }

    private static function getParams($line){
        if(!empty($line[2]) ){
            if (count($line) % 2 != 0){
                throw new MVCException(E_INCORRECT_PARAMS);
            }
            $keys = $values = array();
            for($i = 2, $cnt = count($line); $i < $cnt; $i++){
                ($i % 2 == 0) ? $keys[] = $line[$i] : $values[] = $line[$i];
            }
            $params = array_combine($keys, $values);

            if (LINKS_TYPE === 1){
                try{
                    if (array_key_exists('cat_id', $params)){
                        $params['cat_id'] = ModelArticles::getCatIntId($params['cat_id']);
                    }
                    if (array_key_exists('article_id', $params)){
                        $params['article_id'] = ModelArticles::getArticleIntId($params['article_id']);
                    }
                }catch (PDOException $e) {
                    throw $e;
                }
            }
            return $params;
        }
       return false;
    }

    public static function ErrorPage($errorMSG){
       // $host = 'http://'.$_SERVER['HTTP_HOST'].'/';
        header('HTTP/1.1 404 Not Found');

        require 'app/controllers/controllererror.php';
        $controllerErr = new ControllerError($errorMSG);
        $controllerErr->action_index();
    }
} 