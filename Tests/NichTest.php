<?php
require_once './Nich/Nich.php';

/**
 * Nichテストクラス
 *
 * @package         Nichan
 * @subpackage      Library
 * @author          Yujiro Takahashi <yujiro3@gamil.com>
 */
class NichTest extends PHPUnit_Framework_TestCase {
    /**
     * Nich
     * @var object
     */
    protected $nichan;

    /**
     * コンストラクタ
     *
     * @access public
     * @return void
     */
    public function __construct() {
        $this->nich = new Nich();
    }

    /**
     * testMakeThreads
     *
     * @access public
     * @return void
     */
    public function testMakeThreads() {
        $result = $this->nich->makeThreads();
        $this->assertTrue($result);
    }

    /**
     * testGetCategories
     *
     * @access public
     * @return void
     */
    public function testGetCategories() {
        $list = $this->nich->getCategories();
        $this->assertEquals(42, count($list));
    }

    /**
     * testGetThreads
     *
     * @access public
     * @return void
     */
    public function testGetThreads() {
        $this->nich->thread = 'be';
        $list = $this->nich->getThreads();
        $this->assertEquals(3, count($list));
    }

    /**
     * testGetSubjects
     *
     * @access public
     * @return void
     */
    public function testGetSubjects() {
        $this->nich->host   = 'kohada.2ch.net';
        $this->nich->thread = 'be';
        $list = $this->nich->getSubjects();
        $this->assertEquals(705, count($list));
    }

    /**
     * testGetBoard
     *
     * @access public
     * @return void
     */
    public function testGetBoard() {
        $this->nich->host   = 'kohada.2ch.net';
        $this->nich->thread = 'be';
        $this->nich->id     = '1344518750';
        $list = $this->nich->getBoard();
        $this->assertEquals(184, count($list));
    }

    /**
     * testGetThread
     *
     * @access public
     * @return void
     */
    public function testGetThread() {
        $this->nich->host   = 'kohada.2ch.net';
        $this->nich->thread = 'be';
        $this->nich->id     = '1344518750';
        $list = $this->nich->getThread();
        $this->assertEquals(184, count($list));
    }
} // class NichTest extends PHPUnit_Framework_TestCase
