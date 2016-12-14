<?php
define('SESSION_PHP', 0);

class Session{
    private $dbh = null;
    private $r_sth = null;
    private $w_sth = null;
    private $isRun = false;

    public function isSessionRun(){
        return $this->isRun;
    }

    public function __construct(){
        // set our custom session functions.
        session_set_save_handler(array($this, 'open'), array($this, 'close'), array($this, 'read'), array($this, 'write'), array($this, 'destroy'), array($this, 'gc'));

        // This line prevents unexpected effects when using objects as save handlers.
        register_shutdown_function('session_write_close');
    }

    public function start_session($session_name, $isHTTPS = false) {
        if ($this->isRun){
            return;
        }
		ini_set('session.gc_divisor', '10');		

        // Make sure the session cookie is not accessible via javascript.
        $httponly = true;

        // Hash algorithm to use for the session. (use hash_algos() to get a list of available hashes.)
        $session_hash = 'sha512';

        // Check if hash is available
        if (in_array($session_hash, hash_algos())) {
            // Set the has function.
            ini_set('session.hash_function', $session_hash);
        }
        // How many bits per character of the hash.
        // The possible values are '4' (0-9, a-f), '5' (0-9, a-v), and '6' (0-9, a-z, A-Z, "-", ",").
        ini_set('session.hash_bits_per_character', 5);

        // Force the session to only use cookies, not URL variables.
        ini_set('session.use_only_cookies', 1);

        // Get session cookie parameters
        $cookieParams = session_get_cookie_params();
        // Set the parameters
        session_set_cookie_params($cookieParams["lifetime"], $cookieParams["path"], $cookieParams["domain"], $isHTTPS, $httponly);
        // Change the session name
        session_name($session_name);
        // Now we cat start the session
        session_start();
        // This line regenerates the session and delete the old one.
        // It also generates a new encryption key in the database.
        session_regenerate_id(true);
        $this->isRun = true;
    }

    public function open() {
        $opt = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8");
        $this->dbh = new PDO("mysql:host=".HOST.";dbname=".DATA_BASE, LOGIN, PASS, $opt);
    }

    public function close() {
        $this->dbh = null;
        return true;
    }

    public function read($id) {
        if(!isset($this->r_sth)) {
            $this->r_sth = $this->dbh->prepare("SELECT data FROM mvc_secure_sessions WHERE id = :id LIMIT 1");
        }

        $this->r_sth->execute(array(':id' => $id));
        $data = $this->r_sth->fetch(PDO::FETCH_LAZY);
		
        $key = $this->getkey($id);
		
        $data = $this->decrypt($data->data, $key);
        return $data;
    }
    public function write($id, $data) {
        // Get unique key
        $key = $this->getkey($id);
        // Encrypt the data
        $data = $this->encrypt($data, $key);

        $time = time();
        if(!isset($this->w_sth)) {
            $this->w_sth = $this->dbh->prepare("REPLACE INTO mvc_secure_sessions (id, set_time, data, session_key) VALUES (:id, :t, :data, :key)");
        }
        $this->w_sth->execute(array(':id' => $id, ':t' => $time, ':data' => $data, ':key' => $key));
        return true;
    }

    public function destroy($id) {
        if(!isset($this->delete_stmt)) {
            $this->delete_stmt = $this->dbh->prepare("DELETE FROM mvc_secure_sessions WHERE id = :id");
        }
        $this->delete_stmt->execute(array(':id' => $id));
        $this->isRun = false;
        return true;
    }

    public function gc($max) {
        if(!isset($this->gc_stmt)) {
            $this->gc_stmt = $this->dbh->prepare("DELETE FROM mvc_secure_sessions WHERE set_time < :t");
        }
        $old = time() - $max;
        $this->gc_stmt->execute(array(':t' => $old));
		error_log("gc() executed\n", 3, "/session.log");
        return true;
    }

    private function getkey($id) {
        if(!isset($this->key_stmt)) {
            $this->key_stmt = $this->dbh->prepare("SELECT session_key FROM mvc_secure_sessions WHERE id = :id LIMIT 1");
        }
        $this->key_stmt->execute(array(':id' => $id));
        $res = $this->key_stmt->fetch(PDO::FETCH_LAZY);

        if($res !== false && !empty($res->session_key)){
            return $res->session_key;
        } else {
            $random_key = hash('sha512', uniqid(mt_rand(1, mt_getrandmax()), true));
            return $random_key;
        }
    }

    private function encrypt($data, $key){
        $key = substr(hash('sha256', SALT_CODE.$key.SALT_CODE), 0, 32);
        $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        $encrypted = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $data, MCRYPT_MODE_ECB, $iv));
        return $encrypted;
    }
    private function decrypt($data, $key){
        $key = substr(hash('sha256', SALT_CODE.$key.SALT_CODE), 0, 32);
        $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        $decrypted = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, base64_decode($data), MCRYPT_MODE_ECB, $iv);
        $decrypted = rtrim($decrypted, "\0");
        return $decrypted;
    }
}