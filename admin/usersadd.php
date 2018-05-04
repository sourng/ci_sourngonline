<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "usersinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$users_add = NULL; // Initialize page object first

class cusers_add extends cusers {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = '{1F40EE59-17A4-45D3-96B6-AA2082B3D64A}';

	// Table name
	var $TableName = 'users';

	// Page object name
	var $PageObjName = 'users_add';

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

		// Table object (users)
		if (!isset($GLOBALS["users"]) || get_class($GLOBALS["users"]) == "cusers") {
			$GLOBALS["users"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["users"];
		}

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'users', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("userslist.php"));
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
		$this->oauth_provider->SetVisibility();
		$this->oauth_uid->SetVisibility();
		$this->first_name->SetVisibility();
		$this->last_name->SetVisibility();
		$this->_email->SetVisibility();
		$this->gender->SetVisibility();
		$this->locale->SetVisibility();
		$this->cover->SetVisibility();
		$this->picture->SetVisibility();
		$this->link->SetVisibility();
		$this->created->SetVisibility();
		$this->modified->SetVisibility();

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
		global $EW_EXPORT, $users;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($users);
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
					if ($pageName == "usersview.php")
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
			if (@$_GET["id"] != "") {
				$this->id->setQueryStringValue($_GET["id"]);
				$this->setKey("id", $this->id->CurrentValue); // Set up key
			} else {
				$this->setKey("id", ""); // Clear key
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
					$this->Page_Terminate("userslist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "userslist.php")
						$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to List page with correct master key if necessary
					elseif (ew_GetPageName($sReturnUrl) == "usersview.php")
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
		$this->id->CurrentValue = NULL;
		$this->id->OldValue = $this->id->CurrentValue;
		$this->oauth_provider->CurrentValue = NULL;
		$this->oauth_provider->OldValue = $this->oauth_provider->CurrentValue;
		$this->oauth_uid->CurrentValue = NULL;
		$this->oauth_uid->OldValue = $this->oauth_uid->CurrentValue;
		$this->first_name->CurrentValue = NULL;
		$this->first_name->OldValue = $this->first_name->CurrentValue;
		$this->last_name->CurrentValue = NULL;
		$this->last_name->OldValue = $this->last_name->CurrentValue;
		$this->_email->CurrentValue = NULL;
		$this->_email->OldValue = $this->_email->CurrentValue;
		$this->gender->CurrentValue = NULL;
		$this->gender->OldValue = $this->gender->CurrentValue;
		$this->locale->CurrentValue = NULL;
		$this->locale->OldValue = $this->locale->CurrentValue;
		$this->cover->CurrentValue = NULL;
		$this->cover->OldValue = $this->cover->CurrentValue;
		$this->picture->CurrentValue = NULL;
		$this->picture->OldValue = $this->picture->CurrentValue;
		$this->link->CurrentValue = NULL;
		$this->link->OldValue = $this->link->CurrentValue;
		$this->created->CurrentValue = NULL;
		$this->created->OldValue = $this->created->CurrentValue;
		$this->modified->CurrentValue = NULL;
		$this->modified->OldValue = $this->modified->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->oauth_provider->FldIsDetailKey) {
			$this->oauth_provider->setFormValue($objForm->GetValue("x_oauth_provider"));
		}
		if (!$this->oauth_uid->FldIsDetailKey) {
			$this->oauth_uid->setFormValue($objForm->GetValue("x_oauth_uid"));
		}
		if (!$this->first_name->FldIsDetailKey) {
			$this->first_name->setFormValue($objForm->GetValue("x_first_name"));
		}
		if (!$this->last_name->FldIsDetailKey) {
			$this->last_name->setFormValue($objForm->GetValue("x_last_name"));
		}
		if (!$this->_email->FldIsDetailKey) {
			$this->_email->setFormValue($objForm->GetValue("x__email"));
		}
		if (!$this->gender->FldIsDetailKey) {
			$this->gender->setFormValue($objForm->GetValue("x_gender"));
		}
		if (!$this->locale->FldIsDetailKey) {
			$this->locale->setFormValue($objForm->GetValue("x_locale"));
		}
		if (!$this->cover->FldIsDetailKey) {
			$this->cover->setFormValue($objForm->GetValue("x_cover"));
		}
		if (!$this->picture->FldIsDetailKey) {
			$this->picture->setFormValue($objForm->GetValue("x_picture"));
		}
		if (!$this->link->FldIsDetailKey) {
			$this->link->setFormValue($objForm->GetValue("x_link"));
		}
		if (!$this->created->FldIsDetailKey) {
			$this->created->setFormValue($objForm->GetValue("x_created"));
			$this->created->CurrentValue = ew_UnFormatDateTime($this->created->CurrentValue, 0);
		}
		if (!$this->modified->FldIsDetailKey) {
			$this->modified->setFormValue($objForm->GetValue("x_modified"));
			$this->modified->CurrentValue = ew_UnFormatDateTime($this->modified->CurrentValue, 7);
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->oauth_provider->CurrentValue = $this->oauth_provider->FormValue;
		$this->oauth_uid->CurrentValue = $this->oauth_uid->FormValue;
		$this->first_name->CurrentValue = $this->first_name->FormValue;
		$this->last_name->CurrentValue = $this->last_name->FormValue;
		$this->_email->CurrentValue = $this->_email->FormValue;
		$this->gender->CurrentValue = $this->gender->FormValue;
		$this->locale->CurrentValue = $this->locale->FormValue;
		$this->cover->CurrentValue = $this->cover->FormValue;
		$this->picture->CurrentValue = $this->picture->FormValue;
		$this->link->CurrentValue = $this->link->FormValue;
		$this->created->CurrentValue = $this->created->FormValue;
		$this->created->CurrentValue = ew_UnFormatDateTime($this->created->CurrentValue, 0);
		$this->modified->CurrentValue = $this->modified->FormValue;
		$this->modified->CurrentValue = ew_UnFormatDateTime($this->modified->CurrentValue, 7);
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
		$this->id->setDbValue($row['id']);
		$this->oauth_provider->setDbValue($row['oauth_provider']);
		$this->oauth_uid->setDbValue($row['oauth_uid']);
		$this->first_name->setDbValue($row['first_name']);
		$this->last_name->setDbValue($row['last_name']);
		$this->_email->setDbValue($row['email']);
		$this->gender->setDbValue($row['gender']);
		$this->locale->setDbValue($row['locale']);
		$this->cover->setDbValue($row['cover']);
		$this->picture->setDbValue($row['picture']);
		$this->link->setDbValue($row['link']);
		$this->created->setDbValue($row['created']);
		$this->modified->setDbValue($row['modified']);
	}

	// Return a row with default values
	function NewRow() {
		$this->LoadDefaultValues();
		$row = array();
		$row['id'] = $this->id->CurrentValue;
		$row['oauth_provider'] = $this->oauth_provider->CurrentValue;
		$row['oauth_uid'] = $this->oauth_uid->CurrentValue;
		$row['first_name'] = $this->first_name->CurrentValue;
		$row['last_name'] = $this->last_name->CurrentValue;
		$row['email'] = $this->_email->CurrentValue;
		$row['gender'] = $this->gender->CurrentValue;
		$row['locale'] = $this->locale->CurrentValue;
		$row['cover'] = $this->cover->CurrentValue;
		$row['picture'] = $this->picture->CurrentValue;
		$row['link'] = $this->link->CurrentValue;
		$row['created'] = $this->created->CurrentValue;
		$row['modified'] = $this->modified->CurrentValue;
		return $row;
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF)
			return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id->DbValue = $row['id'];
		$this->oauth_provider->DbValue = $row['oauth_provider'];
		$this->oauth_uid->DbValue = $row['oauth_uid'];
		$this->first_name->DbValue = $row['first_name'];
		$this->last_name->DbValue = $row['last_name'];
		$this->_email->DbValue = $row['email'];
		$this->gender->DbValue = $row['gender'];
		$this->locale->DbValue = $row['locale'];
		$this->cover->DbValue = $row['cover'];
		$this->picture->DbValue = $row['picture'];
		$this->link->DbValue = $row['link'];
		$this->created->DbValue = $row['created'];
		$this->modified->DbValue = $row['modified'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("id")) <> "")
			$this->id->CurrentValue = $this->getKey("id"); // id
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
		// id
		// oauth_provider
		// oauth_uid
		// first_name
		// last_name
		// email
		// gender
		// locale
		// cover
		// picture
		// link
		// created
		// modified

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// id
		$this->id->ViewValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

		// oauth_provider
		$this->oauth_provider->ViewValue = $this->oauth_provider->CurrentValue;
		$this->oauth_provider->ViewCustomAttributes = "";

		// oauth_uid
		$this->oauth_uid->ViewValue = $this->oauth_uid->CurrentValue;
		$this->oauth_uid->ViewCustomAttributes = "";

		// first_name
		$this->first_name->ViewValue = $this->first_name->CurrentValue;
		$this->first_name->ViewCustomAttributes = "";

		// last_name
		$this->last_name->ViewValue = $this->last_name->CurrentValue;
		$this->last_name->ViewCustomAttributes = "";

		// email
		$this->_email->ViewValue = $this->_email->CurrentValue;
		$this->_email->ViewCustomAttributes = "";

		// gender
		$this->gender->ViewValue = $this->gender->CurrentValue;
		$this->gender->ViewCustomAttributes = "";

		// locale
		$this->locale->ViewValue = $this->locale->CurrentValue;
		$this->locale->ViewCustomAttributes = "";

		// cover
		$this->cover->ViewValue = $this->cover->CurrentValue;
		$this->cover->ViewCustomAttributes = "";

		// picture
		$this->picture->ViewValue = $this->picture->CurrentValue;
		$this->picture->ViewCustomAttributes = "";

		// link
		$this->link->ViewValue = $this->link->CurrentValue;
		$this->link->ViewCustomAttributes = "";

		// created
		$this->created->ViewValue = $this->created->CurrentValue;
		$this->created->ViewValue = ew_FormatDateTime($this->created->ViewValue, 0);
		$this->created->ViewCustomAttributes = "";

		// modified
		$this->modified->ViewValue = $this->modified->CurrentValue;
		$this->modified->ViewValue = ew_FormatDateTime($this->modified->ViewValue, 7);
		$this->modified->ViewCustomAttributes = "";

			// oauth_provider
			$this->oauth_provider->LinkCustomAttributes = "";
			$this->oauth_provider->HrefValue = "";
			$this->oauth_provider->TooltipValue = "";

			// oauth_uid
			$this->oauth_uid->LinkCustomAttributes = "";
			$this->oauth_uid->HrefValue = "";
			$this->oauth_uid->TooltipValue = "";

			// first_name
			$this->first_name->LinkCustomAttributes = "";
			$this->first_name->HrefValue = "";
			$this->first_name->TooltipValue = "";

			// last_name
			$this->last_name->LinkCustomAttributes = "";
			$this->last_name->HrefValue = "";
			$this->last_name->TooltipValue = "";

			// email
			$this->_email->LinkCustomAttributes = "";
			$this->_email->HrefValue = "";
			$this->_email->TooltipValue = "";

			// gender
			$this->gender->LinkCustomAttributes = "";
			$this->gender->HrefValue = "";
			$this->gender->TooltipValue = "";

			// locale
			$this->locale->LinkCustomAttributes = "";
			$this->locale->HrefValue = "";
			$this->locale->TooltipValue = "";

			// cover
			$this->cover->LinkCustomAttributes = "";
			$this->cover->HrefValue = "";
			$this->cover->TooltipValue = "";

			// picture
			$this->picture->LinkCustomAttributes = "";
			$this->picture->HrefValue = "";
			$this->picture->TooltipValue = "";

			// link
			$this->link->LinkCustomAttributes = "";
			$this->link->HrefValue = "";
			$this->link->TooltipValue = "";

			// created
			$this->created->LinkCustomAttributes = "";
			$this->created->HrefValue = "";
			$this->created->TooltipValue = "";

			// modified
			$this->modified->LinkCustomAttributes = "";
			$this->modified->HrefValue = "";
			$this->modified->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// oauth_provider
			$this->oauth_provider->EditAttrs["class"] = "form-control";
			$this->oauth_provider->EditCustomAttributes = "";
			$this->oauth_provider->EditValue = ew_HtmlEncode($this->oauth_provider->CurrentValue);
			$this->oauth_provider->PlaceHolder = ew_RemoveHtml($this->oauth_provider->FldCaption());

			// oauth_uid
			$this->oauth_uid->EditAttrs["class"] = "form-control";
			$this->oauth_uid->EditCustomAttributes = "";
			$this->oauth_uid->EditValue = ew_HtmlEncode($this->oauth_uid->CurrentValue);
			$this->oauth_uid->PlaceHolder = ew_RemoveHtml($this->oauth_uid->FldCaption());

			// first_name
			$this->first_name->EditAttrs["class"] = "form-control";
			$this->first_name->EditCustomAttributes = "";
			$this->first_name->EditValue = ew_HtmlEncode($this->first_name->CurrentValue);
			$this->first_name->PlaceHolder = ew_RemoveHtml($this->first_name->FldCaption());

			// last_name
			$this->last_name->EditAttrs["class"] = "form-control";
			$this->last_name->EditCustomAttributes = "";
			$this->last_name->EditValue = ew_HtmlEncode($this->last_name->CurrentValue);
			$this->last_name->PlaceHolder = ew_RemoveHtml($this->last_name->FldCaption());

			// email
			$this->_email->EditAttrs["class"] = "form-control";
			$this->_email->EditCustomAttributes = "";
			$this->_email->EditValue = ew_HtmlEncode($this->_email->CurrentValue);
			$this->_email->PlaceHolder = ew_RemoveHtml($this->_email->FldCaption());

			// gender
			$this->gender->EditAttrs["class"] = "form-control";
			$this->gender->EditCustomAttributes = "";
			$this->gender->EditValue = ew_HtmlEncode($this->gender->CurrentValue);
			$this->gender->PlaceHolder = ew_RemoveHtml($this->gender->FldCaption());

			// locale
			$this->locale->EditAttrs["class"] = "form-control";
			$this->locale->EditCustomAttributes = "";
			$this->locale->EditValue = ew_HtmlEncode($this->locale->CurrentValue);
			$this->locale->PlaceHolder = ew_RemoveHtml($this->locale->FldCaption());

			// cover
			$this->cover->EditAttrs["class"] = "form-control";
			$this->cover->EditCustomAttributes = "";
			$this->cover->EditValue = ew_HtmlEncode($this->cover->CurrentValue);
			$this->cover->PlaceHolder = ew_RemoveHtml($this->cover->FldCaption());

			// picture
			$this->picture->EditAttrs["class"] = "form-control";
			$this->picture->EditCustomAttributes = "";
			$this->picture->EditValue = ew_HtmlEncode($this->picture->CurrentValue);
			$this->picture->PlaceHolder = ew_RemoveHtml($this->picture->FldCaption());

			// link
			$this->link->EditAttrs["class"] = "form-control";
			$this->link->EditCustomAttributes = "";
			$this->link->EditValue = ew_HtmlEncode($this->link->CurrentValue);
			$this->link->PlaceHolder = ew_RemoveHtml($this->link->FldCaption());

			// created
			$this->created->EditAttrs["class"] = "form-control";
			$this->created->EditCustomAttributes = "";
			$this->created->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->created->CurrentValue, 8));
			$this->created->PlaceHolder = ew_RemoveHtml($this->created->FldCaption());

			// modified
			$this->modified->EditAttrs["class"] = "form-control";
			$this->modified->EditCustomAttributes = "";
			$this->modified->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->modified->CurrentValue, 7));
			$this->modified->PlaceHolder = ew_RemoveHtml($this->modified->FldCaption());

			// Add refer script
			// oauth_provider

			$this->oauth_provider->LinkCustomAttributes = "";
			$this->oauth_provider->HrefValue = "";

			// oauth_uid
			$this->oauth_uid->LinkCustomAttributes = "";
			$this->oauth_uid->HrefValue = "";

			// first_name
			$this->first_name->LinkCustomAttributes = "";
			$this->first_name->HrefValue = "";

			// last_name
			$this->last_name->LinkCustomAttributes = "";
			$this->last_name->HrefValue = "";

			// email
			$this->_email->LinkCustomAttributes = "";
			$this->_email->HrefValue = "";

			// gender
			$this->gender->LinkCustomAttributes = "";
			$this->gender->HrefValue = "";

			// locale
			$this->locale->LinkCustomAttributes = "";
			$this->locale->HrefValue = "";

			// cover
			$this->cover->LinkCustomAttributes = "";
			$this->cover->HrefValue = "";

			// picture
			$this->picture->LinkCustomAttributes = "";
			$this->picture->HrefValue = "";

			// link
			$this->link->LinkCustomAttributes = "";
			$this->link->HrefValue = "";

			// created
			$this->created->LinkCustomAttributes = "";
			$this->created->HrefValue = "";

			// modified
			$this->modified->LinkCustomAttributes = "";
			$this->modified->HrefValue = "";
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
		if (!$this->oauth_provider->FldIsDetailKey && !is_null($this->oauth_provider->FormValue) && $this->oauth_provider->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->oauth_provider->FldCaption(), $this->oauth_provider->ReqErrMsg));
		}
		if (!$this->oauth_uid->FldIsDetailKey && !is_null($this->oauth_uid->FormValue) && $this->oauth_uid->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->oauth_uid->FldCaption(), $this->oauth_uid->ReqErrMsg));
		}
		if (!$this->first_name->FldIsDetailKey && !is_null($this->first_name->FormValue) && $this->first_name->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->first_name->FldCaption(), $this->first_name->ReqErrMsg));
		}
		if (!$this->last_name->FldIsDetailKey && !is_null($this->last_name->FormValue) && $this->last_name->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->last_name->FldCaption(), $this->last_name->ReqErrMsg));
		}
		if (!$this->_email->FldIsDetailKey && !is_null($this->_email->FormValue) && $this->_email->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->_email->FldCaption(), $this->_email->ReqErrMsg));
		}
		if (!$this->gender->FldIsDetailKey && !is_null($this->gender->FormValue) && $this->gender->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->gender->FldCaption(), $this->gender->ReqErrMsg));
		}
		if (!$this->locale->FldIsDetailKey && !is_null($this->locale->FormValue) && $this->locale->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->locale->FldCaption(), $this->locale->ReqErrMsg));
		}
		if (!$this->cover->FldIsDetailKey && !is_null($this->cover->FormValue) && $this->cover->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->cover->FldCaption(), $this->cover->ReqErrMsg));
		}
		if (!$this->picture->FldIsDetailKey && !is_null($this->picture->FormValue) && $this->picture->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->picture->FldCaption(), $this->picture->ReqErrMsg));
		}
		if (!$this->link->FldIsDetailKey && !is_null($this->link->FormValue) && $this->link->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->link->FldCaption(), $this->link->ReqErrMsg));
		}
		if (!$this->created->FldIsDetailKey && !is_null($this->created->FormValue) && $this->created->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->created->FldCaption(), $this->created->ReqErrMsg));
		}
		if (!ew_CheckDateDef($this->created->FormValue)) {
			ew_AddMessage($gsFormError, $this->created->FldErrMsg());
		}
		if (!$this->modified->FldIsDetailKey && !is_null($this->modified->FormValue) && $this->modified->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->modified->FldCaption(), $this->modified->ReqErrMsg));
		}
		if (!ew_CheckEuroDate($this->modified->FormValue)) {
			ew_AddMessage($gsFormError, $this->modified->FldErrMsg());
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

		// oauth_provider
		$this->oauth_provider->SetDbValueDef($rsnew, $this->oauth_provider->CurrentValue, "", FALSE);

		// oauth_uid
		$this->oauth_uid->SetDbValueDef($rsnew, $this->oauth_uid->CurrentValue, "", FALSE);

		// first_name
		$this->first_name->SetDbValueDef($rsnew, $this->first_name->CurrentValue, "", FALSE);

		// last_name
		$this->last_name->SetDbValueDef($rsnew, $this->last_name->CurrentValue, "", FALSE);

		// email
		$this->_email->SetDbValueDef($rsnew, $this->_email->CurrentValue, "", FALSE);

		// gender
		$this->gender->SetDbValueDef($rsnew, $this->gender->CurrentValue, "", FALSE);

		// locale
		$this->locale->SetDbValueDef($rsnew, $this->locale->CurrentValue, "", FALSE);

		// cover
		$this->cover->SetDbValueDef($rsnew, $this->cover->CurrentValue, "", FALSE);

		// picture
		$this->picture->SetDbValueDef($rsnew, $this->picture->CurrentValue, "", FALSE);

		// link
		$this->link->SetDbValueDef($rsnew, $this->link->CurrentValue, "", FALSE);

		// created
		$this->created->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->created->CurrentValue, 0), ew_CurrentDate(), FALSE);

		// modified
		$this->modified->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->modified->CurrentValue, 7), ew_CurrentDate(), FALSE);

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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("userslist.php"), "", $this->TableVar, TRUE);
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
if (!isset($users_add)) $users_add = new cusers_add();

// Page init
$users_add->Page_Init();

// Page main
$users_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$users_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "add";
var CurrentForm = fusersadd = new ew_Form("fusersadd", "add");

// Validate form
fusersadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_oauth_provider");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $users->oauth_provider->FldCaption(), $users->oauth_provider->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_oauth_uid");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $users->oauth_uid->FldCaption(), $users->oauth_uid->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_first_name");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $users->first_name->FldCaption(), $users->first_name->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_last_name");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $users->last_name->FldCaption(), $users->last_name->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "__email");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $users->_email->FldCaption(), $users->_email->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_gender");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $users->gender->FldCaption(), $users->gender->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_locale");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $users->locale->FldCaption(), $users->locale->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_cover");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $users->cover->FldCaption(), $users->cover->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_picture");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $users->picture->FldCaption(), $users->picture->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_link");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $users->link->FldCaption(), $users->link->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_created");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $users->created->FldCaption(), $users->created->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_created");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($users->created->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_modified");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $users->modified->FldCaption(), $users->modified->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_modified");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($users->modified->FldErrMsg()) ?>");

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
fusersadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fusersadd.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $users_add->ShowPageHeader(); ?>
<?php
$users_add->ShowMessage();
?>
<form name="fusersadd" id="fusersadd" class="<?php echo $users_add->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($users_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $users_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="users">
<input type="hidden" name="a_add" id="a_add" value="A">
<input type="hidden" name="modal" value="<?php echo intval($users_add->IsModal) ?>">
<div class="ewAddDiv"><!-- page* -->
<?php if ($users->oauth_provider->Visible) { // oauth_provider ?>
	<div id="r_oauth_provider" class="form-group">
		<label id="elh_users_oauth_provider" for="x_oauth_provider" class="<?php echo $users_add->LeftColumnClass ?>"><?php echo $users->oauth_provider->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $users_add->RightColumnClass ?>"><div<?php echo $users->oauth_provider->CellAttributes() ?>>
<span id="el_users_oauth_provider">
<input type="text" data-table="users" data-field="x_oauth_provider" name="x_oauth_provider" id="x_oauth_provider" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($users->oauth_provider->getPlaceHolder()) ?>" value="<?php echo $users->oauth_provider->EditValue ?>"<?php echo $users->oauth_provider->EditAttributes() ?>>
</span>
<?php echo $users->oauth_provider->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($users->oauth_uid->Visible) { // oauth_uid ?>
	<div id="r_oauth_uid" class="form-group">
		<label id="elh_users_oauth_uid" for="x_oauth_uid" class="<?php echo $users_add->LeftColumnClass ?>"><?php echo $users->oauth_uid->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $users_add->RightColumnClass ?>"><div<?php echo $users->oauth_uid->CellAttributes() ?>>
<span id="el_users_oauth_uid">
<input type="text" data-table="users" data-field="x_oauth_uid" name="x_oauth_uid" id="x_oauth_uid" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($users->oauth_uid->getPlaceHolder()) ?>" value="<?php echo $users->oauth_uid->EditValue ?>"<?php echo $users->oauth_uid->EditAttributes() ?>>
</span>
<?php echo $users->oauth_uid->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($users->first_name->Visible) { // first_name ?>
	<div id="r_first_name" class="form-group">
		<label id="elh_users_first_name" for="x_first_name" class="<?php echo $users_add->LeftColumnClass ?>"><?php echo $users->first_name->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $users_add->RightColumnClass ?>"><div<?php echo $users->first_name->CellAttributes() ?>>
<span id="el_users_first_name">
<input type="text" data-table="users" data-field="x_first_name" name="x_first_name" id="x_first_name" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($users->first_name->getPlaceHolder()) ?>" value="<?php echo $users->first_name->EditValue ?>"<?php echo $users->first_name->EditAttributes() ?>>
</span>
<?php echo $users->first_name->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($users->last_name->Visible) { // last_name ?>
	<div id="r_last_name" class="form-group">
		<label id="elh_users_last_name" for="x_last_name" class="<?php echo $users_add->LeftColumnClass ?>"><?php echo $users->last_name->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $users_add->RightColumnClass ?>"><div<?php echo $users->last_name->CellAttributes() ?>>
<span id="el_users_last_name">
<input type="text" data-table="users" data-field="x_last_name" name="x_last_name" id="x_last_name" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($users->last_name->getPlaceHolder()) ?>" value="<?php echo $users->last_name->EditValue ?>"<?php echo $users->last_name->EditAttributes() ?>>
</span>
<?php echo $users->last_name->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($users->_email->Visible) { // email ?>
	<div id="r__email" class="form-group">
		<label id="elh_users__email" for="x__email" class="<?php echo $users_add->LeftColumnClass ?>"><?php echo $users->_email->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $users_add->RightColumnClass ?>"><div<?php echo $users->_email->CellAttributes() ?>>
<span id="el_users__email">
<input type="text" data-table="users" data-field="x__email" name="x__email" id="x__email" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($users->_email->getPlaceHolder()) ?>" value="<?php echo $users->_email->EditValue ?>"<?php echo $users->_email->EditAttributes() ?>>
</span>
<?php echo $users->_email->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($users->gender->Visible) { // gender ?>
	<div id="r_gender" class="form-group">
		<label id="elh_users_gender" for="x_gender" class="<?php echo $users_add->LeftColumnClass ?>"><?php echo $users->gender->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $users_add->RightColumnClass ?>"><div<?php echo $users->gender->CellAttributes() ?>>
<span id="el_users_gender">
<input type="text" data-table="users" data-field="x_gender" name="x_gender" id="x_gender" size="30" maxlength="10" placeholder="<?php echo ew_HtmlEncode($users->gender->getPlaceHolder()) ?>" value="<?php echo $users->gender->EditValue ?>"<?php echo $users->gender->EditAttributes() ?>>
</span>
<?php echo $users->gender->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($users->locale->Visible) { // locale ?>
	<div id="r_locale" class="form-group">
		<label id="elh_users_locale" for="x_locale" class="<?php echo $users_add->LeftColumnClass ?>"><?php echo $users->locale->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $users_add->RightColumnClass ?>"><div<?php echo $users->locale->CellAttributes() ?>>
<span id="el_users_locale">
<input type="text" data-table="users" data-field="x_locale" name="x_locale" id="x_locale" size="30" maxlength="10" placeholder="<?php echo ew_HtmlEncode($users->locale->getPlaceHolder()) ?>" value="<?php echo $users->locale->EditValue ?>"<?php echo $users->locale->EditAttributes() ?>>
</span>
<?php echo $users->locale->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($users->cover->Visible) { // cover ?>
	<div id="r_cover" class="form-group">
		<label id="elh_users_cover" for="x_cover" class="<?php echo $users_add->LeftColumnClass ?>"><?php echo $users->cover->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $users_add->RightColumnClass ?>"><div<?php echo $users->cover->CellAttributes() ?>>
<span id="el_users_cover">
<input type="text" data-table="users" data-field="x_cover" name="x_cover" id="x_cover" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($users->cover->getPlaceHolder()) ?>" value="<?php echo $users->cover->EditValue ?>"<?php echo $users->cover->EditAttributes() ?>>
</span>
<?php echo $users->cover->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($users->picture->Visible) { // picture ?>
	<div id="r_picture" class="form-group">
		<label id="elh_users_picture" for="x_picture" class="<?php echo $users_add->LeftColumnClass ?>"><?php echo $users->picture->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $users_add->RightColumnClass ?>"><div<?php echo $users->picture->CellAttributes() ?>>
<span id="el_users_picture">
<input type="text" data-table="users" data-field="x_picture" name="x_picture" id="x_picture" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($users->picture->getPlaceHolder()) ?>" value="<?php echo $users->picture->EditValue ?>"<?php echo $users->picture->EditAttributes() ?>>
</span>
<?php echo $users->picture->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($users->link->Visible) { // link ?>
	<div id="r_link" class="form-group">
		<label id="elh_users_link" for="x_link" class="<?php echo $users_add->LeftColumnClass ?>"><?php echo $users->link->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $users_add->RightColumnClass ?>"><div<?php echo $users->link->CellAttributes() ?>>
<span id="el_users_link">
<input type="text" data-table="users" data-field="x_link" name="x_link" id="x_link" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($users->link->getPlaceHolder()) ?>" value="<?php echo $users->link->EditValue ?>"<?php echo $users->link->EditAttributes() ?>>
</span>
<?php echo $users->link->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($users->created->Visible) { // created ?>
	<div id="r_created" class="form-group">
		<label id="elh_users_created" for="x_created" class="<?php echo $users_add->LeftColumnClass ?>"><?php echo $users->created->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $users_add->RightColumnClass ?>"><div<?php echo $users->created->CellAttributes() ?>>
<span id="el_users_created">
<input type="text" data-table="users" data-field="x_created" name="x_created" id="x_created" placeholder="<?php echo ew_HtmlEncode($users->created->getPlaceHolder()) ?>" value="<?php echo $users->created->EditValue ?>"<?php echo $users->created->EditAttributes() ?>>
</span>
<?php echo $users->created->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($users->modified->Visible) { // modified ?>
	<div id="r_modified" class="form-group">
		<label id="elh_users_modified" for="x_modified" class="<?php echo $users_add->LeftColumnClass ?>"><?php echo $users->modified->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $users_add->RightColumnClass ?>"><div<?php echo $users->modified->CellAttributes() ?>>
<span id="el_users_modified">
<input type="text" data-table="users" data-field="x_modified" data-format="7" name="x_modified" id="x_modified" placeholder="<?php echo ew_HtmlEncode($users->modified->getPlaceHolder()) ?>" value="<?php echo $users->modified->EditValue ?>"<?php echo $users->modified->EditAttributes() ?>>
</span>
<?php echo $users->modified->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div><!-- /page* -->
<?php if (!$users_add->IsModal) { ?>
<div class="form-group"><!-- buttons .form-group -->
	<div class="<?php echo $users_add->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $users_add->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div><!-- /buttons offset -->
</div><!-- /buttons .form-group -->
<?php } ?>
</form>
<script type="text/javascript">
fusersadd.Init();
</script>
<?php
$users_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$users_add->Page_Terminate();
?>
