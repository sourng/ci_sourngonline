<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "sectioninfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$section_addopt = NULL; // Initialize page object first

class csection_addopt extends csection {

	// Page ID
	var $PageID = 'addopt';

	// Project ID
	var $ProjectID = '{1F40EE59-17A4-45D3-96B6-AA2082B3D64A}';

	// Table name
	var $TableName = 'section';

	// Page object name
	var $PageObjName = 'section_addopt';

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

		// Table object (section)
		if (!isset($GLOBALS["section"]) || get_class($GLOBALS["section"]) == "csection") {
			$GLOBALS["section"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["section"];
		}

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'addopt', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'section', TRUE);

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
		if (!$Security->CanAdd()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage(ew_DeniedMsg()); // Set no permission
			if ($Security->CanList())
				$this->Page_Terminate(ew_GetUrl("sectionlist.php"));
			else
				$this->Page_Terminate(ew_GetUrl("login.php"));
		}

		// NOTE: Security object may be needed in other part of the script, skip set to Nothing
		// 
		// Security = null;
		// 
		// Create form object

		$objForm = new cFormObj();
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->name->SetVisibility();
		$this->nick_name->SetVisibility();
		$this->class_id->SetVisibility();
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

		// Process auto fill
		if (@$_POST["ajax"] == "autofill") {
			$results = $this->GetAutoFill(@$_POST["name"], @$_POST["q"]);
			if ($results) {

				// Clean output buffer
				if (!EW_DEBUG_ENABLED && ob_get_length())
					ob_end_clean();
				echo $results;
				$this->Page_Terminate();
				exit();
			}
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
		global $EW_EXPORT, $section;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($section);
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

	//
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;
		set_error_handler("ew_ErrorHandler");

		// Set up Breadcrumb
		//$this->SetupBreadcrumb(); // Not used

		$this->LoadRowValues(); // Load default values

		// Process form if post back
		if ($objForm->GetValue("a_addopt") <> "") {
			$this->CurrentAction = $objForm->GetValue("a_addopt"); // Get form action
			$this->LoadFormValues(); // Load form values

			// Validate form
			if (!$this->ValidateForm()) {
				$this->CurrentAction = "I"; // Form error, reset action
				$this->setFailureMessage($gsFormError);
			}
		} else { // Not post back
			$this->CurrentAction = "I"; // Display blank record
		}

		// Perform action based on action code
		switch ($this->CurrentAction) {
			case "I": // Blank record, no action required
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow()) { // Add successful
					$row = array();
					$row["x_section_id"] = $this->section_id->DbValue;
					$row["x_name"] = ew_ConvertToUtf8($this->name->DbValue);
					$row["x_nick_name"] = ew_ConvertToUtf8($this->nick_name->DbValue);
					$row["x_class_id"] = $this->class_id->DbValue;
					$row["x_teacher_id"] = $this->teacher_id->DbValue;
					if (!EW_DEBUG_ENABLED && ob_get_length())
						ob_end_clean();
					ew_Header(FALSE, "utf-8", TRUE);
					echo ew_ArrayToJson(array($row));
				} else {
					$this->ShowMessage();
				}
				$this->Page_Terminate();
				exit();
		}

		// Render row
		$this->RowType = EW_ROWTYPE_ADD; // Render add type
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Get upload files
	function GetUploadFiles() {
		global $objForm, $Language;

		// Get upload data
	}

	// Load default values
	function LoadDefaultValues() {
		$this->section_id->CurrentValue = NULL;
		$this->section_id->OldValue = $this->section_id->CurrentValue;
		$this->name->CurrentValue = NULL;
		$this->name->OldValue = $this->name->CurrentValue;
		$this->nick_name->CurrentValue = NULL;
		$this->nick_name->OldValue = $this->nick_name->CurrentValue;
		$this->class_id->CurrentValue = NULL;
		$this->class_id->OldValue = $this->class_id->CurrentValue;
		$this->teacher_id->CurrentValue = NULL;
		$this->teacher_id->OldValue = $this->teacher_id->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->name->FldIsDetailKey) {
			$this->name->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_name")));
		}
		if (!$this->nick_name->FldIsDetailKey) {
			$this->nick_name->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_nick_name")));
		}
		if (!$this->class_id->FldIsDetailKey) {
			$this->class_id->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_class_id")));
		}
		if (!$this->teacher_id->FldIsDetailKey) {
			$this->teacher_id->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_teacher_id")));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->name->CurrentValue = ew_ConvertToUtf8($this->name->FormValue);
		$this->nick_name->CurrentValue = ew_ConvertToUtf8($this->nick_name->FormValue);
		$this->class_id->CurrentValue = ew_ConvertToUtf8($this->class_id->FormValue);
		$this->teacher_id->CurrentValue = ew_ConvertToUtf8($this->teacher_id->FormValue);
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
		$this->section_id->setDbValue($row['section_id']);
		$this->name->setDbValue($row['name']);
		$this->nick_name->setDbValue($row['nick_name']);
		$this->class_id->setDbValue($row['class_id']);
		$this->teacher_id->setDbValue($row['teacher_id']);
	}

	// Return a row with default values
	function NewRow() {
		$this->LoadDefaultValues();
		$row = array();
		$row['section_id'] = $this->section_id->CurrentValue;
		$row['name'] = $this->name->CurrentValue;
		$row['nick_name'] = $this->nick_name->CurrentValue;
		$row['class_id'] = $this->class_id->CurrentValue;
		$row['teacher_id'] = $this->teacher_id->CurrentValue;
		return $row;
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF)
			return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->section_id->DbValue = $row['section_id'];
		$this->name->DbValue = $row['name'];
		$this->nick_name->DbValue = $row['nick_name'];
		$this->class_id->DbValue = $row['class_id'];
		$this->teacher_id->DbValue = $row['teacher_id'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// section_id
		// name
		// nick_name
		// class_id
		// teacher_id

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// section_id
		$this->section_id->ViewValue = $this->section_id->CurrentValue;
		$this->section_id->ViewCustomAttributes = "";

		// name
		$this->name->ViewValue = $this->name->CurrentValue;
		$this->name->ViewCustomAttributes = "";

		// nick_name
		$this->nick_name->ViewValue = $this->nick_name->CurrentValue;
		$this->nick_name->ViewCustomAttributes = "";

		// class_id
		$this->class_id->ViewValue = $this->class_id->CurrentValue;
		$this->class_id->ViewCustomAttributes = "";

		// teacher_id
		$this->teacher_id->ViewValue = $this->teacher_id->CurrentValue;
		$this->teacher_id->ViewCustomAttributes = "";

			// name
			$this->name->LinkCustomAttributes = "";
			$this->name->HrefValue = "";
			$this->name->TooltipValue = "";

			// nick_name
			$this->nick_name->LinkCustomAttributes = "";
			$this->nick_name->HrefValue = "";
			$this->nick_name->TooltipValue = "";

			// class_id
			$this->class_id->LinkCustomAttributes = "";
			$this->class_id->HrefValue = "";
			$this->class_id->TooltipValue = "";

			// teacher_id
			$this->teacher_id->LinkCustomAttributes = "";
			$this->teacher_id->HrefValue = "";
			$this->teacher_id->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// name
			$this->name->EditAttrs["class"] = "form-control";
			$this->name->EditCustomAttributes = "";
			$this->name->EditValue = ew_HtmlEncode($this->name->CurrentValue);
			$this->name->PlaceHolder = ew_RemoveHtml($this->name->FldCaption());

			// nick_name
			$this->nick_name->EditAttrs["class"] = "form-control";
			$this->nick_name->EditCustomAttributes = "";
			$this->nick_name->EditValue = ew_HtmlEncode($this->nick_name->CurrentValue);
			$this->nick_name->PlaceHolder = ew_RemoveHtml($this->nick_name->FldCaption());

			// class_id
			$this->class_id->EditAttrs["class"] = "form-control";
			$this->class_id->EditCustomAttributes = "";
			$this->class_id->EditValue = ew_HtmlEncode($this->class_id->CurrentValue);
			$this->class_id->PlaceHolder = ew_RemoveHtml($this->class_id->FldCaption());

			// teacher_id
			$this->teacher_id->EditAttrs["class"] = "form-control";
			$this->teacher_id->EditCustomAttributes = "";
			$this->teacher_id->EditValue = ew_HtmlEncode($this->teacher_id->CurrentValue);
			$this->teacher_id->PlaceHolder = ew_RemoveHtml($this->teacher_id->FldCaption());

			// Add refer script
			// name

			$this->name->LinkCustomAttributes = "";
			$this->name->HrefValue = "";

			// nick_name
			$this->nick_name->LinkCustomAttributes = "";
			$this->nick_name->HrefValue = "";

			// class_id
			$this->class_id->LinkCustomAttributes = "";
			$this->class_id->HrefValue = "";

			// teacher_id
			$this->teacher_id->LinkCustomAttributes = "";
			$this->teacher_id->HrefValue = "";
		}
		if ($this->RowType == EW_ROWTYPE_ADD || $this->RowType == EW_ROWTYPE_EDIT || $this->RowType == EW_ROWTYPE_SEARCH) // Add/Edit/Search row
			$this->SetupFieldTitles();

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Validate form
	function ValidateForm() {
		global $Language, $gsFormError;

		// Initialize form error message
		$gsFormError = "";

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return ($gsFormError == "");
		if (!$this->name->FldIsDetailKey && !is_null($this->name->FormValue) && $this->name->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->name->FldCaption(), $this->name->ReqErrMsg));
		}
		if (!$this->nick_name->FldIsDetailKey && !is_null($this->nick_name->FormValue) && $this->nick_name->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->nick_name->FldCaption(), $this->nick_name->ReqErrMsg));
		}
		if (!$this->class_id->FldIsDetailKey && !is_null($this->class_id->FormValue) && $this->class_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->class_id->FldCaption(), $this->class_id->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->class_id->FormValue)) {
			ew_AddMessage($gsFormError, $this->class_id->FldErrMsg());
		}
		if (!$this->teacher_id->FldIsDetailKey && !is_null($this->teacher_id->FormValue) && $this->teacher_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->teacher_id->FldCaption(), $this->teacher_id->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->teacher_id->FormValue)) {
			ew_AddMessage($gsFormError, $this->teacher_id->FldErrMsg());
		}

		// Return validate result
		$ValidateForm = ($gsFormError == "");

		// Call Form_CustomValidate event
		$sFormCustomError = "";
		$ValidateForm = $ValidateForm && $this->Form_CustomValidate($sFormCustomError);
		if ($sFormCustomError <> "") {
			ew_AddMessage($gsFormError, $sFormCustomError);
		}
		return $ValidateForm;
	}

	// Add record
	function AddRow($rsold = NULL) {
		global $Language, $Security;
		$conn = &$this->Connection();

		// Load db values from rsold
		$this->LoadDbValues($rsold);
		if ($rsold) {
		}
		$rsnew = array();

		// name
		$this->name->SetDbValueDef($rsnew, $this->name->CurrentValue, "", FALSE);

		// nick_name
		$this->nick_name->SetDbValueDef($rsnew, $this->nick_name->CurrentValue, "", FALSE);

		// class_id
		$this->class_id->SetDbValueDef($rsnew, $this->class_id->CurrentValue, 0, FALSE);

		// teacher_id
		$this->teacher_id->SetDbValueDef($rsnew, $this->teacher_id->CurrentValue, 0, FALSE);

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);
		if ($bInsertRow) {
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			$AddRow = $this->Insert($rsnew);
			$conn->raiseErrorFn = '';
			if ($AddRow) {
			}
		} else {
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("InsertCancelled"));
			}
			$AddRow = FALSE;
		}
		if ($AddRow) {

			// Call Row Inserted event
			$rs = ($rsold == NULL) ? NULL : $rsold->fields;
			$this->Row_Inserted($rs, $rsnew);
		}
		return $AddRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("sectionlist.php"), "", $this->TableVar, TRUE);
		$PageId = "addopt";
		$Breadcrumb->Add("addopt", $PageId, $url);
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

	// Custom validate event
	// Form Custom Validate event
	function Form_CustomValidate(&$CustomError) {

		// Return error message in CustomError
		return TRUE;
	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($section_addopt)) $section_addopt = new csection_addopt();

// Page init
$section_addopt->Page_Init();

// Page main
$section_addopt->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$section_addopt->Page_Render();
?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "addopt";
var CurrentForm = fsectionaddopt = new ew_Form("fsectionaddopt", "addopt");

// Validate form
fsectionaddopt.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_name");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $section->name->FldCaption(), $section->name->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_nick_name");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $section->nick_name->FldCaption(), $section->nick_name->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_class_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $section->class_id->FldCaption(), $section->class_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_class_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($section->class_id->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_teacher_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $section->teacher_id->FldCaption(), $section->teacher_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_teacher_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($section->teacher_id->FldErrMsg()) ?>");

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
	}
	return true;
}

// Form_CustomValidate event
fsectionaddopt.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fsectionaddopt.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php
$section_addopt->ShowMessage();
?>
<form name="fsectionaddopt" id="fsectionaddopt" class="ewForm form-horizontal" action="sectionaddopt.php" method="post">
<?php if ($section_addopt->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $section_addopt->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="section">
<input type="hidden" name="a_addopt" id="a_addopt" value="A">
<?php if ($section->name->Visible) { // name ?>
	<div class="form-group">
		<label class="col-sm-2 control-label ewLabel" for="x_name"><?php echo $section->name->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10">
<textarea data-table="section" data-field="x_name" name="x_name" id="x_name" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($section->name->getPlaceHolder()) ?>"<?php echo $section->name->EditAttributes() ?>><?php echo $section->name->EditValue ?></textarea>
</div>
	</div>
<?php } ?>
<?php if ($section->nick_name->Visible) { // nick_name ?>
	<div class="form-group">
		<label class="col-sm-2 control-label ewLabel" for="x_nick_name"><?php echo $section->nick_name->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10">
<textarea data-table="section" data-field="x_nick_name" name="x_nick_name" id="x_nick_name" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($section->nick_name->getPlaceHolder()) ?>"<?php echo $section->nick_name->EditAttributes() ?>><?php echo $section->nick_name->EditValue ?></textarea>
</div>
	</div>
<?php } ?>
<?php if ($section->class_id->Visible) { // class_id ?>
	<div class="form-group">
		<label class="col-sm-2 control-label ewLabel" for="x_class_id"><?php echo $section->class_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10">
<input type="text" data-table="section" data-field="x_class_id" name="x_class_id" id="x_class_id" size="30" placeholder="<?php echo ew_HtmlEncode($section->class_id->getPlaceHolder()) ?>" value="<?php echo $section->class_id->EditValue ?>"<?php echo $section->class_id->EditAttributes() ?>>
</div>
	</div>
<?php } ?>
<?php if ($section->teacher_id->Visible) { // teacher_id ?>
	<div class="form-group">
		<label class="col-sm-2 control-label ewLabel" for="x_teacher_id"><?php echo $section->teacher_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10">
<input type="text" data-table="section" data-field="x_teacher_id" name="x_teacher_id" id="x_teacher_id" size="30" placeholder="<?php echo ew_HtmlEncode($section->teacher_id->getPlaceHolder()) ?>" value="<?php echo $section->teacher_id->EditValue ?>"<?php echo $section->teacher_id->EditAttributes() ?>>
</div>
	</div>
<?php } ?>
</form>
<script type="text/javascript">
fsectionaddopt.Init();
</script>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php
$section_addopt->Page_Terminate();
?>
