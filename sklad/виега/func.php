<?php
include 'config.php';
$name = $_POST['name'];
$namename = $_POST['namename'];
$price = $_POST['price'];

// Create

if (isset($_POST['submit'])) {
	$sql = ("INSERT INTO `viega`(`name`, `namename`,`price`)VALUES (?,?,?)");
	$query = $pdo->prepare($sql);
	$query->execute([$name, $namename, $price]);
	$success = '<div class="alert alert-success alert-dismissible fade show" role="alert">
  <strong>Данные успешно отправлены!</strong> Вы можете закрыть это сообщение.
  <button type="button" class="close" onclick="history.back();" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>';
}
	
// Read

$sql = $pdo->prepare("SELECT * FROM `viega`");
$sql->execute();
$result = $sql->fetchAll();

// Update
$edit_name = $_POST['edit_name'];
$edit_namename = $_POST['edit_namename'];
$edit_price = $_POST['edit_price'];

$get_id = $_GET['id'];

if (isset($_POST['edit-submit'])) {
	$sqll = "UPDATE viega SET name=?, namename=?, price=? WHERE id=?";
	$querys = $pdo->prepare($sqll);
	$querys->execute([$edit_name, $edit_namename, $edit_price, $get_id]);
	header('Location: '. $_SERVER['HTTP_REFERER']);
}

// DELETE
if (isset($_POST['delete_submit'])) {
	$sql = "DELETE FROM viega WHERE id=?";
	$query = $pdo->prepare($sql);
	$query->execute([$get_id]);
	header('Location: '. $_SERVER['HTTP_REFERER']);
}



