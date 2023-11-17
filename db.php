<?php

$rows = all('member', 'users');
printdata($rows);

function all($dbname = null, $tableName)
{
  $dsn = "mysql:host=localhost;charset=utf8;dbname=$dbname";
  $pdo = new PDO($dsn, 'root', '');

  if (isset($tableName) && isset($dbname) && !empty($table) && !empty($dbname)) {

    $sql = "select * from `$tableName`";
    $rows = $pdo->query($sql)->fetchAll();
    return $rows;
  } else {
    echo "錯誤:沒有指定的資料庫或對應的資料表名稱";
  }
}

function printdata($array)
{
  echo "<pre>";
  print_r($array);
  echo "</pre>";
}