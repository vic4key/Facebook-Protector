<?php
define('ROOT', __DIR__ . DIRECTORY_SEPARATOR);

class jsonGenerator {
	private $_blacklist = array();

	private function _fileReadToArray($fileName) {
		$filePath = ROOT . $fileName;
	
		if (file_exists($filePath)) {
			return file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
		}

		return NULL;
	}

	private function _addData($fileName, $type) {
		$data = $this->_fileReadToArray($fileName);

		if ( ! is_null($data) ) {
			foreach ($data as $key => $value) {
				array_push($this->_blacklist, array(
					'url'  => $value,
					'type' => $type
				));
			}

			unset($data);
		}
	}

	public function generate() {
		// Type Map
		// -----------
		// 0: phishing
		// 1: malware
		// 2: scam
		// 3: other
		$this->_addData('phishing.txt', 0);
		$this->_addData('malware.txt', 1);
		$this->_addData('scam.txt', 2);
		$this->_addData('other.txt', 3);

		return $this->_blacklist;
	}

	public function printJSON() {
		if (count($this->_blacklist) === 0) {
			$this->generate();
		}
		
		header('Content-Type: application/json');
		echo json_encode(array(
			'blacklist' => $this->_blacklist,
			'whitelist' => array(),
			'version'   => 20160116
		));
		exit;
	}
}

if (isset($_SERVER['HTTP_X_FB_PROTECTOR_VERSION'])) {
	$db = new jsonGenerator();
	$db->printJSON();
}
