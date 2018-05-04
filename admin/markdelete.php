<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "markinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$mark_delete = NULL; // Initialize page object first

class cmark_delete extends cmark {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = '{1F40EE59-17A4-45D3-96B6-AA2082B3D64A}';

	// Table name
	var $TableName = 'mark';

	// Page object name
	var $PageObjName = 'mark_delete';

	// Page headings
	var $Heading = '';
	var $Subheading = '';

	// Page heading
	function PageHeading() {
		global $Language;
		if ($this->Heading <> "")
			return $this->Heading;
		if (method_exists($this, "TableCaption"))
			return $this->TableCaption();
		return "";
	}

	// Page subheading
	function PageSubheading() {
		global $Language;
		if ($this->Subheading <> "")
			return $this->Subheading;
		if ($this->TableName)
			return $Language->Phrase($this->PageID);
		return "";
	}

	// Page name
	function PageName() {
		return ew_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		$PageUrl = ew_CurrentPage() . "?";
		if ($this->UseTokenInUrl) $PageUrl .= "t=" . $this->TableVar . "&"; // Add page token
		return $PageUrl;
	}

	// Message
	function getMessage() {
		return @$_SESSION[EW_SESSION_MESSAGE];
	}

	function setMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_MESSAGE], $v);
	}

	function getFailureMessage() {
		return @$_SESSION[EW_SESSION_FAILURE_MESSAGE];
	}

	function setFailureMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_FAILURE_MESSAGE], $v);
	}

	function getSuccessMessage() {
		return @$_SESSION[EW_SESSION_SUCCESS_MESSAGE];
	}

	function setSuccessMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_SUCCESS_MESSAGE], $v);
	}

	function getWarningMessage() {
		return @$_SESSION[EW_SESSION_WARNING_MESSAGE];
	}

	function setWarningMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_WARNING_MESSAGE], $v);
	}

	// Methods to clear message
	function ClearMessage() {
		$_SESSION[EW_SESSION_MESSAGE] = "";
	}

	function ClearFailureMessage() {
		$_SESSION[EW_SESSION_FAILURE_MESSAGE] = "";
	}

	function ClearSuccessMessage() {
		$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = "";
	}

	function ClearWarningMessage() {
		$_SESSION[EW_SESSION_WARNING_MESSAGE] = "";
	}

	function ClearMessages() {
		$_SESSION[EW_SESSION_MESSAGE] = "";
		$_SESSION[EW_SESSION_FAILURE_MESSAGE] = "";
		$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = "";
		$_SESSION[EW_SESSION_WARNING_MESSAGE] = "";
	}

	// Show message
	function ShowMessage() {
		$hidden = FALSE;
		$html = "";

		// Message
		$sMessage = $this->getMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sMessage, "");
		if ($sMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sMessage;
			$html .= "<div class=\"alert alert-info ewInfo\">" . $sMessage . "</div>";
			$_SESSION[EW_SESSION_MESSAGE] = ""; // Clear message in Session
		}

		// Warning message
		$sWarningMessage = $this->getWarningMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sWarningMessage, "warning");
		if ($sWarningMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sWarningMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sWarningMessage;
			$html .= "<div class=\"alert alert-warning ewWarning\">" . $sWarningMessage . "</div>";
			$_SESSION[EW_SESSION_WARNING_MESSAGE] = ""; // Clear message in Session
		}

		// Success message
		$sSuccessMessage = $this->getSuccessMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sSuccessMessage, "success");
		if ($sSuccessMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sSuccessMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sSuccessMessage;
			$html .= "<div class=\"alert alert-success ewSuccess\">" . $sSuccessMessage . "</div>";
			$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = ""; // Clear message in Session
		}

		// Failure message
		$sErrorMessage = $this->getFailureMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sErrorMessage, "failure");
		if ($sErrorMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sErrorMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sErrorMessage;
			$html .= "<div class=\"alert alert-danger ewError\">" . $sErrorMessage . "</div>";
			$_SESSION[EW_SESSION_FAILURE_MESSAGE] = ""; // Clear message in Session
		}
		echo "<div class=\"ewMessageDialog\"" . (($hidden) ? " style=\"display: none;\"" : "") . ">" . $html . "</div>";
	}
	var $PageHeader;
	var $PageFooter;

	// Show Page Header
	function ShowPageHeader() {
		$sHeader = $this->PageHeader;
		$this->Page_DataRendering($sHeader);
		if ($sHeader <> "") { // Header exists, display
			echo "<p>" . $sHeader . "</p>";
		}
	}

	// Show Page Footer
	function ShowPageFooter() {
		$sFooter = $this->PageFooter;
		$this->Page_DataRendered($sFooter);
		if ($sFooter <> "") { // Footer exists, display
			echo "<p>" . $sFooter . "</p>";
		}
	}

	// Validate page request
	function IsPageRequest() {
		global $objForm;
		if ($this->UseTokenInUrl) {
			if ($objForm)
				return ($this->TableVar == $objForm->GetValue("t"));
			if (@$_GET["t"] <> "")
				return ($this->TableVar == $_GET["t"]);
		} else {
			return TRUE;
		}
	}
	var $Token = "";
	var $TokenTimeout = 0;
	var $CheckToken = EW_CHECK_TOKEN;
	var $CheckTokenFn = "ew_CheckToken";
	var $CreateTokenFn = "ew_CreateToken";

	// Valid Post
	function ValidPost() {
		if (!$this->CheckToken || !ew_IsPost())
			return TRUE;
		if (!isset($_POST[EW_TOKEN_NAME]))
			return FALSE;
		$fn = $this->CheckTokenFn;
		if (is_callable($fn))
			return $fn($_POST[EW_TOKEN_NAME], $this->TokenTimeout);
		return FALSE;
	}

	// Create Token
	function CreateToken() {
		global $gsToken;
		if ($this->CheckToken) {
			$fn = $this->CreateTokenFn;
			if ($this->Token == "" && is_callable($fn)) // Create token
				$this->Token = $fn();
			$gsToken = $this->Token; // Save to global variable
		}
	}

	//
	// Page class constructor
	//
	function __construct() {
		global $conn, $Language;
		$GLOBALS["Page"] = &$this;
		$this->TokenTimeout = ew_SessionTimeoutTime();

		// Language object
		if (!isset($Language)) $Language = new cLanguage();

		// Parent constuctor
		parent::__construct();

		// Table object (mark)
		if (!isset($GLOBALS["mark"]) || get_class($GLOBALS["mark"]) == "cmark") {
			$GLOBALS["mark"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["mark"];
		}

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'mark', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"]))
			$GLOBALS["gTimer"] = new cTimer();

		// Debug message
		ew_LoadDebugMsg();

		// Open connection
		if (!isset($conn))
			$conn = ew_Connect($this->DBID);
	}

	//
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsCustomExport, $gsExportFile, $UserProfile, $Language, $Security, $objForm;

		// User profile
		$UserProfile = new cUserProfile();

		// Security
		$Security = new cAdvancedSecurity();
		if (!$Security->IsLoggedIn()) $Security->AutoLogin();
		$Security->LoadCurrentUserLevel($this->ProjectID . $this->TableName);
		if (!$Security->CanDelete()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage(ew_DeniedMsg()); // Set no permission
			if ($Security->CanList())
				$this->Page_Terminate(ew_GetUrl("marklist.php"));
			else
				$this->Page_Terminate(ew_GetUrl("login.php"));
		}

		// NOTE: Security object may be needed in other part of the script, skip set to Nothing
		// 
		// Security = null;
		// 

		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->mark_id->SetVisibility();
		if ($this->IsAdd() || $this->IsCopy() || $this->IsGridAdd())
			$this->mark_id->Visible = FALSE;
		$this->student_id->SetVisibility();
		$this->subject_id->SetVisibility();
		$this->class_id->SetVisibility();
		$this->exam_id->SetVisibility();
		$this->mark_obtained->SetVisibility();
		$this->mark_total->SetVisibility();

		// Global Page Loading event (in userfn*.php)
		Page_Loading();

		// Page Load event
		$this->Page_Load();

		// Check token
		if (!$this->ValidPost()) {
			echo $Language->Phrase("InvalidPostRequest");
			$this->Page_Terminate();
			exit();
		}

		// Create Token
		$this->CreateToken();
	}

	//
	// Page_Terminate
	//
	function Page_Terminate($url = "") {
		global $gsExportFile, $gTmpImages;

		// Page Unload event
		$this->Page_Unload();

		// Global Page Unloaded event (in userfn*.php)
		Page_Unloaded();

		// Export
		global $EW_EXPORT, $mark;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($mark);
				$doc->Text = $sContent;
				if ($this->Export == "email")
					echo $this->ExportEmail($doc->Text);
				else
					$doc->Export();
				ew_DeleteTmpImages(); // Delete temp images
				exit();
			}
		}
		$this->Page_Redirecting($url);

		// Close connection
		ew_CloseConn();

		// Go to URL if specified
		if ($url <> "") {
			if (!EW_DEBUG_ENABLED && ob_get_length())
				ob_end_clean();
			ew_SaveDebugMsg();
			header("Location: " . $url);
		}
		exit();
	}
	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
	var $StartRec;
	var $TotalRecs = 0;
	var $RecCnt;
	var $RecKeys = array();
	var $Recordset;
	var $StartRowCnt = 1;
	var $RowCnt = 0;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Load key parameters
		$this->RecKeys = $this->GetRecordKeys(); // Load record keys
		$sFilter = $this->GetKeyFilter();
		if ($sFilter == "")
			$this->Page_Terminate("marklist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in mark class, markinfo.php

		$this->CurrentFilter = $sFilter;

		// Get action
		if (@$_POST["a_delete"] <> "") {
			$this->CurrentAction = $_POST["a_delete"];
		} elseif (@$_GET["a_delete"] == "1") {
			$this->CurrentAction = "D"; // Delete record directly
		} else {
			$this->CurrentAction = "I"; // Display record
		}
		if ($this->CurrentAction == "D") {
			$this->SendEmail = TRUE; // Send email on delete success
			if ($this->DeleteRows()) { // Delete rows
				if ($this->getSuccessMessage() == "")
					$this->setSuccessMessage($Language->Phrase("DeleteSuccess")); // Set up success message
				$this->Page_Terminate($this->getReturnUrl()); // Return to caller
			} else { // Delete failed
				$this->CurrentAction = "I"; // Display record
			}
		}
		if ($this->CurrentAction == "I") { // Load records for display
			if ($this->Recordset = $this->LoadRecordset())
				$this->TotalRecs = $this->Recordset->RecordCount(); // Get record count
			if ($this->TotalRecs <= 0) { // No record found, exit
				if ($this->Recordset)
					$this->Recordset->Close();
				$this->Page_Terminate("marklist.php"); // Return to list
			}
		}
	}

	// Load recordset
	function LoadRecordset($offset = -1, $rowcnt = -1) {

		// Load List page SQL
		$sSql = $this->ListSQL();
		$conn = &$this->Connection();

		// Load recordset
		$dbtype = ew_GetConnectionType($this->DBID);
		if ($this->UseSelectLimit) {
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			if ($dbtype == "MSSQL") {
				$rs = $conn->SelectLimit($sSql, $rowcnt, $offset, array("_hasOrderBy" => trim($this->getOrderBy()) || trim($this->getSessionOrderBy())));
			} else {
				$rs = $conn->SelectLimit($sSql, $rowcnt, $offset);
			}
			$conn->raiseErrorFn = '';
		} else {
			$rs = ew_LoadRecordset($sSql, $conn);
		}

		// Call Recordset Selected event
		$this->Recordset_Selected($rs);
		return $rs;
	}

	// Load row based on key values
	function LoadRow() {
		global $Security, $Language;
		$sFilter = $this->KeyFilter();

		// Call Row Selecting event
		$this->Row_Selecting($sFilter);

		// Load SQL based on filter
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$conn = &$this->Connection();
		$res = FALSE;
		$rs = ew_LoadRecordset($sSql, $conn);
		if ($rs && !$rs->EOF) {
			$res = TRUE;
			$this->LoadRowValues($rs); // Load row values
			$rs->Close();
		}
		return $res;
	}

	// Load row values from recordset
	function LoadRowValues($rs = NULL) {
		if ($rs && !$rs->EOF)
			$row = $rs->fields;
		else
			$row = $this->NewRow(); 

		// Call Row Selected event
		$this->Row_Selected($row);
		if (!$rs || $rs->EOF)
			return;
		$this->mark_id->setDbValue($row['mark_id']);
		$this->student_id->setDbValue($row['student_id']);
		$this->subject_id->setDbValue($row['subject_id']);
		$this->class_id->setDbValue($row['class_id']);
		$this->exam_id->setDbValue($row['exam_id']);
		$this->mark_obtained->setDbValue($row['mark_obtained']);
		$this->mark_total->setDbValue($row['mark_total']);
		$this->comment->setDbValue($row['comment']);
	}

	// Return a row with default values
	function NewRow() {
		$row = array();
		$row['mark_id'] = NULL;
		$row['student_id'] = NULL;
		$row['subject_id'] = NULL;
		$row['class_id'] = NULL;
		$row['exam_id'] = NULL;
		$row['mark_obtained'] = NULL;
		$row['mark_total'] = NULL;
		$row['comment'] = NULL;
		return $row;
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF)
			return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->mark_id->DbValue = $row['mark_id'];
		$this->student_id->DbValue = $row['student_id'];
		$this->subject_id->DbValue = $row['subject_id'];
		$this->class_id->DbValue = $row['class_id'];
		$this->exam_id->DbValue = $row['exam_id'];
		$this->mark_obtained->DbValue = $row['mark_obtained'];
		$this->mark_total->DbValue = $row['mark_total'];
		$this->comment->DbValue = $row['comment'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// mark_id
		// student_id
		// subject_id
		// class_id
		// exam_id
		// mark_obtained
		// mark_total
		// comment

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// mark_id
		$this->mark_id->ViewValue = $this->mark_id->CurrentValue;
		$this->mark_id->ViewCustomAttributes = "";

		// student_id
		$this->student_id->ViewValue = $this->student_id->CurrentValue;
		$this->student_id->ViewCustomAttributes = "";

		// subject_id
		$this->subject_id->ViewValue = $this->subject_id->CurrentValue;
		$this->subject_id->ViewCustomAttributes = "";

		// class_id
		$this->class_id->ViewValue = $this->class_id->CurrentValue;
		$this->class_id->ViewCustomAttributes = "";

		// exam_id
		$this->exam_id->ViewValue = $this->exam_id->CurrentValue;
		$this->exam_id->ViewCustomAttributes = "";

		// mark_obtained
		$this->mark_obtained->ViewValue = $this->mark_obtained->CurrentValue;
		$this->mark_obtained->ViewCustomAttributes = "";

		// mark_total
		$this->mark_total->ViewValue = $this->mark_total->CurrentValue;
		$this->mark_total->ViewCustomAttributes = "";

			// mark_id
			$this->mark_id->LinkCustomAttributes = "";
			$this->mark_id->HrefValue = "";
			$this->mark_id->TooltipValue = "";

			// student_id
			$this->student_id->LinkCustomAttributes = "";
			$this->student_id->HrefValue = "";
			$this->student_id->TooltipValue = "";

			// subject_id
			$this->subject_id->LinkCustomAttributes = "";
			$this->subject_id->HrefValue = "";
			$this->subject_id->TooltipValue = "";

			// class_id
			$this->class_id->LinkCustomAttributes = "";
			$this->class_id->HrefValue = "";
			$this->class_id->TooltipValue = "";

			// exam_id
			$this->exam_id->LinkCustomAttributes = "";
			$this->exam_id->HrefValue = "";
			$this->exam_id->TooltipValue = "";

			// mark_obtained
			$this->mark_obtained->LinkCustomAttributes = "";
			$this->mark_obtained->HrefValue = "";
			$this->mark_obtained->TooltipValue = "";

			// mark_total
			$this->mark_total->LinkCustomAttributes = "";
			$this->mark_total->HrefValue = "";
			$this->mark_total->TooltipValue = "";
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	//
	// Delete records based on current filter
	//
	function DeleteRows() {
		global $Language, $Security;
		$DeleteRows = TRUE;
		$sSql = $this->SQL();
		$conn = &$this->Connection();
		$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE) {
			return FALSE;
		} elseif ($rs->EOF) {
			$this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
			$rs->Close();
			return FALSE;
		}
		$rows = ($rs) ? $rs->GetRows() : array();
		$conn->BeginTrans();

		// Clone old rows
		$rsold = $rows;
		if ($rs)
			$rs->Close();

		// Call row deleting event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$DeleteRows = $this->Row_Deleting($row);
				if (!$DeleteRows) break;
			}
		}
		if ($DeleteRows) {
			$sKey = "";
			foreach ($rsold as $row) {
				$sThisKey = "";
				if ($sThisKey <> "") $sThisKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
				$sThisKey .= $row['mark_id'];
				$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
				$DeleteRows = $this->Delete($row); // Delete
				$conn->raiseErrorFn = '';
				if ($DeleteRows === FALSE)
					break;
				if ($sKey <> "") $sKey .= ", ";
				$sKey .= $sThisKey;
			}
		}
		if (!$DeleteRows) {

			// Set up error message
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("DeleteCancelled"));
			}
		}
		if ($DeleteRows) {
			$conn->CommitTrans(); // Commit the changes
		} else {
			$conn->RollbackTrans(); // Rollback changes
		}

		// Call Row Deleted event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$this->Row_Deleted($row);
			}
		}
		return $DeleteRows;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("marklist.php"), "", $this->TableVar, TRUE);
		$PageId = "delete";
		$Breadcrumb->Add("delete", $PageId, $url);
	}

	// Setup lookup filters of a field
	function SetupLookupFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		}
	}

	// Setup AutoSuggest filters of a field
	function SetupAutoSuggestFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		}
	}

	// Page Load event
	function Page_Load() {

		//echo "Page Load";
	}

	// Page Unload event
	function Page_Unload() {

		//echo "Page Unload";
	}

	// Page Redirecting event
	function Page_Redirecting(&$url) {

		// Example:
		//$url = "your URL";

	}

	// Message Showing event
	// $type = ''|'success'|'failure'|'warning'
	function Message_Showing(&$msg, $type) {
		if ($type == 'success') {

			//$msg = "your success message";
		} elseif ($type == 'failure') {

			//$msg = "your failure message";
		} elseif ($type == 'warning') {

			//$msg = "your warning message";
		} else {

			//$msg = "your message";
		}
	}

	// Page Render event
	function Page_Render() {

		//echo "Page Render";
	}

	// Page Data Rendering event
	function Page_DataRendering(&$header) {

		// Example:
		//$header = "your header";

	}

	// Page Data Rendered event
	function Page_DataRendered(&$footer) {

		// Example:
		//$footer = "your footer";

	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($mark_delete)) $mark_delete = new cmark_delete();

// Page init
$mark_delete->Page_Init();

// Page main
$mark_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$mark_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "delete";
var CurrentForm = fmarkdelete = new ew_Form("fmarkdelete", "delete");

// Form_CustomValidate event
fmarkdelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fmarkdelete.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $mark_delete->ShowPageHeader(); ?>
<?php
$mark_delete->ShowMessage();
?>
<form name="fmarkdelete" id="fmarkdelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($mark_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $mark_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="mark">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($mark_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="box ewBox ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { ?>table-responsive <?php } ?>ewGridMiddlePanel">
<table class="table ewTable">
	<thead>
	<tr class="ewTableHeader">
<?php if ($mark->mark_id->Visible) { // mark_id ?>
		<th class="<?php echo $mark->mark_id->HeaderCellClass() ?>"><span id="elh_mark_mark_id" class="mark_mark_id"><?php echo $mark->mark_id->FldCaption() ?></span></th>
<?php } ?>
<?php if ($mark->student_id->Visible) { // student_id ?>
		<th class="<?php echo $mark->student_id->HeaderCellClass() ?>"><span id="elh_mark_student_id" class="mark_student_id"><?php echo $mark->student_id->FldCaption() ?></span></th>
<?php } ?>
<?php if ($mark->subject_id->Visible) { // subject_id ?>
		<th class="<?php echo $mark->subject_id->HeaderCellClass() ?>"><span id="elh_mark_subject_id" class="mark_subject_id"><?php echo $mark->subject_id->FldCaption() ?></span></th>
<?php } ?>
<?php if ($mark->class_id->Visible) { // class_id ?>
		<th class="<?php echo $mark->class_id->HeaderCellClass() ?>"><span id="elh_mark_class_id" class="mark_class_id"><?php echo $mark->class_id->FldCaption() ?></span></th>
<?php } ?>
<?php if ($mark->exam_id->Visible) { // exam_id ?>
		<th class="<?php echo $mark->exam_id->HeaderCellClass() ?>"><span id="elh_mark_exam_id" class="mark_exam_id"><?php echo $mark->exam_id->FldCaption() ?></span></th>
<?php } ?>
<?php if ($mark->mark_obtained->Visible) { // mark_obtained ?>
		<th class="<?php echo $mark->mark_obtained->HeaderCellClass() ?>"><span id="elh_mark_mark_obtained" class="mark_mark_obtained"><?php echo $mark->mark_obtained->FldCaption() ?></span></th>
<?php } ?>
<?php if ($mark->mark_total->Visible) { // mark_total ?>
		<th class="<?php echo $mark->mark_total->HeaderCellClass() ?>"><span id="elh_mark_mark_total" class="mark_mark_total"><?php echo $mark->mark_total->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$mark_delete->RecCnt = 0;
$i = 0;
while (!$mark_delete->Recordset->EOF) {
	$mark_delete->RecCnt++;
	$mark_delete->RowCnt++;

	// Set row properties
	$mark->ResetAttrs();
	$mark->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$mark_delete->LoadRowValues($mark_delete->Recordset);

	// Render row
	$mark_delete->RenderRow();
?>
	<tr<?php echo $mark->RowAttributes() ?>>
<?php if ($mark->mark_id->Visible) { // mark_id ?>
		<td<?php echo $mark->mark_id->CellAttributes() ?>>
<span id="el<?php echo $mark_delete->RowCnt ?>_mark_mark_id" class="mark_mark_id">
<span<?php echo $mark->mark_id->ViewAttributes() ?>>
<?php echo $mark->mark_id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($mark->student_id->Visible) { // student_id ?>
		<td<?php echo $mark->student_id->CellAttributes() ?>>
<span id="el<?php echo $mark_delete->RowCnt ?>_mark_student_id" class="mark_student_id">
<span<?php echo $mark->student_id->ViewAttributes() ?>>
<?php echo $mark->student_id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($mark->subject_id->Visible) { // subject_id ?>
		<td<?php echo $mark->subject_id->CellAttributes() ?>>
<span id="el<?php echo $mark_delete->RowCnt ?>_mark_subject_id" class="mark_subject_id">
<span<?php echo $mark->subject_id->ViewAttributes() ?>>
<?php echo $mark->subject_id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($mark->class_id->Visible) { // class_id ?>
		<td<?php echo $mark->class_id->CellAttributes() ?>>
<span id="el<?php echo $mark_delete->RowCnt ?>_mark_class_id" class="mark_class_id">
<span<?php echo $mark->class_id->ViewAttributes() ?>>
<?php echo $mark->class_id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($mark->exam_id->Visible) { // exam_id ?>
		<td<?php echo $mark->exam_id->CellAttributes() ?>>
<span id="el<?php echo $mark_delete->RowCnt ?>_mark_exam_id" class="mark_exam_id">
<span<?php echo $mark->exam_id->ViewAttributes() ?>>
<?php echo $mark->exam_id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($mark->mark_obtained->Visible) { // mark_obtained ?>
		<td<?php echo $mark->mark_obtained->CellAttributes() ?>>
<span id="el<?php echo $mark_delete->RowCnt ?>_mark_mark_obtained" class="mark_mark_obtained">
<span<?php echo $mark->mark_obtained->ViewAttributes() ?>>
<?php echo $mark->mark_obtained->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($mark->mark_total->Visible) { // mark_total ?>
		<td<?php echo $mark->mark_total->CellAttributes() ?>>
<span id="el<?php echo $mark_delete->RowCnt ?>_mark_mark_total" class="mark_mark_total">
<span<?php echo $mark->mark_total->ViewAttributes() ?>>
<?php echo $mark->mark_total->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$mark_delete->Recordset->MoveNext();
}
$mark_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</div>
<div>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $mark_delete->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
</div>
</form>
<script type="text/javascript">
fmarkdelete.Init();
</script>
<?php
$mark_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$mark_delete->Page_Terminate();
?>
