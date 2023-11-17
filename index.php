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
?>
<h2>不定參數的用法</h2>
<?php
// 不固定參數函數練習
// 自己想的
function minus(...$arg)
{
  $sum = 0;
  foreach ($arg as $num) {
    // 檢查元素是否數字，因為陣列裡面可能有一般的string
    if (is_numeric($num))
      $sum += $num;
  }
  echo "總和是：" . $sum;
  echo "<hr>";
}

minus(22, 33, 44);

// 老師的範例
function sum2(...$arg)
{
  print_r($arg);
  echo "<hr>";
}

sum2(1, 2);
sum2(29, 15, 8);
sum2(2, 18, 29, 35, 47);