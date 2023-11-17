<?php
// 自訂函數

// 全域變數 $c
$c = 100;
function sum($a, $b)
{
  // 重要：要在 function 內使用全域變數就要這樣寫
  global $c;
  $sum = $a + $b + $c;
  echo "輸入：" . $a . "、" . $b;
  echo "<br>";
  return $sum;
}

$total = sum(10, 20);
echo "總和是：$total";
echo "<hr>";

$total = sum(37, 15);
echo "總和是：$total";
echo "<hr>";

// 函數本身也是變數的證明
echo "總和是：" . sum(29, 52);