<?php
include 'config.php';

// Добавьте код для обработки поиска
if (isset($_POST['search_submit'])) {
    $search_query = $_POST['search_query'];
    $sql = "SELECT * FROM tovar WHERE name LIKE ? OR code LIKE ?";
    $query = $pdo->prepare($sql);
    $query->execute(["%$search_query%", "%$search_query%"]);
    $search_results = $query->fetchAll();
}

$name = $_POST['name'];
$namename = $_POST['namename'];
$code = $_POST['code'];
$clientname = $_POST['clientname'];
$price = $_POST['price'];
$quantityskl = $_POST['quantityskl'];
$quantitysold = $_POST['quantitysold'];
$phone = $_POST['phone'];
$email = $_POST['email'];
$address = $_POST['address'];
$place = $_POST['place'];
$euro_price = $_POST['euro-price'];

//euroкурс
$sql = ("SELECT euro FROM kurs WHERE id=1");
$query = $pdo->prepare($sql);
$query->execute();
$euro = $query->fetchColumn();

// Create
if (isset($_POST['submit'])) {
    $sql = ("INSERT INTO `tovar`(`name`, `namename`, `place`, `code`,  `quantityskl` , `quantitysold`, `price`) VALUES (?,?,?,?,?,?,?)");
    $query = $pdo->prepare($sql);
    $query->execute([$name, $namename, $place, $code,  $quantityskl, $quantitysold, $price]);
    $success = '<div class="alert alert-success alert-dismissible fade show" role="alert">
      <strong>Данные успешно отправлены!</strong> Вы можете закрыть это сообщение.
      <button type="button" class="close" onclick="history.back();" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>';
}

// Read

$sql = $pdo->prepare("SELECT * FROM `tovar`");
$sql->execute();
$result = $sql->fetchAll();

// Update
$edit_name = $_POST['edit_name'];
$edit_namename = $_POST['edit_namename'];
$edit_place = $_POST['edit_place'];
$edit_code = $_POST['edit_code'];
$edit_quantityskl = $_POST['edit_quantityskl'];
$edit_price = $_POST['edit_price'];
$edit_quantitysold = $_POST['edit_quantitysold'];
$get_id = $_GET['id'];

if (isset($_POST['edit-submit'])) {
    $sqll = "UPDATE tovar SET name=?, namename=?, place=?, code=?, quantityskl=?, quantitysold=?, price=? WHERE id=?";
    $querys = $pdo->prepare($sqll);
    $querys->execute([$edit_name, $edit_namename, $edit_place, $edit_code, $edit_quantityskl, $edit_quantitysold, $edit_price, $get_id]);
    header('Location: ' . $_SERVER['HTTP_REFERER']);
}

// DELETE
if (isset($_POST['delete_submit'])) {
    $sql = "DELETE FROM tovar WHERE id=?";
    $query = $pdo->prepare($sql);
    $query->execute([$get_id]);
    header('Location: ' . $_SERVER['HTTP_REFERER']);
}

// SCAN
if (isset($_POST['scan_submit'])) {
    $count = 0;
    $addprice = 0;
    $code = explode(' ', $code);
    foreach ($code as $value) {
        // Проверка на пустую строку
        if (iconv_strlen($value) > 0) {
            $sqll = "SELECT price FROM tovar WHERE code=?";
            $querys = $pdo->prepare($sqll);
            $querys->execute([$value]);
            $row = $querys->fetchColumn();
            $addprice = $addprice + $row;
            $count = $count + 1;
        } else {
            continue;
        }
    }
    $sql = ("SELECT COUNT(*) FROM client WHERE clientname=?");
    $query = $pdo->prepare($sql);
    $query->execute([$clientname]);
    $check = $query->fetchColumn();
    if ($check > 0) {
        $sqll = "UPDATE client SET purchases=purchases+?, totalprice=totalprice+? WHERE clientname=? ";
        $querys = $pdo->prepare($sqll);
        $querys->execute([$count, $addprice, $clientname]);
        foreach ($code as $value) {
            $sqll = "UPDATE tovar SET quantityskl=quantityskl-1, quantitysold=quantitysold+1  WHERE code=?";
            $querys = $pdo->prepare($sqll);
            $querys->execute([$value]);
        }
    } else {
        $sql = "INSERT INTO `client`(`clientname`, `phone`, `email`, `address`, `purchases`, `totalprice`) VALUES (?,?,?,?,?,?)";
        $query = $pdo->prepare($sql);
        $query->execute([$clientname, $phone, $email, $address, $count, $addprice]);
        foreach ($code as $value) {
            $sqll = "UPDATE tovar SET quantityskl=quantityskl-1, quantitysold=quantitysold+1  WHERE code=?";
            $querys = $pdo->prepare($sqll);
            $querys->execute([$value]);
        }
    }

    header('Location: ' . $_SERVER['HTTP_REFERER']);
}

// Добавление на склад
if (isset($_POST['scanadd_submit'])) {
    $code = explode(' ', $code);
    foreach ($code as $value) {
        $sql = ("SELECT COUNT(*) FROM tovar WHERE code=?");
        $query = $pdo->prepare($sql);
        $query->execute([$value]);
        $check = $query->fetchColumn();
        if ($check > 0) {
            $sqll = "UPDATE tovar SET quantityskl=quantityskl+1  WHERE code=?";
            $querys = $pdo->prepare($sqll);
            $querys->execute([$value]);
            header('Location: ' . $_SERVER['HTTP_REFERER']);
        } else {
            $success = '<div class="alert alert-success alert-dismissible fade show" role="alert">
              <strong>Штрихкод не найден!!!</strong> Вы можете закрыть это сообщение.
              <button type="button" class="close" onclick="history.back();" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>';
        }
    }
}

// Изменение курса валюты евро
if (isset($_POST['euro-submit'])) {
    $sqll = "UPDATE kurs SET euro=? WHERE id=1";
   $querys = $pdo->prepare($sqll);
    $querys->execute([$euro_price]);
    header('Location: ' . $_SERVER['HTTP_REFERER']);
}

session_start();
// Добавление в корзину
if(isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $quantity = max(1, intval($_POST['quantity']));
    if(isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] += $quantity;
    } else {
        $_SESSION['cart'][$product_id] = $quantity;
    }
    header("Location: ".$_SERVER['PHP_SELF']);
    exit;
}

// Удаление из корзины
if(isset($_POST['remove_from_cart'])) {
    $product_id = $_POST['product_id'];
    unset($_SESSION['cart'][$product_id]);
    header("Location: ".$_SERVER['PHP_SELF']);
    exit;
}

// Оформление заказа
if(isset($_POST['checkout_submit'])) {
    foreach($_SESSION['cart'] as $product_id => $quantity) {
        $stmt = $pdo->prepare("SELECT quantityskl FROM tovar WHERE id=?");
        $stmt->execute([$product_id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if($row['quantityskl'] >= $quantity) {
            $stmt = $pdo->prepare("UPDATE tovar SET quantityskl = quantityskl - ?, quantitysold = quantitysold + ? WHERE id=?");
            $stmt->execute([$quantity, $quantity, $product_id]);
        } else {
            $error = "Товара с ID $product_id недостаточно на складе!";
        }
    }
    $_SESSION['cart'] = [];
    header("Location: ".$_SERVER['PHP_SELF']);
    exit;
}
// Функция эспорта
function exportDatabase($pdo) {
    $filename = "sklad_backup_" . date("Y-m-d_H-i-s") . ".sql";

    // Отправляем заголовки для скачивания
    header('Content-Type: application/sql');
    header('Content-Disposition: attachment; filename="'.$filename.'"');

    try {
        $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);

        foreach ($tables as $table) {
            // DROP TABLE
            echo "DROP TABLE IF EXISTS `$table`;\n";

            // CREATE TABLE
            $create = $pdo->query("SHOW CREATE TABLE `$table`")->fetch(PDO::FETCH_ASSOC)['Create Table'];
            echo $create . ";\n\n";

            // INSERT
            $rows = $pdo->query("SELECT * FROM `$table`")->fetchAll(PDO::FETCH_ASSOC);
            foreach ($rows as $row) {
                $vals = array_map(function($v){
                    return $v===null ? "NULL" : "'".addslashes($v)."'";
                }, $row);
                echo "INSERT INTO `$table` (`".implode('`,`', array_keys($row))."`) VALUES (".implode(',', $vals).");\n";
            }
            echo "\n\n";
        }
    } catch (PDOException $e) {
        echo "-- Ошибка: " . $e->getMessage();
    }
    exit;
}

// Функция импорта
function importDatabase($pdo, $filePath) {
    $sql = file_get_contents($filePath);
    try {
        $pdo->exec($sql);
        echo '<div class="alert alert-success">Импорт успешно завершён!</div>';
    } catch (PDOException $e) {
        echo '<div class="alert alert-danger">Ошибка импорта: ' . $e->getMessage() . '</div>';
    }
}

// Функция эспорта CSV
function exportTovarToCSV($pdo) {
    $filename = "tovar_export_" . date('Y-m-d_H-i-s') . ".csv";
    header('Content-Type: text/csv; charset=windows-1251');
    header('Content-Disposition: attachment; filename=' . $filename);

    $output = fopen('php://output', 'w');

    // Функция для конвертации в Windows-1251
    function convertToWin1251(array $arr) {
        return array_map(function($value) {
            return iconv('UTF-8', 'Windows-1251//IGNORE', $value);
        }, $arr);
    }

    // Заголовки
    fputcsv($output, convertToWin1251(['ID', 'Артикул', 'Название товара', 'Позиция на складе', 'Штрихкод', 'Кол-во на складе', 'Кол-во продано', 'Цена']), ';');

    $stmt = $pdo->query("SELECT id, name, namename, place, code, quantityskl, quantitysold, price FROM tovar");

    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        fputcsv($output, convertToWin1251($row), ';');
    }

    fclose($output);
    exit;
}
// Функция эспорта CSV с малым остатком
function exportLowStockToCSV($pdo, $threshold = 5) {
    $filename = "low_stock_" . date('Y-m-d_H-i-s') . ".csv";
    header('Content-Type: text/csv; charset=windows-1251');
    header('Content-Disposition: attachment; filename=' . $filename);

    $output = fopen('php://output', 'w');

    // Конвертация строки в Windows-1251
    function convertToWin1251(array $arr) {
        return array_map(function($value) {
            return iconv('UTF-8', 'Windows-1251//IGNORE', $value);
        }, $arr);
    }

    // Заголовки
    fputcsv($output, convertToWin1251(['ID', 'Артикул', 'Название товара', 'Позиция на складе', 'Штрихкод', 'Кол-во на складе', 'Кол-во продано', 'Цена']), ';');

    // Получаем товары с количеством меньше порога
    $stmt = $pdo->prepare("SELECT id, name, namename, place, code, quantityskl, quantitysold, price FROM tovar WHERE quantityskl < ?");
    $stmt->execute([$threshold]);

    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        fputcsv($output, convertToWin1251($row), ';');
    }

    fclose($output);
    exit;
}
