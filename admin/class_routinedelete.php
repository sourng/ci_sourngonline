<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "class_routineinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$class_routine_delete = NULL; // Initialize page object first

class cclass_routine_delete extends cclass_routine {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = '{1F40EE59-17A4-45D3-96B6-AA2082B3D64A}';

	// Table name
	var $TableName = 'class_routine';

	// Page object name
	var $PageObjName = 'class_routine_delete';

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

		// Table object (class_routine)
		if (!isset($GLOBALS["class_routine"]) || get_class($GLOBALS["class_routine"]) == "cclass_routine") {
			$GLOBALS["class_routine"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["class_routine"];
		}

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'class_routine', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("class_routinelist.php"));
			else
				$this->Page_Terminate(ew_GetUrl("login.php"));
		}

		// NOTE: Security object may be needed in other part of the script, skip set to Nothing
		// 
		// Security = null;
		// 

		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->class_routine_id->SetVisibility();
		if ($this->IsAdd() || $this->IsCopy() || $this->IsGridAdd())
			$this->class_routine_id->Visible = FALSE;
		$this->class_id->SetVisibility();
		$this->subject_id->SetVisibility();
		$this->time_start->SetVisibility();
		$this->time_end->SetVisibility();
		$this->time_start_min->SetVisibility();
		$this->time_end_min->SetVisibility();

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
		global $EW_EXPORT, $class_routine;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($class_routine);
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
			$this->Page_Terminate("class_routinelist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in class_routine class, class_routineinfo.php

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
				$this->Page_Terminate("class_routinelist.php"); // Return to list
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
		$this->class_routine_id->setDbValue($row['class_routine_id']);
		$this->class_id->setDbValue($row['class_id']);
		$this->subject_id->setDbValue($row['subject_id']);
		$this->time_start->setDbValue($row['time_start']);
		$this->time_end->setDbValue($row['time_end']);
		$this->time_start_min->setDbValue($row['time_start_min']);
		$this->time_end_min->setDbValue($row['time_end_min']);
		$this->day->setDbValue($row['day']);
	}

	// Return a row with default values
	function NewRow() {
		$row = array();
		$row['class_routine_id'] = NULL;
		$row['class_id'] = NULL;
		$row['subject_id'] = NULL;
		$row['time_start'] = NULL;
		$row['time_end'] = NULL;
		$row['time_start_min'] = NULL;
		$row['time_end_min'] = NULL;
		$row['day'] = NULL;
		return $row;
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF)
			return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->class_routine_id->DbValue = $row['class_routine_id'];
		$this->class_id->DbValue = $row['class_id'];
		$this->subject_id->DbValue = $row['subject_id'];
		$this->time_start->DbValue = $row['time_start'];
		$this->time_end->DbValue = $row['time_end'];
		$this->time_start_min->DbValue = $row['time_start_min'];
		$this->time_end_min->DbValue = $row['time_end_min'];
		$this->day->DbValue = $row['day'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// class_routine_id
		// class_id
		// subject_id
		// time_start
		// time_end
		// time_start_min
		// time_end_min
		// day

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// class_routine_id
		$this->class_routine_id->ViewValue = $this->class_routine_id->CurrentValue;
		$this->class_routine_id->ViewCustomAttributes = "";

		// class_id
		$this->class_id->ViewValue = $this->class_id->CurrentValue;
		$this->class_id->ViewCustomAttributes = "";

		// subject_id
		$this->subject_id->ViewValue = $this->subject_id->CurrentValue;
		$this->subject_id->ViewCustomAttributes = "";

		// time_start
		$this->time_start->ViewValue = $this->time_start->CurrentValue;
		$this->time_start->ViewCustomAttributes = "";

		// time_end
		$this->time_end->ViewValue = $this->time_end->CurrentValue;
		$this->time_end->ViewCustomAttributes = "";

		// time_start_min
		$this->time_start_min->ViewValue = $this->time_start_min->CurrentValue;
		$this->time_start_min->ViewCustomAttributes = "";

		// time_end_min
		$this->time_end_min->ViewValue = $this->time_end_min->CurrentValue;
		$this->time_end_min->ViewCustomAttributes = "";

			// class_routine_id
			$this->class_routine_id->LinkCustomAttributes = "";
			$this->class_routine_id->HrefValue = "";
			$this->class_routine_id->TooltipValue = "";

			// class_id
			$this->class_id->LinkCustomAttributes = "";
			$this->class_id->HrefValue = "";
			$this->class_id->TooltipValue = "";

			// subject_id
			$this->subject_id->LinkCustomAttributes = "";
			$this->subject_id->HrefValue = "";
			$this->subject_id->TooltipValue = "";

			// time_start
			$this->time_start->LinkCustomAttributes = "";
			$this->time_start->HrefValue = "";
			$this->time_start->TooltipValue = "";

			// time_end
			$this->time_end->LinkCustomAttributes = "";
			$this->time_end->HrefValue = "";
			$this->time_end->TooltipValue = "";

			// time_start_min
			$this->time_start_min->LinkCustomAttributes = "";
			$this->time_start_min->HrefValue = "";
			$this->time_start_min->TooltipValue = "";

			// time_end_min
			$this->time_end_min->LinkCustomAttributes = "";
			$this->time_end_min->HrefValue = "";
			$this->time_end_min->TooltipValue = "";
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
				$sThisKey .= $row['class_routine_id'];
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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("class_routinelist.php"), "", $this->TableVar, TRUE);
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
if (!isset($class_routine_delete)) $class_routine_delete = new cclass_routine_delete();

// Page init
$class_routine_delete->Page_Init();

// Page main
$class_routine_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$class_routine_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "delete";
var CurrentForm = fclass_routinedelete = new ew_Form("fclass_routinedelete", "delete");

// Form_CustomValidate event
fclass_routinedelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fclass_routinedelete.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $class_routine_delete->ShowPageHeader(); ?>
<?php
$class_routine_delete->ShowMessage();
?>
<form name="fclass_routinedelete" id="fclass_routinedelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($class_routine_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $class_routine_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="class_routine">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($class_routine_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="box ewBox ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { ?>table-responsive <?php } ?>ewGridMiddlePanel">
<table class="table ewTable">
	<thead>
	<tr class="ewTableHeader">
<?php if ($class_routine->class_routine_id->Visible) { // class_routine_id ?>
		<th class="<?php echo $class_routine->class_routine_id->HeaderCellClass() ?>"><span id="elh_class_routine_class_routine_id" class="class_routine_class_routine_id"><?php echo $class_routine->class_routine_id->FldCaption() ?></span></th>
<?php } ?>
<?php if ($class_routine->class_id->Visible) { // class_id ?>
		<th class="<?php echo $class_routine->class_id->HeaderCellClass() ?>"><span id="elh_class_routine_class_id" class="class_routine_class_id"><?php echo $class_routine->class_id->FldCaption() ?></span></th>
<?php } ?>
<?php if ($class_routine->subject_id->Visible) { // subject_id ?>
		<th class="<?php echo $class_routine->subject_id->HeaderCellClass() ?>"><span id="elh_class_routine_subject_id" class="class_routine_subject_id"><?php echo $class_routine->subject_id->FldCaption() ?></span></th>
<?php } ?>
<?php if ($class_routine->time_start->Visible) { // time_start ?>
		<th class="<?php echo $class_routine->time_start->HeaderCellClass() ?>"><span id="elh_class_routine_time_start" class="class_routine_time_start"><?php echo $class_routine->time_start->FldCaption() ?></span></th>
<?php } ?>
<?php if ($class_routine->time_end->Visible) { // time_end ?>
		<th class="<?php echo $class_routine->time_end->HeaderCellClass() ?>"><span id="elh_class_routine_time_end" class="class_routine_time_end"><?php echo $class_routine->time_end->FldCaption() ?></span></th>
<?php } ?>
<?php if ($class_routine->time_start_min->Visible) { // time_start_min ?>
		<th class="<?php echo $class_routine->time_start_min->HeaderCellClass() ?>"><span id="elh_class_routine_time_start_min" class="class_routine_time_start_min"><?php echo $class_routine->time_start_min->FldCaption() ?></span></th>
<?php } ?>
<?php if ($class_routine->time_end_min->Visible) { // time_end_min ?>
		<th class="<?php echo $class_routine->time_end_min->HeaderCellClass() ?>"><span id="elh_class_routine_time_end_min" class="class_routine_time_end_min"><?php echo $class_routine->time_end_min->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$class_routine_delete->RecCnt = 0;
$i = 0;
while (!$class_routine_delete->Recordset->EOF) {
	$class_routine_delete->RecCnt++;
	$class_routine_delete->RowCnt++;

	// Set row properties
	$class_routine->ResetAttrs();
	$class_routine->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$class_routine_delete->LoadRowValues($class_routine_delete->Recordset);

	// Render row
	$class_routine_delete->RenderRow();
?>
	<tr<?php echo $class_routine->RowAttributes() ?>>
<?php if ($class_routine->class_routine_id->Visible) { // class_routine_id ?>
		<td<?php echo $class_routine->class_routine_id->CellAttributes() ?>>
<span id="el<?php echo $class_routine_delete->RowCnt ?>_class_routine_class_routine_id" class="class_routine_class_routine_id">
<span<?php echo $class_routine->class_routine_id->ViewAttributes() ?>>
<?php echo $class_routine->class_routine_id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($class_routine->class_id->Visible) { // class_id ?>
		<td<?php echo $class_routine->class_id->CellAttributes() ?>>
<span id="el<?php echo $class_routine_delete->RowCnt ?>_class_routine_class_id" class="class_routine_class_id">
<span<?php echo $class_routine->class_id->ViewAttributes() ?>>
<?php echo $class_routine->class_id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($class_routine->subject_id->Visible) { // subject_id ?>
		<td<?php echo $class_routine->subject_id->CellAttributes() ?>>
<span id="el<?php echo $class_routine_delete->RowCnt ?>_class_routine_subject_id" class="class_routine_subject_id">
<span<?php echo $class_routine->subject_id->ViewAttributes() ?>>
<?php echo $class_routine->subject_id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($class_routine->time_start->Visible) { // time_start ?>
		<td<?php echo $class_routine->time_start->CellAttributes() ?>>
<span id="el<?php echo $class_routine_delete->RowCnt ?>_class_routine_time_start" class="class_routine_time_start">
<span<?php echo $class_routine->time_start->ViewAttributes() ?>>
<?php echo $class_routine->time_start->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($class_routine->time_end->Visible) { // time_end ?>
		<td<?php echo $class_routine->time_end->CellAttributes() ?>>
<span id="el<?php echo $class_routine_delete->RowCnt ?>_class_routine_time_end" class="class_routine_time_end">
<span<?php echo $class_routine->time_end->ViewAttributes() ?>>
<?php echo $class_routine->time_end->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($class_routine->time_start_min->Visible) { // time_start_min ?>
		<td<?php echo $class_routine->time_start_min->CellAttributes() ?>>
<span id="el<?php echo $class_routine_delete->RowCnt ?>_class_routine_time_start_min" class="class_routine_time_start_min">
<span<?php echo $class_routine->time_start_min->ViewAttributes() ?>>
<?php echo $class_routine->time_start_min->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($class_routine->time_end_min->Visible) { // time_end_min ?>
		<td<?php echo $class_routine->time_end_min->CellAttributes() ?>>
<span id="el<?php echo $class_routine_delete->RowCnt ?>_class_routine_time_end_min" class="class_routine_time_end_min">
<span<?php echo $class_routine->time_end_min->ViewAttributes() ?>>
<?php echo $class_routine->time_end_min->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$class_routine_delete->Recordset->MoveNext();
}
$class_routine_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</div>
<div>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $class_routine_delete->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
</div>
</form>
<script type="text/javascript">
fclass_routinedelete.Init();
</script>
<?php
$class_routine_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$class_routine_delete->Page_Terminate();
?>
