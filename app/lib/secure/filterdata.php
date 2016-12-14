<?php
define('FILTER_DATA_PHP', 0);

/* Статический класс, проверяет/фильтрует данные */

/*
    Правила отбора корректных значений:
    1) id должен быть целым положительным числом
    2) Логин должен состоять из букв английского алфавита, цифр, знака подчеркивания (регистроензависимый)
    3) Пароль должен состоять из букв английского алфавита, цифр, знака подчеркивания,
    -, @, #, ., /, \, ?, ~, !, +, ^, %, $, &, *, ;, :, (запятая) (регистроензависимый)
    4) Почта проверяется фильтром
*/

class FilterData{
    const CHECK_ID = 1;
    const CHECK_LOGIN = 2;
    const CHECK_PASS = 3;
    const CHECK_MAIL = 4;
    const CHECK_STR_ID = 5;
    const CHECK_DATE = 6;

    private static $tagsWhiteList = array('pre', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'a', 'b', 's', 'strong', 'ul', 'ol', 'li', 'p', 'div', 'em', 'br', 'table','thead','tbody', 'tr', 'td', 'th', 'img');
    public static $sortOrdersWhiteList = array('ASC', 'DESC');
    public static $attrWhiteList = array('center', 'left', 'right');

        // возвращает true, если корректное значение, иначе — false
    public static function isCorrect($data, $mode = self::CHECK_ID) {
        switch($mode) {
            case self::CHECK_ID: if (filter_var($data, FILTER_VALIDATE_INT) && ($data > 0)) return 1; else return 0;
            case self::CHECK_LOGIN: return preg_match('/^[a-z0-9_]{3,32}$/i', $data);
            case self::CHECK_PASS: return preg_match('/^[a-z0-9\-@#\.\/\?,~!\+\^%\$&\*_\\;:]{3,32}$/i', $data);
            case self::CHECK_MAIL: if (filter_var($data, FILTER_VALIDATE_EMAIL) != null) return 1; else return 0;
            case self::CHECK_STR_ID: return preg_match('/^[a-z0-9]+([_|-]?[a-z0-9]+)*$/i', $data);
            case self::CHECK_DATE: return preg_match("/^\d{4}\-(0[1-9]|1[012])\-(0[1-9]|[12][0-9]|3[01]) [0-2][0-9](\:[0-5][0-9]){2}$/", $data);
            default: return 0;
        }
    }

    public static function isPermitTags($str, &$denyTag = null) {
        if (empty($str)) return 1;
        $found = array();
        preg_match_all('/<\s*([a-z]+[0-9]*)\s*/i', $str, $found, PREG_SET_ORDER);

        if (!empty($found)){
            foreach($found as $index => $tag){
                if (!in_array($tag[1], self::$tagsWhiteList)){
                    $denyTag = $tag[1];
                    return 0;
                }
            }
        }
        return 1;
    }

    // возвращает отфильтрованую $data
    public static function makeCorrect($data){
        if (get_magic_quotes_gpc()) // Получает текущую активную установку конфигурации "магических" кавычек gpc.
            $data = stripslashes($data);    // Удаляет экранирование символов, произведенное функцией addslashes (Экранирует спецсимволы в строке)
        $data = strip_tags($data);      // Удаляет HTML и PHP тэги из строки
        $data = trim($data);    // Удвлякт пробелы в начале и конце строки
        $data = htmlspecialchars($data, ENT_QUOTES);    // Преобразует специальные символы в HTML сущности
        return $data;
    }
}