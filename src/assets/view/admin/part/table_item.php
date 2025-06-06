<tr id="post-1" class="iedit author-self level-0 post-1 type-post status-publish format-standard hentry category-uncategorized">
	<th scope="row" class="check-column">
		<label class="screen-reader-text" for="cb-select-<?= $item->id;?>">Выбрать</label>
		<input id="cb-select-<?= $item["id"];?>" type="checkbox" name="item[]" value="<?= $item["id"];?>">
	</th>
	<?php foreach($columns as $column) { ?>

		<td class="<?= $column["name"];?> column-<?= $column["name"];?>" data-colname="<?= $column["title"];?>">
			<?php if( $column["name"] == "name" ) { ?>
				<strong><a href="#" class="row-title  js-cf-button-ajax-html" data-action="<?= $action_edit;?>" data-html="cf-popup-<?= $form_size;?>" data-param="id=<?= $item["id"];?>"><?= $item[$column["name"]];?></a></strong>
				<div class="row-actions">
					<span class="edit"><a href="#"  class="js-cf-button-ajax-html" data-action="<?= $action_edit;?>" data-html="cf-popup-<?= $form_size;?>" data-param="id=<?= $item["id"];?>">Изменить</a> | </span>
					<span class="trash"><a href="#" class="submitdelete js-cf-ajax-html" data-action="<?= $action_delete;?>-form" data-html="cf-popup-<?= $form_size;?>" data-param="id=<?= $item["id"];?>&table=<?= $table;?>">Удалить</a></span>
				</div>


			<?php } else {?>
				<?= $item[$column["name"]];?>
			<?php } ?>
		</td>
	<?php } ?>
</tr>