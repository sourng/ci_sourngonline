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

$invoice_view = NULL; // Initialize page object first

class cinvoice_view extends cinvoice {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = '{1F40EE59-17A4-45D3-96B6-AA2082B3D64A}';

	// Table name
	var $TableName = 'invoice';

	// Page object name
	var $PageObjName = 'invoice_view';

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

	// Page URLs
	var $AddUrl;
	var $EditUrl;
	var $CopyUrl;
	var $DeleteUrl;
	var $ViewUrl;
	var $ListUrl;

	// Export URLs
	var $ExportPrintUrl;
	var $ExportHtmlUrl;
	var $ExportExcelUrl;
	var $ExportWordUrl;
	var $ExportXmlUrl;
	var $ExportCsvUrl;
	var $ExportPdfUrl;

	// Custom export
	var $ExportExcelCustom = FALSE;
	var $ExportWordCustom = FALSE;
	var $ExportPdfCustom = FALSE;
	var $ExportEmailCustom = FALSE;

	// Update URLs
	var $InlineAddUrl;
	var $InlineCopyUrl;
	var $InlineEditUrl;
	var $GridAddUrl;
	var $GridEditUrl;
	var $MultiDeleteUrl;
	var $MultiUpdateUrl;

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
		$KeyUrl = "";
		if (@$_GET["invoice_id"] <> "") {
			$this->RecKey["invoice_id"] = $_GET["invoice_id"];
			$KeyUrl .= "&amp;invoice_id=" . urlencode($this->RecKey["invoice_id"]);
		}
		$this->ExportPrintUrl = $this->PageUrl() . "export=print" . $KeyUrl;
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html" . $KeyUrl;
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel" . $KeyUrl;
		$this->ExportWordUrl = $this->PageUrl() . "export=word" . $KeyUrl;
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml" . $KeyUrl;
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv" . $KeyUrl;
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf" . $KeyUrl;

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'view', TRUE);

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

		// Export options
		$this->ExportOptions = new cListOptions();
		$this->ExportOptions->Tag = "div";
		$this->ExportOptions->TagClassName = "ewExportOption";

		// Other options
		$this->OtherOptions['action'] = new cListOptions();
		$this->OtherOptions['action']->Tag = "div";
		$this->OtherOptions['action']->TagClassName = "ewActionOption";
		$this->OtherOptions['detail'] = new cListOptions();
		$this->OtherOptions['detail']->Tag = "div";
		$this->OtherOptions['detail']->TagClassName = "ewDetailOption";
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
		if (!$Security->CanView()) {
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
	var $ExportOptions; // Export options
	var $OtherOptions = array(); // Other options
	var $DisplayRecs = 1;
	var $DbMasterFilter;
	var $DbDetailFilter;
	var $StartRec;
	var $StopRec;
	var $TotalRecs = 0;
	var $RecRange = 10;
	var $RecCnt;
	var $RecKey = array();
	var $IsModal = FALSE;
	var $Recordset;

	//
	// Page main
	//
	function Page_Main() {
		global $Language, $gbSkipHeaderFooter, $EW_EXPORT;

		// Check modal
		if ($this->IsModal)
			$gbSkipHeaderFooter = TRUE;
		$sReturnUrl = "";
		$bMatchRecord = FALSE;
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET["invoice_id"] <> "") {
				$this->invoice_id->setQueryStringValue($_GET["invoice_id"]);
				$this->RecKey["invoice_id"] = $this->invoice_id->QueryStringValue;
			} elseif (@$_POST["invoice_id"] <> "") {
				$this->invoice_id->setFormValue($_POST["invoice_id"]);
				$this->RecKey["invoice_id"] = $this->invoice_id->FormValue;
			} else {
				$sReturnUrl = "invoicelist.php"; // Return to list
			}

			// Get action
			$this->CurrentAction = "I"; // Display form
			switch ($this->CurrentAction) {
				case "I": // Get a record to display
					if (!$this->LoadRow()) { // Load record based on key
						if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
							$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
						$sReturnUrl = "invoicelist.php"; // No matching record, return to list
					}
			}
		} else {
			$sReturnUrl = "invoicelist.php"; // Not page request, return to list
		}
		if ($sReturnUrl <> "")
			$this->Page_Terminate($sReturnUrl);

		// Set up Breadcrumb
		if ($this->Export == "")
			$this->SetupBreadcrumb();

		// Render row
		$this->RowType = EW_ROWTYPE_VIEW;
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Set up other options
	function SetupOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
		$option = &$options["action"];

		// Add
		$item = &$option->Add("add");
		$addcaption = ew_HtmlTitle($Language->Phrase("ViewPageAddLink"));
		if ($this->IsModal) // Modal
			$item->Body = "<a class=\"ewAction ewAdd\" title=\"" . $addcaption . "\" data-caption=\"" . $addcaption . "\" href=\"javascript:void(0);\" onclick=\"ew_ModalDialogShow({lnk:this,url:'" . ew_HtmlEncode($this->AddUrl) . "'});\">" . $Language->Phrase("ViewPageAddLink") . "</a>";
		else
			$item->Body = "<a class=\"ewAction ewAdd\" title=\"" . $addcaption . "\" data-caption=\"" . $addcaption . "\" href=\"" . ew_HtmlEncode($this->AddUrl) . "\">" . $Language->Phrase("ViewPageAddLink") . "</a>";
		$item->Visible = ($this->AddUrl <> "" && $Security->IsLoggedIn());

		// Edit
		$item = &$option->Add("edit");
		$editcaption = ew_HtmlTitle($Language->Phrase("ViewPageEditLink"));
		if ($this->IsModal) // Modal
			$item->Body = "<a class=\"ewAction ewEdit\" title=\"" . $editcaption . "\" data-caption=\"" . $editcaption . "\" href=\"javascript:void(0);\" onclick=\"ew_ModalDialogShow({lnk:this,url:'" . ew_HtmlEncode($this->EditUrl) . "'});\">" . $Language->Phrase("ViewPageEditLink") . "</a>";
		else
			$item->Body = "<a class=\"ewAction ewEdit\" title=\"" . $editcaption . "\" data-caption=\"" . $editcaption . "\" href=\"" . ew_HtmlEncode($this->EditUrl) . "\">" . $Language->Phrase("ViewPageEditLink") . "</a>";
		$item->Visible = ($this->EditUrl <> "" && $Security->IsLoggedIn());

		// Copy
		$item = &$option->Add("copy");
		$copycaption = ew_HtmlTitle($Language->Phrase("ViewPageCopyLink"));
		if ($this->IsModal) // Modal
			$item->Body = "<a class=\"ewAction ewCopy\" title=\"" . $copycaption . "\" data-caption=\"" . $copycaption . "\" href=\"javascript:void(0);\" onclick=\"ew_ModalDialogShow({lnk:this,btn:'AddBtn',url:'" . ew_HtmlEncode($this->CopyUrl) . "'});\">" . $Language->Phrase("ViewPageCopyLink") . "</a>";
		else
			$item->Body = "<a class=\"ewAction ewCopy\" title=\"" . $copycaption . "\" data-caption=\"" . $copycaption . "\" href=\"" . ew_HtmlEncode($this->CopyUrl) . "\">" . $Language->Phrase("ViewPageCopyLink") . "</a>";
		$item->Visible = ($this->CopyUrl <> "" && $Security->IsLoggedIn());

		// Delete
		$item = &$option->Add("delete");
		if ($this->IsModal) // Handle as inline delete
			$item->Body = "<a onclick=\"return ew_ConfirmDelete(this);\" class=\"ewAction ewDelete\" title=\"" . ew_HtmlTitle($Language->Phrase("ViewPageDeleteLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("ViewPageDeleteLink")) . "\" href=\"" . ew_HtmlEncode(ew_UrlAddQuery($this->DeleteUrl, "a_delete=1")) . "\">" . $Language->Phrase("ViewPageDeleteLink") . "</a>";
		else
			$item->Body = "<a class=\"ewAction ewDelete\" title=\"" . ew_HtmlTitle($Language->Phrase("ViewPageDeleteLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("ViewPageDeleteLink")) . "\" href=\"" . ew_HtmlEncode($this->DeleteUrl) . "\">" . $Language->Phrase("ViewPageDeleteLink") . "</a>";
		$item->Visible = ($this->DeleteUrl <> "" && $Security->IsLoggedIn());

		// Set up action default
		$option = &$options["action"];
		$option->DropDownButtonPhrase = $Language->Phrase("ButtonActions");
		$option->UseImageAndText = TRUE;
		$option->UseDropDownButton = FALSE;
		$option->UseButtonGroup = TRUE;
		$item = &$option->Add($option->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;
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

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		$this->AddUrl = $this->GetAddUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();
		$this->ListUrl = $this->GetListUrl();
		$this->SetupOtherOptions();

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
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("invoicelist.php"), "", $this->TableVar, TRUE);
		$PageId = "view";
		$Breadcrumb->Add("view", $PageId, $url);
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

	// Page Exporting event
	// $this->ExportDoc = export document object
	function Page_Exporting() {

		//$this->ExportDoc->Text = "my header"; // Export header
		//return FALSE; // Return FALSE to skip default export and use Row_Export event

		return TRUE; // Return TRUE to use default export and skip Row_Export event
	}

	// Row Export event
	// $this->ExportDoc = export document object
	function Row_Export($rs) {

		//$this->ExportDoc->Text .= "my content"; // Build HTML with field value: $rs["MyField"] or $this->MyField->ViewValue
	}

	// Page Exported event
	// $this->ExportDoc = export document object
	function Page_Exported() {

		//$this->ExportDoc->Text .= "my footer"; // Export footer
		//echo $this->ExportDoc->Text;

	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($invoice_view)) $invoice_view = new cinvoice_view();

// Page init
$invoice_view->Page_Init();

// Page main
$invoice_view->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$invoice_view->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "view";
var CurrentForm = finvoiceview = new ew_Form("finvoiceview", "view");

// Form_CustomValidate event
finvoiceview.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
finvoiceview.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php $invoice_view->ExportOptions->Render("body") ?>
<?php
	foreach ($invoice_view->OtherOptions as &$option)
		$option->Render("body");
?>
<div class="clearfix"></div>
</div>
<?php $invoice_view->ShowPageHeader(); ?>
<?php
$invoice_view->ShowMessage();
?>
<form name="finvoiceview" id="finvoiceview" class="form-inline ewForm ewViewForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($invoice_view->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $invoice_view->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="invoice">
<input type="hidden" name="modal" value="<?php echo intval($invoice_view->IsModal) ?>">
<table class="table table-striped table-bordered table-hover table-condensed ewViewTable">
<?php if ($invoice->invoice_id->Visible) { // invoice_id ?>
	<tr id="r_invoice_id">
		<td class="col-sm-2"><span id="elh_invoice_invoice_id"><?php echo $invoice->invoice_id->FldCaption() ?></span></td>
		<td data-name="invoice_id"<?php echo $invoice->invoice_id->CellAttributes() ?>>
<span id="el_invoice_invoice_id">
<span<?php echo $invoice->invoice_id->ViewAttributes() ?>>
<?php echo $invoice->invoice_id->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($invoice->student_id->Visible) { // student_id ?>
	<tr id="r_student_id">
		<td class="col-sm-2"><span id="elh_invoice_student_id"><?php echo $invoice->student_id->FldCaption() ?></span></td>
		<td data-name="student_id"<?php echo $invoice->student_id->CellAttributes() ?>>
<span id="el_invoice_student_id">
<span<?php echo $invoice->student_id->ViewAttributes() ?>>
<?php echo $invoice->student_id->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($invoice->title->Visible) { // title ?>
	<tr id="r_title">
		<td class="col-sm-2"><span id="elh_invoice_title"><?php echo $invoice->title->FldCaption() ?></span></td>
		<td data-name="title"<?php echo $invoice->title->CellAttributes() ?>>
<span id="el_invoice_title">
<span<?php echo $invoice->title->ViewAttributes() ?>>
<?php echo $invoice->title->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($invoice->description->Visible) { // description ?>
	<tr id="r_description">
		<td class="col-sm-2"><span id="elh_invoice_description"><?php echo $invoice->description->FldCaption() ?></span></td>
		<td data-name="description"<?php echo $invoice->description->CellAttributes() ?>>
<span id="el_invoice_description">
<span<?php echo $invoice->description->ViewAttributes() ?>>
<?php echo $invoice->description->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($invoice->amount->Visible) { // amount ?>
	<tr id="r_amount">
		<td class="col-sm-2"><span id="elh_invoice_amount"><?php echo $invoice->amount->FldCaption() ?></span></td>
		<td data-name="amount"<?php echo $invoice->amount->CellAttributes() ?>>
<span id="el_invoice_amount">
<span<?php echo $invoice->amount->ViewAttributes() ?>>
<?php echo $invoice->amount->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($invoice->amount_paid->Visible) { // amount_paid ?>
	<tr id="r_amount_paid">
		<td class="col-sm-2"><span id="elh_invoice_amount_paid"><?php echo $invoice->amount_paid->FldCaption() ?></span></td>
		<td data-name="amount_paid"<?php echo $invoice->amount_paid->CellAttributes() ?>>
<span id="el_invoice_amount_paid">
<span<?php echo $invoice->amount_paid->ViewAttributes() ?>>
<?php echo $invoice->amount_paid->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($invoice->due->Visible) { // due ?>
	<tr id="r_due">
		<td class="col-sm-2"><span id="elh_invoice_due"><?php echo $invoice->due->FldCaption() ?></span></td>
		<td data-name="due"<?php echo $invoice->due->CellAttributes() ?>>
<span id="el_invoice_due">
<span<?php echo $invoice->due->ViewAttributes() ?>>
<?php echo $invoice->due->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($invoice->creation_timestamp->Visible) { // creation_timestamp ?>
	<tr id="r_creation_timestamp">
		<td class="col-sm-2"><span id="elh_invoice_creation_timestamp"><?php echo $invoice->creation_timestamp->FldCaption() ?></span></td>
		<td data-name="creation_timestamp"<?php echo $invoice->creation_timestamp->CellAttributes() ?>>
<span id="el_invoice_creation_timestamp">
<span<?php echo $invoice->creation_timestamp->ViewAttributes() ?>>
<?php echo $invoice->creation_timestamp->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($invoice->payment_timestamp->Visible) { // payment_timestamp ?>
	<tr id="r_payment_timestamp">
		<td class="col-sm-2"><span id="elh_invoice_payment_timestamp"><?php echo $invoice->payment_timestamp->FldCaption() ?></span></td>
		<td data-name="payment_timestamp"<?php echo $invoice->payment_timestamp->CellAttributes() ?>>
<span id="el_invoice_payment_timestamp">
<span<?php echo $invoice->payment_timestamp->ViewAttributes() ?>>
<?php echo $invoice->payment_timestamp->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($invoice->payment_method->Visible) { // payment_method ?>
	<tr id="r_payment_method">
		<td class="col-sm-2"><span id="elh_invoice_payment_method"><?php echo $invoice->payment_method->FldCaption() ?></span></td>
		<td data-name="payment_method"<?php echo $invoice->payment_method->CellAttributes() ?>>
<span id="el_invoice_payment_method">
<span<?php echo $invoice->payment_method->ViewAttributes() ?>>
<?php echo $invoice->payment_method->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($invoice->payment_details->Visible) { // payment_details ?>
	<tr id="r_payment_details">
		<td class="col-sm-2"><span id="elh_invoice_payment_details"><?php echo $invoice->payment_details->FldCaption() ?></span></td>
		<td data-name="payment_details"<?php echo $invoice->payment_details->CellAttributes() ?>>
<span id="el_invoice_payment_details">
<span<?php echo $invoice->payment_details->ViewAttributes() ?>>
<?php echo $invoice->payment_details->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($invoice->status->Visible) { // status ?>
	<tr id="r_status">
		<td class="col-sm-2"><span id="elh_invoice_status"><?php echo $invoice->status->FldCaption() ?></span></td>
		<td data-name="status"<?php echo $invoice->status->CellAttributes() ?>>
<span id="el_invoice_status">
<span<?php echo $invoice->status->ViewAttributes() ?>>
<?php echo $invoice->status->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
</form>
<script type="text/javascript">
finvoiceview.Init();
</script>
<?php
$invoice_view->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$invoice_view->Page_Terminate();
?>
