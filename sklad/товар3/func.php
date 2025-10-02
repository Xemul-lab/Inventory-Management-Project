<?php
include 'config.php';
$name = $_POST['name'];
$namename = $_POST['namename'];
$code = $_POST['code'];
$clientname = $_POST['clientname'];
$price = $_POST['price'];
$quantityskl = $_POST['quantityskl'];
$quantitysold = $_POST['quantitysold'];

$sql = ("SELECT euro FROM kurs WHERE id=1");
$query = $pdo->prepare($sql);
$query->execute();
$euro = $query->fetchColumn();

// Create

if (isset($_POST['submit'])) {
	$sql = ("INSERT INTO `tovar3`(`name`, `namename`, `code`,  `quantityskl` , `quantitysold`, `price`) VALUES (?,?,?,?,?,?)");
	$query = $pdo->prepare($sql);
	$query->execute([$name, $namename, $code,  $quantityskl, $quantitysold, $price]);
	$success = '<div class="alert alert-success alert-dismissible fade show" role="alert">
  <strong>Данные успешно отправлены!</strong> Вы можете закрыть это сообщение.
  <button type="button" class="close" onclick="history.back();" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>';
}
	
// Read

$sql = $pdo->prepare("SELECT * FROM `tovar3`");
$sql->execute();
$result = $sql->fetchAll();

// Update
$edit_name = $_POST['edit_name'];
$edit_namename = $_POST['edit_namename'];
$edit_code = $_POST['edit_code'];
$edit_quantityskl = $_POST['edit_quantityskl'];
$edit_price = $_POST['edit_price'];
$edit_quantitysold = $_POST['edit_quantitysold'];
$get_id = $_GET['id'];

if (isset($_POST['edit-submit'])) {
	$sqll = "UPDATE tovar3 SET name=?, namename=?, code=?, quantityskl=?, quantitysold=?, price=? WHERE id=?";
	$querys = $pdo->prepare($sqll);
	$querys->execute([$edit_name, $edit_namename, $edit_code,  $edit_quantityskl, $edit_quantitysold, $edit_price, $get_id]);
	header('Location: '. $_SERVER['HTTP_REFERER']);
}

// DELETE
if (isset($_POST['delete_submit'])) {
	$sql = "DELETE FROM tovar3 WHERE id=?";
	$query = $pdo->prepare($sql);
	$query->execute([$get_id]);
	header('Location: '. $_SERVER['HTTP_REFERER']);
}



