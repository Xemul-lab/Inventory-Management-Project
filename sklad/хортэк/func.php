<?php
include 'config.php';
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
$euro_price = $_POST['euro-price'];

//euroкурс
$sql = ("SELECT euro FROM kurs WHERE id=1");
$query = $pdo->prepare($sql);
$query->execute();
$euro = $query->fetchColumn();

// Create

if (isset($_POST['submit'])) {
	$sql = ("INSERT INTO `hortek`(`name`, `namename`, `code`,  `quantityskl` , `quantitysold`, `price`)VALUES (?,?,?,?,?,?)");
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

$sql = $pdo->prepare("SELECT * FROM `hortek`");
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
	$sqll = "UPDATE hortek SET name=?, namename=?, code=?, quantityskl=?, quantitysold=?, price=? WHERE id=?";
	$querys = $pdo->prepare($sqll);
	$querys->execute([$edit_name, $edit_namename, $edit_code,  $edit_quantityskl, $edit_quantitysold, $edit_price, $get_id]);
	header('Location: '. $_SERVER['HTTP_REFERER']);
}

// DELETE
if (isset($_POST['delete_submit'])) {
	$sql = "DELETE FROM hortek WHERE id=?";
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
			//проверка на пустую строку
			if (iconv_strlen($value)>0) 
			{
				$sqll = "SELECT price FROM hortek WHERE code=?";
				$querys = $pdo->prepare($sqll);
				$querys->execute([$value]);
				$row = $querys->fetchColumn();
				$addprice=$addprice + $row;
				$count = $count+1;
				}
			else 
			{
				continue;
			}
		}
		$sql = ("SELECT COUNT(*) FROM client WHERE clientname=?");
		$query = $pdo->prepare($sql);
		$query->execute([$clientname]);
		$check = $query->fetchColumn();
		if ($check > 0)
			{
				$sqll = "UPDATE client SET purchases=purchases+?, totalprice=totalprice+? WHERE clientname=? ";
				$querys = $pdo->prepare($sqll);
				$querys->execute([$count, $addprice, $clientname]);
				foreach ($code as $value) {
					$sqll = "UPDATE hortek SET quantityskl=quantityskl-1, quantitysold=quantitysold+1  WHERE code=?";
					$querys = $pdo->prepare($sqll);
					$querys->execute([$value]);
				}
			}
			else 
			{
				$sql = "INSERT INTO `client`(`clientname`, `phone`, `email`, `address`, `purchases`, `totalprice`) VALUES (?,?,?,?,?,?)";
				$query = $pdo->prepare($sql);
				$query->execute([$clientname, $phone, $email, $address, $count, $addprice]);
				foreach ($code as $value) {
					$sqll = "UPDATE hortek SET quantityskl=quantityskl-1, quantitysold=quantitysold+1  WHERE code=?";
					$querys = $pdo->prepare($sqll);
					$querys->execute([$value]);
				}
			}

		header('Location: '. $_SERVER['HTTP_REFERER']);
}

//Добавление на склад
if (isset($_POST['scanadd_submit'])){
	$code = explode(' ', $code);
	foreach ($code as $value) 
	{
		$sql = ("SELECT COUNT(*) FROM hortek WHERE code=?");
		$query = $pdo->prepare($sql);
		$query->execute([$value]);
		$check = $query->fetchColumn();
		if ($check > 0)
		{
			$sqll = "UPDATE hortek SET quantityskl=quantityskl+1  WHERE code=?";
			$querys = $pdo->prepare($sqll);
			$querys->execute([$value]);
			header('Location: '. $_SERVER['HTTP_REFERER']);
		} 
		else 
		{ 
			$success = '<div class="alert alert-success alert-dismissible fade show" role="alert">
		  <strong>Штрихкод не найден!!!</strong> Вы можете закрыть это сообщение.
		  <button type="button" class="close" onclick="history.back();" data-dismiss="alert" aria-label="Close">
		    <span aria-hidden="true">&times;</span>
		  </button>
			</div>';
		}
	}
}

//изменение курса валюты евро

if (isset($_POST['euro-submit'])) {
	$sqll = "UPDATE kurs SET euro=? WHERE id=1";
	$querys = $pdo->prepare($sqll);
	$querys->execute([$euro_price]);
	header('Location: '. $_SERVER['HTTP_REFERER']);
}

