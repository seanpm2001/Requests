<?php

namespace WpOrg\Requests\Tests\Response;

use WpOrg\Requests\Exception;
use WpOrg\Requests\Response\Headers;
use WpOrg\Requests\Tests\TestCase;

final class HeadersTest extends TestCase {

	public function testOffsetSetInvalidKey() {
		$this->expectException(Exception::class);
		$this->expectExceptionMessage('Object is a dictionary, not a list');

		$headers   = new Headers();
		$headers[] = 'text/plain';
	}

	/**
	 * Test array access for the object is supported and supported in a case-insensitive manner.
	 *
	 * @dataProvider dataCaseInsensitiveArrayAccess
	 *
	 * @param string $key Key to request.
	 *
	 * @return void
	 */
	public function testCaseInsensitiveArrayAccess($key) {
		$headers                 = new Headers();
		$headers['Content-Type'] = 'text/plain';

		$this->assertSame('text/plain', $headers[$key]);
	}

	/**
	 * Data provider.
	 *
	 * @return array
	 */
	public function dataCaseInsensitiveArrayAccess() {
		return array(
			'access using case as set' => array('Content-Type'),
			'access using lowercase'   => array('content-type'),
			'access using uppercase'   => array('CONTENT-TYPE'),
		);
	}

	public function testMultipleHeaders() {
		$headers           = new Headers();
		$headers['Accept'] = 'text/html;q=1.0';
		$headers['Accept'] = '*/*;q=0.1';

		$this->assertSame('text/html;q=1.0,*/*;q=0.1', $headers['Accept']);
	}

	/**
	 * @depends testArrayAccess
	 */
	public function testIteration() {
		$headers                   = new Headers();
		$headers['Content-Type']   = 'text/plain';
		$headers['Content-Length'] = 10;

		foreach ($headers as $name => $value) {
			switch (strtolower($name)) {
				case 'content-type':
					$this->assertSame('text/plain', $value);
					break;
				case 'content-length':
					$this->assertSame('10', $value);
					break;
				default:
					throw new Exception('Invalid name: ' . $name);
			}
		}
	}
}
