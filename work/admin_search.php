<?php

require("admin_header.php");

$hit = false;
$nothit = false;
$users;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  validateToken();

  if (isset($_POST['name'])) {

    $pdo = new PDO($dsn, $username, $password);
  
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

}

?>

  <div class="right_contents">

    <h1>ユーザー検索</h1>

    <form action="" method="post" class="search_form">

      <input type="text" name="name" autocomplete=off>
      <input type="hidden" name="token" value="<?php echo h($_SESSION['token']) ?>">
      <input type="submit" value="検索">

    </form>

    <?php if ($hit) { ?>

      <p><?php echo count($users).'件の候補が見つかりました' ?></p>

      <table class="search_result">

        <thead>
          <tr>
            <td>名前</td>
            <td>カナ</td>
            <td>パスワード</td>
            <td>MAIL</td>
            <td>TEL</td>
            <td>履歴</td>
            <td>編集</td>
            <td>削除</td>
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
                print('<td>'.$user[$i].'</td>');
              }

          ?>

              <td>
                <form action="admin_history.php" method="post" class="hstry">
                  <input type="hidden" name="token" value="<?php echo h($_SESSION['token']) ?>">
                  <input type="hidden" name="history" value="<?php echo h($user['id']) ?>">
                  <input type="hidden" name="name" value="<?php echo h($user['name']) ?>">
                  <input type="submit" value="履歴">
                </form>
              </td>

              <td>
                <form action="admin_edit.php" method="post" class="edit">
                  <input type="hidden" name="token" value="<?php echo h($_SESSION['token']) ?>">
                  <input type="hidden" name="edit" value="<?php echo h($user['id']) ?>">
                  <input type="submit" value="編集" class="edit">
                </form>
              </td>

              <td>
                <form action="admin_user_delete.php" method="post" class="del">
                  <input type="hidden" name="token" value="<?php echo h($_SESSION['token']) ?>">
                  <input type="hidden" name="id" value="<?php echo h($user['id']) ?>">
                  <input type="submit" value="削除">
                </form>
              </td>

          <?php
            
              print("</tr>");
            
              $cnt++;
              
            }

          ?>

        </tbody>

      </table>

    <?php } else if ($nothit) {?>

      <p>候補が見つかりませんでした</p>

    <?php } ?>

  </div>

<?php require("admin_footer.php"); ?>