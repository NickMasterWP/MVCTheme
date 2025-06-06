<table class="wp-list-table widefat fixed striped table-view-list <?= $table;?>">
	
	<thead>
		<tr>
			<td id="cb" class="manage-column column-cb check-column">
				<label class="screen-reader-text" for="cb-select-all-1">Выделить все</label>
				<input id="cb-select-all-1" type="checkbox">
			</td>
			<?php foreach($columns as $column) { ?>
			<th scope="col" id="<?= $column["name"];?>" class="manage-column column-title column-primary <?= ( isset($column["orderby"]) && $column["orderby"] ? "sortable" : "");?>  <?= ( isset($column["order"]) && $column["order"] ? $column["order"] : "asc");?>">
				<?php if ( isset($column["orderby"]) && $column["orderby"] ) { ?>
					<a href="#">
						<span><?= $column["title"];?></span>
						<span class="sorting-indicator"></span>
					</a>
				<?php } else { ?>
					<?= $column["title"];?>
				<?php } ?> 
			</th>
			<?php } ?>
		</tr>
	</thead>

	<tbody id="the-list" class="cf-the-list">
		
		<?php foreach($items as $item) {
            echo View::admin_part( "table_item", [
				"columns" => $columns,
				"item" => $item,
				"action_edit" => $action_edit,
				"table" => $table,
				"action_delete" => "cf-delete-item",
				"form_size" => $form_size
			] );
        }?>
			 
	</tbody>

	<tfoot>
		
		<tr>
	
			<td id="cb" class="manage-column column-cb check-column">
				<label class="screen-reader-text" for="cb-select-all-1">Выделить все</label>
				<input id="cb-select-all-1" type="checkbox">
			</td>
			<?php foreach($columns as $column) { ?>
			<th scope="col" id="<?= $column["name"];?>" class="manage-column column-title column-primary <?= ( isset($column["orderby"]) && $column["orderby"] ? "sortable" : "");?>  <?= ( isset($column["order"]) && $column["order"] ? $column["order"] : "asc");?>">
				<?php if ( isset($column["orderby"]) && $column["orderby"] ) { ?>
					<a href="#">
						<span><?= $column["title"];?></span>
						<span class="sorting-indicator"></span>
					</a>
				<?php } else { ?>
					<?= $column["title"];?>
				<?php } ?> 
			</th>
			<?php } ?>
		
		</tr>
	
	</tfoot>

</table>