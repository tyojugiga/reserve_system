<?php

require("user_header.php");

$users;
$message = "";
$nothit = false;

$pdo = new PDO($dsn, $username, $password);

try {
  
  $stmt = $pdo->prepare(
    "SELECT
      *
    From
      history
    WHERE
      user_id = :id
    ORDER BY
      year ASC,
      month ASC,
      date ASC
    "
  );

  $stmt->bindValue(':id', h($_SESSION['userid']));
  $stmt->execute();
  $users = $stmt->fetchAll();

  if (empty($users[0])) {

    $nothit = true;
    $message = "予約履歴がありません。";

  }
  
} catch (PDOException $e) {

  print($e->getMessage());

}

?>

  <div class="right_contents">

    <h1>予約履歴</h1>

    <?php if ($nothit) {

      print('<h1>'.$message.'</h1>');

    } else { ?>

    <h1>今後の予定</h1>

    <table class="search_result">

      <thead>
        <tr>
          <td>年</td>
          <td>月</td>
          <td>日</td>
          <td>時間</td>
        </tr>
      </thead>

      <tbody>

        <?php
        
          $cnt = 0;
          foreach($users as $user) {

            if (($user['year']===date("Y") && $user['month']===date("n") && $user['date']>=date("j")) || 
                ($user['year']>=date("Y") && $user['month']>date("n"))) {

              $gray = '';
  
              if ($cnt%2 !== 0) {
                $gray = 'class="gray"';
              }
  
              print("<tr ".$gray.">");
            
              for ($i=3;$i<=6;$i++) {
                print('<td class="history">'.$user[$i].'</td>');
              }
  
              print("</tr>");
  
              $cnt++;
                        
            }

          }          

        ?>

      </tbody>

    </table>

    <h1>過去の予約履歴</h1>

    <table class="search_result">

      <thead>
        <tr>
          <td>年</td>
          <td>月</td>
          <td>日</td>
          <td>時間</td>
        </tr>
      </thead>

      <tbody>

        <?php
        
          $cnt = 0;
          foreach($users as $user) {

            if ($user['year']<=date("Y") && $user['month']<=date("n") && $user['date']<date("j")) {

              $gray = '';
  
              if ($cnt%2 !== 0) {
                $gray = 'class="gray"';
              }
  
              print("<tr ".$gray.">");
            
              for ($i=3;$i<=6;$i++) {
                print('<td class="history">'.$user[$i].'</td>');
              }
  
              print("</tr>");
  
              $cnt++;
                        
            }

          }          

        ?>

      </tbody>

    </table>

    <?php } ?>

    <a href="user_top.php" class="back">戻る</a>

  </div>

<?php require("admin_footer.php"); ?>