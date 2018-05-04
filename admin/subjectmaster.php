<?php

// subject_id
// image
// class_id
// teacher_id

?>
<?php if ($subject->Visible) { ?>
<div class="ewMasterDiv">
<table id="tbl_subjectmaster" class="table ewViewTable ewMasterTable ewVertical">
	<tbody>
<?php if ($subject->subject_id->Visible) { // subject_id ?>
		<tr id="r_subject_id">
			<td class="col-sm-2"><?php echo $subject->subject_id->FldCaption() ?></td>
			<td<?php echo $subject->subject_id->CellAttributes() ?>>
<span id="el_subject_subject_id">
<span<?php echo $subject->subject_id->ViewAttributes() ?>>
<?php echo $subject->subject_id->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($subject->image->Visible) { // image ?>
		<tr id="r_image">
			<td class="col-sm-2"><?php echo $subject->image->FldCaption() ?></td>
			<td<?php echo $subject->image->CellAttributes() ?>>
<span id="el_subject_image">
<span<?php echo $subject->image->ViewAttributes() ?>>
<?php echo $subject->image->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($subject->class_id->Visible) { // class_id ?>
		<tr id="r_class_id">
			<td class="col-sm-2"><?php echo $subject->class_id->FldCaption() ?></td>
			<td<?php echo $subject->class_id->CellAttributes() ?>>
<span id="el_subject_class_id">
<span<?php echo $subject->class_id->ViewAttributes() ?>>
<?php echo $subject->class_id->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($subject->teacher_id->Visible) { // teacher_id ?>
		<tr id="r_teacher_id">
			<td class="col-sm-2"><?php echo $subject->teacher_id->FldCaption() ?></td>
			<td<?php echo $subject->teacher_id->CellAttributes() ?>>
<span id="el_subject_teacher_id">
<span<?php echo $subject->teacher_id->ViewAttributes() ?>>
<?php echo $subject->teacher_id->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
	</tbody>
</table>
</div>
<?php } ?>
