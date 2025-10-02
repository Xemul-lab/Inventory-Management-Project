<!-- Modal Edit-->
<div class="modal fade" id="editModal<?=$value['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content shadow">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Редактировать запись № <?=$value['id'] ?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="?id=<?=$value['id'] ?>" method="post">
        	<div class="form-group">
        		<input type="text" class="form-control" name="edit_clientname" value="<?=$value['clientname'] ?>" placeholder="Имя клиента">
        	</div>
          <div class="md-form">
            <input type="tel" class="form-control" name="edit_phone" value="<?=$value['phone'] ?>" placeholder="Номер телефона">
          </div>
          <div class="form-group">
            <input type="email" class="form-control" name="edit_email" value="<?=$value['email'] ?>" placeholder="email">
          </div>
          <div class="form-group">
            <input type="text" class="form-control" name="edit_address" value="<?=$value['address'] ?>" placeholder="Адрес">
          </div>
        	<div class="md-form">
        		<input type="number" class="form-control" name="edit_purchases" value="<?=$value['purchases'] ?>" placeholder="Кол-во покупок">
        	</div>
          <div class="md-form">
            <input type="number" class="form-control" name="edit_totalprice" value="<?=$value['totalprice'] ?>" placeholder="Сумма покупок">
          </div>
        	<div class="modal-footer">
        		<button type="submit" name="edit-submit" class="btn btn-primary">Обновить</button>
        	</div>
        </form>	
      </div>
    </div>
  </div>
</div>

<!-- DELETE MODAL -->
<div class="modal fade" id="deleteModal<?=$value['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content shadow">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Удалить запись № <?=$value['id'] ?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
        <form action="?id=<?=$value['id'] ?>" method="post">
        	<button type="submit" name="delete_submit" class="btn btn-danger">Удалить</button>
    	</form>
      </div>
    </div>
  </div>
</div>
