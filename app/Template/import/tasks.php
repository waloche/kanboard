<div class="page-header">
    <h2>
        <?= t('Tasks importation for "%s"', $project['name']) ?>
    </h2>
</div>

<form action="<?= $this->u('import', 'csvRead', array('project_id'=>$project['id'])) ?>" method="post" enctype="multipart/form-data">
    <?= $this->formCsrf() ?>
    <input type="file" name="files[]"  accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" required />
    <div class="form-actions">
		CSV entries should look like that : [Title];[Commentary]
		<br>
		<br>
		<table border="0">
			<tr>
				<td style="border: 0px solid black;width:300px;">Please select column to import to</td>
				<td style="border: 0px solid black;"><?= $this->formSelect('columnId',($this->board->getColumnsList($project['id'])),$values) ?></td> 
			</tr>
			<tr>
				<td style="border: 0px solid black;width:300px;">Please select swimlane to import to</td>
				<td style="border: 0px solid black;"><?= $this->formSelect('swimId',($this->swimlane->getSwimlanesList($project['id'])),$values) ?></td> 
			</tr>
		</table>
		
        <input type="submit" value="<?= t('Import') ?>" class="btn btn-blue"/>
        <?= t('or') ?>
        <?= $this->a(t('cancel'), 'project', 'show', array('project_id'=>$project['id'])) ?>
    </div>
</form>
