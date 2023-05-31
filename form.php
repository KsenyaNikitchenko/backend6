<?php
header('Content-Type: text/html; charset=UTF-8');
$user = 'u52984';
$pass = '8295850';
$db = new PDO('mysql:host=localhost;dbname=u52984', $user, $pass, array(PDO::ATTR_PERSISTENT => true));
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  $messages = array();
  if (!empty($_COOKIE['save'])) {
    setcookie('save', '', 100);
    setcookie('login','',100);
    setcookie('password','',100);
    $messages[] = 'Спасибо, результаты сохранены.<br>';
    if(!empty($_COOKIE['password'])){
      $messages[] = sprintf('Вы можете <a href="login.php">войти</a> с логином <strong>%s</strong>
        и паролем <strong>%s</strong> для изменения данных.',
        strip_tags($_COOKIE['login']),
        strip_tags($_COOKIE['password']));
    }
  }
  $errors = array();
  $errors['name'] = !empty($_COOKIE['name_error']);
  $errors['email'] = !empty($_COOKIE['email_error']);
  $errors['year'] = !empty($_COOKIE['year_error']);
  $errors['gender'] = !empty($_COOKIE['gender_error']);
  $errors['limbs'] = !empty($_COOKIE['limbs_error']);
  $errors['super'] = !empty($_COOKIE['super_error']);
  $errors['biography'] = !empty($_COOKIE['biography_error']);
  if ($errors['name']) {
    setcookie('name_error', '', 100);
    $messages[] = '<div class="error_m">Заполните имя. Данное поле может содержать
     символы русского и английского алфавитов.</div>';
  }
  if ($errors['email']) {
    setcookie('email_error', '', 100);
    $messages[] = '<div class="error_m">Заполните email. Поле должно содержать только символы 
    английского алфавита и знак @ (почта должна иметь домен .ru)</div>';
  }
  if ($errors['year']) {
    setcookie('year_error', '', 100);
    $messages[] = '<div class="error_m">Заполните год рождения.</div>';
  }
  if ($errors['gender']) {
    setcookie('gender_error', '', 100);
    $messages[] = '<div class="error_m">Выберите пол.</div>';
  }
  if ($errors['limbs']) {
    setcookie('limbs_error', '', 100);
    $messages[] = '<div class="error_m">Укажите количество конечностей.</div>';
  }
  if($errors['super']){
    setcookie('super_error','',100);
    $messages[]='<div class="error_m">Выберите минимум одну сверхспособность.</div>';
  }
  if ($errors['biography']) {
    setcookie('biography_error', '', 100);
    $messages[] = '<div class="error_m">Расскажите что-нибудь о себе.</div>';
  }

  // Складываем предыдущие значения полей в массив, если есть.
  $values = array();
  $values['name'] = empty($_COOKIE['name_value']) ? '' : $_COOKIE['name_value'];
  $values['email'] = empty($_COOKIE['email_value']) ? '' : $_COOKIE['email_value'];
  $values['year'] = empty($_COOKIE['year_value']) ? '' : $_COOKIE['year_value'];
  $values['gender'] = empty($_COOKIE['gender_value']) ? '' : $_COOKIE['gender_value'];
  $values['limbs'] = empty($_COOKIE['limbs_value']) ? '' : $_COOKIE['limbs_value'];
  $values['super'] = [];
  $values['biography'] = empty($_COOKIE['biography_value']) ? '' : $_COOKIE['biography_value'];
  $super=array(
    'deathless'=>'Бессмертие',
    'walls'=>'Прохождение сквозь стены',
    'levitation'=>'Левитация',
    'elements'=>'Управление стихиями',
    'time travel'=>'Путешествие во времени',
  );
if(!empty($_COOKIE['super_value'])){
  $super_value=unserialize($_COOKIE['super_value']);
  foreach($super_value as $el){
    if(!empty($super[$el])){
      $values['super'][$el]=$el;
    }
  }
}
if(!empty($_COOKIE[session_name()])&&session_start()&&!empty($_SESSION['login'])){
  try{
    $sth=$db->prepare("SELECT id FROM user WHERE login = ?");
    $sth->execute(array($_SESSION['login']));
    $user_id=($sth->fetchAll(PDO::FETCH_COLUMN,0))['0'];
    $sth=$db->prepare("SELECT * FROM person5 WHERE id_person = ?");
    $sth->execute(array($user_id));
    $user_data=($sth->fetchAll(PDO::FETCH_ASSOC));
    foreach($user_data as $key=>$val){
      $values[$key]=$val;
    }
    $values['super']=[];
    $super_value=unserialize($_COOKIE['super_value']);
    foreach($super_value as $s){
      if(!empty($super[$s])){
        $values['super'][$s]=$s;
      }
    }
  }
  catch(PDOException $e){
    print($e->getMessage());
    exit();
  }
}
  include('index.php');
}
else {
  // Проверяем ошибки.
  $errors = FALSE;
  if (empty($_POST['name'])) {
    setcookie('name_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  }
  else if(!preg_match("/[a-zA-Zа-яёА-ЯЁ]/", $_POST['name'])){
    setcookie('name_error', $_POST['name'], time() + 24 * 60 * 60);
    $errors = TRUE;
  }
  else {
    setcookie('name_value', $_POST['name'], time() + 30 * 24 * 60 * 60);
  }
  if (empty($_POST['email'])) {
    setcookie('email_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  }
  else if(!preg_match("/.*@.*\.ru$/", $_POST['email'])){
    setcookie('email_error', $_POST['email'], time() + 24 * 60 * 60);
    $errors = TRUE;
  }
  else {
    setcookie('email_value', $_POST['email'], time() + 30 * 24 * 60 * 60);
  }
  if (empty($_POST['year'])) {
    setcookie('year_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  }
  else {
    setcookie('year_value', $_POST['year'], time() + 30 * 24 * 60 * 60);
  }
  if (empty($_POST['gender'])) {
    setcookie('gender_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  }
  else {
    setcookie('gender_value', $_POST['gender'], time() + 30 * 24 * 60 * 60);
  }
  if (empty($_POST['limbs'])) {
    setcookie('limbs_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  }
  else {
    setcookie('limbs_value', $_POST['limbs'], time() + 30 * 24 * 60 * 60);
  }
  if (empty($_POST['biography'])) {
    setcookie('biography_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  }
  else {
    setcookie('biography_value', $_POST['biography'], time() + 30 * 24 * 60 * 60);
  }
  $super=array();
  if(empty($_POST['super'])){
    setcookie('super_error','1',time()+24*60*60);
    $errors=TRUE;
  }
  else{
    foreach($_POST['super'] as $key=>$value){
      $super[$key]=$value;
    }
    setcookie('super_value',serialize($super),time()+30*24*60*60);
  }

  if ($errors) {
    // При наличии ошибок перезагружаем страницу и завершаем работу скрипта.
    header('Location: form.php');
    exit();
  }
  else {
    // Удаляем Cookies с признаками ошибок.
    setcookie('name_error', '', 100000);
    setcookie('email_error', '', 100000);
    setcookie('year_error', '', 100000);
    setcookie('gender_error', '', 100000);
    setcookie('limbs_error', '', 100000);
    setcookie('superpower_error', '', 100000);
    setcookie('fio_error', '', 100000);
  }
  // Проверяем меняются ли ранее сохраненные данные или отправляются новые.
  if (!empty($_COOKIE[session_name()]) && session_start() && !empty($_SESSION['login'])) {
    try{
      $stmt=$db->prepare("SELECT id FROM user WHERE login =?");
      $stmt->execute(array($_SESSION['login']));
      $user_id=($stmt->fetchAll(PDO::FETCH_COLUMN))['0'];
      $stmt=$db->prepare("UPDATE person5 SET name=?, email=?, year=?, gender=?, limbs=?, biography=?  WHERE id=?");
      $stmt->execute(array(
        $_POST['name'],
        $_POST['email'],
        $_POST['year'],
        $_POST['gender'],
        $_POST['limbs'],
        $_POST['biography'],
        $user_id,
      ));
      $sth=$db->prepare("DELETE FROM ability5 WHERE id_user=?");
      $sth->execute(array($user_id));
      $stmt=$db->prepare("INSERT INTO ability5 SET id_user=?, id_superpower=?");
      $stmt = $db->prepare("SELECT id_power FROM superpower WHERE superpower = ?");
    foreach ($_POST['superpowers'] as $value) {
        $stmt->execute([$value]);
        $id_power=$stmt->fetchColumn();
        $stmt1 = $db->prepare("INSERT INTO ability5 (id_user, id_superpower) VALUES (?, ?)");
        $stmt1 -> execute([$last_index, $id_power]);
    }
    unset($value);
    }
    catch(PDOException $e){
      print("Error: ".$e->getMessage());
      exit();
    }
  }
  else {
    $sth=$db->prepare("SELECT login From user");
    $sth->execute();
    $login_array=$sth->fetchAll(PDO::FETCH_COLUMN);
    $flag=true;
    do{
      $login=rand(1,1000);
      $password=rand(1,1000);
      foreach($login_array as $key=>$value){
        if($value==$login){
          $flag=false;
        }
      }
    }while($flag==false);
    $hash=password_hash((string)$password, PASSWORD_BCRYPT);
    setcookie('login',$login);
    setcookie('password',$password);
    try {
      $stmt = $db->prepare("INSERT INTO person5 (name, email, year, gender, limbs, biography) VALUES (?, ?, ?, ?, ?, ?)");
      $stmt -> execute([$_POST['name'], $_POST['email'], $_POST['year'], $_POST['gender'], $_POST['limbs'], $_POST['biography']]);
      $last_index=$db->lastInsertId();
      $stmt = $db->prepare("SELECT id_power FROM superpower WHERE superpower = ?");
      foreach ($_POST['superpowers'] as $value) {
          $stmt->execute([$value]);
          $id_power=$stmt->fetchColumn();
          $stmt1 = $db->prepare("INSERT INTO ability5 (id_user, id_superpower) VALUES (?, ?)");
          $stmt1 -> execute([$last_index, $id_power]);
      }
      unset($value);
      $stmt=$db->prepare("INSERT INTO user SET login=?, password=?");
      $stmt->execute(array(
        $login,
        $hash,
      ));
  }
  catch(PDOException $e){
  print('Error: ' . $e->getMessage());
  exit();
  }
  }
setcookie('save','1');
header('Location: ?save=1');
}