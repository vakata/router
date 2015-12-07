<?php
namespace vakata\router\test;

class RouterTest extends \PHPUnit_Framework_TestCase
{
	protected static $router = null;

	public static function setUpBeforeClass() {
	}
	public static function tearDownAfterClass() {
	}
	protected function setUp() {
	}
	protected function tearDown() {
	}

	public function testCreate() {
		self::$router = new \vakata\router\Router($data);
		$this->assertEquals(true, self::$router->isEmpty());
		self::$router
			->get('/get', function () { return 1; })
			->get('/get', function () { return 2; })
			->post('post', function () { return 3; })
			->report('report', function () { return 4; })
			->options('options', function () { return 5; })
			->put('put', function () { return 6; })
			->patch('patch', function () { return 7; })
			->add('DELETE', function () { return 8; })
			->head('head', function () { return 9; })
			->add(['GET','POST'], '/mixed', function () { return 10; })
			->get('/nested/path', function () { return 11; })
			->get('/named/{*:named}', function ($arg) { return $arg['named']; })
			->get('/types/{i}', function ($arg) { return $arg[1]; })
			->get('/types/{a}', function ($arg) { return $arg[1]; })
			->get('/types/{h}', function ($arg) { return $arg[1]; })
			->get('/types/{*}', function ($arg) { return $arg[1]; })
			->get('/types/{**}', function ($arg) { return $arg[2]; })
			->get('/optional/{?i}', function ($arg) { return isset($arg[1]) ? $arg[1] : ''; })
			->with('block', function () { return false; })
				->get('', function () { return 12; })
			->with('with')
				->get('test', function () { return 13; })
			->with('')
				->get('regex/{(asdf|zxcv)}', function () { return 14; });
		$this->assertEquals(false, self::$router->isEmpty());
	}
	/**
	 * @depends testCreate
	 */
	public function testRoutes() {
		$this->assertEquals(2, self::$router->run('get'));
		$this->assertEquals(3, self::$router->run('post', 'POST'));
		$this->assertEquals(4, self::$router->run('report', 'REPORT'));
		$this->assertEquals(5, self::$router->run('options', 'OPTIONS'));
		$this->assertEquals(6, self::$router->run('put', 'PUT'));
		$this->assertEquals(7, self::$router->run('patch', 'PATCH'));
		$this->assertEquals(8, self::$router->run('delete/something', 'DELETE'));
		$this->assertEquals(9, self::$router->run('head', 'HEAD'));
		$this->assertEquals(10, self::$router->run('mixed', 'GET'));
		$this->assertEquals(10, self::$router->run('mixed', 'POST'));
		$this->assertEquals(11, self::$router->run('/nested/path/'));
		$this->assertEquals('name1', self::$router->run('named/name1'));
		$this->assertEquals('name2', self::$router->run('named/name2'));
		$this->assertEquals('1', self::$router->run('types/1'));
		$this->assertEquals('a', self::$router->run('types/a'));
		$this->assertEquals('a0', self::$router->run('types/a0'));
		$this->assertEquals('@', self::$router->run('types/@'));
		$this->assertEquals('#', self::$router->run('types/@/#'));
		$this->assertEquals('', self::$router->run('optional'));
		$this->assertEquals('1', self::$router->run('optional/1'));
		$this->assertEquals(false, self::$router->run('block'));
		$this->assertEquals(false, self::$router->run('block/1'));
		$this->assertEquals(13, self::$router->run('with/test'));
		$this->assertEquals(14, self::$router->run('regex/asdf'));
		$this->assertEquals(14, self::$router->run('regex/zxcv'));
	}
	/**
	 * @depends testCreate
	 */
	public function testInvalid() {
		$this->setExpectedException('\vakata\router\RouterException');
		self::$router->run('regex/qwer');
	}
}
