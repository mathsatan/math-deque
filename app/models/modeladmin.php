<?php
define('MODEL_ADMIN_PHP', 0);

class ModelAdmin extends Model
{
    private $RESULT = null;

    public function __construct() {
    }
    public function getData() {
        return $this->RESULT;
    }
    public static function getImages($picCount = 10, $offset = 0){
        if (filter_var($offset, FILTER_VALIDATE_INT) != $offset ||
            filter_var($picCount, FILTER_VALIDATE_INT) != $picCount){
            throw new MVCException(E_INCORRECT_DATA);
        }
        try {
            $opt = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8", PDO::ATTR_EMULATE_PREPARES => false);
            $dbh = new PDO("mysql:host=" . HOST . ";dbname=" . DATA_BASE, LOGIN, PASS, $opt);

            $sth = $dbh->prepare("SELECT * FROM mvc_images LIMIT :offset, :num;");
            $sth->bindParam(':offset', $offset);
            $sth->bindParam(':num', $picCount);

            $sth->execute();
            $pics = $sth->fetchAll();
            $dbh = null;
        }
        catch(PDOException $e) {
            throw $e;
        }
        return $pics;
    }

    public static function getImgCount(){
        try {
            $opt = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8");
            $dbh = new PDO("mysql:host=" . HOST . ";dbname=" . DATA_BASE, LOGIN, PASS, $opt);

            $sth = $dbh->prepare("SELECT COUNT(*) FROM mvc_images;");
            $sth->execute();
            $count = $sth->fetch();
            $dbh = null;
        }
        catch(PDOException $e) {
            throw $e;
        }
        return $count['COUNT(*)'];
    }

    public static function getImageById($id){
        if (!FilterData::isCorrect($id, FilterData::CHECK_ID)){
            throw new MVCException(E_WRONG_ID);
        }
        try {
            $opt = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8");
            $dbh = new PDO("mysql:host=" . HOST . ";dbname=" . DATA_BASE, LOGIN, PASS, $opt);

            $sth = $dbh->prepare("SELECT * FROM mvc_images WHERE pic_id = :id");
            $sth->execute(array(':id' => $id));
            $pic = $sth->fetch();
            $dbh = null;
        }
        catch(PDOException $e) {
            throw $e;
        }
        return $pic;
    }

    public function loadImage($desc, $align, $alt, $imgURL = null){
        if (!file_exists('app/core/image.php'))
            throw new MVCException(E_CLASS_NOT_FOUND.': Image');
        include('app/core/image.php');

        if (!in_array($align, FilterData::$attrWhiteList))
            throw new MVCException(E_INCORRECT_DATA.': align');
        if (!FilterData::isPermitTags($desc, $tag)){
            throw new MVCException(E_DENY_HTML_TAGS.': '.$tag);
        }

        $alt = FilterData::makeCorrect($alt);

        if ($imgURL !== null){
            if ($this->isUrlFileExist(urlencode($imgURL)) === false){
                throw new MVCException(E_PICS_NOT_FOUND);
            }
            $w = $h = 0;
            SimpleImage::getSize($imgURL, $w, $h);
            if ($w < MIN_IMAGE_SIZE || $h < MIN_IMAGE_SIZE) {
                throw new MVCException(E_PIC_TOO_SMALL);
            }

            $name = uniqid();   //preg_replace('/.*\//', '', $imgURL);
            $path = '/img/article_pic/original/'.$name;
            $fullPath = $_SERVER['DOCUMENT_ROOT'].$path;

            if(file_exists($fullPath)){
                throw new MVCException(E_FILE_ALREADY_EXIST);
            }
            if(copy($imgURL, $fullPath) === false){
                throw new MVCException(E_CANT_LOAD_PIC);
            }
        }else{
            if($_FILES["article_image"]["error"] !== 0){
                throw new MVCException(E_LOADING_FILE_FAIL);
            }
            if($_FILES["article_image"]["size"] > MAX_UPLOAD_FILE_SIZE ){
                throw new MVCException(E_CRITICAL_FILE_SIZE);
            }
            $name = $_FILES["article_image"]["name"];
            $path = '/img/article_pic/original/'.$name;
            $fullPath = $_SERVER['DOCUMENT_ROOT'].$path;
            if(file_exists($fullPath)){
                throw new MVCException(E_FILE_ALREADY_EXIST);
            }
            if(is_uploaded_file($_FILES["article_image"]["tmp_name"])) {
                $w = $h = 0;
                SimpleImage::getSize($_FILES["article_image"]["tmp_name"], $w, $h);
                if ($w < MIN_IMAGE_SIZE || $h < MIN_IMAGE_SIZE) {
                    throw new MVCException(E_PIC_TOO_SMALL);
                }
                move_uploaded_file($_FILES["article_image"]["tmp_name"],  $fullPath);
            }else{
                throw new MVCException(E_LOADING_FILE_FAIL);
            }
        }
        $image = new SimpleImage();
        $image->load($fullPath);
        $image->resize(MIN_IMAGE_SIZE, MIN_IMAGE_SIZE);
        $image->save($_SERVER['DOCUMENT_ROOT'].'/img/article_pic/small/'.$name);

        try {
            $opt = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8");
            $dbh = new PDO("mysql:host=" . HOST . ";dbname=" . DATA_BASE, LOGIN, PASS, $opt);

            $sth = $dbh->prepare("INSERT INTO mvc_images (pic_path, pic_alt, pic_align, cap) VALUES (:path, :alt, :align, :cap)");
            $this->RESULT['is_success'] = $sth->execute(array(':path' => $path, ':alt' => $alt, ':align' => $align,  ':cap' => $desc));
            $dbh = null;
        }catch (PDOException $e) {
            throw $e;
        }
    }

    public function deleteImage($picId){
        if (!FilterData::isCorrect($picId, FilterData::CHECK_ID)){
            throw new MVCException(E_WRONG_ID);
        }
        try {
            $opt = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8");
            $dbh = new PDO("mysql:host=" . HOST . ";dbname=" . DATA_BASE, LOGIN, PASS, $opt);

            $sth = $dbh->prepare("SELECT pic_path FROM mvc_images WHERE pic_id = :pic_id;)");
            $sth->execute(array(':pic_id' => $picId));
            $this->RESULT['img'] = $sth->fetch();

            if(file_exists($_SERVER['DOCUMENT_ROOT'].$this->RESULT['img']['pic_path']) && !empty($this->RESULT['img']['pic_path'])){
                $this->RESULT['is_success'] = unlink($_SERVER['DOCUMENT_ROOT'].$this->RESULT['img']['pic_path']);
                $small = $_SERVER['DOCUMENT_ROOT'].preg_replace('/\/original\//', '/small/', $this->RESULT['img']['pic_path']);
                if(file_exists($small)){
                    $this->RESULT['is_success'] = unlink($small) && $this->RESULT['is_success'];
                }
            }
            $sth = $dbh->prepare("DELETE FROM mvc_images WHERE pic_id = :pic_id;)");
            $this->RESULT['is_success'] = $sth->execute(array(':pic_id' => $picId)) && $this->RESULT['is_success'];

            $dbh = null;
        }catch (PDOException $e) {
            throw $e;
        }
    }

    public function updateImage($imageId, $desc, $newAlign, $newAlt){
        if (empty($newAlign) || empty($imageId)) throw new MVCException(E_EMPTY_FIELD);
        if (!FilterData::isCorrect($imageId, FilterData::CHECK_ID)){
            throw new MVCException(E_WRONG_ID);
        }
        if (!FilterData::isPermitTags($desc, $tag)){
            throw new MVCException(E_DENY_HTML_TAGS.': '.$tag);
        }
        if (!in_array($newAlign, FilterData::$attrWhiteList))
            throw new MVCException(E_INCORRECT_DATA.': align');

        $newAlt = FilterData::makeCorrect($newAlt);

        try {
            $opt = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8");
            $dbh = new PDO("mysql:host=" . HOST . ";dbname=" . DATA_BASE, LOGIN, PASS, $opt);

            $sth = $dbh->prepare("UPDATE mvc_images SET pic_align = :align, pic_alt = :alt, cap = :cap WHERE pic_id = :id;");
            $this->RESULT['is_success'] = $sth->execute(array(':id' => $imageId, ':align' => $newAlign, ':alt' => $newAlt, ':cap' => $desc));

            $dbh = null;
        }catch (PDOException $e) {
            throw $e;
        }
    }

    protected function isUrlFileExist($url) {
        $file_headers = @get_headers($url);
        if($file_headers[0] == 'HTTP/1.1 404 Not Found') {
            return false;
        }
        else {
            return true;
        }
    }
}