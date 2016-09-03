<?php
try {
  // Определяем входные параметры для дальнейшей работы
  if (!isset($_POST['username']) or !isset($_POST['password'])) {
    throw new Exception('Произошла ошибка. Не определены параметры.');
  }
     
  $username = addslashes($_POST['username']);
  $password = addslashes($_POST['password']);
  
  $count = $modx->getCount('modUser', array('username' => $username));
  $countEmail = $modx->getCount('modUserProfile', array('email' => $username));
  
  if($count == 0 && $countEmail == 0) {
    throw new Exception('Пользователь с такими данными не найден.');
  } 
  
  // Пробуем найти пользователя по логину или адресу электронной почты
  if ($count > 0) $currentUser = $modx->getObject('modUser', array('username' => $username));
  if ($countEmail > 0) {
    $_temp = $modx->getObject('modUserProfile', array('email' => $username));
    $currentUser = $modx->getObject('modUser', array('id' => $_temp->get('id')));
  }
  
  if ($currentUser) {
    // Проверка найдененого пользователя на пренадлежность к группе Users
    if (!$currentUser->isMember('Users')) {
      throw new Exception('Пользователь с такими данными не найден.');
    }
  }
  
  // При успешной первичной проверке запускаем процессор авторизации MODx
  $resp = $modx->runProcessor('/security/login', array(
    'username' => $currentUser->get('username'),
    'password' => $password,
    'rememberme' => true
  ));
  
  // Если вернулась ошибка, скорее всего не корректный пароль, потому-что все другие проверки пройдены
  if($resp->isError()) {
    throw new Exception('Неправильно введен пароль');
  }
  
  // Кодируем ответ для REST API в JSON формат
  echo json_encode(array('code' => 1, 'message' => 'Пользователь авторизован'));
} catch (Exception $e) {
  echo json_encode(array('code' => 0, 'message' => $e->getMessage()));
}
