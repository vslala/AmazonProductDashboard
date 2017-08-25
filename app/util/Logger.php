<?php

namespace Util;

use \Log;
class Logger {
	private $logger;

	private function __construct() {
		
	}
	public static function write($txt) {
		$logger = new Log('info.log');
		$logger->write($txt, 'r');
	}
}