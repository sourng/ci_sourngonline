<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($subject_grid)) $subject_grid = new csubject_grid();

// Page init
$subject_grid->Page_Init();

// Page main
$subject_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$subject_grid->Page_Render();
?>
<?php if ($subject->Export == "") { ?>
<script type="text/javascript">

// Form object
var fsubjectgrid = new ew_Form("fsubjectgrid", "grid");
fsubjectgrid.FormKeyCountName = '<?php echo $subject_grid->FormKeyCountName ?>';

// Validate form
fsubjectgrid.Validate = function() {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	var $ = jQuery, fobj = this.GetForm(), $fobj = $(fobj);
	if ($fobj.find("#a_confirm").val() == "F")
		return true;
	var elm, felm, uelm, addcnt = 0;
	var $k = $fobj.find("#" + this.FormKeyCountName); // Get key_count
	var rowcnt = ($k[0]) ? parseInt($k.val(), 10) : 1;
	var startcnt = (rowcnt == 0) ? 0 : 1; // Check rowcnt == 0 => Inline-Add
	var gridinsert = $fobj.find("#a_list").val() == "gridinsert";
	for (var i = startcnt; i <= rowcnt; i++) {
		var infix = ($k[0]) ? String(i) : "";
		$fobj.data("rowindex", infix);
		var checkrow = (gridinsert) ? !this.EmptyRow(infix) : true;
		if (checkrow) {
			addcnt++;
			elm = this.GetElements("x" + infix + "_image");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $subject->image->FldCaption(), $subject->image->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_class_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $subject->class_id->FldCaption(), $subject->class_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_class_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($subject->class_id->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_teacher_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($subject->teacher_id->FldErrMsg()) ?>");

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
		} // End Grid Add checking
	}
	return true;
}

// Check empty row
fsubjectgrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "image", false)) return false;
	if (ew_ValueChanged(fobj, infix, "class_id", false)) return false;
	if (ew_ValueChanged(fobj, infix, "teacher_id", false)) return false;
	return true;
}

// Form_CustomValidate event
fsubjectgrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fsubjectgrid.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
// Form object for search

</script>
<?php } ?>
<?php
if ($subject->CurrentAction == "gridadd") {
	if ($subject->CurrentMode == "copy") {
		$bSelectLimit = $subject_grid->UseSelectLimit;
		if ($bSelectLimit) {
			$subject_grid->TotalRecs = $subject->ListRecordCount();
			$subject_grid->Recordset = $subject_grid->LoadRecordset($subject_grid->StartRec-1, $subject_grid->DisplayRecs);
		} else {
			if ($subject_grid->Recordset = $subject_grid->LoadRecordset())
				$subject_grid->TotalRecs = $subject_grid->Recordset->RecordCount();
		}
		$subject_grid->StartRec = 1;
		$subject_grid->DisplayRecs = $subject_grid->TotalRecs;
	} else {
		$subject->CurrentFilter = "0=1";
		$subject_grid->StartRec = 1;
		$subject_grid->DisplayRecs = $subject->GridAddRowCount;
	}
	$subject_grid->TotalRecs = $subject_grid->DisplayRecs;
	$subject_grid->StopRec = $subject_grid->DisplayRecs;
} else {
	$bSelectLimit = $subject_grid->UseSelectLimit;
	if ($bSelectLimit) {
		if ($subject_grid->TotalRecs <= 0)
			$subject_grid->TotalRecs = $subject->ListRecordCount();
	} else {
		if (!$subject_grid->Recordset && ($subject_grid->Recordset = $subject_grid->LoadRecordset()))
			$subject_grid->TotalRecs = $subject_grid->Recordset->RecordCount();
	}
	$subject_grid->StartRec = 1;
	$subject_grid->DisplayRecs = $subject_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$subject_grid->Recordset = $subject_grid->LoadRecordset($subject_grid->StartRec-1, $subject_grid->DisplayRecs);

	// Set no record found message
	if ($subject->CurrentAction == "" && $subject_grid->TotalRecs == 0) {
		if ($subject_grid->SearchWhere == "0=101")
			$subject_grid->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$subject_grid->setWarningMessage($Language->Phrase("NoRecord"));
	}
}
$subject_grid->RenderOtherOptions();
?>
<?php $subject_grid->ShowPageHeader(); ?>
<?php
$subject_grid->ShowMessage();
?>
<?php if ($subject_grid->TotalRecs > 0 || $subject->CurrentAction <> "") { ?>
<div class="box ewBox ewGrid<?php if ($subject_grid->IsAddOrEdit()) { ?> ewGridAddEdit<?php } ?> subject">
<div id="fsubjectgrid" class="ewForm ewListForm form-inline">
<div id="gmp_subject" class="<?php if (ew_IsResponsiveLayout()) { ?>table-responsive <?php } ?>ewGridMiddlePanel">
<table id="tbl_subjectgrid" class="table ewTable">
<thead>
	<tr class="ewTableHeader">
<?php

// Header row
$subject_grid->RowType = EW_ROWTYPE_HEADER;

// Render list options
$subject_grid->RenderListOptions();

// Render list options (header, left)
$subject_grid->ListOptions->Render("header", "left");
?>
<?php if ($subject->subject_id->Visible) { // subject_id ?>
	<?php if ($subject->SortUrl($subject->subject_id) == "") { ?>
		<th data-name="subject_id" class="<?php echo $subject->subject_id->HeaderCellClass() ?>"><div id="elh_subject_subject_id" class="subject_subject_id"><div class="ewTableHeaderCaption"><?php echo $subject->subject_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="subject_id" class="<?php echo $subject->subject_id->HeaderCellClass() ?>"><div><div id="elh_subject_subject_id" class="subject_subject_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $subject->subject_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($subject->subject_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($subject->subject_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($subject->image->Visible) { // image ?>
	<?php if ($subject->SortUrl($subject->image) == "") { ?>
		<th data-name="image" class="<?php echo $subject->image->HeaderCellClass() ?>"><div id="elh_subject_image" class="subject_image"><div class="ewTableHeaderCaption"><?php echo $subject->image->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="image" class="<?php echo $subject->image->HeaderCellClass() ?>"><div><div id="elh_subject_image" class="subject_image">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $subject->image->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($subject->image->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($subject->image->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($subject->class_id->Visible) { // class_id ?>
	<?php if ($subject->SortUrl($subject->class_id) == "") { ?>
		<th data-name="class_id" class="<?php echo $subject->class_id->HeaderCellClass() ?>"><div id="elh_subject_class_id" class="subject_class_id"><div class="ewTableHeaderCaption"><?php echo $subject->class_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="class_id" class="<?php echo $subject->class_id->HeaderCellClass() ?>"><div><div id="elh_subject_class_id" class="subject_class_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $subject->class_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($subject->class_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($subject->class_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($subject->teacher_id->Visible) { // teacher_id ?>
	<?php if ($subject->SortUrl($subject->teacher_id) == "") { ?>
		<th data-name="teacher_id" class="<?php echo $subject->teacher_id->HeaderCellClass() ?>"><div id="elh_subject_teacher_id" class="subject_teacher_id"><div class="ewTableHeaderCaption"><?php echo $subject->teacher_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="teacher_id" class="<?php echo $subject->teacher_id->HeaderCellClass() ?>"><div><div id="elh_subject_teacher_id" class="subject_teacher_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $subject->teacher_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($subject->teacher_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($subject->teacher_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php

// Render list options (header, right)
$subject_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$subject_grid->StartRec = 1;
$subject_grid->StopRec = $subject_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($subject_grid->FormKeyCountName) && ($subject->CurrentAction == "gridadd" || $subject->CurrentAction == "gridedit" || $subject->CurrentAction == "F")) {
		$subject_grid->KeyCount = $objForm->GetValue($subject_grid->FormKeyCountName);
		$subject_grid->StopRec = $subject_grid->StartRec + $subject_grid->KeyCount - 1;
	}
}
$subject_grid->RecCnt = $subject_grid->StartRec - 1;
if ($subject_grid->Recordset && !$subject_grid->Recordset->EOF) {
	$subject_grid->Recordset->MoveFirst();
	$bSelectLimit = $subject_grid->UseSelectLimit;
	if (!$bSelectLimit && $subject_grid->StartRec > 1)
		$subject_grid->Recordset->Move($subject_grid->StartRec - 1);
} elseif (!$subject->AllowAddDeleteRow && $subject_grid->StopRec == 0) {
	$subject_grid->StopRec = $subject->GridAddRowCount;
}

// Initialize aggregate
$subject->RowType = EW_ROWTYPE_AGGREGATEINIT;
$subject->ResetAttrs();
$subject_grid->RenderRow();
if ($subject->CurrentAction == "gridadd")
	$subject_grid->RowIndex = 0;
if ($subject->CurrentAction == "gridedit")
	$subject_grid->RowIndex = 0;
while ($subject_grid->RecCnt < $subject_grid->StopRec) {
	$subject_grid->RecCnt++;
	if (intval($subject_grid->RecCnt) >= intval($subject_grid->StartRec)) {
		$subject_grid->RowCnt++;
		if ($subject->CurrentAction == "gridadd" || $subject->CurrentAction == "gridedit" || $subject->CurrentAction == "F") {
			$subject_grid->RowIndex++;
			$objForm->Index = $subject_grid->RowIndex;
			if ($objForm->HasValue($subject_grid->FormActionName))
				$subject_grid->RowAction = strval($objForm->GetValue($subject_grid->FormActionName));
			elseif ($subject->CurrentAction == "gridadd")
				$subject_grid->RowAction = "insert";
			else
				$subject_grid->RowAction = "";
		}

		// Set up key count
		$subject_grid->KeyCount = $subject_grid->RowIndex;

		// Init row class and style
		$subject->ResetAttrs();
		$subject->CssClass = "";
		if ($subject->CurrentAction == "gridadd") {
			if ($subject->CurrentMode == "copy") {
				$subject_grid->LoadRowValues($subject_grid->Recordset); // Load row values
				$subject_grid->SetRecordKey($subject_grid->RowOldKey, $subject_grid->Recordset); // Set old record key
			} else {
				$subject_grid->LoadRowValues(); // Load default values
				$subject_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$subject_grid->LoadRowValues($subject_grid->Recordset); // Load row values
		}
		$subject->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($subject->CurrentAction == "gridadd") // Grid add
			$subject->RowType = EW_ROWTYPE_ADD; // Render add
		if ($subject->CurrentAction == "gridadd" && $subject->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$subject_grid->RestoreCurrentRowFormValues($subject_grid->RowIndex); // Restore form values
		if ($subject->CurrentAction == "gridedit") { // Grid edit
			if ($subject->EventCancelled) {
				$subject_grid->RestoreCurrentRowFormValues($subject_grid->RowIndex); // Restore form values
			}
			if ($subject_grid->RowAction == "insert")
				$subject->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$subject->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($subject->CurrentAction == "gridedit" && ($subject->RowType == EW_ROWTYPE_EDIT || $subject->RowType == EW_ROWTYPE_ADD) && $subject->EventCancelled) // Update failed
			$subject_grid->RestoreCurrentRowFormValues($subject_grid->RowIndex); // Restore form values
		if ($subject->RowType == EW_ROWTYPE_EDIT) // Edit row
			$subject_grid->EditRowCnt++;
		if ($subject->CurrentAction == "F") // Confirm row
			$subject_grid->RestoreCurrentRowFormValues($subject_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$subject->RowAttrs = array_merge($subject->RowAttrs, array('data-rowindex'=>$subject_grid->RowCnt, 'id'=>'r' . $subject_grid->RowCnt . '_subject', 'data-rowtype'=>$subject->RowType));

		// Render row
		$subject_grid->RenderRow();

		// Render list options
		$subject_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($subject_grid->RowAction <> "delete" && $subject_grid->RowAction <> "insertdelete" && !($subject_grid->RowAction == "insert" && $subject->CurrentAction == "F" && $subject_grid->EmptyRow())) {
?>
	<tr<?php echo $subject->RowAttributes() ?>>
<?php

// Render list options (body, left)
$subject_grid->ListOptions->Render("body", "left", $subject_grid->RowCnt);
?>
	<?php if ($subject->subject_id->Visible) { // subject_id ?>
		<td data-name="subject_id"<?php echo $subject->subject_id->CellAttributes() ?>>
<?php if ($subject->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-table="subject" data-field="x_subject_id" name="o<?php echo $subject_grid->RowIndex ?>_subject_id" id="o<?php echo $subject_grid->RowIndex ?>_subject_id" value="<?php echo ew_HtmlEncode($subject->subject_id->OldValue) ?>">
<?php } ?>
<?php if ($subject->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $subject_grid->RowCnt ?>_subject_subject_id" class="form-group subject_subject_id">
<span<?php echo $subject->subject_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $subject->subject_id->EditValue ?></p></span>
</span>
<input type="hidden" data-table="subject" data-field="x_subject_id" name="x<?php echo $subject_grid->RowIndex ?>_subject_id" id="x<?php echo $subject_grid->RowIndex ?>_subject_id" value="<?php echo ew_HtmlEncode($subject->subject_id->CurrentValue) ?>">
<?php } ?>
<?php if ($subject->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $subject_grid->RowCnt ?>_subject_subject_id" class="subject_subject_id">
<span<?php echo $subject->subject_id->ViewAttributes() ?>>
<?php echo $subject->subject_id->ListViewValue() ?></span>
</span>
<?php if ($subject->CurrentAction <> "F") { ?>
<input type="hidden" data-table="subject" data-field="x_subject_id" name="x<?php echo $subject_grid->RowIndex ?>_subject_id" id="x<?php echo $subject_grid->RowIndex ?>_subject_id" value="<?php echo ew_HtmlEncode($subject->subject_id->FormValue) ?>">
<input type="hidden" data-table="subject" data-field="x_subject_id" name="o<?php echo $subject_grid->RowIndex ?>_subject_id" id="o<?php echo $subject_grid->RowIndex ?>_subject_id" value="<?php echo ew_HtmlEncode($subject->subject_id->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="subject" data-field="x_subject_id" name="fsubjectgrid$x<?php echo $subject_grid->RowIndex ?>_subject_id" id="fsubjectgrid$x<?php echo $subject_grid->RowIndex ?>_subject_id" value="<?php echo ew_HtmlEncode($subject->subject_id->FormValue) ?>">
<input type="hidden" data-table="subject" data-field="x_subject_id" name="fsubjectgrid$o<?php echo $subject_grid->RowIndex ?>_subject_id" id="fsubjectgrid$o<?php echo $subject_grid->RowIndex ?>_subject_id" value="<?php echo ew_HtmlEncode($subject->subject_id->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($subject->image->Visible) { // image ?>
		<td data-name="image"<?php echo $subject->image->CellAttributes() ?>>
<?php if ($subject->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $subject_grid->RowCnt ?>_subject_image" class="form-group subject_image">
<input type="text" data-table="subject" data-field="x_image" name="x<?php echo $subject_grid->RowIndex ?>_image" id="x<?php echo $subject_grid->RowIndex ?>_image" size="30" maxlength="250" placeholder="<?php echo ew_HtmlEncode($subject->image->getPlaceHolder()) ?>" value="<?php echo $subject->image->EditValue ?>"<?php echo $subject->image->EditAttributes() ?>>
</span>
<input type="hidden" data-table="subject" data-field="x_image" name="o<?php echo $subject_grid->RowIndex ?>_image" id="o<?php echo $subject_grid->RowIndex ?>_image" value="<?php echo ew_HtmlEncode($subject->image->OldValue) ?>">
<?php } ?>
<?php if ($subject->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $subject_grid->RowCnt ?>_subject_image" class="form-group subject_image">
<input type="text" data-table="subject" data-field="x_image" name="x<?php echo $subject_grid->RowIndex ?>_image" id="x<?php echo $subject_grid->RowIndex ?>_image" size="30" maxlength="250" placeholder="<?php echo ew_HtmlEncode($subject->image->getPlaceHolder()) ?>" value="<?php echo $subject->image->EditValue ?>"<?php echo $subject->image->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($subject->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $subject_grid->RowCnt ?>_subject_image" class="subject_image">
<span<?php echo $subject->image->ViewAttributes() ?>>
<?php echo $subject->image->ListViewValue() ?></span>
</span>
<?php if ($subject->CurrentAction <> "F") { ?>
<input type="hidden" data-table="subject" data-field="x_image" name="x<?php echo $subject_grid->RowIndex ?>_image" id="x<?php echo $subject_grid->RowIndex ?>_image" value="<?php echo ew_HtmlEncode($subject->image->FormValue) ?>">
<input type="hidden" data-table="subject" data-field="x_image" name="o<?php echo $subject_grid->RowIndex ?>_image" id="o<?php echo $subject_grid->RowIndex ?>_image" value="<?php echo ew_HtmlEncode($subject->image->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="subject" data-field="x_image" name="fsubjectgrid$x<?php echo $subject_grid->RowIndex ?>_image" id="fsubjectgrid$x<?php echo $subject_grid->RowIndex ?>_image" value="<?php echo ew_HtmlEncode($subject->image->FormValue) ?>">
<input type="hidden" data-table="subject" data-field="x_image" name="fsubjectgrid$o<?php echo $subject_grid->RowIndex ?>_image" id="fsubjectgrid$o<?php echo $subject_grid->RowIndex ?>_image" value="<?php echo ew_HtmlEncode($subject->image->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($subject->class_id->Visible) { // class_id ?>
		<td data-name="class_id"<?php echo $subject->class_id->CellAttributes() ?>>
<?php if ($subject->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $subject_grid->RowCnt ?>_subject_class_id" class="form-group subject_class_id">
<input type="text" data-table="subject" data-field="x_class_id" name="x<?php echo $subject_grid->RowIndex ?>_class_id" id="x<?php echo $subject_grid->RowIndex ?>_class_id" size="30" placeholder="<?php echo ew_HtmlEncode($subject->class_id->getPlaceHolder()) ?>" value="<?php echo $subject->class_id->EditValue ?>"<?php echo $subject->class_id->EditAttributes() ?>>
</span>
<input type="hidden" data-table="subject" data-field="x_class_id" name="o<?php echo $subject_grid->RowIndex ?>_class_id" id="o<?php echo $subject_grid->RowIndex ?>_class_id" value="<?php echo ew_HtmlEncode($subject->class_id->OldValue) ?>">
<?php } ?>
<?php if ($subject->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $subject_grid->RowCnt ?>_subject_class_id" class="form-group subject_class_id">
<input type="text" data-table="subject" data-field="x_class_id" name="x<?php echo $subject_grid->RowIndex ?>_class_id" id="x<?php echo $subject_grid->RowIndex ?>_class_id" size="30" placeholder="<?php echo ew_HtmlEncode($subject->class_id->getPlaceHolder()) ?>" value="<?php echo $subject->class_id->EditValue ?>"<?php echo $subject->class_id->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($subject->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $subject_grid->RowCnt ?>_subject_class_id" class="subject_class_id">
<span<?php echo $subject->class_id->ViewAttributes() ?>>
<?php echo $subject->class_id->ListViewValue() ?></span>
</span>
<?php if ($subject->CurrentAction <> "F") { ?>
<input type="hidden" data-table="subject" data-field="x_class_id" name="x<?php echo $subject_grid->RowIndex ?>_class_id" id="x<?php echo $subject_grid->RowIndex ?>_class_id" value="<?php echo ew_HtmlEncode($subject->class_id->FormValue) ?>">
<input type="hidden" data-table="subject" data-field="x_class_id" name="o<?php echo $subject_grid->RowIndex ?>_class_id" id="o<?php echo $subject_grid->RowIndex ?>_class_id" value="<?php echo ew_HtmlEncode($subject->class_id->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="subject" data-field="x_class_id" name="fsubjectgrid$x<?php echo $subject_grid->RowIndex ?>_class_id" id="fsubjectgrid$x<?php echo $subject_grid->RowIndex ?>_class_id" value="<?php echo ew_HtmlEncode($subject->class_id->FormValue) ?>">
<input type="hidden" data-table="subject" data-field="x_class_id" name="fsubjectgrid$o<?php echo $subject_grid->RowIndex ?>_class_id" id="fsubjectgrid$o<?php echo $subject_grid->RowIndex ?>_class_id" value="<?php echo ew_HtmlEncode($subject->class_id->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($subject->teacher_id->Visible) { // teacher_id ?>
		<td data-name="teacher_id"<?php echo $subject->teacher_id->CellAttributes() ?>>
<?php if ($subject->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<?php if ($subject->teacher_id->getSessionValue() <> "") { ?>
<span id="el<?php echo $subject_grid->RowCnt ?>_subject_teacher_id" class="form-group subject_teacher_id">
<span<?php echo $subject->teacher_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $subject->teacher_id->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $subject_grid->RowIndex ?>_teacher_id" name="x<?php echo $subject_grid->RowIndex ?>_teacher_id" value="<?php echo ew_HtmlEncode($subject->teacher_id->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $subject_grid->RowCnt ?>_subject_teacher_id" class="form-group subject_teacher_id">
<input type="text" data-table="subject" data-field="x_teacher_id" name="x<?php echo $subject_grid->RowIndex ?>_teacher_id" id="x<?php echo $subject_grid->RowIndex ?>_teacher_id" size="30" placeholder="<?php echo ew_HtmlEncode($subject->teacher_id->getPlaceHolder()) ?>" value="<?php echo $subject->teacher_id->EditValue ?>"<?php echo $subject->teacher_id->EditAttributes() ?>>
</span>
<?php } ?>
<input type="hidden" data-table="subject" data-field="x_teacher_id" name="o<?php echo $subject_grid->RowIndex ?>_teacher_id" id="o<?php echo $subject_grid->RowIndex ?>_teacher_id" value="<?php echo ew_HtmlEncode($subject->teacher_id->OldValue) ?>">
<?php } ?>
<?php if ($subject->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php if ($subject->teacher_id->getSessionValue() <> "") { ?>
<span id="el<?php echo $subject_grid->RowCnt ?>_subject_teacher_id" class="form-group subject_teacher_id">
<span<?php echo $subject->teacher_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $subject->teacher_id->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $subject_grid->RowIndex ?>_teacher_id" name="x<?php echo $subject_grid->RowIndex ?>_teacher_id" value="<?php echo ew_HtmlEncode($subject->teacher_id->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $subject_grid->RowCnt ?>_subject_teacher_id" class="form-group subject_teacher_id">
<input type="text" data-table="subject" data-field="x_teacher_id" name="x<?php echo $subject_grid->RowIndex ?>_teacher_id" id="x<?php echo $subject_grid->RowIndex ?>_teacher_id" size="30" placeholder="<?php echo ew_HtmlEncode($subject->teacher_id->getPlaceHolder()) ?>" value="<?php echo $subject->teacher_id->EditValue ?>"<?php echo $subject->teacher_id->EditAttributes() ?>>
</span>
<?php } ?>
<?php } ?>
<?php if ($subject->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $subject_grid->RowCnt ?>_subject_teacher_id" class="subject_teacher_id">
<span<?php echo $subject->teacher_id->ViewAttributes() ?>>
<?php echo $subject->teacher_id->ListViewValue() ?></span>
</span>
<?php if ($subject->CurrentAction <> "F") { ?>
<input type="hidden" data-table="subject" data-field="x_teacher_id" name="x<?php echo $subject_grid->RowIndex ?>_teacher_id" id="x<?php echo $subject_grid->RowIndex ?>_teacher_id" value="<?php echo ew_HtmlEncode($subject->teacher_id->FormValue) ?>">
<input type="hidden" data-table="subject" data-field="x_teacher_id" name="o<?php echo $subject_grid->RowIndex ?>_teacher_id" id="o<?php echo $subject_grid->RowIndex ?>_teacher_id" value="<?php echo ew_HtmlEncode($subject->teacher_id->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="subject" data-field="x_teacher_id" name="fsubjectgrid$x<?php echo $subject_grid->RowIndex ?>_teacher_id" id="fsubjectgrid$x<?php echo $subject_grid->RowIndex ?>_teacher_id" value="<?php echo ew_HtmlEncode($subject->teacher_id->FormValue) ?>">
<input type="hidden" data-table="subject" data-field="x_teacher_id" name="fsubjectgrid$o<?php echo $subject_grid->RowIndex ?>_teacher_id" id="fsubjectgrid$o<?php echo $subject_grid->RowIndex ?>_teacher_id" value="<?php echo ew_HtmlEncode($subject->teacher_id->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$subject_grid->ListOptions->Render("body", "right", $subject_grid->RowCnt);
?>
	</tr>
<?php if ($subject->RowType == EW_ROWTYPE_ADD || $subject->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fsubjectgrid.UpdateOpts(<?php echo $subject_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($subject->CurrentAction <> "gridadd" || $subject->CurrentMode == "copy")
		if (!$subject_grid->Recordset->EOF) $subject_grid->Recordset->MoveNext();
}
?>
<?php
	if ($subject->CurrentMode == "add" || $subject->CurrentMode == "copy" || $subject->CurrentMode == "edit") {
		$subject_grid->RowIndex = '$rowindex$';
		$subject_grid->LoadRowValues();

		// Set row properties
		$subject->ResetAttrs();
		$subject->RowAttrs = array_merge($subject->RowAttrs, array('data-rowindex'=>$subject_grid->RowIndex, 'id'=>'r0_subject', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($subject->RowAttrs["class"], "ewTemplate");
		$subject->RowType = EW_ROWTYPE_ADD;

		// Render row
		$subject_grid->RenderRow();

		// Render list options
		$subject_grid->RenderListOptions();
		$subject_grid->StartRowCnt = 0;
?>
	<tr<?php echo $subject->RowAttributes() ?>>
<?php

// Render list options (body, left)
$subject_grid->ListOptions->Render("body", "left", $subject_grid->RowIndex);
?>
	<?php if ($subject->subject_id->Visible) { // subject_id ?>
		<td data-name="subject_id">
<?php if ($subject->CurrentAction <> "F") { ?>
<?php } else { ?>
<span id="el$rowindex$_subject_subject_id" class="form-group subject_subject_id">
<span<?php echo $subject->subject_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $subject->subject_id->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="subject" data-field="x_subject_id" name="x<?php echo $subject_grid->RowIndex ?>_subject_id" id="x<?php echo $subject_grid->RowIndex ?>_subject_id" value="<?php echo ew_HtmlEncode($subject->subject_id->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="subject" data-field="x_subject_id" name="o<?php echo $subject_grid->RowIndex ?>_subject_id" id="o<?php echo $subject_grid->RowIndex ?>_subject_id" value="<?php echo ew_HtmlEncode($subject->subject_id->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($subject->image->Visible) { // image ?>
		<td data-name="image">
<?php if ($subject->CurrentAction <> "F") { ?>
<span id="el$rowindex$_subject_image" class="form-group subject_image">
<input type="text" data-table="subject" data-field="x_image" name="x<?php echo $subject_grid->RowIndex ?>_image" id="x<?php echo $subject_grid->RowIndex ?>_image" size="30" maxlength="250" placeholder="<?php echo ew_HtmlEncode($subject->image->getPlaceHolder()) ?>" value="<?php echo $subject->image->EditValue ?>"<?php echo $subject->image->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_subject_image" class="form-group subject_image">
<span<?php echo $subject->image->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $subject->image->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="subject" data-field="x_image" name="x<?php echo $subject_grid->RowIndex ?>_image" id="x<?php echo $subject_grid->RowIndex ?>_image" value="<?php echo ew_HtmlEncode($subject->image->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="subject" data-field="x_image" name="o<?php echo $subject_grid->RowIndex ?>_image" id="o<?php echo $subject_grid->RowIndex ?>_image" value="<?php echo ew_HtmlEncode($subject->image->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($subject->class_id->Visible) { // class_id ?>
		<td data-name="class_id">
<?php if ($subject->CurrentAction <> "F") { ?>
<span id="el$rowindex$_subject_class_id" class="form-group subject_class_id">
<input type="text" data-table="subject" data-field="x_class_id" name="x<?php echo $subject_grid->RowIndex ?>_class_id" id="x<?php echo $subject_grid->RowIndex ?>_class_id" size="30" placeholder="<?php echo ew_HtmlEncode($subject->class_id->getPlaceHolder()) ?>" value="<?php echo $subject->class_id->EditValue ?>"<?php echo $subject->class_id->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_subject_class_id" class="form-group subject_class_id">
<span<?php echo $subject->class_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $subject->class_id->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="subject" data-field="x_class_id" name="x<?php echo $subject_grid->RowIndex ?>_class_id" id="x<?php echo $subject_grid->RowIndex ?>_class_id" value="<?php echo ew_HtmlEncode($subject->class_id->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="subject" data-field="x_class_id" name="o<?php echo $subject_grid->RowIndex ?>_class_id" id="o<?php echo $subject_grid->RowIndex ?>_class_id" value="<?php echo ew_HtmlEncode($subject->class_id->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($subject->teacher_id->Visible) { // teacher_id ?>
		<td data-name="teacher_id">
<?php if ($subject->CurrentAction <> "F") { ?>
<?php if ($subject->teacher_id->getSessionValue() <> "") { ?>
<span id="el$rowindex$_subject_teacher_id" class="form-group subject_teacher_id">
<span<?php echo $subject->teacher_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $subject->teacher_id->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $subject_grid->RowIndex ?>_teacher_id" name="x<?php echo $subject_grid->RowIndex ?>_teacher_id" value="<?php echo ew_HtmlEncode($subject->teacher_id->CurrentValue) ?>">
<?php } else { ?>
<span id="el$rowindex$_subject_teacher_id" class="form-group subject_teacher_id">
<input type="text" data-table="subject" data-field="x_teacher_id" name="x<?php echo $subject_grid->RowIndex ?>_teacher_id" id="x<?php echo $subject_grid->RowIndex ?>_teacher_id" size="30" placeholder="<?php echo ew_HtmlEncode($subject->teacher_id->getPlaceHolder()) ?>" value="<?php echo $subject->teacher_id->EditValue ?>"<?php echo $subject->teacher_id->EditAttributes() ?>>
</span>
<?php } ?>
<?php } else { ?>
<span id="el$rowindex$_subject_teacher_id" class="form-group subject_teacher_id">
<span<?php echo $subject->teacher_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $subject->teacher_id->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="subject" data-field="x_teacher_id" name="x<?php echo $subject_grid->RowIndex ?>_teacher_id" id="x<?php echo $subject_grid->RowIndex ?>_teacher_id" value="<?php echo ew_HtmlEncode($subject->teacher_id->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="subject" data-field="x_teacher_id" name="o<?php echo $subject_grid->RowIndex ?>_teacher_id" id="o<?php echo $subject_grid->RowIndex ?>_teacher_id" value="<?php echo ew_HtmlEncode($subject->teacher_id->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$subject_grid->ListOptions->Render("body", "right", $subject_grid->RowIndex);
?>
<script type="text/javascript">
fsubjectgrid.UpdateOpts(<?php echo $subject_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($subject->CurrentMode == "add" || $subject->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $subject_grid->FormKeyCountName ?>" id="<?php echo $subject_grid->FormKeyCountName ?>" value="<?php echo $subject_grid->KeyCount ?>">
<?php echo $subject_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($subject->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $subject_grid->FormKeyCountName ?>" id="<?php echo $subject_grid->FormKeyCountName ?>" value="<?php echo $subject_grid->KeyCount ?>">
<?php echo $subject_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($subject->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="fsubjectgrid">
</div>
<?php

// Close recordset
if ($subject_grid->Recordset)
	$subject_grid->Recordset->Close();
?>
<?php if ($subject_grid->ShowOtherOptions) { ?>
<div class="box-footer ewGridLowerPanel">
<?php
	foreach ($subject_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
</div>
</div>
<?php } ?>
<?php if ($subject_grid->TotalRecs == 0 && $subject->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($subject_grid->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($subject->Export == "") { ?>
<script type="text/javascript">
fsubjectgrid.Init();
</script>
<?php } ?>
<?php
$subject_grid->Page_Terminate();
?>
