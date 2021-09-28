<?php

require("user_header.php");

$edit = false;
$comfirm = false;
$fix = false;
$decide = false;
$id;
$name;
$kana;
$pass;
$mail;
$tel;
$name_error;
$kana_error;
$pass_error;
$mail_error;
$tel_error;

$pdo = new PDO($dsn, $username, $password);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  validateToken();

  if (isset($_POST['comfirm'])) {

    $id = h($_POST['id']);
    $name = h($_POST['name']);
    $kana = h($_POST['kana']);
    $pass = h($_POST['pass']);
    $mail = h($_POST['mail']);
    $tel = h($_POST['tel']);

    if (mb_strlen(trim($name)) === 0) {
      $name_error = "名前を入力してください。";
    } else if (mb_strlen($name) > 50) {
      $name_error = "名前は50文字以内で入力してください。";
    }

    if (mb_strlen(trim($kana)) === 0) {
      $kana_error = "フリガナを入力してください。";
    } else if (!preg_match("/^[ァ-ヾ]+$/u",$kana)) {
      $kana_error = "フリガナは全角カタカナで入力してください。";
    } else if (mb_strlen($name) > 50) {
      $kana_error = "フリガナは50文字以内で入力してください。";
    }

    if (mb_strlen(trim($pass)) === 0) {
      $pass_error = "パスワードを入力してください。";
    } else if (!preg_match("/^[a-zA-Z0-9]+$/",$pass)) {
      $pass_error = "パスワードは半角英数字で入力してください。";
    } else if (mb_strlen($pass) > 50 || mb_strlen($pass) < 8) {
      $pass_error = "パスワードは8文字以上50文字以内で入力してください。";
    }

    if (mb_strlen(trim($mail)) === 0) {
      $mail_error = "E-mailアドレスを入力してください。";
    } else if (mb_strlen($mail) > 100) {
      $mail_error = "E-mailアドレスは100文字以内で入力してください。";
    } else if (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
      $mail_error = "不正なE-mailアドレスです。";
    }

    if (mb_strlen(trim($tel)) === 0) {
      $tel_error = "電話番号を入力してください。";
    } else if (!preg_match("/^[0-9]{2,4}-[0-9]{2,4}-[0-9]{3,4}$/",$tel)) {
      $tel_error = "不正な電話番号です。";
    }

    if (
      empty($name_error) &&
      empty($kana_error) &&
      empty($pass_error) &&
      empty($mail_error) &&
      empty($tel_error)
    ) {
      $comfirm = true;
    } else {
      $fix = true;
    }

  } else if (isset($_POST['fix'])) {

    $fix = true;
    $id = h($_POST['id']);
    $name = h($_POST['name']);
    $kana = h($_POST['kana']);
    $pass = h($_POST['pass']);
    $mail = h($_POST['mail']);
    $tel = h($_POST['tel']);

  } else if (isset($_POST['decide'])) {

    $id = h($_POST['id']);
    $name = h($_POST['name']);
    $kana = h($_POST['kana']);
    $pass = h($_POST['pass']);
    $mail = h($_POST['mail']);
    $tel = h($_POST['tel']);

    try {

      $pdo->beginTransaction();
  
      $stmt = $pdo->prepare(
        "UPDATE
          user_table
        SET
          name = :name,
          kana = :kana,
          pass = :pass,
          mail = :mail,
          tel = :tel
        WHERE
          id = :id
        "
      );
  
      $stmt->bindValue(':id', $id);
      $stmt->bindValue(':name', $name);
      $stmt->bindValue(':kana', $kana);
      $stmt->bindValue(':pass', $pass);
      $stmt->bindValue(':mail', $mail);
      $stmt->bindValue(':tel', $tel);
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
  $id = $_SESSION['userid'];

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

    $stmt->bindValue(':id', $id);
    $stmt->execute();
    $users = $stmt->fetchAll();
  
    if (empty($users[0])) {

      header("Location: index.php");
      exit();

    } else {

      foreach($users as $user) {
        $name = h($user['name']);
        $kana = h($user['kana']);
        $pass = h($user['pass']);
        $mail = h($user['mail']);
        $tel = h($user['tel']);
      }

    }

  } catch (PDOException $e) {

    print($e->getMessage());

  }


}

?>

  <div class="right_contents">

    <h1>ユーザー情報の編集</h1>

    <?php if ($edit || $fix) { ?>

    <form action="" method="post" class="search_form spw">

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
          <td>フリガナ</td>
          <td>
            ：<input type="text" name="kana" autocomplete=off value="<?php echo $kana ?>">
          </td>
        </tr>
        <?php if (!empty($kana_error)) {
          print('<tr><td colspan="2" class="error">※'.$kana_error.'</td></tr>');
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
          <td>MAIL</td>
          <td>
            ：<input type="text" name="mail" autocomplete=off value="<?php echo $mail ?>">
          </td>
        </tr>
        <?php if (!empty($mail_error)) {
          print('<tr><td colspan="2" class="error">※'.$mail_error.'</td></tr>');
        } ?>

        <tr>
          <td>TEL</td>
          <td>
            ：<input type="text" name="tel" autocomplete=off value="<?php echo $tel ?>">
          </td>
        </tr>
        <?php if (!empty($tel_error)) {
          print('<tr><td colspan="2" class="error">※'.$tel_error.'</td></tr>');
        } ?>

      </table>

      <input type="hidden" name="token" value="<?php echo h($_SESSION['token']) ?>">
      <input type="hidden" name="id" value="<?php echo $id ?>">
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
            <td>フリガナ</td>
            <td>
              <p>：<?php echo $kana ?></p>
              <input type="hidden" name="kana" autocomplete=off value="<?php echo $kana ?>">
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
            <td>MAIL</td>
            <td>
              <p>：<?php echo $mail ?></p>
              <input type="hidden" name="mail" autocomplete=off value="<?php echo $mail ?>">
            </td>
          </tr>

          <tr>
            <td>TEL</td>
            <td>
              <p>：<?php echo $tel ?></p>
              <input type="hidden" name="tel" autocomplete=off value="<?php echo $tel ?>">
            </td>
          </tr>

        </table>

        <input type="hidden" name="token" value="<?php echo h($_SESSION['token']) ?>">
        <input type="hidden" name="id" value="<?php echo $id ?>">

        <input type="submit" name="decide" value="変更する">

        <input type="submit" name="fix" value="キャンセル">

      </form>

    <?php } else if ($decide) { ?>

      <p>ユーザー情報を変更しました</p>
      <a href="user_top.php" class="back">戻る</a>

    <?php } ?>

  </div>

<?php require("admin_footer.php"); ?>