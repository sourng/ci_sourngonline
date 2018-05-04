<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "lessonsinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$lessons_delete = NULL; // Initialize page object first

class clessons_delete extends clessons {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = '{1F40EE59-17A4-45D3-96B6-AA2082B3D64A}';

	// Table name
	var $TableName = 'lessons';

	// Page object name
	var $PageObjName = 'lessons_delete';

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

		// Table object (lessons)
		if (!isset($GLOBALS["lessons"]) || get_class($GLOBALS["lessons"]) == "clessons") {
			$GLOBALS["lessons"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["lessons"];
		}

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'lessons', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("lessonslist.php"));
			else
				$this->Page_Terminate(ew_GetUrl("login.php"));
		}

		// NOTE: Security object may be needed in other part of the script, skip set to Nothing
		// 
		// Security = null;
		// 

		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->lesson_id->SetVisibility();
		if ($this->IsAdd() || $this->IsCopy() || $this->IsGridAdd())
			$this->lesson_id->Visible = FALSE;
		$this->title->SetVisibility();
		$this->lesson_no->SetVisibility();
		$this->description->SetVisibility();
		$this->video->SetVisibility();
		$this->order->SetVisibility();
		$this->subject_id->SetVisibility();
		$this->teacher_id->SetVisibility();

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
		global $EW_EXPORT, $lessons;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($lessons);
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
			$this->Page_Terminate("lessonslist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in lessons class, lessonsinfo.php

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
				$this->Page_Terminate("lessonslist.php"); // Return to list
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
		$this->lesson_id->setDbValue($row['lesson_id']);
		$this->title->setDbValue($row['title']);
		$this->lesson_no->setDbValue($row['lesson_no']);
		$this->description->setDbValue($row['description']);
		$this->detail->setDbValue($row['detail']);
		$this->image->setDbValue($row['image']);
		$this->video->setDbValue($row['video']);
		$this->order->setDbValue($row['order']);
		$this->subject_id->setDbValue($row['subject_id']);
		$this->teacher_id->setDbValue($row['teacher_id']);
	}

	// Return a row with default values
	function NewRow() {
		$row = array();
		$row['lesson_id'] = NULL;
		$row['title'] = NULL;
		$row['lesson_no'] = NULL;
		$row['description'] = NULL;
		$row['detail'] = NULL;
		$row['image'] = NULL;
		$row['video'] = NULL;
		$row['order'] = NULL;
		$row['subject_id'] = NULL;
		$row['teacher_id'] = NULL;
		return $row;
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF)
			return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->lesson_id->DbValue = $row['lesson_id'];
		$this->title->DbValue = $row['title'];
		$this->lesson_no->DbValue = $row['lesson_no'];
		$this->description->DbValue = $row['description'];
		$this->detail->DbValue = $row['detail'];
		$this->image->DbValue = $row['image'];
		$this->video->DbValue = $row['video'];
		$this->order->DbValue = $row['order'];
		$this->subject_id->DbValue = $row['subject_id'];
		$this->teacher_id->DbValue = $row['teacher_id'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// lesson_id
		// title
		// lesson_no
		// description
		// detail
		// image
		// video
		// order
		// subject_id
		// teacher_id

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// lesson_id
		$this->lesson_id->ViewValue = $this->lesson_id->CurrentValue;
		$this->lesson_id->ViewCustomAttributes = "";

		// title
		$this->title->ViewValue = $this->title->CurrentValue;
		$this->title->ViewCustomAttributes = "";

		// lesson_no
		$this->lesson_no->ViewValue = $this->lesson_no->CurrentValue;
		$this->lesson_no->ViewCustomAttributes = "";

		// description
		$this->description->ViewValue = $this->description->CurrentValue;
		$this->description->ViewCustomAttributes = "";

		// video
		$this->video->ViewValue = $this->video->CurrentValue;
		$this->video->ViewCustomAttributes = "";

		// order
		$this->order->ViewValue = $this->order->CurrentValue;
		$this->order->ViewCustomAttributes = "";

		// subject_id
		$this->subject_id->ViewValue = $this->subject_id->CurrentValue;
		$this->subject_id->ViewCustomAttributes = "";

		// teacher_id
		$this->teacher_id->ViewValue = $this->teacher_id->CurrentValue;
		$this->teacher_id->ViewCustomAttributes = "";

			// lesson_id
			$this->lesson_id->LinkCustomAttributes = "";
			$this->lesson_id->HrefValue = "";
			$this->lesson_id->TooltipValue = "";

			// title
			$this->title->LinkCustomAttributes = "";
			$this->title->HrefValue = "";
			$this->title->TooltipValue = "";

			// lesson_no
			$this->lesson_no->LinkCustomAttributes = "";
			$this->lesson_no->HrefValue = "";
			$this->lesson_no->TooltipValue = "";

			// description
			$this->description->LinkCustomAttributes = "";
			$this->description->HrefValue = "";
			$this->description->TooltipValue = "";

			// video
			$this->video->LinkCustomAttributes = "";
			$this->video->HrefValue = "";
			$this->video->TooltipValue = "";

			// order
			$this->order->LinkCustomAttributes = "";
			$this->order->HrefValue = "";
			$this->order->TooltipValue = "";

			// subject_id
			$this->subject_id->LinkCustomAttributes = "";
			$this->subject_id->HrefValue = "";
			$this->subject_id->TooltipValue = "";

			// teacher_id
			$this->teacher_id->LinkCustomAttributes = "";
			$this->teacher_id->HrefValue = "";
			$this->teacher_id->TooltipValue = "";
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
				$sThisKey .= $row['lesson_id'];
				if ($sThisKey <> "") $sThisKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
				$sThisKey .= $row['subject_id'];
				if ($sThisKey <> "") $sThisKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
				$sThisKey .= $row['teacher_id'];
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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("lessonslist.php"), "", $this->TableVar, TRUE);
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
if (!isset($lessons_delete)) $lessons_delete = new clessons_delete();

// Page init
$lessons_delete->Page_Init();

// Page main
$lessons_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$lessons_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "delete";
var CurrentForm = flessonsdelete = new ew_Form("flessonsdelete", "delete");

// Form_CustomValidate event
flessonsdelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
flessonsdelete.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $lessons_delete->ShowPageHeader(); ?>
<?php
$lessons_delete->ShowMessage();
?>
<form name="flessonsdelete" id="flessonsdelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($lessons_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $lessons_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="lessons">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($lessons_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="box ewBox ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { ?>table-responsive <?php } ?>ewGridMiddlePanel">
<table class="table ewTable">
	<thead>
	<tr class="ewTableHeader">
<?php if ($lessons->lesson_id->Visible) { // lesson_id ?>
		<th class="<?php echo $lessons->lesson_id->HeaderCellClass() ?>"><span id="elh_lessons_lesson_id" class="lessons_lesson_id"><?php echo $lessons->lesson_id->FldCaption() ?></span></th>
<?php } ?>
<?php if ($lessons->title->Visible) { // title ?>
		<th class="<?php echo $lessons->title->HeaderCellClass() ?>"><span id="elh_lessons_title" class="lessons_title"><?php echo $lessons->title->FldCaption() ?></span></th>
<?php } ?>
<?php if ($lessons->lesson_no->Visible) { // lesson_no ?>
		<th class="<?php echo $lessons->lesson_no->HeaderCellClass() ?>"><span id="elh_lessons_lesson_no" class="lessons_lesson_no"><?php echo $lessons->lesson_no->FldCaption() ?></span></th>
<?php } ?>
<?php if ($lessons->description->Visible) { // description ?>
		<th class="<?php echo $lessons->description->HeaderCellClass() ?>"><span id="elh_lessons_description" class="lessons_description"><?php echo $lessons->description->FldCaption() ?></span></th>
<?php } ?>
<?php if ($lessons->video->Visible) { // video ?>
		<th class="<?php echo $lessons->video->HeaderCellClass() ?>"><span id="elh_lessons_video" class="lessons_video"><?php echo $lessons->video->FldCaption() ?></span></th>
<?php } ?>
<?php if ($lessons->order->Visible) { // order ?>
		<th class="<?php echo $lessons->order->HeaderCellClass() ?>"><span id="elh_lessons_order" class="lessons_order"><?php echo $lessons->order->FldCaption() ?></span></th>
<?php } ?>
<?php if ($lessons->subject_id->Visible) { // subject_id ?>
		<th class="<?php echo $lessons->subject_id->HeaderCellClass() ?>"><span id="elh_lessons_subject_id" class="lessons_subject_id"><?php echo $lessons->subject_id->FldCaption() ?></span></th>
<?php } ?>
<?php if ($lessons->teacher_id->Visible) { // teacher_id ?>
		<th class="<?php echo $lessons->teacher_id->HeaderCellClass() ?>"><span id="elh_lessons_teacher_id" class="lessons_teacher_id"><?php echo $lessons->teacher_id->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$lessons_delete->RecCnt = 0;
$i = 0;
while (!$lessons_delete->Recordset->EOF) {
	$lessons_delete->RecCnt++;
	$lessons_delete->RowCnt++;

	// Set row properties
	$lessons->ResetAttrs();
	$lessons->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$lessons_delete->LoadRowValues($lessons_delete->Recordset);

	// Render row
	$lessons_delete->RenderRow();
?>
	<tr<?php echo $lessons->RowAttributes() ?>>
<?php if ($lessons->lesson_id->Visible) { // lesson_id ?>
		<td<?php echo $lessons->lesson_id->CellAttributes() ?>>
<span id="el<?php echo $lessons_delete->RowCnt ?>_lessons_lesson_id" class="lessons_lesson_id">
<span<?php echo $lessons->lesson_id->ViewAttributes() ?>>
<?php echo $lessons->lesson_id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($lessons->title->Visible) { // title ?>
		<td<?php echo $lessons->title->CellAttributes() ?>>
<span id="el<?php echo $lessons_delete->RowCnt ?>_lessons_title" class="lessons_title">
<span<?php echo $lessons->title->ViewAttributes() ?>>
<?php echo $lessons->title->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($lessons->lesson_no->Visible) { // lesson_no ?>
		<td<?php echo $lessons->lesson_no->CellAttributes() ?>>
<span id="el<?php echo $lessons_delete->RowCnt ?>_lessons_lesson_no" class="lessons_lesson_no">
<span<?php echo $lessons->lesson_no->ViewAttributes() ?>>
<?php echo $lessons->lesson_no->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($lessons->description->Visible) { // description ?>
		<td<?php echo $lessons->description->CellAttributes() ?>>
<span id="el<?php echo $lessons_delete->RowCnt ?>_lessons_description" class="lessons_description">
<span<?php echo $lessons->description->ViewAttributes() ?>>
<?php echo $lessons->description->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($lessons->video->Visible) { // video ?>
		<td<?php echo $lessons->video->CellAttributes() ?>>
<span id="el<?php echo $lessons_delete->RowCnt ?>_lessons_video" class="lessons_video">
<span<?php echo $lessons->video->ViewAttributes() ?>>
<?php echo $lessons->video->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($lessons->order->Visible) { // order ?>
		<td<?php echo $lessons->order->CellAttributes() ?>>
<span id="el<?php echo $lessons_delete->RowCnt ?>_lessons_order" class="lessons_order">
<span<?php echo $lessons->order->ViewAttributes() ?>>
<?php echo $lessons->order->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($lessons->subject_id->Visible) { // subject_id ?>
		<td<?php echo $lessons->subject_id->CellAttributes() ?>>
<span id="el<?php echo $lessons_delete->RowCnt ?>_lessons_subject_id" class="lessons_subject_id">
<span<?php echo $lessons->subject_id->ViewAttributes() ?>>
<?php echo $lessons->subject_id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($lessons->teacher_id->Visible) { // teacher_id ?>
		<td<?php echo $lessons->teacher_id->CellAttributes() ?>>
<span id="el<?php echo $lessons_delete->RowCnt ?>_lessons_teacher_id" class="lessons_teacher_id">
<span<?php echo $lessons->teacher_id->ViewAttributes() ?>>
<?php echo $lessons->teacher_id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$lessons_delete->Recordset->MoveNext();
}
$lessons_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</div>
<div>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $lessons_delete->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
</div>
</form>
<script type="text/javascript">
flessonsdelete.Init();
</script>
<?php
$lessons_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$lessons_delete->Page_Terminate();
?>
