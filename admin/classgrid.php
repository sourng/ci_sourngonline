<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($class_grid)) $class_grid = new cclass_grid();

// Page init
$class_grid->Page_Init();

// Page main
$class_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$class_grid->Page_Render();
?>
<?php if ($class->Export == "") { ?>
<script type="text/javascript">

// Form object
var fclassgrid = new ew_Form("fclassgrid", "grid");
fclassgrid.FormKeyCountName = '<?php echo $class_grid->FormKeyCountName ?>';

// Validate form
fclassgrid.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_teacher_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $class->teacher_id->FldCaption(), $class->teacher_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_teacher_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($class->teacher_id->FldErrMsg()) ?>");

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
		} // End Grid Add checking
	}
	return true;
}

// Check empty row
fclassgrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "teacher_id", false)) return false;
	return true;
}

// Form_CustomValidate event
fclassgrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fclassgrid.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
// Form object for search

</script>
<?php } ?>
<?php
if ($class->CurrentAction == "gridadd") {
	if ($class->CurrentMode == "copy") {
		$bSelectLimit = $class_grid->UseSelectLimit;
		if ($bSelectLimit) {
			$class_grid->TotalRecs = $class->ListRecordCount();
			$class_grid->Recordset = $class_grid->LoadRecordset($class_grid->StartRec-1, $class_grid->DisplayRecs);
		} else {
			if ($class_grid->Recordset = $class_grid->LoadRecordset())
				$class_grid->TotalRecs = $class_grid->Recordset->RecordCount();
		}
		$class_grid->StartRec = 1;
		$class_grid->DisplayRecs = $class_grid->TotalRecs;
	} else {
		$class->CurrentFilter = "0=1";
		$class_grid->StartRec = 1;
		$class_grid->DisplayRecs = $class->GridAddRowCount;
	}
	$class_grid->TotalRecs = $class_grid->DisplayRecs;
	$class_grid->StopRec = $class_grid->DisplayRecs;
} else {
	$bSelectLimit = $class_grid->UseSelectLimit;
	if ($bSelectLimit) {
		if ($class_grid->TotalRecs <= 0)
			$class_grid->TotalRecs = $class->ListRecordCount();
	} else {
		if (!$class_grid->Recordset && ($class_grid->Recordset = $class_grid->LoadRecordset()))
			$class_grid->TotalRecs = $class_grid->Recordset->RecordCount();
	}
	$class_grid->StartRec = 1;
	$class_grid->DisplayRecs = $class_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$class_grid->Recordset = $class_grid->LoadRecordset($class_grid->StartRec-1, $class_grid->DisplayRecs);

	// Set no record found message
	if ($class->CurrentAction == "" && $class_grid->TotalRecs == 0) {
		if ($class_grid->SearchWhere == "0=101")
			$class_grid->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$class_grid->setWarningMessage($Language->Phrase("NoRecord"));
	}
}
$class_grid->RenderOtherOptions();
?>
<?php $class_grid->ShowPageHeader(); ?>
<?php
$class_grid->ShowMessage();
?>
<?php if ($class_grid->TotalRecs > 0 || $class->CurrentAction <> "") { ?>
<div class="box ewBox ewGrid<?php if ($class_grid->IsAddOrEdit()) { ?> ewGridAddEdit<?php } ?> class">
<div id="fclassgrid" class="ewForm ewListForm form-inline">
<div id="gmp_class" class="<?php if (ew_IsResponsiveLayout()) { ?>table-responsive <?php } ?>ewGridMiddlePanel">
<table id="tbl_classgrid" class="table ewTable">
<thead>
	<tr class="ewTableHeader">
<?php

// Header row
$class_grid->RowType = EW_ROWTYPE_HEADER;

// Render list options
$class_grid->RenderListOptions();

// Render list options (header, left)
$class_grid->ListOptions->Render("header", "left");
?>
<?php if ($class->class_id->Visible) { // class_id ?>
	<?php if ($class->SortUrl($class->class_id) == "") { ?>
		<th data-name="class_id" class="<?php echo $class->class_id->HeaderCellClass() ?>"><div id="elh_class_class_id" class="class_class_id"><div class="ewTableHeaderCaption"><?php echo $class->class_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="class_id" class="<?php echo $class->class_id->HeaderCellClass() ?>"><div><div id="elh_class_class_id" class="class_class_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $class->class_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($class->class_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($class->class_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($class->teacher_id->Visible) { // teacher_id ?>
	<?php if ($class->SortUrl($class->teacher_id) == "") { ?>
		<th data-name="teacher_id" class="<?php echo $class->teacher_id->HeaderCellClass() ?>"><div id="elh_class_teacher_id" class="class_teacher_id"><div class="ewTableHeaderCaption"><?php echo $class->teacher_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="teacher_id" class="<?php echo $class->teacher_id->HeaderCellClass() ?>"><div><div id="elh_class_teacher_id" class="class_teacher_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $class->teacher_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($class->teacher_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($class->teacher_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php

// Render list options (header, right)
$class_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$class_grid->StartRec = 1;
$class_grid->StopRec = $class_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($class_grid->FormKeyCountName) && ($class->CurrentAction == "gridadd" || $class->CurrentAction == "gridedit" || $class->CurrentAction == "F")) {
		$class_grid->KeyCount = $objForm->GetValue($class_grid->FormKeyCountName);
		$class_grid->StopRec = $class_grid->StartRec + $class_grid->KeyCount - 1;
	}
}
$class_grid->RecCnt = $class_grid->StartRec - 1;
if ($class_grid->Recordset && !$class_grid->Recordset->EOF) {
	$class_grid->Recordset->MoveFirst();
	$bSelectLimit = $class_grid->UseSelectLimit;
	if (!$bSelectLimit && $class_grid->StartRec > 1)
		$class_grid->Recordset->Move($class_grid->StartRec - 1);
} elseif (!$class->AllowAddDeleteRow && $class_grid->StopRec == 0) {
	$class_grid->StopRec = $class->GridAddRowCount;
}

// Initialize aggregate
$class->RowType = EW_ROWTYPE_AGGREGATEINIT;
$class->ResetAttrs();
$class_grid->RenderRow();
if ($class->CurrentAction == "gridadd")
	$class_grid->RowIndex = 0;
if ($class->CurrentAction == "gridedit")
	$class_grid->RowIndex = 0;
while ($class_grid->RecCnt < $class_grid->StopRec) {
	$class_grid->RecCnt++;
	if (intval($class_grid->RecCnt) >= intval($class_grid->StartRec)) {
		$class_grid->RowCnt++;
		if ($class->CurrentAction == "gridadd" || $class->CurrentAction == "gridedit" || $class->CurrentAction == "F") {
			$class_grid->RowIndex++;
			$objForm->Index = $class_grid->RowIndex;
			if ($objForm->HasValue($class_grid->FormActionName))
				$class_grid->RowAction = strval($objForm->GetValue($class_grid->FormActionName));
			elseif ($class->CurrentAction == "gridadd")
				$class_grid->RowAction = "insert";
			else
				$class_grid->RowAction = "";
		}

		// Set up key count
		$class_grid->KeyCount = $class_grid->RowIndex;

		// Init row class and style
		$class->ResetAttrs();
		$class->CssClass = "";
		if ($class->CurrentAction == "gridadd") {
			if ($class->CurrentMode == "copy") {
				$class_grid->LoadRowValues($class_grid->Recordset); // Load row values
				$class_grid->SetRecordKey($class_grid->RowOldKey, $class_grid->Recordset); // Set old record key
			} else {
				$class_grid->LoadRowValues(); // Load default values
				$class_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$class_grid->LoadRowValues($class_grid->Recordset); // Load row values
		}
		$class->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($class->CurrentAction == "gridadd") // Grid add
			$class->RowType = EW_ROWTYPE_ADD; // Render add
		if ($class->CurrentAction == "gridadd" && $class->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$class_grid->RestoreCurrentRowFormValues($class_grid->RowIndex); // Restore form values
		if ($class->CurrentAction == "gridedit") { // Grid edit
			if ($class->EventCancelled) {
				$class_grid->RestoreCurrentRowFormValues($class_grid->RowIndex); // Restore form values
			}
			if ($class_grid->RowAction == "insert")
				$class->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$class->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($class->CurrentAction == "gridedit" && ($class->RowType == EW_ROWTYPE_EDIT || $class->RowType == EW_ROWTYPE_ADD) && $class->EventCancelled) // Update failed
			$class_grid->RestoreCurrentRowFormValues($class_grid->RowIndex); // Restore form values
		if ($class->RowType == EW_ROWTYPE_EDIT) // Edit row
			$class_grid->EditRowCnt++;
		if ($class->CurrentAction == "F") // Confirm row
			$class_grid->RestoreCurrentRowFormValues($class_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$class->RowAttrs = array_merge($class->RowAttrs, array('data-rowindex'=>$class_grid->RowCnt, 'id'=>'r' . $class_grid->RowCnt . '_class', 'data-rowtype'=>$class->RowType));

		// Render row
		$class_grid->RenderRow();

		// Render list options
		$class_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($class_grid->RowAction <> "delete" && $class_grid->RowAction <> "insertdelete" && !($class_grid->RowAction == "insert" && $class->CurrentAction == "F" && $class_grid->EmptyRow())) {
?>
	<tr<?php echo $class->RowAttributes() ?>>
<?php

// Render list options (body, left)
$class_grid->ListOptions->Render("body", "left", $class_grid->RowCnt);
?>
	<?php if ($class->class_id->Visible) { // class_id ?>
		<td data-name="class_id"<?php echo $class->class_id->CellAttributes() ?>>
<?php if ($class->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-table="class" data-field="x_class_id" name="o<?php echo $class_grid->RowIndex ?>_class_id" id="o<?php echo $class_grid->RowIndex ?>_class_id" value="<?php echo ew_HtmlEncode($class->class_id->OldValue) ?>">
<?php } ?>
<?php if ($class->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $class_grid->RowCnt ?>_class_class_id" class="form-group class_class_id">
<span<?php echo $class->class_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $class->class_id->EditValue ?></p></span>
</span>
<input type="hidden" data-table="class" data-field="x_class_id" name="x<?php echo $class_grid->RowIndex ?>_class_id" id="x<?php echo $class_grid->RowIndex ?>_class_id" value="<?php echo ew_HtmlEncode($class->class_id->CurrentValue) ?>">
<?php } ?>
<?php if ($class->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $class_grid->RowCnt ?>_class_class_id" class="class_class_id">
<span<?php echo $class->class_id->ViewAttributes() ?>>
<?php echo $class->class_id->ListViewValue() ?></span>
</span>
<?php if ($class->CurrentAction <> "F") { ?>
<input type="hidden" data-table="class" data-field="x_class_id" name="x<?php echo $class_grid->RowIndex ?>_class_id" id="x<?php echo $class_grid->RowIndex ?>_class_id" value="<?php echo ew_HtmlEncode($class->class_id->FormValue) ?>">
<input type="hidden" data-table="class" data-field="x_class_id" name="o<?php echo $class_grid->RowIndex ?>_class_id" id="o<?php echo $class_grid->RowIndex ?>_class_id" value="<?php echo ew_HtmlEncode($class->class_id->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="class" data-field="x_class_id" name="fclassgrid$x<?php echo $class_grid->RowIndex ?>_class_id" id="fclassgrid$x<?php echo $class_grid->RowIndex ?>_class_id" value="<?php echo ew_HtmlEncode($class->class_id->FormValue) ?>">
<input type="hidden" data-table="class" data-field="x_class_id" name="fclassgrid$o<?php echo $class_grid->RowIndex ?>_class_id" id="fclassgrid$o<?php echo $class_grid->RowIndex ?>_class_id" value="<?php echo ew_HtmlEncode($class->class_id->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($class->teacher_id->Visible) { // teacher_id ?>
		<td data-name="teacher_id"<?php echo $class->teacher_id->CellAttributes() ?>>
<?php if ($class->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $class_grid->RowCnt ?>_class_teacher_id" class="form-group class_teacher_id">
<input type="text" data-table="class" data-field="x_teacher_id" name="x<?php echo $class_grid->RowIndex ?>_teacher_id" id="x<?php echo $class_grid->RowIndex ?>_teacher_id" size="30" placeholder="<?php echo ew_HtmlEncode($class->teacher_id->getPlaceHolder()) ?>" value="<?php echo $class->teacher_id->EditValue ?>"<?php echo $class->teacher_id->EditAttributes() ?>>
</span>
<input type="hidden" data-table="class" data-field="x_teacher_id" name="o<?php echo $class_grid->RowIndex ?>_teacher_id" id="o<?php echo $class_grid->RowIndex ?>_teacher_id" value="<?php echo ew_HtmlEncode($class->teacher_id->OldValue) ?>">
<?php } ?>
<?php if ($class->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $class_grid->RowCnt ?>_class_teacher_id" class="form-group class_teacher_id">
<input type="text" data-table="class" data-field="x_teacher_id" name="x<?php echo $class_grid->RowIndex ?>_teacher_id" id="x<?php echo $class_grid->RowIndex ?>_teacher_id" size="30" placeholder="<?php echo ew_HtmlEncode($class->teacher_id->getPlaceHolder()) ?>" value="<?php echo $class->teacher_id->EditValue ?>"<?php echo $class->teacher_id->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($class->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $class_grid->RowCnt ?>_class_teacher_id" class="class_teacher_id">
<span<?php echo $class->teacher_id->ViewAttributes() ?>>
<?php echo $class->teacher_id->ListViewValue() ?></span>
</span>
<?php if ($class->CurrentAction <> "F") { ?>
<input type="hidden" data-table="class" data-field="x_teacher_id" name="x<?php echo $class_grid->RowIndex ?>_teacher_id" id="x<?php echo $class_grid->RowIndex ?>_teacher_id" value="<?php echo ew_HtmlEncode($class->teacher_id->FormValue) ?>">
<input type="hidden" data-table="class" data-field="x_teacher_id" name="o<?php echo $class_grid->RowIndex ?>_teacher_id" id="o<?php echo $class_grid->RowIndex ?>_teacher_id" value="<?php echo ew_HtmlEncode($class->teacher_id->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="class" data-field="x_teacher_id" name="fclassgrid$x<?php echo $class_grid->RowIndex ?>_teacher_id" id="fclassgrid$x<?php echo $class_grid->RowIndex ?>_teacher_id" value="<?php echo ew_HtmlEncode($class->teacher_id->FormValue) ?>">
<input type="hidden" data-table="class" data-field="x_teacher_id" name="fclassgrid$o<?php echo $class_grid->RowIndex ?>_teacher_id" id="fclassgrid$o<?php echo $class_grid->RowIndex ?>_teacher_id" value="<?php echo ew_HtmlEncode($class->teacher_id->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$class_grid->ListOptions->Render("body", "right", $class_grid->RowCnt);
?>
	</tr>
<?php if ($class->RowType == EW_ROWTYPE_ADD || $class->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fclassgrid.UpdateOpts(<?php echo $class_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($class->CurrentAction <> "gridadd" || $class->CurrentMode == "copy")
		if (!$class_grid->Recordset->EOF) $class_grid->Recordset->MoveNext();
}
?>
<?php
	if ($class->CurrentMode == "add" || $class->CurrentMode == "copy" || $class->CurrentMode == "edit") {
		$class_grid->RowIndex = '$rowindex$';
		$class_grid->LoadRowValues();

		// Set row properties
		$class->ResetAttrs();
		$class->RowAttrs = array_merge($class->RowAttrs, array('data-rowindex'=>$class_grid->RowIndex, 'id'=>'r0_class', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($class->RowAttrs["class"], "ewTemplate");
		$class->RowType = EW_ROWTYPE_ADD;

		// Render row
		$class_grid->RenderRow();

		// Render list options
		$class_grid->RenderListOptions();
		$class_grid->StartRowCnt = 0;
?>
	<tr<?php echo $class->RowAttributes() ?>>
<?php

// Render list options (body, left)
$class_grid->ListOptions->Render("body", "left", $class_grid->RowIndex);
?>
	<?php if ($class->class_id->Visible) { // class_id ?>
		<td data-name="class_id">
<?php if ($class->CurrentAction <> "F") { ?>
<?php } else { ?>
<span id="el$rowindex$_class_class_id" class="form-group class_class_id">
<span<?php echo $class->class_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $class->class_id->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="class" data-field="x_class_id" name="x<?php echo $class_grid->RowIndex ?>_class_id" id="x<?php echo $class_grid->RowIndex ?>_class_id" value="<?php echo ew_HtmlEncode($class->class_id->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="class" data-field="x_class_id" name="o<?php echo $class_grid->RowIndex ?>_class_id" id="o<?php echo $class_grid->RowIndex ?>_class_id" value="<?php echo ew_HtmlEncode($class->class_id->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($class->teacher_id->Visible) { // teacher_id ?>
		<td data-name="teacher_id">
<?php if ($class->CurrentAction <> "F") { ?>
<span id="el$rowindex$_class_teacher_id" class="form-group class_teacher_id">
<input type="text" data-table="class" data-field="x_teacher_id" name="x<?php echo $class_grid->RowIndex ?>_teacher_id" id="x<?php echo $class_grid->RowIndex ?>_teacher_id" size="30" placeholder="<?php echo ew_HtmlEncode($class->teacher_id->getPlaceHolder()) ?>" value="<?php echo $class->teacher_id->EditValue ?>"<?php echo $class->teacher_id->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_class_teacher_id" class="form-group class_teacher_id">
<span<?php echo $class->teacher_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $class->teacher_id->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="class" data-field="x_teacher_id" name="x<?php echo $class_grid->RowIndex ?>_teacher_id" id="x<?php echo $class_grid->RowIndex ?>_teacher_id" value="<?php echo ew_HtmlEncode($class->teacher_id->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="class" data-field="x_teacher_id" name="o<?php echo $class_grid->RowIndex ?>_teacher_id" id="o<?php echo $class_grid->RowIndex ?>_teacher_id" value="<?php echo ew_HtmlEncode($class->teacher_id->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$class_grid->ListOptions->Render("body", "right", $class_grid->RowIndex);
?>
<script type="text/javascript">
fclassgrid.UpdateOpts(<?php echo $class_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($class->CurrentMode == "add" || $class->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $class_grid->FormKeyCountName ?>" id="<?php echo $class_grid->FormKeyCountName ?>" value="<?php echo $class_grid->KeyCount ?>">
<?php echo $class_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($class->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $class_grid->FormKeyCountName ?>" id="<?php echo $class_grid->FormKeyCountName ?>" value="<?php echo $class_grid->KeyCount ?>">
<?php echo $class_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($class->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="fclassgrid">
</div>
<?php

// Close recordset
if ($class_grid->Recordset)
	$class_grid->Recordset->Close();
?>
<?php if ($class_grid->ShowOtherOptions) { ?>
<div class="box-footer ewGridLowerPanel">
<?php
	foreach ($class_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
</div>
</div>
<?php } ?>
<?php if ($class_grid->TotalRecs == 0 && $class->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($class_grid->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($class->Export == "") { ?>
<script type="text/javascript">
fclassgrid.Init();
</script>
<?php } ?>
<?php
$class_grid->Page_Terminate();
?>
