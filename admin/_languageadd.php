<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "_languageinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$p_language_add = NULL; // Initialize page object first

class cp_language_add extends c_language {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = '{1F40EE59-17A4-45D3-96B6-AA2082B3D64A}';

	// Table name
	var $TableName = 'language';

	// Page object name
	var $PageObjName = 'p_language_add';

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

		// Table object (_language)
		if (!isset($GLOBALS["_language"]) || get_class($GLOBALS["_language"]) == "c_language") {
			$GLOBALS["_language"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["_language"];
		}

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'language', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("_languagelist.php"));
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
		$this->phrase->SetVisibility();
		$this->english->SetVisibility();
		$this->bengali->SetVisibility();
		$this->spanish->SetVisibility();
		$this->arabic->SetVisibility();
		$this->dutch->SetVisibility();
		$this->russian->SetVisibility();
		$this->chinese->SetVisibility();
		$this->turkish->SetVisibility();
		$this->portuguese->SetVisibility();
		$this->hungarian->SetVisibility();
		$this->french->SetVisibility();
		$this->greek->SetVisibility();
		$this->german->SetVisibility();
		$this->italian->SetVisibility();
		$this->thai->SetVisibility();
		$this->urdu->SetVisibility();
		$this->hindi->SetVisibility();
		$this->latin->SetVisibility();
		$this->indonesian->SetVisibility();
		$this->japanese->SetVisibility();
		$this->korean->SetVisibility();
		$this->_178117D2179817C2179A->SetVisibility();
		$this->Khmer->SetVisibility();

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
		global $EW_EXPORT, $_language;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($_language);
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
					if ($pageName == "_languageview.php")
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
			if (@$_GET["phrase_id"] != "") {
				$this->phrase_id->setQueryStringValue($_GET["phrase_id"]);
				$this->setKey("phrase_id", $this->phrase_id->CurrentValue); // Set up key
			} else {
				$this->setKey("phrase_id", ""); // Clear key
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
					$this->Page_Terminate("_languagelist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "_languagelist.php")
						$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to List page with correct master key if necessary
					elseif (ew_GetPageName($sReturnUrl) == "_languageview.php")
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
		$this->phrase_id->CurrentValue = NULL;
		$this->phrase_id->OldValue = $this->phrase_id->CurrentValue;
		$this->phrase->CurrentValue = NULL;
		$this->phrase->OldValue = $this->phrase->CurrentValue;
		$this->english->CurrentValue = NULL;
		$this->english->OldValue = $this->english->CurrentValue;
		$this->bengali->CurrentValue = NULL;
		$this->bengali->OldValue = $this->bengali->CurrentValue;
		$this->spanish->CurrentValue = NULL;
		$this->spanish->OldValue = $this->spanish->CurrentValue;
		$this->arabic->CurrentValue = NULL;
		$this->arabic->OldValue = $this->arabic->CurrentValue;
		$this->dutch->CurrentValue = NULL;
		$this->dutch->OldValue = $this->dutch->CurrentValue;
		$this->russian->CurrentValue = NULL;
		$this->russian->OldValue = $this->russian->CurrentValue;
		$this->chinese->CurrentValue = NULL;
		$this->chinese->OldValue = $this->chinese->CurrentValue;
		$this->turkish->CurrentValue = NULL;
		$this->turkish->OldValue = $this->turkish->CurrentValue;
		$this->portuguese->CurrentValue = NULL;
		$this->portuguese->OldValue = $this->portuguese->CurrentValue;
		$this->hungarian->CurrentValue = NULL;
		$this->hungarian->OldValue = $this->hungarian->CurrentValue;
		$this->french->CurrentValue = NULL;
		$this->french->OldValue = $this->french->CurrentValue;
		$this->greek->CurrentValue = NULL;
		$this->greek->OldValue = $this->greek->CurrentValue;
		$this->german->CurrentValue = NULL;
		$this->german->OldValue = $this->german->CurrentValue;
		$this->italian->CurrentValue = NULL;
		$this->italian->OldValue = $this->italian->CurrentValue;
		$this->thai->CurrentValue = NULL;
		$this->thai->OldValue = $this->thai->CurrentValue;
		$this->urdu->CurrentValue = NULL;
		$this->urdu->OldValue = $this->urdu->CurrentValue;
		$this->hindi->CurrentValue = NULL;
		$this->hindi->OldValue = $this->hindi->CurrentValue;
		$this->latin->CurrentValue = NULL;
		$this->latin->OldValue = $this->latin->CurrentValue;
		$this->indonesian->CurrentValue = NULL;
		$this->indonesian->OldValue = $this->indonesian->CurrentValue;
		$this->japanese->CurrentValue = NULL;
		$this->japanese->OldValue = $this->japanese->CurrentValue;
		$this->korean->CurrentValue = NULL;
		$this->korean->OldValue = $this->korean->CurrentValue;
		$this->_178117D2179817C2179A->CurrentValue = NULL;
		$this->_178117D2179817C2179A->OldValue = $this->_178117D2179817C2179A->CurrentValue;
		$this->Khmer->CurrentValue = NULL;
		$this->Khmer->OldValue = $this->Khmer->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->phrase->FldIsDetailKey) {
			$this->phrase->setFormValue($objForm->GetValue("x_phrase"));
		}
		if (!$this->english->FldIsDetailKey) {
			$this->english->setFormValue($objForm->GetValue("x_english"));
		}
		if (!$this->bengali->FldIsDetailKey) {
			$this->bengali->setFormValue($objForm->GetValue("x_bengali"));
		}
		if (!$this->spanish->FldIsDetailKey) {
			$this->spanish->setFormValue($objForm->GetValue("x_spanish"));
		}
		if (!$this->arabic->FldIsDetailKey) {
			$this->arabic->setFormValue($objForm->GetValue("x_arabic"));
		}
		if (!$this->dutch->FldIsDetailKey) {
			$this->dutch->setFormValue($objForm->GetValue("x_dutch"));
		}
		if (!$this->russian->FldIsDetailKey) {
			$this->russian->setFormValue($objForm->GetValue("x_russian"));
		}
		if (!$this->chinese->FldIsDetailKey) {
			$this->chinese->setFormValue($objForm->GetValue("x_chinese"));
		}
		if (!$this->turkish->FldIsDetailKey) {
			$this->turkish->setFormValue($objForm->GetValue("x_turkish"));
		}
		if (!$this->portuguese->FldIsDetailKey) {
			$this->portuguese->setFormValue($objForm->GetValue("x_portuguese"));
		}
		if (!$this->hungarian->FldIsDetailKey) {
			$this->hungarian->setFormValue($objForm->GetValue("x_hungarian"));
		}
		if (!$this->french->FldIsDetailKey) {
			$this->french->setFormValue($objForm->GetValue("x_french"));
		}
		if (!$this->greek->FldIsDetailKey) {
			$this->greek->setFormValue($objForm->GetValue("x_greek"));
		}
		if (!$this->german->FldIsDetailKey) {
			$this->german->setFormValue($objForm->GetValue("x_german"));
		}
		if (!$this->italian->FldIsDetailKey) {
			$this->italian->setFormValue($objForm->GetValue("x_italian"));
		}
		if (!$this->thai->FldIsDetailKey) {
			$this->thai->setFormValue($objForm->GetValue("x_thai"));
		}
		if (!$this->urdu->FldIsDetailKey) {
			$this->urdu->setFormValue($objForm->GetValue("x_urdu"));
		}
		if (!$this->hindi->FldIsDetailKey) {
			$this->hindi->setFormValue($objForm->GetValue("x_hindi"));
		}
		if (!$this->latin->FldIsDetailKey) {
			$this->latin->setFormValue($objForm->GetValue("x_latin"));
		}
		if (!$this->indonesian->FldIsDetailKey) {
			$this->indonesian->setFormValue($objForm->GetValue("x_indonesian"));
		}
		if (!$this->japanese->FldIsDetailKey) {
			$this->japanese->setFormValue($objForm->GetValue("x_japanese"));
		}
		if (!$this->korean->FldIsDetailKey) {
			$this->korean->setFormValue($objForm->GetValue("x_korean"));
		}
		if (!$this->_178117D2179817C2179A->FldIsDetailKey) {
			$this->_178117D2179817C2179A->setFormValue($objForm->GetValue("x__178117D2179817C2179A"));
		}
		if (!$this->Khmer->FldIsDetailKey) {
			$this->Khmer->setFormValue($objForm->GetValue("x_Khmer"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->phrase->CurrentValue = $this->phrase->FormValue;
		$this->english->CurrentValue = $this->english->FormValue;
		$this->bengali->CurrentValue = $this->bengali->FormValue;
		$this->spanish->CurrentValue = $this->spanish->FormValue;
		$this->arabic->CurrentValue = $this->arabic->FormValue;
		$this->dutch->CurrentValue = $this->dutch->FormValue;
		$this->russian->CurrentValue = $this->russian->FormValue;
		$this->chinese->CurrentValue = $this->chinese->FormValue;
		$this->turkish->CurrentValue = $this->turkish->FormValue;
		$this->portuguese->CurrentValue = $this->portuguese->FormValue;
		$this->hungarian->CurrentValue = $this->hungarian->FormValue;
		$this->french->CurrentValue = $this->french->FormValue;
		$this->greek->CurrentValue = $this->greek->FormValue;
		$this->german->CurrentValue = $this->german->FormValue;
		$this->italian->CurrentValue = $this->italian->FormValue;
		$this->thai->CurrentValue = $this->thai->FormValue;
		$this->urdu->CurrentValue = $this->urdu->FormValue;
		$this->hindi->CurrentValue = $this->hindi->FormValue;
		$this->latin->CurrentValue = $this->latin->FormValue;
		$this->indonesian->CurrentValue = $this->indonesian->FormValue;
		$this->japanese->CurrentValue = $this->japanese->FormValue;
		$this->korean->CurrentValue = $this->korean->FormValue;
		$this->_178117D2179817C2179A->CurrentValue = $this->_178117D2179817C2179A->FormValue;
		$this->Khmer->CurrentValue = $this->Khmer->FormValue;
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
		$this->phrase_id->setDbValue($row['phrase_id']);
		$this->phrase->setDbValue($row['phrase']);
		$this->english->setDbValue($row['english']);
		$this->bengali->setDbValue($row['bengali']);
		$this->spanish->setDbValue($row['spanish']);
		$this->arabic->setDbValue($row['arabic']);
		$this->dutch->setDbValue($row['dutch']);
		$this->russian->setDbValue($row['russian']);
		$this->chinese->setDbValue($row['chinese']);
		$this->turkish->setDbValue($row['turkish']);
		$this->portuguese->setDbValue($row['portuguese']);
		$this->hungarian->setDbValue($row['hungarian']);
		$this->french->setDbValue($row['french']);
		$this->greek->setDbValue($row['greek']);
		$this->german->setDbValue($row['german']);
		$this->italian->setDbValue($row['italian']);
		$this->thai->setDbValue($row['thai']);
		$this->urdu->setDbValue($row['urdu']);
		$this->hindi->setDbValue($row['hindi']);
		$this->latin->setDbValue($row['latin']);
		$this->indonesian->setDbValue($row['indonesian']);
		$this->japanese->setDbValue($row['japanese']);
		$this->korean->setDbValue($row['korean']);
		$this->_178117D2179817C2179A->setDbValue($row['ខ្មែរ']);
		$this->Khmer->setDbValue($row['Khmer']);
	}

	// Return a row with default values
	function NewRow() {
		$this->LoadDefaultValues();
		$row = array();
		$row['phrase_id'] = $this->phrase_id->CurrentValue;
		$row['phrase'] = $this->phrase->CurrentValue;
		$row['english'] = $this->english->CurrentValue;
		$row['bengali'] = $this->bengali->CurrentValue;
		$row['spanish'] = $this->spanish->CurrentValue;
		$row['arabic'] = $this->arabic->CurrentValue;
		$row['dutch'] = $this->dutch->CurrentValue;
		$row['russian'] = $this->russian->CurrentValue;
		$row['chinese'] = $this->chinese->CurrentValue;
		$row['turkish'] = $this->turkish->CurrentValue;
		$row['portuguese'] = $this->portuguese->CurrentValue;
		$row['hungarian'] = $this->hungarian->CurrentValue;
		$row['french'] = $this->french->CurrentValue;
		$row['greek'] = $this->greek->CurrentValue;
		$row['german'] = $this->german->CurrentValue;
		$row['italian'] = $this->italian->CurrentValue;
		$row['thai'] = $this->thai->CurrentValue;
		$row['urdu'] = $this->urdu->CurrentValue;
		$row['hindi'] = $this->hindi->CurrentValue;
		$row['latin'] = $this->latin->CurrentValue;
		$row['indonesian'] = $this->indonesian->CurrentValue;
		$row['japanese'] = $this->japanese->CurrentValue;
		$row['korean'] = $this->korean->CurrentValue;
		$row['ខ្មែរ'] = $this->_178117D2179817C2179A->CurrentValue;
		$row['Khmer'] = $this->Khmer->CurrentValue;
		return $row;
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF)
			return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->phrase_id->DbValue = $row['phrase_id'];
		$this->phrase->DbValue = $row['phrase'];
		$this->english->DbValue = $row['english'];
		$this->bengali->DbValue = $row['bengali'];
		$this->spanish->DbValue = $row['spanish'];
		$this->arabic->DbValue = $row['arabic'];
		$this->dutch->DbValue = $row['dutch'];
		$this->russian->DbValue = $row['russian'];
		$this->chinese->DbValue = $row['chinese'];
		$this->turkish->DbValue = $row['turkish'];
		$this->portuguese->DbValue = $row['portuguese'];
		$this->hungarian->DbValue = $row['hungarian'];
		$this->french->DbValue = $row['french'];
		$this->greek->DbValue = $row['greek'];
		$this->german->DbValue = $row['german'];
		$this->italian->DbValue = $row['italian'];
		$this->thai->DbValue = $row['thai'];
		$this->urdu->DbValue = $row['urdu'];
		$this->hindi->DbValue = $row['hindi'];
		$this->latin->DbValue = $row['latin'];
		$this->indonesian->DbValue = $row['indonesian'];
		$this->japanese->DbValue = $row['japanese'];
		$this->korean->DbValue = $row['korean'];
		$this->_178117D2179817C2179A->DbValue = $row['ខ្មែរ'];
		$this->Khmer->DbValue = $row['Khmer'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("phrase_id")) <> "")
			$this->phrase_id->CurrentValue = $this->getKey("phrase_id"); // phrase_id
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
		// phrase_id
		// phrase
		// english
		// bengali
		// spanish
		// arabic
		// dutch
		// russian
		// chinese
		// turkish
		// portuguese
		// hungarian
		// french
		// greek
		// german
		// italian
		// thai
		// urdu
		// hindi
		// latin
		// indonesian
		// japanese
		// korean
		// ខ្មែរ
		// Khmer

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// phrase_id
		$this->phrase_id->ViewValue = $this->phrase_id->CurrentValue;
		$this->phrase_id->ViewCustomAttributes = "";

		// phrase
		$this->phrase->ViewValue = $this->phrase->CurrentValue;
		$this->phrase->ViewCustomAttributes = "";

		// english
		$this->english->ViewValue = $this->english->CurrentValue;
		$this->english->ViewCustomAttributes = "";

		// bengali
		$this->bengali->ViewValue = $this->bengali->CurrentValue;
		$this->bengali->ViewCustomAttributes = "";

		// spanish
		$this->spanish->ViewValue = $this->spanish->CurrentValue;
		$this->spanish->ViewCustomAttributes = "";

		// arabic
		$this->arabic->ViewValue = $this->arabic->CurrentValue;
		$this->arabic->ViewCustomAttributes = "";

		// dutch
		$this->dutch->ViewValue = $this->dutch->CurrentValue;
		$this->dutch->ViewCustomAttributes = "";

		// russian
		$this->russian->ViewValue = $this->russian->CurrentValue;
		$this->russian->ViewCustomAttributes = "";

		// chinese
		$this->chinese->ViewValue = $this->chinese->CurrentValue;
		$this->chinese->ViewCustomAttributes = "";

		// turkish
		$this->turkish->ViewValue = $this->turkish->CurrentValue;
		$this->turkish->ViewCustomAttributes = "";

		// portuguese
		$this->portuguese->ViewValue = $this->portuguese->CurrentValue;
		$this->portuguese->ViewCustomAttributes = "";

		// hungarian
		$this->hungarian->ViewValue = $this->hungarian->CurrentValue;
		$this->hungarian->ViewCustomAttributes = "";

		// french
		$this->french->ViewValue = $this->french->CurrentValue;
		$this->french->ViewCustomAttributes = "";

		// greek
		$this->greek->ViewValue = $this->greek->CurrentValue;
		$this->greek->ViewCustomAttributes = "";

		// german
		$this->german->ViewValue = $this->german->CurrentValue;
		$this->german->ViewCustomAttributes = "";

		// italian
		$this->italian->ViewValue = $this->italian->CurrentValue;
		$this->italian->ViewCustomAttributes = "";

		// thai
		$this->thai->ViewValue = $this->thai->CurrentValue;
		$this->thai->ViewCustomAttributes = "";

		// urdu
		$this->urdu->ViewValue = $this->urdu->CurrentValue;
		$this->urdu->ViewCustomAttributes = "";

		// hindi
		$this->hindi->ViewValue = $this->hindi->CurrentValue;
		$this->hindi->ViewCustomAttributes = "";

		// latin
		$this->latin->ViewValue = $this->latin->CurrentValue;
		$this->latin->ViewCustomAttributes = "";

		// indonesian
		$this->indonesian->ViewValue = $this->indonesian->CurrentValue;
		$this->indonesian->ViewCustomAttributes = "";

		// japanese
		$this->japanese->ViewValue = $this->japanese->CurrentValue;
		$this->japanese->ViewCustomAttributes = "";

		// korean
		$this->korean->ViewValue = $this->korean->CurrentValue;
		$this->korean->ViewCustomAttributes = "";

		// ខ្មែរ
		$this->_178117D2179817C2179A->ViewValue = $this->_178117D2179817C2179A->CurrentValue;
		$this->_178117D2179817C2179A->ViewCustomAttributes = "";

		// Khmer
		$this->Khmer->ViewValue = $this->Khmer->CurrentValue;
		$this->Khmer->ViewCustomAttributes = "";

			// phrase
			$this->phrase->LinkCustomAttributes = "";
			$this->phrase->HrefValue = "";
			$this->phrase->TooltipValue = "";

			// english
			$this->english->LinkCustomAttributes = "";
			$this->english->HrefValue = "";
			$this->english->TooltipValue = "";

			// bengali
			$this->bengali->LinkCustomAttributes = "";
			$this->bengali->HrefValue = "";
			$this->bengali->TooltipValue = "";

			// spanish
			$this->spanish->LinkCustomAttributes = "";
			$this->spanish->HrefValue = "";
			$this->spanish->TooltipValue = "";

			// arabic
			$this->arabic->LinkCustomAttributes = "";
			$this->arabic->HrefValue = "";
			$this->arabic->TooltipValue = "";

			// dutch
			$this->dutch->LinkCustomAttributes = "";
			$this->dutch->HrefValue = "";
			$this->dutch->TooltipValue = "";

			// russian
			$this->russian->LinkCustomAttributes = "";
			$this->russian->HrefValue = "";
			$this->russian->TooltipValue = "";

			// chinese
			$this->chinese->LinkCustomAttributes = "";
			$this->chinese->HrefValue = "";
			$this->chinese->TooltipValue = "";

			// turkish
			$this->turkish->LinkCustomAttributes = "";
			$this->turkish->HrefValue = "";
			$this->turkish->TooltipValue = "";

			// portuguese
			$this->portuguese->LinkCustomAttributes = "";
			$this->portuguese->HrefValue = "";
			$this->portuguese->TooltipValue = "";

			// hungarian
			$this->hungarian->LinkCustomAttributes = "";
			$this->hungarian->HrefValue = "";
			$this->hungarian->TooltipValue = "";

			// french
			$this->french->LinkCustomAttributes = "";
			$this->french->HrefValue = "";
			$this->french->TooltipValue = "";

			// greek
			$this->greek->LinkCustomAttributes = "";
			$this->greek->HrefValue = "";
			$this->greek->TooltipValue = "";

			// german
			$this->german->LinkCustomAttributes = "";
			$this->german->HrefValue = "";
			$this->german->TooltipValue = "";

			// italian
			$this->italian->LinkCustomAttributes = "";
			$this->italian->HrefValue = "";
			$this->italian->TooltipValue = "";

			// thai
			$this->thai->LinkCustomAttributes = "";
			$this->thai->HrefValue = "";
			$this->thai->TooltipValue = "";

			// urdu
			$this->urdu->LinkCustomAttributes = "";
			$this->urdu->HrefValue = "";
			$this->urdu->TooltipValue = "";

			// hindi
			$this->hindi->LinkCustomAttributes = "";
			$this->hindi->HrefValue = "";
			$this->hindi->TooltipValue = "";

			// latin
			$this->latin->LinkCustomAttributes = "";
			$this->latin->HrefValue = "";
			$this->latin->TooltipValue = "";

			// indonesian
			$this->indonesian->LinkCustomAttributes = "";
			$this->indonesian->HrefValue = "";
			$this->indonesian->TooltipValue = "";

			// japanese
			$this->japanese->LinkCustomAttributes = "";
			$this->japanese->HrefValue = "";
			$this->japanese->TooltipValue = "";

			// korean
			$this->korean->LinkCustomAttributes = "";
			$this->korean->HrefValue = "";
			$this->korean->TooltipValue = "";

			// ខ្មែរ
			$this->_178117D2179817C2179A->LinkCustomAttributes = "";
			$this->_178117D2179817C2179A->HrefValue = "";
			$this->_178117D2179817C2179A->TooltipValue = "";

			// Khmer
			$this->Khmer->LinkCustomAttributes = "";
			$this->Khmer->HrefValue = "";
			$this->Khmer->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// phrase
			$this->phrase->EditAttrs["class"] = "form-control";
			$this->phrase->EditCustomAttributes = "";
			$this->phrase->EditValue = ew_HtmlEncode($this->phrase->CurrentValue);
			$this->phrase->PlaceHolder = ew_RemoveHtml($this->phrase->FldCaption());

			// english
			$this->english->EditAttrs["class"] = "form-control";
			$this->english->EditCustomAttributes = "";
			$this->english->EditValue = ew_HtmlEncode($this->english->CurrentValue);
			$this->english->PlaceHolder = ew_RemoveHtml($this->english->FldCaption());

			// bengali
			$this->bengali->EditAttrs["class"] = "form-control";
			$this->bengali->EditCustomAttributes = "";
			$this->bengali->EditValue = ew_HtmlEncode($this->bengali->CurrentValue);
			$this->bengali->PlaceHolder = ew_RemoveHtml($this->bengali->FldCaption());

			// spanish
			$this->spanish->EditAttrs["class"] = "form-control";
			$this->spanish->EditCustomAttributes = "";
			$this->spanish->EditValue = ew_HtmlEncode($this->spanish->CurrentValue);
			$this->spanish->PlaceHolder = ew_RemoveHtml($this->spanish->FldCaption());

			// arabic
			$this->arabic->EditAttrs["class"] = "form-control";
			$this->arabic->EditCustomAttributes = "";
			$this->arabic->EditValue = ew_HtmlEncode($this->arabic->CurrentValue);
			$this->arabic->PlaceHolder = ew_RemoveHtml($this->arabic->FldCaption());

			// dutch
			$this->dutch->EditAttrs["class"] = "form-control";
			$this->dutch->EditCustomAttributes = "";
			$this->dutch->EditValue = ew_HtmlEncode($this->dutch->CurrentValue);
			$this->dutch->PlaceHolder = ew_RemoveHtml($this->dutch->FldCaption());

			// russian
			$this->russian->EditAttrs["class"] = "form-control";
			$this->russian->EditCustomAttributes = "";
			$this->russian->EditValue = ew_HtmlEncode($this->russian->CurrentValue);
			$this->russian->PlaceHolder = ew_RemoveHtml($this->russian->FldCaption());

			// chinese
			$this->chinese->EditAttrs["class"] = "form-control";
			$this->chinese->EditCustomAttributes = "";
			$this->chinese->EditValue = ew_HtmlEncode($this->chinese->CurrentValue);
			$this->chinese->PlaceHolder = ew_RemoveHtml($this->chinese->FldCaption());

			// turkish
			$this->turkish->EditAttrs["class"] = "form-control";
			$this->turkish->EditCustomAttributes = "";
			$this->turkish->EditValue = ew_HtmlEncode($this->turkish->CurrentValue);
			$this->turkish->PlaceHolder = ew_RemoveHtml($this->turkish->FldCaption());

			// portuguese
			$this->portuguese->EditAttrs["class"] = "form-control";
			$this->portuguese->EditCustomAttributes = "";
			$this->portuguese->EditValue = ew_HtmlEncode($this->portuguese->CurrentValue);
			$this->portuguese->PlaceHolder = ew_RemoveHtml($this->portuguese->FldCaption());

			// hungarian
			$this->hungarian->EditAttrs["class"] = "form-control";
			$this->hungarian->EditCustomAttributes = "";
			$this->hungarian->EditValue = ew_HtmlEncode($this->hungarian->CurrentValue);
			$this->hungarian->PlaceHolder = ew_RemoveHtml($this->hungarian->FldCaption());

			// french
			$this->french->EditAttrs["class"] = "form-control";
			$this->french->EditCustomAttributes = "";
			$this->french->EditValue = ew_HtmlEncode($this->french->CurrentValue);
			$this->french->PlaceHolder = ew_RemoveHtml($this->french->FldCaption());

			// greek
			$this->greek->EditAttrs["class"] = "form-control";
			$this->greek->EditCustomAttributes = "";
			$this->greek->EditValue = ew_HtmlEncode($this->greek->CurrentValue);
			$this->greek->PlaceHolder = ew_RemoveHtml($this->greek->FldCaption());

			// german
			$this->german->EditAttrs["class"] = "form-control";
			$this->german->EditCustomAttributes = "";
			$this->german->EditValue = ew_HtmlEncode($this->german->CurrentValue);
			$this->german->PlaceHolder = ew_RemoveHtml($this->german->FldCaption());

			// italian
			$this->italian->EditAttrs["class"] = "form-control";
			$this->italian->EditCustomAttributes = "";
			$this->italian->EditValue = ew_HtmlEncode($this->italian->CurrentValue);
			$this->italian->PlaceHolder = ew_RemoveHtml($this->italian->FldCaption());

			// thai
			$this->thai->EditAttrs["class"] = "form-control";
			$this->thai->EditCustomAttributes = "";
			$this->thai->EditValue = ew_HtmlEncode($this->thai->CurrentValue);
			$this->thai->PlaceHolder = ew_RemoveHtml($this->thai->FldCaption());

			// urdu
			$this->urdu->EditAttrs["class"] = "form-control";
			$this->urdu->EditCustomAttributes = "";
			$this->urdu->EditValue = ew_HtmlEncode($this->urdu->CurrentValue);
			$this->urdu->PlaceHolder = ew_RemoveHtml($this->urdu->FldCaption());

			// hindi
			$this->hindi->EditAttrs["class"] = "form-control";
			$this->hindi->EditCustomAttributes = "";
			$this->hindi->EditValue = ew_HtmlEncode($this->hindi->CurrentValue);
			$this->hindi->PlaceHolder = ew_RemoveHtml($this->hindi->FldCaption());

			// latin
			$this->latin->EditAttrs["class"] = "form-control";
			$this->latin->EditCustomAttributes = "";
			$this->latin->EditValue = ew_HtmlEncode($this->latin->CurrentValue);
			$this->latin->PlaceHolder = ew_RemoveHtml($this->latin->FldCaption());

			// indonesian
			$this->indonesian->EditAttrs["class"] = "form-control";
			$this->indonesian->EditCustomAttributes = "";
			$this->indonesian->EditValue = ew_HtmlEncode($this->indonesian->CurrentValue);
			$this->indonesian->PlaceHolder = ew_RemoveHtml($this->indonesian->FldCaption());

			// japanese
			$this->japanese->EditAttrs["class"] = "form-control";
			$this->japanese->EditCustomAttributes = "";
			$this->japanese->EditValue = ew_HtmlEncode($this->japanese->CurrentValue);
			$this->japanese->PlaceHolder = ew_RemoveHtml($this->japanese->FldCaption());

			// korean
			$this->korean->EditAttrs["class"] = "form-control";
			$this->korean->EditCustomAttributes = "";
			$this->korean->EditValue = ew_HtmlEncode($this->korean->CurrentValue);
			$this->korean->PlaceHolder = ew_RemoveHtml($this->korean->FldCaption());

			// ខ្មែរ
			$this->_178117D2179817C2179A->EditAttrs["class"] = "form-control";
			$this->_178117D2179817C2179A->EditCustomAttributes = "";
			$this->_178117D2179817C2179A->EditValue = ew_HtmlEncode($this->_178117D2179817C2179A->CurrentValue);
			$this->_178117D2179817C2179A->PlaceHolder = ew_RemoveHtml($this->_178117D2179817C2179A->FldCaption());

			// Khmer
			$this->Khmer->EditAttrs["class"] = "form-control";
			$this->Khmer->EditCustomAttributes = "";
			$this->Khmer->EditValue = ew_HtmlEncode($this->Khmer->CurrentValue);
			$this->Khmer->PlaceHolder = ew_RemoveHtml($this->Khmer->FldCaption());

			// Add refer script
			// phrase

			$this->phrase->LinkCustomAttributes = "";
			$this->phrase->HrefValue = "";

			// english
			$this->english->LinkCustomAttributes = "";
			$this->english->HrefValue = "";

			// bengali
			$this->bengali->LinkCustomAttributes = "";
			$this->bengali->HrefValue = "";

			// spanish
			$this->spanish->LinkCustomAttributes = "";
			$this->spanish->HrefValue = "";

			// arabic
			$this->arabic->LinkCustomAttributes = "";
			$this->arabic->HrefValue = "";

			// dutch
			$this->dutch->LinkCustomAttributes = "";
			$this->dutch->HrefValue = "";

			// russian
			$this->russian->LinkCustomAttributes = "";
			$this->russian->HrefValue = "";

			// chinese
			$this->chinese->LinkCustomAttributes = "";
			$this->chinese->HrefValue = "";

			// turkish
			$this->turkish->LinkCustomAttributes = "";
			$this->turkish->HrefValue = "";

			// portuguese
			$this->portuguese->LinkCustomAttributes = "";
			$this->portuguese->HrefValue = "";

			// hungarian
			$this->hungarian->LinkCustomAttributes = "";
			$this->hungarian->HrefValue = "";

			// french
			$this->french->LinkCustomAttributes = "";
			$this->french->HrefValue = "";

			// greek
			$this->greek->LinkCustomAttributes = "";
			$this->greek->HrefValue = "";

			// german
			$this->german->LinkCustomAttributes = "";
			$this->german->HrefValue = "";

			// italian
			$this->italian->LinkCustomAttributes = "";
			$this->italian->HrefValue = "";

			// thai
			$this->thai->LinkCustomAttributes = "";
			$this->thai->HrefValue = "";

			// urdu
			$this->urdu->LinkCustomAttributes = "";
			$this->urdu->HrefValue = "";

			// hindi
			$this->hindi->LinkCustomAttributes = "";
			$this->hindi->HrefValue = "";

			// latin
			$this->latin->LinkCustomAttributes = "";
			$this->latin->HrefValue = "";

			// indonesian
			$this->indonesian->LinkCustomAttributes = "";
			$this->indonesian->HrefValue = "";

			// japanese
			$this->japanese->LinkCustomAttributes = "";
			$this->japanese->HrefValue = "";

			// korean
			$this->korean->LinkCustomAttributes = "";
			$this->korean->HrefValue = "";

			// ខ្មែរ
			$this->_178117D2179817C2179A->LinkCustomAttributes = "";
			$this->_178117D2179817C2179A->HrefValue = "";

			// Khmer
			$this->Khmer->LinkCustomAttributes = "";
			$this->Khmer->HrefValue = "";
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
		if (!$this->phrase->FldIsDetailKey && !is_null($this->phrase->FormValue) && $this->phrase->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->phrase->FldCaption(), $this->phrase->ReqErrMsg));
		}
		if (!$this->english->FldIsDetailKey && !is_null($this->english->FormValue) && $this->english->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->english->FldCaption(), $this->english->ReqErrMsg));
		}
		if (!$this->bengali->FldIsDetailKey && !is_null($this->bengali->FormValue) && $this->bengali->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->bengali->FldCaption(), $this->bengali->ReqErrMsg));
		}
		if (!$this->spanish->FldIsDetailKey && !is_null($this->spanish->FormValue) && $this->spanish->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->spanish->FldCaption(), $this->spanish->ReqErrMsg));
		}
		if (!$this->arabic->FldIsDetailKey && !is_null($this->arabic->FormValue) && $this->arabic->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->arabic->FldCaption(), $this->arabic->ReqErrMsg));
		}
		if (!$this->dutch->FldIsDetailKey && !is_null($this->dutch->FormValue) && $this->dutch->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->dutch->FldCaption(), $this->dutch->ReqErrMsg));
		}
		if (!$this->russian->FldIsDetailKey && !is_null($this->russian->FormValue) && $this->russian->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->russian->FldCaption(), $this->russian->ReqErrMsg));
		}
		if (!$this->chinese->FldIsDetailKey && !is_null($this->chinese->FormValue) && $this->chinese->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->chinese->FldCaption(), $this->chinese->ReqErrMsg));
		}
		if (!$this->turkish->FldIsDetailKey && !is_null($this->turkish->FormValue) && $this->turkish->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->turkish->FldCaption(), $this->turkish->ReqErrMsg));
		}
		if (!$this->portuguese->FldIsDetailKey && !is_null($this->portuguese->FormValue) && $this->portuguese->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->portuguese->FldCaption(), $this->portuguese->ReqErrMsg));
		}
		if (!$this->hungarian->FldIsDetailKey && !is_null($this->hungarian->FormValue) && $this->hungarian->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->hungarian->FldCaption(), $this->hungarian->ReqErrMsg));
		}
		if (!$this->french->FldIsDetailKey && !is_null($this->french->FormValue) && $this->french->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->french->FldCaption(), $this->french->ReqErrMsg));
		}
		if (!$this->greek->FldIsDetailKey && !is_null($this->greek->FormValue) && $this->greek->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->greek->FldCaption(), $this->greek->ReqErrMsg));
		}
		if (!$this->german->FldIsDetailKey && !is_null($this->german->FormValue) && $this->german->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->german->FldCaption(), $this->german->ReqErrMsg));
		}
		if (!$this->italian->FldIsDetailKey && !is_null($this->italian->FormValue) && $this->italian->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->italian->FldCaption(), $this->italian->ReqErrMsg));
		}
		if (!$this->thai->FldIsDetailKey && !is_null($this->thai->FormValue) && $this->thai->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->thai->FldCaption(), $this->thai->ReqErrMsg));
		}
		if (!$this->urdu->FldIsDetailKey && !is_null($this->urdu->FormValue) && $this->urdu->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->urdu->FldCaption(), $this->urdu->ReqErrMsg));
		}
		if (!$this->hindi->FldIsDetailKey && !is_null($this->hindi->FormValue) && $this->hindi->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->hindi->FldCaption(), $this->hindi->ReqErrMsg));
		}
		if (!$this->latin->FldIsDetailKey && !is_null($this->latin->FormValue) && $this->latin->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->latin->FldCaption(), $this->latin->ReqErrMsg));
		}
		if (!$this->indonesian->FldIsDetailKey && !is_null($this->indonesian->FormValue) && $this->indonesian->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->indonesian->FldCaption(), $this->indonesian->ReqErrMsg));
		}
		if (!$this->japanese->FldIsDetailKey && !is_null($this->japanese->FormValue) && $this->japanese->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->japanese->FldCaption(), $this->japanese->ReqErrMsg));
		}
		if (!$this->korean->FldIsDetailKey && !is_null($this->korean->FormValue) && $this->korean->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->korean->FldCaption(), $this->korean->ReqErrMsg));
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

		// phrase
		$this->phrase->SetDbValueDef($rsnew, $this->phrase->CurrentValue, "", FALSE);

		// english
		$this->english->SetDbValueDef($rsnew, $this->english->CurrentValue, "", FALSE);

		// bengali
		$this->bengali->SetDbValueDef($rsnew, $this->bengali->CurrentValue, "", FALSE);

		// spanish
		$this->spanish->SetDbValueDef($rsnew, $this->spanish->CurrentValue, "", FALSE);

		// arabic
		$this->arabic->SetDbValueDef($rsnew, $this->arabic->CurrentValue, "", FALSE);

		// dutch
		$this->dutch->SetDbValueDef($rsnew, $this->dutch->CurrentValue, "", FALSE);

		// russian
		$this->russian->SetDbValueDef($rsnew, $this->russian->CurrentValue, "", FALSE);

		// chinese
		$this->chinese->SetDbValueDef($rsnew, $this->chinese->CurrentValue, "", FALSE);

		// turkish
		$this->turkish->SetDbValueDef($rsnew, $this->turkish->CurrentValue, "", FALSE);

		// portuguese
		$this->portuguese->SetDbValueDef($rsnew, $this->portuguese->CurrentValue, "", FALSE);

		// hungarian
		$this->hungarian->SetDbValueDef($rsnew, $this->hungarian->CurrentValue, "", FALSE);

		// french
		$this->french->SetDbValueDef($rsnew, $this->french->CurrentValue, "", FALSE);

		// greek
		$this->greek->SetDbValueDef($rsnew, $this->greek->CurrentValue, "", FALSE);

		// german
		$this->german->SetDbValueDef($rsnew, $this->german->CurrentValue, "", FALSE);

		// italian
		$this->italian->SetDbValueDef($rsnew, $this->italian->CurrentValue, "", FALSE);

		// thai
		$this->thai->SetDbValueDef($rsnew, $this->thai->CurrentValue, "", FALSE);

		// urdu
		$this->urdu->SetDbValueDef($rsnew, $this->urdu->CurrentValue, "", FALSE);

		// hindi
		$this->hindi->SetDbValueDef($rsnew, $this->hindi->CurrentValue, "", FALSE);

		// latin
		$this->latin->SetDbValueDef($rsnew, $this->latin->CurrentValue, "", FALSE);

		// indonesian
		$this->indonesian->SetDbValueDef($rsnew, $this->indonesian->CurrentValue, "", FALSE);

		// japanese
		$this->japanese->SetDbValueDef($rsnew, $this->japanese->CurrentValue, "", FALSE);

		// korean
		$this->korean->SetDbValueDef($rsnew, $this->korean->CurrentValue, "", FALSE);

		// ខ្មែរ
		$this->_178117D2179817C2179A->SetDbValueDef($rsnew, $this->_178117D2179817C2179A->CurrentValue, NULL, FALSE);

		// Khmer
		$this->Khmer->SetDbValueDef($rsnew, $this->Khmer->CurrentValue, NULL, FALSE);

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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("_languagelist.php"), "", $this->TableVar, TRUE);
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
if (!isset($p_language_add)) $p_language_add = new cp_language_add();

// Page init
$p_language_add->Page_Init();

// Page main
$p_language_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$p_language_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "add";
var CurrentForm = f_languageadd = new ew_Form("f_languageadd", "add");

// Validate form
f_languageadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_phrase");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $_language->phrase->FldCaption(), $_language->phrase->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_english");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $_language->english->FldCaption(), $_language->english->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_bengali");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $_language->bengali->FldCaption(), $_language->bengali->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_spanish");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $_language->spanish->FldCaption(), $_language->spanish->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_arabic");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $_language->arabic->FldCaption(), $_language->arabic->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_dutch");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $_language->dutch->FldCaption(), $_language->dutch->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_russian");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $_language->russian->FldCaption(), $_language->russian->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_chinese");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $_language->chinese->FldCaption(), $_language->chinese->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_turkish");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $_language->turkish->FldCaption(), $_language->turkish->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_portuguese");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $_language->portuguese->FldCaption(), $_language->portuguese->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_hungarian");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $_language->hungarian->FldCaption(), $_language->hungarian->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_french");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $_language->french->FldCaption(), $_language->french->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_greek");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $_language->greek->FldCaption(), $_language->greek->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_german");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $_language->german->FldCaption(), $_language->german->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_italian");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $_language->italian->FldCaption(), $_language->italian->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_thai");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $_language->thai->FldCaption(), $_language->thai->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_urdu");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $_language->urdu->FldCaption(), $_language->urdu->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_hindi");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $_language->hindi->FldCaption(), $_language->hindi->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_latin");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $_language->latin->FldCaption(), $_language->latin->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_indonesian");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $_language->indonesian->FldCaption(), $_language->indonesian->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_japanese");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $_language->japanese->FldCaption(), $_language->japanese->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_korean");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $_language->korean->FldCaption(), $_language->korean->ReqErrMsg)) ?>");

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
f_languageadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
f_languageadd.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $p_language_add->ShowPageHeader(); ?>
<?php
$p_language_add->ShowMessage();
?>
<form name="f_languageadd" id="f_languageadd" class="<?php echo $p_language_add->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($p_language_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $p_language_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="_language">
<input type="hidden" name="a_add" id="a_add" value="A">
<input type="hidden" name="modal" value="<?php echo intval($p_language_add->IsModal) ?>">
<div class="ewAddDiv"><!-- page* -->
<?php if ($_language->phrase->Visible) { // phrase ?>
	<div id="r_phrase" class="form-group">
		<label id="elh__language_phrase" for="x_phrase" class="<?php echo $p_language_add->LeftColumnClass ?>"><?php echo $_language->phrase->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $p_language_add->RightColumnClass ?>"><div<?php echo $_language->phrase->CellAttributes() ?>>
<span id="el__language_phrase">
<textarea data-table="_language" data-field="x_phrase" name="x_phrase" id="x_phrase" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($_language->phrase->getPlaceHolder()) ?>"<?php echo $_language->phrase->EditAttributes() ?>><?php echo $_language->phrase->EditValue ?></textarea>
</span>
<?php echo $_language->phrase->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($_language->english->Visible) { // english ?>
	<div id="r_english" class="form-group">
		<label id="elh__language_english" for="x_english" class="<?php echo $p_language_add->LeftColumnClass ?>"><?php echo $_language->english->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $p_language_add->RightColumnClass ?>"><div<?php echo $_language->english->CellAttributes() ?>>
<span id="el__language_english">
<textarea data-table="_language" data-field="x_english" name="x_english" id="x_english" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($_language->english->getPlaceHolder()) ?>"<?php echo $_language->english->EditAttributes() ?>><?php echo $_language->english->EditValue ?></textarea>
</span>
<?php echo $_language->english->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($_language->bengali->Visible) { // bengali ?>
	<div id="r_bengali" class="form-group">
		<label id="elh__language_bengali" for="x_bengali" class="<?php echo $p_language_add->LeftColumnClass ?>"><?php echo $_language->bengali->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $p_language_add->RightColumnClass ?>"><div<?php echo $_language->bengali->CellAttributes() ?>>
<span id="el__language_bengali">
<textarea data-table="_language" data-field="x_bengali" name="x_bengali" id="x_bengali" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($_language->bengali->getPlaceHolder()) ?>"<?php echo $_language->bengali->EditAttributes() ?>><?php echo $_language->bengali->EditValue ?></textarea>
</span>
<?php echo $_language->bengali->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($_language->spanish->Visible) { // spanish ?>
	<div id="r_spanish" class="form-group">
		<label id="elh__language_spanish" for="x_spanish" class="<?php echo $p_language_add->LeftColumnClass ?>"><?php echo $_language->spanish->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $p_language_add->RightColumnClass ?>"><div<?php echo $_language->spanish->CellAttributes() ?>>
<span id="el__language_spanish">
<textarea data-table="_language" data-field="x_spanish" name="x_spanish" id="x_spanish" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($_language->spanish->getPlaceHolder()) ?>"<?php echo $_language->spanish->EditAttributes() ?>><?php echo $_language->spanish->EditValue ?></textarea>
</span>
<?php echo $_language->spanish->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($_language->arabic->Visible) { // arabic ?>
	<div id="r_arabic" class="form-group">
		<label id="elh__language_arabic" for="x_arabic" class="<?php echo $p_language_add->LeftColumnClass ?>"><?php echo $_language->arabic->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $p_language_add->RightColumnClass ?>"><div<?php echo $_language->arabic->CellAttributes() ?>>
<span id="el__language_arabic">
<textarea data-table="_language" data-field="x_arabic" name="x_arabic" id="x_arabic" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($_language->arabic->getPlaceHolder()) ?>"<?php echo $_language->arabic->EditAttributes() ?>><?php echo $_language->arabic->EditValue ?></textarea>
</span>
<?php echo $_language->arabic->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($_language->dutch->Visible) { // dutch ?>
	<div id="r_dutch" class="form-group">
		<label id="elh__language_dutch" for="x_dutch" class="<?php echo $p_language_add->LeftColumnClass ?>"><?php echo $_language->dutch->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $p_language_add->RightColumnClass ?>"><div<?php echo $_language->dutch->CellAttributes() ?>>
<span id="el__language_dutch">
<textarea data-table="_language" data-field="x_dutch" name="x_dutch" id="x_dutch" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($_language->dutch->getPlaceHolder()) ?>"<?php echo $_language->dutch->EditAttributes() ?>><?php echo $_language->dutch->EditValue ?></textarea>
</span>
<?php echo $_language->dutch->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($_language->russian->Visible) { // russian ?>
	<div id="r_russian" class="form-group">
		<label id="elh__language_russian" for="x_russian" class="<?php echo $p_language_add->LeftColumnClass ?>"><?php echo $_language->russian->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $p_language_add->RightColumnClass ?>"><div<?php echo $_language->russian->CellAttributes() ?>>
<span id="el__language_russian">
<textarea data-table="_language" data-field="x_russian" name="x_russian" id="x_russian" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($_language->russian->getPlaceHolder()) ?>"<?php echo $_language->russian->EditAttributes() ?>><?php echo $_language->russian->EditValue ?></textarea>
</span>
<?php echo $_language->russian->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($_language->chinese->Visible) { // chinese ?>
	<div id="r_chinese" class="form-group">
		<label id="elh__language_chinese" for="x_chinese" class="<?php echo $p_language_add->LeftColumnClass ?>"><?php echo $_language->chinese->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $p_language_add->RightColumnClass ?>"><div<?php echo $_language->chinese->CellAttributes() ?>>
<span id="el__language_chinese">
<textarea data-table="_language" data-field="x_chinese" name="x_chinese" id="x_chinese" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($_language->chinese->getPlaceHolder()) ?>"<?php echo $_language->chinese->EditAttributes() ?>><?php echo $_language->chinese->EditValue ?></textarea>
</span>
<?php echo $_language->chinese->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($_language->turkish->Visible) { // turkish ?>
	<div id="r_turkish" class="form-group">
		<label id="elh__language_turkish" for="x_turkish" class="<?php echo $p_language_add->LeftColumnClass ?>"><?php echo $_language->turkish->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $p_language_add->RightColumnClass ?>"><div<?php echo $_language->turkish->CellAttributes() ?>>
<span id="el__language_turkish">
<textarea data-table="_language" data-field="x_turkish" name="x_turkish" id="x_turkish" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($_language->turkish->getPlaceHolder()) ?>"<?php echo $_language->turkish->EditAttributes() ?>><?php echo $_language->turkish->EditValue ?></textarea>
</span>
<?php echo $_language->turkish->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($_language->portuguese->Visible) { // portuguese ?>
	<div id="r_portuguese" class="form-group">
		<label id="elh__language_portuguese" for="x_portuguese" class="<?php echo $p_language_add->LeftColumnClass ?>"><?php echo $_language->portuguese->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $p_language_add->RightColumnClass ?>"><div<?php echo $_language->portuguese->CellAttributes() ?>>
<span id="el__language_portuguese">
<textarea data-table="_language" data-field="x_portuguese" name="x_portuguese" id="x_portuguese" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($_language->portuguese->getPlaceHolder()) ?>"<?php echo $_language->portuguese->EditAttributes() ?>><?php echo $_language->portuguese->EditValue ?></textarea>
</span>
<?php echo $_language->portuguese->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($_language->hungarian->Visible) { // hungarian ?>
	<div id="r_hungarian" class="form-group">
		<label id="elh__language_hungarian" for="x_hungarian" class="<?php echo $p_language_add->LeftColumnClass ?>"><?php echo $_language->hungarian->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $p_language_add->RightColumnClass ?>"><div<?php echo $_language->hungarian->CellAttributes() ?>>
<span id="el__language_hungarian">
<textarea data-table="_language" data-field="x_hungarian" name="x_hungarian" id="x_hungarian" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($_language->hungarian->getPlaceHolder()) ?>"<?php echo $_language->hungarian->EditAttributes() ?>><?php echo $_language->hungarian->EditValue ?></textarea>
</span>
<?php echo $_language->hungarian->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($_language->french->Visible) { // french ?>
	<div id="r_french" class="form-group">
		<label id="elh__language_french" for="x_french" class="<?php echo $p_language_add->LeftColumnClass ?>"><?php echo $_language->french->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $p_language_add->RightColumnClass ?>"><div<?php echo $_language->french->CellAttributes() ?>>
<span id="el__language_french">
<textarea data-table="_language" data-field="x_french" name="x_french" id="x_french" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($_language->french->getPlaceHolder()) ?>"<?php echo $_language->french->EditAttributes() ?>><?php echo $_language->french->EditValue ?></textarea>
</span>
<?php echo $_language->french->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($_language->greek->Visible) { // greek ?>
	<div id="r_greek" class="form-group">
		<label id="elh__language_greek" for="x_greek" class="<?php echo $p_language_add->LeftColumnClass ?>"><?php echo $_language->greek->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $p_language_add->RightColumnClass ?>"><div<?php echo $_language->greek->CellAttributes() ?>>
<span id="el__language_greek">
<textarea data-table="_language" data-field="x_greek" name="x_greek" id="x_greek" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($_language->greek->getPlaceHolder()) ?>"<?php echo $_language->greek->EditAttributes() ?>><?php echo $_language->greek->EditValue ?></textarea>
</span>
<?php echo $_language->greek->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($_language->german->Visible) { // german ?>
	<div id="r_german" class="form-group">
		<label id="elh__language_german" for="x_german" class="<?php echo $p_language_add->LeftColumnClass ?>"><?php echo $_language->german->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $p_language_add->RightColumnClass ?>"><div<?php echo $_language->german->CellAttributes() ?>>
<span id="el__language_german">
<textarea data-table="_language" data-field="x_german" name="x_german" id="x_german" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($_language->german->getPlaceHolder()) ?>"<?php echo $_language->german->EditAttributes() ?>><?php echo $_language->german->EditValue ?></textarea>
</span>
<?php echo $_language->german->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($_language->italian->Visible) { // italian ?>
	<div id="r_italian" class="form-group">
		<label id="elh__language_italian" for="x_italian" class="<?php echo $p_language_add->LeftColumnClass ?>"><?php echo $_language->italian->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $p_language_add->RightColumnClass ?>"><div<?php echo $_language->italian->CellAttributes() ?>>
<span id="el__language_italian">
<textarea data-table="_language" data-field="x_italian" name="x_italian" id="x_italian" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($_language->italian->getPlaceHolder()) ?>"<?php echo $_language->italian->EditAttributes() ?>><?php echo $_language->italian->EditValue ?></textarea>
</span>
<?php echo $_language->italian->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($_language->thai->Visible) { // thai ?>
	<div id="r_thai" class="form-group">
		<label id="elh__language_thai" for="x_thai" class="<?php echo $p_language_add->LeftColumnClass ?>"><?php echo $_language->thai->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $p_language_add->RightColumnClass ?>"><div<?php echo $_language->thai->CellAttributes() ?>>
<span id="el__language_thai">
<textarea data-table="_language" data-field="x_thai" name="x_thai" id="x_thai" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($_language->thai->getPlaceHolder()) ?>"<?php echo $_language->thai->EditAttributes() ?>><?php echo $_language->thai->EditValue ?></textarea>
</span>
<?php echo $_language->thai->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($_language->urdu->Visible) { // urdu ?>
	<div id="r_urdu" class="form-group">
		<label id="elh__language_urdu" for="x_urdu" class="<?php echo $p_language_add->LeftColumnClass ?>"><?php echo $_language->urdu->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $p_language_add->RightColumnClass ?>"><div<?php echo $_language->urdu->CellAttributes() ?>>
<span id="el__language_urdu">
<textarea data-table="_language" data-field="x_urdu" name="x_urdu" id="x_urdu" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($_language->urdu->getPlaceHolder()) ?>"<?php echo $_language->urdu->EditAttributes() ?>><?php echo $_language->urdu->EditValue ?></textarea>
</span>
<?php echo $_language->urdu->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($_language->hindi->Visible) { // hindi ?>
	<div id="r_hindi" class="form-group">
		<label id="elh__language_hindi" for="x_hindi" class="<?php echo $p_language_add->LeftColumnClass ?>"><?php echo $_language->hindi->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $p_language_add->RightColumnClass ?>"><div<?php echo $_language->hindi->CellAttributes() ?>>
<span id="el__language_hindi">
<textarea data-table="_language" data-field="x_hindi" name="x_hindi" id="x_hindi" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($_language->hindi->getPlaceHolder()) ?>"<?php echo $_language->hindi->EditAttributes() ?>><?php echo $_language->hindi->EditValue ?></textarea>
</span>
<?php echo $_language->hindi->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($_language->latin->Visible) { // latin ?>
	<div id="r_latin" class="form-group">
		<label id="elh__language_latin" for="x_latin" class="<?php echo $p_language_add->LeftColumnClass ?>"><?php echo $_language->latin->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $p_language_add->RightColumnClass ?>"><div<?php echo $_language->latin->CellAttributes() ?>>
<span id="el__language_latin">
<textarea data-table="_language" data-field="x_latin" name="x_latin" id="x_latin" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($_language->latin->getPlaceHolder()) ?>"<?php echo $_language->latin->EditAttributes() ?>><?php echo $_language->latin->EditValue ?></textarea>
</span>
<?php echo $_language->latin->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($_language->indonesian->Visible) { // indonesian ?>
	<div id="r_indonesian" class="form-group">
		<label id="elh__language_indonesian" for="x_indonesian" class="<?php echo $p_language_add->LeftColumnClass ?>"><?php echo $_language->indonesian->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $p_language_add->RightColumnClass ?>"><div<?php echo $_language->indonesian->CellAttributes() ?>>
<span id="el__language_indonesian">
<textarea data-table="_language" data-field="x_indonesian" name="x_indonesian" id="x_indonesian" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($_language->indonesian->getPlaceHolder()) ?>"<?php echo $_language->indonesian->EditAttributes() ?>><?php echo $_language->indonesian->EditValue ?></textarea>
</span>
<?php echo $_language->indonesian->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($_language->japanese->Visible) { // japanese ?>
	<div id="r_japanese" class="form-group">
		<label id="elh__language_japanese" for="x_japanese" class="<?php echo $p_language_add->LeftColumnClass ?>"><?php echo $_language->japanese->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $p_language_add->RightColumnClass ?>"><div<?php echo $_language->japanese->CellAttributes() ?>>
<span id="el__language_japanese">
<textarea data-table="_language" data-field="x_japanese" name="x_japanese" id="x_japanese" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($_language->japanese->getPlaceHolder()) ?>"<?php echo $_language->japanese->EditAttributes() ?>><?php echo $_language->japanese->EditValue ?></textarea>
</span>
<?php echo $_language->japanese->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($_language->korean->Visible) { // korean ?>
	<div id="r_korean" class="form-group">
		<label id="elh__language_korean" for="x_korean" class="<?php echo $p_language_add->LeftColumnClass ?>"><?php echo $_language->korean->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $p_language_add->RightColumnClass ?>"><div<?php echo $_language->korean->CellAttributes() ?>>
<span id="el__language_korean">
<textarea data-table="_language" data-field="x_korean" name="x_korean" id="x_korean" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($_language->korean->getPlaceHolder()) ?>"<?php echo $_language->korean->EditAttributes() ?>><?php echo $_language->korean->EditValue ?></textarea>
</span>
<?php echo $_language->korean->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($_language->_178117D2179817C2179A->Visible) { // ខ្មែរ ?>
	<div id="r__178117D2179817C2179A" class="form-group">
		<label id="elh__language__178117D2179817C2179A" for="x__178117D2179817C2179A" class="<?php echo $p_language_add->LeftColumnClass ?>"><?php echo $_language->_178117D2179817C2179A->FldCaption() ?></label>
		<div class="<?php echo $p_language_add->RightColumnClass ?>"><div<?php echo $_language->_178117D2179817C2179A->CellAttributes() ?>>
<span id="el__language__178117D2179817C2179A">
<textarea data-table="_language" data-field="x__178117D2179817C2179A" name="x__178117D2179817C2179A" id="x__178117D2179817C2179A" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($_language->_178117D2179817C2179A->getPlaceHolder()) ?>"<?php echo $_language->_178117D2179817C2179A->EditAttributes() ?>><?php echo $_language->_178117D2179817C2179A->EditValue ?></textarea>
</span>
<?php echo $_language->_178117D2179817C2179A->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($_language->Khmer->Visible) { // Khmer ?>
	<div id="r_Khmer" class="form-group">
		<label id="elh__language_Khmer" for="x_Khmer" class="<?php echo $p_language_add->LeftColumnClass ?>"><?php echo $_language->Khmer->FldCaption() ?></label>
		<div class="<?php echo $p_language_add->RightColumnClass ?>"><div<?php echo $_language->Khmer->CellAttributes() ?>>
<span id="el__language_Khmer">
<textarea data-table="_language" data-field="x_Khmer" name="x_Khmer" id="x_Khmer" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($_language->Khmer->getPlaceHolder()) ?>"<?php echo $_language->Khmer->EditAttributes() ?>><?php echo $_language->Khmer->EditValue ?></textarea>
</span>
<?php echo $_language->Khmer->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div><!-- /page* -->
<?php if (!$p_language_add->IsModal) { ?>
<div class="form-group"><!-- buttons .form-group -->
	<div class="<?php echo $p_language_add->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $p_language_add->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div><!-- /buttons offset -->
</div><!-- /buttons .form-group -->
<?php } ?>
</form>
<script type="text/javascript">
f_languageadd.Init();
</script>
<?php
$p_language_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$p_language_add->Page_Terminate();
?>
