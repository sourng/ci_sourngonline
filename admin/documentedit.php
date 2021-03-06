<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "documentinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$document_edit = NULL; // Initialize page object first

class cdocument_edit extends cdocument {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = '{1F40EE59-17A4-45D3-96B6-AA2082B3D64A}';

	// Table name
	var $TableName = 'document';

	// Page object name
	var $PageObjName = 'document_edit';

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

		// Table object (document)
		if (!isset($GLOBALS["document"]) || get_class($GLOBALS["document"]) == "cdocument") {
			$GLOBALS["document"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["document"];
		}

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'document', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("documentlist.php"));
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
		$this->document_id->SetVisibility();
		if ($this->IsAdd() || $this->IsCopy() || $this->IsGridAdd())
			$this->document_id->Visible = FALSE;
		$this->title->SetVisibility();
		$this->description->SetVisibility();
		$this->file_name->SetVisibility();
		$this->file_type->SetVisibility();
		$this->class_id->SetVisibility();
		$this->teacher_id->SetVisibility();
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
		global $EW_EXPORT, $document;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($document);
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
					if ($pageName == "documentview.php")
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
			if ($objForm->HasValue("x_document_id")) {
				$this->document_id->setFormValue($objForm->GetValue("x_document_id"));
			}
		} else {
			$this->CurrentAction = "I"; // Default action is display

			// Load key from QueryString
			$loadByQuery = FALSE;
			if (isset($_GET["document_id"])) {
				$this->document_id->setQueryStringValue($_GET["document_id"]);
				$loadByQuery = TRUE;
			} else {
				$this->document_id->CurrentValue = NULL;
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
					$this->Page_Terminate("documentlist.php"); // No matching record, return to list
				}
				break;
			Case "U": // Update
				$sReturnUrl = $this->getReturnUrl();
				if (ew_GetPageName($sReturnUrl) == "documentlist.php")
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
		if (!$this->document_id->FldIsDetailKey)
			$this->document_id->setFormValue($objForm->GetValue("x_document_id"));
		if (!$this->title->FldIsDetailKey) {
			$this->title->setFormValue($objForm->GetValue("x_title"));
		}
		if (!$this->description->FldIsDetailKey) {
			$this->description->setFormValue($objForm->GetValue("x_description"));
		}
		if (!$this->file_name->FldIsDetailKey) {
			$this->file_name->setFormValue($objForm->GetValue("x_file_name"));
		}
		if (!$this->file_type->FldIsDetailKey) {
			$this->file_type->setFormValue($objForm->GetValue("x_file_type"));
		}
		if (!$this->class_id->FldIsDetailKey) {
			$this->class_id->setFormValue($objForm->GetValue("x_class_id"));
		}
		if (!$this->teacher_id->FldIsDetailKey) {
			$this->teacher_id->setFormValue($objForm->GetValue("x_teacher_id"));
		}
		if (!$this->timestamp->FldIsDetailKey) {
			$this->timestamp->setFormValue($objForm->GetValue("x_timestamp"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->document_id->CurrentValue = $this->document_id->FormValue;
		$this->title->CurrentValue = $this->title->FormValue;
		$this->description->CurrentValue = $this->description->FormValue;
		$this->file_name->CurrentValue = $this->file_name->FormValue;
		$this->file_type->CurrentValue = $this->file_type->FormValue;
		$this->class_id->CurrentValue = $this->class_id->FormValue;
		$this->teacher_id->CurrentValue = $this->teacher_id->FormValue;
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
		$this->document_id->setDbValue($row['document_id']);
		$this->title->setDbValue($row['title']);
		$this->description->setDbValue($row['description']);
		$this->file_name->setDbValue($row['file_name']);
		$this->file_type->setDbValue($row['file_type']);
		$this->class_id->setDbValue($row['class_id']);
		$this->teacher_id->setDbValue($row['teacher_id']);
		$this->timestamp->setDbValue($row['timestamp']);
	}

	// Return a row with default values
	function NewRow() {
		$row = array();
		$row['document_id'] = NULL;
		$row['title'] = NULL;
		$row['description'] = NULL;
		$row['file_name'] = NULL;
		$row['file_type'] = NULL;
		$row['class_id'] = NULL;
		$row['teacher_id'] = NULL;
		$row['timestamp'] = NULL;
		return $row;
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF)
			return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->document_id->DbValue = $row['document_id'];
		$this->title->DbValue = $row['title'];
		$this->description->DbValue = $row['description'];
		$this->file_name->DbValue = $row['file_name'];
		$this->file_type->DbValue = $row['file_type'];
		$this->class_id->DbValue = $row['class_id'];
		$this->teacher_id->DbValue = $row['teacher_id'];
		$this->timestamp->DbValue = $row['timestamp'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("document_id")) <> "")
			$this->document_id->CurrentValue = $this->getKey("document_id"); // document_id
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
		// document_id
		// title
		// description
		// file_name
		// file_type
		// class_id
		// teacher_id
		// timestamp

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// document_id
		$this->document_id->ViewValue = $this->document_id->CurrentValue;
		$this->document_id->ViewCustomAttributes = "";

		// title
		$this->title->ViewValue = $this->title->CurrentValue;
		$this->title->ViewCustomAttributes = "";

		// description
		$this->description->ViewValue = $this->description->CurrentValue;
		$this->description->ViewCustomAttributes = "";

		// file_name
		$this->file_name->ViewValue = $this->file_name->CurrentValue;
		$this->file_name->ViewCustomAttributes = "";

		// file_type
		$this->file_type->ViewValue = $this->file_type->CurrentValue;
		$this->file_type->ViewCustomAttributes = "";

		// class_id
		$this->class_id->ViewValue = $this->class_id->CurrentValue;
		$this->class_id->ViewCustomAttributes = "";

		// teacher_id
		$this->teacher_id->ViewValue = $this->teacher_id->CurrentValue;
		$this->teacher_id->ViewCustomAttributes = "";

		// timestamp
		$this->timestamp->ViewValue = $this->timestamp->CurrentValue;
		$this->timestamp->ViewCustomAttributes = "";

			// document_id
			$this->document_id->LinkCustomAttributes = "";
			$this->document_id->HrefValue = "";
			$this->document_id->TooltipValue = "";

			// title
			$this->title->LinkCustomAttributes = "";
			$this->title->HrefValue = "";
			$this->title->TooltipValue = "";

			// description
			$this->description->LinkCustomAttributes = "";
			$this->description->HrefValue = "";
			$this->description->TooltipValue = "";

			// file_name
			$this->file_name->LinkCustomAttributes = "";
			$this->file_name->HrefValue = "";
			$this->file_name->TooltipValue = "";

			// file_type
			$this->file_type->LinkCustomAttributes = "";
			$this->file_type->HrefValue = "";
			$this->file_type->TooltipValue = "";

			// class_id
			$this->class_id->LinkCustomAttributes = "";
			$this->class_id->HrefValue = "";
			$this->class_id->TooltipValue = "";

			// teacher_id
			$this->teacher_id->LinkCustomAttributes = "";
			$this->teacher_id->HrefValue = "";
			$this->teacher_id->TooltipValue = "";

			// timestamp
			$this->timestamp->LinkCustomAttributes = "";
			$this->timestamp->HrefValue = "";
			$this->timestamp->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// document_id
			$this->document_id->EditAttrs["class"] = "form-control";
			$this->document_id->EditCustomAttributes = "";
			$this->document_id->EditValue = $this->document_id->CurrentValue;
			$this->document_id->ViewCustomAttributes = "";

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

			// file_name
			$this->file_name->EditAttrs["class"] = "form-control";
			$this->file_name->EditCustomAttributes = "";
			$this->file_name->EditValue = ew_HtmlEncode($this->file_name->CurrentValue);
			$this->file_name->PlaceHolder = ew_RemoveHtml($this->file_name->FldCaption());

			// file_type
			$this->file_type->EditAttrs["class"] = "form-control";
			$this->file_type->EditCustomAttributes = "";
			$this->file_type->EditValue = ew_HtmlEncode($this->file_type->CurrentValue);
			$this->file_type->PlaceHolder = ew_RemoveHtml($this->file_type->FldCaption());

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

			// timestamp
			$this->timestamp->EditAttrs["class"] = "form-control";
			$this->timestamp->EditCustomAttributes = "";
			$this->timestamp->EditValue = ew_HtmlEncode($this->timestamp->CurrentValue);
			$this->timestamp->PlaceHolder = ew_RemoveHtml($this->timestamp->FldCaption());

			// Edit refer script
			// document_id

			$this->document_id->LinkCustomAttributes = "";
			$this->document_id->HrefValue = "";

			// title
			$this->title->LinkCustomAttributes = "";
			$this->title->HrefValue = "";

			// description
			$this->description->LinkCustomAttributes = "";
			$this->description->HrefValue = "";

			// file_name
			$this->file_name->LinkCustomAttributes = "";
			$this->file_name->HrefValue = "";

			// file_type
			$this->file_type->LinkCustomAttributes = "";
			$this->file_type->HrefValue = "";

			// class_id
			$this->class_id->LinkCustomAttributes = "";
			$this->class_id->HrefValue = "";

			// teacher_id
			$this->teacher_id->LinkCustomAttributes = "";
			$this->teacher_id->HrefValue = "";

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
		if (!$this->title->FldIsDetailKey && !is_null($this->title->FormValue) && $this->title->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->title->FldCaption(), $this->title->ReqErrMsg));
		}
		if (!$this->description->FldIsDetailKey && !is_null($this->description->FormValue) && $this->description->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->description->FldCaption(), $this->description->ReqErrMsg));
		}
		if (!$this->file_name->FldIsDetailKey && !is_null($this->file_name->FormValue) && $this->file_name->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->file_name->FldCaption(), $this->file_name->ReqErrMsg));
		}
		if (!$this->file_type->FldIsDetailKey && !is_null($this->file_type->FormValue) && $this->file_type->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->file_type->FldCaption(), $this->file_type->ReqErrMsg));
		}
		if (!$this->class_id->FldIsDetailKey && !is_null($this->class_id->FormValue) && $this->class_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->class_id->FldCaption(), $this->class_id->ReqErrMsg));
		}
		if (!$this->teacher_id->FldIsDetailKey && !is_null($this->teacher_id->FormValue) && $this->teacher_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->teacher_id->FldCaption(), $this->teacher_id->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->teacher_id->FormValue)) {
			ew_AddMessage($gsFormError, $this->teacher_id->FldErrMsg());
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

			// title
			$this->title->SetDbValueDef($rsnew, $this->title->CurrentValue, "", $this->title->ReadOnly);

			// description
			$this->description->SetDbValueDef($rsnew, $this->description->CurrentValue, "", $this->description->ReadOnly);

			// file_name
			$this->file_name->SetDbValueDef($rsnew, $this->file_name->CurrentValue, "", $this->file_name->ReadOnly);

			// file_type
			$this->file_type->SetDbValueDef($rsnew, $this->file_type->CurrentValue, "", $this->file_type->ReadOnly);

			// class_id
			$this->class_id->SetDbValueDef($rsnew, $this->class_id->CurrentValue, "", $this->class_id->ReadOnly);

			// teacher_id
			$this->teacher_id->SetDbValueDef($rsnew, $this->teacher_id->CurrentValue, 0, $this->teacher_id->ReadOnly);

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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("documentlist.php"), "", $this->TableVar, TRUE);
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
if (!isset($document_edit)) $document_edit = new cdocument_edit();

// Page init
$document_edit->Page_Init();

// Page main
$document_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$document_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = fdocumentedit = new ew_Form("fdocumentedit", "edit");

// Validate form
fdocumentedit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_title");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $document->title->FldCaption(), $document->title->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_description");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $document->description->FldCaption(), $document->description->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_file_name");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $document->file_name->FldCaption(), $document->file_name->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_file_type");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $document->file_type->FldCaption(), $document->file_type->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_class_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $document->class_id->FldCaption(), $document->class_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_teacher_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $document->teacher_id->FldCaption(), $document->teacher_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_teacher_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($document->teacher_id->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_timestamp");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $document->timestamp->FldCaption(), $document->timestamp->ReqErrMsg)) ?>");

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
fdocumentedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fdocumentedit.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $document_edit->ShowPageHeader(); ?>
<?php
$document_edit->ShowMessage();
?>
<form name="fdocumentedit" id="fdocumentedit" class="<?php echo $document_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($document_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $document_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="document">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<input type="hidden" name="modal" value="<?php echo intval($document_edit->IsModal) ?>">
<div class="ewEditDiv"><!-- page* -->
<?php if ($document->document_id->Visible) { // document_id ?>
	<div id="r_document_id" class="form-group">
		<label id="elh_document_document_id" class="<?php echo $document_edit->LeftColumnClass ?>"><?php echo $document->document_id->FldCaption() ?></label>
		<div class="<?php echo $document_edit->RightColumnClass ?>"><div<?php echo $document->document_id->CellAttributes() ?>>
<span id="el_document_document_id">
<span<?php echo $document->document_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $document->document_id->EditValue ?></p></span>
</span>
<input type="hidden" data-table="document" data-field="x_document_id" name="x_document_id" id="x_document_id" value="<?php echo ew_HtmlEncode($document->document_id->CurrentValue) ?>">
<?php echo $document->document_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($document->title->Visible) { // title ?>
	<div id="r_title" class="form-group">
		<label id="elh_document_title" for="x_title" class="<?php echo $document_edit->LeftColumnClass ?>"><?php echo $document->title->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $document_edit->RightColumnClass ?>"><div<?php echo $document->title->CellAttributes() ?>>
<span id="el_document_title">
<textarea data-table="document" data-field="x_title" name="x_title" id="x_title" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($document->title->getPlaceHolder()) ?>"<?php echo $document->title->EditAttributes() ?>><?php echo $document->title->EditValue ?></textarea>
</span>
<?php echo $document->title->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($document->description->Visible) { // description ?>
	<div id="r_description" class="form-group">
		<label id="elh_document_description" for="x_description" class="<?php echo $document_edit->LeftColumnClass ?>"><?php echo $document->description->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $document_edit->RightColumnClass ?>"><div<?php echo $document->description->CellAttributes() ?>>
<span id="el_document_description">
<textarea data-table="document" data-field="x_description" name="x_description" id="x_description" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($document->description->getPlaceHolder()) ?>"<?php echo $document->description->EditAttributes() ?>><?php echo $document->description->EditValue ?></textarea>
</span>
<?php echo $document->description->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($document->file_name->Visible) { // file_name ?>
	<div id="r_file_name" class="form-group">
		<label id="elh_document_file_name" for="x_file_name" class="<?php echo $document_edit->LeftColumnClass ?>"><?php echo $document->file_name->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $document_edit->RightColumnClass ?>"><div<?php echo $document->file_name->CellAttributes() ?>>
<span id="el_document_file_name">
<textarea data-table="document" data-field="x_file_name" name="x_file_name" id="x_file_name" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($document->file_name->getPlaceHolder()) ?>"<?php echo $document->file_name->EditAttributes() ?>><?php echo $document->file_name->EditValue ?></textarea>
</span>
<?php echo $document->file_name->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($document->file_type->Visible) { // file_type ?>
	<div id="r_file_type" class="form-group">
		<label id="elh_document_file_type" for="x_file_type" class="<?php echo $document_edit->LeftColumnClass ?>"><?php echo $document->file_type->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $document_edit->RightColumnClass ?>"><div<?php echo $document->file_type->CellAttributes() ?>>
<span id="el_document_file_type">
<textarea data-table="document" data-field="x_file_type" name="x_file_type" id="x_file_type" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($document->file_type->getPlaceHolder()) ?>"<?php echo $document->file_type->EditAttributes() ?>><?php echo $document->file_type->EditValue ?></textarea>
</span>
<?php echo $document->file_type->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($document->class_id->Visible) { // class_id ?>
	<div id="r_class_id" class="form-group">
		<label id="elh_document_class_id" for="x_class_id" class="<?php echo $document_edit->LeftColumnClass ?>"><?php echo $document->class_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $document_edit->RightColumnClass ?>"><div<?php echo $document->class_id->CellAttributes() ?>>
<span id="el_document_class_id">
<textarea data-table="document" data-field="x_class_id" name="x_class_id" id="x_class_id" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($document->class_id->getPlaceHolder()) ?>"<?php echo $document->class_id->EditAttributes() ?>><?php echo $document->class_id->EditValue ?></textarea>
</span>
<?php echo $document->class_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($document->teacher_id->Visible) { // teacher_id ?>
	<div id="r_teacher_id" class="form-group">
		<label id="elh_document_teacher_id" for="x_teacher_id" class="<?php echo $document_edit->LeftColumnClass ?>"><?php echo $document->teacher_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $document_edit->RightColumnClass ?>"><div<?php echo $document->teacher_id->CellAttributes() ?>>
<span id="el_document_teacher_id">
<input type="text" data-table="document" data-field="x_teacher_id" name="x_teacher_id" id="x_teacher_id" size="30" placeholder="<?php echo ew_HtmlEncode($document->teacher_id->getPlaceHolder()) ?>" value="<?php echo $document->teacher_id->EditValue ?>"<?php echo $document->teacher_id->EditAttributes() ?>>
</span>
<?php echo $document->teacher_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($document->timestamp->Visible) { // timestamp ?>
	<div id="r_timestamp" class="form-group">
		<label id="elh_document_timestamp" for="x_timestamp" class="<?php echo $document_edit->LeftColumnClass ?>"><?php echo $document->timestamp->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $document_edit->RightColumnClass ?>"><div<?php echo $document->timestamp->CellAttributes() ?>>
<span id="el_document_timestamp">
<textarea data-table="document" data-field="x_timestamp" name="x_timestamp" id="x_timestamp" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($document->timestamp->getPlaceHolder()) ?>"<?php echo $document->timestamp->EditAttributes() ?>><?php echo $document->timestamp->EditValue ?></textarea>
</span>
<?php echo $document->timestamp->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div><!-- /page* -->
<?php if (!$document_edit->IsModal) { ?>
<div class="form-group"><!-- buttons .form-group -->
	<div class="<?php echo $document_edit->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $document_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div><!-- /buttons offset -->
</div><!-- /buttons .form-group -->
<?php } ?>
</form>
<script type="text/javascript">
fdocumentedit.Init();
</script>
<?php
$document_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$document_edit->Page_Terminate();
?>
