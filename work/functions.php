<?php

function h($str) {
  return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

function createToken() {
  if (!isset($_SESSION['token'])) {
    $_SESSION['token'] = bin2hex(random_bytes(32));
  }
}

function validateToken() {
  if (
    empty($_SESSION['token']) ||
    $_SESSION['token'] !== filter_input(INPUT_POST, 'token')
  ) {
    header("Location: index.php");
    exit();
  }
}

function endDate($y,$m) {
  $a;
  if ($m < 10) {
    $a = date($y."-0".$m."-01");
  } else {
    $a = date($y."-".$m."-01");
  }
  return date("t", strtotime($a));
}

function nextM($m) {
  if ($m === 12) {
    return 1;
  } else {
    return $m+1;
  }
}

function nextY($y,$m) {
  if ($m === 12) {
    return $y+1;
  } else {
    return $y;
  }
}

function sym($a) {

  switch ($a) {
    case -1:
      return "×";
      break;
    case 0:
      return "〇";
      break;
    default:
    return "※";
      break;
  }

}

function symcolor($a) {

  switch ($a) {
    case -1:
      return 'class="batu"';
      break;
    case 0:
      return 'class="maru"';
      break;
    default:
      return 'class="kome"';
      break;
  }

}

function disabled($a) {

  switch ($a) {
    case -1:
      return "";
      break;
    case 0:
      return "";
      break;
    default:
      return 'disabled="disabled"';
      break;
  }

}

function timesc($a) {
  switch ($a) {
    case 0:
      return "9:00";
      break;
    case 1:
      return "9:30";
      break;
    case 2:
      return "10:00";
      break;
    case 3:
      return "10:30";
      break;
    case 4:
      return "11:00";
      break;
    case 5:
      return "11:30";
      break;
    case 6:
      return "12:00";
      break;
    case 7:
      return "12:30";
      break;
    case 8:
      return "13:00";
      break;
    case 9:
      return "13:30";
      break;
    case 10:
      return "14:00";
      break;
    case 11:
      return "14:30";
      break;
    case 12:
      return "15:00";
      break;
    case 13:
      return "15:30";
      break;
    case 14:
      return "16:00";
      break;
    case 15:
      return "16:30";
      break;
    case 16:
      return "17:00";
      break;
    case 17:
      return "17:30";
      break;
    case 18:
      return "18:00";
      break;
    case 19:
      return "18:30";
      break;
    case 20:
      return "19:00";
      break;
    case 21:
      return "19:30";
      break;
    case 22:
      return "20:00";
      break;
    case 23:
      return "20:30";
      break;
  }
}

function today ($d,$t,$m,$tm,$y,$ty) {
  if ($d === $t &&
      $m === $tm &&
      $y === $ty) {
    return "today ";
  } else {
    return "";
  }
}

function passedDay ($d,$t,$m,$tm,$y,$ty) {

  $a = (int)$tm + 1;
  $b = (string)$a;

  if (($d <= $t && $m === $tm && $y === $ty) ||
      (($m < $tm || $m > $b) && $y === $ty) ||
      ($y > $ty && ($tm !== '12' || ($tm === '12' && $m !== '1'))) ||
      $y < $ty) {
    return "disabled";
  } else {
    return "";
  }
}

function add_date(&$d,$sw,$ne,$ew) {
  for ($i=0; $i<$sw; $i++) {
    array_unshift($d, "");
  }
  
  for ($i=1; $i<=$ne; $i++) {
    array_push($d, $i);
  }
  
  for ($i=1; $i<=$ew; $i++) {
    array_push($d, "");
  }
}

$dsn = "mysql:host=mysql; dbname=reserve_db; charset=utf8mb4";
$username = "root";
$password = "pass";

session_start();