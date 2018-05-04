<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "invoiceinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$invoice_edit = NULL; // Initialize page object first

class cinvoice_edit extends cinvoice {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = '{1F40EE59-17A4-45D3-96B6-AA2082B3D64A}';

	// Table name
	var $TableName = 'invoice';

	// Page object name
	var $PageObjName = 'invoice_edit';

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

		// Table object (invoice)
		if (!isset($GLOBALS["invoice"]) || get_class($GLOBALS["invoice"]) == "cinvoice") {
			$GLOBALS["invoice"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["invoice"];
		}

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'invoice', TRUE);

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
		if (!$Security->CanEdit()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage(ew_DeniedMsg()); // Set no permission
			if ($Security->CanList())
				$this->Page_Terminate(ew_GetUrl("invoicelist.php"));
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
		$this->invoice_id->SetVisibility();
		if ($this->IsAdd() || $this->IsCopy() || $this->IsGridAdd())
			$this->invoice_id->Visible = FALSE;
		$this->student_id->SetVisibility();
		$this->title->SetVisibility();
		$this->description->SetVisibility();
		$this->amount->SetVisibility();
		$this->amount_paid->SetVisibility();
		$this->due->SetVisibility();
		$this->creation_timestamp->SetVisibility();
		$this->payment_timestamp->SetVisibility();
		$this->payment_method->SetVisibility();
		$this->payment_details->SetVisibility();
		$this->status->SetVisibility();

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
		global $EW_EXPORT, $invoice;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($invoice);
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
					if ($pageName == "invoiceview.php")
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
	var $FormClassName = "form-horizontal ewForm ewEditForm";
	var $IsModal = FALSE;
	var $IsMobileOrModal = FALSE;
	var $DbMasterFilter;
	var $DbDetailFilter;

	//
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError, $gbSkipHeaderFooter;

		// Check modal
		if ($this->IsModal)
			$gbSkipHeaderFooter = TRUE;
		$this->IsMobileOrModal = ew_IsMobile() || $this->IsModal;
		$this->FormClassName = "ewForm ewEditForm form-horizontal";
		$sReturnUrl = "";
		$loaded = FALSE;
		$postBack = FALSE;

		// Set up current action and primary key
		if (@$_POST["a_edit"] <> "") {
			$this->CurrentAction = $_POST["a_edit"]; // Get action code
			if ($this->CurrentAction <> "I") // Not reload record, handle as postback
				$postBack = TRUE;

			// Load key from Form
			if ($objForm->HasValue("x_invoice_id")) {
				$this->invoice_id->setFormValue($objForm->GetValue("x_invoice_id"));
			}
		} else {
			$this->CurrentAction = "I"; // Default action is display

			// Load key from QueryString
			$loadByQuery = FALSE;
			if (isset($_GET["invoice_id"])) {
				$this->invoice_id->setQueryStringValue($_GET["invoice_id"]);
				$loadByQuery = TRUE;
			} else {
				$this->invoice_id->CurrentValue = NULL;
			}
		}

		// Load current record
		$loaded = $this->LoadRow();

		// Process form if post back
		if ($postBack) {
			$this->LoadFormValues(); // Get form values
		}

		// Validate form if post back
		if ($postBack) {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = ""; // Form error, reset action
				$this->setFailureMessage($gsFormError);
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues();
			}
		}

		// Perform current action
		switch ($this->CurrentAction) {
			case "I": // Get a record to display
				if (!$loaded) { // Load record based on key
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("invoicelist.php"); // No matching record, return to list
				}
				break;
			Case "U": // Update
				$sReturnUrl = $this->getReturnUrl();
				if (ew_GetPageName($sReturnUrl) == "invoicelist.php")
					$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to List page with correct master key if necessary
				$this->SendEmail = TRUE; // Send email on update success
				if ($this->EditRow()) { // Update record based on key
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Update success
					$this->Page_Terminate($sReturnUrl); // Return to caller
				} elseif ($this->getFailureMessage() == $Language->Phrase("NoRecord")) {
					$this->Page_Terminate($sReturnUrl); // Return to caller
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Restore form values if update failed
				}
		}

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Render the record
		$this->RowType = EW_ROWTYPE_EDIT; // Render as Edit
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Set up starting record parameters
	function SetupStartRec() {
		if ($this->DisplayRecs == 0)
			return;
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET[EW_TABLE_START_REC] <> "") { // Check for "start" parameter
				$this->StartRec = $_GET[EW_TABLE_START_REC];
				$this->setStartRecordNumber($this->StartRec);
			} elseif (@$_GET[EW_TABLE_PAGE_NO] <> "") {
				$PageNo = $_GET[EW_TABLE_PAGE_NO];
				if (is_numeric($PageNo)) {
					$this->StartRec = ($PageNo-1)*$this->DisplayRecs+1;
					if ($this->StartRec <= 0) {
						$this->StartRec = 1;
					} elseif ($this->StartRec >= intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1) {
						$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1;
					}
					$this->setStartRecordNumber($this->StartRec);
				}
			}
		}
		$this->StartRec = $this->getStartRecordNumber();

		// Check if correct start record counter
		if (!is_numeric($this->StartRec) || $this->StartRec == "") { // Avoid invalid start record counter
			$this->StartRec = 1; // Reset start record counter
			$this->setStartRecordNumber($this->StartRec);
		} elseif (intval($this->StartRec) > intval($this->TotalRecs)) { // Avoid starting record > total records
			$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to last page first record
			$this->setStartRecordNumber($this->StartRec);
		} elseif (($this->StartRec-1) % $this->DisplayRecs <> 0) {
			$this->StartRec = intval(($this->StartRec-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to page boundary
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Get upload files
	function GetUploadFiles() {
		global $objForm, $Language;

		// Get upload data
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->invoice_id->FldIsDetailKey)
			$this->invoice_id->setFormValue($objForm->GetValue("x_invoice_id"));
		if (!$this->student_id->FldIsDetailKey) {
			$this->student_id->setFormValue($objForm->GetValue("x_student_id"));
		}
		if (!$this->title->FldIsDetailKey) {
			$this->title->setFormValue($objForm->GetValue("x_title"));
		}
		if (!$this->description->FldIsDetailKey) {
			$this->description->setFormValue($objForm->GetValue("x_description"));
		}
		if (!$this->amount->FldIsDetailKey) {
			$this->amount->setFormValue($objForm->GetValue("x_amount"));
		}
		if (!$this->amount_paid->FldIsDetailKey) {
			$this->amount_paid->setFormValue($objForm->GetValue("x_amount_paid"));
		}
		if (!$this->due->FldIsDetailKey) {
			$this->due->setFormValue($objForm->GetValue("x_due"));
		}
		if (!$this->creation_timestamp->FldIsDetailKey) {
			$this->creation_timestamp->setFormValue($objForm->GetValue("x_creation_timestamp"));
		}
		if (!$this->payment_timestamp->FldIsDetailKey) {
			$this->payment_timestamp->setFormValue($objForm->GetValue("x_payment_timestamp"));
		}
		if (!$this->payment_method->FldIsDetailKey) {
			$this->payment_method->setFormValue($objForm->GetValue("x_payment_method"));
		}
		if (!$this->payment_details->FldIsDetailKey) {
			$this->payment_details->setFormValue($objForm->GetValue("x_payment_details"));
		}
		if (!$this->status->FldIsDetailKey) {
			$this->status->setFormValue($objForm->GetValue("x_status"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->invoice_id->CurrentValue = $this->invoice_id->FormValue;
		$this->student_id->CurrentValue = $this->student_id->FormValue;
		$this->title->CurrentValue = $this->title->FormValue;
		$this->description->CurrentValue = $this->description->FormValue;
		$this->amount->CurrentValue = $this->amount->FormValue;
		$this->amount_paid->CurrentValue = $this->amount_paid->FormValue;
		$this->due->CurrentValue = $this->due->FormValue;
		$this->creation_timestamp->CurrentValue = $this->creation_timestamp->FormValue;
		$this->payment_timestamp->CurrentValue = $this->payment_timestamp->FormValue;
		$this->payment_method->CurrentValue = $this->payment_method->FormValue;
		$this->payment_details->CurrentValue = $this->payment_details->FormValue;
		$this->status->CurrentValue = $this->status->FormValue;
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
		$this->invoice_id->setDbValue($row['invoice_id']);
		$this->student_id->setDbValue($row['student_id']);
		$this->title->setDbValue($row['title']);
		$this->description->setDbValue($row['description']);
		$this->amount->setDbValue($row['amount']);
		$this->amount_paid->setDbValue($row['amount_paid']);
		$this->due->setDbValue($row['due']);
		$this->creation_timestamp->setDbValue($row['creation_timestamp']);
		$this->payment_timestamp->setDbValue($row['payment_timestamp']);
		$this->payment_method->setDbValue($row['payment_method']);
		$this->payment_details->setDbValue($row['payment_details']);
		$this->status->setDbValue($row['status']);
	}

	// Return a row with default values
	function NewRow() {
		$row = array();
		$row['invoice_id'] = NULL;
		$row['student_id'] = NULL;
		$row['title'] = NULL;
		$row['description'] = NULL;
		$row['amount'] = NULL;
		$row['amount_paid'] = NULL;
		$row['due'] = NULL;
		$row['creation_timestamp'] = NULL;
		$row['payment_timestamp'] = NULL;
		$row['payment_method'] = NULL;
		$row['payment_details'] = NULL;
		$row['status'] = NULL;
		return $row;
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF)
			return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->invoice_id->DbValue = $row['invoice_id'];
		$this->student_id->DbValue = $row['student_id'];
		$this->title->DbValue = $row['title'];
		$this->description->DbValue = $row['description'];
		$this->amount->DbValue = $row['amount'];
		$this->amount_paid->DbValue = $row['amount_paid'];
		$this->due->DbValue = $row['due'];
		$this->creation_timestamp->DbValue = $row['creation_timestamp'];
		$this->payment_timestamp->DbValue = $row['payment_timestamp'];
		$this->payment_method->DbValue = $row['payment_method'];
		$this->payment_details->DbValue = $row['payment_details'];
		$this->status->DbValue = $row['status'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("invoice_id")) <> "")
			$this->invoice_id->CurrentValue = $this->getKey("invoice_id"); // invoice_id
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
		// invoice_id
		// student_id
		// title
		// description
		// amount
		// amount_paid
		// due
		// creation_timestamp
		// payment_timestamp
		// payment_method
		// payment_details
		// status

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// invoice_id
		$this->invoice_id->ViewValue = $this->invoice_id->CurrentValue;
		$this->invoice_id->ViewCustomAttributes = "";

		// student_id
		$this->student_id->ViewValue = $this->student_id->CurrentValue;
		$this->student_id->ViewCustomAttributes = "";

		// title
		$this->title->ViewValue = $this->title->CurrentValue;
		$this->title->ViewCustomAttributes = "";

		// description
		$this->description->ViewValue = $this->description->CurrentValue;
		$this->description->ViewCustomAttributes = "";

		// amount
		$this->amount->ViewValue = $this->amount->CurrentValue;
		$this->amount->ViewCustomAttributes = "";

		// amount_paid
		$this->amount_paid->ViewValue = $this->amount_paid->CurrentValue;
		$this->amount_paid->ViewCustomAttributes = "";

		// due
		$this->due->ViewValue = $this->due->CurrentValue;
		$this->due->ViewCustomAttributes = "";

		// creation_timestamp
		$this->creation_timestamp->ViewValue = $this->creation_timestamp->CurrentValue;
		$this->creation_timestamp->ViewCustomAttributes = "";

		// payment_timestamp
		$this->payment_timestamp->ViewValue = $this->payment_timestamp->CurrentValue;
		$this->payment_timestamp->ViewCustomAttributes = "";

		// payment_method
		$this->payment_method->ViewValue = $this->payment_method->CurrentValue;
		$this->payment_method->ViewCustomAttributes = "";

		// payment_details
		$this->payment_details->ViewValue = $this->payment_details->CurrentValue;
		$this->payment_details->ViewCustomAttributes = "";

		// status
		$this->status->ViewValue = $this->status->CurrentValue;
		$this->status->ViewCustomAttributes = "";

			// invoice_id
			$this->invoice_id->LinkCustomAttributes = "";
			$this->invoice_id->HrefValue = "";
			$this->invoice_id->TooltipValue = "";

			// student_id
			$this->student_id->LinkCustomAttributes = "";
			$this->student_id->HrefValue = "";
			$this->student_id->TooltipValue = "";

			// title
			$this->title->LinkCustomAttributes = "";
			$this->title->HrefValue = "";
			$this->title->TooltipValue = "";

			// description
			$this->description->LinkCustomAttributes = "";
			$this->description->HrefValue = "";
			$this->description->TooltipValue = "";

			// amount
			$this->amount->LinkCustomAttributes = "";
			$this->amount->HrefValue = "";
			$this->amount->TooltipValue = "";

			// amount_paid
			$this->amount_paid->LinkCustomAttributes = "";
			$this->amount_paid->HrefValue = "";
			$this->amount_paid->TooltipValue = "";

			// due
			$this->due->LinkCustomAttributes = "";
			$this->due->HrefValue = "";
			$this->due->TooltipValue = "";

			// creation_timestamp
			$this->creation_timestamp->LinkCustomAttributes = "";
			$this->creation_timestamp->HrefValue = "";
			$this->creation_timestamp->TooltipValue = "";

			// payment_timestamp
			$this->payment_timestamp->LinkCustomAttributes = "";
			$this->payment_timestamp->HrefValue = "";
			$this->payment_timestamp->TooltipValue = "";

			// payment_method
			$this->payment_method->LinkCustomAttributes = "";
			$this->payment_method->HrefValue = "";
			$this->payment_method->TooltipValue = "";

			// payment_details
			$this->payment_details->LinkCustomAttributes = "";
			$this->payment_details->HrefValue = "";
			$this->payment_details->TooltipValue = "";

			// status
			$this->status->LinkCustomAttributes = "";
			$this->status->HrefValue = "";
			$this->status->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// invoice_id
			$this->invoice_id->EditAttrs["class"] = "form-control";
			$this->invoice_id->EditCustomAttributes = "";
			$this->invoice_id->EditValue = $this->invoice_id->CurrentValue;
			$this->invoice_id->ViewCustomAttributes = "";

			// student_id
			$this->student_id->EditAttrs["class"] = "form-control";
			$this->student_id->EditCustomAttributes = "";
			$this->student_id->EditValue = ew_HtmlEncode($this->student_id->CurrentValue);
			$this->student_id->PlaceHolder = ew_RemoveHtml($this->student_id->FldCaption());

			// title
			$this->title->EditAttrs["class"] = "form-control";
			$this->title->EditCustomAttributes = "";
			$this->title->EditValue = ew_HtmlEncode($this->title->CurrentValue);
			$this->title->PlaceHolder = ew_RemoveHtml($this->title->FldCaption());

			// description
			$this->description->EditAttrs["class"] = "form-control";
			$this->description->EditCustomAttributes = "";
			$this->description->EditValue = ew_HtmlEncode($this->description->CurrentValue);
			$this->description->PlaceHolder = ew_RemoveHtml($this->description->FldCaption());

			// amount
			$this->amount->EditAttrs["class"] = "form-control";
			$this->amount->EditCustomAttributes = "";
			$this->amount->EditValue = ew_HtmlEncode($this->amount->CurrentValue);
			$this->amount->PlaceHolder = ew_RemoveHtml($this->amount->FldCaption());

			// amount_paid
			$this->amount_paid->EditAttrs["class"] = "form-control";
			$this->amount_paid->EditCustomAttributes = "";
			$this->amount_paid->EditValue = ew_HtmlEncode($this->amount_paid->CurrentValue);
			$this->amount_paid->PlaceHolder = ew_RemoveHtml($this->amount_paid->FldCaption());

			// due
			$this->due->EditAttrs["class"] = "form-control";
			$this->due->EditCustomAttributes = "";
			$this->due->EditValue = ew_HtmlEncode($this->due->CurrentValue);
			$this->due->PlaceHolder = ew_RemoveHtml($this->due->FldCaption());

			// creation_timestamp
			$this->creation_timestamp->EditAttrs["class"] = "form-control";
			$this->creation_timestamp->EditCustomAttributes = "";
			$this->creation_timestamp->EditValue = ew_HtmlEncode($this->creation_timestamp->CurrentValue);
			$this->creation_timestamp->PlaceHolder = ew_RemoveHtml($this->creation_timestamp->FldCaption());

			// payment_timestamp
			$this->payment_timestamp->EditAttrs["class"] = "form-control";
			$this->payment_timestamp->EditCustomAttributes = "";
			$this->payment_timestamp->EditValue = ew_HtmlEncode($this->payment_timestamp->CurrentValue);
			$this->payment_timestamp->PlaceHolder = ew_RemoveHtml($this->payment_timestamp->FldCaption());

			// payment_method
			$this->payment_method->EditAttrs["class"] = "form-control";
			$this->payment_method->EditCustomAttributes = "";
			$this->payment_method->EditValue = ew_HtmlEncode($this->payment_method->CurrentValue);
			$this->payment_method->PlaceHolder = ew_RemoveHtml($this->payment_method->FldCaption());

			// payment_details
			$this->payment_details->EditAttrs["class"] = "form-control";
			$this->payment_details->EditCustomAttributes = "";
			$this->payment_details->EditValue = ew_HtmlEncode($this->payment_details->CurrentValue);
			$this->payment_details->PlaceHolder = ew_RemoveHtml($this->payment_details->FldCaption());

			// status
			$this->status->EditAttrs["class"] = "form-control";
			$this->status->EditCustomAttributes = "";
			$this->status->EditValue = ew_HtmlEncode($this->status->CurrentValue);
			$this->status->PlaceHolder = ew_RemoveHtml($this->status->FldCaption());

			// Edit refer script
			// invoice_id

			$this->invoice_id->LinkCustomAttributes = "";
			$this->invoice_id->HrefValue = "";

			// student_id
			$this->student_id->LinkCustomAttributes = "";
			$this->student_id->HrefValue = "";

			// title
			$this->title->LinkCustomAttributes = "";
			$this->title->HrefValue = "";

			// description
			$this->description->LinkCustomAttributes = "";
			$this->description->HrefValue = "";

			// amount
			$this->amount->LinkCustomAttributes = "";
			$this->amount->HrefValue = "";

			// amount_paid
			$this->amount_paid->LinkCustomAttributes = "";
			$this->amount_paid->HrefValue = "";

			// due
			$this->due->LinkCustomAttributes = "";
			$this->due->HrefValue = "";

			// creation_timestamp
			$this->creation_timestamp->LinkCustomAttributes = "";
			$this->creation_timestamp->HrefValue = "";

			// payment_timestamp
			$this->payment_timestamp->LinkCustomAttributes = "";
			$this->payment_timestamp->HrefValue = "";

			// payment_method
			$this->payment_method->LinkCustomAttributes = "";
			$this->payment_method->HrefValue = "";

			// payment_details
			$this->payment_details->LinkCustomAttributes = "";
			$this->payment_details->HrefValue = "";

			// status
			$this->status->LinkCustomAttributes = "";
			$this->status->HrefValue = "";
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
		if (!$this->student_id->FldIsDetailKey && !is_null($this->student_id->FormValue) && $this->student_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->student_id->FldCaption(), $this->student_id->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->student_id->FormValue)) {
			ew_AddMessage($gsFormError, $this->student_id->FldErrMsg());
		}
		if (!$this->title->FldIsDetailKey && !is_null($this->title->FormValue) && $this->title->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->title->FldCaption(), $this->title->ReqErrMsg));
		}
		if (!$this->description->FldIsDetailKey && !is_null($this->description->FormValue) && $this->description->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->description->FldCaption(), $this->description->ReqErrMsg));
		}
		if (!$this->amount->FldIsDetailKey && !is_null($this->amount->FormValue) && $this->amount->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->amount->FldCaption(), $this->amount->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->amount->FormValue)) {
			ew_AddMessage($gsFormError, $this->amount->FldErrMsg());
		}
		if (!$this->amount_paid->FldIsDetailKey && !is_null($this->amount_paid->FormValue) && $this->amount_paid->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->amount_paid->FldCaption(), $this->amount_paid->ReqErrMsg));
		}
		if (!$this->due->FldIsDetailKey && !is_null($this->due->FormValue) && $this->due->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->due->FldCaption(), $this->due->ReqErrMsg));
		}
		if (!$this->creation_timestamp->FldIsDetailKey && !is_null($this->creation_timestamp->FormValue) && $this->creation_timestamp->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->creation_timestamp->FldCaption(), $this->creation_timestamp->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->creation_timestamp->FormValue)) {
			ew_AddMessage($gsFormError, $this->creation_timestamp->FldErrMsg());
		}
		if (!$this->payment_timestamp->FldIsDetailKey && !is_null($this->payment_timestamp->FormValue) && $this->payment_timestamp->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->payment_timestamp->FldCaption(), $this->payment_timestamp->ReqErrMsg));
		}
		if (!$this->payment_method->FldIsDetailKey && !is_null($this->payment_method->FormValue) && $this->payment_method->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->payment_method->FldCaption(), $this->payment_method->ReqErrMsg));
		}
		if (!$this->payment_details->FldIsDetailKey && !is_null($this->payment_details->FormValue) && $this->payment_details->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->payment_details->FldCaption(), $this->payment_details->ReqErrMsg));
		}
		if (!$this->status->FldIsDetailKey && !is_null($this->status->FormValue) && $this->status->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->status->FldCaption(), $this->status->ReqErrMsg));
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

	// Update record based on key values
	function EditRow() {
		global $Security, $Language;
		$sFilter = $this->KeyFilter();
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$conn = &$this->Connection();
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE)
			return FALSE;
		if ($rs->EOF) {
			$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
			$EditRow = FALSE; // Update Failed
		} else {

			// Save old values
			$rsold = &$rs->fields;
			$this->LoadDbValues($rsold);
			$rsnew = array();

			// student_id
			$this->student_id->SetDbValueDef($rsnew, $this->student_id->CurrentValue, 0, $this->student_id->ReadOnly);

			// title
			$this->title->SetDbValueDef($rsnew, $this->title->CurrentValue, "", $this->title->ReadOnly);

			// description
			$this->description->SetDbValueDef($rsnew, $this->description->CurrentValue, "", $this->description->ReadOnly);

			// amount
			$this->amount->SetDbValueDef($rsnew, $this->amount->CurrentValue, 0, $this->amount->ReadOnly);

			// amount_paid
			$this->amount_paid->SetDbValueDef($rsnew, $this->amount_paid->CurrentValue, "", $this->amount_paid->ReadOnly);

			// due
			$this->due->SetDbValueDef($rsnew, $this->due->CurrentValue, "", $this->due->ReadOnly);

			// creation_timestamp
			$this->creation_timestamp->SetDbValueDef($rsnew, $this->creation_timestamp->CurrentValue, 0, $this->creation_timestamp->ReadOnly);

			// payment_timestamp
			$this->payment_timestamp->SetDbValueDef($rsnew, $this->payment_timestamp->CurrentValue, "", $this->payment_timestamp->ReadOnly);

			// payment_method
			$this->payment_method->SetDbValueDef($rsnew, $this->payment_method->CurrentValue, "", $this->payment_method->ReadOnly);

			// payment_details
			$this->payment_details->SetDbValueDef($rsnew, $this->payment_details->CurrentValue, "", $this->payment_details->ReadOnly);

			// status
			$this->status->SetDbValueDef($rsnew, $this->status->CurrentValue, "", $this->status->ReadOnly);

			// Call Row Updating event
			$bUpdateRow = $this->Row_Updating($rsold, $rsnew);
			if ($bUpdateRow) {
				$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
				if (count($rsnew) > 0)
					$EditRow = $this->Update($rsnew, "", $rsold);
				else
					$EditRow = TRUE; // No field to update
				$conn->raiseErrorFn = '';
				if ($EditRow) {
				}
			} else {
				if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

					// Use the message, do nothing
				} elseif ($this->CancelMessage <> "") {
					$this->setFailureMessage($this->CancelMessage);
					$this->CancelMessage = "";
				} else {
					$this->setFailureMessage($Language->Phrase("UpdateCancelled"));
				}
				$EditRow = FALSE;
			}
		}

		// Call Row_Updated event
		if ($EditRow)
			$this->Row_Updated($rsold, $rsnew);
		$rs->Close();
		return $EditRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("invoicelist.php"), "", $this->TableVar, TRUE);
		$PageId = "edit";
		$Breadcrumb->Add("edit", $PageId, $url);
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
if (!isset($invoice_edit)) $invoice_edit = new cinvoice_edit();

// Page init
$invoice_edit->Page_Init();

// Page main
$invoice_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$invoice_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = finvoiceedit = new ew_Form("finvoiceedit", "edit");

// Validate form
finvoiceedit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_student_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $invoice->student_id->FldCaption(), $invoice->student_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_student_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($invoice->student_id->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_title");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $invoice->title->FldCaption(), $invoice->title->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_description");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $invoice->description->FldCaption(), $invoice->description->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_amount");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $invoice->amount->FldCaption(), $invoice->amount->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_amount");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($invoice->amount->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_amount_paid");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $invoice->amount_paid->FldCaption(), $invoice->amount_paid->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_due");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $invoice->due->FldCaption(), $invoice->due->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_creation_timestamp");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $invoice->creation_timestamp->FldCaption(), $invoice->creation_timestamp->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_creation_timestamp");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($invoice->creation_timestamp->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_payment_timestamp");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $invoice->payment_timestamp->FldCaption(), $invoice->payment_timestamp->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_payment_method");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $invoice->payment_method->FldCaption(), $invoice->payment_method->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_payment_details");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $invoice->payment_details->FldCaption(), $invoice->payment_details->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_status");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $invoice->status->FldCaption(), $invoice->status->ReqErrMsg)) ?>");

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
finvoiceedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
finvoiceedit.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $invoice_edit->ShowPageHeader(); ?>
<?php
$invoice_edit->ShowMessage();
?>
<form name="finvoiceedit" id="finvoiceedit" class="<?php echo $invoice_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($invoice_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $invoice_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="invoice">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<input type="hidden" name="modal" value="<?php echo intval($invoice_edit->IsModal) ?>">
<div class="ewEditDiv"><!-- page* -->
<?php if ($invoice->invoice_id->Visible) { // invoice_id ?>
	<div id="r_invoice_id" class="form-group">
		<label id="elh_invoice_invoice_id" class="<?php echo $invoice_edit->LeftColumnClass ?>"><?php echo $invoice->invoice_id->FldCaption() ?></label>
		<div class="<?php echo $invoice_edit->RightColumnClass ?>"><div<?php echo $invoice->invoice_id->CellAttributes() ?>>
<span id="el_invoice_invoice_id">
<span<?php echo $invoice->invoice_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $invoice->invoice_id->EditValue ?></p></span>
</span>
<input type="hidden" data-table="invoice" data-field="x_invoice_id" name="x_invoice_id" id="x_invoice_id" value="<?php echo ew_HtmlEncode($invoice->invoice_id->CurrentValue) ?>">
<?php echo $invoice->invoice_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($invoice->student_id->Visible) { // student_id ?>
	<div id="r_student_id" class="form-group">
		<label id="elh_invoice_student_id" for="x_student_id" class="<?php echo $invoice_edit->LeftColumnClass ?>"><?php echo $invoice->student_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $invoice_edit->RightColumnClass ?>"><div<?php echo $invoice->student_id->CellAttributes() ?>>
<span id="el_invoice_student_id">
<input type="text" data-table="invoice" data-field="x_student_id" name="x_student_id" id="x_student_id" size="30" placeholder="<?php echo ew_HtmlEncode($invoice->student_id->getPlaceHolder()) ?>" value="<?php echo $invoice->student_id->EditValue ?>"<?php echo $invoice->student_id->EditAttributes() ?>>
</span>
<?php echo $invoice->student_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($invoice->title->Visible) { // title ?>
	<div id="r_title" class="form-group">
		<label id="elh_invoice_title" for="x_title" class="<?php echo $invoice_edit->LeftColumnClass ?>"><?php echo $invoice->title->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $invoice_edit->RightColumnClass ?>"><div<?php echo $invoice->title->CellAttributes() ?>>
<span id="el_invoice_title">
<textarea data-table="invoice" data-field="x_title" name="x_title" id="x_title" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($invoice->title->getPlaceHolder()) ?>"<?php echo $invoice->title->EditAttributes() ?>><?php echo $invoice->title->EditValue ?></textarea>
</span>
<?php echo $invoice->title->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($invoice->description->Visible) { // description ?>
	<div id="r_description" class="form-group">
		<label id="elh_invoice_description" for="x_description" class="<?php echo $invoice_edit->LeftColumnClass ?>"><?php echo $invoice->description->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $invoice_edit->RightColumnClass ?>"><div<?php echo $invoice->description->CellAttributes() ?>>
<span id="el_invoice_description">
<textarea data-table="invoice" data-field="x_description" name="x_description" id="x_description" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($invoice->description->getPlaceHolder()) ?>"<?php echo $invoice->description->EditAttributes() ?>><?php echo $invoice->description->EditValue ?></textarea>
</span>
<?php echo $invoice->description->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($invoice->amount->Visible) { // amount ?>
	<div id="r_amount" class="form-group">
		<label id="elh_invoice_amount" for="x_amount" class="<?php echo $invoice_edit->LeftColumnClass ?>"><?php echo $invoice->amount->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $invoice_edit->RightColumnClass ?>"><div<?php echo $invoice->amount->CellAttributes() ?>>
<span id="el_invoice_amount">
<input type="text" data-table="invoice" data-field="x_amount" name="x_amount" id="x_amount" size="30" placeholder="<?php echo ew_HtmlEncode($invoice->amount->getPlaceHolder()) ?>" value="<?php echo $invoice->amount->EditValue ?>"<?php echo $invoice->amount->EditAttributes() ?>>
</span>
<?php echo $invoice->amount->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($invoice->amount_paid->Visible) { // amount_paid ?>
	<div id="r_amount_paid" class="form-group">
		<label id="elh_invoice_amount_paid" for="x_amount_paid" class="<?php echo $invoice_edit->LeftColumnClass ?>"><?php echo $invoice->amount_paid->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $invoice_edit->RightColumnClass ?>"><div<?php echo $invoice->amount_paid->CellAttributes() ?>>
<span id="el_invoice_amount_paid">
<textarea data-table="invoice" data-field="x_amount_paid" name="x_amount_paid" id="x_amount_paid" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($invoice->amount_paid->getPlaceHolder()) ?>"<?php echo $invoice->amount_paid->EditAttributes() ?>><?php echo $invoice->amount_paid->EditValue ?></textarea>
</span>
<?php echo $invoice->amount_paid->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($invoice->due->Visible) { // due ?>
	<div id="r_due" class="form-group">
		<label id="elh_invoice_due" for="x_due" class="<?php echo $invoice_edit->LeftColumnClass ?>"><?php echo $invoice->due->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $invoice_edit->RightColumnClass ?>"><div<?php echo $invoice->due->CellAttributes() ?>>
<span id="el_invoice_due">
<textarea data-table="invoice" data-field="x_due" name="x_due" id="x_due" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($invoice->due->getPlaceHolder()) ?>"<?php echo $invoice->due->EditAttributes() ?>><?php echo $invoice->due->EditValue ?></textarea>
</span>
<?php echo $invoice->due->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($invoice->creation_timestamp->Visible) { // creation_timestamp ?>
	<div id="r_creation_timestamp" class="form-group">
		<label id="elh_invoice_creation_timestamp" for="x_creation_timestamp" class="<?php echo $invoice_edit->LeftColumnClass ?>"><?php echo $invoice->creation_timestamp->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $invoice_edit->RightColumnClass ?>"><div<?php echo $invoice->creation_timestamp->CellAttributes() ?>>
<span id="el_invoice_creation_timestamp">
<input type="text" data-table="invoice" data-field="x_creation_timestamp" name="x_creation_timestamp" id="x_creation_timestamp" size="30" placeholder="<?php echo ew_HtmlEncode($invoice->creation_timestamp->getPlaceHolder()) ?>" value="<?php echo $invoice->creation_timestamp->EditValue ?>"<?php echo $invoice->creation_timestamp->EditAttributes() ?>>
</span>
<?php echo $invoice->creation_timestamp->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($invoice->payment_timestamp->Visible) { // payment_timestamp ?>
	<div id="r_payment_timestamp" class="form-group">
		<label id="elh_invoice_payment_timestamp" for="x_payment_timestamp" class="<?php echo $invoice_edit->LeftColumnClass ?>"><?php echo $invoice->payment_timestamp->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $invoice_edit->RightColumnClass ?>"><div<?php echo $invoice->payment_timestamp->CellAttributes() ?>>
<span id="el_invoice_payment_timestamp">
<textarea data-table="invoice" data-field="x_payment_timestamp" name="x_payment_timestamp" id="x_payment_timestamp" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($invoice->payment_timestamp->getPlaceHolder()) ?>"<?php echo $invoice->payment_timestamp->EditAttributes() ?>><?php echo $invoice->payment_timestamp->EditValue ?></textarea>
</span>
<?php echo $invoice->payment_timestamp->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($invoice->payment_method->Visible) { // payment_method ?>
	<div id="r_payment_method" class="form-group">
		<label id="elh_invoice_payment_method" for="x_payment_method" class="<?php echo $invoice_edit->LeftColumnClass ?>"><?php echo $invoice->payment_method->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $invoice_edit->RightColumnClass ?>"><div<?php echo $invoice->payment_method->CellAttributes() ?>>
<span id="el_invoice_payment_method">
<textarea data-table="invoice" data-field="x_payment_method" name="x_payment_method" id="x_payment_method" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($invoice->payment_method->getPlaceHolder()) ?>"<?php echo $invoice->payment_method->EditAttributes() ?>><?php echo $invoice->payment_method->EditValue ?></textarea>
</span>
<?php echo $invoice->payment_method->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($invoice->payment_details->Visible) { // payment_details ?>
	<div id="r_payment_details" class="form-group">
		<label id="elh_invoice_payment_details" for="x_payment_details" class="<?php echo $invoice_edit->LeftColumnClass ?>"><?php echo $invoice->payment_details->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $invoice_edit->RightColumnClass ?>"><div<?php echo $invoice->payment_details->CellAttributes() ?>>
<span id="el_invoice_payment_details">
<textarea data-table="invoice" data-field="x_payment_details" name="x_payment_details" id="x_payment_details" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($invoice->payment_details->getPlaceHolder()) ?>"<?php echo $invoice->payment_details->EditAttributes() ?>><?php echo $invoice->payment_details->EditValue ?></textarea>
</span>
<?php echo $invoice->payment_details->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($invoice->status->Visible) { // status ?>
	<div id="r_status" class="form-group">
		<label id="elh_invoice_status" for="x_status" class="<?php echo $invoice_edit->LeftColumnClass ?>"><?php echo $invoice->status->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $invoice_edit->RightColumnClass ?>"><div<?php echo $invoice->status->CellAttributes() ?>>
<span id="el_invoice_status">
<textarea data-table="invoice" data-field="x_status" name="x_status" id="x_status" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($invoice->status->getPlaceHolder()) ?>"<?php echo $invoice->status->EditAttributes() ?>><?php echo $invoice->status->EditValue ?></textarea>
</span>
<?php echo $invoice->status->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div><!-- /page* -->
<?php if (!$invoice_edit->IsModal) { ?>
<div class="form-group"><!-- buttons .form-group -->
	<div class="<?php echo $invoice_edit->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $invoice_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div><!-- /buttons offset -->
</div><!-- /buttons .form-group -->
<?php } ?>
</form>
<script type="text/javascript">
finvoiceedit.Init();
</script>
<?php
$invoice_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$invoice_edit->Page_Terminate();
?>
