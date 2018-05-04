<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "messageinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$message_add = NULL; // Initialize page object first

class cmessage_add extends cmessage {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = '{1F40EE59-17A4-45D3-96B6-AA2082B3D64A}';

	// Table name
	var $TableName = 'message';

	// Page object name
	var $PageObjName = 'message_add';

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

		// Table object (message)
		if (!isset($GLOBALS["message"]) || get_class($GLOBALS["message"]) == "cmessage") {
			$GLOBALS["message"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["message"];
		}

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'message', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("messagelist.php"));
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
		$this->message_thread_code->SetVisibility();
		$this->message->SetVisibility();
		$this->sender->SetVisibility();
		$this->timestamp->SetVisibility();
		$this->read_status->SetVisibility();

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
		global $EW_EXPORT, $message;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($message);
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
					if ($pageName == "messageview.php")
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
			if (@$_GET["message_id"] != "") {
				$this->message_id->setQueryStringValue($_GET["message_id"]);
				$this->setKey("message_id", $this->message_id->CurrentValue); // Set up key
			} else {
				$this->setKey("message_id", ""); // Clear key
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
					$this->Page_Terminate("messagelist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "messagelist.php")
						$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to List page with correct master key if necessary
					elseif (ew_GetPageName($sReturnUrl) == "messageview.php")
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
		$this->message_id->CurrentValue = NULL;
		$this->message_id->OldValue = $this->message_id->CurrentValue;
		$this->message_thread_code->CurrentValue = NULL;
		$this->message_thread_code->OldValue = $this->message_thread_code->CurrentValue;
		$this->message->CurrentValue = NULL;
		$this->message->OldValue = $this->message->CurrentValue;
		$this->sender->CurrentValue = NULL;
		$this->sender->OldValue = $this->sender->CurrentValue;
		$this->timestamp->CurrentValue = NULL;
		$this->timestamp->OldValue = $this->timestamp->CurrentValue;
		$this->read_status->CurrentValue = 0;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->message_thread_code->FldIsDetailKey) {
			$this->message_thread_code->setFormValue($objForm->GetValue("x_message_thread_code"));
		}
		if (!$this->message->FldIsDetailKey) {
			$this->message->setFormValue($objForm->GetValue("x_message"));
		}
		if (!$this->sender->FldIsDetailKey) {
			$this->sender->setFormValue($objForm->GetValue("x_sender"));
		}
		if (!$this->timestamp->FldIsDetailKey) {
			$this->timestamp->setFormValue($objForm->GetValue("x_timestamp"));
		}
		if (!$this->read_status->FldIsDetailKey) {
			$this->read_status->setFormValue($objForm->GetValue("x_read_status"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->message_thread_code->CurrentValue = $this->message_thread_code->FormValue;
		$this->message->CurrentValue = $this->message->FormValue;
		$this->sender->CurrentValue = $this->sender->FormValue;
		$this->timestamp->CurrentValue = $this->timestamp->FormValue;
		$this->read_status->CurrentValue = $this->read_status->FormValue;
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
		$this->message_id->setDbValue($row['message_id']);
		$this->message_thread_code->setDbValue($row['message_thread_code']);
		$this->message->setDbValue($row['message']);
		$this->sender->setDbValue($row['sender']);
		$this->timestamp->setDbValue($row['timestamp']);
		$this->read_status->setDbValue($row['read_status']);
	}

	// Return a row with default values
	function NewRow() {
		$this->LoadDefaultValues();
		$row = array();
		$row['message_id'] = $this->message_id->CurrentValue;
		$row['message_thread_code'] = $this->message_thread_code->CurrentValue;
		$row['message'] = $this->message->CurrentValue;
		$row['sender'] = $this->sender->CurrentValue;
		$row['timestamp'] = $this->timestamp->CurrentValue;
		$row['read_status'] = $this->read_status->CurrentValue;
		return $row;
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF)
			return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->message_id->DbValue = $row['message_id'];
		$this->message_thread_code->DbValue = $row['message_thread_code'];
		$this->message->DbValue = $row['message'];
		$this->sender->DbValue = $row['sender'];
		$this->timestamp->DbValue = $row['timestamp'];
		$this->read_status->DbValue = $row['read_status'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("message_id")) <> "")
			$this->message_id->CurrentValue = $this->getKey("message_id"); // message_id
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
		// message_id
		// message_thread_code
		// message
		// sender
		// timestamp
		// read_status

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// message_id
		$this->message_id->ViewValue = $this->message_id->CurrentValue;
		$this->message_id->ViewCustomAttributes = "";

		// message_thread_code
		$this->message_thread_code->ViewValue = $this->message_thread_code->CurrentValue;
		$this->message_thread_code->ViewCustomAttributes = "";

		// message
		$this->message->ViewValue = $this->message->CurrentValue;
		$this->message->ViewCustomAttributes = "";

		// sender
		$this->sender->ViewValue = $this->sender->CurrentValue;
		$this->sender->ViewCustomAttributes = "";

		// timestamp
		$this->timestamp->ViewValue = $this->timestamp->CurrentValue;
		$this->timestamp->ViewCustomAttributes = "";

		// read_status
		$this->read_status->ViewValue = $this->read_status->CurrentValue;
		$this->read_status->ViewCustomAttributes = "";

			// message_thread_code
			$this->message_thread_code->LinkCustomAttributes = "";
			$this->message_thread_code->HrefValue = "";
			$this->message_thread_code->TooltipValue = "";

			// message
			$this->message->LinkCustomAttributes = "";
			$this->message->HrefValue = "";
			$this->message->TooltipValue = "";

			// sender
			$this->sender->LinkCustomAttributes = "";
			$this->sender->HrefValue = "";
			$this->sender->TooltipValue = "";

			// timestamp
			$this->timestamp->LinkCustomAttributes = "";
			$this->timestamp->HrefValue = "";
			$this->timestamp->TooltipValue = "";

			// read_status
			$this->read_status->LinkCustomAttributes = "";
			$this->read_status->HrefValue = "";
			$this->read_status->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// message_thread_code
			$this->message_thread_code->EditAttrs["class"] = "form-control";
			$this->message_thread_code->EditCustomAttributes = "";
			$this->message_thread_code->EditValue = ew_HtmlEncode($this->message_thread_code->CurrentValue);
			$this->message_thread_code->PlaceHolder = ew_RemoveHtml($this->message_thread_code->FldCaption());

			// message
			$this->message->EditAttrs["class"] = "form-control";
			$this->message->EditCustomAttributes = "";
			$this->message->EditValue = ew_HtmlEncode($this->message->CurrentValue);
			$this->message->PlaceHolder = ew_RemoveHtml($this->message->FldCaption());

			// sender
			$this->sender->EditAttrs["class"] = "form-control";
			$this->sender->EditCustomAttributes = "";
			$this->sender->EditValue = ew_HtmlEncode($this->sender->CurrentValue);
			$this->sender->PlaceHolder = ew_RemoveHtml($this->sender->FldCaption());

			// timestamp
			$this->timestamp->EditAttrs["class"] = "form-control";
			$this->timestamp->EditCustomAttributes = "";
			$this->timestamp->EditValue = ew_HtmlEncode($this->timestamp->CurrentValue);
			$this->timestamp->PlaceHolder = ew_RemoveHtml($this->timestamp->FldCaption());

			// read_status
			$this->read_status->EditAttrs["class"] = "form-control";
			$this->read_status->EditCustomAttributes = "";
			$this->read_status->EditValue = ew_HtmlEncode($this->read_status->CurrentValue);
			$this->read_status->PlaceHolder = ew_RemoveHtml($this->read_status->FldCaption());

			// Add refer script
			// message_thread_code

			$this->message_thread_code->LinkCustomAttributes = "";
			$this->message_thread_code->HrefValue = "";

			// message
			$this->message->LinkCustomAttributes = "";
			$this->message->HrefValue = "";

			// sender
			$this->sender->LinkCustomAttributes = "";
			$this->sender->HrefValue = "";

			// timestamp
			$this->timestamp->LinkCustomAttributes = "";
			$this->timestamp->HrefValue = "";

			// read_status
			$this->read_status->LinkCustomAttributes = "";
			$this->read_status->HrefValue = "";
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
		if (!$this->message->FldIsDetailKey && !is_null($this->message->FormValue) && $this->message->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->message->FldCaption(), $this->message->ReqErrMsg));
		}
		if (!$this->sender->FldIsDetailKey && !is_null($this->sender->FormValue) && $this->sender->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->sender->FldCaption(), $this->sender->ReqErrMsg));
		}
		if (!$this->timestamp->FldIsDetailKey && !is_null($this->timestamp->FormValue) && $this->timestamp->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->timestamp->FldCaption(), $this->timestamp->ReqErrMsg));
		}
		if (!$this->read_status->FldIsDetailKey && !is_null($this->read_status->FormValue) && $this->read_status->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->read_status->FldCaption(), $this->read_status->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->read_status->FormValue)) {
			ew_AddMessage($gsFormError, $this->read_status->FldErrMsg());
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

		// message_thread_code
		$this->message_thread_code->SetDbValueDef($rsnew, $this->message_thread_code->CurrentValue, "", FALSE);

		// message
		$this->message->SetDbValueDef($rsnew, $this->message->CurrentValue, "", FALSE);

		// sender
		$this->sender->SetDbValueDef($rsnew, $this->sender->CurrentValue, "", FALSE);

		// timestamp
		$this->timestamp->SetDbValueDef($rsnew, $this->timestamp->CurrentValue, "", FALSE);

		// read_status
		$this->read_status->SetDbValueDef($rsnew, $this->read_status->CurrentValue, 0, strval($this->read_status->CurrentValue) == "");

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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("messagelist.php"), "", $this->TableVar, TRUE);
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
if (!isset($message_add)) $message_add = new cmessage_add();

// Page init
$message_add->Page_Init();

// Page main
$message_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$message_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "add";
var CurrentForm = fmessageadd = new ew_Form("fmessageadd", "add");

// Validate form
fmessageadd.Validate = function() {
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
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $message->message_thread_code->FldCaption(), $message->message_thread_code->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_message");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $message->message->FldCaption(), $message->message->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_sender");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $message->sender->FldCaption(), $message->sender->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_timestamp");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $message->timestamp->FldCaption(), $message->timestamp->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_read_status");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $message->read_status->FldCaption(), $message->read_status->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_read_status");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($message->read_status->FldErrMsg()) ?>");

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
fmessageadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fmessageadd.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $message_add->ShowPageHeader(); ?>
<?php
$message_add->ShowMessage();
?>
<form name="fmessageadd" id="fmessageadd" class="<?php echo $message_add->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($message_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $message_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="message">
<input type="hidden" name="a_add" id="a_add" value="A">
<input type="hidden" name="modal" value="<?php echo intval($message_add->IsModal) ?>">
<div class="ewAddDiv"><!-- page* -->
<?php if ($message->message_thread_code->Visible) { // message_thread_code ?>
	<div id="r_message_thread_code" class="form-group">
		<label id="elh_message_message_thread_code" for="x_message_thread_code" class="<?php echo $message_add->LeftColumnClass ?>"><?php echo $message->message_thread_code->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $message_add->RightColumnClass ?>"><div<?php echo $message->message_thread_code->CellAttributes() ?>>
<span id="el_message_message_thread_code">
<textarea data-table="message" data-field="x_message_thread_code" name="x_message_thread_code" id="x_message_thread_code" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($message->message_thread_code->getPlaceHolder()) ?>"<?php echo $message->message_thread_code->EditAttributes() ?>><?php echo $message->message_thread_code->EditValue ?></textarea>
</span>
<?php echo $message->message_thread_code->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($message->message->Visible) { // message ?>
	<div id="r_message" class="form-group">
		<label id="elh_message_message" for="x_message" class="<?php echo $message_add->LeftColumnClass ?>"><?php echo $message->message->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $message_add->RightColumnClass ?>"><div<?php echo $message->message->CellAttributes() ?>>
<span id="el_message_message">
<textarea data-table="message" data-field="x_message" name="x_message" id="x_message" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($message->message->getPlaceHolder()) ?>"<?php echo $message->message->EditAttributes() ?>><?php echo $message->message->EditValue ?></textarea>
</span>
<?php echo $message->message->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($message->sender->Visible) { // sender ?>
	<div id="r_sender" class="form-group">
		<label id="elh_message_sender" for="x_sender" class="<?php echo $message_add->LeftColumnClass ?>"><?php echo $message->sender->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $message_add->RightColumnClass ?>"><div<?php echo $message->sender->CellAttributes() ?>>
<span id="el_message_sender">
<textarea data-table="message" data-field="x_sender" name="x_sender" id="x_sender" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($message->sender->getPlaceHolder()) ?>"<?php echo $message->sender->EditAttributes() ?>><?php echo $message->sender->EditValue ?></textarea>
</span>
<?php echo $message->sender->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($message->timestamp->Visible) { // timestamp ?>
	<div id="r_timestamp" class="form-group">
		<label id="elh_message_timestamp" for="x_timestamp" class="<?php echo $message_add->LeftColumnClass ?>"><?php echo $message->timestamp->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $message_add->RightColumnClass ?>"><div<?php echo $message->timestamp->CellAttributes() ?>>
<span id="el_message_timestamp">
<textarea data-table="message" data-field="x_timestamp" name="x_timestamp" id="x_timestamp" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($message->timestamp->getPlaceHolder()) ?>"<?php echo $message->timestamp->EditAttributes() ?>><?php echo $message->timestamp->EditValue ?></textarea>
</span>
<?php echo $message->timestamp->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($message->read_status->Visible) { // read_status ?>
	<div id="r_read_status" class="form-group">
		<label id="elh_message_read_status" for="x_read_status" class="<?php echo $message_add->LeftColumnClass ?>"><?php echo $message->read_status->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $message_add->RightColumnClass ?>"><div<?php echo $message->read_status->CellAttributes() ?>>
<span id="el_message_read_status">
<input type="text" data-table="message" data-field="x_read_status" name="x_read_status" id="x_read_status" size="30" placeholder="<?php echo ew_HtmlEncode($message->read_status->getPlaceHolder()) ?>" value="<?php echo $message->read_status->EditValue ?>"<?php echo $message->read_status->EditAttributes() ?>>
</span>
<?php echo $message->read_status->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div><!-- /page* -->
<?php if (!$message_add->IsModal) { ?>
<div class="form-group"><!-- buttons .form-group -->
	<div class="<?php echo $message_add->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $message_add->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div><!-- /buttons offset -->
</div><!-- /buttons .form-group -->
<?php } ?>
</form>
<script type="text/javascript">
fmessageadd.Init();
</script>
<?php
$message_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$message_add->Page_Terminate();
?>
