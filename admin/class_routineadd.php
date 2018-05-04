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

$class_routine_add = NULL; // Initialize page object first

class cclass_routine_add extends cclass_routine {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = '{1F40EE59-17A4-45D3-96B6-AA2082B3D64A}';

	// Table name
	var $TableName = 'class_routine';

	// Page object name
	var $PageObjName = 'class_routine_add';

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
			define("EW_PAGE_ID", 'add', TRUE);

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

		// Is modal
		$this->IsModal = (@$_GET["modal"] == "1" || @$_POST["modal"] == "1");

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
				$this->Page_Terminate(ew_GetUrl("class_routinelist.php"));
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
		$this->class_id->SetVisibility();
		$this->subject_id->SetVisibility();
		$this->time_start->SetVisibility();
		$this->time_end->SetVisibility();
		$this->time_start_min->SetVisibility();
		$this->time_end_min->SetVisibility();
		$this->day->SetVisibility();

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

			// Handle modal response
			if ($this->IsModal) { // Show as modal
				$row = array("url" => $url, "modal" => "1");
				$pageName = ew_GetPageName($url);
				if ($pageName != $this->GetListUrl()) { // Not List page
					$row["caption"] = $this->GetModalCaption($pageName);
					if ($pageName == "class_routineview.php")
						$row["view"] = "1";
				} else { // List page should not be shown as modal => error
					$row["error"] = $this->getFailureMessage();
					$this->clearFailureMessage();
				}
				header("Content-Type: application/json; charset=utf-8");
				echo ew_ConvertToUtf8(ew_ArrayToJson(array($row)));
			} else {
				ew_SaveDebugMsg();
				header("Location: " . $url);
			}
		}
		exit();
	}
	var $FormClassName = "form-horizontal ewForm ewAddForm";
	var $IsModal = FALSE;
	var $IsMobileOrModal = FALSE;
	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
	var $StartRec;
	var $Priv = 0;
	var $OldRecordset;
	var $CopyRecord;

	//
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;
		global $gbSkipHeaderFooter;

		// Check modal
		if ($this->IsModal)
			$gbSkipHeaderFooter = TRUE;
		$this->IsMobileOrModal = ew_IsMobile() || $this->IsModal;
		$this->FormClassName = "ewForm ewAddForm form-horizontal";

		// Set up current action
		if (@$_POST["a_add"] <> "") {
			$this->CurrentAction = $_POST["a_add"]; // Get form action
		} else { // Not post back

			// Load key values from QueryString
			$this->CopyRecord = TRUE;
			if (@$_GET["class_routine_id"] != "") {
				$this->class_routine_id->setQueryStringValue($_GET["class_routine_id"]);
				$this->setKey("class_routine_id", $this->class_routine_id->CurrentValue); // Set up key
			} else {
				$this->setKey("class_routine_id", ""); // Clear key
				$this->CopyRecord = FALSE;
			}
			if ($this->CopyRecord) {
				$this->CurrentAction = "C"; // Copy record
			} else {
				$this->CurrentAction = "I"; // Display blank record
			}
		}

		// Load old record / default values
		$loaded = $this->LoadOldRecord();

		// Load form values
		if (@$_POST["a_add"] <> "") {
			$this->LoadFormValues(); // Load form values
		}

		// Validate form if post back
		if (@$_POST["a_add"] <> "") {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = "I"; // Form error, reset action
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues(); // Restore form values
				$this->setFailureMessage($gsFormError);
			}
		}

		// Perform current action
		switch ($this->CurrentAction) {
			case "I": // Blank record
				break;
			case "C": // Copy an existing record
				if (!$loaded) { // Record not loaded
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("class_routinelist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "class_routinelist.php")
						$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to List page with correct master key if necessary
					elseif (ew_GetPageName($sReturnUrl) == "class_routineview.php")
						$sReturnUrl = $this->GetViewUrl(); // View page, return to View page with keyurl directly
					$this->Page_Terminate($sReturnUrl); // Clean up and return
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Add failed, restore form values
				}
		}

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Render row based on row type
		$this->RowType = EW_ROWTYPE_ADD; // Render add type

		// Render row
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
		$this->class_routine_id->CurrentValue = NULL;
		$this->class_routine_id->OldValue = $this->class_routine_id->CurrentValue;
		$this->class_id->CurrentValue = NULL;
		$this->class_id->OldValue = $this->class_id->CurrentValue;
		$this->subject_id->CurrentValue = NULL;
		$this->subject_id->OldValue = $this->subject_id->CurrentValue;
		$this->time_start->CurrentValue = NULL;
		$this->time_start->OldValue = $this->time_start->CurrentValue;
		$this->time_end->CurrentValue = NULL;
		$this->time_end->OldValue = $this->time_end->CurrentValue;
		$this->time_start_min->CurrentValue = NULL;
		$this->time_start_min->OldValue = $this->time_start_min->CurrentValue;
		$this->time_end_min->CurrentValue = NULL;
		$this->time_end_min->OldValue = $this->time_end_min->CurrentValue;
		$this->day->CurrentValue = NULL;
		$this->day->OldValue = $this->day->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->class_id->FldIsDetailKey) {
			$this->class_id->setFormValue($objForm->GetValue("x_class_id"));
		}
		if (!$this->subject_id->FldIsDetailKey) {
			$this->subject_id->setFormValue($objForm->GetValue("x_subject_id"));
		}
		if (!$this->time_start->FldIsDetailKey) {
			$this->time_start->setFormValue($objForm->GetValue("x_time_start"));
		}
		if (!$this->time_end->FldIsDetailKey) {
			$this->time_end->setFormValue($objForm->GetValue("x_time_end"));
		}
		if (!$this->time_start_min->FldIsDetailKey) {
			$this->time_start_min->setFormValue($objForm->GetValue("x_time_start_min"));
		}
		if (!$this->time_end_min->FldIsDetailKey) {
			$this->time_end_min->setFormValue($objForm->GetValue("x_time_end_min"));
		}
		if (!$this->day->FldIsDetailKey) {
			$this->day->setFormValue($objForm->GetValue("x_day"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->class_id->CurrentValue = $this->class_id->FormValue;
		$this->subject_id->CurrentValue = $this->subject_id->FormValue;
		$this->time_start->CurrentValue = $this->time_start->FormValue;
		$this->time_end->CurrentValue = $this->time_end->FormValue;
		$this->time_start_min->CurrentValue = $this->time_start_min->FormValue;
		$this->time_end_min->CurrentValue = $this->time_end_min->FormValue;
		$this->day->CurrentValue = $this->day->FormValue;
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
		$this->LoadDefaultValues();
		$row = array();
		$row['class_routine_id'] = $this->class_routine_id->CurrentValue;
		$row['class_id'] = $this->class_id->CurrentValue;
		$row['subject_id'] = $this->subject_id->CurrentValue;
		$row['time_start'] = $this->time_start->CurrentValue;
		$row['time_end'] = $this->time_end->CurrentValue;
		$row['time_start_min'] = $this->time_start_min->CurrentValue;
		$row['time_end_min'] = $this->time_end_min->CurrentValue;
		$row['day'] = $this->day->CurrentValue;
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

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("class_routine_id")) <> "")
			$this->class_routine_id->CurrentValue = $this->getKey("class_routine_id"); // class_routine_id
		else
			$bValidKey = FALSE;

		// Load old record
		$this->OldRecordset = NULL;
		if ($bValidKey) {
			$this->CurrentFilter = $this->KeyFilter();
			$sSql = $this->SQL();
			$conn = &$this->Connection();
			$this->OldRecordset = ew_LoadRecordset($sSql, $conn);
		}
		$this->LoadRowValues($this->OldRecordset); // Load row values
		return $bValidKey;
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

		// day
		$this->day->ViewValue = $this->day->CurrentValue;
		$this->day->ViewCustomAttributes = "";

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

			// day
			$this->day->LinkCustomAttributes = "";
			$this->day->HrefValue = "";
			$this->day->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// class_id
			$this->class_id->EditAttrs["class"] = "form-control";
			$this->class_id->EditCustomAttributes = "";
			$this->class_id->EditValue = ew_HtmlEncode($this->class_id->CurrentValue);
			$this->class_id->PlaceHolder = ew_RemoveHtml($this->class_id->FldCaption());

			// subject_id
			$this->subject_id->EditAttrs["class"] = "form-control";
			$this->subject_id->EditCustomAttributes = "";
			$this->subject_id->EditValue = ew_HtmlEncode($this->subject_id->CurrentValue);
			$this->subject_id->PlaceHolder = ew_RemoveHtml($this->subject_id->FldCaption());

			// time_start
			$this->time_start->EditAttrs["class"] = "form-control";
			$this->time_start->EditCustomAttributes = "";
			$this->time_start->EditValue = ew_HtmlEncode($this->time_start->CurrentValue);
			$this->time_start->PlaceHolder = ew_RemoveHtml($this->time_start->FldCaption());

			// time_end
			$this->time_end->EditAttrs["class"] = "form-control";
			$this->time_end->EditCustomAttributes = "";
			$this->time_end->EditValue = ew_HtmlEncode($this->time_end->CurrentValue);
			$this->time_end->PlaceHolder = ew_RemoveHtml($this->time_end->FldCaption());

			// time_start_min
			$this->time_start_min->EditAttrs["class"] = "form-control";
			$this->time_start_min->EditCustomAttributes = "";
			$this->time_start_min->EditValue = ew_HtmlEncode($this->time_start_min->CurrentValue);
			$this->time_start_min->PlaceHolder = ew_RemoveHtml($this->time_start_min->FldCaption());

			// time_end_min
			$this->time_end_min->EditAttrs["class"] = "form-control";
			$this->time_end_min->EditCustomAttributes = "";
			$this->time_end_min->EditValue = ew_HtmlEncode($this->time_end_min->CurrentValue);
			$this->time_end_min->PlaceHolder = ew_RemoveHtml($this->time_end_min->FldCaption());

			// day
			$this->day->EditAttrs["class"] = "form-control";
			$this->day->EditCustomAttributes = "";
			$this->day->EditValue = ew_HtmlEncode($this->day->CurrentValue);
			$this->day->PlaceHolder = ew_RemoveHtml($this->day->FldCaption());

			// Add refer script
			// class_id

			$this->class_id->LinkCustomAttributes = "";
			$this->class_id->HrefValue = "";

			// subject_id
			$this->subject_id->LinkCustomAttributes = "";
			$this->subject_id->HrefValue = "";

			// time_start
			$this->time_start->LinkCustomAttributes = "";
			$this->time_start->HrefValue = "";

			// time_end
			$this->time_end->LinkCustomAttributes = "";
			$this->time_end->HrefValue = "";

			// time_start_min
			$this->time_start_min->LinkCustomAttributes = "";
			$this->time_start_min->HrefValue = "";

			// time_end_min
			$this->time_end_min->LinkCustomAttributes = "";
			$this->time_end_min->HrefValue = "";

			// day
			$this->day->LinkCustomAttributes = "";
			$this->day->HrefValue = "";
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
		if (!$this->class_id->FldIsDetailKey && !is_null($this->class_id->FormValue) && $this->class_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->class_id->FldCaption(), $this->class_id->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->class_id->FormValue)) {
			ew_AddMessage($gsFormError, $this->class_id->FldErrMsg());
		}
		if (!$this->subject_id->FldIsDetailKey && !is_null($this->subject_id->FormValue) && $this->subject_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->subject_id->FldCaption(), $this->subject_id->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->subject_id->FormValue)) {
			ew_AddMessage($gsFormError, $this->subject_id->FldErrMsg());
		}
		if (!$this->time_start->FldIsDetailKey && !is_null($this->time_start->FormValue) && $this->time_start->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->time_start->FldCaption(), $this->time_start->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->time_start->FormValue)) {
			ew_AddMessage($gsFormError, $this->time_start->FldErrMsg());
		}
		if (!$this->time_end->FldIsDetailKey && !is_null($this->time_end->FormValue) && $this->time_end->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->time_end->FldCaption(), $this->time_end->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->time_end->FormValue)) {
			ew_AddMessage($gsFormError, $this->time_end->FldErrMsg());
		}
		if (!$this->time_start_min->FldIsDetailKey && !is_null($this->time_start_min->FormValue) && $this->time_start_min->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->time_start_min->FldCaption(), $this->time_start_min->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->time_start_min->FormValue)) {
			ew_AddMessage($gsFormError, $this->time_start_min->FldErrMsg());
		}
		if (!$this->time_end_min->FldIsDetailKey && !is_null($this->time_end_min->FormValue) && $this->time_end_min->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->time_end_min->FldCaption(), $this->time_end_min->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->time_end_min->FormValue)) {
			ew_AddMessage($gsFormError, $this->time_end_min->FldErrMsg());
		}
		if (!$this->day->FldIsDetailKey && !is_null($this->day->FormValue) && $this->day->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->day->FldCaption(), $this->day->ReqErrMsg));
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

		// class_id
		$this->class_id->SetDbValueDef($rsnew, $this->class_id->CurrentValue, 0, FALSE);

		// subject_id
		$this->subject_id->SetDbValueDef($rsnew, $this->subject_id->CurrentValue, 0, FALSE);

		// time_start
		$this->time_start->SetDbValueDef($rsnew, $this->time_start->CurrentValue, 0, FALSE);

		// time_end
		$this->time_end->SetDbValueDef($rsnew, $this->time_end->CurrentValue, 0, FALSE);

		// time_start_min
		$this->time_start_min->SetDbValueDef($rsnew, $this->time_start_min->CurrentValue, 0, FALSE);

		// time_end_min
		$this->time_end_min->SetDbValueDef($rsnew, $this->time_end_min->CurrentValue, 0, FALSE);

		// day
		$this->day->SetDbValueDef($rsnew, $this->day->CurrentValue, "", FALSE);

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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("class_routinelist.php"), "", $this->TableVar, TRUE);
		$PageId = ($this->CurrentAction == "C") ? "Copy" : "Add";
		$Breadcrumb->Add("add", $PageId, $url);
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
if (!isset($class_routine_add)) $class_routine_add = new cclass_routine_add();

// Page init
$class_routine_add->Page_Init();

// Page main
$class_routine_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$class_routine_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "add";
var CurrentForm = fclass_routineadd = new ew_Form("fclass_routineadd", "add");

// Validate form
fclass_routineadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_class_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $class_routine->class_id->FldCaption(), $class_routine->class_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_class_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($class_routine->class_id->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_subject_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $class_routine->subject_id->FldCaption(), $class_routine->subject_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_subject_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($class_routine->subject_id->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_time_start");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $class_routine->time_start->FldCaption(), $class_routine->time_start->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_time_start");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($class_routine->time_start->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_time_end");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $class_routine->time_end->FldCaption(), $class_routine->time_end->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_time_end");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($class_routine->time_end->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_time_start_min");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $class_routine->time_start_min->FldCaption(), $class_routine->time_start_min->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_time_start_min");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($class_routine->time_start_min->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_time_end_min");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $class_routine->time_end_min->FldCaption(), $class_routine->time_end_min->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_time_end_min");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($class_routine->time_end_min->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_day");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $class_routine->day->FldCaption(), $class_routine->day->ReqErrMsg)) ?>");

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
	}

	// Process detail forms
	var dfs = $fobj.find("input[name='detailpage']").get();
	for (var i = 0; i < dfs.length; i++) {
		var df = dfs[i], val = df.value;
		if (val && ewForms[val])
			if (!ewForms[val].Validate())
				return false;
	}
	return true;
}

// Form_CustomValidate event
fclass_routineadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fclass_routineadd.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $class_routine_add->ShowPageHeader(); ?>
<?php
$class_routine_add->ShowMessage();
?>
<form name="fclass_routineadd" id="fclass_routineadd" class="<?php echo $class_routine_add->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($class_routine_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $class_routine_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="class_routine">
<input type="hidden" name="a_add" id="a_add" value="A">
<input type="hidden" name="modal" value="<?php echo intval($class_routine_add->IsModal) ?>">
<div class="ewAddDiv"><!-- page* -->
<?php if ($class_routine->class_id->Visible) { // class_id ?>
	<div id="r_class_id" class="form-group">
		<label id="elh_class_routine_class_id" for="x_class_id" class="<?php echo $class_routine_add->LeftColumnClass ?>"><?php echo $class_routine->class_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $class_routine_add->RightColumnClass ?>"><div<?php echo $class_routine->class_id->CellAttributes() ?>>
<span id="el_class_routine_class_id">
<input type="text" data-table="class_routine" data-field="x_class_id" name="x_class_id" id="x_class_id" size="30" placeholder="<?php echo ew_HtmlEncode($class_routine->class_id->getPlaceHolder()) ?>" value="<?php echo $class_routine->class_id->EditValue ?>"<?php echo $class_routine->class_id->EditAttributes() ?>>
</span>
<?php echo $class_routine->class_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($class_routine->subject_id->Visible) { // subject_id ?>
	<div id="r_subject_id" class="form-group">
		<label id="elh_class_routine_subject_id" for="x_subject_id" class="<?php echo $class_routine_add->LeftColumnClass ?>"><?php echo $class_routine->subject_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $class_routine_add->RightColumnClass ?>"><div<?php echo $class_routine->subject_id->CellAttributes() ?>>
<span id="el_class_routine_subject_id">
<input type="text" data-table="class_routine" data-field="x_subject_id" name="x_subject_id" id="x_subject_id" size="30" placeholder="<?php echo ew_HtmlEncode($class_routine->subject_id->getPlaceHolder()) ?>" value="<?php echo $class_routine->subject_id->EditValue ?>"<?php echo $class_routine->subject_id->EditAttributes() ?>>
</span>
<?php echo $class_routine->subject_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($class_routine->time_start->Visible) { // time_start ?>
	<div id="r_time_start" class="form-group">
		<label id="elh_class_routine_time_start" for="x_time_start" class="<?php echo $class_routine_add->LeftColumnClass ?>"><?php echo $class_routine->time_start->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $class_routine_add->RightColumnClass ?>"><div<?php echo $class_routine->time_start->CellAttributes() ?>>
<span id="el_class_routine_time_start">
<input type="text" data-table="class_routine" data-field="x_time_start" name="x_time_start" id="x_time_start" size="30" placeholder="<?php echo ew_HtmlEncode($class_routine->time_start->getPlaceHolder()) ?>" value="<?php echo $class_routine->time_start->EditValue ?>"<?php echo $class_routine->time_start->EditAttributes() ?>>
</span>
<?php echo $class_routine->time_start->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($class_routine->time_end->Visible) { // time_end ?>
	<div id="r_time_end" class="form-group">
		<label id="elh_class_routine_time_end" for="x_time_end" class="<?php echo $class_routine_add->LeftColumnClass ?>"><?php echo $class_routine->time_end->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $class_routine_add->RightColumnClass ?>"><div<?php echo $class_routine->time_end->CellAttributes() ?>>
<span id="el_class_routine_time_end">
<input type="text" data-table="class_routine" data-field="x_time_end" name="x_time_end" id="x_time_end" size="30" placeholder="<?php echo ew_HtmlEncode($class_routine->time_end->getPlaceHolder()) ?>" value="<?php echo $class_routine->time_end->EditValue ?>"<?php echo $class_routine->time_end->EditAttributes() ?>>
</span>
<?php echo $class_routine->time_end->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($class_routine->time_start_min->Visible) { // time_start_min ?>
	<div id="r_time_start_min" class="form-group">
		<label id="elh_class_routine_time_start_min" for="x_time_start_min" class="<?php echo $class_routine_add->LeftColumnClass ?>"><?php echo $class_routine->time_start_min->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $class_routine_add->RightColumnClass ?>"><div<?php echo $class_routine->time_start_min->CellAttributes() ?>>
<span id="el_class_routine_time_start_min">
<input type="text" data-table="class_routine" data-field="x_time_start_min" name="x_time_start_min" id="x_time_start_min" size="30" placeholder="<?php echo ew_HtmlEncode($class_routine->time_start_min->getPlaceHolder()) ?>" value="<?php echo $class_routine->time_start_min->EditValue ?>"<?php echo $class_routine->time_start_min->EditAttributes() ?>>
</span>
<?php echo $class_routine->time_start_min->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($class_routine->time_end_min->Visible) { // time_end_min ?>
	<div id="r_time_end_min" class="form-group">
		<label id="elh_class_routine_time_end_min" for="x_time_end_min" class="<?php echo $class_routine_add->LeftColumnClass ?>"><?php echo $class_routine->time_end_min->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $class_routine_add->RightColumnClass ?>"><div<?php echo $class_routine->time_end_min->CellAttributes() ?>>
<span id="el_class_routine_time_end_min">
<input type="text" data-table="class_routine" data-field="x_time_end_min" name="x_time_end_min" id="x_time_end_min" size="30" placeholder="<?php echo ew_HtmlEncode($class_routine->time_end_min->getPlaceHolder()) ?>" value="<?php echo $class_routine->time_end_min->EditValue ?>"<?php echo $class_routine->time_end_min->EditAttributes() ?>>
</span>
<?php echo $class_routine->time_end_min->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($class_routine->day->Visible) { // day ?>
	<div id="r_day" class="form-group">
		<label id="elh_class_routine_day" for="x_day" class="<?php echo $class_routine_add->LeftColumnClass ?>"><?php echo $class_routine->day->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $class_routine_add->RightColumnClass ?>"><div<?php echo $class_routine->day->CellAttributes() ?>>
<span id="el_class_routine_day">
<textarea data-table="class_routine" data-field="x_day" name="x_day" id="x_day" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($class_routine->day->getPlaceHolder()) ?>"<?php echo $class_routine->day->EditAttributes() ?>><?php echo $class_routine->day->EditValue ?></textarea>
</span>
<?php echo $class_routine->day->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div><!-- /page* -->
<?php if (!$class_routine_add->IsModal) { ?>
<div class="form-group"><!-- buttons .form-group -->
	<div class="<?php echo $class_routine_add->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $class_routine_add->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div><!-- /buttons offset -->
</div><!-- /buttons .form-group -->
<?php } ?>
</form>
<script type="text/javascript">
fclass_routineadd.Init();
</script>
<?php
$class_routine_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$class_routine_add->Page_Terminate();
?>
