<?php

require("admin_header.php");

$hit = false;
$nothit = false;
$users;

$pdo = new PDO($dsn, $username, $password);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  validateToken();

  if (isset($_POST['canceled'])) {

    try {

      $pdo->beginTransaction();

      switch ($_POST['time']) {

        case "4":
          $stmt = $pdo->prepare(
            "UPDATE
              reservation_table
            SET
              t0 = -1
            WHERE
              id = :id
            "
          );
          break;

        case "5":
          $stmt = $pdo->prepare(
            "UPDATE
              reservation_table
            SET
              t0_5 = -1
            WHERE
              id = :id
            "
          );
          break;

        case "6":
          $stmt = $pdo->prepare(
            "UPDATE
              reservation_table
            SET
              t1 = -1
            WHERE
              id = :id
            "
          );
          break;

        case "7":
          $stmt = $pdo->prepare(
            "UPDATE
              reservation_table
            SET
              t1_5 = -1
            WHERE
              id = :id
            "
          );
          break;

        case "8":
          $stmt = $pdo->prepare(
            "UPDATE
              reservation_table
            SET
              t2 = -1
            WHERE
              id = :id
            "
          );
          break;

        case "9":
          $stmt = $pdo->prepare(
            "UPDATE
              reservation_table
            SET
              t2_5 = -1
            WHERE
              id = :id
            "
          );
          break;

        case "10":
          $stmt = $pdo->prepare(
            "UPDATE
              reservation_table
            SET
              t3 = -1
            WHERE
              id = :id
            "
          );
          break;

        case "11":
          $stmt = $pdo->prepare(
            "UPDATE
              reservation_table
            SET
              t3_5 = -1
            WHERE
              id = :id
            "
          );
          break;

        case "12":
          $stmt = $pdo->prepare(
            "UPDATE
              reservation_table
            SET
              t4 = -1
            WHERE
              id = :id
            "
          );
          break;

        case "13":
          $stmt = $pdo->prepare(
            "UPDATE
              reservation_table
            SET
              t4_5 = -1
            WHERE
              id = :id
            "
          );
          break;

        case "14":
          $stmt = $pdo->prepare(
            "UPDATE
              reservation_table
            SET
              t5 = -1
            WHERE
              id = :id
            "
          );
          break;

        case "15":
          $stmt = $pdo->prepare(
            "UPDATE
              reservation_table
            SET
              t5_5 = -1
            WHERE
              id = :id
            "
          );
          break;

        case "16":
          $stmt = $pdo->prepare(
            "UPDATE
              reservation_table
            SET
              t6 = -1
            WHERE
              id = :id
            "
          );
          break;

        case "17":
          $stmt = $pdo->prepare(
            "UPDATE
              reservation_table
            SET
              t6_5 = -1
            WHERE
              id = :id
            "
          );
          break;

        case "18":
          $stmt = $pdo->prepare(
            "UPDATE
              reservation_table
            SET
              t7 = -1
            WHERE
              id = :id
            "
          );
          break;

        case "19":
          $stmt = $pdo->prepare(
            "UPDATE
              reservation_table
            SET
              t7_5 = -1
            WHERE
              id = :id
            "
          );
          break;

        case "20":
          $stmt = $pdo->prepare(
            "UPDATE
              reservation_table
            SET
              t8 = -1
            WHERE
              id = :id
            "
          );
          break;

        case "21":
          $stmt = $pdo->prepare(
            "UPDATE
              reservation_table
            SET
              t8_5 = -1
            WHERE
              id = :id
            "
          );
          break;

        case "22":
          $stmt = $pdo->prepare(
            "UPDATE
              reservation_table
            SET
              t9 = -1
            WHERE
              id = :id
            "
          );
          break;

        case "23":
          $stmt = $pdo->prepare(
            "UPDATE
              reservation_table
            SET
              t9_5 = -1
            WHERE
              id = :id
            "
          );
          break;

        case "24":
          $stmt = $pdo->prepare(
            "UPDATE
              reservation_table
            SET
              t10 = -1
            WHERE
              id = :id
            "
          );
          break;

        case "25":
          $stmt = $pdo->prepare(
            "UPDATE
              reservation_table
            SET
              t10_5 = -1
            WHERE
              id = :id
            "
          );
          break;

        case "26":
          $stmt = $pdo->prepare(
            "UPDATE
              reservation_table
            SET
              t11 = -1
            WHERE
              id = :id
            "
          );
          break;

        case "27":
          $stmt = $pdo->prepare(
            "UPDATE
              reservation_table
            SET
              t11_5 = -1
            WHERE
              id = :id
            "
          );
          break;

      }

      $stmt->bindValue(':id', h($_POST['timeid']));
      $stmt->execute();

      $stmt = $pdo->prepare(
        "DELETE FROM
          history
        WHERE
         user_id = :userid &&
         year = :year &&
         month = :month &&
         date = :date &&
         time = :time
        "
      );

      $stmt->bindValue(':userid', h($_POST['userid']));
      $stmt->bindValue(':year', h($_POST['year']));
      $stmt->bindValue(':month', h($_POST['month']));
      $stmt->bindValue(':date', h($_POST['date']));
      $stmt->bindValue(':time', h($_POST['timesc']));
      $stmt->execute();

      $pdo->commit();

      define('headers', "From: admin@example.com\nBCC: admin@example.com");
      $subject = "予約を取り消しました";
      $message = $_POST['name'] . " さま\n\n\n"
           . "予約を取り消しました。\n"
           . "【予約取り消し日時】\n"
           . $_POST['year']."年".$_POST['month']."月".$_POST['date']."日".$_POST['timesc']."\n\n"
           . "こちらは自動送信メールです。\n";
           
      mail($_POST['mail'], $subject, $message, headers);

    } catch (PDOException $e) {
  
      $pdo->rollBack();
      print($e->getMessage());
  
    }

  }

  if (isset($_POST['cancel'])) {
  
    try {
  
      $stmt = $pdo->prepare(
        "SELECT
          *
        From
          user_table
        WHERE
          id = :id
          "
      );
  
      $stmt->bindValue(':id', h($_POST['userid']));
      $stmt->execute();
      $users = $stmt->fetchAll();
    
      if (!empty($users[0])) {
  
        $hit = true;
  
      }
  
    } catch (PDOException $e) {
  
      print($e->getMessage());
  
    }

  }

} else {

  header("Location: index.php");
  exit();

}

?>

  <div class="right_contents">

    <h1>予約＞予約の取り消し</h1>

    <?php if (isset($_POST['cancel'])) { ?>

    <h1><?php echo $_POST['year'].'年'.$_POST['month'].'月'.$_POST['date'].'日'.$_POST['timesc'].'の予約' ?></h1>

    <table class="search_result">

      <thead>
        <tr>
          <td>名前</td>
          <td>カナ</td>
          <td>MAIL</td>
          <td>TEL</td>
          <td>キャンセル</td>
        </tr>
      </thead>

      <tbody>

        <?php
        
          foreach($users as $user) {

            print("<tr>");
          
            for ($i=1;$i<=5;$i++) {
              if ($i !==3) {
                print('<td>'.$user[$i].'</td>');
              }
            }

        ?>

            <td>
              <form action="" method="post">
                <input type="hidden" name="token" value="<?php echo h($_SESSION['token']) ?>">
                <input type="hidden" name="userid" value="<?php echo h($user['id']) ?>">
                <input type="hidden" name="name" value="<?php echo h($user['name']) ?>">
                <input type="hidden" name="mail" value="<?php echo h($user['mail']) ?>">
                <input type="hidden" name="timeid" value="<?php echo h($_POST['timeid']) ?>">
                <input type="hidden" name="year" value="<?php echo h($_POST['year']) ?>">
                <input type="hidden" name="month" value="<?php echo h($_POST['month']) ?>">
                <input type="hidden" name="date" value="<?php echo h($_POST['date']) ?>">
                <input type="hidden" name="time" value="<?php echo h($_POST['time']) ?>">
                <input type="hidden" name="timesc" value="<?php echo h($_POST['timesc']) ?>">

                <div class="delete a">予約取り消し</div>
                <div class="alert b nodis"></div>
                <div class="alert_window c nodis">
                  
                  <p>！<?php echo $user['name'] ?>の予約を取り消します！</p>
                  <p>よろしいですか？</p>
                  <input type="submit" name="canceled" value="予約を取り消す">
                  <div class="cancel d">キャンセル</div>
                  
                </div>

              </form>
            </td>

        <?php
          
            print("</tr>");
                      
          }

        ?>

        <script>

          var del = document.querySelector('.a');
          var al = document.querySelector('.b');
          var aw = document.querySelector('.c');
          var ccl = document.querySelector('.d');

          del.addEventListener('click', () =>{
            al.classList.remove('nodis');
            aw.classList.remove('nodis');
            // main.classList.add('no_scroll');
          });

          ccl.addEventListener('click', () =>{
            al.classList.add('nodis');
            aw.classList.add('nodis');
            // main.classList.remove('no_scroll');
          });

        </script>

      </tbody>

    </table>

    <?php } else { ?>
      <h1><?php echo $_POST['year'].'年'.$_POST['month'].'月'.$_POST['date'].'日'.$_POST['timesc'] ?></h1>
      <h1><?php echo $_POST['name'].'の予約を取り消しました。' ?></h1>
    <?php } ?>

    <a href="admin_reserve.php" class="back">予約に戻る</a>

  </div>

<?php require("admin_footer.php"); ?>