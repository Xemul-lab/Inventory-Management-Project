<?php
ob_start();
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(E_ALL);

include 'config.php'; // должно создавать $pdo
if(!isset($pdo)) die("Ошибка: \$pdo не найден");

@set_time_limit(300);

// --- Создание эталонной таблицы ---
try{
    $pdo->exec("CREATE TABLE IF NOT EXISTS `tovar_reference` LIKE `tovar`");
    $cnt = (int)$pdo->query("SELECT COUNT(*) FROM `tovar_reference`")->fetchColumn();
    if($cnt===0){
        $pdo->exec("INSERT INTO `tovar_reference` SELECT * FROM `tovar`");
    }
}catch(PDOException $e){
    die("Ошибка подготовки эталона: ".$e->getMessage());
}

// --- Обработка форм ---
if($_SERVER['REQUEST_METHOD']==='POST'){
    try{
        // Редактирование
        if(isset($_POST['edit_submit'])){
            $stmt=$pdo->prepare("UPDATE tovar SET name=?, namename=?, place=?, code=?, quantityskl=?, price=? WHERE id=?");
            $stmt->execute([
                $_POST['edit_name'], $_POST['edit_namename'], $_POST['edit_place'],
                $_POST['edit_code'], $_POST['edit_quantityskl'], $_POST['edit_price'], $_POST['edit_id']
            ]);
            header("Location: inventory.php"); exit;
        }

        // Удаление
        if(isset($_POST['delete_submit'])){
            $stmt=$pdo->prepare("DELETE FROM tovar WHERE id=?");
            $stmt->execute([$_POST['delete_id']]);
            header("Location: inventory.php"); exit;
        }

        // Добавление нового товара
        if(isset($_POST['add_submit'])){
            $stmt=$pdo->prepare("INSERT INTO tovar (name, namename, place, code, quantityskl, price) VALUES (?,?,?,?,?,?)");
            $stmt->execute([
                $_POST['add_name'], $_POST['add_namename'], $_POST['add_place'],
                $_POST['add_code'], $_POST['add_quantityskl'], $_POST['add_price']
            ]);
            header("Location: inventory.php"); exit;
        }

        // Сброс изменений
        if(isset($_POST['reset_inventory'])){
            $pdo->beginTransaction();
            try{
                $pdo->exec("TRUNCATE TABLE `tovar`");
                $pdo->exec("INSERT INTO `tovar` (id,name,namename,place,code,quantityskl,quantitysold,price,euro_price)
                            SELECT id,name,namename,place,code,quantityskl,quantitysold,price,euro_price FROM `tovar_reference`");
                $pdo->commit();
            }catch(PDOException $e){
                $pdo->rollBack();
                die("Ошибка при сбросе: ".$e->getMessage());
            }
            header("Location: inventory.php"); exit;
        }

        // Завершить и сохранить как эталон
        if(isset($_POST['finish_inventory'])){
            $pdo->beginTransaction();
            try{
                $pdo->exec("SET FOREIGN_KEY_CHECKS=0");
                $pdo->exec("TRUNCATE TABLE `tovar_reference`");
                $pdo->exec("INSERT INTO `tovar_reference` SELECT * FROM `tovar`");
                $pdo->exec("SET FOREIGN_KEY_CHECKS=1");
                $pdo->commit();
                header("Location: index.php"); // <-- редирект на главную страницу
                exit;
            }catch(PDOException $e){
                if($pdo->inTransaction()) $pdo->rollBack();
                die("Ошибка при завершении: ".$e->getMessage());
            }
        }

        // Пересоздать эталон
        if(isset($_POST['recreate_reference'])){
            $pdo->exec("TRUNCATE TABLE `tovar_reference`");
            $pdo->exec("INSERT INTO `tovar_reference` SELECT * FROM `tovar`");
            header("Location: inventory.php"); exit;
        }

    }catch(PDOException $e){ if($pdo->inTransaction()) $pdo->rollBack(); die("Ошибка: ".$e->getMessage()); }
}

// --- Получение данных ---
$tovar = $pdo->query("SELECT * FROM `tovar` ORDER BY id ASC")->fetchAll(PDO::FETCH_ASSOC);
$refRows = $pdo->query("SELECT * FROM `tovar_reference`")->fetchAll(PDO::FETCH_ASSOC);
$ref_indexed = [];
foreach($refRows as $r){ if(isset($r['id'])) $ref_indexed[$r['id']] = $r; }
$fields_to_check = ['name','namename','code','quantityskl'];
?>
<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="utf-8">
<title>Инвентаризация</title>
<link href="bootstrap.min.css" rel="stylesheet">
<style>
.changed{background-color:#f8d7da!important;}
.new{background-color:#d4edda!important;}
.small-actions{display:flex;gap:8px;align-items:center;}
.table-wrap{overflow:auto;}
</style>
</head>
<body class="container mt-4">

<h1 class="mb-3">Режим инвентаризации</h1>

<div class="mb-3 small-actions">
<form method="post" style="display:inline">
    <form method="post" style="display:inline">
    <button type="submit" name="reset_inventory" 
            class="btn btn-danger btn-sm" 
            title="Сбросить все изменения и восстановить данные из бэкапа"
            onclick="return confirm('Вы уверены? Все текущие изменения будут отменены и заменены эталоном.');">
        🔄 Сбросить изменения
    </button>
</form>

<form method="post" style="display:inline">
    <button type="submit" name="finish_inventory" 
            class="btn btn-success btn-sm" 
            title="Сохранить текущее состояние как бэкап и вернуться на главную страницу"
            onclick="return confirm('Принять текущее состояние как новый эталон?');">
        ✅ Завершить и сохранить
    </button>
</form>

<form method="post" style="display:inline">
    <button type="submit" name="recreate_reference" 
            class="btn btn-secondary btn-sm" 
            title="Сохранить текущее состояние как бэкап, оставаясь на странице инвентаризации"
            onclick="return confirm('Пересоздать эталон из текущих данных?');">
        ♻️ Сохранить
    </button>
</form>
<form method="post" style="display:inline">
    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addModal">➕ Добавить товар</button>
</form>
<div class="ms-auto">
<small class="text-muted">Сравниваются поля: <?=htmlspecialchars(implode(',',$fields_to_check))?></small>
</div>
</div>

<div class="table-wrap">
<table class="table table-bordered table-sm">
<thead class="table-dark">
<tr>
<th style="width:60px">ID</th>
<th>Артикул</th>
<th>Название</th>
<th>Позиция</th>
<th>Штрихкод</th>
<th style="width:120px">Кол-во на складе</th>
<th>Действия</th>
</tr>
</thead>
<tbody>
<?php if(empty($tovar)): ?>
<tr><td colspan="7">Товаров нет</td></tr>
<?php else: foreach($tovar as $row):
    $class='';
    $id=$row['id']??null;
    if($id!==null){
        if(!isset($ref_indexed[$id])) $class='new';
        else {foreach($fields_to_check as $f){if(($ref_indexed[$id][$f]??null)!==($row[$f]??null)){$class='changed';break;}}}
    }
?>
<tr class="<?= $class ?>">
<td><?=htmlspecialchars($row['id'])?></td>
<td><?=htmlspecialchars($row['name']??'')?></td>
<td><?=htmlspecialchars($row['namename']??'')?></td>
<td><?=htmlspecialchars($row['place']??'')?></td>
<td><?=htmlspecialchars($row['code']??'')?></td>
<td><?=htmlspecialchars($row['quantityskl']??'')?></td>
<td>
<div class="d-flex gap-1">
<button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?=$row['id']?>">Edit</button>
<button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal<?=$row['id']?>">Delete</button>
</div>

<!-- Модальное редактирование -->
<div class="modal fade" id="editModal<?=$row['id']?>" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Редактировать №<?=$row['id']?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form method="post">
        <div class="modal-body">
            <input type="hidden" name="edit_id" value="<?=$row['id']?>">
            <input class="form-control mb-2" name="edit_name" value="<?=htmlspecialchars($row['name'])?>" placeholder="Артикул">
            <input class="form-control mb-2" name="edit_namename" value="<?=htmlspecialchars($row['namename'])?>" placeholder="Название">
            <input class="form-control mb-2" name="edit_place" value="<?=htmlspecialchars($row['place'])?>" placeholder="Позиция">
            <input type="number" class="form-control mb-2" name="edit_code" value="<?=htmlspecialchars($row['code'])?>" placeholder="Штрихкод">
            <input type="number" class="form-control mb-2" name="edit_quantityskl" value="<?=htmlspecialchars($row['quantityskl'])?>" placeholder="Кол-во">
            <input type="number" step="any" class="form-control mb-2" name="edit_price" value="<?=htmlspecialchars($row['price'])?>" placeholder="Цена">
        </div>
        <div class="modal-footer">
            <button type="submit" name="edit_submit" class="btn btn-primary">Сохранить</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Модальное удаление -->
<div class="modal fade" id="deleteModal<?=$row['id']?>" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Удалить №<?=$row['id']?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-footer">
        <form method="post">
            <input type="hidden" name="delete_id" value="<?=$row['id']?>">
            <button type="submit" name="delete_submit" class="btn btn-danger">Удалить</button>
        </form>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
      </div>
    </div>
  </div>
</div>

<?php endforeach; endif; ?>
</tbody>
</table>
</div>

<!-- Модальное добавление нового товара -->
<div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Добавить новый товар</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form method="post">
        <div class="modal-body">
            <input class="form-control mb-2" name="add_name" placeholder="Артикул">
            <input class="form-control mb-2" name="add_namename" placeholder="Название">
            <input class="form-control mb-2" name="add_place" placeholder="Позиция">
            <input type="number" class="form-control mb-2" name="add_code" placeholder="Штрихкод">
            <input type="number" class="form-control mb-2" name="add_quantityskl" placeholder="Кол-во">
            <input type="number" step="any" class="form-control mb-2" name="add_price" placeholder="Цена">
        </div>
        <div class="modal-footer">
            <button type="submit" name="add_submit" class="btn btn-primary">Добавить</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php ob_end_flush(); ?>


