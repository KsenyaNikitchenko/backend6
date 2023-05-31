<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="utf-8">
    <title>Сверхспособности</title>
    <link rel="stylesheet" href="style_form.css">
    <style>
.error {
  border: 2px solid red;
}
.error_m{
    text-decoration: underline red;
}
    </style>
</head>

<body>
<?php
if (!empty($messages)) {
  foreach ($messages as $m) {
    print($m);
  }
  print('</div>');
}
?>
    <div class="form">
        <h1>Сверхспособности</h1>
        <form action="" method="POST">
            <label for="name">Введите имя:<br>
            <input name="name" <?php if ($errors['name']) {print 'class="error"';} ?> value=<?php if(empty($errors['name'])&&!empty($values['name'])) print $values['name']; ?>><br>
            </label>
            <label for="email">Адрес электронной почты:<br>
            <input name="email" <?php if ($errors['email']) {print 'class="error"';} ?> value='<?php print $values['email'];?>'><br>
            </label>
            <label for="year">Год рождения</label>
            <select name="year" <?php if ($errors['year']) {print 'class="error"';} ?>>
            <option selected ><?php !empty($values['year']) ? print ($values['year']) : print '' ?></option>
                <?php for ($i = 1922; $i <= 2022; $i++) {
                    printf('<option value="%d">%d</option>', $i, $i);
                }?>
            </select>
            <br>
            <label for="gender" <?php if($errors['gender']){print 'class="error_m"';} ?>>Выберите пол:</label><br>
            <label><input type="radio" checked="checked" name="gender" value="female" <?php if (isset($values['gender'])&&$values['gender'] == 'female') print("checked"); ?>>
                Женский</label>
            <label><input type="radio" name="gender" value="male" <?php if (isset($values['gender'])&&$values['gender'] == 'male') print("checked"); ?>>
                Мужской</label>
            <br>
            <label for="limbs" <?php if ($errors['limbs']) {print 'class="error_m"';} ?>>Количество конечностей:</label><br>
            <label><input type="radio" checked="checked" name="limbs" value="1" <?php if (isset($values['limbs'])&&$values['limbs'] == '1') print("checked"); ?>>1</label>
            <label><input type="radio" name="limbs" value="2" <?php if (isset($values['limbs'])&&$values['limbs'] == '2') print("checked"); ?>>2</label>
            <label><input type="radio" name="limbs" value="3" <?php if (isset($values['limbs'])&&$values['limbs'] == '3') print("checked"); ?>>3</label>
            <label><input type="radio" name="limbs" value="4" <?php if (isset($values['limbs'])&&$values['limbs'] == '4') print("checked"); ?>>4</label>
            <br>
            <label for="superpowers" <?php if ($errors['super']) {print 'class="error_m"';} ?>>Сверхспособности:</label><br>
            <select name="super[]" multiple="multiple">
                <option id="deathless" value="deathless" <?php if(isset($values['super']['deathless'])&&$values['super']['deathless']=='deathless') print("selected") ?>>Бессмертие</option>
                <option id="walls" value="walls" <?php if(isset($values['super']['walls'])&&$values['super']['walls']=='walls') print("selected") ?>>Прохождение сквозь стены</option>
                <option id="levitation" value="levitation" <?php if(isset($values['super']['levitation'])&&$values['super']['levitation']=='levitation') print("selected") ?>>Левитация</option>
                <option id="elements" value="elements" <?php if(isset($values['super']['elements'])&&$values['super']['elements']=='elements') print("selected") ?>>Управление стихиями</option>
                <option id="time travel" value="time travel" <?php if(isset($values['super']['time travel'])&&$values['super']['time travel']=='time travel') print("selected") ?>>Путешествие во времени</option>
            </select>
            <br>
            <label for="biography">Биография:</label><br>
            <textarea name="biography" <?php if ($errors['biography']) {print 'class="error"';} ?>><?php if($values['biography']) print $values['biography'];?></textarea><br>
            <label><input type="checkbox" checked="checked" name="check-kontrol">с контрактом ознакомлен(а)</label>
            <br>
            <input type="submit" class="submit" value="Отправить" />

        </form>
        <?php if(!empty($_COOKIE[session_name()]) && !empty($_SESSION['login'])) print( '<div id="footer">Вход с логином ' . $_SESSION["login"]. '<br> <a href=login.php?do=logout> Выход</a><br></div>');?>
    </div>

</body>

</html>