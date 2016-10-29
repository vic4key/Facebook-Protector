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
          'domain'  => $value,
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
    // 3: autolike
    // 4: other
    $this->_addData('phishing.txt', 0);
    $this->_addData('malware.txt', 1);
    $this->_addData('scam.txt', 2);
    $this->_addData('autolike.txt', 3);
    $this->_addData('other.txt', 4);

    return $this->_blacklist;
  }

  public function printJSON() {
    if (count($this->_blacklist) === 0) {
      $this->generate();
    }
    
    file_put_contents('db.json', json_encode($this->_blacklist));
  }
}

$db = new jsonGenerator();
$db->printJSON();
