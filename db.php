<?php
// 注意這兩句的位置，要在被引用的地方上面
date_default_timezone_set("Asia/Taipei");
$dsn = "mysql:host=localhost;charset=utf8;dbname=school";
$pdo = new PDO($dsn, 'root', '');


// $rows = all('students'," where `dept`= '2'");
// printData($rows);

// $row = find('students', ['dept' => '1', 'graduate_at' => '23']);
// $rows = all('students', ['dept' => '1', 'graduate_at' => '23']);

// echo "<h3>相同參數使用 find()";
// printData($row);
// echo "<hr>";
// echo "<h3>相同參數使用 all()";
// printData($rows);

// $up = update('students', ['dept' => '98', 'name' => '魯蛇'], '6');
// $up = update('students', ['dept' => '98', 'name' => '魯蛇'], ['dept' => '3', 'status_code' => '001  ']);
// printData($up);

// insert('dept', ['code' => '110', 'name' => '森林系']);
// del('dept', ['code' => '110', 'name' => '森林系']);

// 取得資料表中所有資料 / 所有滿足條件的資料的函數
function all($table = null, $where = '', $other = '' /*這個 $other 指的是 sql 語句中 where 後面其他的條件句*/)
{
    global $pdo;
    $sql = "select * from `$table` ";

    if (isset($table) && !empty($table)) {

        if (is_array($where)) {
            /*
         這裡判斷是否為陣列是因為 sql 語句中可能有多個條件，這時候如果將多個條件用字串的方式表示可能會有輸入錯誤的情況產生，
         所以把多個條件放入陣列中 (陣列中的格式單純 '' => '')
        
           例如：
           ['dept' => '3', 'graduate_at' => '12'] 要轉換成能放入 sql 語句的 `dept` = '3' && `graduate_at` = '12' 格式
        */

            if (!empty($where)) {
                // $tmp = [];
                foreach ($where as $key => $value) {
                    // 這裡的 $tmp[] 代表已經宣告完且直接拿來使用
                    $tmp[] = "`$key` = '$value'";
                }
                $sql .=  "where " . join(' && ', $tmp);
            } else {
                /* 如果是空陣列的話，去做 join( ) 不會顯示任何東西，另外 where 後面沒放東西的 sql 語，在資料庫中會有問題，所以乾脆把 sql 語句中的 where 的拿掉
                $sql = "select * from `$table` ";
                */

                // 直接把 select * from $table 提出去放在變數裡面
                $sql;
            }
        } else {

            $sql .=  $where;
        }

        $sql .= $other;

        // 用這種方式測試組合出來的 sql 語句是否正確
        // echo $sql;

        /*
        這種寫法會回傳欄位名稱、欄位值以及欄位索引值、欄位值，會不必要地增加網路傳輸的資料量
        $data = $pdo->query($sql)->fetchAll();
        */

        // PDO::FETCH_ASSOC : 只會回傳欄位名稱、欄位值；其中 ASSOC 指的是欄位名稱
        // 另外 fetchAll() 會回傳二微陣列
        $data = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);

        return $data;
    } else {
        echo "錯誤:沒有指定的資料表名稱";
    }
}

// 取得資料表中滿足條件的【一筆】資料
function find($table, $id) /* $id 可能是數字或是儲存著多個條件的陣列；陣列的例子有: 帳號和密碼，因為一組帳號密碼正常來說只會有一筆資料 */
{
    global $pdo;
    $sql = "select * from `$table` ";

    if (is_array($id)) {
        foreach ($id as $key => $value) {
            $tmp[] = "`$key` = '$value'";
        }
        $sql .=  "where " . join(' && ', $tmp);
    } else if (is_numeric($id)) {
        $sql .= "where `id` = '$id'";
    } else {
        echo "錯誤:參數的資料型態必須是數字或陣列";
    }

    // echo $sql;

    // fetch() 會回傳一維陣列
    $row = $pdo->query($sql)->fetch(PDO::FETCH_ASSOC);
    return $row;
}

// 更新某資料庫中某資料表的資料的函數
function update($table, $cols, $id) /* $cols 是 sql 語句中 set 之後的欄位 */
{
    global $pdo;
    $sql = "update `$table` set ";

    // $sql = "update `$table` set `` = '', `` = '', `` = '', ... where `id` = ''"
    if (!empty($cols)) {

        foreach ($cols as $key => $value) {
            $tmp[] = "`$key` = '$value'";
        }
    } else {
        echo "錯誤:缺少要編輯的指定欄位";
    }

    $sql .= join(' , ', $tmp);

    if (is_array($id)) {
        $tmp = [];
        foreach ($id as $key => $value) {
            $tmp[] = "`$key` = '$value'";
        }
        $sql .=  " where " . join(' && ', $tmp);
    } else if (is_numeric($id)) {
        $sql .= " where `id` = '$id'";
    } else {
        echo "錯誤:參數的資料型態必須是數字或陣列";
    }

    echo $sql;
    // exec() 會回傳 sql 語句影響的列數 (筆數 )
    return $pdo->exec($sql);
}

// 新增資料到資料表的函數
function insert($table, $values) // $values 是包含欄位和欄位值的陣列
{
    global $pdo;
    $sql = "insert into `$table` ";

// $sql = "insert into $table (`col1`, `col2`, `col3`, ...) values ('val1', 'val2', 'val3', ...)"

    // ['col1' => 'val1', 'col2' => 'val2', 'col3' => 'val3' ...] 轉換成 (`col1`,`col2`,`col3`, ...) values ('val1','val2','val3', ...)"

    /*
    這是最後要的結果
    $cols = "(``,``,``,``)";
    $vals = "('','','','')";
    $sql = $sql . $cols . " values " . $vals;
    */

    /* 試試看用 foreach 代替 array_keys( ) 的寫法
    foreach ($values as $key => $value) {
        $tmp[] = "`$key`";
    }
    */

    $cols = "(`" . join("`,`", array_keys($values)) . "`)";
    $vals = "('" . join("','", $values) . "')";
    $sql = $sql . $cols . " values " . $vals;

    echo $sql;
    return $pdo->exec($sql);
}

// 刪除資料表中一筆 / 多筆資料的函數
function del($table, $id)
{
    global $pdo;
    // 直接把 where 寫在 $sql 裡面，因為要刪除資料時一定都有條件，否則就會變成刪除整張資料表
    $sql = "delete from `$table` where ";

    /*
    $sql = "delete from `$table` where `id` = ''"
    或
    $sql = "delete from `$table` where `col1` = 'val1 && `col2` = 'val2'"
    */
    
    if (is_array($id)) {
        foreach ($id as $key => $value) {
            $tmp[] = "`$key` = '$value'";
        }
        $sql .= join(' && ', $tmp);
    } else if (is_numeric($id)) {
        $sql .= " `id` = '$id'";
    } else {
        echo "錯誤:參數的資料型態必須是數字或陣列";
    }

    // echo $sql;
    return $pdo->exec($sql);
}

// 印出從資料表取得的資料的函數
function printData($array)
{
    echo "<pre>";
    print_r($array);
    echo "</pre>";
}