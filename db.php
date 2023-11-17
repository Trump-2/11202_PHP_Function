<?php

$rows = all('member', 'users');
printdata($rows);

function all($dbname, $tableName)
{
  $dsn = "mysql:host=localhost;charset=utf8;dbname=$dbname";
  $pdo = new PDO($dsn, 'root', '');

  $sql = "select * from `$tableName`";
  $rows = $pdo->query($sql)->fetchAll();
  return $rows;
}

function printdata($array)
{
  echo "<pre>";
  print_r($array);
  echo "</pre>";
}