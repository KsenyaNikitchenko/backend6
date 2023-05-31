<head>
<meta charset="utf-8">
    <title>Сверхспособности</title>
    <link rel="stylesheet" href="style_form.css">
</head>
    <?php
    $db_user = 'u52984';
    $db_pass = '8295850';
    $db = new PDO('mysql:host=localhost;dbname=u52984', $db_user, $db_pass, array(
        PDO::ATTR_PERSISTENT => true
    ));
    $login = isset($_SERVER['PHP_AUTH_USER']) ? $_SERVER['PHP_AUTH_USER'] : '';
    $stmt = $db->prepare("SELECT * FROM admin WHERE login = ?");
    $stmt->execute(array($login));
    $admin_pass = $stmt->fetch();

    if (empty($_SERVER['PHP_AUTH_USER']) ||
    empty($_SERVER['PHP_AUTH_PW']) ||
    $_SERVER['PHP_AUTH_USER'] != 'admin' ||
    $_SERVER['PHP_AUTH_PW']!='admin'){
      header('HTTP/1.1 401 Unanthorized');
      header('WWW-Authenticate: Basic realm="My site"');
      print('<h1>401 Требуется авторизация</h1>');
      exit();
}

print('Вы успешно авторизовались и видите защищенные паролем данные.<br>');
function show_tables($db){
  $sql = 'SELECT person5.*, ability5.id_superpower, user.login 
  FROM person5 JOIN ability5 ON person5.id_person=ability5.id_user
  JOIN user ON person5.id_person=user.id;';
  ?>
  <br><br>
  <table class="table">
  <caption>Данные пользователей</caption> 
    <tr><th>id_person</th><th>name</th><th>e-mail</th><th>year</th><th>gender</th><th>limbs</th><th>biography</th><th>id_superpower</th><th>login</th><th colspan="3">action</th></tr><!--ряд с ячейками заголовков-->
  <?php
	  foreach ($db->query($sql, PDO::FETCH_ASSOC) as $row) {
      print('<tr>');
      foreach ($row as $v){
        print('<td>'.$v. '</td>');
      }
      print('<td colspan="2"> <a href="?act=edit_article&edit_id_person='.$row["id_person"].'">edit</a></td>');
      print('<td> <a href="?act=delete_article&delete_id_person='.$row["id_person"].'">delete</a></td>');
    } 
    print('</tr></table>');

    
    $sql = 'SELECT ability5.id_superpower, COUNT(ability5.id_user)
    FROM ability5 GROUP BY id_superpower;';
    ?>
    <br><br>
    <table class="table">
    <caption>Статистика для суперспособностей</caption> 
      <tr><th>id_superpower</th><th>number_of_users</th></tr><!--ряд с ячейками заголовков-->
    <?php
    foreach ($db->query($sql, PDO::FETCH_ASSOC) as $row) {
      print('<tr>');
      foreach ($row as $k=>$v){
	  	  print('<td>'.$v. '</td>');
      }
    }print('</tr></table>');
    print('<br><a href=login.php?do=logout> Выход</a><br>');
}
function form($db){
  ?>
  <label>
                Введите имя:<br>
                <input name="name" placeholder="Введите имя" /><br>
            </label>
            <label>
                Адрес электронной почты:<br>
                <input name="email" type="email" placeholder="Введите email" /><br>
            </label>
            <label for="year">Год рождения</label>
            <select name="year">
    <?php 
    for ($i = 1922; $i <= 2022; $i++) {
      printf('<option value="%d">%d</option>', $i, $i);
    }
    ?>
  </select>
            <br>
            Выберите пол:<br>
            <label><input type="radio" checked="checked" name="gender" value="female" />
                Женский</label>
            <label><input type="radio" name="gender" value="male" />
                Мужской</label>
            <br>
            Количество конечностей:<br>
            <label><input type="radio" checked="checked" name="limbs" value="1" />
                1</label>
            <label><input type="radio" name="limbs" value="2" />
                2</label>
            <label><input type="radio" name="limbs" value="3" />
                3</label>
            <label><input type="radio" name="limbs" value="4" />
                4</label>
            <label>
                <br>
                Сверхспособности:<br>
                <select name="superpowers[]" multiple="multiple">
                    <option value="deathless">Бессмертие</option>
                    <option value="walls" selected="selected">Прохождение сквозь стены</option>
                    <option value="levitation">Левитация</option>
                    <option value="elements">Управление стихиями</option>
                    <option value="time travel">Путешествие во времени</option>
                </select>
                <br>
                Биография:<br>
                <textarea name="biography">Напишите о себе</textarea><br>
                <label><input type="checkbox" checked="checked" name="check-kontrol" />
                    с контрактом ознакомлен(а)</label>
                <br>
  <?php
}
function errors(){
  $errors = FALSE;
    // ИМЯ
    if (empty($_POST['name'])&&empty($_GET['edit_id_person'])) {
      $errors = TRUE;
    }
    else if(!empty($_POST['name'])&&!preg_match("/^[а-яё]|[a-z]$/iu", $_POST['name'])){
      $errors = TRUE;
    }
    // EMAIL
    if (empty($_POST['email'])&&empty($_GET['edit_id_person'])){
      $errors = TRUE;
    }
    else if(!empty($_POST['email'])&&!preg_match("/^[a-zA-Z0-9._-]+@[a-zA-Z0-9-]+.[a-zA-Z.]{2,5}$/", $_POST['email'])){
      $errors = TRUE;
    }
    // ГОД
    if (empty($_POST['year'])&&empty($_GET['edit_id_person'])){
      $errors = TRUE;
    }
    // ПОЛ
    if (empty($_POST['gender'])&&empty($_GET['edit_id_person'])) {
      $errors = TRUE;
    }
    // КОНЕЧНОСТИ
    if (empty($_POST['kon'])&&empty($_GET['edit_id_person'])) {
      $errors = TRUE;
    }
    // СВЕРХСПОСОБНОСТИ
    $super=array();
    if(empty($_POST['super'])&&empty($_GET['edit_id_person'])){
      $errors = TRUE;
    }
    else if(!empty($_POST['super'])){
      foreach ($_POST['super'] as $key => $value) {
        $super[$key] = $value;
      }
    }
    // БИОГРАФИЯ
    if (empty($_POST['bio'])&&empty($_GET['edit_id_person'])) {
      $errors = TRUE;
    }
    if ($errors) {
      return true;
    }
    else{
      return false;
    }
}
function delete_user($db, $del){
  try{
    $sth = $db->prepare("DELETE FROM person5 WHERE id_person = ?");
    $sth->execute(array($del));
    $sth = $db->prepare("DELETE FROM ability5 WHERE id_user = ?");
    $sth->execute(array($del));
    $sth = $db->prepare("DELETE FROM user WHERE id = ?");
    $sth->execute(array($del));
  }
  catch(PDOException $e) {
    echo 'Ошибка: ' . $e->getMessage();
    exit();
  }
}
function edit_user($db, $edit){
  try {
    $stmt = $db->prepare('SELECT * FROM person5 WHERE id_user=?');
    $stmt -> execute(array($edit));
    $a = array();
    $old_data = ($stmt->fetchAll(PDO::FETCH_ASSOC))[$edit];
    foreach ($old_data as $key=>$val){
      $a[$key] = $val;
    }
    $name = empty($_POST['name']) ? $a['name'] : $_POST['name'];
    $email = empty($_POST['email']) ? $a['email'] : $_POST['email'];
    $year = empty($_POST['year']) ? $a['year'] : $_POST['year'];
    $gender = empty($_POST['gender']) ? $a['gender'] : $_POST['gender'];
    $limbs = empty($_POST['limbs']) ? $a['limbs'] : $_POST['limbs'];
    $biography = empty($_POST['biography']) ? $a['biography'] : $_POST['biography'];

    $stmt = $db->prepare("UPDATE person5 SET name = ?, email = ?, year = ?, gender = ?, limbs = ?, biography = ? WHERE id_person =?");
    $stmt -> execute([$name,$email,$year,$gender,$limbs,$biography,$edit]);
    //удаляем старые данные о способностях и заполняем новыми
    if(!empty($_POST['super'])){
      $sth = $db->prepare("DELETE FROM ability5 WHERE id_user = ?");
      $sth->execute(array($edit));
      $stmt = $db->prepare("SELECT id_power FROM superpower WHERE superpower = ?");
    foreach ($_POST['superpowers'] as $value) {
        $stmt->execute([$value]);
        $id_power=$stmt->fetchColumn();
        $stmt1 = $db->prepare("INSERT INTO ability (id_user, id_superpower) VALUES (?, ?)");
        $stmt1 -> execute([$edit, $id_power]);
    }
    unset($value);
    }
  }
  catch(PDOException $e) {
    echo 'Ошибка: ' . $e->getMessage();
    exit();
  }
}

if($_SERVER['REQUEST_METHOD'] == 'GET'){
  show_tables($db);
  if(isset($_GET['act'])&&$_GET['act']=='edit_article'){
    ?><form action="" method="post">
      <h5>Редактировать профиль с id=<?php print($_GET['edit_id_person']); ?></h5>
    <p>
      <?php form($db);?>
    </p>
    <p><button type="submit" value="send">Отправить</button></p>
    </form>
    <?php
  }
  else if(isset($_GET['act'])&&$_GET['act']=='add_article'){
    ?><form action="" method="post">
      <h5>Добавить профиль</h5>
    <p>
      <?php form($db);?>
    </p>
    <p><button type="submit" value="send">Отправить</button></p>
    </form>
    <?php
     }
  else if(isset($_GET['act'])&&$_GET['act']=='delete_article'){
    ?>
    <form action="" method="post">
    <h5>Удалить пользователя c id=<?php print($_GET['delete_id_person']);?>?</h5>
    <p><button type="submit" value="send">Ок</button></p>
    </form>
    <?php
    }
}
else{
  try {
    if(!empty($_GET['delete_id_person'])){delete_user($db, $_GET['delete_id_person']);}
    if(!empty($_GET['edit_id_person']))if(!errors()){edit_user($db, $_GET['edit_id_person']);}
  }
  catch(PDOException $e) {
    echo 'Ошибка: ' . $e->getMessage();
    exit();
  }
  header('Location: ./admin.php');
}