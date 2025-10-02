<?php
include 'config.php';
$name = $_POST['name'];
$namename = $_POST['namename'];
$code = $_POST['code'];
$clientname = $_POST['clientname'];
$price = $_POST['price'];
$quantityskl = $_POST['quantityskl'];
$quantitysold = $_POST['quantitysold'];

// Create

if (isset($_POST['submit'])) {
	$sql = ("INSERT INTO `tovar`(`name`, `namename`, `code`,  `quantityskl` , `quantitysold`, `price`)VALUES (?,?,?,?,?,?)");
	$query = $pdo->prepare($sql);
	$query->execute([$name, $namename, $code,  $quantityskl, $quantitysold, $price]);
	$success = '<div class="alert alert-success alert-dismissible fade show" role="alert">
  <strong>Данные успешно отправлены!</strong> Вы можете закрыть это сообщение.
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>';
	header('Location: '. $_SERVER['HTTP_REFERER']);
}

// Read

$sql = $pdo->prepare("SELECT * FROM `tovar`");
$sql->execute();
$result = $sql->fetchAll();

// Update
$edit_name = $_POST['edit_name'];
$edit_namename = $_POST['edit_namename'];
$edit_code = $_POST['edit_code'];
//$edit_clientname =  $_POST['edit_clientname'];
$edit_quantityskl = $_POST['edit_quantityskl'];
$edit_price = $_POST['edit_price'];
$edit_quantitysold = $_POST['edit_quantitysold'];
$get_id = $_GET['id'];

if (isset($_POST['edit-submit'])) {
	$sqll = "UPDATE tovar SET name=?, namename=?, code=?, quantityskl=?, quantitysold=?, price=? WHERE id=?";
	$querys = $pdo->prepare($sqll);
	$querys->execute([$edit_name, $edit_namename, $edit_code,  $edit_quantityskl, $edit_quantitysold, $edit_price, $get_id]);
	header('Location: '. $_SERVER['HTTP_REFERER']);
}

// DELETE
if (isset($_POST['delete_submit'])) {
	$sql = "DELETE FROM tovar WHERE id=?";
	$query = $pdo->prepare($sql);
	$query->execute([$get_id]);
	header('Location: '. $_SERVER['HTTP_REFERER']);
}

//SCAN
if (isset($_POST['scan_submit'])){
		$count = 0;
		$addprice = 0;
		$code = explode(' ', $code);
		foreach ($code as $value) 
		{
			$sqll = "UPDATE tovar SET quantityskl=quantityskl-1, quantitysold=quantitysold+1  WHERE code=?";
			$querys = $pdo->prepare($sqll);
			$querys->execute([$value]);
			$sqll = "SELECT price FROM tovar WHERE code=?";
			$querys = $pdo->prepare($sqll);
			$querys->execute([$value]);
			$row = $querys->fetchColumn();
			$addprice=$addprice + $row;
			$count = $count+1;
		}
		//$sql = ("INSERT INTO `client`(`clientname`, `purchases`)VALUES (?,?)");
		$sql = ("SELECT COUNT(*) FROM client WHERE clientname=?");
		$query = $pdo->prepare($sql);
		$query->execute([$clientname]);
		$check = $query->fetchColumn();
		if ($check > 0)
			{
				$sqll = "UPDATE client SET purchases=purchases+?, totalprice=totalprice+? WHERE clientname=? ";
				$querys = $pdo->prepare($sqll);
				$querys->execute([$count, $addprice, $clientname]);
			}
			else 
			{
				$sql = "INSERT INTO `client`(`clientname`, `purchases`, `totalprice`) VALUES (?,?,?)";
				$query = $pdo->prepare($sql);
				$query->execute([$clientname, $count, $addprice]);
			}

		header('Location: '. $_SERVER['HTTP_REFERER']);
}
//Добавление на склад
if (isset($_POST['scanadd_submit'])){
	$code = explode(' ', $code);
	foreach ($code as $value) 
	{
		$sqll = "UPDATE tovar SET quantityskl=quantityskl+1  WHERE code=?";
		$querys = $pdo->prepare($sqll);
		$querys->execute([$value]);
	}
	header('Location: '. $_SERVER['HTTP_REFERER']);
}



