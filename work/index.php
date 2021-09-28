<?php

require("functions.php");

createToken();

$_SESSION['admin'] = false;
$_SESSION['login'] = false;
$_SESSION['userid'] = "";
$_SESSION['username'] = "";

?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>
<body>

<h1>ログイン</h1>

<form action="login.php" method="post" autocomplete=off>
  <input type="text" name="name" placeholder="mail"><br>
  <input type="password" name="pass" placeholder="pass"><br>
  <input type="hidden" name="token" value="<?php echo h($_SESSION['token']) ?>">
  <input type="submit" name="login" value="ログイン" class="login">
</form>

</body>
</html>