<?php
// 注意這兩句的位置，要在被引用的地方上面
$dsn = "mysql:host=localhost;charset=utf8;dbname=school";
$pdo = new PDO($dsn, 'root', '');

date_default_timezone_set("Asia/Taipei");

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
                /* 如果是空陣列的話，去做 join( ) 不會顯示任何東西，另外 where 後面沒放東西的 sql 語，在資料庫中會有問題，所以乾脆把包含 where 的部分拿掉
                $sql = "select * from `$table` ";
                */

                $sql;
            }
        } else {

            $sql .=  $where;
        }

        $sql .= $other;

        echo $sql;
        /*
        這種寫法會回傳欄位名稱、欄位值以及欄位索引值、欄位值，會不必要地增加網路傳輸的資料量
        $data = $pdo->query($sql)->fetchAll();
        */

        // PDO::FETCH_ASSOC : 只會回傳欄位名稱、欄位值；其中 ASSOC 指的是欄位名稱
        $data = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);

        return $data;
    } else {
        echo "錯誤:沒有指定的資料表名稱";
    }
}

// 取得資料表中滿足條件的【一筆】資料
function find($table, $id) /* $id 可能是數字或是儲存著多個條件的陣列 */
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

    echo $sql;
    $row = $pdo->query($sql)->fetch(PDO::FETCH_ASSOC);
    return $row;
}

// 更新某資料庫中某資料表的資料的函數
function update($table, $cols, $id) /* $cols 是 sql 語句中 set 之後的欄位 */
{
    global $pdo;
    $sql = "update `$table` set ";

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
    // exec() 會回傳影響的列數 (筆數 )
    return $pdo->exec($sql);
}

// 新增資料到資料表的函數
function insert($table, $values)
{
    global $pdo;
    $sql = "insert into `$table` ";

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
    $sql = "delete from `$table` where ";

    if (is_array($id)) {
        foreach ($id as $key => $value) {
            $tmp[] = "`$key` = '$value'";
        }
        $sql .= join(' && ', $tmp);
    } else if (is_numeric($id)) {
        $sql .= "`id` = '$id'";
    } else {
        echo "錯誤:參數的資料型態必須是數字或陣列";
    }

    echo $sql;
    // return $pdo->exec($sql);
}

// 印出從資料表取得的資料的函數
function printData($array)
{
    echo "<pre>";
    print_r($array);
    echo "</pre>";
}
