<?php
define('MODEL_USERS_PHP', 0);

if (file_exists('app/core/template.php')) {
    if (!defined('TEMPLATE_PHP')) include 'app/core/template.php';
}else {
    throw new MVCException(E_CLASS_NOT_FOUND.': Template');
}
if (file_exists('app/lib/secure/recaptchalib.php')) {
    if (!defined('RECAPTCHALIB_PHP')) include 'app/lib/secure/recaptchalib.php';
}
else {
    throw new MVCException(E_CLASS_NOT_FOUND.': ReCaptcha');
}

if (file_exists('app/lib/email/sendmailsmtp.php')) {
    if (!defined('SENDMAILSMTP_PHP')) include 'app/lib/email/sendmailsmtp.php';
}
else {
    throw new MVCException(E_CLASS_NOT_FOUND.': SendMailSmtp');
}

/* Обеспечивает работу с пользователями */
class ModelUsers extends Model
{
    private $RESULT = null;

    public function __construct(){
    }

    public static function getUserCount(){
        try {
            $opt = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8");
            $dbh = new PDO("mysql:host=" . HOST . ";dbname=" . DATA_BASE, LOGIN, PASS, $opt);

            $sth = $dbh->prepare("SELECT COUNT(*) FROM mvc_users;");
            $sth->execute();
            $count = $sth->fetch();
            $dbh = null;
        }
        catch(PDOException $e) {
            throw $e;
        }
        return $count['COUNT(*)'];
    }

    public function getUsers($rowCount = 10, $offset = 0){
        // add new
        if ($rowCount !== filter_var($rowCount, FILTER_VALIDATE_INT) || $offset !== filter_var($offset, FILTER_VALIDATE_INT)){
            throw new MVCException(E_INCORRECT_DATA);
        }
        try {
            $opt = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8");
            $dbh = new PDO("mysql:host=".HOST.";dbname=".DATA_BASE, LOGIN, PASS, $opt);
            $sth = $dbh->prepare("SELECT * FROM mvc_users LIMIT :offset, :num;");
            $sth->bindParam(':offset', $offset, PDO::PARAM_INT);
            $sth->bindParam(':num', $rowCount, PDO::PARAM_INT);
            $sth->execute();

            $this->RESULT['users'] = $sth->fetchAll();
            $dbh = null;
        }
        catch(PDOException $e) {
            throw $e;
        }
    }

    public function deleteUser($id){
        // add new
        if (!FilterData::isCorrect($id, FilterData::CHECK_ID)){
            throw new MVCException(E_INCORRECT_DATA);
        }

        try {
            $opt = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8");
            $dbh = new PDO("mysql:host=" . HOST . ";dbname=" . DATA_BASE, LOGIN, PASS, $opt);
            $sth = $dbh->prepare("DELETE FROM mvc_users WHERE  user_id = :id;");
            $this->RESULT['is_success'] = $sth->execute(array(':id' => $id));
            $dbh = null;
        }catch (PDOException $e) {
            throw $e;
        }
    }

    public function updateUser($id, $login, $pass, $mail, $status = 0, $isActive = 1){
        // add new
        $this->checkUserData($login, $pass, $mail, $status, $isActive);

        $user = $this->isUserExist($login);

        if (($user['user_id'] != $id) && ($login === $user['login']))
            throw new MVCException(E_LOGIN_ALREADY_EXIST);

        try {
            $opt = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8");
            $dbh = new PDO("mysql:host=" . HOST . ";dbname=" . DATA_BASE, LOGIN, PASS, $opt);
            if (empty($pass))  {
                $sth = $dbh->prepare("UPDATE mvc_users SET login = :login, email = :mail, status = :status, is_active = :is_active WHERE user_id = :id;");
                $this->RESULT['is_success'] = $sth->execute(array(':id' => $id, ':login' => $login, ':mail' => $mail, ':status' => $status, ':is_active' => $isActive));
            }
            else{
                $sth = $dbh->prepare("UPDATE mvc_users SET login = :login, pass = :pass, email = :mail, status = :status, is_active = :is_active WHERE user_id = :id;");
                $this->RESULT['is_success'] = $sth->execute(array(':id' => $id, ':login' => $login, ':pass' => MD5($pass), ':mail' => $mail, ':status' => $status, ':is_active' => $isActive));
            }
            $dbh = null;
        }catch (PDOException $e) {
            throw $e;
        }
    }

    public function signUp($login, $pass, $pass2, $mail, $reCaptchaResponse, $isAdmin = 0, $isActive = 1) {
        if (!$this->checkReCaptcha($reCaptchaResponse)){
            throw new MVCException(E_WRONG_CAPTCHA);
        }

        $this->checkUserData($login, $pass, $mail, $isAdmin, $isActive);
        if ($pass !== $pass2){
            throw new MVCException(E_PASSWORDS_DOESNT_MATCH);
        }
        $this->RESULT['user'] = $this->isUserExist($login);

        if (!empty($this->RESULT['user']))
            throw new MVCException(E_LOGIN_ALREADY_EXIST);

        try{
            $opt = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8");
            $dbh = new PDO("mysql:host=".HOST.";dbname=".DATA_BASE, LOGIN, PASS, $opt);
            $sth = $dbh->prepare("INSERT INTO mvc_users (user_id, login, pass, email, status, is_active) VALUES (NULL, :login, MD5(:pass), :mail, :status, :is_active)");
            $this->RESULT['is_success'] = $sth->execute(array(':login' => $login, ':pass' => $pass, ':mail' => $mail, ':status' => $isAdmin, ':is_active' => $isActive));
            $dbh = null;

            $mailSMTP = new SendMailSmtp(SYSTEM_EMAIL, SYSTEM_PASSWORD, 'ssl://smtp.yandex.ru', COMPANY, 465);
            $headers= "MIME-Version: 1.0\r\n";
            $headers .= "Content-type: text/html; charset=utf-8\r\n"; // кодировка письма
            $headers .= "From: ".SYSTEM_EMAIL."\r\n"; // от кого письмо
            $headers .= "To: ".$mail."\r\n"; // кому письмо

            $text = new Template('txt/', 'reg_success.htx');
            $text->addKey('login', $login);
            $text->addKey('pass', $pass);
            $text->addKey('company_name', COMPANY);

            $this->RESULT['is_success'] = $mailSMTP->send($mail, 'Sign up success', $text->parseTemplate(), $headers);
            unset($text);
        }catch (PDOException $e1){
            throw $e1;
        }catch (TemplateException $e2){
            throw $e2;
        }
    }
    private function tryToSignIn($login, $pass, $reCaptcha){
        if (!FilterData::isCorrect($login, FilterData::CHECK_LOGIN) || !FilterData::isCorrect($pass, FilterData::CHECK_PASS)){
            $this->RESULT['is_success'] = false;
            $this->RESULT['error_msg'] = E_WRONG_LOGIN_OR_PASS;
            return;
        }

        $user = $this->isUserExist($login);
        if (empty($user)){
            $this->RESULT['is_success'] = false;
            $this->RESULT['error_msg'] = E_USER_NOT_FOUND;
            return;
        }

        $this->RESULT['user_info'] = $user;

        if ($reCaptcha !== null){
            if (!$this->checkReCaptcha($reCaptcha)){
                $this->RESULT['is_success'] = false;
                $this->RESULT['error_msg'] = E_WRONG_CAPTCHA;
                return;
            }
        }
        if ($user['is_active'] == 0){
            $this->RESULT['is_success'] = false;
            $this->RESULT['error_msg'] = E_USER_NOT_ACTIVE;
            return;
        }
        if ($user['login'] === $login && $user['pass'] === md5($pass)) {
            $this->RESULT['is_success'] = true;
        }
        else{
            $this->RESULT['is_success'] = false;
            $this->RESULT['error_msg'] = E_WRONG_LOGIN_OR_PASS;
        }
    }
    public function signIn($login, $pass, $reCaptcha){
        $this->tryToSignIn($login, $pass, $reCaptcha);
        $userIP = sprintf('%u', ip2long($_SERVER['REMOTE_ADDR']));
        if ($this->RESULT['is_success'] === true){
            $this->deleteUserAttemptsNumber($userIP);
        }else{
            $this->incLoginAttemptsNumber($userIP);
        }
    }

    public function getAttemptsNumber($ip){
        $opt = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8");
        $dbh = new PDO("mysql:host=".HOST.";dbname=".DATA_BASE, LOGIN, PASS, $opt);
        $sth = $dbh->prepare("SELECT attempts_number FROM mvc_login_attempts WHERE user_ip = :ip;");
        $sth->execute(array(':ip' => $ip));
        $result = $sth->fetch();
        $dbh = null;
        if (empty($result['attempts_number']))
            return 0;
        else return $result['attempts_number'];
    }

    protected function deleteUserAttemptsNumber($ip){
        $opt = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8");
        $dbh = new PDO("mysql:host=".HOST.";dbname=".DATA_BASE, LOGIN, PASS, $opt);
        $sth = $dbh->prepare("DELETE FROM mvc_login_attempts WHERE user_ip = :ip;");
        $result = $sth->execute(array(':ip' => $ip));
        $dbh = null;
        return $result;
    }
    protected function incLoginAttemptsNumber($ip){
        try {
            $opt = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8");
            $dbh = new PDO("mysql:host=".HOST.";dbname=".DATA_BASE, LOGIN, PASS, $opt);
            $attemptsNumber = $this->getAttemptsNumber($ip);
            if ($attemptsNumber !== 0){
                $sth = $dbh->prepare("UPDATE mvc_login_attempts SET attempts_number = :num WHERE user_ip = :ip;");
                $sth->execute(array(':ip' => $ip, ':num' => $attemptsNumber + 1));
                $dbh = null;
            }else{
                $sth = $dbh->prepare("INSERT INTO mvc_login_attempts (user_ip, attempts_number) VALUES (:ip, 1);");
                $sth->execute(array(':ip' => $ip));
                $dbh = null;
            }
        } catch (PDOException $e){
            throw $e;
        }
    }

    public function getUserInfo($id){
        if (!FilterData::isCorrect($id, FilterData::CHECK_ID))
            throw new MVCException(E_WRONG_ID);

        try {
            $opt = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8");
            $dbh = new PDO("mysql:host=".HOST.";dbname=".DATA_BASE, LOGIN, PASS, $opt);
            $sth = $dbh->prepare("SELECT * FROM mvc_users WHERE user_id = :user_id");
            $sth->execute(array(':user_id' => $id));
            $this->RESULT['user_info'] = $sth->fetch();
            $dbh = null;
        } catch (PDOException $e) {
            throw $e;
        }
    }

    protected function isUserExist($login) {
        if (!FilterData::isCorrect($login, FilterData::CHECK_LOGIN)) return false;

        try {
            $opt = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8");
            $dbh = new PDO("mysql:host=".HOST.";dbname=".DATA_BASE, LOGIN, PASS, $opt);
            $sth = $dbh->prepare("SELECT * FROM mvc_users WHERE login = :login");
            $sth->execute(array(':login' => $login));
            $user = $sth->fetch();
            $dbh = null;
        }
        catch(PDOException $e) {
            throw $e;
        }
        if (empty($user))
            return false;
        else
            return $user;
    }

    public function isCurrentUserAdmin($id, $pass){ // add new
        if (!FilterData::isCorrect($id, FilterData::CHECK_ID) || !FilterData::isCorrect($pass, FilterData::CHECK_PASS)){
            return false;
        }

        try {
            $opt = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8");
            $dbh = new PDO("mysql:host=".HOST.";dbname=".DATA_BASE, LOGIN, PASS, $opt);
            $sth = $dbh->prepare("SELECT pass, status FROM mvc_users WHERE user_id = :id");
            $sth->execute(array(':id' => $id));
            $user = $sth->fetch();
            $dbh = null;
        }
        catch(PDOException $e) {
            throw $e;
        }
        if ($user['pass'] == $pass && $user['status'] == 1)
            return true;

        return false;
    }

    protected function checkUserData($login, $pass, $mail, $isAdmin = 0, $isActive = 1) {
        if (empty($login) || empty($pass) || empty($mail))
            throw new MVCException(E_EMPTY_FIELD);

        if (!FilterData::isCorrect($mail, FilterData::CHECK_MAIL))
            throw new MVCException(E_INVALID_EMAIL);

        if (!FilterData::isCorrect($pass, FilterData::CHECK_PASS) || !FilterData::isCorrect($login, FilterData::CHECK_LOGIN))
            throw new MVCException(E_WRONG_LOGIN_OR_PASS);

        if ($isAdmin !== filter_var($isAdmin, FILTER_VALIDATE_INT) || $isActive !== filter_var($isActive, FILTER_VALIDATE_INT))
            throw new MVCException(E_INCORRECT_DATA);
    }

    public function genNewPassword($email, $response){
        if(!$this->checkReCaptcha($response)){
            throw new MVCException(E_WRONG_CAPTCHA);
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
            throw new MVCException(E_INVALID_EMAIL);
        }

        try{
            $opt = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8");
            $dbh = new PDO("mysql:host=".HOST.";dbname=".DATA_BASE, LOGIN, PASS, $opt);
            $sth = $dbh->prepare("SELECT user_id, login FROM mvc_users WHERE email = :email");
            $sth->execute(array(':email' => $email));
            $user = $sth->fetch();
            if (empty($user['user_id'])){
                $this->RESULT['is_success'] = false;
                throw new MVCException(E_EMAIL_NOT_EXIST);
            }
            $newPass = uniqid();
            $sth = $dbh->prepare("UPDATE mvc_users SET pass = :pass WHERE user_id = :id;");
            $this->RESULT['is_success'] = $sth->execute(array(':pass' => MD5($newPass), ':id' => $user['user_id']));
            $dbh = null;

            $mailSMTP = new SendMailSmtp(SYSTEM_EMAIL, SYSTEM_PASSWORD, 'ssl://smtp.yandex.ru', COMPANY, 465);
            $headers= "MIME-Version: 1.0\r\n";
            $headers .= "Content-type: text/html; charset=utf-8\r\n";
            $headers .= "From: ".SYSTEM_EMAIL."\r\n";
            $headers .= "To: ".$email."\r\n";

            $text = new Template('txt/', 'password_restore.htx');
            $text->addKey('new_pass', $newPass);
            $text->addKey('login', $user['login']);
            $text->addKey('company_name', COMPANY);

            $this->RESULT['is_success'] = $mailSMTP->send($email, 'Password restore', $text->parseTemplate(), $headers);
            unset($text);
        }catch (MVCException $e1){
            throw $e1;
        }catch (PDOException $e2){
            throw $e2;
        }catch (TemplateException $e3){
            throw $e3;
        }
    }
    private function checkReCaptcha($reCaptchaResponse){
        $response = null;
        $reCaptcha = new ReCaptcha(RECAPTCHA_SECRET_KEY);
            // if submitted check response
        if ($reCaptchaResponse) {
            $response = $reCaptcha->verifyResponse($_SERVER["REMOTE_ADDR"], $reCaptchaResponse);
        }
        if ($response != null && $response->success) {
            return true;
        } else {
            return false;
        }
    }

    public function getData() {
        return $this->RESULT;
    }
}