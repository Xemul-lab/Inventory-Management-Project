<!-- Modal Edit -->
<div class="modal fade" id="editModal<?=$value['id'] ?>" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content shadow">
      <div class="modal-header">
        <h5 class="modal-title">Редактировать запись № <?=$value['id'] ?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
      </div>
      <form action="?id=<?=$value['id'] ?>" method="post">
        <div class="modal-body">
          <div class="mb-2">
            <input type="text" class="form-control" name="edit_name" value="<?=$value['name'] ?>" placeholder="Артикул">
          </div>
          <div class="mb-2">
            <input type="text" class="form-control" name="edit_namename" value="<?=$value['namename']?>" placeholder="Название товара">
          </div>
          <div class="mb-2">
            <input type="text" class="form-control" name="edit_place" value="<?=$value['place']?>" placeholder="Позиция на складе">
          </div>
          <div class="mb-2">
            <input type="number" class="form-control" name="edit_code" value="<?=$value['code'] ?>" placeholder="Штрихкод">
          </div>
          <div class="mb-2">
            <input type="number" class="form-control" name="edit_quantityskl" value="<?=$value['quantityskl'] ?>" placeholder="Кол-во товара на складе">
          </div>
          <div class="mb-2">
            <input type="number" class="form-control" name="edit_quantitysold" value="<?=$value['quantitysold'] ?>" placeholder="Кол-во товара продано">
          </div>
          <div class="mb-2">
            <input type="number" class="form-control" name="edit_price" step="any" value="<?=$value['price'] ?>" placeholder="Цена">
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" name="edit-submit" class="btn btn-primary">Обновить</button>
        </div>
      </form>  
    </div>
  </div>
</div>

<!-- DELETE MODAL -->
<div class="modal fade" id="deleteModal<?=$value['id'] ?>" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content shadow">
      <div class="modal-header">
        <h5 class="modal-title">Удалить запись № <?=$value['id'] ?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
        <form action="?id=<?=$value['id'] ?>" method="post" class="d-inline">
          <button type="submit" name="delete_submit" class="btn btn-danger">Удалить</button>
        </form>
      </div>
    </div>
  </div>
</div>
