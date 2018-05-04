<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "lessonsinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$lessons_edit = NULL; // Initialize page object first

class clessons_edit extends clessons {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = '{1F40EE59-17A4-45D3-96B6-AA2082B3D64A}';

	// Table name
	var $TableName = 'lessons';

	// Page object name
	var $PageObjName = 'lessons_edit';

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

		// Table object (lessons)
		if (!isset($GLOBALS["lessons"]) || get_class($GLOBALS["lessons"]) == "clessons") {
			$GLOBALS["lessons"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["lessons"];
		}

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'lessons', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("lessonslist.php"));
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
		$this->lesson_id->SetVisibility();
		if ($this->IsAdd() || $this->IsCopy() || $this->IsGridAdd())
			$this->lesson_id->Visible = FALSE;
		$this->title->SetVisibility();
		$this->lesson_no->SetVisibility();
		$this->description->SetVisibility();
		$this->detail->SetVisibility();
		$this->image->SetVisibility();
		$this->video->SetVisibility();
		$this->order->SetVisibility();
		$this->subject_id->SetVisibility();
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
		global $EW_EXPORT, $lessons;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($lessons);
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
					if ($pageName == "lessonsview.php")
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
			if ($objForm->HasValue("x_lesson_id")) {
				$this->lesson_id->setFormValue($objForm->GetValue("x_lesson_id"));
			}
			if ($objForm->HasValue("x_subject_id")) {
				$this->subject_id->setFormValue($objForm->GetValue("x_subject_id"));
			}
			if ($objForm->HasValue("x_teacher_id")) {
				$this->teacher_id->setFormValue($objForm->GetValue("x_teacher_id"));
			}
		} else {
			$this->CurrentAction = "I"; // Default action is display

			// Load key from QueryString
			$loadByQuery = FALSE;
			if (isset($_GET["lesson_id"])) {
				$this->lesson_id->setQueryStringValue($_GET["lesson_id"]);
				$loadByQuery = TRUE;
			} else {
				$this->lesson_id->CurrentValue = NULL;
			}
			if (isset($_GET["subject_id"])) {
				$this->subject_id->setQueryStringValue($_GET["subject_id"]);
				$loadByQuery = TRUE;
			} else {
				$this->subject_id->CurrentValue = NULL;
			}
			if (isset($_GET["teacher_id"])) {
				$this->teacher_id->setQueryStringValue($_GET["teacher_id"]);
				$loadByQuery = TRUE;
			} else {
				$this->teacher_id->CurrentValue = NULL;
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
					$this->Page_Terminate("lessonslist.php"); // No matching record, return to list
				}
				break;
			Case "U": // Update
				$sReturnUrl = $this->getReturnUrl();
				if (ew_GetPageName($sReturnUrl) == "lessonslist.php")
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
		if (!$this->lesson_id->FldIsDetailKey)
			$this->lesson_id->setFormValue($objForm->GetValue("x_lesson_id"));
		if (!$this->title->FldIsDetailKey) {
			$this->title->setFormValue($objForm->GetValue("x_title"));
		}
		if (!$this->lesson_no->FldIsDetailKey) {
			$this->lesson_no->setFormValue($objForm->GetValue("x_lesson_no"));
		}
		if (!$this->description->FldIsDetailKey) {
			$this->description->setFormValue($objForm->GetValue("x_description"));
		}
		if (!$this->detail->FldIsDetailKey) {
			$this->detail->setFormValue($objForm->GetValue("x_detail"));
		}
		if (!$this->image->FldIsDetailKey) {
			$this->image->setFormValue($objForm->GetValue("x_image"));
		}
		if (!$this->video->FldIsDetailKey) {
			$this->video->setFormValue($objForm->GetValue("x_video"));
		}
		if (!$this->order->FldIsDetailKey) {
			$this->order->setFormValue($objForm->GetValue("x_order"));
		}
		if (!$this->subject_id->FldIsDetailKey) {
			$this->subject_id->setFormValue($objForm->GetValue("x_subject_id"));
		}
		if (!$this->teacher_id->FldIsDetailKey) {
			$this->teacher_id->setFormValue($objForm->GetValue("x_teacher_id"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->lesson_id->CurrentValue = $this->lesson_id->FormValue;
		$this->title->CurrentValue = $this->title->FormValue;
		$this->lesson_no->CurrentValue = $this->lesson_no->FormValue;
		$this->description->CurrentValue = $this->description->FormValue;
		$this->detail->CurrentValue = $this->detail->FormValue;
		$this->image->CurrentValue = $this->image->FormValue;
		$this->video->CurrentValue = $this->video->FormValue;
		$this->order->CurrentValue = $this->order->FormValue;
		$this->subject_id->CurrentValue = $this->subject_id->FormValue;
		$this->teacher_id->CurrentValue = $this->teacher_id->FormValue;
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
		$this->lesson_id->setDbValue($row['lesson_id']);
		$this->title->setDbValue($row['title']);
		$this->lesson_no->setDbValue($row['lesson_no']);
		$this->description->setDbValue($row['description']);
		$this->detail->setDbValue($row['detail']);
		$this->image->setDbValue($row['image']);
		$this->video->setDbValue($row['video']);
		$this->order->setDbValue($row['order']);
		$this->subject_id->setDbValue($row['subject_id']);
		$this->teacher_id->setDbValue($row['teacher_id']);
	}

	// Return a row with default values
	function NewRow() {
		$row = array();
		$row['lesson_id'] = NULL;
		$row['title'] = NULL;
		$row['lesson_no'] = NULL;
		$row['description'] = NULL;
		$row['detail'] = NULL;
		$row['image'] = NULL;
		$row['video'] = NULL;
		$row['order'] = NULL;
		$row['subject_id'] = NULL;
		$row['teacher_id'] = NULL;
		return $row;
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF)
			return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->lesson_id->DbValue = $row['lesson_id'];
		$this->title->DbValue = $row['title'];
		$this->lesson_no->DbValue = $row['lesson_no'];
		$this->description->DbValue = $row['description'];
		$this->detail->DbValue = $row['detail'];
		$this->image->DbValue = $row['image'];
		$this->video->DbValue = $row['video'];
		$this->order->DbValue = $row['order'];
		$this->subject_id->DbValue = $row['subject_id'];
		$this->teacher_id->DbValue = $row['teacher_id'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("lesson_id")) <> "")
			$this->lesson_id->CurrentValue = $this->getKey("lesson_id"); // lesson_id
		else
			$bValidKey = FALSE;
		if (strval($this->getKey("subject_id")) <> "")
			$this->subject_id->CurrentValue = $this->getKey("subject_id"); // subject_id
		else
			$bValidKey = FALSE;
		if (strval($this->getKey("teacher_id")) <> "")
			$this->teacher_id->CurrentValue = $this->getKey("teacher_id"); // teacher_id
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
		// lesson_id
		// title
		// lesson_no
		// description
		// detail
		// image
		// video
		// order
		// subject_id
		// teacher_id

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// lesson_id
		$this->lesson_id->ViewValue = $this->lesson_id->CurrentValue;
		$this->lesson_id->ViewCustomAttributes = "";

		// title
		$this->title->ViewValue = $this->title->CurrentValue;
		$this->title->ViewCustomAttributes = "";

		// lesson_no
		$this->lesson_no->ViewValue = $this->lesson_no->CurrentValue;
		$this->lesson_no->ViewCustomAttributes = "";

		// description
		$this->description->ViewValue = $this->description->CurrentValue;
		$this->description->ViewCustomAttributes = "";

		// detail
		$this->detail->ViewValue = $this->detail->CurrentValue;
		$this->detail->ViewCustomAttributes = "";

		// image
		$this->image->ViewValue = $this->image->CurrentValue;
		$this->image->ViewCustomAttributes = "";

		// video
		$this->video->ViewValue = $this->video->CurrentValue;
		$this->video->ViewCustomAttributes = "";

		// order
		$this->order->ViewValue = $this->order->CurrentValue;
		$this->order->ViewCustomAttributes = "";

		// subject_id
		$this->subject_id->ViewValue = $this->subject_id->CurrentValue;
		$this->subject_id->ViewCustomAttributes = "";

		// teacher_id
		$this->teacher_id->ViewValue = $this->teacher_id->CurrentValue;
		$this->teacher_id->ViewCustomAttributes = "";

			// lesson_id
			$this->lesson_id->LinkCustomAttributes = "";
			$this->lesson_id->HrefValue = "";
			$this->lesson_id->TooltipValue = "";

			// title
			$this->title->LinkCustomAttributes = "";
			$this->title->HrefValue = "";
			$this->title->TooltipValue = "";

			// lesson_no
			$this->lesson_no->LinkCustomAttributes = "";
			$this->lesson_no->HrefValue = "";
			$this->lesson_no->TooltipValue = "";

			// description
			$this->description->LinkCustomAttributes = "";
			$this->description->HrefValue = "";
			$this->description->TooltipValue = "";

			// detail
			$this->detail->LinkCustomAttributes = "";
			$this->detail->HrefValue = "";
			$this->detail->TooltipValue = "";

			// image
			$this->image->LinkCustomAttributes = "";
			$this->image->HrefValue = "";
			$this->image->TooltipValue = "";

			// video
			$this->video->LinkCustomAttributes = "";
			$this->video->HrefValue = "";
			$this->video->TooltipValue = "";

			// order
			$this->order->LinkCustomAttributes = "";
			$this->order->HrefValue = "";
			$this->order->TooltipValue = "";

			// subject_id
			$this->subject_id->LinkCustomAttributes = "";
			$this->subject_id->HrefValue = "";
			$this->subject_id->TooltipValue = "";

			// teacher_id
			$this->teacher_id->LinkCustomAttributes = "";
			$this->teacher_id->HrefValue = "";
			$this->teacher_id->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// lesson_id
			$this->lesson_id->EditAttrs["class"] = "form-control";
			$this->lesson_id->EditCustomAttributes = "";
			$this->lesson_id->EditValue = $this->lesson_id->CurrentValue;
			$this->lesson_id->ViewCustomAttributes = "";

			// title
			$this->title->EditAttrs["class"] = "form-control";
			$this->title->EditCustomAttributes = "";
			$this->title->EditValue = ew_HtmlEncode($this->title->CurrentValue);
			$this->title->PlaceHolder = ew_RemoveHtml($this->title->FldCaption());

			// lesson_no
			$this->lesson_no->EditAttrs["class"] = "form-control";
			$this->lesson_no->EditCustomAttributes = "";
			$this->lesson_no->EditValue = ew_HtmlEncode($this->lesson_no->CurrentValue);
			$this->lesson_no->PlaceHolder = ew_RemoveHtml($this->lesson_no->FldCaption());

			// description
			$this->description->EditAttrs["class"] = "form-control";
			$this->description->EditCustomAttributes = "";
			$this->description->EditValue = ew_HtmlEncode($this->description->CurrentValue);
			$this->description->PlaceHolder = ew_RemoveHtml($this->description->FldCaption());

			// detail
			$this->detail->EditAttrs["class"] = "form-control";
			$this->detail->EditCustomAttributes = "";
			$this->detail->EditValue = ew_HtmlEncode($this->detail->CurrentValue);
			$this->detail->PlaceHolder = ew_RemoveHtml($this->detail->FldCaption());

			// image
			$this->image->EditAttrs["class"] = "form-control";
			$this->image->EditCustomAttributes = "";
			$this->image->EditValue = ew_HtmlEncode($this->image->CurrentValue);
			$this->image->PlaceHolder = ew_RemoveHtml($this->image->FldCaption());

			// video
			$this->video->EditAttrs["class"] = "form-control";
			$this->video->EditCustomAttributes = "";
			$this->video->EditValue = ew_HtmlEncode($this->video->CurrentValue);
			$this->video->PlaceHolder = ew_RemoveHtml($this->video->FldCaption());

			// order
			$this->order->EditAttrs["class"] = "form-control";
			$this->order->EditCustomAttributes = "";
			$this->order->EditValue = ew_HtmlEncode($this->order->CurrentValue);
			$this->order->PlaceHolder = ew_RemoveHtml($this->order->FldCaption());

			// subject_id
			$this->subject_id->EditAttrs["class"] = "form-control";
			$this->subject_id->EditCustomAttributes = "";
			$this->subject_id->EditValue = $this->subject_id->CurrentValue;
			$this->subject_id->ViewCustomAttributes = "";

			// teacher_id
			$this->teacher_id->EditAttrs["class"] = "form-control";
			$this->teacher_id->EditCustomAttributes = "";
			$this->teacher_id->EditValue = $this->teacher_id->CurrentValue;
			$this->teacher_id->ViewCustomAttributes = "";

			// Edit refer script
			// lesson_id

			$this->lesson_id->LinkCustomAttributes = "";
			$this->lesson_id->HrefValue = "";

			// title
			$this->title->LinkCustomAttributes = "";
			$this->title->HrefValue = "";

			// lesson_no
			$this->lesson_no->LinkCustomAttributes = "";
			$this->lesson_no->HrefValue = "";

			// description
			$this->description->LinkCustomAttributes = "";
			$this->description->HrefValue = "";

			// detail
			$this->detail->LinkCustomAttributes = "";
			$this->detail->HrefValue = "";

			// image
			$this->image->LinkCustomAttributes = "";
			$this->image->HrefValue = "";

			// video
			$this->video->LinkCustomAttributes = "";
			$this->video->HrefValue = "";

			// order
			$this->order->LinkCustomAttributes = "";
			$this->order->HrefValue = "";

			// subject_id
			$this->subject_id->LinkCustomAttributes = "";
			$this->subject_id->HrefValue = "";

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
		if (!ew_CheckInteger($this->order->FormValue)) {
			ew_AddMessage($gsFormError, $this->order->FldErrMsg());
		}
		if (!$this->subject_id->FldIsDetailKey && !is_null($this->subject_id->FormValue) && $this->subject_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->subject_id->FldCaption(), $this->subject_id->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->subject_id->FormValue)) {
			ew_AddMessage($gsFormError, $this->subject_id->FldErrMsg());
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
			$this->title->SetDbValueDef($rsnew, $this->title->CurrentValue, NULL, $this->title->ReadOnly);

			// lesson_no
			$this->lesson_no->SetDbValueDef($rsnew, $this->lesson_no->CurrentValue, NULL, $this->lesson_no->ReadOnly);

			// description
			$this->description->SetDbValueDef($rsnew, $this->description->CurrentValue, NULL, $this->description->ReadOnly);

			// detail
			$this->detail->SetDbValueDef($rsnew, $this->detail->CurrentValue, NULL, $this->detail->ReadOnly);

			// image
			$this->image->SetDbValueDef($rsnew, $this->image->CurrentValue, NULL, $this->image->ReadOnly);

			// video
			$this->video->SetDbValueDef($rsnew, $this->video->CurrentValue, NULL, $this->video->ReadOnly);

			// order
			$this->order->SetDbValueDef($rsnew, $this->order->CurrentValue, NULL, $this->order->ReadOnly);

			// subject_id
			// teacher_id
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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("lessonslist.php"), "", $this->TableVar, TRUE);
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
if (!isset($lessons_edit)) $lessons_edit = new clessons_edit();

// Page init
$lessons_edit->Page_Init();

// Page main
$lessons_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$lessons_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = flessonsedit = new ew_Form("flessonsedit", "edit");

// Validate form
flessonsedit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_order");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($lessons->order->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_subject_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $lessons->subject_id->FldCaption(), $lessons->subject_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_subject_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($lessons->subject_id->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_teacher_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $lessons->teacher_id->FldCaption(), $lessons->teacher_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_teacher_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($lessons->teacher_id->FldErrMsg()) ?>");

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
flessonsedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
flessonsedit.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $lessons_edit->ShowPageHeader(); ?>
<?php
$lessons_edit->ShowMessage();
?>
<form name="flessonsedit" id="flessonsedit" class="<?php echo $lessons_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($lessons_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $lessons_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="lessons">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<input type="hidden" name="modal" value="<?php echo intval($lessons_edit->IsModal) ?>">
<div class="ewEditDiv"><!-- page* -->
<?php if ($lessons->lesson_id->Visible) { // lesson_id ?>
	<div id="r_lesson_id" class="form-group">
		<label id="elh_lessons_lesson_id" class="<?php echo $lessons_edit->LeftColumnClass ?>"><?php echo $lessons->lesson_id->FldCaption() ?></label>
		<div class="<?php echo $lessons_edit->RightColumnClass ?>"><div<?php echo $lessons->lesson_id->CellAttributes() ?>>
<span id="el_lessons_lesson_id">
<span<?php echo $lessons->lesson_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $lessons->lesson_id->EditValue ?></p></span>
</span>
<input type="hidden" data-table="lessons" data-field="x_lesson_id" name="x_lesson_id" id="x_lesson_id" value="<?php echo ew_HtmlEncode($lessons->lesson_id->CurrentValue) ?>">
<?php echo $lessons->lesson_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($lessons->title->Visible) { // title ?>
	<div id="r_title" class="form-group">
		<label id="elh_lessons_title" for="x_title" class="<?php echo $lessons_edit->LeftColumnClass ?>"><?php echo $lessons->title->FldCaption() ?></label>
		<div class="<?php echo $lessons_edit->RightColumnClass ?>"><div<?php echo $lessons->title->CellAttributes() ?>>
<span id="el_lessons_title">
<input type="text" data-table="lessons" data-field="x_title" name="x_title" id="x_title" size="30" maxlength="250" placeholder="<?php echo ew_HtmlEncode($lessons->title->getPlaceHolder()) ?>" value="<?php echo $lessons->title->EditValue ?>"<?php echo $lessons->title->EditAttributes() ?>>
</span>
<?php echo $lessons->title->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($lessons->lesson_no->Visible) { // lesson_no ?>
	<div id="r_lesson_no" class="form-group">
		<label id="elh_lessons_lesson_no" for="x_lesson_no" class="<?php echo $lessons_edit->LeftColumnClass ?>"><?php echo $lessons->lesson_no->FldCaption() ?></label>
		<div class="<?php echo $lessons_edit->RightColumnClass ?>"><div<?php echo $lessons->lesson_no->CellAttributes() ?>>
<span id="el_lessons_lesson_no">
<input type="text" data-table="lessons" data-field="x_lesson_no" name="x_lesson_no" id="x_lesson_no" size="30" maxlength="250" placeholder="<?php echo ew_HtmlEncode($lessons->lesson_no->getPlaceHolder()) ?>" value="<?php echo $lessons->lesson_no->EditValue ?>"<?php echo $lessons->lesson_no->EditAttributes() ?>>
</span>
<?php echo $lessons->lesson_no->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($lessons->description->Visible) { // description ?>
	<div id="r_description" class="form-group">
		<label id="elh_lessons_description" for="x_description" class="<?php echo $lessons_edit->LeftColumnClass ?>"><?php echo $lessons->description->FldCaption() ?></label>
		<div class="<?php echo $lessons_edit->RightColumnClass ?>"><div<?php echo $lessons->description->CellAttributes() ?>>
<span id="el_lessons_description">
<input type="text" data-table="lessons" data-field="x_description" name="x_description" id="x_description" size="30" maxlength="250" placeholder="<?php echo ew_HtmlEncode($lessons->description->getPlaceHolder()) ?>" value="<?php echo $lessons->description->EditValue ?>"<?php echo $lessons->description->EditAttributes() ?>>
</span>
<?php echo $lessons->description->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($lessons->detail->Visible) { // detail ?>
	<div id="r_detail" class="form-group">
		<label id="elh_lessons_detail" for="x_detail" class="<?php echo $lessons_edit->LeftColumnClass ?>"><?php echo $lessons->detail->FldCaption() ?></label>
		<div class="<?php echo $lessons_edit->RightColumnClass ?>"><div<?php echo $lessons->detail->CellAttributes() ?>>
<span id="el_lessons_detail">
<textarea data-table="lessons" data-field="x_detail" name="x_detail" id="x_detail" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($lessons->detail->getPlaceHolder()) ?>"<?php echo $lessons->detail->EditAttributes() ?>><?php echo $lessons->detail->EditValue ?></textarea>
</span>
<?php echo $lessons->detail->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($lessons->image->Visible) { // image ?>
	<div id="r_image" class="form-group">
		<label id="elh_lessons_image" for="x_image" class="<?php echo $lessons_edit->LeftColumnClass ?>"><?php echo $lessons->image->FldCaption() ?></label>
		<div class="<?php echo $lessons_edit->RightColumnClass ?>"><div<?php echo $lessons->image->CellAttributes() ?>>
<span id="el_lessons_image">
<textarea data-table="lessons" data-field="x_image" name="x_image" id="x_image" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($lessons->image->getPlaceHolder()) ?>"<?php echo $lessons->image->EditAttributes() ?>><?php echo $lessons->image->EditValue ?></textarea>
</span>
<?php echo $lessons->image->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($lessons->video->Visible) { // video ?>
	<div id="r_video" class="form-group">
		<label id="elh_lessons_video" for="x_video" class="<?php echo $lessons_edit->LeftColumnClass ?>"><?php echo $lessons->video->FldCaption() ?></label>
		<div class="<?php echo $lessons_edit->RightColumnClass ?>"><div<?php echo $lessons->video->CellAttributes() ?>>
<span id="el_lessons_video">
<input type="text" data-table="lessons" data-field="x_video" name="x_video" id="x_video" size="30" maxlength="250" placeholder="<?php echo ew_HtmlEncode($lessons->video->getPlaceHolder()) ?>" value="<?php echo $lessons->video->EditValue ?>"<?php echo $lessons->video->EditAttributes() ?>>
</span>
<?php echo $lessons->video->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($lessons->order->Visible) { // order ?>
	<div id="r_order" class="form-group">
		<label id="elh_lessons_order" for="x_order" class="<?php echo $lessons_edit->LeftColumnClass ?>"><?php echo $lessons->order->FldCaption() ?></label>
		<div class="<?php echo $lessons_edit->RightColumnClass ?>"><div<?php echo $lessons->order->CellAttributes() ?>>
<span id="el_lessons_order">
<input type="text" data-table="lessons" data-field="x_order" name="x_order" id="x_order" size="30" placeholder="<?php echo ew_HtmlEncode($lessons->order->getPlaceHolder()) ?>" value="<?php echo $lessons->order->EditValue ?>"<?php echo $lessons->order->EditAttributes() ?>>
</span>
<?php echo $lessons->order->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($lessons->subject_id->Visible) { // subject_id ?>
	<div id="r_subject_id" class="form-group">
		<label id="elh_lessons_subject_id" for="x_subject_id" class="<?php echo $lessons_edit->LeftColumnClass ?>"><?php echo $lessons->subject_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $lessons_edit->RightColumnClass ?>"><div<?php echo $lessons->subject_id->CellAttributes() ?>>
<span id="el_lessons_subject_id">
<span<?php echo $lessons->subject_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $lessons->subject_id->EditValue ?></p></span>
</span>
<input type="hidden" data-table="lessons" data-field="x_subject_id" name="x_subject_id" id="x_subject_id" value="<?php echo ew_HtmlEncode($lessons->subject_id->CurrentValue) ?>">
<?php echo $lessons->subject_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($lessons->teacher_id->Visible) { // teacher_id ?>
	<div id="r_teacher_id" class="form-group">
		<label id="elh_lessons_teacher_id" for="x_teacher_id" class="<?php echo $lessons_edit->LeftColumnClass ?>"><?php echo $lessons->teacher_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $lessons_edit->RightColumnClass ?>"><div<?php echo $lessons->teacher_id->CellAttributes() ?>>
<span id="el_lessons_teacher_id">
<span<?php echo $lessons->teacher_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $lessons->teacher_id->EditValue ?></p></span>
</span>
<input type="hidden" data-table="lessons" data-field="x_teacher_id" name="x_teacher_id" id="x_teacher_id" value="<?php echo ew_HtmlEncode($lessons->teacher_id->CurrentValue) ?>">
<?php echo $lessons->teacher_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div><!-- /page* -->
<?php if (!$lessons_edit->IsModal) { ?>
<div class="form-group"><!-- buttons .form-group -->
	<div class="<?php echo $lessons_edit->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $lessons_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div><!-- /buttons offset -->
</div><!-- /buttons .form-group -->
<?php } ?>
</form>
<script type="text/javascript">
flessonsedit.Init();
</script>
<?php
$lessons_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$lessons_edit->Page_Terminate();
?>
