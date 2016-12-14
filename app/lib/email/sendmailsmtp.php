<?php
/**
* SendMailSmtp
* 
* Класс для отправки писем через SMTP с авторизацией
* Может работать через SSL протокол
* Тестировалось на почтовых серверах yandex.ru, mail.ru и gmail.com
* 
* @author Ipatov Evgeniy <admin@ipatov-soft.ru>
* @version 1.0
*/

define('SENDMAILSMTP_PHP', 0);

class SendMailSmtp {

    /**
    * 
    * @var string $smtp_username - логин
    * @var string $smtp_password - пароль
    * @var string $smtp_host - хост
    * @var string $smtp_from - от кого
    * @var integer $smtp_port - порт
    * @var string $smtp_charset - кодировка
    *
    */   
    public $smtp_username;
    public $smtp_password;
    public $smtp_host;
    public $smtp_from;
    public $smtp_port;
    public $smtp_charset;
    
    public function __construct($smtp_username, $smtp_password, $smtp_host, $smtp_from, $smtp_port = 25, $smtp_charset = "utf-8") {
        $this->smtp_username = $smtp_username;
        $this->smtp_password = $smtp_password;
        $this->smtp_host = $smtp_host;
        $this->smtp_from = $smtp_from;
        $this->smtp_port = $smtp_port;
        $this->smtp_charset = $smtp_charset;
    }
    
    /*
    * Отправка письма
    * 
    * @param string $mailTo - получатель письма
    * @param string $subject - тема письма
    * @param string $message - тело письма
    * @param string $headers - заголовки письма
    *
    * @return bool|string В случаи отправки вернет true, иначе текст ошибки
    */

    function send($mailTo, $subject, $message, $headers) {  /* throwable MVCException */
        $contentMail = "Date: " . date("D, d M Y H:i:s") . " UT\r\n";
        $contentMail .= 'Subject: =?' . $this->smtp_charset . '?B?'  . base64_encode($subject) . "=?=\r\n";
        $contentMail .= $headers . "\r\n";
        $contentMail .= $message . "\r\n";

        if(!$socket = @fsockopen($this->smtp_host, $this->smtp_port, $errorNumber, $errorDescription, 30)){
            throw new MVCException($errorNumber.".".$errorDescription);
        }
        if (!$this->_parseServer($socket, "220")){
            throw new MVCException(E_CONNECTION_ERROR);
        }

        $server_name = $_SERVER["SERVER_NAME"];
        fputs($socket, "HELO $server_name\r\n");
        if (!$this->_parseServer($socket, "250")) {
            fclose($socket);
            throw new MVCException('Error of command sending: HELO');
        }

        fputs($socket, "AUTH LOGIN\r\n");
        if (!$this->_parseServer($socket, "334")) {
            fclose($socket);
            throw new MVCException(E_AUTH_ERROR);
        }

        fputs($socket, base64_encode($this->smtp_username) . "\r\n");
        if (!$this->_parseServer($socket, "334")) {
            fclose($socket);
            throw new MVCException(E_AUTH_ERROR);
        }

        fputs($socket, base64_encode($this->smtp_password) . "\r\n");
        if (!$this->_parseServer($socket, "235")) {
            fclose($socket);
            throw new MVCException(E_AUTH_ERROR);
        }

        fputs($socket, "MAIL FROM: <".$this->smtp_username.">\r\n");
        if (!$this->_parseServer($socket, "250")) {
            fclose($socket);
            throw new MVCException(E_SENDING_MAIL_FROM_ERROR);
        }

        $mailTo = ltrim($mailTo, '<');
        $mailTo = rtrim($mailTo, '>');
        fputs($socket, "RCPT TO: <" . $mailTo . ">\r\n");
        if (!$this->_parseServer($socket, "250")) {
            fclose($socket);
            throw new MVCException(E_SENDING_RCPT_TO_ERROR);
        }

        fputs($socket, "DATA\r\n");
        if (!$this->_parseServer($socket, "354")) {
            fclose($socket);
            throw new MVCException(E_DATA_ERROR);
        }

        fputs($socket, $contentMail."\r\n.\r\n");
        if (!$this->_parseServer($socket, "250")) {
            fclose($socket);
            throw new MVCException(E_EMAIL_DIDNT_SENT);
        }

        fputs($socket, "QUIT\r\n");
        fclose($socket);

        return true;
    }
    
    private function _parseServer($socket, $response) {
        while (@substr($responseServer, 3, 1) != ' ') {
            if (!($responseServer = fgets($socket, 256))) {
                return false;
            }
        }
        if (!(substr($responseServer, 0, 3) == $response)) {
            return false;
        }
        return true;
    }
}