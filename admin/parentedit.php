<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "parentinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$parent_edit = NULL; // Initialize page object first

class cparent_edit extends cparent {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = '{1F40EE59-17A4-45D3-96B6-AA2082B3D64A}';

	// Table name
	var $TableName = 'parent';

	// Page object name
	var $PageObjName = 'parent_edit';

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

		// Table object (parent)
		if (!isset($GLOBALS["parent"]) || get_class($GLOBALS["parent"]) == "cparent") {
			$GLOBALS["parent"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["parent"];
		}

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'parent', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("parentlist.php"));
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
		$this->parent_id->SetVisibility();
		if ($this->IsAdd() || $this->IsCopy() || $this->IsGridAdd())
			$this->parent_id->Visible = FALSE;
		$this->name->SetVisibility();
		$this->_email->SetVisibility();
		$this->password->SetVisibility();
		$this->phone->SetVisibility();
		$this->address->SetVisibility();
		$this->profession->SetVisibility();
		$this->authentication_key->SetVisibility();

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
		global $EW_EXPORT, $parent;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($parent);
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
					if ($pageName == "parentview.php")
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
			if ($objForm->HasValue("x_parent_id")) {
				$this->parent_id->setFormValue($objForm->GetValue("x_parent_id"));
			}
		} else {
			$this->CurrentAction = "I"; // Default action is display

			// Load key from QueryString
			$loadByQuery = FALSE;
			if (isset($_GET["parent_id"])) {
				$this->parent_id->setQueryStringValue($_GET["parent_id"]);
				$loadByQuery = TRUE;
			} else {
				$this->parent_id->CurrentValue = NULL;
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
					$this->Page_Terminate("parentlist.php"); // No matching record, return to list
				}
				break;
			Case "U": // Update
				$sReturnUrl = $this->getReturnUrl();
				if (ew_GetPageName($sReturnUrl) == "parentlist.php")
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
		if (!$this->parent_id->FldIsDetailKey)
			$this->parent_id->setFormValue($objForm->GetValue("x_parent_id"));
		if (!$this->name->FldIsDetailKey) {
			$this->name->setFormValue($objForm->GetValue("x_name"));
		}
		if (!$this->_email->FldIsDetailKey) {
			$this->_email->setFormValue($objForm->GetValue("x__email"));
		}
		if (!$this->password->FldIsDetailKey) {
			$this->password->setFormValue($objForm->GetValue("x_password"));
		}
		if (!$this->phone->FldIsDetailKey) {
			$this->phone->setFormValue($objForm->GetValue("x_phone"));
		}
		if (!$this->address->FldIsDetailKey) {
			$this->address->setFormValue($objForm->GetValue("x_address"));
		}
		if (!$this->profession->FldIsDetailKey) {
			$this->profession->setFormValue($objForm->GetValue("x_profession"));
		}
		if (!$this->authentication_key->FldIsDetailKey) {
			$this->authentication_key->setFormValue($objForm->GetValue("x_authentication_key"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->parent_id->CurrentValue = $this->parent_id->FormValue;
		$this->name->CurrentValue = $this->name->FormValue;
		$this->_email->CurrentValue = $this->_email->FormValue;
		$this->password->CurrentValue = $this->password->FormValue;
		$this->phone->CurrentValue = $this->phone->FormValue;
		$this->address->CurrentValue = $this->address->FormValue;
		$this->profession->CurrentValue = $this->profession->FormValue;
		$this->authentication_key->CurrentValue = $this->authentication_key->FormValue;
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
		$this->parent_id->setDbValue($row['parent_id']);
		$this->name->setDbValue($row['name']);
		$this->_email->setDbValue($row['email']);
		$this->password->setDbValue($row['password']);
		$this->phone->setDbValue($row['phone']);
		$this->address->setDbValue($row['address']);
		$this->profession->setDbValue($row['profession']);
		$this->authentication_key->setDbValue($row['authentication_key']);
	}

	// Return a row with default values
	function NewRow() {
		$row = array();
		$row['parent_id'] = NULL;
		$row['name'] = NULL;
		$row['email'] = NULL;
		$row['password'] = NULL;
		$row['phone'] = NULL;
		$row['address'] = NULL;
		$row['profession'] = NULL;
		$row['authentication_key'] = NULL;
		return $row;
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF)
			return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->parent_id->DbValue = $row['parent_id'];
		$this->name->DbValue = $row['name'];
		$this->_email->DbValue = $row['email'];
		$this->password->DbValue = $row['password'];
		$this->phone->DbValue = $row['phone'];
		$this->address->DbValue = $row['address'];
		$this->profession->DbValue = $row['profession'];
		$this->authentication_key->DbValue = $row['authentication_key'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("parent_id")) <> "")
			$this->parent_id->CurrentValue = $this->getKey("parent_id"); // parent_id
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
		// parent_id
		// name
		// email
		// password
		// phone
		// address
		// profession
		// authentication_key

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// parent_id
		$this->parent_id->ViewValue = $this->parent_id->CurrentValue;
		$this->parent_id->ViewCustomAttributes = "";

		// name
		$this->name->ViewValue = $this->name->CurrentValue;
		$this->name->ViewCustomAttributes = "";

		// email
		$this->_email->ViewValue = $this->_email->CurrentValue;
		$this->_email->ViewCustomAttributes = "";

		// password
		$this->password->ViewValue = $this->password->CurrentValue;
		$this->password->ViewCustomAttributes = "";

		// phone
		$this->phone->ViewValue = $this->phone->CurrentValue;
		$this->phone->ViewCustomAttributes = "";

		// address
		$this->address->ViewValue = $this->address->CurrentValue;
		$this->address->ViewCustomAttributes = "";

		// profession
		$this->profession->ViewValue = $this->profession->CurrentValue;
		$this->profession->ViewCustomAttributes = "";

		// authentication_key
		$this->authentication_key->ViewValue = $this->authentication_key->CurrentValue;
		$this->authentication_key->ViewCustomAttributes = "";

			// parent_id
			$this->parent_id->LinkCustomAttributes = "";
			$this->parent_id->HrefValue = "";
			$this->parent_id->TooltipValue = "";

			// name
			$this->name->LinkCustomAttributes = "";
			$this->name->HrefValue = "";
			$this->name->TooltipValue = "";

			// email
			$this->_email->LinkCustomAttributes = "";
			$this->_email->HrefValue = "";
			$this->_email->TooltipValue = "";

			// password
			$this->password->LinkCustomAttributes = "";
			$this->password->HrefValue = "";
			$this->password->TooltipValue = "";

			// phone
			$this->phone->LinkCustomAttributes = "";
			$this->phone->HrefValue = "";
			$this->phone->TooltipValue = "";

			// address
			$this->address->LinkCustomAttributes = "";
			$this->address->HrefValue = "";
			$this->address->TooltipValue = "";

			// profession
			$this->profession->LinkCustomAttributes = "";
			$this->profession->HrefValue = "";
			$this->profession->TooltipValue = "";

			// authentication_key
			$this->authentication_key->LinkCustomAttributes = "";
			$this->authentication_key->HrefValue = "";
			$this->authentication_key->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// parent_id
			$this->parent_id->EditAttrs["class"] = "form-control";
			$this->parent_id->EditCustomAttributes = "";
			$this->parent_id->EditValue = $this->parent_id->CurrentValue;
			$this->parent_id->ViewCustomAttributes = "";

			// name
			$this->name->EditAttrs["class"] = "form-control";
			$this->name->EditCustomAttributes = "";
			$this->name->EditValue = ew_HtmlEncode($this->name->CurrentValue);
			$this->name->PlaceHolder = ew_RemoveHtml($this->name->FldCaption());

			// email
			$this->_email->EditAttrs["class"] = "form-control";
			$this->_email->EditCustomAttributes = "";
			$this->_email->EditValue = ew_HtmlEncode($this->_email->CurrentValue);
			$this->_email->PlaceHolder = ew_RemoveHtml($this->_email->FldCaption());

			// password
			$this->password->EditAttrs["class"] = "form-control";
			$this->password->EditCustomAttributes = "";
			$this->password->EditValue = ew_HtmlEncode($this->password->CurrentValue);
			$this->password->PlaceHolder = ew_RemoveHtml($this->password->FldCaption());

			// phone
			$this->phone->EditAttrs["class"] = "form-control";
			$this->phone->EditCustomAttributes = "";
			$this->phone->EditValue = ew_HtmlEncode($this->phone->CurrentValue);
			$this->phone->PlaceHolder = ew_RemoveHtml($this->phone->FldCaption());

			// address
			$this->address->EditAttrs["class"] = "form-control";
			$this->address->EditCustomAttributes = "";
			$this->address->EditValue = ew_HtmlEncode($this->address->CurrentValue);
			$this->address->PlaceHolder = ew_RemoveHtml($this->address->FldCaption());

			// profession
			$this->profession->EditAttrs["class"] = "form-control";
			$this->profession->EditCustomAttributes = "";
			$this->profession->EditValue = ew_HtmlEncode($this->profession->CurrentValue);
			$this->profession->PlaceHolder = ew_RemoveHtml($this->profession->FldCaption());

			// authentication_key
			$this->authentication_key->EditAttrs["class"] = "form-control";
			$this->authentication_key->EditCustomAttributes = "";
			$this->authentication_key->EditValue = ew_HtmlEncode($this->authentication_key->CurrentValue);
			$this->authentication_key->PlaceHolder = ew_RemoveHtml($this->authentication_key->FldCaption());

			// Edit refer script
			// parent_id

			$this->parent_id->LinkCustomAttributes = "";
			$this->parent_id->HrefValue = "";

			// name
			$this->name->LinkCustomAttributes = "";
			$this->name->HrefValue = "";

			// email
			$this->_email->LinkCustomAttributes = "";
			$this->_email->HrefValue = "";

			// password
			$this->password->LinkCustomAttributes = "";
			$this->password->HrefValue = "";

			// phone
			$this->phone->LinkCustomAttributes = "";
			$this->phone->HrefValue = "";

			// address
			$this->address->LinkCustomAttributes = "";
			$this->address->HrefValue = "";

			// profession
			$this->profession->LinkCustomAttributes = "";
			$this->profession->HrefValue = "";

			// authentication_key
			$this->authentication_key->LinkCustomAttributes = "";
			$this->authentication_key->HrefValue = "";
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
		if (!$this->_email->FldIsDetailKey && !is_null($this->_email->FormValue) && $this->_email->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->_email->FldCaption(), $this->_email->ReqErrMsg));
		}
		if (!$this->password->FldIsDetailKey && !is_null($this->password->FormValue) && $this->password->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->password->FldCaption(), $this->password->ReqErrMsg));
		}
		if (!$this->phone->FldIsDetailKey && !is_null($this->phone->FormValue) && $this->phone->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->phone->FldCaption(), $this->phone->ReqErrMsg));
		}
		if (!$this->address->FldIsDetailKey && !is_null($this->address->FormValue) && $this->address->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->address->FldCaption(), $this->address->ReqErrMsg));
		}
		if (!$this->profession->FldIsDetailKey && !is_null($this->profession->FormValue) && $this->profession->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->profession->FldCaption(), $this->profession->ReqErrMsg));
		}
		if (!$this->authentication_key->FldIsDetailKey && !is_null($this->authentication_key->FormValue) && $this->authentication_key->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->authentication_key->FldCaption(), $this->authentication_key->ReqErrMsg));
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

			// email
			$this->_email->SetDbValueDef($rsnew, $this->_email->CurrentValue, "", $this->_email->ReadOnly);

			// password
			$this->password->SetDbValueDef($rsnew, $this->password->CurrentValue, "", $this->password->ReadOnly);

			// phone
			$this->phone->SetDbValueDef($rsnew, $this->phone->CurrentValue, "", $this->phone->ReadOnly);

			// address
			$this->address->SetDbValueDef($rsnew, $this->address->CurrentValue, "", $this->address->ReadOnly);

			// profession
			$this->profession->SetDbValueDef($rsnew, $this->profession->CurrentValue, "", $this->profession->ReadOnly);

			// authentication_key
			$this->authentication_key->SetDbValueDef($rsnew, $this->authentication_key->CurrentValue, "", $this->authentication_key->ReadOnly);

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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("parentlist.php"), "", $this->TableVar, TRUE);
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
if (!isset($parent_edit)) $parent_edit = new cparent_edit();

// Page init
$parent_edit->Page_Init();

// Page main
$parent_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$parent_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = fparentedit = new ew_Form("fparentedit", "edit");

// Validate form
fparentedit.Validate = function() {
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
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $parent->name->FldCaption(), $parent->name->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "__email");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $parent->_email->FldCaption(), $parent->_email->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_password");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $parent->password->FldCaption(), $parent->password->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_phone");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $parent->phone->FldCaption(), $parent->phone->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_address");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $parent->address->FldCaption(), $parent->address->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_profession");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $parent->profession->FldCaption(), $parent->profession->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_authentication_key");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $parent->authentication_key->FldCaption(), $parent->authentication_key->ReqErrMsg)) ?>");

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
fparentedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fparentedit.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $parent_edit->ShowPageHeader(); ?>
<?php
$parent_edit->ShowMessage();
?>
<form name="fparentedit" id="fparentedit" class="<?php echo $parent_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($parent_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $parent_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="parent">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<input type="hidden" name="modal" value="<?php echo intval($parent_edit->IsModal) ?>">
<div class="ewEditDiv"><!-- page* -->
<?php if ($parent->parent_id->Visible) { // parent_id ?>
	<div id="r_parent_id" class="form-group">
		<label id="elh_parent_parent_id" class="<?php echo $parent_edit->LeftColumnClass ?>"><?php echo $parent->parent_id->FldCaption() ?></label>
		<div class="<?php echo $parent_edit->RightColumnClass ?>"><div<?php echo $parent->parent_id->CellAttributes() ?>>
<span id="el_parent_parent_id">
<span<?php echo $parent->parent_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $parent->parent_id->EditValue ?></p></span>
</span>
<input type="hidden" data-table="parent" data-field="x_parent_id" name="x_parent_id" id="x_parent_id" value="<?php echo ew_HtmlEncode($parent->parent_id->CurrentValue) ?>">
<?php echo $parent->parent_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($parent->name->Visible) { // name ?>
	<div id="r_name" class="form-group">
		<label id="elh_parent_name" for="x_name" class="<?php echo $parent_edit->LeftColumnClass ?>"><?php echo $parent->name->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $parent_edit->RightColumnClass ?>"><div<?php echo $parent->name->CellAttributes() ?>>
<span id="el_parent_name">
<textarea data-table="parent" data-field="x_name" name="x_name" id="x_name" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($parent->name->getPlaceHolder()) ?>"<?php echo $parent->name->EditAttributes() ?>><?php echo $parent->name->EditValue ?></textarea>
</span>
<?php echo $parent->name->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($parent->_email->Visible) { // email ?>
	<div id="r__email" class="form-group">
		<label id="elh_parent__email" for="x__email" class="<?php echo $parent_edit->LeftColumnClass ?>"><?php echo $parent->_email->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $parent_edit->RightColumnClass ?>"><div<?php echo $parent->_email->CellAttributes() ?>>
<span id="el_parent__email">
<textarea data-table="parent" data-field="x__email" name="x__email" id="x__email" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($parent->_email->getPlaceHolder()) ?>"<?php echo $parent->_email->EditAttributes() ?>><?php echo $parent->_email->EditValue ?></textarea>
</span>
<?php echo $parent->_email->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($parent->password->Visible) { // password ?>
	<div id="r_password" class="form-group">
		<label id="elh_parent_password" for="x_password" class="<?php echo $parent_edit->LeftColumnClass ?>"><?php echo $parent->password->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $parent_edit->RightColumnClass ?>"><div<?php echo $parent->password->CellAttributes() ?>>
<span id="el_parent_password">
<textarea data-table="parent" data-field="x_password" name="x_password" id="x_password" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($parent->password->getPlaceHolder()) ?>"<?php echo $parent->password->EditAttributes() ?>><?php echo $parent->password->EditValue ?></textarea>
</span>
<?php echo $parent->password->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($parent->phone->Visible) { // phone ?>
	<div id="r_phone" class="form-group">
		<label id="elh_parent_phone" for="x_phone" class="<?php echo $parent_edit->LeftColumnClass ?>"><?php echo $parent->phone->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $parent_edit->RightColumnClass ?>"><div<?php echo $parent->phone->CellAttributes() ?>>
<span id="el_parent_phone">
<textarea data-table="parent" data-field="x_phone" name="x_phone" id="x_phone" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($parent->phone->getPlaceHolder()) ?>"<?php echo $parent->phone->EditAttributes() ?>><?php echo $parent->phone->EditValue ?></textarea>
</span>
<?php echo $parent->phone->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($parent->address->Visible) { // address ?>
	<div id="r_address" class="form-group">
		<label id="elh_parent_address" for="x_address" class="<?php echo $parent_edit->LeftColumnClass ?>"><?php echo $parent->address->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $parent_edit->RightColumnClass ?>"><div<?php echo $parent->address->CellAttributes() ?>>
<span id="el_parent_address">
<textarea data-table="parent" data-field="x_address" name="x_address" id="x_address" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($parent->address->getPlaceHolder()) ?>"<?php echo $parent->address->EditAttributes() ?>><?php echo $parent->address->EditValue ?></textarea>
</span>
<?php echo $parent->address->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($parent->profession->Visible) { // profession ?>
	<div id="r_profession" class="form-group">
		<label id="elh_parent_profession" for="x_profession" class="<?php echo $parent_edit->LeftColumnClass ?>"><?php echo $parent->profession->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $parent_edit->RightColumnClass ?>"><div<?php echo $parent->profession->CellAttributes() ?>>
<span id="el_parent_profession">
<textarea data-table="parent" data-field="x_profession" name="x_profession" id="x_profession" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($parent->profession->getPlaceHolder()) ?>"<?php echo $parent->profession->EditAttributes() ?>><?php echo $parent->profession->EditValue ?></textarea>
</span>
<?php echo $parent->profession->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($parent->authentication_key->Visible) { // authentication_key ?>
	<div id="r_authentication_key" class="form-group">
		<label id="elh_parent_authentication_key" for="x_authentication_key" class="<?php echo $parent_edit->LeftColumnClass ?>"><?php echo $parent->authentication_key->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $parent_edit->RightColumnClass ?>"><div<?php echo $parent->authentication_key->CellAttributes() ?>>
<span id="el_parent_authentication_key">
<textarea data-table="parent" data-field="x_authentication_key" name="x_authentication_key" id="x_authentication_key" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($parent->authentication_key->getPlaceHolder()) ?>"<?php echo $parent->authentication_key->EditAttributes() ?>><?php echo $parent->authentication_key->EditValue ?></textarea>
</span>
<?php echo $parent->authentication_key->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div><!-- /page* -->
<?php if (!$parent_edit->IsModal) { ?>
<div class="form-group"><!-- buttons .form-group -->
	<div class="<?php echo $parent_edit->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $parent_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div><!-- /buttons offset -->
</div><!-- /buttons .form-group -->
<?php } ?>
</form>
<script type="text/javascript">
fparentedit.Init();
</script>
<?php
$parent_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$parent_edit->Page_Terminate();
?>
