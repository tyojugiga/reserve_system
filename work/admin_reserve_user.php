<?php

require("admin_header.php");

$hit = false;
$nothit = false;
$users;

$reserve_flag = false;

$pdo = new PDO($dsn, $username, $password);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  validateToken();

  if (isset($_POST['reserve'])) {

    try {

      $pdo->beginTransaction();

      $stmt = $pdo->prepare(
        "SELECT
          *
        From
          reservation_table
        WHERE
          id = :id
        "
      );

      $stmt->bindValue(':id', h($_POST['timeid']));
      $stmt->execute();
      $timesc = $stmt->fetchAll();

      foreach ($timesc as $time) {
        if ($time[$_POST['time']] === "0") {
          $reserve_flag = true;
        }
      }

      if ($reserve_flag) {

        switch ($_POST['time']) {

          case "4":
            $stmt = $pdo->prepare(
              "UPDATE
                reservation_table
              SET
                t0 = :userid
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
                t0_5 = :userid
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
                t1 = :userid
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
                t1_5 = :userid
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
                t2 = :userid
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
                t2_5 = :userid
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
                t3 = :userid
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
                t3_5 = :userid
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
                t4 = :userid
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
                t4_5 = :userid
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
                t5 = :userid
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
                t5_5 = :userid
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
                t6 = :userid
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
                t6_5 = :userid
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
                t7 = :userid
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
                t7_5 = :userid
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
                t8 = :userid
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
                t8_5 = :userid
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
                t9 = :userid
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
                t9_5 = :userid
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
                t10 = :userid
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
                t10_5 = :userid
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
                t11 = :userid
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
                t11_5 = :userid
              WHERE
                id = :id
              "
            );
            break;

        }

        $stmt->bindValue(':id', h($_POST['timeid']));
        $stmt->bindValue(':userid', h($_POST['userid']));
        $stmt->execute();
        
        $stmt = $pdo->prepare(
          "INSERT INTO
            history (user_id,name,year,month,date,time)
          VALUES
            (:user_id,:name,:year,:month,:date,:time)"
        );
        
        $stmt->bindValue(':user_id', h($_POST['userid']));
        $stmt->bindValue(':name', h($_POST['resname']));
        $stmt->bindValue(':year', h($_POST['year']));
        $stmt->bindValue(':month', h($_POST['month']));
        $stmt->bindValue(':date', h($_POST['date']));
        $stmt->bindValue(':time', h($_POST['timesc']));
        $stmt->execute();
        
      }

      $pdo->commit();

      define('headers', "From: admin@example.com\nBCC: admin@example.com");
      $subject = "ご予約ありがとうございます";
      $message = $_POST['resname'] . " さま\n\n\n"
           . "ご予約ありがとうございます。\n"
           . "【ご予約内容】\n"
           . $_POST['year']."年".$_POST['month']."月".$_POST['date']."日".$_POST['timesc']."\n\n"
           . "こちらは自動送信メールです。\n";
           
      mail($_POST['mail'], $subject, $message, headers);

    } catch (PDOException $e) {
  
      $pdo->rollBack();
      print($e->getMessage());
  
    }

  }

  if (isset($_POST['name'])) {

  
    try {
  
      $stmt = $pdo->prepare(
        "SELECT
          *
        From
          user_table
        WHERE
          id <> 1 &&
          (name LIKE :name ||
          kana LIKE :kana)
          "
      );
  
      $stmt->bindValue(':name', "%".h($_POST['name'])."%");
      $stmt->bindValue(':kana', "%".h($_POST['name'])."%");
      $stmt->execute();
      $users = $stmt->fetchAll();
    
      if (!empty($users[0])) {
  
        $hit = true;
  
      } else {
  
        $nothit = true;
  
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

    <h1>予約＞ユーザー検索</h1>

    <?php if (isset($_POST['name'])) { ?>
    <h1><?php echo $_POST['year'].'年'.$_POST['month'].'月'.$_POST['date'].'日'.$_POST['timesc'].'の予約' ?></h1>

    <form action="" method="post" class="search_form">

      <input type="text" name="name" autocomplete=off>
      <input type="hidden" name="token" value="<?php echo h($_SESSION['token']) ?>">
      <input type="hidden" name="timeid" value="<?php echo h($_POST['timeid']) ?>">
      <input type="hidden" name="year" value="<?php echo h($_POST['year']) ?>">
      <input type="hidden" name="month" value="<?php echo h($_POST['month']) ?>">
      <input type="hidden" name="date" value="<?php echo h($_POST['date']) ?>">
      <input type="hidden" name="time" value="<?php echo h($_POST['time']) ?>">
      <input type="hidden" name="timesc" value="<?php echo h($_POST['timesc']) ?>">
      <input type="submit" value="検索">

    </form>

    <?php if ($hit) { ?>

      <p><?php echo count($users).'件の候補が見つかりました' ?></p>

      <table class="search_result">

        <thead>
          <tr>
            <td>名前</td>
            <td>カナ</td>
            <td>MAIL</td>
            <td>TEL</td>
            <td>予約</td>
          </tr>
        </thead>

        <tbody>

          <?php
          
            $cnt = 0;
            foreach($users as $user) {
              $gray = '';

              if ($cnt%2 !== 0) {
                $gray = 'class="gray"';
              }

              print("<tr ".$gray.">");
            
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
                  <input type="hidden" name="resname" value="<?php echo h($user['name']) ?>">
                  <input type="hidden" name="mail" value="<?php echo h($user['mail']) ?>">
                  <input type="hidden" name="timeid" value="<?php echo h($_POST['timeid']) ?>">
                  <input type="hidden" name="year" value="<?php echo h($_POST['year']) ?>">
                  <input type="hidden" name="month" value="<?php echo h($_POST['month']) ?>">
                  <input type="hidden" name="date" value="<?php echo h($_POST['date']) ?>">
                  <input type="hidden" name="time" value="<?php echo h($_POST['time']) ?>">
                  <input type="hidden" name="timesc" value="<?php echo h($_POST['timesc']) ?>">

                  <div class="delete <?php echo 'a'.$cnt ?>">予約</div>
                  <div class="alert <?php echo 'b'.$cnt ?> nodis"></div>
                  <div class="alert_window <?php echo 'c'.$cnt ?> nodis">
                    
                    <p>！<?php echo $user['name'] ?>の予約をします！</p>
                    <p>よろしいですか？</p>
                    <input type="submit" name="reserve" value="予約する">
                    <div class="cancel <?php echo 'd'.$cnt ?>">キャンセル</div>
                    
                  </div>

                </form>
              </td>

          <?php
            
              print("</tr>");
            
              $cnt++;
              
            }

          ?>

          <script>
            var del = [];
            var al = [];
            var aw = [];
            var ccl = [];
            // var main = document.querySelector('main');
            let cnt = <?php echo $cnt ?>;

            for (let i=0; i<cnt; i++) {

              let a = '.a' + i;
              let b = '.b' + i;
              let c = '.c' + i;
              let d = '.d' + i;

              del[i] = document.querySelector(a);
              al[i] = document.querySelector(b);
              aw[i] = document.querySelector(c);
              ccl[i] = document.querySelector(d);

              del[i].addEventListener('click', () =>{
                al[i].classList.remove('nodis');
                aw[i].classList.remove('nodis');
                // main.classList.add('no_scroll');
              });

              ccl[i].addEventListener('click', () =>{
                al[i].classList.add('nodis');
                aw[i].classList.add('nodis');
                // main.classList.remove('no_scroll');
              });

            }
          </script>

        </tbody>

      </table>

    <?php } else if ($nothit) {?>

      <p>候補が見つかりませんでした</p>

    <?php }} else {?>
      <h1><?php echo $_POST['year'].'年'.$_POST['month'].'月'.$_POST['date'].'日'.$_POST['timesc'] ?></h1>
      <h1><?php echo $_POST['resname'].'の予約をしました。' ?></h1>
    <?php }?>

    <a href="admin_reserve.php" class="back">予約に戻る</a>

  </div>

<?php require("admin_footer.php"); ?>