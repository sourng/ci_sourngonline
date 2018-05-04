<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "gradeinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$grade_edit = NULL; // Initialize page object first

class cgrade_edit extends cgrade {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = '{1F40EE59-17A4-45D3-96B6-AA2082B3D64A}';

	// Table name
	var $TableName = 'grade';

	// Page object name
	var $PageObjName = 'grade_edit';

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

		// Table object (grade)
		if (!isset($GLOBALS["grade"]) || get_class($GLOBALS["grade"]) == "cgrade") {
			$GLOBALS["grade"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["grade"];
		}

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'grade', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("gradelist.php"));
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
		$this->grade_id->SetVisibility();
		if ($this->IsAdd() || $this->IsCopy() || $this->IsGridAdd())
			$this->grade_id->Visible = FALSE;
		$this->name->SetVisibility();
		$this->grade_point->SetVisibility();
		$this->mark_from->SetVisibility();
		$this->mark_upto->SetVisibility();
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
		global $EW_EXPORT, $grade;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($grade);
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
					if ($pageName == "gradeview.php")
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
			if ($objForm->HasValue("x_grade_id")) {
				$this->grade_id->setFormValue($objForm->GetValue("x_grade_id"));
			}
		} else {
			$this->CurrentAction = "I"; // Default action is display

			// Load key from QueryString
			$loadByQuery = FALSE;
			if (isset($_GET["grade_id"])) {
				$this->grade_id->setQueryStringValue($_GET["grade_id"]);
				$loadByQuery = TRUE;
			} else {
				$this->grade_id->CurrentValue = NULL;
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
					$this->Page_Terminate("gradelist.php"); // No matching record, return to list
				}
				break;
			Case "U": // Update
				$sReturnUrl = $this->getReturnUrl();
				if (ew_GetPageName($sReturnUrl) == "gradelist.php")
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
		if (!$this->grade_id->FldIsDetailKey)
			$this->grade_id->setFormValue($objForm->GetValue("x_grade_id"));
		if (!$this->name->FldIsDetailKey) {
			$this->name->setFormValue($objForm->GetValue("x_name"));
		}
		if (!$this->grade_point->FldIsDetailKey) {
			$this->grade_point->setFormValue($objForm->GetValue("x_grade_point"));
		}
		if (!$this->mark_from->FldIsDetailKey) {
			$this->mark_from->setFormValue($objForm->GetValue("x_mark_from"));
		}
		if (!$this->mark_upto->FldIsDetailKey) {
			$this->mark_upto->setFormValue($objForm->GetValue("x_mark_upto"));
		}
		if (!$this->comment->FldIsDetailKey) {
			$this->comment->setFormValue($objForm->GetValue("x_comment"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->grade_id->CurrentValue = $this->grade_id->FormValue;
		$this->name->CurrentValue = $this->name->FormValue;
		$this->grade_point->CurrentValue = $this->grade_point->FormValue;
		$this->mark_from->CurrentValue = $this->mark_from->FormValue;
		$this->mark_upto->CurrentValue = $this->mark_upto->FormValue;
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
		$this->grade_id->setDbValue($row['grade_id']);
		$this->name->setDbValue($row['name']);
		$this->grade_point->setDbValue($row['grade_point']);
		$this->mark_from->setDbValue($row['mark_from']);
		$this->mark_upto->setDbValue($row['mark_upto']);
		$this->comment->setDbValue($row['comment']);
	}

	// Return a row with default values
	function NewRow() {
		$row = array();
		$row['grade_id'] = NULL;
		$row['name'] = NULL;
		$row['grade_point'] = NULL;
		$row['mark_from'] = NULL;
		$row['mark_upto'] = NULL;
		$row['comment'] = NULL;
		return $row;
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF)
			return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->grade_id->DbValue = $row['grade_id'];
		$this->name->DbValue = $row['name'];
		$this->grade_point->DbValue = $row['grade_point'];
		$this->mark_from->DbValue = $row['mark_from'];
		$this->mark_upto->DbValue = $row['mark_upto'];
		$this->comment->DbValue = $row['comment'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("grade_id")) <> "")
			$this->grade_id->CurrentValue = $this->getKey("grade_id"); // grade_id
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
		// grade_id
		// name
		// grade_point
		// mark_from
		// mark_upto
		// comment

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// grade_id
		$this->grade_id->ViewValue = $this->grade_id->CurrentValue;
		$this->grade_id->ViewCustomAttributes = "";

		// name
		$this->name->ViewValue = $this->name->CurrentValue;
		$this->name->ViewCustomAttributes = "";

		// grade_point
		$this->grade_point->ViewValue = $this->grade_point->CurrentValue;
		$this->grade_point->ViewCustomAttributes = "";

		// mark_from
		$this->mark_from->ViewValue = $this->mark_from->CurrentValue;
		$this->mark_from->ViewCustomAttributes = "";

		// mark_upto
		$this->mark_upto->ViewValue = $this->mark_upto->CurrentValue;
		$this->mark_upto->ViewCustomAttributes = "";

		// comment
		$this->comment->ViewValue = $this->comment->CurrentValue;
		$this->comment->ViewCustomAttributes = "";

			// grade_id
			$this->grade_id->LinkCustomAttributes = "";
			$this->grade_id->HrefValue = "";
			$this->grade_id->TooltipValue = "";

			// name
			$this->name->LinkCustomAttributes = "";
			$this->name->HrefValue = "";
			$this->name->TooltipValue = "";

			// grade_point
			$this->grade_point->LinkCustomAttributes = "";
			$this->grade_point->HrefValue = "";
			$this->grade_point->TooltipValue = "";

			// mark_from
			$this->mark_from->LinkCustomAttributes = "";
			$this->mark_from->HrefValue = "";
			$this->mark_from->TooltipValue = "";

			// mark_upto
			$this->mark_upto->LinkCustomAttributes = "";
			$this->mark_upto->HrefValue = "";
			$this->mark_upto->TooltipValue = "";

			// comment
			$this->comment->LinkCustomAttributes = "";
			$this->comment->HrefValue = "";
			$this->comment->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// grade_id
			$this->grade_id->EditAttrs["class"] = "form-control";
			$this->grade_id->EditCustomAttributes = "";
			$this->grade_id->EditValue = $this->grade_id->CurrentValue;
			$this->grade_id->ViewCustomAttributes = "";

			// name
			$this->name->EditAttrs["class"] = "form-control";
			$this->name->EditCustomAttributes = "";
			$this->name->EditValue = ew_HtmlEncode($this->name->CurrentValue);
			$this->name->PlaceHolder = ew_RemoveHtml($this->name->FldCaption());

			// grade_point
			$this->grade_point->EditAttrs["class"] = "form-control";
			$this->grade_point->EditCustomAttributes = "";
			$this->grade_point->EditValue = ew_HtmlEncode($this->grade_point->CurrentValue);
			$this->grade_point->PlaceHolder = ew_RemoveHtml($this->grade_point->FldCaption());

			// mark_from
			$this->mark_from->EditAttrs["class"] = "form-control";
			$this->mark_from->EditCustomAttributes = "";
			$this->mark_from->EditValue = ew_HtmlEncode($this->mark_from->CurrentValue);
			$this->mark_from->PlaceHolder = ew_RemoveHtml($this->mark_from->FldCaption());

			// mark_upto
			$this->mark_upto->EditAttrs["class"] = "form-control";
			$this->mark_upto->EditCustomAttributes = "";
			$this->mark_upto->EditValue = ew_HtmlEncode($this->mark_upto->CurrentValue);
			$this->mark_upto->PlaceHolder = ew_RemoveHtml($this->mark_upto->FldCaption());

			// comment
			$this->comment->EditAttrs["class"] = "form-control";
			$this->comment->EditCustomAttributes = "";
			$this->comment->EditValue = ew_HtmlEncode($this->comment->CurrentValue);
			$this->comment->PlaceHolder = ew_RemoveHtml($this->comment->FldCaption());

			// Edit refer script
			// grade_id

			$this->grade_id->LinkCustomAttributes = "";
			$this->grade_id->HrefValue = "";

			// name
			$this->name->LinkCustomAttributes = "";
			$this->name->HrefValue = "";

			// grade_point
			$this->grade_point->LinkCustomAttributes = "";
			$this->grade_point->HrefValue = "";

			// mark_from
			$this->mark_from->LinkCustomAttributes = "";
			$this->mark_from->HrefValue = "";

			// mark_upto
			$this->mark_upto->LinkCustomAttributes = "";
			$this->mark_upto->HrefValue = "";

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
		if (!$this->name->FldIsDetailKey && !is_null($this->name->FormValue) && $this->name->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->name->FldCaption(), $this->name->ReqErrMsg));
		}
		if (!$this->grade_point->FldIsDetailKey && !is_null($this->grade_point->FormValue) && $this->grade_point->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->grade_point->FldCaption(), $this->grade_point->ReqErrMsg));
		}
		if (!$this->mark_from->FldIsDetailKey && !is_null($this->mark_from->FormValue) && $this->mark_from->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->mark_from->FldCaption(), $this->mark_from->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->mark_from->FormValue)) {
			ew_AddMessage($gsFormError, $this->mark_from->FldErrMsg());
		}
		if (!$this->mark_upto->FldIsDetailKey && !is_null($this->mark_upto->FormValue) && $this->mark_upto->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->mark_upto->FldCaption(), $this->mark_upto->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->mark_upto->FormValue)) {
			ew_AddMessage($gsFormError, $this->mark_upto->FldErrMsg());
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

			// name
			$this->name->SetDbValueDef($rsnew, $this->name->CurrentValue, "", $this->name->ReadOnly);

			// grade_point
			$this->grade_point->SetDbValueDef($rsnew, $this->grade_point->CurrentValue, "", $this->grade_point->ReadOnly);

			// mark_from
			$this->mark_from->SetDbValueDef($rsnew, $this->mark_from->CurrentValue, 0, $this->mark_from->ReadOnly);

			// mark_upto
			$this->mark_upto->SetDbValueDef($rsnew, $this->mark_upto->CurrentValue, 0, $this->mark_upto->ReadOnly);

			// comment
			$this->comment->SetDbValueDef($rsnew, $this->comment->CurrentValue, "", $this->comment->ReadOnly);

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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("gradelist.php"), "", $this->TableVar, TRUE);
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
if (!isset($grade_edit)) $grade_edit = new cgrade_edit();

// Page init
$grade_edit->Page_Init();

// Page main
$grade_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$grade_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = fgradeedit = new ew_Form("fgradeedit", "edit");

// Validate form
fgradeedit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_name");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $grade->name->FldCaption(), $grade->name->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_grade_point");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $grade->grade_point->FldCaption(), $grade->grade_point->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_mark_from");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $grade->mark_from->FldCaption(), $grade->mark_from->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_mark_from");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($grade->mark_from->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_mark_upto");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $grade->mark_upto->FldCaption(), $grade->mark_upto->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_mark_upto");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($grade->mark_upto->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_comment");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $grade->comment->FldCaption(), $grade->comment->ReqErrMsg)) ?>");

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
fgradeedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fgradeedit.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $grade_edit->ShowPageHeader(); ?>
<?php
$grade_edit->ShowMessage();
?>
<form name="fgradeedit" id="fgradeedit" class="<?php echo $grade_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($grade_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $grade_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="grade">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<input type="hidden" name="modal" value="<?php echo intval($grade_edit->IsModal) ?>">
<div class="ewEditDiv"><!-- page* -->
<?php if ($grade->grade_id->Visible) { // grade_id ?>
	<div id="r_grade_id" class="form-group">
		<label id="elh_grade_grade_id" class="<?php echo $grade_edit->LeftColumnClass ?>"><?php echo $grade->grade_id->FldCaption() ?></label>
		<div class="<?php echo $grade_edit->RightColumnClass ?>"><div<?php echo $grade->grade_id->CellAttributes() ?>>
<span id="el_grade_grade_id">
<span<?php echo $grade->grade_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $grade->grade_id->EditValue ?></p></span>
</span>
<input type="hidden" data-table="grade" data-field="x_grade_id" name="x_grade_id" id="x_grade_id" value="<?php echo ew_HtmlEncode($grade->grade_id->CurrentValue) ?>">
<?php echo $grade->grade_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($grade->name->Visible) { // name ?>
	<div id="r_name" class="form-group">
		<label id="elh_grade_name" for="x_name" class="<?php echo $grade_edit->LeftColumnClass ?>"><?php echo $grade->name->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $grade_edit->RightColumnClass ?>"><div<?php echo $grade->name->CellAttributes() ?>>
<span id="el_grade_name">
<textarea data-table="grade" data-field="x_name" name="x_name" id="x_name" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($grade->name->getPlaceHolder()) ?>"<?php echo $grade->name->EditAttributes() ?>><?php echo $grade->name->EditValue ?></textarea>
</span>
<?php echo $grade->name->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($grade->grade_point->Visible) { // grade_point ?>
	<div id="r_grade_point" class="form-group">
		<label id="elh_grade_grade_point" for="x_grade_point" class="<?php echo $grade_edit->LeftColumnClass ?>"><?php echo $grade->grade_point->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $grade_edit->RightColumnClass ?>"><div<?php echo $grade->grade_point->CellAttributes() ?>>
<span id="el_grade_grade_point">
<textarea data-table="grade" data-field="x_grade_point" name="x_grade_point" id="x_grade_point" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($grade->grade_point->getPlaceHolder()) ?>"<?php echo $grade->grade_point->EditAttributes() ?>><?php echo $grade->grade_point->EditValue ?></textarea>
</span>
<?php echo $grade->grade_point->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($grade->mark_from->Visible) { // mark_from ?>
	<div id="r_mark_from" class="form-group">
		<label id="elh_grade_mark_from" for="x_mark_from" class="<?php echo $grade_edit->LeftColumnClass ?>"><?php echo $grade->mark_from->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $grade_edit->RightColumnClass ?>"><div<?php echo $grade->mark_from->CellAttributes() ?>>
<span id="el_grade_mark_from">
<input type="text" data-table="grade" data-field="x_mark_from" name="x_mark_from" id="x_mark_from" size="30" placeholder="<?php echo ew_HtmlEncode($grade->mark_from->getPlaceHolder()) ?>" value="<?php echo $grade->mark_from->EditValue ?>"<?php echo $grade->mark_from->EditAttributes() ?>>
</span>
<?php echo $grade->mark_from->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($grade->mark_upto->Visible) { // mark_upto ?>
	<div id="r_mark_upto" class="form-group">
		<label id="elh_grade_mark_upto" for="x_mark_upto" class="<?php echo $grade_edit->LeftColumnClass ?>"><?php echo $grade->mark_upto->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $grade_edit->RightColumnClass ?>"><div<?php echo $grade->mark_upto->CellAttributes() ?>>
<span id="el_grade_mark_upto">
<input type="text" data-table="grade" data-field="x_mark_upto" name="x_mark_upto" id="x_mark_upto" size="30" placeholder="<?php echo ew_HtmlEncode($grade->mark_upto->getPlaceHolder()) ?>" value="<?php echo $grade->mark_upto->EditValue ?>"<?php echo $grade->mark_upto->EditAttributes() ?>>
</span>
<?php echo $grade->mark_upto->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($grade->comment->Visible) { // comment ?>
	<div id="r_comment" class="form-group">
		<label id="elh_grade_comment" for="x_comment" class="<?php echo $grade_edit->LeftColumnClass ?>"><?php echo $grade->comment->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $grade_edit->RightColumnClass ?>"><div<?php echo $grade->comment->CellAttributes() ?>>
<span id="el_grade_comment">
<textarea data-table="grade" data-field="x_comment" name="x_comment" id="x_comment" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($grade->comment->getPlaceHolder()) ?>"<?php echo $grade->comment->EditAttributes() ?>><?php echo $grade->comment->EditValue ?></textarea>
</span>
<?php echo $grade->comment->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div><!-- /page* -->
<?php if (!$grade_edit->IsModal) { ?>
<div class="form-group"><!-- buttons .form-group -->
	<div class="<?php echo $grade_edit->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $grade_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div><!-- /buttons offset -->
</div><!-- /buttons .form-group -->
<?php } ?>
</form>
<script type="text/javascript">
fgradeedit.Init();
</script>
<?php
$grade_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$grade_edit->Page_Terminate();
?>
