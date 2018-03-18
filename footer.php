<table class="table" width="100%">
	<tr>
		<td align="left">
			<div class="switch switch-small">
				<input type="checkbox" id="proj-switch" <?php if($current == $inuse && $current != ''){ echo "checked";} ?> />
			</div>
		</td>

		<td align="right">
			<form id="delete-form" method="POST" action="delete.php" style="display:inline">
				<button class="btn btn-xs btn-danger" type="button" data-toggle="modal" data-target="#confirmDelete" data-title="删除" data-message="确定删除该工程:'<?=$current?>'吗?">
					<i class="glyphicon glyphicon-trash"></i> Delete
    			</button>
			</form>
		</td>
	</tr>
</table>

<?php
	include_once('delete_confirm.php');
?>
