<?php
include 'func.php';
if(isset($_POST['export_db'])) {
    exportDatabase($pdo);
}
if(isset($_FILES['sql_file']) && $_FILES['sql_file']['error'] === UPLOAD_ERR_OK) {
    importDatabase($pdo, $_FILES['sql_file']['tmp_name']);
    header("Location: " . $_SERVER['PHP_SELF']); // чтобы обновить страницу после импорта
    exit;
}
if(isset($_POST['export_csv'])) {
    exportTovarToCSV($pdo);
}
if(isset($_POST['export_low_stock'])) {
    $threshold = intval($_POST['low_stock_threshold']);
    if($threshold < 1) $threshold = 5; // минимальный порог
    exportLowStockToCSV($pdo, $threshold);
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Товары на складе</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSS -->
    <link href="bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="all.css">
</head>
<body>
<div class="container mt-3">
    <?=$success ?>

<!-- Кнопка меню -->
	<div class="container mt-3 d-flex justify-content-end">
    <div class="dropdown">
        <button class="btn btn-secondary dropdown-toggle" type="button" id="dbMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
            Меню базы данных
        </button>
        <ul class="dropdown-menu" aria-labelledby="dbMenuButton">
            <li>
                <form method="post">
    		<button type="submit" name="export_db" class="dropdown-item">Экспорт базы</button>
		</form>
            </li>

	    <li>
    		<form method="post">
        		<button type="submit" name="export_csv" class="dropdown-item">Экспорт CSV</button>
    		</form>
	    </li>

	    <li>
    		<form method="post" class="d-flex align-items-center px-3 py-1">
        		<input type="number" name="low_stock_threshold" value="5" min="1" class="form-control form-control-sm me-2" placeholder="Порог">
        		<button type="submit" name="export_low_stock" class="btn-sm btn btn-warning btn-sm py-1 px-2"> Экспорт малых остатков
			</button>
    		</form>
		</li>
		<li>
  			<a class="dropdown-item" href="inventory.php">Инвентаризация</a>
		</li>

            <li>
                <form method="post" enctype="multipart/form-data">
        		<label class="dropdown-item mb-0">
            			Импорт базы
            			<input type="file" name="sql_file" accept=".sql" class="d-none" onchange="this.form.submit()">
        		</label>
    		</form>
            </li>
        </ul>
    </div>
</div>


    <!-- ===== Кнопки модалок ===== -->
    <div class="mb-3">
        <button class="btn btn-success me-2 mb-2" data-bs-toggle="modal" data-bs-target="#Modal">
            <i class="fas fa-user-plus"></i> Добавить товар
        </button>
        <button class="btn btn-success me-2 mb-2" data-bs-toggle="modal" data-bs-target="#Scan">
            <i class="fas fa-usd"></i> Продажа
        </button>
        <button class="btn btn-success me-2 mb-2" data-bs-toggle="modal" data-bs-target="#ScanAdd">
            <i class="fas fa-dropbox"></i> Добавить на склад
        </button>
        <button class="btn btn-success me-2 mb-2" data-bs-toggle="modal" data-bs-target="#Euro">
            <i class="fas fa-euro-sign"></i> Курс евро
        </button>
	<button class="btn btn-warning mb-2" data-bs-toggle="modal" data-bs-target="#cartModal">
            <i class="fas fa-shopping-cart"></i> Корзина
            <?php if(!empty($_SESSION['cart'])): ?>
                <span class="badge bg-danger"><?=array_sum($_SESSION['cart'])?></span>
            <?php endif; ?>
        </button>
    </div>

	    <hr class="bg-danger border-2">

    <!-- ===== Ссылки на разделы ===== -->
    <div class="mb-3">
        <a href="http://localhost/клиенты/" class="btn btn-primary btn-lg me-2 mb-2">Учет клиентов</a>
        <a href="http://localhost/хортэк/" class="btn btn-primary btn-lg me-2 mb-2">хортэк</a>
        <hr class="bg-danger border-2">
        <a href="http://localhost/виега/" class="btn btn-primary btn-lg me-2 mb-2">Виега</a>
        <a href="http://localhost/товар1/" class="btn btn-primary btn-lg me-2 mb-2">товар1</a>
        <a href="http://localhost/товар2/" class="btn btn-primary btn-lg me-2 mb-2">товар2</a>
        <a href="http://localhost/товар3/" class="btn btn-primary btn-lg me-2 mb-2">товар3</a>
    </div>

    <!-- ===== Поиск и сортировка ===== -->
   <div class="d-flex mb-3 mt-3 align-items-center">
    <input type="text" id="searchInput" class="form-control me-2" placeholder="Поиск товара...">
    <select id="sortSelect" class="form-select w-auto me-2">
        <option value="">Сортировка</option>
        <option value="nameAsc">Название ↑</option>
        <option value="nameDesc">Название ↓</option>
        <option value="stockAsc">Наличие ↑</option>
        <option value="stockDesc">Наличие ↓</option>
        <option value="soldAsc">Продано ↑</option>
        <option value="soldDesc">Продано ↓</option>
    </select>
    <button class="btn btn-secondary" id="resetSortBtn"><i class="fas fa-undo"></i> Сбросить сортировку</button>
</div>

    <!-- ===== Таблица товаров ===== -->
    <table class="table table-hover shadow">
        <thead class="table-dark">
        <tr>
            <th>№</th>
            <th>Артикул</th>
            <th>Название товара</th>
            <th>Позиция на складе</th>
            <th>Штрихкод</th>
            <th>Кол-во товара на складе</th>
            <th>Кол-во товара продано</th>
            <th>Цена в рублях</th>
            <th>Цена в евро</th>
            <th>Действие</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($result as $value) { ?>
            <tr>
                <td><?=$value['id']?></td>
                <td><?=$value['name']?></td>
                <td><?=$value['namename']?></td>
                <td><?=$value['place']?></td>
                <td><?=$value['code']?></td>
                <td><?=$value['quantityskl']?></td>
                <td><?=$value['quantitysold']?></td>
                <td><?=$value['price']?></td>
                <td><?=$value['price']/$euro?></td>
                <td>
                    <button class="btn btn-success btn-sm me-1" data-bs-toggle="modal" data-bs-target="#editModal<?=$value['id']?>"><i class="fas fa-edit"></i></button>
                    <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal<?=$value['id']?>"><i class="fas fa-trash"></i></button>
    			<form method="post" style="display:inline">
        			<input type="hidden" name="product_id" value="<?=$value['id']?>">
        			<input type="hidden" name="quantity" value="1">
        			<button type="submit" name="add_to_cart" class="btn btn-warning btn-sm">
            				<i class="fas fa-shopping-cart"></i>
       					</button>
				</form>
                    <?php require 'modal.php'; ?>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>

<!-- ===== Модальные окна ===== -->

<!-- Добавить товар -->
<div class="modal fade" id="Modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow">
            <div class="modal-header">
                <h5 class="modal-title">Добавить товар</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
            </div>
            <form action="" method="post">
                <div class="modal-body">
                    <input type="text" class="form-control mb-2" name="name" placeholder="Артикул">
                    <input type="text" class="form-control mb-2" name="namename" placeholder="Название товара">
                    <input type="text" class="form-control mb-2" name="place" placeholder="Позиция на складе">
                    <input type="number" class="form-control mb-2" name="code" placeholder="Штрихкод">
                    <input type="number" class="form-control mb-2" name="quantityskl" placeholder="Кол-во товара на складе">
                    <input type="number" class="form-control mb-2" name="quantitysold" placeholder="Кол-во товара продано">
                    <input type="number" step="any" class="form-control mb-2" name="price" placeholder="Стоимость товара">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                    <button type="submit" name="submit" class="btn btn-primary">Добавить</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Продажа товара -->
<div class="modal fade" id="Scan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow">
            <div class="modal-header">
                <h5 class="modal-title">Продажа товара</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
            </div>
            <form action="" method="post">
                <div class="modal-body">
                    <input type="text" class="form-control mb-2" name="code" placeholder="Штрихкод" autofocus>
                    <input type="text" class="form-control mb-2" name="clientname" placeholder="Имя клиента">
                    <input type="tel" class="form-control mb-2" name="phone" placeholder="Телефон">
                    <input type="email" class="form-control mb-2" name="email" placeholder="Email">
                    <input type="text" class="form-control mb-2" name="address" placeholder="Адрес">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                    <button type="submit" name="scan_submit" class="btn btn-primary">Продать товар</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Добавить на склад -->
<div class="modal fade" id="ScanAdd" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow">
            <div class="modal-header">
                <h5 class="modal-title">Добавление на склад</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
            </div>
            <form action="" method="post">
                <div class="modal-body">
                    <input type="text" class="form-control" name="code" placeholder="Штрихкод" autofocus>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                    <button type="submit" name="scanadd_submit" class="btn btn-primary">Добавить в склад</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Курс евро -->
<div class="modal fade" id="Euro" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow">
            <div class="modal-header">
                <h5 class="modal-title">Изменить курс евро</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
            </div>
            <form action="" method="post">
                <div class="modal-body">
                    <input type="number" step="any" class="form-control" name="euro-price" placeholder="Стоимость евро">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                    <button type="submit" name="euro-submit" class="btn btn-primary">Сохранить</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ===== Корзина ===== -->
<!-- Корзина -->
<div class="modal fade" id="cartModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content shadow">
            <div class="modal-header">
                <h5 class="modal-title">Корзина</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
            </div>
            <div class="modal-body">
                <?php if(!empty($_SESSION['cart'])): ?>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Название</th>
                                <th>Цена</th>
                                <th>Кол-во</th>
                                <th>Сумма</th>
                                <th>Действие</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php 
                        $total = 0;
                        foreach($_SESSION['cart'] as $id => $qty):
                            $stmt = $pdo->prepare("SELECT namename, price FROM tovar WHERE id=?");
                            $stmt->execute([$id]);
                            $product = $stmt->fetch(PDO::FETCH_ASSOC);
                            $sum = $product['price'] * $qty;
                            $total += $sum;
                        ?>
                            <tr>
                                <td><?=$product['namename']?></td>
                                <td><?=$product['price']?></td>
                                <td><?=$qty?></td>
                                <td><?=$sum?></td>
                                <td>
                                    <form method="post">
                                        <input type="hidden" name="product_id" value="<?=$id?>">
                                        <button type="submit" name="remove_from_cart" class="btn btn-sm btn-danger">Удалить</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                    <h5 class="text-end">Итого: <?=$total?> руб.</h5>
                    <form method="post">
                        <button type="submit" name="checkout_submit" class="btn btn-primary float-end">Оформить заказ</button>
                    </form>
                <?php else: ?>
                    <p>Корзина пуста</p>
                <?php endif; ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
            </div>
        </div>
    </div>
</div>


<!-- JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
// Поиск по таблице
document.getElementById("searchInput").addEventListener("keyup", function () {
    let filter = this.value.toLowerCase();
    let rows = document.querySelectorAll("table tbody tr");

    rows.forEach(row => {
        let text = row.textContent.toLowerCase();
        row.style.display = text.includes(filter) ? "" : "none";
    });
});
// Сортировка таблицы
let table = document.querySelector("table");
let tbody = table.tBodies[0];
let originalRows = Array.from(tbody.rows); // сохраняем исходный порядок

document.getElementById("sortSelect").addEventListener("change", function () {
    let rows = Array.from(tbody.rows);

    switch (this.value) {
        case "nameAsc":
            rows.sort((a, b) => a.cells[2].innerText.localeCompare(b.cells[2].innerText));
            break;
        case "nameDesc":
            rows.sort((a, b) => b.cells[2].innerText.localeCompare(a.cells[2].innerText));
            break;
        case "stockAsc":
            rows.sort((a, b) => parseFloat(a.cells[5].innerText) - parseFloat(b.cells[5].innerText));
            break;
        case "stockDesc":
            rows.sort((a, b) => parseFloat(b.cells[5].innerText) - parseFloat(a.cells[5].innerText));
            break;
        case "soldAsc":
            rows.sort((a, b) => parseFloat(a.cells[6].innerText) - parseFloat(b.cells[6].innerText));
            break;
        case "soldDesc":
            rows.sort((a, b) => parseFloat(b.cells[6].innerText) - parseFloat(a.cells[6].innerText));
            break;
        default:
            return;
    }

    rows.forEach(row => tbody.appendChild(row));
});

// ====== Сброс сортировки и поиска ======
document.getElementById("resetSortBtn").addEventListener("click", function () {
    // Восстанавливаем исходный порядок
    originalRows.forEach(row => tbody.appendChild(row));
    // Очищаем селект сортировки
    document.getElementById("sortSelect").value = "";
    // Очищаем поиск
    document.getElementById("searchInput").value = "";
    // Показываем все строки
    originalRows.forEach(row => row.style.display = "");
});

// ===== Корзина =====
let cart = [];

function updateCart() {
    const tbodyCart = document.querySelector("#cartTable tbody");
    tbodyCart.innerHTML = "";
    let total = 0;

    cart.forEach((item, index) => {
        let sum = item.price * item.quantity;
        total += sum;
        tbodyCart.innerHTML += `
            <tr>
                <td>${item.name}</td>
                <td>${item.price}</td>
                <td><input type="number" min="1" value="${item.quantity}" data-index="${index}" class="cart-qty form-control form-control-sm"></td>
                <td>${sum.toFixed(2)}</td>
                <td><button class="btn btn-danger btn-sm remove-cart" data-index="${index}">Удалить</button></td>
            </tr>
        `;
    });

    document.getElementById("cartTotal").innerText = total.toFixed(2);

    // Обновление количества
    document.querySelectorAll(".cart-qty").forEach(input => {
        input.addEventListener("change", (e) => {
            let idx = e.target.dataset.index;
            cart[idx].quantity = parseInt(e.target.value) || 1;
            updateCart();
        });
    });

    // Удаление из корзины
    document.querySelectorAll(".remove-cart").forEach(btn => {
        btn.addEventListener("click", (e) => {
            let idx = e.target.dataset.index;
            cart.splice(idx, 1);
            updateCart();
        });
    });
}

// Добавление товара в корзину
document.querySelectorAll(".add-to-cart").forEach(btn => {
    btn.addEventListener("click", () => {
        let id = btn.dataset.id;
        let name = btn.dataset.name;
        let price = parseFloat(btn.dataset.price);
        let existing = cart.find(item => item.id == id);
        if(existing) existing.quantity++;
        else cart.push({id, name, price, quantity: 1});
        updateCart();
        new bootstrap.Modal(document.getElementById('CartModal')).show();
    });
});

// Оформление заказа
document.getElementById("checkoutBtn").addEventListener("click", () => {
    if(cart.length === 0) {
        alert("Корзина пуста!");
        return;
    }
    alert("Заказ оформлен!");
    cart = [];
    updateCart();
    new bootstrap.Modal(document.getElementById('CartModal')).hide();
});

</script>
</body>
</html>



