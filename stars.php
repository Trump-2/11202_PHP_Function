<style>
/* 這裡使用 universal selector 來選取所有的元素 */
* {
  font-family: 'Courier New', Courier, monospace;
  /* 擺脫瀏覽器預設的行高 */
  line-height: 0.5;
}

span {
  color: red;
}
</style>
</style>
<?php

// 這裡呼叫函數的位置在定義函數的上方也能正常使用
equilateral_triangle(5);
equilateral_triangle(10);
equilateral_triangle(15);
echo "<hr>";
reverse_equilateral_triangle(5);
reverse_equilateral_triangle(10);
reverse_equilateral_triangle(15);
echo "<hr>";
retangle(5);
retangle(7);
retangle(9);
function equilateral_triangle($size)
{

  for ($i = 0; $i < $size; $i++) {
    // 此迴圈負責印空白
    for ($j = 0; $j < $size - 1 - $i; $j++) {
      echo "&nbsp;";
    }

    // 此迴圈負責印星星
    for ($k = 0; $k < 2 * $i + 1; $k++) {
      echo "*";
    }
    echo "<br>";
  }
}
?>

<?php
function reverse_equilateral_triangle($size)
{

  // 巢狀迴圈，內層有兩個迴圈
  // 外迴圈負責產生列數
  for ($i = $size - 1; $i >= 0; $i--) {
    // 此迴圈負責印空白
    for ($j = 0; $j < $size - 1 - $i; $j++) {
      echo "&nbsp;";
    }

    // 此迴圈負責印星星
    for ($k = 0; $k < 2 * $i + 1; $k++) {
      echo "*";
    }
    echo "<br>";
  }
  echo "<hr>";
}
?>


<?php
// 巢狀迴圈，內層有兩個迴圈

function retangle($size)
{
  $tmp = 0;
  $mid = floor(($size * 2 - 1) / 2);
  for ($i = 0; $i < $size * 2 - 1; $i++) {
    //
    if ($i <= $mid) {
      $tmp = $i;
    } else {
      // 這裡要先在外面宣告 $tmp
      $tmp--;
    }

    // 此迴圈負責印空白
    for ($j = 0; $j < $mid - $tmp; $j++) {
      echo "&nbsp;";
    }

    // 此迴圈負責印星星
    for ($k = 0; $k < 2 * $tmp + 1; $k++) {
      echo "*";
    }
    echo "<br>";
  }
  echo "<hr>";
}
// 外迴圈負責產生列數
?>