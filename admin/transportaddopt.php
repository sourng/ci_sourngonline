<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "transportinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$transport_addopt = NULL; // Initialize page object first

class ctransport_addopt extends ctransport {

	// Page ID
	var $PageID = 'addopt';

	// Project ID
	var $ProjectID = '{1F40EE59-17A4-45D3-96B6-AA2082B3D64A}';

	// Table name
	var $TableName = 'transport';

	// Page object name
	var $PageObjName = 'transport_addopt';

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

		// Table object (transport)
		if (!isset($GLOBALS["transport"]) || get_class($GLOBALS["transport"]) == "ctransport") {
			$GLOBALS["transport"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["transport"];
		}

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'addopt', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'transport', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("transportlist.php"));
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
		$this->route_name->SetVisibility();
		$this->number_of_vehicle->SetVisibility();
		$this->description->SetVisibility();
		$this->route_fare->SetVisibility();

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
		global $EW_EXPORT, $transport;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($transport);
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
					$row["x_transport_id"] = $this->transport_id->DbValue;
					$row["x_route_name"] = ew_ConvertToUtf8($this->route_name->DbValue);
					$row["x_number_of_vehicle"] = ew_ConvertToUtf8($this->number_of_vehicle->DbValue);
					$row["x_description"] = ew_ConvertToUtf8($this->description->DbValue);
					$row["x_route_fare"] = ew_ConvertToUtf8($this->route_fare->DbValue);
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
		$this->transport_id->CurrentValue = NULL;
		$this->transport_id->OldValue = $this->transport_id->CurrentValue;
		$this->route_name->CurrentValue = NULL;
		$this->route_name->OldValue = $this->route_name->CurrentValue;
		$this->number_of_vehicle->CurrentValue = NULL;
		$this->number_of_vehicle->OldValue = $this->number_of_vehicle->CurrentValue;
		$this->description->CurrentValue = NULL;
		$this->description->OldValue = $this->description->CurrentValue;
		$this->route_fare->CurrentValue = NULL;
		$this->route_fare->OldValue = $this->route_fare->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->route_name->FldIsDetailKey) {
			$this->route_name->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_route_name")));
		}
		if (!$this->number_of_vehicle->FldIsDetailKey) {
			$this->number_of_vehicle->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_number_of_vehicle")));
		}
		if (!$this->description->FldIsDetailKey) {
			$this->description->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_description")));
		}
		if (!$this->route_fare->FldIsDetailKey) {
			$this->route_fare->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_route_fare")));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->route_name->CurrentValue = ew_ConvertToUtf8($this->route_name->FormValue);
		$this->number_of_vehicle->CurrentValue = ew_ConvertToUtf8($this->number_of_vehicle->FormValue);
		$this->description->CurrentValue = ew_ConvertToUtf8($this->description->FormValue);
		$this->route_fare->CurrentValue = ew_ConvertToUtf8($this->route_fare->FormValue);
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
		$this->transport_id->setDbValue($row['transport_id']);
		$this->route_name->setDbValue($row['route_name']);
		$this->number_of_vehicle->setDbValue($row['number_of_vehicle']);
		$this->description->setDbValue($row['description']);
		$this->route_fare->setDbValue($row['route_fare']);
	}

	// Return a row with default values
	function NewRow() {
		$this->LoadDefaultValues();
		$row = array();
		$row['transport_id'] = $this->transport_id->CurrentValue;
		$row['route_name'] = $this->route_name->CurrentValue;
		$row['number_of_vehicle'] = $this->number_of_vehicle->CurrentValue;
		$row['description'] = $this->description->CurrentValue;
		$row['route_fare'] = $this->route_fare->CurrentValue;
		return $row;
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF)
			return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->transport_id->DbValue = $row['transport_id'];
		$this->route_name->DbValue = $row['route_name'];
		$this->number_of_vehicle->DbValue = $row['number_of_vehicle'];
		$this->description->DbValue = $row['description'];
		$this->route_fare->DbValue = $row['route_fare'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// transport_id
		// route_name
		// number_of_vehicle
		// description
		// route_fare

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// transport_id
		$this->transport_id->ViewValue = $this->transport_id->CurrentValue;
		$this->transport_id->ViewCustomAttributes = "";

		// route_name
		$this->route_name->ViewValue = $this->route_name->CurrentValue;
		$this->route_name->ViewCustomAttributes = "";

		// number_of_vehicle
		$this->number_of_vehicle->ViewValue = $this->number_of_vehicle->CurrentValue;
		$this->number_of_vehicle->ViewCustomAttributes = "";

		// description
		$this->description->ViewValue = $this->description->CurrentValue;
		$this->description->ViewCustomAttributes = "";

		// route_fare
		$this->route_fare->ViewValue = $this->route_fare->CurrentValue;
		$this->route_fare->ViewCustomAttributes = "";

			// route_name
			$this->route_name->LinkCustomAttributes = "";
			$this->route_name->HrefValue = "";
			$this->route_name->TooltipValue = "";

			// number_of_vehicle
			$this->number_of_vehicle->LinkCustomAttributes = "";
			$this->number_of_vehicle->HrefValue = "";
			$this->number_of_vehicle->TooltipValue = "";

			// description
			$this->description->LinkCustomAttributes = "";
			$this->description->HrefValue = "";
			$this->description->TooltipValue = "";

			// route_fare
			$this->route_fare->LinkCustomAttributes = "";
			$this->route_fare->HrefValue = "";
			$this->route_fare->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// route_name
			$this->route_name->EditAttrs["class"] = "form-control";
			$this->route_name->EditCustomAttributes = "";
			$this->route_name->EditValue = ew_HtmlEncode($this->route_name->CurrentValue);
			$this->route_name->PlaceHolder = ew_RemoveHtml($this->route_name->FldCaption());

			// number_of_vehicle
			$this->number_of_vehicle->EditAttrs["class"] = "form-control";
			$this->number_of_vehicle->EditCustomAttributes = "";
			$this->number_of_vehicle->EditValue = ew_HtmlEncode($this->number_of_vehicle->CurrentValue);
			$this->number_of_vehicle->PlaceHolder = ew_RemoveHtml($this->number_of_vehicle->FldCaption());

			// description
			$this->description->EditAttrs["class"] = "form-control";
			$this->description->EditCustomAttributes = "";
			$this->description->EditValue = ew_HtmlEncode($this->description->CurrentValue);
			$this->description->PlaceHolder = ew_RemoveHtml($this->description->FldCaption());

			// route_fare
			$this->route_fare->EditAttrs["class"] = "form-control";
			$this->route_fare->EditCustomAttributes = "";
			$this->route_fare->EditValue = ew_HtmlEncode($this->route_fare->CurrentValue);
			$this->route_fare->PlaceHolder = ew_RemoveHtml($this->route_fare->FldCaption());

			// Add refer script
			// route_name

			$this->route_name->LinkCustomAttributes = "";
			$this->route_name->HrefValue = "";

			// number_of_vehicle
			$this->number_of_vehicle->LinkCustomAttributes = "";
			$this->number_of_vehicle->HrefValue = "";

			// description
			$this->description->LinkCustomAttributes = "";
			$this->description->HrefValue = "";

			// route_fare
			$this->route_fare->LinkCustomAttributes = "";
			$this->route_fare->HrefValue = "";
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
		if (!$this->route_name->FldIsDetailKey && !is_null($this->route_name->FormValue) && $this->route_name->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->route_name->FldCaption(), $this->route_name->ReqErrMsg));
		}
		if (!$this->number_of_vehicle->FldIsDetailKey && !is_null($this->number_of_vehicle->FormValue) && $this->number_of_vehicle->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->number_of_vehicle->FldCaption(), $this->number_of_vehicle->ReqErrMsg));
		}
		if (!$this->description->FldIsDetailKey && !is_null($this->description->FormValue) && $this->description->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->description->FldCaption(), $this->description->ReqErrMsg));
		}
		if (!$this->route_fare->FldIsDetailKey && !is_null($this->route_fare->FormValue) && $this->route_fare->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->route_fare->FldCaption(), $this->route_fare->ReqErrMsg));
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

		// route_name
		$this->route_name->SetDbValueDef($rsnew, $this->route_name->CurrentValue, "", FALSE);

		// number_of_vehicle
		$this->number_of_vehicle->SetDbValueDef($rsnew, $this->number_of_vehicle->CurrentValue, "", FALSE);

		// description
		$this->description->SetDbValueDef($rsnew, $this->description->CurrentValue, "", FALSE);

		// route_fare
		$this->route_fare->SetDbValueDef($rsnew, $this->route_fare->CurrentValue, "", FALSE);

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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("transportlist.php"), "", $this->TableVar, TRUE);
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
if (!isset($transport_addopt)) $transport_addopt = new ctransport_addopt();

// Page init
$transport_addopt->Page_Init();

// Page main
$transport_addopt->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$transport_addopt->Page_Render();
?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "addopt";
var CurrentForm = ftransportaddopt = new ew_Form("ftransportaddopt", "addopt");

// Validate form
ftransportaddopt.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_route_name");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $transport->route_name->FldCaption(), $transport->route_name->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_number_of_vehicle");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $transport->number_of_vehicle->FldCaption(), $transport->number_of_vehicle->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_description");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $transport->description->FldCaption(), $transport->description->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_route_fare");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $transport->route_fare->FldCaption(), $transport->route_fare->ReqErrMsg)) ?>");

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
	}
	return true;
}

// Form_CustomValidate event
ftransportaddopt.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
ftransportaddopt.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php
$transport_addopt->ShowMessage();
?>
<form name="ftransportaddopt" id="ftransportaddopt" class="ewForm form-horizontal" action="transportaddopt.php" method="post">
<?php if ($transport_addopt->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $transport_addopt->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="transport">
<input type="hidden" name="a_addopt" id="a_addopt" value="A">
<?php if ($transport->route_name->Visible) { // route_name ?>
	<div class="form-group">
		<label class="col-sm-2 control-label ewLabel" for="x_route_name"><?php echo $transport->route_name->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10">
<textarea data-table="transport" data-field="x_route_name" name="x_route_name" id="x_route_name" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($transport->route_name->getPlaceHolder()) ?>"<?php echo $transport->route_name->EditAttributes() ?>><?php echo $transport->route_name->EditValue ?></textarea>
</div>
	</div>
<?php } ?>
<?php if ($transport->number_of_vehicle->Visible) { // number_of_vehicle ?>
	<div class="form-group">
		<label class="col-sm-2 control-label ewLabel" for="x_number_of_vehicle"><?php echo $transport->number_of_vehicle->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10">
<textarea data-table="transport" data-field="x_number_of_vehicle" name="x_number_of_vehicle" id="x_number_of_vehicle" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($transport->number_of_vehicle->getPlaceHolder()) ?>"<?php echo $transport->number_of_vehicle->EditAttributes() ?>><?php echo $transport->number_of_vehicle->EditValue ?></textarea>
</div>
	</div>
<?php } ?>
<?php if ($transport->description->Visible) { // description ?>
	<div class="form-group">
		<label class="col-sm-2 control-label ewLabel" for="x_description"><?php echo $transport->description->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10">
<textarea data-table="transport" data-field="x_description" name="x_description" id="x_description" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($transport->description->getPlaceHolder()) ?>"<?php echo $transport->description->EditAttributes() ?>><?php echo $transport->description->EditValue ?></textarea>
</div>
	</div>
<?php } ?>
<?php if ($transport->route_fare->Visible) { // route_fare ?>
	<div class="form-group">
		<label class="col-sm-2 control-label ewLabel" for="x_route_fare"><?php echo $transport->route_fare->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10">
<textarea data-table="transport" data-field="x_route_fare" name="x_route_fare" id="x_route_fare" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($transport->route_fare->getPlaceHolder()) ?>"<?php echo $transport->route_fare->EditAttributes() ?>><?php echo $transport->route_fare->EditValue ?></textarea>
</div>
	</div>
<?php } ?>
</form>
<script type="text/javascript">
ftransportaddopt.Init();
</script>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php
$transport_addopt->Page_Terminate();
?>
