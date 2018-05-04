<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "teacherinfo.php" ?>
<?php include_once "subjectgridcls.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$teacher_add = NULL; // Initialize page object first

class cteacher_add extends cteacher {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = '{1F40EE59-17A4-45D3-96B6-AA2082B3D64A}';

	// Table name
	var $TableName = 'teacher';

	// Page object name
	var $PageObjName = 'teacher_add';

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

		// Table object (teacher)
		if (!isset($GLOBALS["teacher"]) || get_class($GLOBALS["teacher"]) == "cteacher") {
			$GLOBALS["teacher"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["teacher"];
		}

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'teacher', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("teacherlist.php"));
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
		$this->name->SetVisibility();
		$this->birthday->SetVisibility();
		$this->sex->SetVisibility();
		$this->religion->SetVisibility();
		$this->blood_group->SetVisibility();
		$this->address->SetVisibility();
		$this->phone->SetVisibility();
		$this->_email->SetVisibility();
		$this->password->SetVisibility();
		$this->authentication_key->SetVisibility();
		$this->photo->SetVisibility();
		$this->active->SetVisibility();
		$this->teacher_image->SetVisibility();
		$this->twitter->SetVisibility();
		$this->facebook->SetVisibility();
		$this->google->SetVisibility();
		$this->linkedin->SetVisibility();
		$this->pinterest->SetVisibility();

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

			// Get the keys for master table
			$sDetailTblVar = $this->getCurrentDetailTable();
			if ($sDetailTblVar <> "") {
				$DetailTblVar = explode(",", $sDetailTblVar);
				if (in_array("subject", $DetailTblVar)) {

					// Process auto fill for detail table 'subject'
					if (preg_match('/^fsubject(grid|add|addopt|edit|update|search)$/', @$_POST["form"])) {
						if (!isset($GLOBALS["subject_grid"])) $GLOBALS["subject_grid"] = new csubject_grid;
						$GLOBALS["subject_grid"]->Page_Init();
						$this->Page_Terminate();
						exit();
					}
				}
			}
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
		global $EW_EXPORT, $teacher;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($teacher);
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
					if ($pageName == "teacherview.php")
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
			if (@$_GET["teacher_id"] != "") {
				$this->teacher_id->setQueryStringValue($_GET["teacher_id"]);
				$this->setKey("teacher_id", $this->teacher_id->CurrentValue); // Set up key
			} else {
				$this->setKey("teacher_id", ""); // Clear key
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

		// Set up detail parameters
		$this->SetupDetailParms();

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
					$this->Page_Terminate("teacherlist.php"); // No matching record, return to list
				}

				// Set up detail parameters
				$this->SetupDetailParms();
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = "teacherlist.php";
					if (ew_GetPageName($sReturnUrl) == "teacherlist.php")
						$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to List page with correct master key if necessary
					elseif (ew_GetPageName($sReturnUrl) == "teacherview.php")
						$sReturnUrl = $this->GetViewUrl(); // View page, return to View page with keyurl directly
					$this->Page_Terminate($sReturnUrl); // Clean up and return
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Add failed, restore form values

					// Set up detail parameters
					$this->SetupDetailParms();
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
		$this->photo->Upload->Index = $objForm->Index;
		$this->photo->Upload->UploadFile();
		$this->photo->CurrentValue = $this->photo->Upload->FileName;
		$this->teacher_image->Upload->Index = $objForm->Index;
		$this->teacher_image->Upload->UploadFile();
	}

	// Load default values
	function LoadDefaultValues() {
		$this->teacher_id->CurrentValue = NULL;
		$this->teacher_id->OldValue = $this->teacher_id->CurrentValue;
		$this->name->CurrentValue = NULL;
		$this->name->OldValue = $this->name->CurrentValue;
		$this->birthday->CurrentValue = NULL;
		$this->birthday->OldValue = $this->birthday->CurrentValue;
		$this->sex->CurrentValue = NULL;
		$this->sex->OldValue = $this->sex->CurrentValue;
		$this->religion->CurrentValue = NULL;
		$this->religion->OldValue = $this->religion->CurrentValue;
		$this->blood_group->CurrentValue = NULL;
		$this->blood_group->OldValue = $this->blood_group->CurrentValue;
		$this->address->CurrentValue = NULL;
		$this->address->OldValue = $this->address->CurrentValue;
		$this->phone->CurrentValue = NULL;
		$this->phone->OldValue = $this->phone->CurrentValue;
		$this->_email->CurrentValue = NULL;
		$this->_email->OldValue = $this->_email->CurrentValue;
		$this->password->CurrentValue = NULL;
		$this->password->OldValue = $this->password->CurrentValue;
		$this->authentication_key->CurrentValue = NULL;
		$this->authentication_key->OldValue = $this->authentication_key->CurrentValue;
		$this->photo->Upload->DbValue = NULL;
		$this->photo->OldValue = $this->photo->Upload->DbValue;
		$this->photo->CurrentValue = NULL; // Clear file related field
		$this->active->CurrentValue = 1;
		$this->teacher_image->Upload->DbValue = NULL;
		$this->teacher_image->OldValue = $this->teacher_image->Upload->DbValue;
		$this->twitter->CurrentValue = "#";
		$this->facebook->CurrentValue = "#";
		$this->google->CurrentValue = "#";
		$this->linkedin->CurrentValue = "#";
		$this->pinterest->CurrentValue = "#";
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		$this->GetUploadFiles(); // Get upload files
		if (!$this->name->FldIsDetailKey) {
			$this->name->setFormValue($objForm->GetValue("x_name"));
		}
		if (!$this->birthday->FldIsDetailKey) {
			$this->birthday->setFormValue($objForm->GetValue("x_birthday"));
		}
		if (!$this->sex->FldIsDetailKey) {
			$this->sex->setFormValue($objForm->GetValue("x_sex"));
		}
		if (!$this->religion->FldIsDetailKey) {
			$this->religion->setFormValue($objForm->GetValue("x_religion"));
		}
		if (!$this->blood_group->FldIsDetailKey) {
			$this->blood_group->setFormValue($objForm->GetValue("x_blood_group"));
		}
		if (!$this->address->FldIsDetailKey) {
			$this->address->setFormValue($objForm->GetValue("x_address"));
		}
		if (!$this->phone->FldIsDetailKey) {
			$this->phone->setFormValue($objForm->GetValue("x_phone"));
		}
		if (!$this->_email->FldIsDetailKey) {
			$this->_email->setFormValue($objForm->GetValue("x__email"));
		}
		if (!$this->password->FldIsDetailKey) {
			$this->password->setFormValue($objForm->GetValue("x_password"));
		}
		if (!$this->authentication_key->FldIsDetailKey) {
			$this->authentication_key->setFormValue($objForm->GetValue("x_authentication_key"));
		}
		if (!$this->active->FldIsDetailKey) {
			$this->active->setFormValue($objForm->GetValue("x_active"));
		}
		if (!$this->twitter->FldIsDetailKey) {
			$this->twitter->setFormValue($objForm->GetValue("x_twitter"));
		}
		if (!$this->facebook->FldIsDetailKey) {
			$this->facebook->setFormValue($objForm->GetValue("x_facebook"));
		}
		if (!$this->google->FldIsDetailKey) {
			$this->google->setFormValue($objForm->GetValue("x_google"));
		}
		if (!$this->linkedin->FldIsDetailKey) {
			$this->linkedin->setFormValue($objForm->GetValue("x_linkedin"));
		}
		if (!$this->pinterest->FldIsDetailKey) {
			$this->pinterest->setFormValue($objForm->GetValue("x_pinterest"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->name->CurrentValue = $this->name->FormValue;
		$this->birthday->CurrentValue = $this->birthday->FormValue;
		$this->sex->CurrentValue = $this->sex->FormValue;
		$this->religion->CurrentValue = $this->religion->FormValue;
		$this->blood_group->CurrentValue = $this->blood_group->FormValue;
		$this->address->CurrentValue = $this->address->FormValue;
		$this->phone->CurrentValue = $this->phone->FormValue;
		$this->_email->CurrentValue = $this->_email->FormValue;
		$this->password->CurrentValue = $this->password->FormValue;
		$this->authentication_key->CurrentValue = $this->authentication_key->FormValue;
		$this->active->CurrentValue = $this->active->FormValue;
		$this->twitter->CurrentValue = $this->twitter->FormValue;
		$this->facebook->CurrentValue = $this->facebook->FormValue;
		$this->google->CurrentValue = $this->google->FormValue;
		$this->linkedin->CurrentValue = $this->linkedin->FormValue;
		$this->pinterest->CurrentValue = $this->pinterest->FormValue;
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
		$this->teacher_id->setDbValue($row['teacher_id']);
		$this->name->setDbValue($row['name']);
		$this->birthday->setDbValue($row['birthday']);
		$this->sex->setDbValue($row['sex']);
		$this->religion->setDbValue($row['religion']);
		$this->blood_group->setDbValue($row['blood_group']);
		$this->address->setDbValue($row['address']);
		$this->phone->setDbValue($row['phone']);
		$this->_email->setDbValue($row['email']);
		$this->password->setDbValue($row['password']);
		$this->authentication_key->setDbValue($row['authentication_key']);
		$this->photo->Upload->DbValue = $row['photo'];
		$this->photo->setDbValue($this->photo->Upload->DbValue);
		$this->active->setDbValue($row['active']);
		$this->teacher_image->Upload->DbValue = $row['teacher_image'];
		if (is_array($this->teacher_image->Upload->DbValue) || is_object($this->teacher_image->Upload->DbValue)) // Byte array
			$this->teacher_image->Upload->DbValue = ew_BytesToStr($this->teacher_image->Upload->DbValue);
		$this->twitter->setDbValue($row['twitter']);
		$this->facebook->setDbValue($row['facebook']);
		$this->google->setDbValue($row['google']);
		$this->linkedin->setDbValue($row['linkedin']);
		$this->pinterest->setDbValue($row['pinterest']);
	}

	// Return a row with default values
	function NewRow() {
		$this->LoadDefaultValues();
		$row = array();
		$row['teacher_id'] = $this->teacher_id->CurrentValue;
		$row['name'] = $this->name->CurrentValue;
		$row['birthday'] = $this->birthday->CurrentValue;
		$row['sex'] = $this->sex->CurrentValue;
		$row['religion'] = $this->religion->CurrentValue;
		$row['blood_group'] = $this->blood_group->CurrentValue;
		$row['address'] = $this->address->CurrentValue;
		$row['phone'] = $this->phone->CurrentValue;
		$row['email'] = $this->_email->CurrentValue;
		$row['password'] = $this->password->CurrentValue;
		$row['authentication_key'] = $this->authentication_key->CurrentValue;
		$row['photo'] = $this->photo->Upload->DbValue;
		$row['active'] = $this->active->CurrentValue;
		$row['teacher_image'] = $this->teacher_image->Upload->DbValue;
		$row['twitter'] = $this->twitter->CurrentValue;
		$row['facebook'] = $this->facebook->CurrentValue;
		$row['google'] = $this->google->CurrentValue;
		$row['linkedin'] = $this->linkedin->CurrentValue;
		$row['pinterest'] = $this->pinterest->CurrentValue;
		return $row;
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF)
			return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->teacher_id->DbValue = $row['teacher_id'];
		$this->name->DbValue = $row['name'];
		$this->birthday->DbValue = $row['birthday'];
		$this->sex->DbValue = $row['sex'];
		$this->religion->DbValue = $row['religion'];
		$this->blood_group->DbValue = $row['blood_group'];
		$this->address->DbValue = $row['address'];
		$this->phone->DbValue = $row['phone'];
		$this->_email->DbValue = $row['email'];
		$this->password->DbValue = $row['password'];
		$this->authentication_key->DbValue = $row['authentication_key'];
		$this->photo->Upload->DbValue = $row['photo'];
		$this->active->DbValue = $row['active'];
		$this->teacher_image->Upload->DbValue = $row['teacher_image'];
		$this->twitter->DbValue = $row['twitter'];
		$this->facebook->DbValue = $row['facebook'];
		$this->google->DbValue = $row['google'];
		$this->linkedin->DbValue = $row['linkedin'];
		$this->pinterest->DbValue = $row['pinterest'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
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
		// teacher_id
		// name
		// birthday
		// sex
		// religion
		// blood_group
		// address
		// phone
		// email
		// password
		// authentication_key
		// photo
		// active
		// teacher_image
		// twitter
		// facebook
		// google
		// linkedin
		// pinterest

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// teacher_id
		$this->teacher_id->ViewValue = $this->teacher_id->CurrentValue;
		$this->teacher_id->ViewCustomAttributes = "";

		// name
		$this->name->ViewValue = $this->name->CurrentValue;
		$this->name->ViewCustomAttributes = "";

		// birthday
		$this->birthday->ViewValue = $this->birthday->CurrentValue;
		$this->birthday->ViewValue = ew_FormatDateTime($this->birthday->ViewValue, 7);
		$this->birthday->ViewCustomAttributes = "";

		// sex
		if (strval($this->sex->CurrentValue) <> "") {
			$this->sex->ViewValue = $this->sex->OptionCaption($this->sex->CurrentValue);
		} else {
			$this->sex->ViewValue = NULL;
		}
		$this->sex->ViewCustomAttributes = "";

		// religion
		$this->religion->ViewValue = $this->religion->CurrentValue;
		$this->religion->ViewCustomAttributes = "";

		// blood_group
		$this->blood_group->ViewValue = $this->blood_group->CurrentValue;
		$this->blood_group->ViewCustomAttributes = "";

		// address
		$this->address->ViewValue = $this->address->CurrentValue;
		$this->address->ViewCustomAttributes = "";

		// phone
		$this->phone->ViewValue = $this->phone->CurrentValue;
		$this->phone->ViewCustomAttributes = "";

		// email
		$this->_email->ViewValue = $this->_email->CurrentValue;
		$this->_email->ViewCustomAttributes = "";

		// password
		$this->password->ViewValue = $Language->Phrase("PasswordMask");
		$this->password->ViewCustomAttributes = "";

		// authentication_key
		$this->authentication_key->ViewValue = $this->authentication_key->CurrentValue;
		$this->authentication_key->ViewCustomAttributes = "";

		// photo
		$this->photo->UploadPath = "../uploads/teacher";
		if (!ew_Empty($this->photo->Upload->DbValue)) {
			$this->photo->ImageWidth = 0;
			$this->photo->ImageHeight = 94;
			$this->photo->ImageAlt = $this->photo->FldAlt();
			$this->photo->ViewValue = $this->photo->Upload->DbValue;
		} else {
			$this->photo->ViewValue = "";
		}
		$this->photo->ViewCustomAttributes = "";

		// active
		if (strval($this->active->CurrentValue) <> "") {
			$this->active->ViewValue = $this->active->OptionCaption($this->active->CurrentValue);
		} else {
			$this->active->ViewValue = NULL;
		}
		$this->active->ViewCustomAttributes = "";

		// teacher_image
		if (!ew_Empty($this->teacher_image->Upload->DbValue)) {
			$this->teacher_image->ViewValue = "teacher_teacher_image_bv.php?" . "teacher_id=" . $this->teacher_id->CurrentValue;
			$this->teacher_image->IsBlobImage = ew_IsImageFile(ew_ContentExt(substr($this->teacher_image->Upload->DbValue, 0, 11)));
		} else {
			$this->teacher_image->ViewValue = "";
		}
		$this->teacher_image->ViewCustomAttributes = "";

		// twitter
		$this->twitter->ViewValue = $this->twitter->CurrentValue;
		$this->twitter->ViewCustomAttributes = "";

		// facebook
		$this->facebook->ViewValue = $this->facebook->CurrentValue;
		$this->facebook->ViewCustomAttributes = "";

		// google
		$this->google->ViewValue = $this->google->CurrentValue;
		$this->google->ViewCustomAttributes = "";

		// linkedin
		$this->linkedin->ViewValue = $this->linkedin->CurrentValue;
		$this->linkedin->ViewCustomAttributes = "";

		// pinterest
		$this->pinterest->ViewValue = $this->pinterest->CurrentValue;
		$this->pinterest->ViewCustomAttributes = "";

			// name
			$this->name->LinkCustomAttributes = "";
			$this->name->HrefValue = "";
			$this->name->TooltipValue = "";

			// birthday
			$this->birthday->LinkCustomAttributes = "";
			$this->birthday->HrefValue = "";
			$this->birthday->TooltipValue = "";

			// sex
			$this->sex->LinkCustomAttributes = "";
			$this->sex->HrefValue = "";
			$this->sex->TooltipValue = "";

			// religion
			$this->religion->LinkCustomAttributes = "";
			$this->religion->HrefValue = "";
			$this->religion->TooltipValue = "";

			// blood_group
			$this->blood_group->LinkCustomAttributes = "";
			$this->blood_group->HrefValue = "";
			$this->blood_group->TooltipValue = "";

			// address
			$this->address->LinkCustomAttributes = "";
			$this->address->HrefValue = "";
			$this->address->TooltipValue = "";

			// phone
			$this->phone->LinkCustomAttributes = "";
			$this->phone->HrefValue = "";
			$this->phone->TooltipValue = "";

			// email
			$this->_email->LinkCustomAttributes = "";
			$this->_email->HrefValue = "";
			$this->_email->TooltipValue = "";

			// password
			$this->password->LinkCustomAttributes = "";
			$this->password->HrefValue = "";
			$this->password->TooltipValue = "";

			// authentication_key
			$this->authentication_key->LinkCustomAttributes = "";
			$this->authentication_key->HrefValue = "";
			$this->authentication_key->TooltipValue = "";

			// photo
			$this->photo->LinkCustomAttributes = "";
			$this->photo->UploadPath = "../uploads/teacher";
			if (!ew_Empty($this->photo->Upload->DbValue)) {
				$this->photo->HrefValue = ew_GetFileUploadUrl($this->photo, $this->photo->Upload->DbValue); // Add prefix/suffix
				$this->photo->LinkAttrs["target"] = ""; // Add target
				if ($this->Export <> "") $this->photo->HrefValue = ew_FullUrl($this->photo->HrefValue, "href");
			} else {
				$this->photo->HrefValue = "";
			}
			$this->photo->HrefValue2 = $this->photo->UploadPath . $this->photo->Upload->DbValue;
			$this->photo->TooltipValue = "";
			if ($this->photo->UseColorbox) {
				if (ew_Empty($this->photo->TooltipValue))
					$this->photo->LinkAttrs["title"] = $Language->Phrase("ViewImageGallery");
				$this->photo->LinkAttrs["data-rel"] = "teacher_x_photo";
				ew_AppendClass($this->photo->LinkAttrs["class"], "ewLightbox");
			}

			// active
			$this->active->LinkCustomAttributes = "";
			$this->active->HrefValue = "";
			$this->active->TooltipValue = "";

			// teacher_image
			$this->teacher_image->LinkCustomAttributes = "";
			if (!empty($this->teacher_image->Upload->DbValue)) {
				$this->teacher_image->HrefValue = "teacher_teacher_image_bv.php?teacher_id=" . $this->teacher_id->CurrentValue;
				$this->teacher_image->LinkAttrs["target"] = "_blank";
				if ($this->Export <> "") $this->teacher_image->HrefValue = ew_FullUrl($this->teacher_image->HrefValue, "href");
			} else {
				$this->teacher_image->HrefValue = "";
			}
			$this->teacher_image->HrefValue2 = "teacher_teacher_image_bv.php?teacher_id=" . $this->teacher_id->CurrentValue;
			$this->teacher_image->TooltipValue = "";

			// twitter
			$this->twitter->LinkCustomAttributes = "";
			$this->twitter->HrefValue = "";
			$this->twitter->TooltipValue = "";

			// facebook
			$this->facebook->LinkCustomAttributes = "";
			$this->facebook->HrefValue = "";
			$this->facebook->TooltipValue = "";

			// google
			$this->google->LinkCustomAttributes = "";
			$this->google->HrefValue = "";
			$this->google->TooltipValue = "";

			// linkedin
			$this->linkedin->LinkCustomAttributes = "";
			$this->linkedin->HrefValue = "";
			$this->linkedin->TooltipValue = "";

			// pinterest
			$this->pinterest->LinkCustomAttributes = "";
			$this->pinterest->HrefValue = "";
			$this->pinterest->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// name
			$this->name->EditAttrs["class"] = "form-control";
			$this->name->EditCustomAttributes = "";
			$this->name->EditValue = ew_HtmlEncode($this->name->CurrentValue);
			$this->name->PlaceHolder = ew_RemoveHtml($this->name->FldCaption());

			// birthday
			$this->birthday->EditAttrs["class"] = "form-control";
			$this->birthday->EditCustomAttributes = "";
			$this->birthday->EditValue = ew_HtmlEncode($this->birthday->CurrentValue);
			$this->birthday->PlaceHolder = ew_RemoveHtml($this->birthday->FldCaption());

			// sex
			$this->sex->EditAttrs["class"] = "form-control";
			$this->sex->EditCustomAttributes = "";
			$this->sex->EditValue = $this->sex->Options(TRUE);

			// religion
			$this->religion->EditAttrs["class"] = "form-control";
			$this->religion->EditCustomAttributes = "";
			$this->religion->EditValue = ew_HtmlEncode($this->religion->CurrentValue);
			$this->religion->PlaceHolder = ew_RemoveHtml($this->religion->FldCaption());

			// blood_group
			$this->blood_group->EditAttrs["class"] = "form-control";
			$this->blood_group->EditCustomAttributes = "";
			$this->blood_group->EditValue = ew_HtmlEncode($this->blood_group->CurrentValue);
			$this->blood_group->PlaceHolder = ew_RemoveHtml($this->blood_group->FldCaption());

			// address
			$this->address->EditAttrs["class"] = "form-control";
			$this->address->EditCustomAttributes = "";
			$this->address->EditValue = ew_HtmlEncode($this->address->CurrentValue);
			$this->address->PlaceHolder = ew_RemoveHtml($this->address->FldCaption());

			// phone
			$this->phone->EditAttrs["class"] = "form-control";
			$this->phone->EditCustomAttributes = "";
			$this->phone->EditValue = ew_HtmlEncode($this->phone->CurrentValue);
			$this->phone->PlaceHolder = ew_RemoveHtml($this->phone->FldCaption());

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

			// authentication_key
			$this->authentication_key->EditAttrs["class"] = "form-control";
			$this->authentication_key->EditCustomAttributes = "";
			$this->authentication_key->EditValue = ew_HtmlEncode($this->authentication_key->CurrentValue);
			$this->authentication_key->PlaceHolder = ew_RemoveHtml($this->authentication_key->FldCaption());

			// photo
			$this->photo->EditAttrs["class"] = "form-control";
			$this->photo->EditCustomAttributes = "";
			$this->photo->UploadPath = "../uploads/teacher";
			if (!ew_Empty($this->photo->Upload->DbValue)) {
				$this->photo->ImageWidth = 0;
				$this->photo->ImageHeight = 94;
				$this->photo->ImageAlt = $this->photo->FldAlt();
				$this->photo->EditValue = $this->photo->Upload->DbValue;
			} else {
				$this->photo->EditValue = "";
			}
			if (!ew_Empty($this->photo->CurrentValue))
					$this->photo->Upload->FileName = $this->photo->CurrentValue;
			if (($this->CurrentAction == "I" || $this->CurrentAction == "C") && !$this->EventCancelled) ew_RenderUploadField($this->photo);

			// active
			$this->active->EditCustomAttributes = "";
			$this->active->EditValue = $this->active->Options(FALSE);

			// teacher_image
			$this->teacher_image->EditAttrs["class"] = "form-control";
			$this->teacher_image->EditCustomAttributes = "";
			if (!ew_Empty($this->teacher_image->Upload->DbValue)) {
				$this->teacher_image->EditValue = "teacher_teacher_image_bv.php?" . "teacher_id=" . $this->teacher_id->CurrentValue;
				$this->teacher_image->IsBlobImage = ew_IsImageFile(ew_ContentExt(substr($this->teacher_image->Upload->DbValue, 0, 11)));
			} else {
				$this->teacher_image->EditValue = "";
			}
			if (($this->CurrentAction == "I" || $this->CurrentAction == "C") && !$this->EventCancelled) ew_RenderUploadField($this->teacher_image);

			// twitter
			$this->twitter->EditAttrs["class"] = "form-control";
			$this->twitter->EditCustomAttributes = "";
			$this->twitter->EditValue = ew_HtmlEncode($this->twitter->CurrentValue);
			$this->twitter->PlaceHolder = ew_RemoveHtml($this->twitter->FldCaption());

			// facebook
			$this->facebook->EditAttrs["class"] = "form-control";
			$this->facebook->EditCustomAttributes = "";
			$this->facebook->EditValue = ew_HtmlEncode($this->facebook->CurrentValue);
			$this->facebook->PlaceHolder = ew_RemoveHtml($this->facebook->FldCaption());

			// google
			$this->google->EditAttrs["class"] = "form-control";
			$this->google->EditCustomAttributes = "";
			$this->google->EditValue = ew_HtmlEncode($this->google->CurrentValue);
			$this->google->PlaceHolder = ew_RemoveHtml($this->google->FldCaption());

			// linkedin
			$this->linkedin->EditAttrs["class"] = "form-control";
			$this->linkedin->EditCustomAttributes = "";
			$this->linkedin->EditValue = ew_HtmlEncode($this->linkedin->CurrentValue);
			$this->linkedin->PlaceHolder = ew_RemoveHtml($this->linkedin->FldCaption());

			// pinterest
			$this->pinterest->EditAttrs["class"] = "form-control";
			$this->pinterest->EditCustomAttributes = "";
			$this->pinterest->EditValue = ew_HtmlEncode($this->pinterest->CurrentValue);
			$this->pinterest->PlaceHolder = ew_RemoveHtml($this->pinterest->FldCaption());

			// Add refer script
			// name

			$this->name->LinkCustomAttributes = "";
			$this->name->HrefValue = "";

			// birthday
			$this->birthday->LinkCustomAttributes = "";
			$this->birthday->HrefValue = "";

			// sex
			$this->sex->LinkCustomAttributes = "";
			$this->sex->HrefValue = "";

			// religion
			$this->religion->LinkCustomAttributes = "";
			$this->religion->HrefValue = "";

			// blood_group
			$this->blood_group->LinkCustomAttributes = "";
			$this->blood_group->HrefValue = "";

			// address
			$this->address->LinkCustomAttributes = "";
			$this->address->HrefValue = "";

			// phone
			$this->phone->LinkCustomAttributes = "";
			$this->phone->HrefValue = "";

			// email
			$this->_email->LinkCustomAttributes = "";
			$this->_email->HrefValue = "";

			// password
			$this->password->LinkCustomAttributes = "";
			$this->password->HrefValue = "";

			// authentication_key
			$this->authentication_key->LinkCustomAttributes = "";
			$this->authentication_key->HrefValue = "";

			// photo
			$this->photo->LinkCustomAttributes = "";
			$this->photo->UploadPath = "../uploads/teacher";
			if (!ew_Empty($this->photo->Upload->DbValue)) {
				$this->photo->HrefValue = ew_GetFileUploadUrl($this->photo, $this->photo->Upload->DbValue); // Add prefix/suffix
				$this->photo->LinkAttrs["target"] = ""; // Add target
				if ($this->Export <> "") $this->photo->HrefValue = ew_FullUrl($this->photo->HrefValue, "href");
			} else {
				$this->photo->HrefValue = "";
			}
			$this->photo->HrefValue2 = $this->photo->UploadPath . $this->photo->Upload->DbValue;

			// active
			$this->active->LinkCustomAttributes = "";
			$this->active->HrefValue = "";

			// teacher_image
			$this->teacher_image->LinkCustomAttributes = "";
			if (!empty($this->teacher_image->Upload->DbValue)) {
				$this->teacher_image->HrefValue = "teacher_teacher_image_bv.php?teacher_id=" . $this->teacher_id->CurrentValue;
				$this->teacher_image->LinkAttrs["target"] = "_blank";
				if ($this->Export <> "") $this->teacher_image->HrefValue = ew_FullUrl($this->teacher_image->HrefValue, "href");
			} else {
				$this->teacher_image->HrefValue = "";
			}
			$this->teacher_image->HrefValue2 = "teacher_teacher_image_bv.php?teacher_id=" . $this->teacher_id->CurrentValue;

			// twitter
			$this->twitter->LinkCustomAttributes = "";
			$this->twitter->HrefValue = "";

			// facebook
			$this->facebook->LinkCustomAttributes = "";
			$this->facebook->HrefValue = "";

			// google
			$this->google->LinkCustomAttributes = "";
			$this->google->HrefValue = "";

			// linkedin
			$this->linkedin->LinkCustomAttributes = "";
			$this->linkedin->HrefValue = "";

			// pinterest
			$this->pinterest->LinkCustomAttributes = "";
			$this->pinterest->HrefValue = "";
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
		if (!$this->birthday->FldIsDetailKey && !is_null($this->birthday->FormValue) && $this->birthday->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->birthday->FldCaption(), $this->birthday->ReqErrMsg));
		}
		if (!$this->sex->FldIsDetailKey && !is_null($this->sex->FormValue) && $this->sex->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->sex->FldCaption(), $this->sex->ReqErrMsg));
		}
		if (!$this->religion->FldIsDetailKey && !is_null($this->religion->FormValue) && $this->religion->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->religion->FldCaption(), $this->religion->ReqErrMsg));
		}
		if (!$this->blood_group->FldIsDetailKey && !is_null($this->blood_group->FormValue) && $this->blood_group->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->blood_group->FldCaption(), $this->blood_group->ReqErrMsg));
		}
		if (!$this->address->FldIsDetailKey && !is_null($this->address->FormValue) && $this->address->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->address->FldCaption(), $this->address->ReqErrMsg));
		}
		if (!$this->phone->FldIsDetailKey && !is_null($this->phone->FormValue) && $this->phone->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->phone->FldCaption(), $this->phone->ReqErrMsg));
		}
		if (!$this->_email->FldIsDetailKey && !is_null($this->_email->FormValue) && $this->_email->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->_email->FldCaption(), $this->_email->ReqErrMsg));
		}
		if (!$this->password->FldIsDetailKey && !is_null($this->password->FormValue) && $this->password->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->password->FldCaption(), $this->password->ReqErrMsg));
		}
		if (!$this->authentication_key->FldIsDetailKey && !is_null($this->authentication_key->FormValue) && $this->authentication_key->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->authentication_key->FldCaption(), $this->authentication_key->ReqErrMsg));
		}
		if ($this->active->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->active->FldCaption(), $this->active->ReqErrMsg));
		}
		if ($this->teacher_image->Upload->FileName == "" && !$this->teacher_image->Upload->KeepFile) {
			ew_AddMessage($gsFormError, str_replace("%s", $this->teacher_image->FldCaption(), $this->teacher_image->ReqErrMsg));
		}
		if (!$this->twitter->FldIsDetailKey && !is_null($this->twitter->FormValue) && $this->twitter->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->twitter->FldCaption(), $this->twitter->ReqErrMsg));
		}
		if (!$this->facebook->FldIsDetailKey && !is_null($this->facebook->FormValue) && $this->facebook->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->facebook->FldCaption(), $this->facebook->ReqErrMsg));
		}
		if (!$this->google->FldIsDetailKey && !is_null($this->google->FormValue) && $this->google->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->google->FldCaption(), $this->google->ReqErrMsg));
		}
		if (!$this->linkedin->FldIsDetailKey && !is_null($this->linkedin->FormValue) && $this->linkedin->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->linkedin->FldCaption(), $this->linkedin->ReqErrMsg));
		}
		if (!$this->pinterest->FldIsDetailKey && !is_null($this->pinterest->FormValue) && $this->pinterest->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->pinterest->FldCaption(), $this->pinterest->ReqErrMsg));
		}

		// Validate detail grid
		$DetailTblVar = explode(",", $this->getCurrentDetailTable());
		if (in_array("subject", $DetailTblVar) && $GLOBALS["subject"]->DetailAdd) {
			if (!isset($GLOBALS["subject_grid"])) $GLOBALS["subject_grid"] = new csubject_grid(); // get detail page object
			$GLOBALS["subject_grid"]->ValidateGridForm();
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

		// Begin transaction
		if ($this->getCurrentDetailTable() <> "")
			$conn->BeginTrans();

		// Load db values from rsold
		$this->LoadDbValues($rsold);
		if ($rsold) {
			$this->photo->OldUploadPath = "../uploads/teacher";
			$this->photo->UploadPath = $this->photo->OldUploadPath;
		}
		$rsnew = array();

		// name
		$this->name->SetDbValueDef($rsnew, $this->name->CurrentValue, "", FALSE);

		// birthday
		$this->birthday->SetDbValueDef($rsnew, $this->birthday->CurrentValue, "", FALSE);

		// sex
		$this->sex->SetDbValueDef($rsnew, $this->sex->CurrentValue, "", FALSE);

		// religion
		$this->religion->SetDbValueDef($rsnew, $this->religion->CurrentValue, "", FALSE);

		// blood_group
		$this->blood_group->SetDbValueDef($rsnew, $this->blood_group->CurrentValue, "", FALSE);

		// address
		$this->address->SetDbValueDef($rsnew, $this->address->CurrentValue, "", FALSE);

		// phone
		$this->phone->SetDbValueDef($rsnew, $this->phone->CurrentValue, "", FALSE);

		// email
		$this->_email->SetDbValueDef($rsnew, $this->_email->CurrentValue, "", FALSE);

		// password
		$this->password->SetDbValueDef($rsnew, $this->password->CurrentValue, "", FALSE);

		// authentication_key
		$this->authentication_key->SetDbValueDef($rsnew, $this->authentication_key->CurrentValue, "", FALSE);

		// photo
		if ($this->photo->Visible && !$this->photo->Upload->KeepFile) {
			$this->photo->Upload->DbValue = ""; // No need to delete old file
			if ($this->photo->Upload->FileName == "") {
				$rsnew['photo'] = NULL;
			} else {
				$rsnew['photo'] = $this->photo->Upload->FileName;
			}
			$this->photo->ImageWidth = 1300; // Resize width
			$this->photo->ImageHeight = 1400; // Resize height
		}

		// active
		$this->active->SetDbValueDef($rsnew, $this->active->CurrentValue, 0, strval($this->active->CurrentValue) == "");

		// teacher_image
		if ($this->teacher_image->Visible && !$this->teacher_image->Upload->KeepFile) {
			if (is_null($this->teacher_image->Upload->Value)) {
				$rsnew['teacher_image'] = NULL;
			} else {
				$rsnew['teacher_image'] = $this->teacher_image->Upload->Value;
			}
		}

		// twitter
		$this->twitter->SetDbValueDef($rsnew, $this->twitter->CurrentValue, "", strval($this->twitter->CurrentValue) == "");

		// facebook
		$this->facebook->SetDbValueDef($rsnew, $this->facebook->CurrentValue, "", strval($this->facebook->CurrentValue) == "");

		// google
		$this->google->SetDbValueDef($rsnew, $this->google->CurrentValue, "", strval($this->google->CurrentValue) == "");

		// linkedin
		$this->linkedin->SetDbValueDef($rsnew, $this->linkedin->CurrentValue, "", strval($this->linkedin->CurrentValue) == "");

		// pinterest
		$this->pinterest->SetDbValueDef($rsnew, $this->pinterest->CurrentValue, "", strval($this->pinterest->CurrentValue) == "");
		if ($this->photo->Visible && !$this->photo->Upload->KeepFile) {
			$this->photo->UploadPath = "../uploads/teacher";
			$OldFiles = ew_Empty($this->photo->Upload->DbValue) ? array() : array($this->photo->Upload->DbValue);
			if (!ew_Empty($this->photo->Upload->FileName)) {
				$NewFiles = array($this->photo->Upload->FileName);
				$NewFileCount = count($NewFiles);
				for ($i = 0; $i < $NewFileCount; $i++) {
					$fldvar = ($this->photo->Upload->Index < 0) ? $this->photo->FldVar : substr($this->photo->FldVar, 0, 1) . $this->photo->Upload->Index . substr($this->photo->FldVar, 1);
					if ($NewFiles[$i] <> "") {
						$file = $NewFiles[$i];
						if (file_exists(ew_UploadTempPath($fldvar, $this->photo->TblVar) . $file)) {
							$file1 = ew_UploadFileNameEx($this->photo->PhysicalUploadPath(), $file); // Get new file name
							if ($file1 <> $file) { // Rename temp file
								while (file_exists(ew_UploadTempPath($fldvar, $this->photo->TblVar) . $file1) || file_exists($this->photo->PhysicalUploadPath() . $file1)) // Make sure no file name clash
									$file1 = ew_UniqueFilename($this->photo->PhysicalUploadPath(), $file1, TRUE); // Use indexed name
								rename(ew_UploadTempPath($fldvar, $this->photo->TblVar) . $file, ew_UploadTempPath($fldvar, $this->photo->TblVar) . $file1);
								$NewFiles[$i] = $file1;
							}
						}
					}
				}
				$this->photo->Upload->DbValue = empty($OldFiles) ? "" : implode(EW_MULTIPLE_UPLOAD_SEPARATOR, $OldFiles);
				$this->photo->Upload->FileName = implode(EW_MULTIPLE_UPLOAD_SEPARATOR, $NewFiles);
				$this->photo->SetDbValueDef($rsnew, $this->photo->Upload->FileName, NULL, strval($this->photo->CurrentValue) == "");
			}
		}

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);
		if ($bInsertRow) {
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			$AddRow = $this->Insert($rsnew);
			$conn->raiseErrorFn = '';
			if ($AddRow) {
				if ($this->photo->Visible && !$this->photo->Upload->KeepFile) {
					$OldFiles = ew_Empty($this->photo->Upload->DbValue) ? array() : array($this->photo->Upload->DbValue);
					if (!ew_Empty($this->photo->Upload->FileName)) {
						$NewFiles = array($this->photo->Upload->FileName);
						$NewFiles2 = array($rsnew['photo']);
						$NewFileCount = count($NewFiles);
						for ($i = 0; $i < $NewFileCount; $i++) {
							$fldvar = ($this->photo->Upload->Index < 0) ? $this->photo->FldVar : substr($this->photo->FldVar, 0, 1) . $this->photo->Upload->Index . substr($this->photo->FldVar, 1);
							if ($NewFiles[$i] <> "") {
								$file = ew_UploadTempPath($fldvar, $this->photo->TblVar) . $NewFiles[$i];
								if (file_exists($file)) {
									if (@$NewFiles2[$i] <> "") // Use correct file name
										$NewFiles[$i] = $NewFiles2[$i];
									if (!$this->photo->Upload->ResizeAndSaveToFile($this->photo->ImageWidth, $this->photo->ImageHeight, EW_THUMBNAIL_DEFAULT_QUALITY, $NewFiles[$i], TRUE, $i)) {
										$this->setFailureMessage($Language->Phrase("UploadErrMsg7"));
										return FALSE;
									}
								}
							}
						}
					} else {
						$NewFiles = array();
					}
				}
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

		// Add detail records
		if ($AddRow) {
			$DetailTblVar = explode(",", $this->getCurrentDetailTable());
			if (in_array("subject", $DetailTblVar) && $GLOBALS["subject"]->DetailAdd) {
				$GLOBALS["subject"]->teacher_id->setSessionValue($this->teacher_id->CurrentValue); // Set master key
				if (!isset($GLOBALS["subject_grid"])) $GLOBALS["subject_grid"] = new csubject_grid(); // Get detail page object
				$AddRow = $GLOBALS["subject_grid"]->GridInsert();
				if (!$AddRow)
					$GLOBALS["subject"]->teacher_id->setSessionValue(""); // Clear master key if insert failed
			}
		}

		// Commit/Rollback transaction
		if ($this->getCurrentDetailTable() <> "") {
			if ($AddRow) {
				$conn->CommitTrans(); // Commit transaction
			} else {
				$conn->RollbackTrans(); // Rollback transaction
			}
		}
		if ($AddRow) {

			// Call Row Inserted event
			$rs = ($rsold == NULL) ? NULL : $rsold->fields;
			$this->Row_Inserted($rs, $rsnew);
		}

		// photo
		ew_CleanUploadTempPath($this->photo, $this->photo->Upload->Index);

		// teacher_image
		ew_CleanUploadTempPath($this->teacher_image, $this->teacher_image->Upload->Index);
		return $AddRow;
	}

	// Set up detail parms based on QueryString
	function SetupDetailParms() {

		// Get the keys for master table
		if (isset($_GET[EW_TABLE_SHOW_DETAIL])) {
			$sDetailTblVar = $_GET[EW_TABLE_SHOW_DETAIL];
			$this->setCurrentDetailTable($sDetailTblVar);
		} else {
			$sDetailTblVar = $this->getCurrentDetailTable();
		}
		if ($sDetailTblVar <> "") {
			$DetailTblVar = explode(",", $sDetailTblVar);
			if (in_array("subject", $DetailTblVar)) {
				if (!isset($GLOBALS["subject_grid"]))
					$GLOBALS["subject_grid"] = new csubject_grid;
				if ($GLOBALS["subject_grid"]->DetailAdd) {
					if ($this->CopyRecord)
						$GLOBALS["subject_grid"]->CurrentMode = "copy";
					else
						$GLOBALS["subject_grid"]->CurrentMode = "add";
					$GLOBALS["subject_grid"]->CurrentAction = "gridadd";

					// Save current master table to detail table
					$GLOBALS["subject_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["subject_grid"]->setStartRecordNumber(1);
					$GLOBALS["subject_grid"]->teacher_id->FldIsDetailKey = TRUE;
					$GLOBALS["subject_grid"]->teacher_id->CurrentValue = $this->teacher_id->CurrentValue;
					$GLOBALS["subject_grid"]->teacher_id->setSessionValue($GLOBALS["subject_grid"]->teacher_id->CurrentValue);
				}
			}
		}
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("teacherlist.php"), "", $this->TableVar, TRUE);
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
if (!isset($teacher_add)) $teacher_add = new cteacher_add();

// Page init
$teacher_add->Page_Init();

// Page main
$teacher_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$teacher_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "add";
var CurrentForm = fteacheradd = new ew_Form("fteacheradd", "add");

// Validate form
fteacheradd.Validate = function() {
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
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $teacher->name->FldCaption(), $teacher->name->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_birthday");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $teacher->birthday->FldCaption(), $teacher->birthday->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_sex");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $teacher->sex->FldCaption(), $teacher->sex->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_religion");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $teacher->religion->FldCaption(), $teacher->religion->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_blood_group");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $teacher->blood_group->FldCaption(), $teacher->blood_group->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_address");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $teacher->address->FldCaption(), $teacher->address->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_phone");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $teacher->phone->FldCaption(), $teacher->phone->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "__email");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $teacher->_email->FldCaption(), $teacher->_email->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_password");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $teacher->password->FldCaption(), $teacher->password->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_authentication_key");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $teacher->authentication_key->FldCaption(), $teacher->authentication_key->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_active");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $teacher->active->FldCaption(), $teacher->active->ReqErrMsg)) ?>");
			felm = this.GetElements("x" + infix + "_teacher_image");
			elm = this.GetElements("fn_x" + infix + "_teacher_image");
			if (felm && elm && !ew_HasValue(elm))
				return this.OnError(felm, "<?php echo ew_JsEncode2(str_replace("%s", $teacher->teacher_image->FldCaption(), $teacher->teacher_image->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_twitter");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $teacher->twitter->FldCaption(), $teacher->twitter->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_facebook");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $teacher->facebook->FldCaption(), $teacher->facebook->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_google");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $teacher->google->FldCaption(), $teacher->google->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_linkedin");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $teacher->linkedin->FldCaption(), $teacher->linkedin->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_pinterest");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $teacher->pinterest->FldCaption(), $teacher->pinterest->ReqErrMsg)) ?>");

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
fteacheradd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fteacheradd.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
fteacheradd.Lists["x_sex"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fteacheradd.Lists["x_sex"].Options = <?php echo json_encode($teacher_add->sex->Options()) ?>;
fteacheradd.Lists["x_active"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fteacheradd.Lists["x_active"].Options = <?php echo json_encode($teacher_add->active->Options()) ?>;

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $teacher_add->ShowPageHeader(); ?>
<?php
$teacher_add->ShowMessage();
?>
<form name="fteacheradd" id="fteacheradd" class="<?php echo $teacher_add->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($teacher_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $teacher_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="teacher">
<input type="hidden" name="a_add" id="a_add" value="A">
<input type="hidden" name="modal" value="<?php echo intval($teacher_add->IsModal) ?>">
<div class="ewAddDiv"><!-- page* -->
<?php if ($teacher->name->Visible) { // name ?>
	<div id="r_name" class="form-group">
		<label id="elh_teacher_name" for="x_name" class="<?php echo $teacher_add->LeftColumnClass ?>"><?php echo $teacher->name->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $teacher_add->RightColumnClass ?>"><div<?php echo $teacher->name->CellAttributes() ?>>
<span id="el_teacher_name">
<input type="text" data-table="teacher" data-field="x_name" data-page="1" name="x_name" id="x_name" placeholder="<?php echo ew_HtmlEncode($teacher->name->getPlaceHolder()) ?>" value="<?php echo $teacher->name->EditValue ?>"<?php echo $teacher->name->EditAttributes() ?>>
</span>
<?php echo $teacher->name->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($teacher->birthday->Visible) { // birthday ?>
	<div id="r_birthday" class="form-group">
		<label id="elh_teacher_birthday" for="x_birthday" class="<?php echo $teacher_add->LeftColumnClass ?>"><?php echo $teacher->birthday->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $teacher_add->RightColumnClass ?>"><div<?php echo $teacher->birthday->CellAttributes() ?>>
<span id="el_teacher_birthday">
<input type="text" data-table="teacher" data-field="x_birthday" data-page="1" name="x_birthday" id="x_birthday" placeholder="<?php echo ew_HtmlEncode($teacher->birthday->getPlaceHolder()) ?>" value="<?php echo $teacher->birthday->EditValue ?>"<?php echo $teacher->birthday->EditAttributes() ?>>
<?php if (!$teacher->birthday->ReadOnly && !$teacher->birthday->Disabled && !isset($teacher->birthday->EditAttrs["readonly"]) && !isset($teacher->birthday->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateDateTimePicker("fteacheradd", "x_birthday", {"ignoreReadonly":true,"useCurrent":false,"format":7});
</script>
<?php } ?>
</span>
<?php echo $teacher->birthday->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($teacher->sex->Visible) { // sex ?>
	<div id="r_sex" class="form-group">
		<label id="elh_teacher_sex" for="x_sex" class="<?php echo $teacher_add->LeftColumnClass ?>"><?php echo $teacher->sex->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $teacher_add->RightColumnClass ?>"><div<?php echo $teacher->sex->CellAttributes() ?>>
<span id="el_teacher_sex">
<select data-table="teacher" data-field="x_sex" data-page="1" data-value-separator="<?php echo $teacher->sex->DisplayValueSeparatorAttribute() ?>" id="x_sex" name="x_sex"<?php echo $teacher->sex->EditAttributes() ?>>
<?php echo $teacher->sex->SelectOptionListHtml("x_sex") ?>
</select>
</span>
<?php echo $teacher->sex->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($teacher->religion->Visible) { // religion ?>
	<div id="r_religion" class="form-group">
		<label id="elh_teacher_religion" for="x_religion" class="<?php echo $teacher_add->LeftColumnClass ?>"><?php echo $teacher->religion->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $teacher_add->RightColumnClass ?>"><div<?php echo $teacher->religion->CellAttributes() ?>>
<span id="el_teacher_religion">
<input type="text" data-table="teacher" data-field="x_religion" data-page="1" name="x_religion" id="x_religion" placeholder="<?php echo ew_HtmlEncode($teacher->religion->getPlaceHolder()) ?>" value="<?php echo $teacher->religion->EditValue ?>"<?php echo $teacher->religion->EditAttributes() ?>>
</span>
<?php echo $teacher->religion->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($teacher->blood_group->Visible) { // blood_group ?>
	<div id="r_blood_group" class="form-group">
		<label id="elh_teacher_blood_group" for="x_blood_group" class="<?php echo $teacher_add->LeftColumnClass ?>"><?php echo $teacher->blood_group->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $teacher_add->RightColumnClass ?>"><div<?php echo $teacher->blood_group->CellAttributes() ?>>
<span id="el_teacher_blood_group">
<input type="text" data-table="teacher" data-field="x_blood_group" data-page="1" name="x_blood_group" id="x_blood_group" placeholder="<?php echo ew_HtmlEncode($teacher->blood_group->getPlaceHolder()) ?>" value="<?php echo $teacher->blood_group->EditValue ?>"<?php echo $teacher->blood_group->EditAttributes() ?>>
</span>
<?php echo $teacher->blood_group->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($teacher->address->Visible) { // address ?>
	<div id="r_address" class="form-group">
		<label id="elh_teacher_address" for="x_address" class="<?php echo $teacher_add->LeftColumnClass ?>"><?php echo $teacher->address->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $teacher_add->RightColumnClass ?>"><div<?php echo $teacher->address->CellAttributes() ?>>
<span id="el_teacher_address">
<input type="text" data-table="teacher" data-field="x_address" data-page="1" name="x_address" id="x_address" placeholder="<?php echo ew_HtmlEncode($teacher->address->getPlaceHolder()) ?>" value="<?php echo $teacher->address->EditValue ?>"<?php echo $teacher->address->EditAttributes() ?>>
</span>
<?php echo $teacher->address->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($teacher->phone->Visible) { // phone ?>
	<div id="r_phone" class="form-group">
		<label id="elh_teacher_phone" for="x_phone" class="<?php echo $teacher_add->LeftColumnClass ?>"><?php echo $teacher->phone->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $teacher_add->RightColumnClass ?>"><div<?php echo $teacher->phone->CellAttributes() ?>>
<span id="el_teacher_phone">
<input type="text" data-table="teacher" data-field="x_phone" data-page="1" name="x_phone" id="x_phone" placeholder="<?php echo ew_HtmlEncode($teacher->phone->getPlaceHolder()) ?>" value="<?php echo $teacher->phone->EditValue ?>"<?php echo $teacher->phone->EditAttributes() ?>>
</span>
<?php echo $teacher->phone->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($teacher->_email->Visible) { // email ?>
	<div id="r__email" class="form-group">
		<label id="elh_teacher__email" for="x__email" class="<?php echo $teacher_add->LeftColumnClass ?>"><?php echo $teacher->_email->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $teacher_add->RightColumnClass ?>"><div<?php echo $teacher->_email->CellAttributes() ?>>
<span id="el_teacher__email">
<input type="text" data-table="teacher" data-field="x__email" data-page="1" name="x__email" id="x__email" placeholder="<?php echo ew_HtmlEncode($teacher->_email->getPlaceHolder()) ?>" value="<?php echo $teacher->_email->EditValue ?>"<?php echo $teacher->_email->EditAttributes() ?>>
</span>
<?php echo $teacher->_email->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($teacher->password->Visible) { // password ?>
	<div id="r_password" class="form-group">
		<label id="elh_teacher_password" for="x_password" class="<?php echo $teacher_add->LeftColumnClass ?>"><?php echo $teacher->password->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $teacher_add->RightColumnClass ?>"><div<?php echo $teacher->password->CellAttributes() ?>>
<span id="el_teacher_password">
<input type="password" data-field="x_password" name="x_password" id="x_password" placeholder="<?php echo ew_HtmlEncode($teacher->password->getPlaceHolder()) ?>"<?php echo $teacher->password->EditAttributes() ?>>
</span>
<?php echo $teacher->password->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($teacher->authentication_key->Visible) { // authentication_key ?>
	<div id="r_authentication_key" class="form-group">
		<label id="elh_teacher_authentication_key" for="x_authentication_key" class="<?php echo $teacher_add->LeftColumnClass ?>"><?php echo $teacher->authentication_key->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $teacher_add->RightColumnClass ?>"><div<?php echo $teacher->authentication_key->CellAttributes() ?>>
<span id="el_teacher_authentication_key">
<input type="text" data-table="teacher" data-field="x_authentication_key" data-page="1" name="x_authentication_key" id="x_authentication_key" placeholder="<?php echo ew_HtmlEncode($teacher->authentication_key->getPlaceHolder()) ?>" value="<?php echo $teacher->authentication_key->EditValue ?>"<?php echo $teacher->authentication_key->EditAttributes() ?>>
</span>
<?php echo $teacher->authentication_key->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($teacher->photo->Visible) { // photo ?>
	<div id="r_photo" class="form-group">
		<label id="elh_teacher_photo" class="<?php echo $teacher_add->LeftColumnClass ?>"><?php echo $teacher->photo->FldCaption() ?></label>
		<div class="<?php echo $teacher_add->RightColumnClass ?>"><div<?php echo $teacher->photo->CellAttributes() ?>>
<span id="el_teacher_photo">
<div id="fd_x_photo">
<span title="<?php echo $teacher->photo->FldTitle() ? $teacher->photo->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($teacher->photo->ReadOnly || $teacher->photo->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="teacher" data-field="x_photo" data-page="1" name="x_photo" id="x_photo"<?php echo $teacher->photo->EditAttributes() ?>>
</span>
<input type="hidden" name="fn_x_photo" id= "fn_x_photo" value="<?php echo $teacher->photo->Upload->FileName ?>">
<input type="hidden" name="fa_x_photo" id= "fa_x_photo" value="0">
<input type="hidden" name="fs_x_photo" id= "fs_x_photo" value="250">
<input type="hidden" name="fx_x_photo" id= "fx_x_photo" value="<?php echo $teacher->photo->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_photo" id= "fm_x_photo" value="<?php echo $teacher->photo->UploadMaxFileSize ?>">
</div>
<table id="ft_x_photo" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php echo $teacher->photo->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($teacher->active->Visible) { // active ?>
	<div id="r_active" class="form-group">
		<label id="elh_teacher_active" class="<?php echo $teacher_add->LeftColumnClass ?>"><?php echo $teacher->active->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $teacher_add->RightColumnClass ?>"><div<?php echo $teacher->active->CellAttributes() ?>>
<span id="el_teacher_active">
<div id="tp_x_active" class="ewTemplate"><input type="radio" data-table="teacher" data-field="x_active" data-page="1" data-value-separator="<?php echo $teacher->active->DisplayValueSeparatorAttribute() ?>" name="x_active" id="x_active" value="{value}"<?php echo $teacher->active->EditAttributes() ?>></div>
<div id="dsl_x_active" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $teacher->active->RadioButtonListHtml(FALSE, "x_active", 1) ?>
</div></div>
</span>
<?php echo $teacher->active->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($teacher->teacher_image->Visible) { // teacher_image ?>
	<div id="r_teacher_image" class="form-group">
		<label id="elh_teacher_teacher_image" class="<?php echo $teacher_add->LeftColumnClass ?>"><?php echo $teacher->teacher_image->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $teacher_add->RightColumnClass ?>"><div<?php echo $teacher->teacher_image->CellAttributes() ?>>
<span id="el_teacher_teacher_image">
<div id="fd_x_teacher_image">
<span title="<?php echo $teacher->teacher_image->FldTitle() ? $teacher->teacher_image->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($teacher->teacher_image->ReadOnly || $teacher->teacher_image->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="teacher" data-field="x_teacher_image" data-page="1" name="x_teacher_image" id="x_teacher_image"<?php echo $teacher->teacher_image->EditAttributes() ?>>
</span>
<input type="hidden" name="fn_x_teacher_image" id= "fn_x_teacher_image" value="<?php echo $teacher->teacher_image->Upload->FileName ?>">
<input type="hidden" name="fa_x_teacher_image" id= "fa_x_teacher_image" value="0">
<input type="hidden" name="fs_x_teacher_image" id= "fs_x_teacher_image" value="0">
<input type="hidden" name="fx_x_teacher_image" id= "fx_x_teacher_image" value="<?php echo $teacher->teacher_image->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_teacher_image" id= "fm_x_teacher_image" value="<?php echo $teacher->teacher_image->UploadMaxFileSize ?>">
</div>
<table id="ft_x_teacher_image" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php echo $teacher->teacher_image->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($teacher->twitter->Visible) { // twitter ?>
	<div id="r_twitter" class="form-group">
		<label id="elh_teacher_twitter" for="x_twitter" class="<?php echo $teacher_add->LeftColumnClass ?>"><?php echo $teacher->twitter->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $teacher_add->RightColumnClass ?>"><div<?php echo $teacher->twitter->CellAttributes() ?>>
<span id="el_teacher_twitter">
<input type="text" data-table="teacher" data-field="x_twitter" data-page="1" name="x_twitter" id="x_twitter" size="30" maxlength="250" placeholder="<?php echo ew_HtmlEncode($teacher->twitter->getPlaceHolder()) ?>" value="<?php echo $teacher->twitter->EditValue ?>"<?php echo $teacher->twitter->EditAttributes() ?>>
</span>
<?php echo $teacher->twitter->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($teacher->facebook->Visible) { // facebook ?>
	<div id="r_facebook" class="form-group">
		<label id="elh_teacher_facebook" for="x_facebook" class="<?php echo $teacher_add->LeftColumnClass ?>"><?php echo $teacher->facebook->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $teacher_add->RightColumnClass ?>"><div<?php echo $teacher->facebook->CellAttributes() ?>>
<span id="el_teacher_facebook">
<input type="text" data-table="teacher" data-field="x_facebook" data-page="1" name="x_facebook" id="x_facebook" size="30" maxlength="250" placeholder="<?php echo ew_HtmlEncode($teacher->facebook->getPlaceHolder()) ?>" value="<?php echo $teacher->facebook->EditValue ?>"<?php echo $teacher->facebook->EditAttributes() ?>>
</span>
<?php echo $teacher->facebook->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($teacher->google->Visible) { // google ?>
	<div id="r_google" class="form-group">
		<label id="elh_teacher_google" for="x_google" class="<?php echo $teacher_add->LeftColumnClass ?>"><?php echo $teacher->google->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $teacher_add->RightColumnClass ?>"><div<?php echo $teacher->google->CellAttributes() ?>>
<span id="el_teacher_google">
<input type="text" data-table="teacher" data-field="x_google" data-page="1" name="x_google" id="x_google" size="30" maxlength="250" placeholder="<?php echo ew_HtmlEncode($teacher->google->getPlaceHolder()) ?>" value="<?php echo $teacher->google->EditValue ?>"<?php echo $teacher->google->EditAttributes() ?>>
</span>
<?php echo $teacher->google->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($teacher->linkedin->Visible) { // linkedin ?>
	<div id="r_linkedin" class="form-group">
		<label id="elh_teacher_linkedin" for="x_linkedin" class="<?php echo $teacher_add->LeftColumnClass ?>"><?php echo $teacher->linkedin->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $teacher_add->RightColumnClass ?>"><div<?php echo $teacher->linkedin->CellAttributes() ?>>
<span id="el_teacher_linkedin">
<input type="text" data-table="teacher" data-field="x_linkedin" data-page="1" name="x_linkedin" id="x_linkedin" size="30" maxlength="250" placeholder="<?php echo ew_HtmlEncode($teacher->linkedin->getPlaceHolder()) ?>" value="<?php echo $teacher->linkedin->EditValue ?>"<?php echo $teacher->linkedin->EditAttributes() ?>>
</span>
<?php echo $teacher->linkedin->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($teacher->pinterest->Visible) { // pinterest ?>
	<div id="r_pinterest" class="form-group">
		<label id="elh_teacher_pinterest" for="x_pinterest" class="<?php echo $teacher_add->LeftColumnClass ?>"><?php echo $teacher->pinterest->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $teacher_add->RightColumnClass ?>"><div<?php echo $teacher->pinterest->CellAttributes() ?>>
<span id="el_teacher_pinterest">
<input type="text" data-table="teacher" data-field="x_pinterest" data-page="1" name="x_pinterest" id="x_pinterest" size="30" maxlength="250" placeholder="<?php echo ew_HtmlEncode($teacher->pinterest->getPlaceHolder()) ?>" value="<?php echo $teacher->pinterest->EditValue ?>"<?php echo $teacher->pinterest->EditAttributes() ?>>
</span>
<?php echo $teacher->pinterest->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div><!-- /page* -->
<?php
	if (in_array("subject", explode(",", $teacher->getCurrentDetailTable())) && $subject->DetailAdd) {
?>
<?php if ($teacher->getCurrentDetailTable() <> "") { ?>
<h4 class="ewDetailCaption"><?php echo $Language->TablePhrase("subject", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "subjectgrid.php" ?>
<?php } ?>
<?php if (!$teacher_add->IsModal) { ?>
<div class="form-group"><!-- buttons .form-group -->
	<div class="<?php echo $teacher_add->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $teacher_add->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div><!-- /buttons offset -->
</div><!-- /buttons .form-group -->
<?php } ?>
</form>
<script type="text/javascript">
fteacheradd.Init();
</script>
<?php
$teacher_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$teacher_add->Page_Terminate();
?>
