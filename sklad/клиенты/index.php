<?php
include 'func.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Учет клиентов</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="all.css">
	<script src="all.js"></script>
	<link rel="stylesheet" type="text/css" href="v4-shims.css">
	<script src="v4-shims.js"></script>
	<link href="bootstrap.css" rel="stylesheet">
	<script src="bootstrap.bundle.js"></script>
	<link href="bootstrap.min.css" rel="stylesheet">
	<script src="bootstrap.bundle.min.js"></script>
</head>
<!-- таблица-->
<body>
	<div class="container">
		<div class="row">
			<div class="col mt-1">
				<?=$success ?>
				<a href="http://localhost/склад/" class="btn btn-primary btn-lg" tabindex="-1" role="button">Товары на складе</a>
				<table class="table shadow ">
					<thead class="thead-dark">
						<tr>
							<th>№</th>
							<th>Имя клиента</th>
							<th>Телефон</th>
							<th>email</th>
							<th>Адрес</th>
							<th>Кол-во покупок</th>
							<th>Сумма покупок</th>
						</tr>
						<!-- Vivod -->
						<?php foreach ($result as $value) { ?>
						<tr>
							<td><?=$value['id'] ?></td>
							<td><?=$value['clientname'] ?></td>
							<td><?=$value['phone'] ?></td>
							<td><?=$value['email'] ?></td>
							<td><?=$value['address'] ?></td>
							<td><?=$value['purchases'] ?></td>
							<td><?=$value['totalprice'] ?></td>
							<td>
								<a href="?edit=<?=$value['id'] ?>" class="btn btn-success btn-sm" data-toggle="modal" data-target="#editModal<?=$value['id'] ?>"><i class="fa fa-edit"></i></a> 
								<a href="?delete=<?=$value['id'] ?>" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteModal<?=$value['id'] ?>"><i class="fa fa-trash"></i></a>
								<?php require 'modal.php'; ?>
							</td>
						</tr> <?php } ?>
						<!-- End Vivod-->
					</thead>
				</table>
			</div>
		</div>
	</div>
	


<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<script>
</body>
</html>