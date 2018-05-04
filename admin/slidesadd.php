<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "slidesinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$slides_add = NULL; // Initialize page object first

class cslides_add extends cslides {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = '{1F40EE59-17A4-45D3-96B6-AA2082B3D64A}';

	// Table name
	var $TableName = 'slides';

	// Page object name
	var $PageObjName = 'slides_add';

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

		// Table object (slides)
		if (!isset($GLOBALS["slides"]) || get_class($GLOBALS["slides"]) == "cslides") {
			$GLOBALS["slides"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["slides"];
		}

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'slides', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("slideslist.php"));
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
		$this->slide_name->SetVisibility();
		$this->slide_image->SetVisibility();
		$this->slide_url->SetVisibility();
		$this->show->SetVisibility();

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
		global $EW_EXPORT, $slides;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($slides);
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
					if ($pageName == "slidesview.php")
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
			if (@$_GET["slide_id"] != "") {
				$this->slide_id->setQueryStringValue($_GET["slide_id"]);
				$this->setKey("slide_id", $this->slide_id->CurrentValue); // Set up key
			} else {
				$this->setKey("slide_id", ""); // Clear key
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
					$this->Page_Terminate("slideslist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "slideslist.php")
						$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to List page with correct master key if necessary
					elseif (ew_GetPageName($sReturnUrl) == "slidesview.php")
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
		$this->slide_id->CurrentValue = NULL;
		$this->slide_id->OldValue = $this->slide_id->CurrentValue;
		$this->slide_name->CurrentValue = NULL;
		$this->slide_name->OldValue = $this->slide_name->CurrentValue;
		$this->slide_image->CurrentValue = NULL;
		$this->slide_image->OldValue = $this->slide_image->CurrentValue;
		$this->slide_url->CurrentValue = NULL;
		$this->slide_url->OldValue = $this->slide_url->CurrentValue;
		$this->show->CurrentValue = NULL;
		$this->show->OldValue = $this->show->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->slide_name->FldIsDetailKey) {
			$this->slide_name->setFormValue($objForm->GetValue("x_slide_name"));
		}
		if (!$this->slide_image->FldIsDetailKey) {
			$this->slide_image->setFormValue($objForm->GetValue("x_slide_image"));
		}
		if (!$this->slide_url->FldIsDetailKey) {
			$this->slide_url->setFormValue($objForm->GetValue("x_slide_url"));
		}
		if (!$this->show->FldIsDetailKey) {
			$this->show->setFormValue($objForm->GetValue("x_show"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->slide_name->CurrentValue = $this->slide_name->FormValue;
		$this->slide_image->CurrentValue = $this->slide_image->FormValue;
		$this->slide_url->CurrentValue = $this->slide_url->FormValue;
		$this->show->CurrentValue = $this->show->FormValue;
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
		$this->slide_id->setDbValue($row['slide_id']);
		$this->slide_name->setDbValue($row['slide_name']);
		$this->slide_image->setDbValue($row['slide_image']);
		$this->slide_url->setDbValue($row['slide_url']);
		$this->show->setDbValue($row['show']);
	}

	// Return a row with default values
	function NewRow() {
		$this->LoadDefaultValues();
		$row = array();
		$row['slide_id'] = $this->slide_id->CurrentValue;
		$row['slide_name'] = $this->slide_name->CurrentValue;
		$row['slide_image'] = $this->slide_image->CurrentValue;
		$row['slide_url'] = $this->slide_url->CurrentValue;
		$row['show'] = $this->show->CurrentValue;
		return $row;
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF)
			return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->slide_id->DbValue = $row['slide_id'];
		$this->slide_name->DbValue = $row['slide_name'];
		$this->slide_image->DbValue = $row['slide_image'];
		$this->slide_url->DbValue = $row['slide_url'];
		$this->show->DbValue = $row['show'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("slide_id")) <> "")
			$this->slide_id->CurrentValue = $this->getKey("slide_id"); // slide_id
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
		// slide_id
		// slide_name
		// slide_image
		// slide_url
		// show

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// slide_id
		$this->slide_id->ViewValue = $this->slide_id->CurrentValue;
		$this->slide_id->ViewCustomAttributes = "";

		// slide_name
		$this->slide_name->ViewValue = $this->slide_name->CurrentValue;
		$this->slide_name->ViewCustomAttributes = "";

		// slide_image
		$this->slide_image->ViewValue = $this->slide_image->CurrentValue;
		$this->slide_image->ViewCustomAttributes = "";

		// slide_url
		$this->slide_url->ViewValue = $this->slide_url->CurrentValue;
		$this->slide_url->ViewCustomAttributes = "";

		// show
		$this->show->ViewValue = $this->show->CurrentValue;
		$this->show->ViewCustomAttributes = "";

			// slide_name
			$this->slide_name->LinkCustomAttributes = "";
			$this->slide_name->HrefValue = "";
			$this->slide_name->TooltipValue = "";

			// slide_image
			$this->slide_image->LinkCustomAttributes = "";
			$this->slide_image->HrefValue = "";
			$this->slide_image->TooltipValue = "";

			// slide_url
			$this->slide_url->LinkCustomAttributes = "";
			$this->slide_url->HrefValue = "";
			$this->slide_url->TooltipValue = "";

			// show
			$this->show->LinkCustomAttributes = "";
			$this->show->HrefValue = "";
			$this->show->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// slide_name
			$this->slide_name->EditAttrs["class"] = "form-control";
			$this->slide_name->EditCustomAttributes = "";
			$this->slide_name->EditValue = ew_HtmlEncode($this->slide_name->CurrentValue);
			$this->slide_name->PlaceHolder = ew_RemoveHtml($this->slide_name->FldCaption());

			// slide_image
			$this->slide_image->EditAttrs["class"] = "form-control";
			$this->slide_image->EditCustomAttributes = "";
			$this->slide_image->EditValue = ew_HtmlEncode($this->slide_image->CurrentValue);
			$this->slide_image->PlaceHolder = ew_RemoveHtml($this->slide_image->FldCaption());

			// slide_url
			$this->slide_url->EditAttrs["class"] = "form-control";
			$this->slide_url->EditCustomAttributes = "";
			$this->slide_url->EditValue = ew_HtmlEncode($this->slide_url->CurrentValue);
			$this->slide_url->PlaceHolder = ew_RemoveHtml($this->slide_url->FldCaption());

			// show
			$this->show->EditAttrs["class"] = "form-control";
			$this->show->EditCustomAttributes = "";
			$this->show->EditValue = ew_HtmlEncode($this->show->CurrentValue);
			$this->show->PlaceHolder = ew_RemoveHtml($this->show->FldCaption());

			// Add refer script
			// slide_name

			$this->slide_name->LinkCustomAttributes = "";
			$this->slide_name->HrefValue = "";

			// slide_image
			$this->slide_image->LinkCustomAttributes = "";
			$this->slide_image->HrefValue = "";

			// slide_url
			$this->slide_url->LinkCustomAttributes = "";
			$this->slide_url->HrefValue = "";

			// show
			$this->show->LinkCustomAttributes = "";
			$this->show->HrefValue = "";
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
		if (!ew_CheckInteger($this->show->FormValue)) {
			ew_AddMessage($gsFormError, $this->show->FldErrMsg());
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

		// slide_name
		$this->slide_name->SetDbValueDef($rsnew, $this->slide_name->CurrentValue, NULL, FALSE);

		// slide_image
		$this->slide_image->SetDbValueDef($rsnew, $this->slide_image->CurrentValue, NULL, FALSE);

		// slide_url
		$this->slide_url->SetDbValueDef($rsnew, $this->slide_url->CurrentValue, NULL, FALSE);

		// show
		$this->show->SetDbValueDef($rsnew, $this->show->CurrentValue, NULL, FALSE);

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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("slideslist.php"), "", $this->TableVar, TRUE);
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
if (!isset($slides_add)) $slides_add = new cslides_add();

// Page init
$slides_add->Page_Init();

// Page main
$slides_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$slides_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "add";
var CurrentForm = fslidesadd = new ew_Form("fslidesadd", "add");

// Validate form
fslidesadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_show");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($slides->show->FldErrMsg()) ?>");

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
fslidesadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fslidesadd.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $slides_add->ShowPageHeader(); ?>
<?php
$slides_add->ShowMessage();
?>
<form name="fslidesadd" id="fslidesadd" class="<?php echo $slides_add->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($slides_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $slides_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="slides">
<input type="hidden" name="a_add" id="a_add" value="A">
<input type="hidden" name="modal" value="<?php echo intval($slides_add->IsModal) ?>">
<div class="ewAddDiv"><!-- page* -->
<?php if ($slides->slide_name->Visible) { // slide_name ?>
	<div id="r_slide_name" class="form-group">
		<label id="elh_slides_slide_name" for="x_slide_name" class="<?php echo $slides_add->LeftColumnClass ?>"><?php echo $slides->slide_name->FldCaption() ?></label>
		<div class="<?php echo $slides_add->RightColumnClass ?>"><div<?php echo $slides->slide_name->CellAttributes() ?>>
<span id="el_slides_slide_name">
<input type="text" data-table="slides" data-field="x_slide_name" name="x_slide_name" id="x_slide_name" size="30" maxlength="250" placeholder="<?php echo ew_HtmlEncode($slides->slide_name->getPlaceHolder()) ?>" value="<?php echo $slides->slide_name->EditValue ?>"<?php echo $slides->slide_name->EditAttributes() ?>>
</span>
<?php echo $slides->slide_name->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($slides->slide_image->Visible) { // slide_image ?>
	<div id="r_slide_image" class="form-group">
		<label id="elh_slides_slide_image" for="x_slide_image" class="<?php echo $slides_add->LeftColumnClass ?>"><?php echo $slides->slide_image->FldCaption() ?></label>
		<div class="<?php echo $slides_add->RightColumnClass ?>"><div<?php echo $slides->slide_image->CellAttributes() ?>>
<span id="el_slides_slide_image">
<input type="text" data-table="slides" data-field="x_slide_image" name="x_slide_image" id="x_slide_image" size="30" maxlength="250" placeholder="<?php echo ew_HtmlEncode($slides->slide_image->getPlaceHolder()) ?>" value="<?php echo $slides->slide_image->EditValue ?>"<?php echo $slides->slide_image->EditAttributes() ?>>
</span>
<?php echo $slides->slide_image->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($slides->slide_url->Visible) { // slide_url ?>
	<div id="r_slide_url" class="form-group">
		<label id="elh_slides_slide_url" for="x_slide_url" class="<?php echo $slides_add->LeftColumnClass ?>"><?php echo $slides->slide_url->FldCaption() ?></label>
		<div class="<?php echo $slides_add->RightColumnClass ?>"><div<?php echo $slides->slide_url->CellAttributes() ?>>
<span id="el_slides_slide_url">
<input type="text" data-table="slides" data-field="x_slide_url" name="x_slide_url" id="x_slide_url" size="30" maxlength="250" placeholder="<?php echo ew_HtmlEncode($slides->slide_url->getPlaceHolder()) ?>" value="<?php echo $slides->slide_url->EditValue ?>"<?php echo $slides->slide_url->EditAttributes() ?>>
</span>
<?php echo $slides->slide_url->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($slides->show->Visible) { // show ?>
	<div id="r_show" class="form-group">
		<label id="elh_slides_show" for="x_show" class="<?php echo $slides_add->LeftColumnClass ?>"><?php echo $slides->show->FldCaption() ?></label>
		<div class="<?php echo $slides_add->RightColumnClass ?>"><div<?php echo $slides->show->CellAttributes() ?>>
<span id="el_slides_show">
<input type="text" data-table="slides" data-field="x_show" name="x_show" id="x_show" size="30" placeholder="<?php echo ew_HtmlEncode($slides->show->getPlaceHolder()) ?>" value="<?php echo $slides->show->EditValue ?>"<?php echo $slides->show->EditAttributes() ?>>
</span>
<?php echo $slides->show->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div><!-- /page* -->
<?php if (!$slides_add->IsModal) { ?>
<div class="form-group"><!-- buttons .form-group -->
	<div class="<?php echo $slides_add->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $slides_add->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div><!-- /buttons offset -->
</div><!-- /buttons .form-group -->
<?php } ?>
</form>
<script type="text/javascript">
fslidesadd.Init();
</script>
<?php
$slides_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$slides_add->Page_Terminate();
?>
