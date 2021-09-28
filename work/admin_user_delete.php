<?php

require("admin_header.php");

$hit = false;
$users;
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  validateToken();
  $pdo = new PDO($dsn, $username, $password);

  if (isset($_POST['adminpass'])) {
  
    try {
  
      $stmt = $pdo->prepare(
        "SELECT
          *
        FROM
          user_table
        WHERE
          id = 1
          "
      );

      $stmt->execute();
      $admin = $stmt->fetchAll();
    
      if ($admin[0]['pass'] === $_POST['adminpass']) {
  
        $stmt = $pdo->prepare(
          "SELECT
            *
          FROM
            user_table
          WHERE
            id = :id
            "
        );

        $stmt->bindValue(':id', $_POST['id']);
        $stmt->execute();
        $users = $stmt->fetchAll();

        $hit = true;
  
      } else {
        $message = "パスワードが正しくありません。";
      }
  
    } catch (PDOException $e) {
  
      print($e->getMessage());
  
    }

  }

  if (isset($_POST['delete'])) {
  
    try {
  
      $pdo->beginTransaction();

      $stmt = $pdo->prepare(
        "DELETE FROM
          user_table
        WHERE
         id = :id
        "
      );
  
      $stmt->bindValue(':id', h($_POST['id']));
      $stmt->execute();

      $pdo->commit();

      $message = $_POST['name'] . "を削除しました。";
  
    } catch (PDOException $e) {
  
      $pdo->rollBack();
      print($e->getMessage());
  
    }

  }


}

?>

  <div class="right_contents">

    <h1>ユーザー情報の削除</h1>

    <?php if ($hit) { ?>

      <table class="search_result">

        <thead>
          <tr>
            <td>名前</td>
            <td>カナ</td>
            <td>パスワード</td>
            <td>MAIL</td>
            <td>TEL</td>
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
            <form action="" method="post" class="del">
              <input type="hidden" name="token" value="<?php echo h($_SESSION['token']) ?>">
              <input type="hidden" name="id" value="<?php echo h($user['id']) ?>">
              <input type="hidden" name="name" value="<?php echo h($user['name']) ?>">

              <div class="delete <?php echo 'a'.$cnt ?> del2">削除</div>
              <div class="alert <?php echo 'b'.$cnt ?> nodis"></div>
              <div class="alert_window <?php echo 'c'.$cnt ?> nodis">
                
                <p class="error">！<?php echo $user['name'] ?>を削除します！</p>
                <p class="error">よろしいですか？</p>
                <input type="submit" name="delete" value="削除する">
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

    <?php } else if (!isset($_POST['delete'])) {?>

      <p>対象ユーザーを削除するには管理者パスワードを入力してください。</p>
      <?php if ($message !== "") { ?>
        <p class="error"><?php echo $message ?></p>
      <?php } ?>

      <form action="" method="post" class="search_form">
        
        <input type="password" name="adminpass" autocomplete=off>
        <input type="hidden" name="token" value="<?php echo h($_SESSION['token']) ?>">
        <input type="hidden" name="id" value="<?php echo h($_POST['id']) ?>">
        <input type="submit" value="入力">
        
      </form>

    <?php } else { ?>
      <p><?php echo $message ?></p>
    <?php } ?>

    <a href="admin_search.php" class="back">ユーザー検索に戻る</a>

  </div>

<?php require("admin_footer.php"); ?>