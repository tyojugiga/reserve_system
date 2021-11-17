<?php

require("admin_header.php");

$year = date("Y");
$month = date("n");
$dates;
$today = date("j");
$endDate;
$thisMonth;
$nextMonth;
$firstEnd;
$secondEnd;
$start;
$end;
$position;
$message = "";

$pdo = new PDO($dsn, $username, $password);

if ($_SERVER['REQUEST_METHOD'] === "POST") {

  validateToken();

  if (isset($_POST['addsc'])) {

    try {

      $pdo->beginTransaction();

      $cnt = 0;

      for ($i=0; $i<3; $i++) {

        if ($i !== 0) {
          $year = nextY($year, $month);
          $month = nextM($month);
        }

        $endDate = endDate($year, $month);

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
        $target = $stmt->fetchAll();

        if (empty($target)) {

          $stmt = $pdo->prepare(
            "INSERT INTO
              reservation_table (year,month,date,t0,t0_5,t1,t1_5,t2,t2_5,t3,t3_5,t4,t4_5,t5,t5_5,t6,t6_5,t7,t7_5,t8,t8_5,t9,t9_5,t10,t10_5,t11,t11_5)
            VALUES
              (:year,:month,:date,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1)"
          );

          $stmt->bindParam(':year', $year, PDO::PARAM_INT);
          $stmt->bindParam(':month', $month, PDO::PARAM_INT);
          $stmt->bindParam(':date', $dates, PDO::PARAM_INT);

          for ($dates=1; $dates<=$endDate; $dates++) {
            $stmt->execute();
          }

        } else {

          $cnt++;

        }

      }

      if ($cnt === 3) {
        $message = "既にスケジュールが登録されています。";
      }

      $pdo->commit();
      
    } catch (PDOException $e) {
      $pdo->rollBack();
      $message = $e->getMessage();
    }
    
  }

  if (isset($_POST['dlsc'])) {

    try {

      $pdo->beginTransaction();

      $stmt = $pdo->prepare(
        "DELETE FROM
          reservation_table
        WHERE
         month < :month &&
         year <= :year
        "
      );

      $stmt->bindValue(':month', date("n"));
      $stmt->bindValue(':year', date("Y"));
      $stmt->execute();

      $pdo->commit();

    } catch (PDOException $e) {
      $pdo->rollBack();
      $message = $e->getMessage();
    }


  }

  if (isset($_POST['edit'])) {

    try {

      $pdo->beginTransaction();

      $stmt = $pdo->prepare(
        "UPDATE
          reservation_table
        SET
          t0 = :t0,
          t0_5 = :t0_5,
          t1 = :t1,
          t1_5 = :t1_5,
          t2 = :t2,
          t2_5 = :t2_5,
          t3 = :t3,
          t3_5 = :t3_5,
          t4 = :t4,
          t4_5 = :t4_5,
          t5 = :t5,
          t5_5 = :t5_5,
          t6 = :t6,
          t6_5 = :t6_5,
          t7 = :t7,
          t7_5 = :t7_5,
          t8 = :t8,
          t8_5 = :t8_5,
          t9 = :t9,
          t9_5 = :t9_5,
          t10 = :t10,
          t10_5 = :t10_5,
          t11 = :t11,
          t11_5 = :t11_5
        WHERE
          id = :id"
      );

      $id = 0;
      $t = [];

      $stmt->bindParam(':id', $id, PDO::PARAM_INT);

      for ($i=0; $i<24; $i++) {

        if ($i%2 === 0) {

          $a = $i/2;
          $stmt->bindParam(':t'.$a, $t[$i], PDO::PARAM_INT);
          
        } else {
          
          $a = ($i-1)/2;
          $stmt->bindParam(':t'.$a.'_5', $t[$i], PDO::PARAM_INT);

        }

      }


      $i = $_POST['start'];

      foreach ($_POST['id'] as $ids) {

        $id = $ids;

        for ($j=0; $j<24; $j++) {

          if (isset($_POST['element1'][$i][$j])) {

            if ($_POST['element1'][$i][$j] === "-1") {
              $t[$j] = 0;
            } else {
              $t[$j] = -1;
            }
            
          } else {
            $t[$j] = $_POST['element2'][$i][$j];
          }
          
        }
        
        $stmt->execute();
        $i++;

      }

      $pdo->commit();

    } catch (PDOException $e) {
      $pdo->rollBack();
      $message = $e->getMessage();
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

$year = date("Y");
$month = date("n");

$stmt->bindParam(':year', $year, PDO::PARAM_INT);
$stmt->bindParam(':month', $month, PDO::PARAM_INT);
$stmt->execute();
$thisMonth = $stmt->fetchAll();

$year = nextY($year, $month);
$month = nextM($month);
$stmt->execute();
$nextMonth = $stmt->fetchAll();

?>

  <div class="right_contents">

    <h1>スケジュール</h1>

    <p><?php echo h($message) ?></p>

    <form action="" method="post" class="add_sc">
      <input type="hidden" name="token" value="<?php echo h($_SESSION['token']) ?>">
      <input type="submit" name="addsc" value="スケジュールの追加">
    </form>

    <form action="" method="post" class="add_sc">
      <input type="hidden" name="token" value="<?php echo h($_SESSION['token']) ?>">
      <input type="submit" name="dlsc" value="過去スケジュールの削除">
    </form>

    <form action="" method="post" class="add_sc">
      <input type="hidden" name="token" value="<?php echo h($_SESSION['token']) ?>">
      <input type="hidden" name="first">
      <input type="submit" name="thismonth" value="<?php echo $thisMonth[0][2] ?>月の表示">
    </form>

    <form action="" method="post" class="add_sc">
      <input type="hidden" name="token" value="<?php echo h($_SESSION['token']) ?>">
      <input type="hidden" name="first">
      <input type="submit" name="nextmonth" value="<?php echo $nextMonth[0][2] ?>月の表示">
    </form>

    <?php if (isset($_POST['thismonth']) && isset($_POST['first'])) { ?>

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
    
    <?php if (isset($_POST['thismonth']) || isset($_POST['nextmonth'])) { ?>
      
    <form action="" method="post">
    <table class="sc">

      <thead>

        <tr>

          <td>time</td>
          
        <?php

        if (isset($_POST['thismonth'])) {

          if (isset($_POST['first'])) {

            $end;

            if (count($thisMonth)-$today > 10) {

              $end = $today + 10;
              
            } else {

              $end = count($thisMonth);

            }

            for ($i=$today; $i<$end; $i++) {

              print("<td>".$thisMonth[$i][2]."/".$thisMonth[$i][3]."</td>");

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

            }
            
          } else {
            
            for ($i=$today+20; $i<count($thisMonth); $i++) {

              print("<td>".$thisMonth[$i][2]."/".$thisMonth[$i][3]."</td>");

            }

          }

        } else if (isset($_POST['nextmonth'])) {

          if (isset($_POST['first'])) {

            for ($i=0; $i<10; $i++) {

              print("<td>".$nextMonth[$i][2]."/".$nextMonth[$i][3]."</td>");

            }

          } else if (isset($_POST['second'])) {

            for ($i=10; $i<20; $i++) {

              print("<td>".$nextMonth[$i][2]."/".$nextMonth[$i][3]."</td>");

            }
            
          } else {
            
            for ($i=20; $i<count($nextMonth); $i++) {

              print("<td>".$nextMonth[$i][2]."/".$nextMonth[$i][3]."</td>");

            }

          }

        } ?>

        </tr>

      </thead>

      <tbody>

      <?php

        $cnt = 0;

        if (isset($_POST['thismonth'])) {

          if (count($thisMonth)-$today > 20) {
            $firstEnd = $today + 10;
            $secondEnd = $today + 20;
          } else if (count($thisMonth)-$today >10) {
            $firstEnd = $today + 10;
            $secondEnd = count($thisMonth);
          } else {
            $firstEnd = count($thisMonth);
          }

          if (isset($_POST['first'])) {
            $start = $today;
            $end = $firstEnd;
            $position = "first";
          } else if (isset($_POST['second'])) {
            $start = $firstEnd;
            $end = $secondEnd;
            $position = "second";
          } else {
            $start = $secondEnd;
            $end = count($thisMonth);
            $position = "third";
          }

          for ($i=4; $i<=27; $i++) {

            $gray = '';
  
            if ($cnt%2 !== 0) {
              $gray = 'class="gray"';
            }
  
            print("<tr ".$gray.">");

            ?>

            <input type="hidden" name="thismonth">

            <td>

              <?php echo timesc($i-4) ?>

            </td>

            <?php
            
            for ($j=$start; $j<$end; $j++) {
  
              $sym = sym($thisMonth[$j][$i]);
              $symcolor = symcolor($thisMonth[$j][$i]);
              $disabled = disabled($thisMonth[$j][$i]);

              print('<td>');
              
              ?>
  
                <label <?php echo $symcolor ?>>
                  <input type="checkbox" name="element1[<?php echo $j ?>][<?php echo $i-4 ?>]" value="<?php echo $thisMonth[$j][$i] ?>" <?php echo $disabled ?>>
                  <input type="hidden" name="element2[<?php echo $j ?>][<?php echo $i-4 ?>]" value="<?php echo $thisMonth[$j][$i] ?>">
                  <input type="hidden" name="id[<?php echo $j ?>]" value="<?php echo $thisMonth[$j][0] ?>">
                  <input type="hidden" name="<?php echo $position ?>">
                  <?php echo $sym ?>
                </label>
  
              <?php
  
              print('</td>');
  
            }
  
            print("</tr>");
  
          }

        }

        if (isset($_POST['nextmonth'])) {

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

          for ($i=4; $i<=27; $i++) {
  
            $gray = '';
  
            if ($i%2 !== 0) {
              $gray = 'class="gray"';
            }
  
            print("<tr ".$gray.">");
  
            ?>

            <input type="hidden" name="nextmonth">

            <td>

              <?php echo timesc($i-4) ?>

            </td>

            <?php
              
            for ($j=$start; $j<$end; $j++) {
  
              $sym = sym($nextMonth[$j][$i]);
              $symcolor = symcolor($nextMonth[$j][$i]);
              $disabled = disabled($nextMonth[$j][$i]);
  
              print('<td>');
  
              ?>
  
                <label <?php echo $symcolor ?>>
                  <input type="checkbox" name="element1[<?php echo $j ?>][<?php echo $i-4 ?>]" value="<?php echo $nextMonth[$j][$i] ?>" <?php echo $disabled ?>>
                  <input type="hidden" name="element2[<?php echo $j ?>][<?php echo $i-4 ?>]" value="<?php echo $nextMonth[$j][$i] ?>">
                  <input type="hidden" name="id[<?php echo $j ?>]" value="<?php echo $nextMonth[$j][0] ?>">
                  <input type="hidden" name="<?php echo $position ?>">
                  <?php echo $sym ?>
                </label>
  
              <?php
  
              print('</td>');
  
            }
              
            print("</tr>");
                
          }

        }

      ?>

      </tbody>      

    </table>

    <div class="scedit">スケジュールの編集</div>
    <div class="alert nodis"></div>
    <div class="alert_window nodis">
      
      <p>！編集します！</p>
      <p>よろしいですか</p>
      <input type="hidden" name="token" value="<?php echo h($_SESSION['token']) ?>">
      <input type="hidden" name="start" value="<?php echo h($start) ?>">
      <input type="submit" name="edit" value="編集する">
      <div class="cancel">キャンセル</div>
      
    </div>
    </form>

    <?php } ?>


  </div>

<?php require("admin_footer.php"); ?>