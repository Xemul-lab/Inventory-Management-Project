<?php
include 'config.php';
$clientname = $_POST['clientname'];
$phone = $_POST['phone'];
$email = $_POST['email'];
$address = $_POST['address'];
$purchases = $_POST['purchases'];
$totalprice = $_POST['totalprice'];


	
// Read

$sql = $pdo->prepare("SELECT * FROM `client`");
$sql->execute();
$result = $sql->fetchAll();

// Update
$edit_clientname = $_POST['edit_clientname'];
$edit_purchases = $_POST['edit_purchases'];
$edit_phone = $_POST['edit_phone'];
$edit_email = $_POST['edit_email'];
$edit_address = $_POST['edit_address'];
$edit_totalprice = $_POST['edit_totalprice'];
$get_id = $_GET['id'];

if (isset($_POST['edit-submit'])) {
	$sqll = "UPDATE client SET clientname=?, phone=?, email=?, address=?, purchases=?, totalprice=? WHERE id=?";
	$querys = $pdo->prepare($sqll);
	$querys->execute([$edit_clientname, $edit_phone, $edit_email, $edit_address , $edit_purchases, $edit_totalprice, $get_id]);
	header('Location: '. $_SERVER['HTTP_REFERER']);
}

// DELETE
if (isset($_POST['delete_submit'])) {
	$sql = "DELETE FROM client WHERE id=?";
	$query = $pdo->prepare($sql);
	$query->execute([$get_id]);
	header('Location: '. $_SERVER['HTTP_REFERER']);
}




