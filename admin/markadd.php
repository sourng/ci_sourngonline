<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "markinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$mark_add = NULL; // Initialize page object first

class cmark_add extends cmark {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = '{1F40EE59-17A4-45D3-96B6-AA2082B3D64A}';

	// Table name
	var $TableName = 'mark';

	// Page object name
	var $PageObjName = 'mark_add';

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

		// Table object (mark)
		if (!isset($GLOBALS["mark"]) || get_class($GLOBALS["mark"]) == "cmark") {
			$GLOBALS["mark"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["mark"];
		}

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'mark', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("marklist.php"));
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
		$this->student_id->SetVisibility();
		$this->subject_id->SetVisibility();
		$this->class_id->SetVisibility();
		$this->exam_id->SetVisibility();
		$this->mark_obtained->SetVisibility();
		$this->mark_total->SetVisibility();
		$this->comment->SetVisibility();

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
		global $EW_EXPORT, $mark;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($mark);
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
					if ($pageName == "markview.php")
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
			if (@$_GET["mark_id"] != "") {
				$this->mark_id->setQueryStringValue($_GET["mark_id"]);
				$this->setKey("mark_id", $this->mark_id->CurrentValue); // Set up key
			} else {
				$this->setKey("mark_id", ""); // Clear key
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
					$this->Page_Terminate("marklist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "marklist.php")
						$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to List page with correct master key if necessary
					elseif (ew_GetPageName($sReturnUrl) == "markview.php")
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
		$this->mark_id->CurrentValue = NULL;
		$this->mark_id->OldValue = $this->mark_id->CurrentValue;
		$this->student_id->CurrentValue = NULL;
		$this->student_id->OldValue = $this->student_id->CurrentValue;
		$this->subject_id->CurrentValue = NULL;
		$this->subject_id->OldValue = $this->subject_id->CurrentValue;
		$this->class_id->CurrentValue = NULL;
		$this->class_id->OldValue = $this->class_id->CurrentValue;
		$this->exam_id->CurrentValue = NULL;
		$this->exam_id->OldValue = $this->exam_id->CurrentValue;
		$this->mark_obtained->CurrentValue = 0;
		$this->mark_total->CurrentValue = 100;
		$this->comment->CurrentValue = NULL;
		$this->comment->OldValue = $this->comment->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->student_id->FldIsDetailKey) {
			$this->student_id->setFormValue($objForm->GetValue("x_student_id"));
		}
		if (!$this->subject_id->FldIsDetailKey) {
			$this->subject_id->setFormValue($objForm->GetValue("x_subject_id"));
		}
		if (!$this->class_id->FldIsDetailKey) {
			$this->class_id->setFormValue($objForm->GetValue("x_class_id"));
		}
		if (!$this->exam_id->FldIsDetailKey) {
			$this->exam_id->setFormValue($objForm->GetValue("x_exam_id"));
		}
		if (!$this->mark_obtained->FldIsDetailKey) {
			$this->mark_obtained->setFormValue($objForm->GetValue("x_mark_obtained"));
		}
		if (!$this->mark_total->FldIsDetailKey) {
			$this->mark_total->setFormValue($objForm->GetValue("x_mark_total"));
		}
		if (!$this->comment->FldIsDetailKey) {
			$this->comment->setFormValue($objForm->GetValue("x_comment"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->student_id->CurrentValue = $this->student_id->FormValue;
		$this->subject_id->CurrentValue = $this->subject_id->FormValue;
		$this->class_id->CurrentValue = $this->class_id->FormValue;
		$this->exam_id->CurrentValue = $this->exam_id->FormValue;
		$this->mark_obtained->CurrentValue = $this->mark_obtained->FormValue;
		$this->mark_total->CurrentValue = $this->mark_total->FormValue;
		$this->comment->CurrentValue = $this->comment->FormValue;
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
		$this->mark_id->setDbValue($row['mark_id']);
		$this->student_id->setDbValue($row['student_id']);
		$this->subject_id->setDbValue($row['subject_id']);
		$this->class_id->setDbValue($row['class_id']);
		$this->exam_id->setDbValue($row['exam_id']);
		$this->mark_obtained->setDbValue($row['mark_obtained']);
		$this->mark_total->setDbValue($row['mark_total']);
		$this->comment->setDbValue($row['comment']);
	}

	// Return a row with default values
	function NewRow() {
		$this->LoadDefaultValues();
		$row = array();
		$row['mark_id'] = $this->mark_id->CurrentValue;
		$row['student_id'] = $this->student_id->CurrentValue;
		$row['subject_id'] = $this->subject_id->CurrentValue;
		$row['class_id'] = $this->class_id->CurrentValue;
		$row['exam_id'] = $this->exam_id->CurrentValue;
		$row['mark_obtained'] = $this->mark_obtained->CurrentValue;
		$row['mark_total'] = $this->mark_total->CurrentValue;
		$row['comment'] = $this->comment->CurrentValue;
		return $row;
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF)
			return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->mark_id->DbValue = $row['mark_id'];
		$this->student_id->DbValue = $row['student_id'];
		$this->subject_id->DbValue = $row['subject_id'];
		$this->class_id->DbValue = $row['class_id'];
		$this->exam_id->DbValue = $row['exam_id'];
		$this->mark_obtained->DbValue = $row['mark_obtained'];
		$this->mark_total->DbValue = $row['mark_total'];
		$this->comment->DbValue = $row['comment'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("mark_id")) <> "")
			$this->mark_id->CurrentValue = $this->getKey("mark_id"); // mark_id
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
		// mark_id
		// student_id
		// subject_id
		// class_id
		// exam_id
		// mark_obtained
		// mark_total
		// comment

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// mark_id
		$this->mark_id->ViewValue = $this->mark_id->CurrentValue;
		$this->mark_id->ViewCustomAttributes = "";

		// student_id
		$this->student_id->ViewValue = $this->student_id->CurrentValue;
		$this->student_id->ViewCustomAttributes = "";

		// subject_id
		$this->subject_id->ViewValue = $this->subject_id->CurrentValue;
		$this->subject_id->ViewCustomAttributes = "";

		// class_id
		$this->class_id->ViewValue = $this->class_id->CurrentValue;
		$this->class_id->ViewCustomAttributes = "";

		// exam_id
		$this->exam_id->ViewValue = $this->exam_id->CurrentValue;
		$this->exam_id->ViewCustomAttributes = "";

		// mark_obtained
		$this->mark_obtained->ViewValue = $this->mark_obtained->CurrentValue;
		$this->mark_obtained->ViewCustomAttributes = "";

		// mark_total
		$this->mark_total->ViewValue = $this->mark_total->CurrentValue;
		$this->mark_total->ViewCustomAttributes = "";

		// comment
		$this->comment->ViewValue = $this->comment->CurrentValue;
		$this->comment->ViewCustomAttributes = "";

			// student_id
			$this->student_id->LinkCustomAttributes = "";
			$this->student_id->HrefValue = "";
			$this->student_id->TooltipValue = "";

			// subject_id
			$this->subject_id->LinkCustomAttributes = "";
			$this->subject_id->HrefValue = "";
			$this->subject_id->TooltipValue = "";

			// class_id
			$this->class_id->LinkCustomAttributes = "";
			$this->class_id->HrefValue = "";
			$this->class_id->TooltipValue = "";

			// exam_id
			$this->exam_id->LinkCustomAttributes = "";
			$this->exam_id->HrefValue = "";
			$this->exam_id->TooltipValue = "";

			// mark_obtained
			$this->mark_obtained->LinkCustomAttributes = "";
			$this->mark_obtained->HrefValue = "";
			$this->mark_obtained->TooltipValue = "";

			// mark_total
			$this->mark_total->LinkCustomAttributes = "";
			$this->mark_total->HrefValue = "";
			$this->mark_total->TooltipValue = "";

			// comment
			$this->comment->LinkCustomAttributes = "";
			$this->comment->HrefValue = "";
			$this->comment->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// student_id
			$this->student_id->EditAttrs["class"] = "form-control";
			$this->student_id->EditCustomAttributes = "";
			$this->student_id->EditValue = ew_HtmlEncode($this->student_id->CurrentValue);
			$this->student_id->PlaceHolder = ew_RemoveHtml($this->student_id->FldCaption());

			// subject_id
			$this->subject_id->EditAttrs["class"] = "form-control";
			$this->subject_id->EditCustomAttributes = "";
			$this->subject_id->EditValue = ew_HtmlEncode($this->subject_id->CurrentValue);
			$this->subject_id->PlaceHolder = ew_RemoveHtml($this->subject_id->FldCaption());

			// class_id
			$this->class_id->EditAttrs["class"] = "form-control";
			$this->class_id->EditCustomAttributes = "";
			$this->class_id->EditValue = ew_HtmlEncode($this->class_id->CurrentValue);
			$this->class_id->PlaceHolder = ew_RemoveHtml($this->class_id->FldCaption());

			// exam_id
			$this->exam_id->EditAttrs["class"] = "form-control";
			$this->exam_id->EditCustomAttributes = "";
			$this->exam_id->EditValue = ew_HtmlEncode($this->exam_id->CurrentValue);
			$this->exam_id->PlaceHolder = ew_RemoveHtml($this->exam_id->FldCaption());

			// mark_obtained
			$this->mark_obtained->EditAttrs["class"] = "form-control";
			$this->mark_obtained->EditCustomAttributes = "";
			$this->mark_obtained->EditValue = ew_HtmlEncode($this->mark_obtained->CurrentValue);
			$this->mark_obtained->PlaceHolder = ew_RemoveHtml($this->mark_obtained->FldCaption());

			// mark_total
			$this->mark_total->EditAttrs["class"] = "form-control";
			$this->mark_total->EditCustomAttributes = "";
			$this->mark_total->EditValue = ew_HtmlEncode($this->mark_total->CurrentValue);
			$this->mark_total->PlaceHolder = ew_RemoveHtml($this->mark_total->FldCaption());

			// comment
			$this->comment->EditAttrs["class"] = "form-control";
			$this->comment->EditCustomAttributes = "";
			$this->comment->EditValue = ew_HtmlEncode($this->comment->CurrentValue);
			$this->comment->PlaceHolder = ew_RemoveHtml($this->comment->FldCaption());

			// Add refer script
			// student_id

			$this->student_id->LinkCustomAttributes = "";
			$this->student_id->HrefValue = "";

			// subject_id
			$this->subject_id->LinkCustomAttributes = "";
			$this->subject_id->HrefValue = "";

			// class_id
			$this->class_id->LinkCustomAttributes = "";
			$this->class_id->HrefValue = "";

			// exam_id
			$this->exam_id->LinkCustomAttributes = "";
			$this->exam_id->HrefValue = "";

			// mark_obtained
			$this->mark_obtained->LinkCustomAttributes = "";
			$this->mark_obtained->HrefValue = "";

			// mark_total
			$this->mark_total->LinkCustomAttributes = "";
			$this->mark_total->HrefValue = "";

			// comment
			$this->comment->LinkCustomAttributes = "";
			$this->comment->HrefValue = "";
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
		if (!$this->subject_id->FldIsDetailKey && !is_null($this->subject_id->FormValue) && $this->subject_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->subject_id->FldCaption(), $this->subject_id->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->subject_id->FormValue)) {
			ew_AddMessage($gsFormError, $this->subject_id->FldErrMsg());
		}
		if (!$this->class_id->FldIsDetailKey && !is_null($this->class_id->FormValue) && $this->class_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->class_id->FldCaption(), $this->class_id->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->class_id->FormValue)) {
			ew_AddMessage($gsFormError, $this->class_id->FldErrMsg());
		}
		if (!$this->exam_id->FldIsDetailKey && !is_null($this->exam_id->FormValue) && $this->exam_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->exam_id->FldCaption(), $this->exam_id->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->exam_id->FormValue)) {
			ew_AddMessage($gsFormError, $this->exam_id->FldErrMsg());
		}
		if (!$this->mark_obtained->FldIsDetailKey && !is_null($this->mark_obtained->FormValue) && $this->mark_obtained->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->mark_obtained->FldCaption(), $this->mark_obtained->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->mark_obtained->FormValue)) {
			ew_AddMessage($gsFormError, $this->mark_obtained->FldErrMsg());
		}
		if (!$this->mark_total->FldIsDetailKey && !is_null($this->mark_total->FormValue) && $this->mark_total->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->mark_total->FldCaption(), $this->mark_total->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->mark_total->FormValue)) {
			ew_AddMessage($gsFormError, $this->mark_total->FldErrMsg());
		}
		if (!$this->comment->FldIsDetailKey && !is_null($this->comment->FormValue) && $this->comment->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->comment->FldCaption(), $this->comment->ReqErrMsg));
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

		// student_id
		$this->student_id->SetDbValueDef($rsnew, $this->student_id->CurrentValue, 0, FALSE);

		// subject_id
		$this->subject_id->SetDbValueDef($rsnew, $this->subject_id->CurrentValue, 0, FALSE);

		// class_id
		$this->class_id->SetDbValueDef($rsnew, $this->class_id->CurrentValue, 0, FALSE);

		// exam_id
		$this->exam_id->SetDbValueDef($rsnew, $this->exam_id->CurrentValue, 0, FALSE);

		// mark_obtained
		$this->mark_obtained->SetDbValueDef($rsnew, $this->mark_obtained->CurrentValue, 0, strval($this->mark_obtained->CurrentValue) == "");

		// mark_total
		$this->mark_total->SetDbValueDef($rsnew, $this->mark_total->CurrentValue, 0, strval($this->mark_total->CurrentValue) == "");

		// comment
		$this->comment->SetDbValueDef($rsnew, $this->comment->CurrentValue, "", FALSE);

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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("marklist.php"), "", $this->TableVar, TRUE);
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
if (!isset($mark_add)) $mark_add = new cmark_add();

// Page init
$mark_add->Page_Init();

// Page main
$mark_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$mark_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "add";
var CurrentForm = fmarkadd = new ew_Form("fmarkadd", "add");

// Validate form
fmarkadd.Validate = function() {
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
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $mark->student_id->FldCaption(), $mark->student_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_student_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($mark->student_id->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_subject_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $mark->subject_id->FldCaption(), $mark->subject_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_subject_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($mark->subject_id->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_class_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $mark->class_id->FldCaption(), $mark->class_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_class_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($mark->class_id->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_exam_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $mark->exam_id->FldCaption(), $mark->exam_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_exam_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($mark->exam_id->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_mark_obtained");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $mark->mark_obtained->FldCaption(), $mark->mark_obtained->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_mark_obtained");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($mark->mark_obtained->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_mark_total");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $mark->mark_total->FldCaption(), $mark->mark_total->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_mark_total");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($mark->mark_total->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_comment");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $mark->comment->FldCaption(), $mark->comment->ReqErrMsg)) ?>");

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
fmarkadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fmarkadd.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $mark_add->ShowPageHeader(); ?>
<?php
$mark_add->ShowMessage();
?>
<form name="fmarkadd" id="fmarkadd" class="<?php echo $mark_add->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($mark_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $mark_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="mark">
<input type="hidden" name="a_add" id="a_add" value="A">
<input type="hidden" name="modal" value="<?php echo intval($mark_add->IsModal) ?>">
<div class="ewAddDiv"><!-- page* -->
<?php if ($mark->student_id->Visible) { // student_id ?>
	<div id="r_student_id" class="form-group">
		<label id="elh_mark_student_id" for="x_student_id" class="<?php echo $mark_add->LeftColumnClass ?>"><?php echo $mark->student_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $mark_add->RightColumnClass ?>"><div<?php echo $mark->student_id->CellAttributes() ?>>
<span id="el_mark_student_id">
<input type="text" data-table="mark" data-field="x_student_id" name="x_student_id" id="x_student_id" size="30" placeholder="<?php echo ew_HtmlEncode($mark->student_id->getPlaceHolder()) ?>" value="<?php echo $mark->student_id->EditValue ?>"<?php echo $mark->student_id->EditAttributes() ?>>
</span>
<?php echo $mark->student_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($mark->subject_id->Visible) { // subject_id ?>
	<div id="r_subject_id" class="form-group">
		<label id="elh_mark_subject_id" for="x_subject_id" class="<?php echo $mark_add->LeftColumnClass ?>"><?php echo $mark->subject_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $mark_add->RightColumnClass ?>"><div<?php echo $mark->subject_id->CellAttributes() ?>>
<span id="el_mark_subject_id">
<input type="text" data-table="mark" data-field="x_subject_id" name="x_subject_id" id="x_subject_id" size="30" placeholder="<?php echo ew_HtmlEncode($mark->subject_id->getPlaceHolder()) ?>" value="<?php echo $mark->subject_id->EditValue ?>"<?php echo $mark->subject_id->EditAttributes() ?>>
</span>
<?php echo $mark->subject_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($mark->class_id->Visible) { // class_id ?>
	<div id="r_class_id" class="form-group">
		<label id="elh_mark_class_id" for="x_class_id" class="<?php echo $mark_add->LeftColumnClass ?>"><?php echo $mark->class_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $mark_add->RightColumnClass ?>"><div<?php echo $mark->class_id->CellAttributes() ?>>
<span id="el_mark_class_id">
<input type="text" data-table="mark" data-field="x_class_id" name="x_class_id" id="x_class_id" size="30" placeholder="<?php echo ew_HtmlEncode($mark->class_id->getPlaceHolder()) ?>" value="<?php echo $mark->class_id->EditValue ?>"<?php echo $mark->class_id->EditAttributes() ?>>
</span>
<?php echo $mark->class_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($mark->exam_id->Visible) { // exam_id ?>
	<div id="r_exam_id" class="form-group">
		<label id="elh_mark_exam_id" for="x_exam_id" class="<?php echo $mark_add->LeftColumnClass ?>"><?php echo $mark->exam_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $mark_add->RightColumnClass ?>"><div<?php echo $mark->exam_id->CellAttributes() ?>>
<span id="el_mark_exam_id">
<input type="text" data-table="mark" data-field="x_exam_id" name="x_exam_id" id="x_exam_id" size="30" placeholder="<?php echo ew_HtmlEncode($mark->exam_id->getPlaceHolder()) ?>" value="<?php echo $mark->exam_id->EditValue ?>"<?php echo $mark->exam_id->EditAttributes() ?>>
</span>
<?php echo $mark->exam_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($mark->mark_obtained->Visible) { // mark_obtained ?>
	<div id="r_mark_obtained" class="form-group">
		<label id="elh_mark_mark_obtained" for="x_mark_obtained" class="<?php echo $mark_add->LeftColumnClass ?>"><?php echo $mark->mark_obtained->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $mark_add->RightColumnClass ?>"><div<?php echo $mark->mark_obtained->CellAttributes() ?>>
<span id="el_mark_mark_obtained">
<input type="text" data-table="mark" data-field="x_mark_obtained" name="x_mark_obtained" id="x_mark_obtained" size="30" placeholder="<?php echo ew_HtmlEncode($mark->mark_obtained->getPlaceHolder()) ?>" value="<?php echo $mark->mark_obtained->EditValue ?>"<?php echo $mark->mark_obtained->EditAttributes() ?>>
</span>
<?php echo $mark->mark_obtained->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($mark->mark_total->Visible) { // mark_total ?>
	<div id="r_mark_total" class="form-group">
		<label id="elh_mark_mark_total" for="x_mark_total" class="<?php echo $mark_add->LeftColumnClass ?>"><?php echo $mark->mark_total->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $mark_add->RightColumnClass ?>"><div<?php echo $mark->mark_total->CellAttributes() ?>>
<span id="el_mark_mark_total">
<input type="text" data-table="mark" data-field="x_mark_total" name="x_mark_total" id="x_mark_total" size="30" placeholder="<?php echo ew_HtmlEncode($mark->mark_total->getPlaceHolder()) ?>" value="<?php echo $mark->mark_total->EditValue ?>"<?php echo $mark->mark_total->EditAttributes() ?>>
</span>
<?php echo $mark->mark_total->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($mark->comment->Visible) { // comment ?>
	<div id="r_comment" class="form-group">
		<label id="elh_mark_comment" for="x_comment" class="<?php echo $mark_add->LeftColumnClass ?>"><?php echo $mark->comment->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $mark_add->RightColumnClass ?>"><div<?php echo $mark->comment->CellAttributes() ?>>
<span id="el_mark_comment">
<textarea data-table="mark" data-field="x_comment" name="x_comment" id="x_comment" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($mark->comment->getPlaceHolder()) ?>"<?php echo $mark->comment->EditAttributes() ?>><?php echo $mark->comment->EditValue ?></textarea>
</span>
<?php echo $mark->comment->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div><!-- /page* -->
<?php if (!$mark_add->IsModal) { ?>
<div class="form-group"><!-- buttons .form-group -->
	<div class="<?php echo $mark_add->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $mark_add->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div><!-- /buttons offset -->
</div><!-- /buttons .form-group -->
<?php } ?>
</form>
<script type="text/javascript">
fmarkadd.Init();
</script>
<?php
$mark_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$mark_add->Page_Terminate();
?>
