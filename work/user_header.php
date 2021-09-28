<?php

require("functions.php");

if (!$_SESSION['login']) {
  header("Location: index.php");
  exit();
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>reserve_system</title>
  <link href="css/admin_top.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body>

  <header class="spw">

    <div class="sp_menu">
      <span class="material-icons" id="open">menu</span>
      <span class="sp_menu_title">menu</span>
    </div>

    <div class="logbox">
      <span><?php echo $_SESSION['username'] ?></span><span>さま</span>
      <a href="index.php" class="logout">ログアウト</a>
    </div>

    <div class="sp_overlay">

      <ul>

        <li>
          <a href="user_top.php">
            <span class="material-icons">home</span>  
            TOP
          </a>
        </li>
                
        <li>
          <a href="user_history.php">
            <span class="material-icons">event_note</span>  
            スケジュール
          </a>
        </li>

        <li>
          <a href="user_edit.php">
            <span class="material-icons">settings</span>  
            設定
          </a>
        </li>

      </ul>


    </div>

  </header>

  <main>