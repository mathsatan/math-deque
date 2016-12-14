<?php
require 'templateexception.php';
define('TEMPLATE_PHP', 0);

class Template
{
    private $templateName;
    private $templateKeys;
    private $templatePath;

    private static $isDebug = FALSE;

    public static function setDebugMode($isDebug = TRUE)
    {
        self::$isDebug = $isDebug;
    }

    public function __construct($path = '', $fileName = '')
    {
        $this->templateKeys = array();
        $this->templateName = $fileName;
        $this->templatePath = $path;
    }

    public function addKey($keyName, $keyValue, $isConcat = false)
    {
        if (preg_match('/^[a-z0-9_]+$/i', $keyName)) {
            if ($keyValue instanceof self) {
                !$isConcat ? $this->templateKeys[$keyName] = $keyValue->parseTemplate():
                    $this->templateKeys[$keyName] .= $keyValue->parseTemplate();
            }
            else {
                !$isConcat ? $this->templateKeys[$keyName] = $keyValue:
                $this->templateKeys[$keyName] .= $keyValue;
            }
        }
        return $this;
    }

    public function __get($keyName)
    {
        if (array_key_exists($keyName, $this->templateKeys))
        {
            return $this->templateKeys[$keyName];
        }
        return NULL;
    }

    public function setTemplateName($name)
    {
        $this->templateName = $name;
    }

    public function setTemplatePath($path)
    {
        $this->templatePath = $path;
    }

    public function parseTemplate()
    {
        if (file_exists($this->templatePath . $this->templateName))
        {
            $content = file_get_contents($this->templatePath . $this->templateName);
            foreach ($this->templateKeys as $name => $value) {
                $content = str_replace('<#' . $name . '#>', $value, $content);
            }
            return (self::$isDebug) ? $content : $this->zip($content);

        }
        else {
            throw new TemplateException(E_TEMPLATE_FILE_DOESNT_EXIST.' (' . $this->templatePath . $this->templateName . ')');
        }
    }

    public function display()
    {
        print ($this->parseTemplate());
    }

    protected function escapeHtml($str)
    {
        if(is_array($str)) {
            foreach($str as &$strItem) {
                $strItem = $this->escapeHtml($strItem);
            }
        }
        else {
            $str = htmlspecialchars($str, ENT_QUOTES);
        }
        return $str;
    }

    protected function escapeUrl($str)
    {
        if(is_array($str)) {
            foreach($str as &$strItem) {
                $strItem = $this->escapeUrl($str);
            }
        }
        else {
            $str = htmlentities($str, ENT_QUOTES);
        }
        return $str;
    }

    private function zip($htmlText)
    {
        return (empty($htmlText)) ? $htmlText : str_replace(array("\t", "\n", "\r"), '', $htmlText);
    }

    public function clearKeys()
    {
        $this->templateKeys = array();
    }
} 