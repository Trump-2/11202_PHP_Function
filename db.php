<?php

$rows = all('school', 'students', ' where `dept` = 1');
printdata($rows);

function all($dbname = null, $tableName = null, $where = '')
{
  $dsn = "mysql:host=localhost;charset=utf8;dbname=$dbname";
  $pdo = new PDO($dsn, 'root', '');
  $sel = "select * from `$tableName` ";

  if (isset($tableName) && isset($dbname) && !empty($tableName) && !empty($dbname)) {
    if (is_array($where)) {
      if (!empty($where)) {
        foreach ($where as $col => $value) {
          $tmp[] = "`$col`='$value'";
        }
        $sql = "$sel where " . join(" && ", $tmp);
      } else {
        $sql = $sel;
      }
    } else {
      $sql = "$sel $where";
    }
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
