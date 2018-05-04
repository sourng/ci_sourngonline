<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "message_threadinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$message_thread_edit = NULL; // Initialize page object first

class cmessage_thread_edit extends cmessage_thread {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = '{1F40EE59-17A4-45D3-96B6-AA2082B3D64A}';

	// Table name
	var $TableName = 'message_thread';

	// Page object name
	var $PageObjName = 'message_thread_edit';

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

		// Table object (message_thread)
		if (!isset($GLOBALS["message_thread"]) || get_class($GLOBALS["message_thread"]) == "cmessage_thread") {
			$GLOBALS["message_thread"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["message_thread"];
		}

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'message_thread', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("message_threadlist.php"));
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
		$this->message_thread_id->SetVisibility();
		if ($this->IsAdd() || $this->IsCopy() || $this->IsGridAdd())
			$this->message_thread_id->Visible = FALSE;
		$this->message_thread_code->SetVisibility();
		$this->sender->SetVisibility();
		$this->reciever->SetVisibility();
		$this->last_message_timestamp->SetVisibility();

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
		global $EW_EXPORT, $message_thread;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($message_thread);
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
					if ($pageName == "message_threadview.php")
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
			if ($objForm->HasValue("x_message_thread_id")) {
				$this->message_thread_id->setFormValue($objForm->GetValue("x_message_thread_id"));
			}
		} else {
			$this->CurrentAction = "I"; // Default action is display

			// Load key from QueryString
			$loadByQuery = FALSE;
			if (isset($_GET["message_thread_id"])) {
				$this->message_thread_id->setQueryStringValue($_GET["message_thread_id"]);
				$loadByQuery = TRUE;
			} else {
				$this->message_thread_id->CurrentValue = NULL;
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
					$this->Page_Terminate("message_threadlist.php"); // No matching record, return to list
				}
				break;
			Case "U": // Update
				$sReturnUrl = $this->getReturnUrl();
				if (ew_GetPageName($sReturnUrl) == "message_threadlist.php")
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
		if (!$this->message_thread_id->FldIsDetailKey)
			$this->message_thread_id->setFormValue($objForm->GetValue("x_message_thread_id"));
		if (!$this->message_thread_code->FldIsDetailKey) {
			$this->message_thread_code->setFormValue($objForm->GetValue("x_message_thread_code"));
		}
		if (!$this->sender->FldIsDetailKey) {
			$this->sender->setFormValue($objForm->GetValue("x_sender"));
		}
		if (!$this->reciever->FldIsDetailKey) {
			$this->reciever->setFormValue($objForm->GetValue("x_reciever"));
		}
		if (!$this->last_message_timestamp->FldIsDetailKey) {
			$this->last_message_timestamp->setFormValue($objForm->GetValue("x_last_message_timestamp"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->message_thread_id->CurrentValue = $this->message_thread_id->FormValue;
		$this->message_thread_code->CurrentValue = $this->message_thread_code->FormValue;
		$this->sender->CurrentValue = $this->sender->FormValue;
		$this->reciever->CurrentValue = $this->reciever->FormValue;
		$this->last_message_timestamp->CurrentValue = $this->last_message_timestamp->FormValue;
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
		$this->message_thread_id->setDbValue($row['message_thread_id']);
		$this->message_thread_code->setDbValue($row['message_thread_code']);
		$this->sender->setDbValue($row['sender']);
		$this->reciever->setDbValue($row['reciever']);
		$this->last_message_timestamp->setDbValue($row['last_message_timestamp']);
	}

	// Return a row with default values
	function NewRow() {
		$row = array();
		$row['message_thread_id'] = NULL;
		$row['message_thread_code'] = NULL;
		$row['sender'] = NULL;
		$row['reciever'] = NULL;
		$row['last_message_timestamp'] = NULL;
		return $row;
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF)
			return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->message_thread_id->DbValue = $row['message_thread_id'];
		$this->message_thread_code->DbValue = $row['message_thread_code'];
		$this->sender->DbValue = $row['sender'];
		$this->reciever->DbValue = $row['reciever'];
		$this->last_message_timestamp->DbValue = $row['last_message_timestamp'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("message_thread_id")) <> "")
			$this->message_thread_id->CurrentValue = $this->getKey("message_thread_id"); // message_thread_id
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
		// message_thread_id
		// message_thread_code
		// sender
		// reciever
		// last_message_timestamp

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// message_thread_id
		$this->message_thread_id->ViewValue = $this->message_thread_id->CurrentValue;
		$this->message_thread_id->ViewCustomAttributes = "";

		// message_thread_code
		$this->message_thread_code->ViewValue = $this->message_thread_code->CurrentValue;
		$this->message_thread_code->ViewCustomAttributes = "";

		// sender
		$this->sender->ViewValue = $this->sender->CurrentValue;
		$this->sender->ViewCustomAttributes = "";

		// reciever
		$this->reciever->ViewValue = $this->reciever->CurrentValue;
		$this->reciever->ViewCustomAttributes = "";

		// last_message_timestamp
		$this->last_message_timestamp->ViewValue = $this->last_message_timestamp->CurrentValue;
		$this->last_message_timestamp->ViewCustomAttributes = "";

			// message_thread_id
			$this->message_thread_id->LinkCustomAttributes = "";
			$this->message_thread_id->HrefValue = "";
			$this->message_thread_id->TooltipValue = "";

			// message_thread_code
			$this->message_thread_code->LinkCustomAttributes = "";
			$this->message_thread_code->HrefValue = "";
			$this->message_thread_code->TooltipValue = "";

			// sender
			$this->sender->LinkCustomAttributes = "";
			$this->sender->HrefValue = "";
			$this->sender->TooltipValue = "";

			// reciever
			$this->reciever->LinkCustomAttributes = "";
			$this->reciever->HrefValue = "";
			$this->reciever->TooltipValue = "";

			// last_message_timestamp
			$this->last_message_timestamp->LinkCustomAttributes = "";
			$this->last_message_timestamp->HrefValue = "";
			$this->last_message_timestamp->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// message_thread_id
			$this->message_thread_id->EditAttrs["class"] = "form-control";
			$this->message_thread_id->EditCustomAttributes = "";
			$this->message_thread_id->EditValue = $this->message_thread_id->CurrentValue;
			$this->message_thread_id->ViewCustomAttributes = "";

			// message_thread_code
			$this->message_thread_code->EditAttrs["class"] = "form-control";
			$this->message_thread_code->EditCustomAttributes = "";
			$this->message_thread_code->EditValue = ew_HtmlEncode($this->message_thread_code->CurrentValue);
			$this->message_thread_code->PlaceHolder = ew_RemoveHtml($this->message_thread_code->FldCaption());

			// sender
			$this->sender->EditAttrs["class"] = "form-control";
			$this->sender->EditCustomAttributes = "";
			$this->sender->EditValue = ew_HtmlEncode($this->sender->CurrentValue);
			$this->sender->PlaceHolder = ew_RemoveHtml($this->sender->FldCaption());

			// reciever
			$this->reciever->EditAttrs["class"] = "form-control";
			$this->reciever->EditCustomAttributes = "";
			$this->reciever->EditValue = ew_HtmlEncode($this->reciever->CurrentValue);
			$this->reciever->PlaceHolder = ew_RemoveHtml($this->reciever->FldCaption());

			// last_message_timestamp
			$this->last_message_timestamp->EditAttrs["class"] = "form-control";
			$this->last_message_timestamp->EditCustomAttributes = "";
			$this->last_message_timestamp->EditValue = ew_HtmlEncode($this->last_message_timestamp->CurrentValue);
			$this->last_message_timestamp->PlaceHolder = ew_RemoveHtml($this->last_message_timestamp->FldCaption());

			// Edit refer script
			// message_thread_id

			$this->message_thread_id->LinkCustomAttributes = "";
			$this->message_thread_id->HrefValue = "";

			// message_thread_code
			$this->message_thread_code->LinkCustomAttributes = "";
			$this->message_thread_code->HrefValue = "";

			// sender
			$this->sender->LinkCustomAttributes = "";
			$this->sender->HrefValue = "";

			// reciever
			$this->reciever->LinkCustomAttributes = "";
			$this->reciever->HrefValue = "";

			// last_message_timestamp
			$this->last_message_timestamp->LinkCustomAttributes = "";
			$this->last_message_timestamp->HrefValue = "";
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
		if (!$this->message_thread_code->FldIsDetailKey && !is_null($this->message_thread_code->FormValue) && $this->message_thread_code->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->message_thread_code->FldCaption(), $this->message_thread_code->ReqErrMsg));
		}
		if (!$this->sender->FldIsDetailKey && !is_null($this->sender->FormValue) && $this->sender->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->sender->FldCaption(), $this->sender->ReqErrMsg));
		}
		if (!$this->reciever->FldIsDetailKey && !is_null($this->reciever->FormValue) && $this->reciever->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->reciever->FldCaption(), $this->reciever->ReqErrMsg));
		}
		if (!$this->last_message_timestamp->FldIsDetailKey && !is_null($this->last_message_timestamp->FormValue) && $this->last_message_timestamp->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->last_message_timestamp->FldCaption(), $this->last_message_timestamp->ReqErrMsg));
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

			// message_thread_code
			$this->message_thread_code->SetDbValueDef($rsnew, $this->message_thread_code->CurrentValue, "", $this->message_thread_code->ReadOnly);

			// sender
			$this->sender->SetDbValueDef($rsnew, $this->sender->CurrentValue, "", $this->sender->ReadOnly);

			// reciever
			$this->reciever->SetDbValueDef($rsnew, $this->reciever->CurrentValue, "", $this->reciever->ReadOnly);

			// last_message_timestamp
			$this->last_message_timestamp->SetDbValueDef($rsnew, $this->last_message_timestamp->CurrentValue, "", $this->last_message_timestamp->ReadOnly);

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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("message_threadlist.php"), "", $this->TableVar, TRUE);
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
if (!isset($message_thread_edit)) $message_thread_edit = new cmessage_thread_edit();

// Page init
$message_thread_edit->Page_Init();

// Page main
$message_thread_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$message_thread_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = fmessage_threadedit = new ew_Form("fmessage_threadedit", "edit");

// Validate form
fmessage_threadedit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_message_thread_code");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $message_thread->message_thread_code->FldCaption(), $message_thread->message_thread_code->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_sender");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $message_thread->sender->FldCaption(), $message_thread->sender->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_reciever");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $message_thread->reciever->FldCaption(), $message_thread->reciever->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_last_message_timestamp");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $message_thread->last_message_timestamp->FldCaption(), $message_thread->last_message_timestamp->ReqErrMsg)) ?>");

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
fmessage_threadedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fmessage_threadedit.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $message_thread_edit->ShowPageHeader(); ?>
<?php
$message_thread_edit->ShowMessage();
?>
<form name="fmessage_threadedit" id="fmessage_threadedit" class="<?php echo $message_thread_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($message_thread_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $message_thread_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="message_thread">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<input type="hidden" name="modal" value="<?php echo intval($message_thread_edit->IsModal) ?>">
<div class="ewEditDiv"><!-- page* -->
<?php if ($message_thread->message_thread_id->Visible) { // message_thread_id ?>
	<div id="r_message_thread_id" class="form-group">
		<label id="elh_message_thread_message_thread_id" class="<?php echo $message_thread_edit->LeftColumnClass ?>"><?php echo $message_thread->message_thread_id->FldCaption() ?></label>
		<div class="<?php echo $message_thread_edit->RightColumnClass ?>"><div<?php echo $message_thread->message_thread_id->CellAttributes() ?>>
<span id="el_message_thread_message_thread_id">
<span<?php echo $message_thread->message_thread_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $message_thread->message_thread_id->EditValue ?></p></span>
</span>
<input type="hidden" data-table="message_thread" data-field="x_message_thread_id" name="x_message_thread_id" id="x_message_thread_id" value="<?php echo ew_HtmlEncode($message_thread->message_thread_id->CurrentValue) ?>">
<?php echo $message_thread->message_thread_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($message_thread->message_thread_code->Visible) { // message_thread_code ?>
	<div id="r_message_thread_code" class="form-group">
		<label id="elh_message_thread_message_thread_code" for="x_message_thread_code" class="<?php echo $message_thread_edit->LeftColumnClass ?>"><?php echo $message_thread->message_thread_code->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $message_thread_edit->RightColumnClass ?>"><div<?php echo $message_thread->message_thread_code->CellAttributes() ?>>
<span id="el_message_thread_message_thread_code">
<textarea data-table="message_thread" data-field="x_message_thread_code" name="x_message_thread_code" id="x_message_thread_code" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($message_thread->message_thread_code->getPlaceHolder()) ?>"<?php echo $message_thread->message_thread_code->EditAttributes() ?>><?php echo $message_thread->message_thread_code->EditValue ?></textarea>
</span>
<?php echo $message_thread->message_thread_code->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($message_thread->sender->Visible) { // sender ?>
	<div id="r_sender" class="form-group">
		<label id="elh_message_thread_sender" for="x_sender" class="<?php echo $message_thread_edit->LeftColumnClass ?>"><?php echo $message_thread->sender->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $message_thread_edit->RightColumnClass ?>"><div<?php echo $message_thread->sender->CellAttributes() ?>>
<span id="el_message_thread_sender">
<textarea data-table="message_thread" data-field="x_sender" name="x_sender" id="x_sender" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($message_thread->sender->getPlaceHolder()) ?>"<?php echo $message_thread->sender->EditAttributes() ?>><?php echo $message_thread->sender->EditValue ?></textarea>
</span>
<?php echo $message_thread->sender->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($message_thread->reciever->Visible) { // reciever ?>
	<div id="r_reciever" class="form-group">
		<label id="elh_message_thread_reciever" for="x_reciever" class="<?php echo $message_thread_edit->LeftColumnClass ?>"><?php echo $message_thread->reciever->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $message_thread_edit->RightColumnClass ?>"><div<?php echo $message_thread->reciever->CellAttributes() ?>>
<span id="el_message_thread_reciever">
<textarea data-table="message_thread" data-field="x_reciever" name="x_reciever" id="x_reciever" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($message_thread->reciever->getPlaceHolder()) ?>"<?php echo $message_thread->reciever->EditAttributes() ?>><?php echo $message_thread->reciever->EditValue ?></textarea>
</span>
<?php echo $message_thread->reciever->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($message_thread->last_message_timestamp->Visible) { // last_message_timestamp ?>
	<div id="r_last_message_timestamp" class="form-group">
		<label id="elh_message_thread_last_message_timestamp" for="x_last_message_timestamp" class="<?php echo $message_thread_edit->LeftColumnClass ?>"><?php echo $message_thread->last_message_timestamp->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $message_thread_edit->RightColumnClass ?>"><div<?php echo $message_thread->last_message_timestamp->CellAttributes() ?>>
<span id="el_message_thread_last_message_timestamp">
<textarea data-table="message_thread" data-field="x_last_message_timestamp" name="x_last_message_timestamp" id="x_last_message_timestamp" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($message_thread->last_message_timestamp->getPlaceHolder()) ?>"<?php echo $message_thread->last_message_timestamp->EditAttributes() ?>><?php echo $message_thread->last_message_timestamp->EditValue ?></textarea>
</span>
<?php echo $message_thread->last_message_timestamp->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div><!-- /page* -->
<?php if (!$message_thread_edit->IsModal) { ?>
<div class="form-group"><!-- buttons .form-group -->
	<div class="<?php echo $message_thread_edit->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $message_thread_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div><!-- /buttons offset -->
</div><!-- /buttons .form-group -->
<?php } ?>
</form>
<script type="text/javascript">
fmessage_threadedit.Init();
</script>
<?php
$message_thread_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$message_thread_edit->Page_Terminate();
?>
