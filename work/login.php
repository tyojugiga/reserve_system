<?php

require("functions.php");

validateToken();

if ($_SERVER['REQUEST_METHOD'] === "POST") {

  $pdo = new PDO($dsn, $username, $password);

  try {

    $stmt = $pdo->prepare(
      "SELECT
        *
      From
        user_table
      WHERE
        id = 1 &&
        name = :name &&
        pass = :pass"
    );

    $stmt->bindValue(':name', $_POST['name']);
    $stmt->bindValue(':pass', $_POST['pass']);
    $stmt->execute();
    $members = $stmt->fetchAll();
  
    if (!empty($members[0])) {

      $_SESSION['admin'] = true;
      header("Location: admin_top.php");

    } else {

      $stmt = $pdo->prepare(
        "SELECT
          *
        From
          user_table
        WHERE
          pass = :pass &&
          mail = :mail"
      );

      $stmt->bindValue(':pass', $_POST['pass']);
      $stmt->bindValue(':mail', $_POST['name']);
      $stmt->execute();
      $members = $stmt->fetchAll();

      if (!empty($members[0])) {

        $_SESSION['login'] = true;
        $_SESSION['userid'] = $members[0][0];
        $_SESSION['username'] = $members[0][1];
        $_SESSION['mail'] = $members[0][4];
        header("Location: user_top.php");

      } else {

        header("Location: index.php");

      }

    }

  } catch (PDOException $e) {
    print($e->getMessage());
  }

} else {
  header("Location: index.php");
}