<?php

require("admin_header.php");

$edit = true;
$comfirm = false;
$fix = false;
$decide = false;
$name = "";
$kana = "";
$pass = "";
$mail = "";
$tel = "";
$name_error;
$kana_error;
$pass_error;
$mail_error;
$tel_error;


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  validateToken();

  if (isset($_POST['comfirm'])) {

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
    } else if (mb_strlen($kana) > 50) {
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
      $edit = false;
    } else {
      $fix = true;
      $edit = false;
    }

  } else if (isset($_POST['fix'])) {

    $fix = true;
    $name = h($_POST['name']);
    $kana = h($_POST['kana']);
    $pass = h($_POST['pass']);
    $mail = h($_POST['mail']);
    $tel = h($_POST['tel']);

  } else if (isset($_POST['decide'])) {

    $name = h($_POST['name']);
    $kana = h($_POST['kana']);
    $pass = h($_POST['pass']);
    $mail = h($_POST['mail']);
    $tel = h($_POST['tel']);

    try {

      $pdo = new PDO($dsn, $username, $password);

      $pdo->beginTransaction();
  
      $stmt = $pdo->prepare(
        "INSERT INTO
          user_table (name, kana, pass, mail, tel)
        VALUES
          (:name, :kana, :pass, :mail, :tel)
        "
      );
  
      $stmt->bindValue(':name', $name);
      $stmt->bindValue(':kana', $kana);
      $stmt->bindValue(':pass', $pass);
      $stmt->bindValue(':mail', $mail);
      $stmt->bindValue(':tel', $tel);
      $stmt->execute();

      $pdo->commit();
      $decide = true;
      $edit = false;
  
    } catch (PDOException $e) {
  
      $pdo->rollBack();
      print($e->getMessage());
  
    }

  } else {

    header("Location: index.php");
    exit();

  }

}

?>

  <div class="right_contents">

    <h1>新規ユーザーの登録</h1>

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
      <input type="submit" name="comfirm" value="内容確認">

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

        <input type="submit" name="decide" value="追加する">

        <input type="submit" name="fix" value="修正する">

      </form>

    <?php } else if ($decide) { ?>

      <p>新規ユーザーを追加しました</p>
      <a href="admin_add.php" class="back">新規ユーザーの登録に戻る</a>

    <?php } ?>

  </div>

<?php require("admin_footer.php"); ?>