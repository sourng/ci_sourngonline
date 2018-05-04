<?php

// teacher_id
// name
// birthday
// sex
// phone
// email
// photo
// active

?>
<?php if ($teacher->Visible) { ?>
<div class="ewMasterDiv">
<table id="tbl_teachermaster" class="table ewViewTable ewMasterTable ewVertical">
	<tbody>
<?php if ($teacher->teacher_id->Visible) { // teacher_id ?>
		<tr id="r_teacher_id">
			<td class="col-sm-2"><?php echo $teacher->teacher_id->FldCaption() ?></td>
			<td<?php echo $teacher->teacher_id->CellAttributes() ?>>
<span id="el_teacher_teacher_id">
<span<?php echo $teacher->teacher_id->ViewAttributes() ?>>
<?php echo $teacher->teacher_id->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($teacher->name->Visible) { // name ?>
		<tr id="r_name">
			<td class="col-sm-2"><?php echo $teacher->name->FldCaption() ?></td>
			<td<?php echo $teacher->name->CellAttributes() ?>>
<span id="el_teacher_name">
<span<?php echo $teacher->name->ViewAttributes() ?>>
<?php echo $teacher->name->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($teacher->birthday->Visible) { // birthday ?>
		<tr id="r_birthday">
			<td class="col-sm-2"><?php echo $teacher->birthday->FldCaption() ?></td>
			<td<?php echo $teacher->birthday->CellAttributes() ?>>
<span id="el_teacher_birthday">
<span<?php echo $teacher->birthday->ViewAttributes() ?>>
<?php echo $teacher->birthday->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($teacher->sex->Visible) { // sex ?>
		<tr id="r_sex">
			<td class="col-sm-2"><?php echo $teacher->sex->FldCaption() ?></td>
			<td<?php echo $teacher->sex->CellAttributes() ?>>
<span id="el_teacher_sex">
<span<?php echo $teacher->sex->ViewAttributes() ?>>
<?php echo $teacher->sex->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($teacher->phone->Visible) { // phone ?>
		<tr id="r_phone">
			<td class="col-sm-2"><?php echo $teacher->phone->FldCaption() ?></td>
			<td<?php echo $teacher->phone->CellAttributes() ?>>
<span id="el_teacher_phone">
<span<?php echo $teacher->phone->ViewAttributes() ?>>
<?php echo $teacher->phone->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($teacher->_email->Visible) { // email ?>
		<tr id="r__email">
			<td class="col-sm-2"><?php echo $teacher->_email->FldCaption() ?></td>
			<td<?php echo $teacher->_email->CellAttributes() ?>>
<span id="el_teacher__email">
<span<?php echo $teacher->_email->ViewAttributes() ?>>
<?php echo $teacher->_email->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($teacher->photo->Visible) { // photo ?>
		<tr id="r_photo">
			<td class="col-sm-2"><?php echo $teacher->photo->FldCaption() ?></td>
			<td<?php echo $teacher->photo->CellAttributes() ?>>
<span id="el_teacher_photo">
<span>
<?php echo ew_GetFileViewTag($teacher->photo, $teacher->photo->ListViewValue()) ?>
</span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($teacher->active->Visible) { // active ?>
		<tr id="r_active">
			<td class="col-sm-2"><?php echo $teacher->active->FldCaption() ?></td>
			<td<?php echo $teacher->active->CellAttributes() ?>>
<span id="el_teacher_active">
<span<?php echo $teacher->active->ViewAttributes() ?>>
<?php echo $teacher->active->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
	</tbody>
</table>
</div>
<?php } ?>
