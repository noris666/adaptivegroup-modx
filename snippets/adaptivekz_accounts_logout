<?php
try {
  $resp = $modx->runProcessor('/security/logout');
  
  if($resp->isError()) {
    throw new Exception('Processor error.');
  }
  
  echo json_encode(array('code' => 0, 'message' => 'Success logout'));
} catch (Exception $e) {
  echo json_encode(array('code' => -1, 'message' => $e->getMessage()));
}
