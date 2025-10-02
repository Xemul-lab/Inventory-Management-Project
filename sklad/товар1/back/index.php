<?php
include 'func.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Товары на складе</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="all.css">
	<script src="all.js"></script>
	<link rel="stylesheet" type="text/css" href="v4-shims.css">
	<script src="v4-shims.js"></script>
	<link href="bootstrap.css" rel="stylesheet">
	<script src="bootstrap.bundle.js"></script>
	<!--<link href="bootstrap.min.css" rel="stylesheet">
	<script src="bootstrap.bundle.min.js"></script>-->
</head>
<!-- таблица-->
<body>
	<div class="container">
		<div class="row">
			<div class="col mt-1">
				<?=$success ?>
				<button class="btn btn-success mb-1" data-toggle="modal" data-target="#Modal"><i class="fa fa-user-plus"></i></button>
				<button class="btn btn-success mb-1" data-toggle="modal" data-target="#Scan"><i class="fa fa-usd"></i></button>
				<button class="btn btn-success mb-1" data-toggle="modal" data-target="#ScanAdd"><i class="fa fa-dropbox"></i></button>
				<table class="table shadow ">
					<thead class="thead-dark">
						<tr>
							<th>№</th>
							<th>Артикул</th>
							<th>Название товара</th>
							<th>Штрихкод</th>
							<th>Кол-во товара на складе</th>
							<th>Кол-во товара продано</th>
							<th>Цена</th>
							<th>Действие</th>
						</tr>
						<!-- Vivod -->
						<?php foreach ($result as $value) { ?>
						<tr>
							<td><?=$value['id'] ?></td>
							<td><?=$value['name'] ?></td>
							<td><?=$value['namename'] ?></td>
							<td><?=$value['code'] ?></td>
							<td><?=$value['quantityskl'] ?></td>
							<td><?=$value['quantitysold'] ?></td>
							<td><?=$value['price'] ?></td>
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
	<!-- Modal create-->
	<div class="modal fade" tabindex="-1" role="dialog" id="Modal">
	  <div class="modal-dialog modal-dialog-centered" role="document">
	    <div class="modal-content shadow">
	      <div class="modal-header">
	        <h5 class="modal-title">Добавить товар</h5>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body">
	      	<!-- Forma -->
	        <form action="" method="post">
	        	<div class="form-group">
	        		<input type="text" class="form-control" name="name" value="" placeholder="Артикул">
	        	</div>
	        	<div class="form-group">
	        		<input type="text" class="form-control" name="namename" value="" placeholder="Название товара">
	        	</div>
	        	<div class="form-group">
	        		<input type="number" class="form-control" name="code" value="" placeholder="Штрихкод">
	        	</div>
	    		<div class="md-form">
	        		<input type="number" class="form-control" name="quantityskl" value="" placeholder="Кол-во товара на складе">
	        	</div>
	        	<div class="md-form">
	        		<input type="number" class="form-control" name="quantitysold" value="" placeholder="Кол-во товара продано">
	        	</div>
	        	<div class="md-form">
	        		<input type="number" class="form-control" name="price" value="" placeholder="Стоимость товара">
	        	</div>
	  	  </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
	        <button type="submit" name="submit" class="btn btn-primary">Добавить</button>
	      </div>
	  		</form>
	    </div>
	  </div>
	</div>
	<div class="modal fade" tabindex="-1" role="dialog" id="Scan">
	  <div class="modal-dialog modal-dialog-centered" role="document">
	    <div class="modal-content shadow">
	      <div class="modal-header">
	        <h5 class="modal-title">Покупка товара</h5>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body">
	         <form action="" method="post">
	        	<div class="form-group">
	        		<input type="text" class="form-control" name="code" value="" placeholder="Штрихкод" autofocus>
	        	</div>
				<div class="form-group">
	        		<input type="text" class="form-control" name="clientname" value="" placeholder="Имя клиента">
	        	</div>
	  	  </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
	        <button type="submit" name="scan_submit" class="btn btn-primary">Продать товар</button>
	      </div>
	  		</form>
	    </div>
	  </div>
	</div>
	<div class="modal fade" tabindex="-1" role="dialog" id="ScanAdd">
	  <div class="modal-dialog modal-dialog-centered" role="document">
	    <div class="modal-content shadow">
	      <div class="modal-header">
	        <h5 class="modal-title">Добавление на склад</h5>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body">
	         <form action="" method="post">
	        	<div class="form-group">
	        		<input type="text" class="form-control" name="code" value="" placeholder="Штрихкод" autofocus>
	        	</div>
	  	  </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
	        <button type="submit" name="scanadd_submit" class="btn btn-primary">Добавить в склад</button>
	      </div>
	  		</form>
	    </div>
	  </div>
	</div>


<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<script>
</body>
</html>