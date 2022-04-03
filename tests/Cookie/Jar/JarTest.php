<?php

namespace WpOrg\Requests\Tests\Cookie\Jar;

use WpOrg\Requests\Cookie;
use WpOrg\Requests\Cookie\Jar;
use WpOrg\Requests\Requests;
use WpOrg\Requests\Tests\TestCase;

/**
 * @covers \WpOrg\Requests\Cookie\Jar
 */
final class JarTest extends TestCase {

	public function testCookieJarIterator() {
		$cookies = [
			'requests-testcookie1' => 'testvalue1',
			'requests-testcookie2' => 'testvalue2',
		];
		$jar     = new Jar($cookies);

		foreach ($jar as $key => $value) {
			$this->assertSame($cookies[$key], $value);
		}
	}

	public function testSendingCookieWithJar() {
		$cookies = new Jar(
			[
				'requests-testcookie1' => 'testvalue1',
			]
		);
		$data    = $this->setCookieRequest($cookies);

		$this->assertArrayHasKey('requests-testcookie1', $data);
		$this->assertSame('testvalue1', $data['requests-testcookie1']);
	}

	public function testSendingMultipleCookiesWithJar() {
		$cookies = new Jar(
			[
				'requests-testcookie1' => 'testvalue1',
				'requests-testcookie2' => 'testvalue2',
			]
		);
		$data    = $this->setCookieRequest($cookies);

		$this->assertArrayHasKey('requests-testcookie1', $data);
		$this->assertSame('testvalue1', $data['requests-testcookie1']);

		$this->assertArrayHasKey('requests-testcookie2', $data);
		$this->assertSame('testvalue2', $data['requests-testcookie2']);
	}

	public function testSendingPrebakedCookie() {
		$cookies = new Jar(
			[
				new Cookie('requests-testcookie', 'testvalue'),
			]
		);
		$data    = $this->setCookieRequest($cookies);

		$this->assertArrayHasKey('requests-testcookie', $data);
		$this->assertSame('testvalue', $data['requests-testcookie']);
	}

	private function setCookieRequest($cookies) {
		$options  = [
			'cookies' => $cookies,
		];
		$response = Requests::get(httpbin('/cookies/set'), [], $options);

		$data = json_decode($response->body, true);
		$this->assertIsArray($data);
		$this->assertArrayHasKey('cookies', $data);
		return $data['cookies'];
	}
}
