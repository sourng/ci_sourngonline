<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "paymentinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$payment_edit = NULL; // Initialize page object first

class cpayment_edit extends cpayment {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = '{1F40EE59-17A4-45D3-96B6-AA2082B3D64A}';

	// Table name
	var $TableName = 'payment';

	// Page object name
	var $PageObjName = 'payment_edit';

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

		// Table object (payment)
		if (!isset($GLOBALS["payment"]) || get_class($GLOBALS["payment"]) == "cpayment") {
			$GLOBALS["payment"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["payment"];
		}

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'payment', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("paymentlist.php"));
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
		$this->payment_id->SetVisibility();
		if ($this->IsAdd() || $this->IsCopy() || $this->IsGridAdd())
			$this->payment_id->Visible = FALSE;
		$this->expense_category_id->SetVisibility();
		$this->title->SetVisibility();
		$this->payment_type->SetVisibility();
		$this->invoice_id->SetVisibility();
		$this->student_id->SetVisibility();
		$this->method->SetVisibility();
		$this->description->SetVisibility();
		$this->amount->SetVisibility();
		$this->timestamp->SetVisibility();

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
		global $EW_EXPORT, $payment;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($payment);
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
					if ($pageName == "paymentview.php")
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
			if ($objForm->HasValue("x_payment_id")) {
				$this->payment_id->setFormValue($objForm->GetValue("x_payment_id"));
			}
		} else {
			$this->CurrentAction = "I"; // Default action is display

			// Load key from QueryString
			$loadByQuery = FALSE;
			if (isset($_GET["payment_id"])) {
				$this->payment_id->setQueryStringValue($_GET["payment_id"]);
				$loadByQuery = TRUE;
			} else {
				$this->payment_id->CurrentValue = NULL;
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
					$this->Page_Terminate("paymentlist.php"); // No matching record, return to list
				}
				break;
			Case "U": // Update
				$sReturnUrl = $this->getReturnUrl();
				if (ew_GetPageName($sReturnUrl) == "paymentlist.php")
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
		if (!$this->payment_id->FldIsDetailKey)
			$this->payment_id->setFormValue($objForm->GetValue("x_payment_id"));
		if (!$this->expense_category_id->FldIsDetailKey) {
			$this->expense_category_id->setFormValue($objForm->GetValue("x_expense_category_id"));
		}
		if (!$this->title->FldIsDetailKey) {
			$this->title->setFormValue($objForm->GetValue("x_title"));
		}
		if (!$this->payment_type->FldIsDetailKey) {
			$this->payment_type->setFormValue($objForm->GetValue("x_payment_type"));
		}
		if (!$this->invoice_id->FldIsDetailKey) {
			$this->invoice_id->setFormValue($objForm->GetValue("x_invoice_id"));
		}
		if (!$this->student_id->FldIsDetailKey) {
			$this->student_id->setFormValue($objForm->GetValue("x_student_id"));
		}
		if (!$this->method->FldIsDetailKey) {
			$this->method->setFormValue($objForm->GetValue("x_method"));
		}
		if (!$this->description->FldIsDetailKey) {
			$this->description->setFormValue($objForm->GetValue("x_description"));
		}
		if (!$this->amount->FldIsDetailKey) {
			$this->amount->setFormValue($objForm->GetValue("x_amount"));
		}
		if (!$this->timestamp->FldIsDetailKey) {
			$this->timestamp->setFormValue($objForm->GetValue("x_timestamp"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->payment_id->CurrentValue = $this->payment_id->FormValue;
		$this->expense_category_id->CurrentValue = $this->expense_category_id->FormValue;
		$this->title->CurrentValue = $this->title->FormValue;
		$this->payment_type->CurrentValue = $this->payment_type->FormValue;
		$this->invoice_id->CurrentValue = $this->invoice_id->FormValue;
		$this->student_id->CurrentValue = $this->student_id->FormValue;
		$this->method->CurrentValue = $this->method->FormValue;
		$this->description->CurrentValue = $this->description->FormValue;
		$this->amount->CurrentValue = $this->amount->FormValue;
		$this->timestamp->CurrentValue = $this->timestamp->FormValue;
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
		$this->payment_id->setDbValue($row['payment_id']);
		$this->expense_category_id->setDbValue($row['expense_category_id']);
		$this->title->setDbValue($row['title']);
		$this->payment_type->setDbValue($row['payment_type']);
		$this->invoice_id->setDbValue($row['invoice_id']);
		$this->student_id->setDbValue($row['student_id']);
		$this->method->setDbValue($row['method']);
		$this->description->setDbValue($row['description']);
		$this->amount->setDbValue($row['amount']);
		$this->timestamp->setDbValue($row['timestamp']);
	}

	// Return a row with default values
	function NewRow() {
		$row = array();
		$row['payment_id'] = NULL;
		$row['expense_category_id'] = NULL;
		$row['title'] = NULL;
		$row['payment_type'] = NULL;
		$row['invoice_id'] = NULL;
		$row['student_id'] = NULL;
		$row['method'] = NULL;
		$row['description'] = NULL;
		$row['amount'] = NULL;
		$row['timestamp'] = NULL;
		return $row;
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF)
			return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->payment_id->DbValue = $row['payment_id'];
		$this->expense_category_id->DbValue = $row['expense_category_id'];
		$this->title->DbValue = $row['title'];
		$this->payment_type->DbValue = $row['payment_type'];
		$this->invoice_id->DbValue = $row['invoice_id'];
		$this->student_id->DbValue = $row['student_id'];
		$this->method->DbValue = $row['method'];
		$this->description->DbValue = $row['description'];
		$this->amount->DbValue = $row['amount'];
		$this->timestamp->DbValue = $row['timestamp'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("payment_id")) <> "")
			$this->payment_id->CurrentValue = $this->getKey("payment_id"); // payment_id
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
		// payment_id
		// expense_category_id
		// title
		// payment_type
		// invoice_id
		// student_id
		// method
		// description
		// amount
		// timestamp

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// payment_id
		$this->payment_id->ViewValue = $this->payment_id->CurrentValue;
		$this->payment_id->ViewCustomAttributes = "";

		// expense_category_id
		$this->expense_category_id->ViewValue = $this->expense_category_id->CurrentValue;
		$this->expense_category_id->ViewCustomAttributes = "";

		// title
		$this->title->ViewValue = $this->title->CurrentValue;
		$this->title->ViewCustomAttributes = "";

		// payment_type
		$this->payment_type->ViewValue = $this->payment_type->CurrentValue;
		$this->payment_type->ViewCustomAttributes = "";

		// invoice_id
		$this->invoice_id->ViewValue = $this->invoice_id->CurrentValue;
		$this->invoice_id->ViewCustomAttributes = "";

		// student_id
		$this->student_id->ViewValue = $this->student_id->CurrentValue;
		$this->student_id->ViewCustomAttributes = "";

		// method
		$this->method->ViewValue = $this->method->CurrentValue;
		$this->method->ViewCustomAttributes = "";

		// description
		$this->description->ViewValue = $this->description->CurrentValue;
		$this->description->ViewCustomAttributes = "";

		// amount
		$this->amount->ViewValue = $this->amount->CurrentValue;
		$this->amount->ViewCustomAttributes = "";

		// timestamp
		$this->timestamp->ViewValue = $this->timestamp->CurrentValue;
		$this->timestamp->ViewCustomAttributes = "";

			// payment_id
			$this->payment_id->LinkCustomAttributes = "";
			$this->payment_id->HrefValue = "";
			$this->payment_id->TooltipValue = "";

			// expense_category_id
			$this->expense_category_id->LinkCustomAttributes = "";
			$this->expense_category_id->HrefValue = "";
			$this->expense_category_id->TooltipValue = "";

			// title
			$this->title->LinkCustomAttributes = "";
			$this->title->HrefValue = "";
			$this->title->TooltipValue = "";

			// payment_type
			$this->payment_type->LinkCustomAttributes = "";
			$this->payment_type->HrefValue = "";
			$this->payment_type->TooltipValue = "";

			// invoice_id
			$this->invoice_id->LinkCustomAttributes = "";
			$this->invoice_id->HrefValue = "";
			$this->invoice_id->TooltipValue = "";

			// student_id
			$this->student_id->LinkCustomAttributes = "";
			$this->student_id->HrefValue = "";
			$this->student_id->TooltipValue = "";

			// method
			$this->method->LinkCustomAttributes = "";
			$this->method->HrefValue = "";
			$this->method->TooltipValue = "";

			// description
			$this->description->LinkCustomAttributes = "";
			$this->description->HrefValue = "";
			$this->description->TooltipValue = "";

			// amount
			$this->amount->LinkCustomAttributes = "";
			$this->amount->HrefValue = "";
			$this->amount->TooltipValue = "";

			// timestamp
			$this->timestamp->LinkCustomAttributes = "";
			$this->timestamp->HrefValue = "";
			$this->timestamp->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// payment_id
			$this->payment_id->EditAttrs["class"] = "form-control";
			$this->payment_id->EditCustomAttributes = "";
			$this->payment_id->EditValue = $this->payment_id->CurrentValue;
			$this->payment_id->ViewCustomAttributes = "";

			// expense_category_id
			$this->expense_category_id->EditAttrs["class"] = "form-control";
			$this->expense_category_id->EditCustomAttributes = "";
			$this->expense_category_id->EditValue = ew_HtmlEncode($this->expense_category_id->CurrentValue);
			$this->expense_category_id->PlaceHolder = ew_RemoveHtml($this->expense_category_id->FldCaption());

			// title
			$this->title->EditAttrs["class"] = "form-control";
			$this->title->EditCustomAttributes = "";
			$this->title->EditValue = ew_HtmlEncode($this->title->CurrentValue);
			$this->title->PlaceHolder = ew_RemoveHtml($this->title->FldCaption());

			// payment_type
			$this->payment_type->EditAttrs["class"] = "form-control";
			$this->payment_type->EditCustomAttributes = "";
			$this->payment_type->EditValue = ew_HtmlEncode($this->payment_type->CurrentValue);
			$this->payment_type->PlaceHolder = ew_RemoveHtml($this->payment_type->FldCaption());

			// invoice_id
			$this->invoice_id->EditAttrs["class"] = "form-control";
			$this->invoice_id->EditCustomAttributes = "";
			$this->invoice_id->EditValue = ew_HtmlEncode($this->invoice_id->CurrentValue);
			$this->invoice_id->PlaceHolder = ew_RemoveHtml($this->invoice_id->FldCaption());

			// student_id
			$this->student_id->EditAttrs["class"] = "form-control";
			$this->student_id->EditCustomAttributes = "";
			$this->student_id->EditValue = ew_HtmlEncode($this->student_id->CurrentValue);
			$this->student_id->PlaceHolder = ew_RemoveHtml($this->student_id->FldCaption());

			// method
			$this->method->EditAttrs["class"] = "form-control";
			$this->method->EditCustomAttributes = "";
			$this->method->EditValue = ew_HtmlEncode($this->method->CurrentValue);
			$this->method->PlaceHolder = ew_RemoveHtml($this->method->FldCaption());

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

			// timestamp
			$this->timestamp->EditAttrs["class"] = "form-control";
			$this->timestamp->EditCustomAttributes = "";
			$this->timestamp->EditValue = ew_HtmlEncode($this->timestamp->CurrentValue);
			$this->timestamp->PlaceHolder = ew_RemoveHtml($this->timestamp->FldCaption());

			// Edit refer script
			// payment_id

			$this->payment_id->LinkCustomAttributes = "";
			$this->payment_id->HrefValue = "";

			// expense_category_id
			$this->expense_category_id->LinkCustomAttributes = "";
			$this->expense_category_id->HrefValue = "";

			// title
			$this->title->LinkCustomAttributes = "";
			$this->title->HrefValue = "";

			// payment_type
			$this->payment_type->LinkCustomAttributes = "";
			$this->payment_type->HrefValue = "";

			// invoice_id
			$this->invoice_id->LinkCustomAttributes = "";
			$this->invoice_id->HrefValue = "";

			// student_id
			$this->student_id->LinkCustomAttributes = "";
			$this->student_id->HrefValue = "";

			// method
			$this->method->LinkCustomAttributes = "";
			$this->method->HrefValue = "";

			// description
			$this->description->LinkCustomAttributes = "";
			$this->description->HrefValue = "";

			// amount
			$this->amount->LinkCustomAttributes = "";
			$this->amount->HrefValue = "";

			// timestamp
			$this->timestamp->LinkCustomAttributes = "";
			$this->timestamp->HrefValue = "";
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
		if (!$this->expense_category_id->FldIsDetailKey && !is_null($this->expense_category_id->FormValue) && $this->expense_category_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->expense_category_id->FldCaption(), $this->expense_category_id->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->expense_category_id->FormValue)) {
			ew_AddMessage($gsFormError, $this->expense_category_id->FldErrMsg());
		}
		if (!$this->title->FldIsDetailKey && !is_null($this->title->FormValue) && $this->title->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->title->FldCaption(), $this->title->ReqErrMsg));
		}
		if (!$this->payment_type->FldIsDetailKey && !is_null($this->payment_type->FormValue) && $this->payment_type->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->payment_type->FldCaption(), $this->payment_type->ReqErrMsg));
		}
		if (!$this->invoice_id->FldIsDetailKey && !is_null($this->invoice_id->FormValue) && $this->invoice_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->invoice_id->FldCaption(), $this->invoice_id->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->invoice_id->FormValue)) {
			ew_AddMessage($gsFormError, $this->invoice_id->FldErrMsg());
		}
		if (!$this->student_id->FldIsDetailKey && !is_null($this->student_id->FormValue) && $this->student_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->student_id->FldCaption(), $this->student_id->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->student_id->FormValue)) {
			ew_AddMessage($gsFormError, $this->student_id->FldErrMsg());
		}
		if (!$this->method->FldIsDetailKey && !is_null($this->method->FormValue) && $this->method->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->method->FldCaption(), $this->method->ReqErrMsg));
		}
		if (!$this->description->FldIsDetailKey && !is_null($this->description->FormValue) && $this->description->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->description->FldCaption(), $this->description->ReqErrMsg));
		}
		if (!$this->amount->FldIsDetailKey && !is_null($this->amount->FormValue) && $this->amount->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->amount->FldCaption(), $this->amount->ReqErrMsg));
		}
		if (!$this->timestamp->FldIsDetailKey && !is_null($this->timestamp->FormValue) && $this->timestamp->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->timestamp->FldCaption(), $this->timestamp->ReqErrMsg));
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

			// expense_category_id
			$this->expense_category_id->SetDbValueDef($rsnew, $this->expense_category_id->CurrentValue, 0, $this->expense_category_id->ReadOnly);

			// title
			$this->title->SetDbValueDef($rsnew, $this->title->CurrentValue, "", $this->title->ReadOnly);

			// payment_type
			$this->payment_type->SetDbValueDef($rsnew, $this->payment_type->CurrentValue, "", $this->payment_type->ReadOnly);

			// invoice_id
			$this->invoice_id->SetDbValueDef($rsnew, $this->invoice_id->CurrentValue, 0, $this->invoice_id->ReadOnly);

			// student_id
			$this->student_id->SetDbValueDef($rsnew, $this->student_id->CurrentValue, 0, $this->student_id->ReadOnly);

			// method
			$this->method->SetDbValueDef($rsnew, $this->method->CurrentValue, "", $this->method->ReadOnly);

			// description
			$this->description->SetDbValueDef($rsnew, $this->description->CurrentValue, "", $this->description->ReadOnly);

			// amount
			$this->amount->SetDbValueDef($rsnew, $this->amount->CurrentValue, "", $this->amount->ReadOnly);

			// timestamp
			$this->timestamp->SetDbValueDef($rsnew, $this->timestamp->CurrentValue, "", $this->timestamp->ReadOnly);

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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("paymentlist.php"), "", $this->TableVar, TRUE);
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
if (!isset($payment_edit)) $payment_edit = new cpayment_edit();

// Page init
$payment_edit->Page_Init();

// Page main
$payment_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$payment_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = fpaymentedit = new ew_Form("fpaymentedit", "edit");

// Validate form
fpaymentedit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_expense_category_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $payment->expense_category_id->FldCaption(), $payment->expense_category_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_expense_category_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($payment->expense_category_id->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_title");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $payment->title->FldCaption(), $payment->title->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_payment_type");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $payment->payment_type->FldCaption(), $payment->payment_type->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_invoice_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $payment->invoice_id->FldCaption(), $payment->invoice_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_invoice_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($payment->invoice_id->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_student_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $payment->student_id->FldCaption(), $payment->student_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_student_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($payment->student_id->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_method");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $payment->method->FldCaption(), $payment->method->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_description");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $payment->description->FldCaption(), $payment->description->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_amount");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $payment->amount->FldCaption(), $payment->amount->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_timestamp");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $payment->timestamp->FldCaption(), $payment->timestamp->ReqErrMsg)) ?>");

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
fpaymentedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fpaymentedit.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $payment_edit->ShowPageHeader(); ?>
<?php
$payment_edit->ShowMessage();
?>
<form name="fpaymentedit" id="fpaymentedit" class="<?php echo $payment_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($payment_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $payment_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="payment">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<input type="hidden" name="modal" value="<?php echo intval($payment_edit->IsModal) ?>">
<div class="ewEditDiv"><!-- page* -->
<?php if ($payment->payment_id->Visible) { // payment_id ?>
	<div id="r_payment_id" class="form-group">
		<label id="elh_payment_payment_id" class="<?php echo $payment_edit->LeftColumnClass ?>"><?php echo $payment->payment_id->FldCaption() ?></label>
		<div class="<?php echo $payment_edit->RightColumnClass ?>"><div<?php echo $payment->payment_id->CellAttributes() ?>>
<span id="el_payment_payment_id">
<span<?php echo $payment->payment_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $payment->payment_id->EditValue ?></p></span>
</span>
<input type="hidden" data-table="payment" data-field="x_payment_id" name="x_payment_id" id="x_payment_id" value="<?php echo ew_HtmlEncode($payment->payment_id->CurrentValue) ?>">
<?php echo $payment->payment_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($payment->expense_category_id->Visible) { // expense_category_id ?>
	<div id="r_expense_category_id" class="form-group">
		<label id="elh_payment_expense_category_id" for="x_expense_category_id" class="<?php echo $payment_edit->LeftColumnClass ?>"><?php echo $payment->expense_category_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $payment_edit->RightColumnClass ?>"><div<?php echo $payment->expense_category_id->CellAttributes() ?>>
<span id="el_payment_expense_category_id">
<input type="text" data-table="payment" data-field="x_expense_category_id" name="x_expense_category_id" id="x_expense_category_id" size="30" placeholder="<?php echo ew_HtmlEncode($payment->expense_category_id->getPlaceHolder()) ?>" value="<?php echo $payment->expense_category_id->EditValue ?>"<?php echo $payment->expense_category_id->EditAttributes() ?>>
</span>
<?php echo $payment->expense_category_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($payment->title->Visible) { // title ?>
	<div id="r_title" class="form-group">
		<label id="elh_payment_title" for="x_title" class="<?php echo $payment_edit->LeftColumnClass ?>"><?php echo $payment->title->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $payment_edit->RightColumnClass ?>"><div<?php echo $payment->title->CellAttributes() ?>>
<span id="el_payment_title">
<textarea data-table="payment" data-field="x_title" name="x_title" id="x_title" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($payment->title->getPlaceHolder()) ?>"<?php echo $payment->title->EditAttributes() ?>><?php echo $payment->title->EditValue ?></textarea>
</span>
<?php echo $payment->title->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($payment->payment_type->Visible) { // payment_type ?>
	<div id="r_payment_type" class="form-group">
		<label id="elh_payment_payment_type" for="x_payment_type" class="<?php echo $payment_edit->LeftColumnClass ?>"><?php echo $payment->payment_type->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $payment_edit->RightColumnClass ?>"><div<?php echo $payment->payment_type->CellAttributes() ?>>
<span id="el_payment_payment_type">
<textarea data-table="payment" data-field="x_payment_type" name="x_payment_type" id="x_payment_type" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($payment->payment_type->getPlaceHolder()) ?>"<?php echo $payment->payment_type->EditAttributes() ?>><?php echo $payment->payment_type->EditValue ?></textarea>
</span>
<?php echo $payment->payment_type->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($payment->invoice_id->Visible) { // invoice_id ?>
	<div id="r_invoice_id" class="form-group">
		<label id="elh_payment_invoice_id" for="x_invoice_id" class="<?php echo $payment_edit->LeftColumnClass ?>"><?php echo $payment->invoice_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $payment_edit->RightColumnClass ?>"><div<?php echo $payment->invoice_id->CellAttributes() ?>>
<span id="el_payment_invoice_id">
<input type="text" data-table="payment" data-field="x_invoice_id" name="x_invoice_id" id="x_invoice_id" size="30" placeholder="<?php echo ew_HtmlEncode($payment->invoice_id->getPlaceHolder()) ?>" value="<?php echo $payment->invoice_id->EditValue ?>"<?php echo $payment->invoice_id->EditAttributes() ?>>
</span>
<?php echo $payment->invoice_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($payment->student_id->Visible) { // student_id ?>
	<div id="r_student_id" class="form-group">
		<label id="elh_payment_student_id" for="x_student_id" class="<?php echo $payment_edit->LeftColumnClass ?>"><?php echo $payment->student_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $payment_edit->RightColumnClass ?>"><div<?php echo $payment->student_id->CellAttributes() ?>>
<span id="el_payment_student_id">
<input type="text" data-table="payment" data-field="x_student_id" name="x_student_id" id="x_student_id" size="30" placeholder="<?php echo ew_HtmlEncode($payment->student_id->getPlaceHolder()) ?>" value="<?php echo $payment->student_id->EditValue ?>"<?php echo $payment->student_id->EditAttributes() ?>>
</span>
<?php echo $payment->student_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($payment->method->Visible) { // method ?>
	<div id="r_method" class="form-group">
		<label id="elh_payment_method" for="x_method" class="<?php echo $payment_edit->LeftColumnClass ?>"><?php echo $payment->method->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $payment_edit->RightColumnClass ?>"><div<?php echo $payment->method->CellAttributes() ?>>
<span id="el_payment_method">
<textarea data-table="payment" data-field="x_method" name="x_method" id="x_method" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($payment->method->getPlaceHolder()) ?>"<?php echo $payment->method->EditAttributes() ?>><?php echo $payment->method->EditValue ?></textarea>
</span>
<?php echo $payment->method->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($payment->description->Visible) { // description ?>
	<div id="r_description" class="form-group">
		<label id="elh_payment_description" for="x_description" class="<?php echo $payment_edit->LeftColumnClass ?>"><?php echo $payment->description->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $payment_edit->RightColumnClass ?>"><div<?php echo $payment->description->CellAttributes() ?>>
<span id="el_payment_description">
<textarea data-table="payment" data-field="x_description" name="x_description" id="x_description" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($payment->description->getPlaceHolder()) ?>"<?php echo $payment->description->EditAttributes() ?>><?php echo $payment->description->EditValue ?></textarea>
</span>
<?php echo $payment->description->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($payment->amount->Visible) { // amount ?>
	<div id="r_amount" class="form-group">
		<label id="elh_payment_amount" for="x_amount" class="<?php echo $payment_edit->LeftColumnClass ?>"><?php echo $payment->amount->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $payment_edit->RightColumnClass ?>"><div<?php echo $payment->amount->CellAttributes() ?>>
<span id="el_payment_amount">
<textarea data-table="payment" data-field="x_amount" name="x_amount" id="x_amount" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($payment->amount->getPlaceHolder()) ?>"<?php echo $payment->amount->EditAttributes() ?>><?php echo $payment->amount->EditValue ?></textarea>
</span>
<?php echo $payment->amount->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($payment->timestamp->Visible) { // timestamp ?>
	<div id="r_timestamp" class="form-group">
		<label id="elh_payment_timestamp" for="x_timestamp" class="<?php echo $payment_edit->LeftColumnClass ?>"><?php echo $payment->timestamp->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $payment_edit->RightColumnClass ?>"><div<?php echo $payment->timestamp->CellAttributes() ?>>
<span id="el_payment_timestamp">
<textarea data-table="payment" data-field="x_timestamp" name="x_timestamp" id="x_timestamp" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($payment->timestamp->getPlaceHolder()) ?>"<?php echo $payment->timestamp->EditAttributes() ?>><?php echo $payment->timestamp->EditValue ?></textarea>
</span>
<?php echo $payment->timestamp->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div><!-- /page* -->
<?php if (!$payment_edit->IsModal) { ?>
<div class="form-group"><!-- buttons .form-group -->
	<div class="<?php echo $payment_edit->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $payment_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div><!-- /buttons offset -->
</div><!-- /buttons .form-group -->
<?php } ?>
</form>
<script type="text/javascript">
fpaymentedit.Init();
</script>
<?php
$payment_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$payment_edit->Page_Terminate();
?>
