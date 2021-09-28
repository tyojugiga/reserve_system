<?php

require("user_header.php");

$dates;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  
  validateToken();
  $pdo = new PDO($dsn, $username, $password);
  
  if (isset($_POST['maru'])) {

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
        $stmt->bindValue(':userid', h($_SESSION['userid']));
        $stmt->execute();
        
        $stmt = $pdo->prepare(
          "INSERT INTO
            history (user_id,name,year,month,date,time)
          VALUES
            (:user_id,:name,:year,:month,:date,:time)"
        );
        
        $stmt->bindValue(':user_id', h($_SESSION['userid']));
        $stmt->bindValue(':name', h($_SESSION['username']));
        $stmt->bindValue(':year', h($_POST['year']));
        $stmt->bindValue(':month', h($_POST['month']));
        $stmt->bindValue(':date', h($_POST['date']));
        $stmt->bindValue(':time', h(timesc($_POST['time']-4)));
        $stmt->execute();
        
      }

      $pdo->commit();

      define('headers', "From: admin@example.com\nBCC: admin@example.com");
      $subject = "ご予約ありがとうございます";
      $message = $_SESSION['username'] . " さま\n\n\n"
           . "ご予約ありがとうございます。\n"
           . "【ご予約内容】\n"
           . $_POST['year']."年".$_POST['month']."月".$_POST['date']."日".timesc($_POST['time']-4)."\n\n"
           . "こちらは自動送信メールです。\n";
           
      mail($_SESSION['mail'], $subject, $message, headers);

    } catch (PDOException $e) {
  
      $pdo->rollBack();
      print($e->getMessage());
  
    }

  }

  if (isset($_POST['kome'])) {

    try {

      $pdo->beginTransaction();

      switch ($_POST['time']) {

        case "4":
          $stmt = $pdo->prepare(
            "UPDATE
              reservation_table
            SET
              t0 = 0
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
              t0_5 = 0
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
              t1 = 0
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
              t1_5 = 0
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
              t2 = 0
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
              t2_5 = 0
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
              t3 = 0
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
              t3_5 = 0
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
              t4 = 0
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
              t4_5 = 0
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
              t5 = 0
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
              t5_5 = 0
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
              t6 = 0
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
              t6_5 = 0
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
              t7 = 0
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
              t7_5 = 0
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
              t8 = 0
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
              t8_5 = 0
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
              t9 = 0
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
              t9_5 = 0
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
              t10 = 0
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
              t10_5 = 0
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
              t11 = 0
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
              t11_5 = 0
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

      $stmt->bindValue(':userid', h($_SESSION['userid']));
      $stmt->bindValue(':year', h($_POST['year']));
      $stmt->bindValue(':month', h($_POST['month']));
      $stmt->bindValue(':date', h($_POST['date']));
      $stmt->bindValue(':time', h(timesc($_POST['time']-4)));
      $stmt->execute();

      $pdo->commit();

      define('headers', "From: admin@example.com\nBCC: admin@example.com");
      $subject = "予約を取り消しました";
      $message = $_SESSION['username'] . " さま\n\n\n"
           . "予約を取り消しました。\n"
           . "【予約取り消し日時】\n"
           . $_POST['year']."年".$_POST['month']."月".$_POST['date']."日".timesc($_POST['time']-4)."\n\n"
           . "こちらは自動送信メールです。\n";
           
      mail($_SESSION['mail'], $subject, $message, headers);

    } catch (PDOException $e) {
  
      $pdo->rollBack();
      print($e->getMessage());
  
    }

  }

  if (isset($_POST['reserve'])) {
  
    try {
  
      $stmt = $pdo->prepare(
        "SELECT
          *
        FROM
          reservation_table
        WHERE
          year = :year &&
          month = :month &&
          date = :date
          "
      );
  
      $stmt->bindValue(':year', h($_POST['year']));
      $stmt->bindValue(':month', h($_POST['month']));
      $stmt->bindValue(':date', h($_POST['date']));
      $stmt->execute();
      $dates = $stmt->fetchAll();
      
    } catch (PDOException $e) {
  
      print($e->getMessage());
  
    }

  }

}

?>

  <div class="right_contents">

    <h1><?php echo h($_POST['year']).'年'.h($_POST['month']).'月'.h($_POST['date']).'の予約' ?></h1>

    <table class="search_result">

      <thead>
        <tr>
          <td>時間</td>
          <td>予約状況</td>
        </tr>
      </thead>

      <tbody>

        <?php
        
          $cnt = 0;
          foreach($dates as $date) {
            
            for ($i=0;$i<24;$i++) {

              $j = $i + 4;

              $gray = '';
  
              if ($cnt%2 !== 0) {
                $gray = 'class="gray"';
              }
  
              print("<tr ".$gray.">");

              print('<td>'.timesc($i).'</td>');

              if($date[$j] === "0") {

                ?>
                <td>
                  <form action="" method="post">
                    <input type="hidden" name="token" value="<?php echo h($_SESSION['token']) ?>">
                    <input type="hidden" name="timeid" value="<?php echo h($date[0]) ?>">
                    <input type="hidden" name="year" value="<?php echo h($_POST['year']) ?>">
                    <input type="hidden" name="month" value="<?php echo h($_POST['month']) ?>">
                    <input type="hidden" name="date" value="<?php echo h($_POST['date']) ?>">
                    <input type="hidden" name="time" value="<?php echo h($j) ?>">
                    <input type="hidden" name="reserve">
  
                    <div class="delete maru <?php echo 'a'.$cnt ?>">〇</div>
                    <div class="alert <?php echo 'b'.$cnt ?> nodis"></div>
                    <div class="alert_window spw <?php echo 'c'.$cnt ?> nodis">
                      
                      <p>！予約します！</p>
                      <p>よろしいですか？</p>
                      <input type="submit" name="maru" value="予約する">
                      <div class="cancel <?php echo 'd'.$cnt ?>">キャンセル</div>
                      
                    </div>
                    
                  </form>
                </td>
                <?php

              } else if($date[$j] === $_SESSION['userid']) {

                ?>
                <td>
                  <form action="" method="post">
                  <input type="hidden" name="token" value="<?php echo h($_SESSION['token']) ?>">
                    <input type="hidden" name="timeid" value="<?php echo h($date[0]) ?>">
                    <input type="hidden" name="year" value="<?php echo h($_POST['year']) ?>">
                    <input type="hidden" name="month" value="<?php echo h($_POST['month']) ?>">
                    <input type="hidden" name="date" value="<?php echo h($_POST['date']) ?>">
                    <input type="hidden" name="time" value="<?php echo h($j) ?>">
                    <input type="hidden" name="reserve">
  
                    <div class="delete kome <?php echo 'a'.$cnt ?>">※</div>
                    <div class="alert <?php echo 'b'.$cnt ?> nodis"></div>
                    <div class="alert_window spw <?php echo 'c'.$cnt ?> nodis">
                      
                      <p>！予約を取り消します！</p>
                      <p>よろしいですか？</p>
                      <input type="submit" name="kome" value="予約を取り消す">
                      <div class="cancel <?php echo 'd'.$cnt ?>">キャンセル</div>
                      
                    </div>
                    
                  </form>
                </td>
                <?php

              } else {

                ?> <td><div class="batu">×</div></td> <?php

              }

              print("</tr>");
            
              $cnt++;

            }
          
            
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

            if (del[i] !== null) {

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


          }
        </script>

      </tbody>

    </table>

    <a href="user_top.php" class="back">戻る</a>

  </div>

<?php require("admin_footer.php"); ?>