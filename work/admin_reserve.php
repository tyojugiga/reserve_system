<?php

require("admin_header.php");

$year = date("Y");
$month = date("n");
$dates;
$today = date("j");
$endDate;
$thisMonth;
$nextMonth;
$message = "";

$pdo = new PDO($dsn, $username, $password);

if ($_SERVER['REQUEST_METHOD'] === "POST") {

  validateToken();

  if (isset($_POST['batu'])) {

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

      $pdo->commit();

    } catch (PDOException $e) {
  
      $pdo->rollBack();
      print($e->getMessage());
  
    }

  }

}

$stmt = $pdo->prepare(
  "SELECT
    *
  From
    reservation_table
  WHERE
    year = :year &&
    month = :month"
);

$stmt->bindParam(':year', $year, PDO::PARAM_INT);
$stmt->bindParam(':month', $month, PDO::PARAM_INT);
$stmt->execute();
$thisMonth = $stmt->fetchAll();

$year = nextY($year, $month);
$month = nextM($month);
$stmt->execute();
$nextMonth = $stmt->fetchAll();

$displayMonth;

if (isset($_POST['thismonth']) || !isset($_POST['nextmonth'])) {
  $displayMonth = $thisMonth[0][2];
} else {
  $displayMonth = $nextMonth[0][2];
}

?>

  <div class="right_contents">

    <h1><?php echo $displayMonth ?>月の予約状況</h1>

    <p><?php echo h($message) ?></p>

    <?php if (($_SERVER['REQUEST_METHOD'] !== "POST") || (isset($_POST['thismonth']) && isset($_POST['first']))) { ?>

      <?php if ((count($thisMonth)-$today) > 10) { ?>

        <form action="" method="post" class="add_sc ten">
          <input type="hidden" name="token" value="<?php echo h($_SESSION['token']) ?>">
          <input type="hidden" name="second">
          <input type="submit" name="thismonth" value="次の10日>">
        </form>

      <?php } ?>
      
    <?php } else if (isset($_POST['thismonth']) && isset($_POST['second'])) { ?>
      
      <form action="" method="post" class="add_sc ten">
        <input type="hidden" name="token" value="<?php echo h($_SESSION['token']) ?>">
        <input type="hidden" name="first">
        <input type="submit" name="thismonth" value="<前の10日">
      </form>

      <?php if ((count($thisMonth)-$today) > 20) { ?>

        <form action="" method="post" class="add_sc ten">
          <input type="hidden" name="token" value="<?php echo h($_SESSION['token']) ?>">
          <input type="hidden" name="third">
          <input type="submit" name="thismonth" value="次の10日>">
        </form>

      <?php } ?>

    <?php } else if (isset($_POST['thismonth']) && isset($_POST['third'])) { ?>

        <form action="" method="post" class="add_sc ten">
          <input type="hidden" name="token" value="<?php echo h($_SESSION['token']) ?>">
          <input type="hidden" name="second">
          <input type="submit" name="thismonth" value="<前の10日">
        </form>

    <?php } ?>

    <?php if (isset($_POST['nextmonth']) && isset($_POST['first'])) { ?>

      <form action="" method="post" class="add_sc ten">
        <input type="hidden" name="token" value="<?php echo h($_SESSION['token']) ?>">
        <input type="hidden" name="second">
        <input type="submit" name="nextmonth" value="次の10日>">
      </form>
      
    <?php } else if (isset($_POST['nextmonth']) && isset($_POST['second'])) { ?>
      
      <form action="" method="post" class="add_sc ten">
        <input type="hidden" name="token" value="<?php echo h($_SESSION['token']) ?>">
        <input type="hidden" name="first">
        <input type="submit" name="nextmonth" value="<前の10日">
      </form>
      
      <form action="" method="post" class="add_sc ten">
        <input type="hidden" name="token" value="<?php echo h($_SESSION['token']) ?>">
        <input type="hidden" name="third">
        <input type="submit" name="nextmonth" value="次の10日>">
      </form>

    <?php } else if (isset($_POST['nextmonth']) && isset($_POST['third'])) { ?>

        <form action="" method="post" class="add_sc ten">
          <input type="hidden" name="token" value="<?php echo h($_SESSION['token']) ?>">
          <input type="hidden" name="second">
          <input type="submit" name="nextmonth" value="<前の10日">
        </form>

    <?php } ?>

    <?php if (!isset($_POST['nextmonth']) || isset($_POST['thismonth'])) { ?>
      
      <form action="" method="post" class="add_sc">
        <input type="hidden" name="token" value="<?php echo h($_SESSION['token']) ?>">
        <input type="hidden" name="first">
        <input type="submit" name="nextmonth" value="<?php echo $nextMonth[0][2] ?>月の予約">
      </form>

    <?php } else { ?>
      
      <form action="" method="post" class="add_sc">
        <input type="hidden" name="token" value="<?php echo h($_SESSION['token']) ?>">
        <input type="hidden" name="first">
        <input type="submit" name="thismonth" value="<?php echo $thisMonth[0][2] ?>月の予約">
      </form>
      
    <?php } ?>
    
    <table class="sc res">

      <thead>
        <tr>
          <td>time</td>

          <?php

          $cnt = 0;
          
          if (isset($_POST['thismonth']) || ($_SERVER['REQUEST_METHOD'] !== "POST")) {

            if (isset($_POST['first']) || ($_SERVER['REQUEST_METHOD'] !== "POST")) {

              $end;

              if (count($thisMonth)-$today > 10) {

                $end = $today + 10;
                
              } else {

                $end = count($thisMonth);

              }

              for ($i=$today; $i<$end; $i++) {
  
                print("<td>".$thisMonth[$i][2]."/".$thisMonth[$i][3]."</td>");
                $cnt++;
  
              }

            } else if (isset($_POST['second'])) {

              $end;

              if (count($thisMonth)-$today > 20) {

                $end = $today + 20;
                
              } else {
                
                $end = count($thisMonth);

              }

              for ($i=$today+10; $i<$end; $i++) {
  
                print("<td>".$thisMonth[$i][2]."/".$thisMonth[$i][3]."</td>");
                $cnt++;
  
              }
              
            } else {
              
              for ($i=$today+20; $i<count($thisMonth); $i++) {
  
                print("<td>".$thisMonth[$i][2]."/".$thisMonth[$i][3]."</td>");
                $cnt++;
  
              }

            }

          } else if (isset($_POST['nextmonth'])) {

            if (isset($_POST['first'])) {

              for ($i=0; $i<10; $i++) {
  
                print("<td>".$nextMonth[$i][2]."/".$nextMonth[$i][3]."</td>");
                $cnt++;
  
              }

            } else if (isset($_POST['second'])) {

              for ($i=10; $i<20; $i++) {
  
                print("<td>".$nextMonth[$i][2]."/".$nextMonth[$i][3]."</td>");
                $cnt++;
  
              }
              
            } else {
              
              for ($i=20; $i<count($nextMonth); $i++) {
  
                print("<td>".$nextMonth[$i][2]."/".$nextMonth[$i][3]."</td>");
                $cnt++;
  
              }

            }

          } ?>
          
        </tr>

      </thead>

      <tbody>

      <?php


      for($i=4; $i<=27; $i++) {

        $k = $i - 4;

        $gray = '';

        if ($i%2 !== 0) {
          $gray = 'class="gray"';
        }

        print("<tr ".$gray.">");

      ?>

      <td>

        <?php echo timesc($i-4) ?>

      </td>

      <?php

        $cnt2 = 0;

        if (isset($_POST['thismonth']) || ($_SERVER['REQUEST_METHOD'] !== "POST")) {

          $firstEnd;
          $secondEnd;

          if (count($thisMonth)-$today > 20) {
            $firstEnd = $today + 10;
            $secondEnd = $today + 20;
          } else if (count($thisMonth)-$today >10) {
            $firstEnd = $today + 10;
            $secondEnd = count($thisMonth);
          } else {
            $firstEnd = count($thisMonth);
          }

          if (isset($_POST['first']) || ($_SERVER['REQUEST_METHOD'] !== "POST")) {

            for ($j=$today; $j<$firstEnd; $j++) {

              $sym = sym($thisMonth[$j][$i]);
              $symcolor = symcolor($thisMonth[$j][$i]);

              print('<td class="e'.$cnt2.$k.'">');
              if ($thisMonth[$j][$i] === "0") {
              ?>

                <form action="admin_reserve_user.php" method="post">
                  <input type="hidden" name="token" value="<?php echo h($_SESSION['token']) ?>">
                  <input type="hidden" name="timeid" value="<?php echo h($thisMonth[$j][0]) ?>">
                  <input type="hidden" name="year" value="<?php echo h($thisMonth[$j][1]) ?>">
                  <input type="hidden" name="month" value="<?php echo h($thisMonth[$j][2]) ?>">
                  <input type="hidden" name="date" value="<?php echo h($thisMonth[$j][3]) ?>">
                  <input type="hidden" name="time" value="<?php echo h($i) ?>">
                  <input type="hidden" name="timesc" value="<?php echo h(timesc($i-4)) ?>">
                  <input type="hidden" name="name" value="">
                  <input type="submit" value="<?php echo $sym ?>" class="maru">
                </form>
  
              <?php } else if ($thisMonth[$j][$i] === "-1") { ?>

                <form action="" method="post">
                  <input type="hidden" name="token" value="<?php echo h($_SESSION['token']) ?>">
                  <input type="hidden" name="timeid" value="<?php echo h($thisMonth[$j][0]) ?>">
                  <input type="hidden" name="time" value="<?php echo h($i) ?>">
                  <input type="hidden" name="thismonth">
                  <input type="hidden" name="first">

                  <div class="delete batu <?php echo 'a'.$cnt2.$k ?>">×</div>
                  <div class="alert <?php echo 'b'.$cnt2.$k ?> nodis"></div>
                  <div class="alert_window <?php echo 'c'.$cnt2.$k ?> nodis">
                    
                    <p>！予約可能にする！</p>
                    <p>よろしいですか？</p>
                    <input type="submit" name="batu" value="予約可にする">
                    <div class="cancel <?php echo 'd'.$cnt2.$k ?>">キャンセル</div>
                    
                  </div>
                  
                </form>
  
              <?php } else { ?>

                <form action="admin_reserve_cancel.php" method="post">
                  <input type="hidden" name="token" value="<?php echo h($_SESSION['token']) ?>">
                  <input type="hidden" name="timeid" value="<?php echo h($thisMonth[$j][0]) ?>">
                  <input type="hidden" name="year" value="<?php echo h($thisMonth[$j][1]) ?>">
                  <input type="hidden" name="month" value="<?php echo h($thisMonth[$j][2]) ?>">
                  <input type="hidden" name="date" value="<?php echo h($thisMonth[$j][3]) ?>">
                  <input type="hidden" name="time" value="<?php echo h($i) ?>">
                  <input type="hidden" name="timesc" value="<?php echo h(timesc($i-4)) ?>">
                  <input type="hidden" name="userid" value="<?php echo h($thisMonth[$j][$i]) ?>">
                  <input type="submit" name="cancel" value="<?php echo $sym ?>" class="kome">
                </form>
  
              <?php

              }

              print('</td>');

              $cnt2++;
  
            }

          } else if (isset($_POST['second'])) {

            for ($j=$firstEnd; $j<$secondEnd; $j++) {

              $sym = sym($thisMonth[$j][$i]);
              $symcolor = symcolor($thisMonth[$j][$i]);

              print('<td class="e'.$cnt2.$k.'">');
              if ($thisMonth[$j][$i] === "0") {
              ?>

                <form action="admin_reserve_user.php" method="post">
                  <input type="hidden" name="token" value="<?php echo h($_SESSION['token']) ?>">
                  <input type="hidden" name="timeid" value="<?php echo h($thisMonth[$j][0]) ?>">
                  <input type="hidden" name="year" value="<?php echo h($thisMonth[$j][1]) ?>">
                  <input type="hidden" name="month" value="<?php echo h($thisMonth[$j][2]) ?>">
                  <input type="hidden" name="date" value="<?php echo h($thisMonth[$j][3]) ?>">
                  <input type="hidden" name="time" value="<?php echo h($i) ?>">
                  <input type="hidden" name="timesc" value="<?php echo h(timesc($i-4)) ?>">
                  <input type="hidden" name="name" value="">
                  <input type="submit" value="<?php echo $sym ?>" class="maru">
                </form>
  
              <?php } else if ($thisMonth[$j][$i] === "-1") { ?>

                <form action="" method="post">
                  <input type="hidden" name="token" value="<?php echo h($_SESSION['token']) ?>">
                  <input type="hidden" name="timeid" value="<?php echo h($thisMonth[$j][0]) ?>">
                  <input type="hidden" name="time" value="<?php echo h($i) ?>">
                  <input type="hidden" name="thismonth">
                  <input type="hidden" name="second">

                  <div class="delete batu <?php echo 'a'.$cnt2.$k ?>">×</div>
                  <div class="alert <?php echo 'b'.$cnt2.$k ?> nodis"></div>
                  <div class="alert_window <?php echo 'c'.$cnt2.$k ?> nodis">
                    
                    <p>！予約可能にする！</p>
                    <p>よろしいですか？</p>
                    <input type="submit" name="batu" value="予約可にする">
                    <div class="cancel <?php echo 'd'.$cnt2.$k ?>">キャンセル</div>
                    
                  </div>
                  
                </form>
  
              <?php } else { ?>

                <form action="admin_reserve_cancel.php" method="post">
                  <input type="hidden" name="token" value="<?php echo h($_SESSION['token']) ?>">
                  <input type="hidden" name="timeid" value="<?php echo h($thisMonth[$j][0]) ?>">
                  <input type="hidden" name="year" value="<?php echo h($thisMonth[$j][1]) ?>">
                  <input type="hidden" name="month" value="<?php echo h($thisMonth[$j][2]) ?>">
                  <input type="hidden" name="date" value="<?php echo h($thisMonth[$j][3]) ?>">
                  <input type="hidden" name="time" value="<?php echo h($i) ?>">
                  <input type="hidden" name="timesc" value="<?php echo h(timesc($i-4)) ?>">
                  <input type="hidden" name="userid" value="<?php echo h($thisMonth[$j][$i]) ?>">
                  <input type="submit" name="cancel" value="<?php echo $sym ?>" class="kome">
                </form>
  
              <?php

              }

              print('</td>');

              $cnt2++;
  
            }

          } else {

            for ($j=$secondEnd; $j<count($thisMonth); $j++) {

              $sym = sym($thisMonth[$j][$i]);
              $symcolor = symcolor($thisMonth[$j][$i]);

              print('<td class="e'.$cnt2.$k.'">');
              if ($thisMonth[$j][$i] === "0") {
              ?>

                <form action="admin_reserve_user.php" method="post">
                  <input type="hidden" name="token" value="<?php echo h($_SESSION['token']) ?>">
                  <input type="hidden" name="timeid" value="<?php echo h($thisMonth[$j][0]) ?>">
                  <input type="hidden" name="year" value="<?php echo h($thisMonth[$j][1]) ?>">
                  <input type="hidden" name="month" value="<?php echo h($thisMonth[$j][2]) ?>">
                  <input type="hidden" name="date" value="<?php echo h($thisMonth[$j][3]) ?>">
                  <input type="hidden" name="time" value="<?php echo h($i) ?>">
                  <input type="hidden" name="timesc" value="<?php echo h(timesc($i-4)) ?>">
                  <input type="hidden" name="name" value="">
                  <input type="submit" value="<?php echo $sym ?>" class="maru">
                </form>
  
              <?php } else if ($thisMonth[$j][$i] === "-1") { ?>

                <form action="" method="post">
                  <input type="hidden" name="token" value="<?php echo h($_SESSION['token']) ?>">
                  <input type="hidden" name="timeid" value="<?php echo h($thisMonth[$j][0]) ?>">
                  <input type="hidden" name="time" value="<?php echo h($i) ?>">
                  <input type="hidden" name="thismonth">
                  <input type="hidden" name="third">

                  <div class="delete batu <?php echo 'a'.$cnt2.$k ?>">×</div>
                  <div class="alert <?php echo 'b'.$cnt2.$k ?> nodis"></div>
                  <div class="alert_window <?php echo 'c'.$cnt2.$k ?> nodis">
                    
                    <p>！予約可能にする！</p>
                    <p>よろしいですか？</p>
                    <input type="submit" name="batu" value="予約可にする">
                    <div class="cancel <?php echo 'd'.$cnt2.$k ?>">キャンセル</div>
                    
                  </div>
                  
                </form>
  
              <?php } else { ?>

                <form action="admin_reserve_cancel.php" method="post">
                  <input type="hidden" name="token" value="<?php echo h($_SESSION['token']) ?>">
                  <input type="hidden" name="timeid" value="<?php echo h($thisMonth[$j][0]) ?>">
                  <input type="hidden" name="year" value="<?php echo h($thisMonth[$j][1]) ?>">
                  <input type="hidden" name="month" value="<?php echo h($thisMonth[$j][2]) ?>">
                  <input type="hidden" name="date" value="<?php echo h($thisMonth[$j][3]) ?>">
                  <input type="hidden" name="time" value="<?php echo h($i) ?>">
                  <input type="hidden" name="timesc" value="<?php echo h(timesc($i-4)) ?>">
                  <input type="hidden" name="userid" value="<?php echo h($thisMonth[$j][$i]) ?>">
                  <input type="submit" name="cancel" value="<?php echo $sym ?>" class="kome">
                </form>
  
              <?php

              }

              print('</td>');

              $cnt2++;
  
            }

          }
          
        } else if (isset($_POST['nextmonth'])) {

          $start;
          $end;
          $position;
          
          if (isset($_POST['first'])) {
            
            $start = 0;
            $end = 10;
            $position = "first";
            
          } else if (isset($_POST['second'])) {
            
            $start = 10;
            $end = 20;
            $position = "second";
            
          } else {

            $start = 20;
            $end = count($nextMonth);
            $position = "third";
            
          }

          for ($j=$start; $j<$end; $j++) {

            $sym = sym($nextMonth[$j][$i]);
            $symcolor = symcolor($nextMonth[$j][$i]);

            print('<td class="e'.$cnt2.$k.'">');
            if ($nextMonth[$j][$i] === "0") {
            ?>

              <form action="admin_reserve_user.php" method="post">
                <input type="hidden" name="token" value="<?php echo h($_SESSION['token']) ?>">
                <input type="hidden" name="timeid" value="<?php echo h($nextMonth[$j][0]) ?>">
                <input type="hidden" name="year" value="<?php echo h($nextMonth[$j][1]) ?>">
                <input type="hidden" name="month" value="<?php echo h($nextMonth[$j][2]) ?>">
                <input type="hidden" name="date" value="<?php echo h($nextMonth[$j][3]) ?>">
                <input type="hidden" name="time" value="<?php echo h($i) ?>">
                <input type="hidden" name="timesc" value="<?php echo h(timesc($i-4)) ?>">
                <input type="hidden" name="name" value="">
                <input type="submit" value="<?php echo $sym ?>" class="maru">
              </form>

            <?php } else if ($nextMonth[$j][$i] === "-1") { ?>

              <form action="" method="post">
                <input type="hidden" name="token" value="<?php echo h($_SESSION['token']) ?>">
                <input type="hidden" name="timeid" value="<?php echo h($nextMonth[$j][0]) ?>">
                <input type="hidden" name="time" value="<?php echo h($i) ?>">
                <input type="hidden" name="nextmonth">
                <input type="hidden" name=<?php echo $position ?>>

                <div class="delete batu <?php echo 'a'.$cnt2.$k ?>">×</div>
                <div class="alert <?php echo 'b'.$cnt2.$k ?> nodis"></div>
                <div class="alert_window <?php echo 'c'.$cnt2.$k ?> nodis">
                  
                  <p>！予約可能にする！</p>
                  <p>よろしいですか？</p>
                  <input type="submit" name="batu" value="予約可にする">
                  <div class="cancel <?php echo 'd'.$cnt2.$k ?>">キャンセル</div>
                  
                </div>
                
              </form>

            <?php } else { ?>

              <form action="admin_reserve_cancel.php" method="post">
                <input type="hidden" name="token" value="<?php echo h($_SESSION['token']) ?>">
                <input type="hidden" name="timeid" value="<?php echo h($nextMonth[$j][0]) ?>">
                <input type="hidden" name="year" value="<?php echo h($nextMonth[$j][1]) ?>">
                <input type="hidden" name="month" value="<?php echo h($nextMonth[$j][2]) ?>">
                <input type="hidden" name="date" value="<?php echo h($nextMonth[$j][3]) ?>">
                <input type="hidden" name="time" value="<?php echo h($i) ?>">
                <input type="hidden" name="timesc" value="<?php echo h(timesc($i-4)) ?>">
                <input type="hidden" name="userid" value="<?php echo h($nextMonth[$j][$i]) ?>">
                <input type="submit" name="cancel" value="<?php echo $sym ?>" class="kome">
              </form>

            <?php

            }

            print('</td>');

            $cnt2++;

          }
            
        }

      print("</tr>");

    }

      ?>

      </tbody>

    </table>

    <script>
      var del = [];
      var al = [];
      var aw = [];
      var ccl = [];
      var element = [];
      // var main = document.querySelector('main');
      let cnt = <?php echo $cnt ?>;

      for (let i=0; i<cnt; i++) {
        del[i] = [];
        al[i] = [];
        aw[i] = [];
        ccl[i] = [];
        element[i] = [];
      }

      for (let i=0; i<cnt; i++) {

        for (let j=0; j<24; j++) {


          let a = '.a' + String(i) + String(j);
          let b = '.b' + String(i) + String(j);
          let c = '.c' + String(i) + String(j);
          let d = '.d' + String(i) + String(j);
          let e = '.e' + String(i) + String(j);

          del[i][j] = document.querySelector(a);
          al[i][j] = document.querySelector(b);
          aw[i][j] = document.querySelector(c);
          ccl[i][j] = document.querySelector(d);
          element[i][j] = document.querySelector(e);

          if (del[i][j] !== null) {

            del[i][j].addEventListener('click', () =>{
              al[i][j].classList.remove('nodis');
              aw[i][j].classList.remove('nodis');
              element[i][j].classList.add('element');
              // main.classList.add('no_scroll');
            });

            ccl[i][j].addEventListener('click', () =>{
              al[i][j].classList.add('nodis');
              aw[i][j].classList.add('nodis');
              element[i][j].classList.remove('element');
              // main.classList.remove('no_scroll');
            });

          }

        }

      }
    </script>

  </div>

<?php require("admin_footer.php"); ?>