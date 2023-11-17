<?php
// 自訂函數
function sum($a, $b)
{
  $sum = $a + $b;
  echo "輸入：" . $a . "、" . $b;
  echo "<br>";
  return $sum;
}

$total = sum(10, 20);
echo "總和是：$total";
echo "<hr>";

$total = sum(37, 15);
echo "總和是：$total";