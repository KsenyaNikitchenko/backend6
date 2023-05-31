<?php
header('Content-Type: text/html; charset=UTF-8');
session_start();

$db_user = 'u52984';
$db_pass = '8295850';

// В суперглобальном массиве $_SERVER PHP сохраняет некторые заголовки запроса HTTP
// и другие сведения о клиненте и сервере, например метод текущего запроса $_SERVER['REQUEST_METHOD'].
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if(isset($_GET['do'])&&$_GET['do'] == 'logout'){
    session_start();    
    session_unset();
    session_destroy();
    setcookie ("PHPSESSID", "", time() - 3600, '/');
    header("Location: form.php");
    exit;}
?>
<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="utf-8">
    <title>Авторизация</title>
    <link rel="stylesheet" href="style_login.css">
    </style>
</head>

<body>
    <div class="form">
    <form action="" method="post">
        <label for="login">Логин:</label><br>
        <input name="login"><br>
        <label for="password">Пароль:</label><br>
        <input name="password"><br>
        <input type="submit" class="submit" value="Войти" />
    </form>
    </div>
</body>
<?php
}
// Иначе, если запрос был методом POST, т.е. нужно сделать авторизацию с записью логина в сессию.
else {

  $login = $_POST['login'];
  $password =  $_POST['password'];

  $db = new PDO('mysql:host=localhost;dbname=u52984', $db_user, $db_pass, array(
    PDO::ATTR_PERSISTENT => true
  ));

  try {
    $stmt = $db->prepare("SELECT * FROM user WHERE login = ?");
    $stmt->execute(array($login));
    // Получаем данные в виде массива из БД.
    $user = $stmt->fetch();
    // Сравнием текущий хэш пароля с тем, что достали из базы.
    if (password_verify($password, $user['password'])) {
      $_SESSION['login'] = $login;
    }
    else {
      echo "Неправильный логин или пароль";
      exit();
    }

  }
  catch(PDOException $e) {
    echo 'Ошибка: ' . $e->getMessage();
    exit();
  }
  header('Location: ./form.php');
}
