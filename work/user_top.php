<?php

require("user_header.php");

$pdo = new PDO($dsn, $username, $password);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  validateToken();

  if (isset($_POST['prev'])) {

    $_SESSION['month']--;

    if ($_SESSION['month'] === 0) {
      $_SESSION['month'] = 12;
      $_SESSION['year']--;
    }

  } else if (isset($_POST['next'])) {

    $_SESSION['month']++;

    if ($_SESSION['month'] === 13) {
      $_SESSION['month'] = 1;
      $_SESSION['year']++;
    }

  }

} else {
  $_SESSION['year'] = date("Y");
  $_SESSION['month'] = date("n");
}

$dates = [];
$dayCount = 1;
$year = $_SESSION['year'];
$month = $_SESSION['month'];
$startDate;
$endDate;

if ($month < 10) {
  $startDate = date($year."-0".$month."-01");
  $endDate = date($year."-0".$month."-t");
} else {
  $startDate = date($year."-".$month."-01");
  $endDate = date($year."-".$month."-t");
}

$startWeek = date("w", strtotime($startDate));
$nowEndDate = date("t", strtotime($startDate));
$endWeek = 6 - date("w", strtotime($endDate));
$td = date("j");
$tm = date("n");
$ty = date("Y");

add_date($dates,$startWeek,$nowEndDate,$endWeek);

$stmt = $pdo->prepare(
  "SELECT
    *
  From
    reservation_table
    WHERE
    year = :year &&
    month = :month
  "
);

$stmt->bindParam(':year', $year, PDO::PARAM_INT);
$stmt->bindParam(':month', $month, PDO::PARAM_INT);
$stmt->execute();
$thisMonth = $stmt->fetchAll();

?>

  <div class="right_contents">

  <h1>予約カレンダー</h1>
    <div class="calender spw">
      <table>
  
        <thead>
          <tr>
            <th>
              <form action="" method="post">
                <input type="hidden" name="token" value="<?php echo h($_SESSION['token']) ?>">
                <input type="submit" name="prev" value="＜" class="arrow">
              </form>
            </th>
    
            <th colspan="5"><?php echo h($year) . "年" . h($month) . "月" ?></th>
    
            <th>
              <form action="" method="post">
                <input type="hidden" name="token" value="<?php echo h($_SESSION['token']) ?>">
                <input type="submit" name="next" value="＞" class="arrow">
              </form>
            </th>
          </tr>
  
          <tr>
          <th class="sunday">日</th>
            <th>月</th>
            <th>火</th>
            <th>水</th>
            <th>木</th>
            <th>金</th>
            <th class="saturday">土</th>
          </tr>
    
        </thead>
  
        <tbody>
  
          <?php
            $m = (string)$month;
            $y = (string)$year;
            $flag = true;
            for ($i=0; $i<count($dates); $i++) {
  
              $d = (string)$dates[$i];
              $m = (string)$month;
              $y = (string)$year;
              $t = today($d,$td,$m,$tm,$y,$ty);
              $disabled = passedDay($d,$td,$m,$tm,$y,$ty);
  
              if(($i+1)%7 === 1) {
                
                if (empty($dates[$i]) && !isset($dates[$i+6])) {
                  $flag = false;
                }
                
                if ($flag) {
                  
                  echo '<tr>';
                  echo '<td class="sunday '.$t.$disabled.'">';
                  echo '<div class="day">';

                  ?>
                    <form action="user_reserve.php" method="post">

                      <input type="hidden" name="token" value="<?php echo h($_SESSION['token']) ?>">
                      <input type="hidden" name="year" value="<?php echo h($year) ?>">
                      <input type="hidden" name="month" value="<?php echo h($month) ?>">
                      <input type="hidden" name="date" value="<?php echo h($dates[$i]) ?>">
                      <input type="submit" name="reserve" value="<?php echo $dates[$i] ?>" <?php echo $disabled ?>>

                    </form>
                  <?php
                  echo '</div>';
  
                  if (!empty($dates[$i])) {
                    for ($j=4; $j<=27; $j++) {
                      $k = $j - 4;
                      if (isset($thisMonth[$dates[$i]-1][$j])) {
                        if ($thisMonth[$dates[$i]-1][$j] === $_SESSION['userid']) {
                          echo '<p class="yotei">'.timesc($k).'</p>';
                        }
                      }
                    }
                  }
                  echo "</td>";

                }


              } else if (($i+1)%7 === 0) {

                if ($flag) {

                  echo '<td class="saturday '.$t.$disabled.'">';
                  echo '<div class="day">';
                  ?>
                    <form action="user_reserve.php" method="post">

                      <input type="hidden" name="token" value="<?php echo h($_SESSION['token']) ?>">
                      <input type="hidden" name="year" value="<?php echo h($year) ?>">
                      <input type="hidden" name="month" value="<?php echo h($month) ?>">
                      <input type="hidden" name="date" value="<?php echo h($dates[$i]) ?>">
                      <input type="submit" name="reserve" value="<?php echo $dates[$i] ?>" <?php echo $disabled ?>>

                    </form>
                  <?php
                  echo '</div>';
                  if (!empty($dates[$i])) {
                    for ($j=4; $j<=27; $j++) {
                      $k = $j - 4;
                      if (isset($thisMonth[$dates[$i]-1][$j])) {
                        if ($thisMonth[$dates[$i]-1][$j] === $_SESSION['userid']) {
                          echo '<p class="yotei">'.timesc($k).'</p>';
                        }
                      }
                    }
                  }
                  echo "</td>";
                  echo '</tr>';

                }


              } else {

                if ($flag) {

                  echo '<td class="'.$t.$disabled.'">';
                  echo '<div class="day">';
                  ?>
                    <form action="user_reserve.php" method="post">

                      <input type="hidden" name="token" value="<?php echo h($_SESSION['token']) ?>">
                      <input type="hidden" name="year" value="<?php echo h($year) ?>">
                      <input type="hidden" name="month" value="<?php echo h($month) ?>">
                      <input type="hidden" name="date" value="<?php echo h($dates[$i]) ?>">
                      <input type="submit" name="reserve" value="<?php echo $dates[$i] ?>" <?php echo $disabled ?>>

                    </form>
                  <?php
                  echo '</div>';
                  if (!empty($dates[$i])) {
                    for ($j=4; $j<=27; $j++) {
                      $k = $j - 4;
                      if (isset($thisMonth[$dates[$i]-1][$j])) {
                        if ($thisMonth[$dates[$i]-1][$j] === $_SESSION['userid']) {
                          echo '<p class="yotei">'.timesc($k).'</p>';
                        }
                      }
                    }
                  }
                  echo "</td>";

                }

              }

            }
          ?>
  
        </tbody>
        
      </table>
      <a href="" class="back">今月に戻る</a>
      
    </div>

  </div>

<?php require("admin_footer.php"); ?>