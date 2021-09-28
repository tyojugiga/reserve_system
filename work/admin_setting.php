<?php

require("admin_header.php");

$edit = false;
$comfirm = false;
$fix = false;
$decide = false;
$id = 1;
$name;
$pass;
$mail1;
$mail2;
$mail3;
$name_error;
$pass_error;
$mail1_error;
$mail2_error;
$mail3_error;

$pdo = new PDO($dsn, $username, $password);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  validateToken();

  if (isset($_POST['comfirm'])) {

    $name = h($_POST['name']);
    $pass = h($_POST['pass']);
    $mail1 = h($_POST['mail1']);
    $mail2 = h($_POST['mail2']);
    $mail3 = h($_POST['mail3']);

    if (mb_strlen(trim($name)) === 0) {
      $name_error = "名前を入力してください。";
    } else if (mb_strlen($name) > 50) {
      $name_error = "名前は50文字以内で入力してください。";
    }

    if (mb_strlen(trim($pass)) === 0) {
      $pass_error = "パスワードを入力してください。";
    } else if (!preg_match("/^[a-zA-Z0-9]+$/",$pass)) {
      $pass_error = "パスワードは半角英数字で入力してください。";
    } else if (mb_strlen($pass) > 50 || mb_strlen($pass) < 8) {
      $pass_error = "パスワードは8文字以上50文字以内で入力してください。";
    }

    if (mb_strlen(trim($mail1)) === 0) {
      $mail1_error = "E-mailアドレスを入力してください。";
    } else if (mb_strlen($mail1) > 100) {
      $mail1_error = "E-mailアドレスは100文字以内で入力してください。";
    } else if (!filter_var($mail1, FILTER_VALIDATE_EMAIL)) {
      $mail1_error = "不正なE-mailアドレスです。";
    }

    if (mb_strlen(trim($mail2)) === 0) {
    } else if (mb_strlen($mail2) > 100) {
      $mail2_error = "E-mailアドレスは100文字以内で入力してください。";
    } else if (!filter_var($mail2, FILTER_VALIDATE_EMAIL)) {
      $mail2_error = "不正なE-mailアドレスです。";
    }

    if (mb_strlen(trim($mail3)) === 0) {
    } else if (mb_strlen($mail3) > 100) {
      $mail3_error = "E-mailアドレスは100文字以内で入力してください。";
    } else if (!filter_var($mail3, FILTER_VALIDATE_EMAIL)) {
      $mail3_error = "不正なE-mailアドレスです。";
    }

    if (
      empty($name_error) &&
      empty($mail1_error) &&
      empty($mail2_error) &&
      empty($mail3_error) &&
      empty($pass_error)
    ) {
      $comfirm = true;
    } else {
      $fix = true;
    }

  } else if (isset($_POST['fix'])) {

    $fix = true;
    $name = h($_POST['name']);
    $pass = h($_POST['pass']);
    $mail1 = h($_POST['mail1']);
    $mail2 = h($_POST['mail2']);
    $mail3 = h($_POST['mail3']);

  } else if (isset($_POST['decide'])) {

    $name = h($_POST['name']);
    $pass = h($_POST['pass']);
    $mail1 = h($_POST['mail1']);
    $mail2 = h($_POST['mail2']);
    $mail3 = h($_POST['mail3']);

    try {

      $pdo->beginTransaction();
  
      $stmt = $pdo->prepare(
        "UPDATE
          user_table
        SET
          name = :name,
          pass = :pass,
          mail = :mail
        WHERE
          id = :id
        "
      );
  
      $stmt->bindValue(':id', 1);
      $stmt->bindValue(':name', $name);
      $stmt->bindValue(':pass', $pass);
      $stmt->bindValue(':mail', $mail1);
      $stmt->execute();

      $stmt = $pdo->prepare(
        "UPDATE
          user_table
        SET
          mail = :mail
        WHERE
          id = :id
        "
      );
  
      $stmt->bindValue(':id', 27);
      $stmt->bindValue(':mail', $mail2);
      $stmt->execute();

      $stmt = $pdo->prepare(
        "UPDATE
          user_table
        SET
          mail = :mail
        WHERE
          id = :id
        "
      );
  
      $stmt->bindValue(':id', 28);
      $stmt->bindValue(':mail', $mail3);
      $stmt->execute();

      $pdo->commit();
      $decide = true;
  
    } catch (PDOException $e) {
  
      $pdo->rollBack();
      print($e->getMessage());
  
    }

  }

} else {

  $edit = true;

  try {

    $stmt = $pdo->prepare(
      "SELECT
        *
      FROM
        user_table
      WHERE
        id = :id
      "
    );

    $stmt->bindValue(':id', 1);
    $stmt->execute();
    $users = $stmt->fetchAll();
  
    if (empty($users[0])) {

      header("Location: index.php");
      exit();

    } else {

      foreach($users as $user) {
        $name = h($user['name']);
        $pass = h($user['pass']);
        $mail1 = h($user['mail']);
      }

    }

    $stmt = $pdo->prepare(
      "SELECT
        *
      FROM
        user_table
      WHERE
        id = :id
      "
    );

    $stmt->bindValue(':id', 27);
    $stmt->execute();
    $users = $stmt->fetchAll();
  
    if (empty($users[0])) {

      header("Location: index.php");
      exit();

    } else {

      foreach($users as $user) {
        $mail2 = h($user['mail']);
      }

    }
    $stmt = $pdo->prepare(
      "SELECT
        *
      FROM
        user_table
      WHERE
        id = :id
      "
    );

    $stmt->bindValue(':id', 28);
    $stmt->execute();
    $users = $stmt->fetchAll();
  
    if (empty($users[0])) {

      header("Location: index.php");
      exit();

    } else {

      foreach($users as $user) {
        $mail3 = h($user['mail']);
      }

    }

  } catch (PDOException $e) {

    print($e->getMessage());

  }


}

?>

  <div class="right_contents">

    <h1>管理者情報の編集</h1>

    <?php if ($edit || $fix) { ?>

    <form action="" method="post" class="search_form">

      <table>

        <tr>
          <td>名前</td>
          <td>
            ：<input type="text" name="name" autocomplete=off value="<?php echo $name ?>">
          </td>
        </tr>
        <?php if (!empty($name_error)) {
          print('<tr><td colspan="2" class="error">※'.$name_error.'</td></tr>');
        } ?>

        <tr>
          <td>パスワード</td>
          <td>
            ：<input type="text" name="pass" autocomplete=off value="<?php echo $pass ?>">
          </td>
        </tr>
        <?php if (!empty($pass_error)) {
          print('<tr><td colspan="2" class="error">※'.$pass_error.'</td></tr>');
        } ?>

        <tr>
          <td>MAIL1</td>
          <td>
            ：<input type="text" name="mail1" autocomplete=off value="<?php echo $mail1 ?>">
          </td>
        </tr>
        <?php if (!empty($mail1_error)) {
          print('<tr><td colspan="2" class="error">※'.$mail1_error.'</td></tr>');
        } ?>

        <tr>
          <td>MAIL2</td>
          <td>
            ：<input type="text" name="mail2" autocomplete=off value="<?php echo $mail2 ?>">
          </td>
        </tr>
        <?php if (!empty($mail2_error)) {
          print('<tr><td colspan="2" class="error">※'.$mail2_error.'</td></tr>');
        } ?>

        <tr>
          <td>MAIL3</td>
          <td>
            ：<input type="text" name="mail3" autocomplete=off value="<?php echo $mail3 ?>">
          </td>
        </tr>
        <?php if (!empty($mail3_error)) {
          print('<tr><td colspan="2" class="error">※'.$mail3_error.'</td></tr>');
        } ?>

      </table>

      <input type="hidden" name="token" value="<?php echo h($_SESSION['token']) ?>">
      <input type="submit" name="comfirm" value="変更確認">

    </form>

    <?php } else if ($comfirm) { ?>

      <form action="" method="post" class="search_form">

        <table>

          <tr>
            <td>名前</td>
            <td>
              <p>：<?php echo $name ?></p>
              <input type="hidden" name="name" autocomplete=off value="<?php echo $name ?>">
            </td>
          </tr>

          <tr>
            <td>パスワード</td>
            <td>
              <p>：<?php echo $pass ?></p>
              <input type="hidden" name="pass" autocomplete=off value="<?php echo $pass ?>">
            </td>
          </tr>

          <tr>
            <td>MAIL1</td>
            <td>
              <p>：<?php echo $mail1 ?></p>
              <input type="hidden" name="mail1" autocomplete=off value="<?php echo $mail1 ?>">
            </td>
          </tr>

          <tr>
            <td>MAIL2</td>
            <td>
              <p>：<?php echo $mail2 ?></p>
              <input type="hidden" name="mail2" autocomplete=off value="<?php echo $mail2 ?>">
            </td>
          </tr>

          <tr>
            <td>MAIL3</td>
            <td>
              <p>：<?php echo $mail3 ?></p>
              <input type="hidden" name="mail3" autocomplete=off value="<?php echo $mail3 ?>">
            </td>
          </tr>

        </table>

        <input type="hidden" name="token" value="<?php echo h($_SESSION['token']) ?>">
        <input type="submit" name="decide" value="変更する">
        <input type="submit" name="fix" value="キャンセル">

      </form>

    <?php } else if ($decide) { ?>

      <p>管理者情報を変更しました</p>
      <a href="admin_top.php" class="back">戻る</a>

    <?php } ?>

  </div>

<?php require("admin_footer.php"); ?>