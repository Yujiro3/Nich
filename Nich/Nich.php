<?php
/**
 * Nichデータ取得
 *
 * @package         Nich
 * @author          Yujiro Takahashi <yujiro3@gamil.com>
 * @filesource
 */

/**
 * Nichクラス
 *
 * @package         Nich
 * @subpackage      Library
 * @author          Yujiro Takahashi <yujiro3@gamil.com>
 */
class Nich {
    /**
     * 板テーマURL
     * @const string
     */
    const SUBJECT_FORMAT = 'http://%s/%s/subject.txt';

    /**
     * スレッドURL
     * @const string
     */
    const THREAD_FORMAT = 'http://%s/%s/dat/%d.dat';

    /**
     * 2chメニューURL
     * @const string
     */
    const BBSMENU = 'http://menu.2ch.net/bbsmenu.html';

    /**
     * エンコード
     * @const integer
     */
    const ENCODING = 'gzip,deflate';

    /**
     * ユーザーエージェント
     * @const integer
     */
    const USERAGENT = 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:8.0) Gecko/20100101 Firefox/8.0';

    /**
     * タイムアウト
     * @const integer
     */
    const TIMEOUT = 4;

    /**
     * スレッド名
     * @var string
     */
    public $thread;

    /**
     * ホスト名
     * @var string
     */
    public $host;

    /**
     * 板名
     * @var string
     */
    public $board;

    /**
     * カテゴリリストの取得
     *
     * @access public
     * @return array
     */
    public function getCategories() {
        return include dirname(__FILE__).'/Data/Category.php';
    }

    /**
     * スレッドリストの取得
     *
     * @access public
     * @return array
     */
    public function getThreads() {
        return include dirname(__FILE__).'/Data/Threads/'.$this->thread.'.php';
    }

    /**
     * スレッドリストの作成
     *
     * @access public
     * @return array
     */
    public function makeThreads() {
        $function = include dirname(__FILE__).'/Parse/Menu.php';
        $categories = $this->getCategories();

        return $function(self::BBSMENU, $categories, dirname(__FILE__).'/Data/Threads/');
    }

    /**
     * 板タイトルの取得
     *
     * @access public
     * @return array
     */
    public function getSubjects() {
        $subject  = $this->_getContents(sprintf(self::SUBJECT_FORMAT, $this->host, $this->board));
        $lines    = explode("\n", $subject);
        $subjects = array();

        foreach ($lines as $line) {
            if (preg_match('/^([\d]+)\.dat\<\>(.*) \(([\d]+)\)$/', $line, $match)) {
                $subjects[$match[1]] = array(
                    'subject'=> $match[2],
                    'res'=>     $match[3],
                );
            }
        }

        return $subjects;
    }

    /**
     * 板内容の取得
     *
     * @access public
     * @return array スレッド
     */
    public function getBoard() {
        $thread = $this->_getContents(sprintf(self::THREAD_FORMAT, $this->host, $this->board, $this->id));

        $list   = explode("\n", $thread);
        $result = array();

        foreach ($list as $line) {
            $line = trim($line);

            if (!empty($line)) {
                $result[] = explode('<>', $line);
            }
        }

        return $result;
    }

    /**
     * スレッドリストの作成
     *
     * @access public
     * @return array
     */
    public function getThread() {
        $function = include dirname(__FILE__).'/Parse/Response.php';
        $list     = $this->getBoard();

        return $function($list);
    }

    /**
     * 2chサーバからコンテンツを取得する
     *
     * @access private
     * $param string  $url
     * @return mixed
     */
    private function _getContents($url) {
        /* gzipを有効にする */
        $headers = array('Accept-Encoding: '.self::ENCODING);

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HTTPHEADER,     $headers);
        curl_setopt($curl, CURLOPT_HEADER,         false);
        curl_setopt($curl, CURLOPT_USERAGENT,      self::USERAGENT);
        curl_setopt($curl, CURLOPT_ENCODING,       self::ENCODING);
        curl_setopt($curl, CURLOPT_TIMEOUT,        self::TIMEOUT);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);

        /* 送信 */
        $result = curl_exec ($curl);
        $code   = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
    
        if (200 != $code) {
            $this->error = 'HTTP Code : '.$code;
            echo $this->error."\n";
            return false;
        }
        return mb_convert_encoding($result, 'UTF-8', 'SJIS-WIN');
    }

} // class Nich
