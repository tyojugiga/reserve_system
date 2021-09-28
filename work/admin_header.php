<?php

require("functions.php");

if (!$_SESSION['admin']) {
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

  <header>

  <div class="logbox">
      <span>admin</span>
      <a href="index.php" class="logout">ログアウト</a>
    </div>

  </header>

  <main>

    <div class="left_menu">

      <ul>

        <li class="li_menu">
          <a href="admin_top.php">
            <span class="material-icons">home</span>  
            管理者TOP
          </a>
        </li>

        <li class="li_menu">
          <a href="admin_reserve.php">
            <span class="material-icons">meeting_room</span>  
            予約
          </a>
        </li>
        
        <li class="li_menu kokyaku">
          
          <span>
            <span class="material-icons">people</span>
            顧客
            <span class="material-icons ac_arrow" id="arrow">expand_more</span>
          </span>
          
        </li>
        
        <li class="ac_content">
          <a href="admin_search.php" ontouchstart="">
            <span class="material-icons">arrow_forward_ios</span>
            検索
          </a>
        </li>

        <li class="ac_content ac_bottom">
          <a href="admin_add.php" ontouchstart="">
            <span class="material-icons">arrow_forward_ios</span>
            新規登録
          </a>
        </li>
        
        <li class="li_menu">
          <a href="add_sc.php">
            <span class="material-icons">event_note</span>  
            スケジュール
          </a>
        </li>

        <li class="li_menu">
          <a href="admin_setting.php">
            <span class="material-icons">settings</span>  
            設定
          </a>
        </li>
    
      </ul>

    </div>