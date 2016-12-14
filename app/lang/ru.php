<?php
// LABELS
define ('L_SITE_TITLE', 'math-deque.rhcloud.com');
define ('L_TITLE', 'Высшая математика на доступном языке');

    // top menu
/*define ('L_MENU', 'Основное меню');*/
define ('L_MAIN', 'Главная');
define ('L_ARTICLES', 'Статьи по категориям');
define ('L_ORDER', 'Заказать работу');
define ('L_FORMULA', 'Формулы');
define ('L_ABOUT', ' О проекте');
define ('L_CALC', 'Калькулятор');
define ('L_CALC_HINT', 'Посчитать производную онлайн');
define ('L_CALC_DERIVATIVE', 'Аналитическое вычисление производной функции');

    // categories
define ('L_NO_ARTICLES_FOUND', 'Не найдено статей по данной категории');

    // sign in & sign out
define ('L_LOGIN', 'Войти');
define ('L_OUT', 'Выйти');
define ('L_REG', 'Регистрация');
define ('L_HELLO', 'Привет');
define ('L_REG_PAGE', 'Страница регистрации');
define ('L_LOGIN_PAGE', 'Страница авторизации');
define ('L_USER_LOGIN', 'Логин');
define ('L_USER_PASS', 'Пароль');
define ('L_USER_PASS_REPEAT', 'Повторите пароль');
define ('L_USER_MAIL', 'Почта');
define ('L_SUBMIT', 'Отправить');
define ('E_USER_NOT_ACTIVE', 'Пользователь неактивен');
define ('E_USER_FORGOT_PASS', 'Забыли пароль?');
define ('E_WRONG_CAPTCHA', 'Ошибка в ReCaptcha');
define ('E_PASSWORDS_DOESNT_MATCH', 'Введенные пароли не совпадают');
define ('L_QUERY', 'Запрос');

    // news
define ('L_NEWS', 'Новые статьи по высшей математике');
define ('L_SOCIAL_NETWORKS', 'Мы в социальных сетях');

    // error page
define ('L_ERROR', 'Ошибка');
/*define ('L_REPORT_ADMIN', 'Сообщить админу');*/

    // admin
define ('L_ADMIN_PANEL', 'Админ панель');
define ('L_USER_LIST', 'Список пользователей');
define ('L_ADD_USER', 'Добавить пользователя');
define ('L_USER_ID', 'ID');
define ('L_USER_STATUS', 'Статус');
define ('L_USER_IS_ACTIVE', 'Активен');
define ('L_USER_UPDATE', 'Обновить пользователя');
define ('L_USER_DELETE', 'Удалить пользователя');
define ('L_USER_RESTORE_PASS', 'Восстановить пароль');

// admin.articles
define ('L_ARTICLES_LIST', 'Список статей');
define ('L_ADD_ARTICLE', 'Добавить статью');
define ('L_ARTICLE_ID', 'ID');
define ('L_ARTICLE_TITLE', 'Титул статьи');
define ('L_ARTICLE_STRING_ID', 'Строковый ID');
/*define ('L_ARTICLE_BRIEFLY', 'Кратко');*/
define ('L_ARTICLE_UPDATE', 'Изменить статью');
define ('L_ARTICLE_DELETE', 'Удалить статью');

// admin menu
define ('L_ADMIN_MENU', 'Админ меню');
define ('L_USER_MANAGEMENT', 'Управление пользователями');
define ('L_ARTICLES_MANAGEMENT', 'Управление статьями');

define ('L_PIC_LIST', 'Список изображений');
define ('L_PIC_VIEW', 'Изображения');
define ('L_PIC_CODE', 'Код для вставки');
define ('L_PIC_UPDATE', 'Изменить изображение');
define ('L_PIC_DELETE', 'Удалить изображение');
define ('L_PIC_ADD', 'Добавить изображение');
define ('L_PIC_ALIGN', 'Выравнивание');
define ('L_PIC_ALT', 'Тег alt');
define ('L_PIC_HINT_OPEN', 'Открыть в полный размер');
define ('L_PIC_DESC', 'Подпись к изображению');

// articles
define ('L_NO_COMMENTS', 'Нет комментариев');
define ('L_COMMENTS', 'Комментарии:');
define ('L_YOUR_COMMENT', 'Ваш комментарий');
define ('L_ARTICLE_AUTHOR', 'Автор статьи');
define ('L_ARTICLE_TEXT', 'Текст статьи');
define ('L_ARTICLE_CAT', 'Категория');
define ('L_DATE', 'Дата');
define ('L_LOAD_PIC', 'Загрузить изображение');
define ('L_URL_PIC', 'Изображение по URL');
define ('L_PUBLISH', 'Опубликовать');
define ('L_SHARE', 'Поделиться');   // не используется
define ('L_TAGS', 'Теги');
define ('L_DESCRIPTION', 'Описание');

// INFO
define ('I_LOGIN_SUCCESS', 'Авторизация прошла успешно');
define ('I_REG_SUCCESS', 'Регистрация прошла успешно');
// admin
define ('I_UPDATE_SUCCESS', 'Обновление прошло успешно');
define ('I_DELETE_SUCCESS', 'Удаление прошло успешно');
define ('I_INSERT_SUCCESS', 'Добавление прошло успешно');

// ERRORS & EXCEPTIONS
define ('E_EMPTY_FIELD', 'Пустые поля!');
define ('E_USER_NOT_FOUND', 'Такого пользователя нет!');
define ('E_WRONG_LOGIN_OR_PASS', 'Неверный логин/пароль!');
define ('E_LOGIN_ALREADY_EXIST', 'Такой логин уже есть!');
define ('E_INVALID_EMAIL', 'Неверный почтовый адрес!');
define ('E_EMAIL_NOT_EXIST', 'Такой почтовый адрес не зарегестрирован!');
define ('E_NOT_ALLOWED', 'Недостаточно прав!');
define ('E_FAIL_GET_ARTICLES', 'Ошибка извлечения статей!');
define ('E_MODEL_FILE_DOESNT_EXIST', 'Файл модели не найден!');
define ('E_CONTROLLER_FILE_DOESNT_EXIST', 'Файл контроллера не найден!');
define ('E_TEMPLATE_FILE_DOESNT_EXIST', 'Файл шаблона не найден!');
define ('E_INCORRECT_ACTION', 'Неверное действие!');
define ('E_INCORRECT_PARAMS', 'Неверные параметры!');

define ('E_WRONG_ID', 'Неверный формат id');
define ('E_NO_ARTICLE_DATA', 'Отсутствуют данные статьи');
define ('E_INCORRECT_DATA', 'Неверные данные');
define ('E_DENY_HTML_TAGS', 'В тексте найдены запрещенные html теги');

// admin
define ('E_UPDATE_FAIL', 'Ошибка обновления');
define ('E_DELETE_FAIL', 'Ошибка удаления');
define ('E_INSERT_FAIL', 'Ошибка добавления');
// admin.articles
define ('E_ARTICLES_NOT_FOUND', 'Статей не найдено');
define ('E_WRONG_DATE', 'Некорректная дата');
define ('E_CRITICAL_FILE_SIZE', 'Слишком большой файл');
define ('E_LOADING_FILE_FAIL', 'Ошибка загрузки файла');
define ('E_WRONG_STR_ID', 'Некорректный строковый id');
define ('E_CLASS_NOT_FOUND', 'Класс не найден');
define ('E_PICS_NOT_FOUND', 'Изображения не найдены');
/*define ('E_FILE_NOT_FOUND', 'Файл не найден');*/
define ('E_CANT_LOAD_PIC', 'Не удалось загрузить изображение');
define ('E_FILE_ALREADY_EXIST', 'Файл с таким именем уже существует');
define ('E_PIC_TOO_SMALL', 'Слишком маленький размер изображения');
// smtp class
define ('E_CONNECTION_ERROR', 'Ошибка соединения');
define ('E_SENDING_HELO_ERROR', 'Ошибка команды: HELO');
define ('E_AUTH_ERROR', 'Ошибка авторизации');
define ('E_SENDING_MAIL_FROM_ERROR', 'Ошибка команды: MAIL FROM');
define ('E_SENDING_RCPT_TO_ERROR', 'Ошибка команды: RCPT TO');
define ('E_DATA_ERROR', 'Ошибка команды: DATA');
define ('E_EMAIL_DIDNT_SENT', 'E-mail не отправлен');

// SEO
define ('L_ARTICLES_MATH', 'Статьи по высшей математике. Примеры решения задач. Заказать контрольную работу');
define ('L_ORDER_WORK_MATH', 'Заказать работу по высшей математике');
define ('L_DONATE', 'Помощь проекту');
define ('L_FORMULA_DESC', 'Математика формулы');