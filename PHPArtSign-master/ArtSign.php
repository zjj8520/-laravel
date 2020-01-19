<?php

/**
 * ArtSign class
 * @author zhusaidong [zhusaidong@gmail.com]
 */
class ArtSign
{
    public $isConvertEncoding = false;
    private $fontListFile = null;
    private $fontPath = null;

    public function __construct($fontListFile, $fontPath)
    {
        $this->fontListFile = $fontListFile;
        $this->fontPath = $fontPath;
    }

    /**
     * get base url
     *
     * @return string base url
     */
    public function getBaseUrl()
    {
//	    ($_SERVER['REQUEST_SCHEME'] ?? 'http').'://'. 这段代码在本地的时候可以注释 线上可以用
        return $_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT'] . dirname($_SERVER["REQUEST_URI"]);
    }

    /**
     * get font lists
     *
     * @return array font lists
     */
    public function getFontLists()
    {
        if (!is_file($this->fontListFile)) {
            return $this->getFontListsByDir();
        }

        $list = [];
        $data = parse_ini_file($this->fontListFile);
        $data = array_flip($data);
        foreach ($data as $key => $value) {
            $fontPath = $this->fontPath . $data[$key];
            is_file($fontPath) and $list[$key] = $fontPath;
        }
        return $list;
    }

    /**
     * get dir FontLists
     */
    private function getFontListsByDir()
    {
        if (PHP_OS == 'WINNT') {
            $this->isConvertEncoding = TRUE;
        }
        $list = [];
        foreach (glob($this->fontPath . '/*.ttf') as $value) {
            //windows下中文是个奇葩
            $this->isConvertEncoding and $value = mb_convert_encoding($value, 'utf-8', 'gbk');
            $list[pathinfo($value, PATHINFO_FILENAME)] = $value;
        }
        foreach (glob($this->fontPath . '/*.TTF') as $value) {
            //windows下中文是个奇葩
            $this->isConvertEncoding and $value = mb_convert_encoding($value, 'utf-8', 'gbk');
            $list[pathinfo($value, PATHINFO_FILENAME)] = $value;
        }
        return $list;
    }

    /**
     * get option html
     * @param array $fontList
     *
     * @return string html
     */
    public function getFontOption($fontList)
    {
        $option = '';
        foreach ($fontList as $key => $value) {
            $option .= '<option value="' . $key . '">' . $key . '</option>';
        }
        return $option;
    }
}
