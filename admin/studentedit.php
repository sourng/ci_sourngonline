<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "studentinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$student_edit = NULL; // Initialize page object first

class cstudent_edit extends cstudent {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = '{1F40EE59-17A4-45D3-96B6-AA2082B3D64A}';

	// Table name
	var $TableName = 'student';

	// Page object name
	var $PageObjName = 'student_edit';

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

		// Table object (student)
		if (!isset($GLOBALS["student"]) || get_class($GLOBALS["student"]) == "cstudent") {
			$GLOBALS["student"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["student"];
		}

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'student', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("studentlist.php"));
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
		if ($this->IsAdd() || $this->IsCopy() || $this->IsGridAdd())
			$this->student_id->Visible = FALSE;
		$this->name->SetVisibility();
		$this->birthday->SetVisibility();
		$this->sex->SetVisibility();
		$this->religion->SetVisibility();
		$this->blood_group->SetVisibility();
		$this->address->SetVisibility();
		$this->phone->SetVisibility();
		$this->_email->SetVisibility();
		$this->password->SetVisibility();
		$this->class_id->SetVisibility();
		$this->section_id->SetVisibility();
		$this->parent_id->SetVisibility();
		$this->roll->SetVisibility();
		$this->transport_id->SetVisibility();
		$this->dormitory_id->SetVisibility();
		$this->dormitory_room_number->SetVisibility();
		$this->authentication_key->SetVisibility();
		$this->image->SetVisibility();

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
		global $EW_EXPORT, $student;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($student);
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
					if ($pageName == "studentview.php")
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
			if ($objForm->HasValue("x_student_id")) {
				$this->student_id->setFormValue($objForm->GetValue("x_student_id"));
			}
		} else {
			$this->CurrentAction = "I"; // Default action is display

			// Load key from QueryString
			$loadByQuery = FALSE;
			if (isset($_GET["student_id"])) {
				$this->student_id->setQueryStringValue($_GET["student_id"]);
				$loadByQuery = TRUE;
			} else {
				$this->student_id->CurrentValue = NULL;
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
					$this->Page_Terminate("studentlist.php"); // No matching record, return to list
				}
				break;
			Case "U": // Update
				$sReturnUrl = $this->getReturnUrl();
				if (ew_GetPageName($sReturnUrl) == "studentlist.php")
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
		$this->image->Upload->Index = $objForm->Index;
		$this->image->Upload->UploadFile();
		$this->image->CurrentValue = $this->image->Upload->FileName;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		$this->GetUploadFiles(); // Get upload files
		if (!$this->student_id->FldIsDetailKey)
			$this->student_id->setFormValue($objForm->GetValue("x_student_id"));
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
		if (!$this->class_id->FldIsDetailKey) {
			$this->class_id->setFormValue($objForm->GetValue("x_class_id"));
		}
		if (!$this->section_id->FldIsDetailKey) {
			$this->section_id->setFormValue($objForm->GetValue("x_section_id"));
		}
		if (!$this->parent_id->FldIsDetailKey) {
			$this->parent_id->setFormValue($objForm->GetValue("x_parent_id"));
		}
		if (!$this->roll->FldIsDetailKey) {
			$this->roll->setFormValue($objForm->GetValue("x_roll"));
		}
		if (!$this->transport_id->FldIsDetailKey) {
			$this->transport_id->setFormValue($objForm->GetValue("x_transport_id"));
		}
		if (!$this->dormitory_id->FldIsDetailKey) {
			$this->dormitory_id->setFormValue($objForm->GetValue("x_dormitory_id"));
		}
		if (!$this->dormitory_room_number->FldIsDetailKey) {
			$this->dormitory_room_number->setFormValue($objForm->GetValue("x_dormitory_room_number"));
		}
		if (!$this->authentication_key->FldIsDetailKey) {
			$this->authentication_key->setFormValue($objForm->GetValue("x_authentication_key"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->student_id->CurrentValue = $this->student_id->FormValue;
		$this->name->CurrentValue = $this->name->FormValue;
		$this->birthday->CurrentValue = $this->birthday->FormValue;
		$this->sex->CurrentValue = $this->sex->FormValue;
		$this->religion->CurrentValue = $this->religion->FormValue;
		$this->blood_group->CurrentValue = $this->blood_group->FormValue;
		$this->address->CurrentValue = $this->address->FormValue;
		$this->phone->CurrentValue = $this->phone->FormValue;
		$this->_email->CurrentValue = $this->_email->FormValue;
		$this->password->CurrentValue = $this->password->FormValue;
		$this->class_id->CurrentValue = $this->class_id->FormValue;
		$this->section_id->CurrentValue = $this->section_id->FormValue;
		$this->parent_id->CurrentValue = $this->parent_id->FormValue;
		$this->roll->CurrentValue = $this->roll->FormValue;
		$this->transport_id->CurrentValue = $this->transport_id->FormValue;
		$this->dormitory_id->CurrentValue = $this->dormitory_id->FormValue;
		$this->dormitory_room_number->CurrentValue = $this->dormitory_room_number->FormValue;
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
		$this->student_id->setDbValue($row['student_id']);
		$this->name->setDbValue($row['name']);
		$this->birthday->setDbValue($row['birthday']);
		$this->sex->setDbValue($row['sex']);
		$this->religion->setDbValue($row['religion']);
		$this->blood_group->setDbValue($row['blood_group']);
		$this->address->setDbValue($row['address']);
		$this->phone->setDbValue($row['phone']);
		$this->_email->setDbValue($row['email']);
		$this->password->setDbValue($row['password']);
		$this->class_id->setDbValue($row['class_id']);
		if (array_key_exists('EV__class_id', $rs->fields)) {
			$this->class_id->VirtualValue = $rs->fields('EV__class_id'); // Set up virtual field value
		} else {
			$this->class_id->VirtualValue = ""; // Clear value
		}
		$this->section_id->setDbValue($row['section_id']);
		if (array_key_exists('EV__section_id', $rs->fields)) {
			$this->section_id->VirtualValue = $rs->fields('EV__section_id'); // Set up virtual field value
		} else {
			$this->section_id->VirtualValue = ""; // Clear value
		}
		$this->parent_id->setDbValue($row['parent_id']);
		$this->roll->setDbValue($row['roll']);
		$this->transport_id->setDbValue($row['transport_id']);
		if (array_key_exists('EV__transport_id', $rs->fields)) {
			$this->transport_id->VirtualValue = $rs->fields('EV__transport_id'); // Set up virtual field value
		} else {
			$this->transport_id->VirtualValue = ""; // Clear value
		}
		$this->dormitory_id->setDbValue($row['dormitory_id']);
		if (array_key_exists('EV__dormitory_id', $rs->fields)) {
			$this->dormitory_id->VirtualValue = $rs->fields('EV__dormitory_id'); // Set up virtual field value
		} else {
			$this->dormitory_id->VirtualValue = ""; // Clear value
		}
		$this->dormitory_room_number->setDbValue($row['dormitory_room_number']);
		$this->authentication_key->setDbValue($row['authentication_key']);
		$this->image->Upload->DbValue = $row['image'];
		$this->image->setDbValue($this->image->Upload->DbValue);
	}

	// Return a row with default values
	function NewRow() {
		$row = array();
		$row['student_id'] = NULL;
		$row['name'] = NULL;
		$row['birthday'] = NULL;
		$row['sex'] = NULL;
		$row['religion'] = NULL;
		$row['blood_group'] = NULL;
		$row['address'] = NULL;
		$row['phone'] = NULL;
		$row['email'] = NULL;
		$row['password'] = NULL;
		$row['class_id'] = NULL;
		$row['section_id'] = NULL;
		$row['parent_id'] = NULL;
		$row['roll'] = NULL;
		$row['transport_id'] = NULL;
		$row['dormitory_id'] = NULL;
		$row['dormitory_room_number'] = NULL;
		$row['authentication_key'] = NULL;
		$row['image'] = NULL;
		return $row;
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF)
			return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->student_id->DbValue = $row['student_id'];
		$this->name->DbValue = $row['name'];
		$this->birthday->DbValue = $row['birthday'];
		$this->sex->DbValue = $row['sex'];
		$this->religion->DbValue = $row['religion'];
		$this->blood_group->DbValue = $row['blood_group'];
		$this->address->DbValue = $row['address'];
		$this->phone->DbValue = $row['phone'];
		$this->_email->DbValue = $row['email'];
		$this->password->DbValue = $row['password'];
		$this->class_id->DbValue = $row['class_id'];
		$this->section_id->DbValue = $row['section_id'];
		$this->parent_id->DbValue = $row['parent_id'];
		$this->roll->DbValue = $row['roll'];
		$this->transport_id->DbValue = $row['transport_id'];
		$this->dormitory_id->DbValue = $row['dormitory_id'];
		$this->dormitory_room_number->DbValue = $row['dormitory_room_number'];
		$this->authentication_key->DbValue = $row['authentication_key'];
		$this->image->Upload->DbValue = $row['image'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("student_id")) <> "")
			$this->student_id->CurrentValue = $this->getKey("student_id"); // student_id
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
		// student_id
		// name
		// birthday
		// sex
		// religion
		// blood_group
		// address
		// phone
		// email
		// password
		// class_id
		// section_id
		// parent_id
		// roll
		// transport_id
		// dormitory_id
		// dormitory_room_number
		// authentication_key
		// image

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// student_id
		$this->student_id->ViewValue = $this->student_id->CurrentValue;
		$this->student_id->ViewCustomAttributes = "";

		// name
		$this->name->ViewValue = $this->name->CurrentValue;
		$this->name->ViewCustomAttributes = "";

		// birthday
		$this->birthday->ViewValue = $this->birthday->CurrentValue;
		$this->birthday->ViewValue = ew_FormatDateTime($this->birthday->ViewValue, 2);
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
		if (strval($this->blood_group->CurrentValue) <> "") {
			$this->blood_group->ViewValue = $this->blood_group->OptionCaption($this->blood_group->CurrentValue);
		} else {
			$this->blood_group->ViewValue = NULL;
		}
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

		// class_id
		if ($this->class_id->VirtualValue <> "") {
			$this->class_id->ViewValue = $this->class_id->VirtualValue;
		} else {
		if (strval($this->class_id->CurrentValue) <> "") {
			$sFilterWrk = "`class_id`" . ew_SearchString("=", $this->class_id->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `class_id`, `name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `class`";
		$sWhereWrk = "";
		$this->class_id->LookupFilters = array("dx1" => '`name`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->class_id, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->class_id->ViewValue = $this->class_id->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->class_id->ViewValue = $this->class_id->CurrentValue;
			}
		} else {
			$this->class_id->ViewValue = NULL;
		}
		}
		$this->class_id->ViewCustomAttributes = "";

		// section_id
		if ($this->section_id->VirtualValue <> "") {
			$this->section_id->ViewValue = $this->section_id->VirtualValue;
		} else {
		if (strval($this->section_id->CurrentValue) <> "") {
			$sFilterWrk = "`section_id`" . ew_SearchString("=", $this->section_id->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `section_id`, `name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `section`";
		$sWhereWrk = "";
		$this->section_id->LookupFilters = array("dx1" => '`name`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->section_id, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->section_id->ViewValue = $this->section_id->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->section_id->ViewValue = $this->section_id->CurrentValue;
			}
		} else {
			$this->section_id->ViewValue = NULL;
		}
		}
		$this->section_id->ViewCustomAttributes = "";

		// parent_id
		if (strval($this->parent_id->CurrentValue) <> "") {
			$sFilterWrk = "`parent_id`" . ew_SearchString("=", $this->parent_id->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `parent_id`, `name` AS `DispFld`, `phone` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `parent`";
		$sWhereWrk = "";
		$this->parent_id->LookupFilters = array("dx1" => '`name`', "dx2" => '`phone`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->parent_id, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$this->parent_id->ViewValue = $this->parent_id->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->parent_id->ViewValue = $this->parent_id->CurrentValue;
			}
		} else {
			$this->parent_id->ViewValue = NULL;
		}
		$this->parent_id->ViewCustomAttributes = "";

		// roll
		$this->roll->ViewValue = $this->roll->CurrentValue;
		$this->roll->ViewCustomAttributes = "";

		// transport_id
		if ($this->transport_id->VirtualValue <> "") {
			$this->transport_id->ViewValue = $this->transport_id->VirtualValue;
		} else {
		if (strval($this->transport_id->CurrentValue) <> "") {
			$sFilterWrk = "`transport_id`" . ew_SearchString("=", $this->transport_id->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT DISTINCT `transport_id`, `route_name` AS `DispFld`, `number_of_vehicle` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `transport`";
		$sWhereWrk = "";
		$this->transport_id->LookupFilters = array("dx1" => '`route_name`', "dx2" => '`number_of_vehicle`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->transport_id, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$this->transport_id->ViewValue = $this->transport_id->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->transport_id->ViewValue = $this->transport_id->CurrentValue;
			}
		} else {
			$this->transport_id->ViewValue = NULL;
		}
		}
		$this->transport_id->ViewCustomAttributes = "";

		// dormitory_id
		if ($this->dormitory_id->VirtualValue <> "") {
			$this->dormitory_id->ViewValue = $this->dormitory_id->VirtualValue;
		} else {
		if (strval($this->dormitory_id->CurrentValue) <> "") {
			$sFilterWrk = "`dormitory_id`" . ew_SearchString("=", $this->dormitory_id->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT DISTINCT `dormitory_id`, `name` AS `DispFld`, `number_of_room` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `dormitory`";
		$sWhereWrk = "";
		$this->dormitory_id->LookupFilters = array("dx1" => '`name`', "dx2" => '`number_of_room`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->dormitory_id, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$this->dormitory_id->ViewValue = $this->dormitory_id->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->dormitory_id->ViewValue = $this->dormitory_id->CurrentValue;
			}
		} else {
			$this->dormitory_id->ViewValue = NULL;
		}
		}
		$this->dormitory_id->ViewCustomAttributes = "";

		// dormitory_room_number
		$this->dormitory_room_number->ViewValue = $this->dormitory_room_number->CurrentValue;
		$this->dormitory_room_number->ViewCustomAttributes = "";

		// authentication_key
		$this->authentication_key->ViewValue = $this->authentication_key->CurrentValue;
		$this->authentication_key->ViewCustomAttributes = "";

		// image
		$this->image->UploadPath = "..\images\student";
		if (!ew_Empty($this->image->Upload->DbValue)) {
			$this->image->ImageWidth = 0;
			$this->image->ImageHeight = 64;
			$this->image->ImageAlt = $this->image->FldAlt();
			$this->image->ViewValue = $this->image->Upload->DbValue;
		} else {
			$this->image->ViewValue = "";
		}
		$this->image->ViewCustomAttributes = "";

			// student_id
			$this->student_id->LinkCustomAttributes = "";
			$this->student_id->HrefValue = "";
			$this->student_id->TooltipValue = "";

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

			// class_id
			$this->class_id->LinkCustomAttributes = "";
			$this->class_id->HrefValue = "";
			$this->class_id->TooltipValue = "";

			// section_id
			$this->section_id->LinkCustomAttributes = "";
			$this->section_id->HrefValue = "";
			$this->section_id->TooltipValue = "";

			// parent_id
			$this->parent_id->LinkCustomAttributes = "";
			$this->parent_id->HrefValue = "";
			$this->parent_id->TooltipValue = "";

			// roll
			$this->roll->LinkCustomAttributes = "";
			$this->roll->HrefValue = "";
			$this->roll->TooltipValue = "";

			// transport_id
			$this->transport_id->LinkCustomAttributes = "";
			$this->transport_id->HrefValue = "";
			$this->transport_id->TooltipValue = "";

			// dormitory_id
			$this->dormitory_id->LinkCustomAttributes = "";
			$this->dormitory_id->HrefValue = "";
			$this->dormitory_id->TooltipValue = "";

			// dormitory_room_number
			$this->dormitory_room_number->LinkCustomAttributes = "";
			$this->dormitory_room_number->HrefValue = "";
			$this->dormitory_room_number->TooltipValue = "";

			// authentication_key
			$this->authentication_key->LinkCustomAttributes = "";
			$this->authentication_key->HrefValue = "";
			$this->authentication_key->TooltipValue = "";

			// image
			$this->image->LinkCustomAttributes = "";
			$this->image->UploadPath = "..\images\student";
			if (!ew_Empty($this->image->Upload->DbValue)) {
				$this->image->HrefValue = ew_GetFileUploadUrl($this->image, $this->image->Upload->DbValue); // Add prefix/suffix
				$this->image->LinkAttrs["target"] = ""; // Add target
				if ($this->Export <> "") $this->image->HrefValue = ew_FullUrl($this->image->HrefValue, "href");
			} else {
				$this->image->HrefValue = "";
			}
			$this->image->HrefValue2 = $this->image->UploadPath . $this->image->Upload->DbValue;
			$this->image->TooltipValue = "";
			if ($this->image->UseColorbox) {
				if (ew_Empty($this->image->TooltipValue))
					$this->image->LinkAttrs["title"] = $Language->Phrase("ViewImageGallery");
				$this->image->LinkAttrs["data-rel"] = "student_x_image";
				ew_AppendClass($this->image->LinkAttrs["class"], "ewLightbox");
			}
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// student_id
			$this->student_id->EditAttrs["class"] = "form-control";
			$this->student_id->EditCustomAttributes = "";
			$this->student_id->EditValue = $this->student_id->CurrentValue;
			$this->student_id->ViewCustomAttributes = "";

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
			$this->blood_group->EditCustomAttributes = "";
			$this->blood_group->EditValue = $this->blood_group->Options(TRUE);

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

			// class_id
			$this->class_id->EditCustomAttributes = "";
			if (trim(strval($this->class_id->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`class_id`" . ew_SearchString("=", $this->class_id->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `class_id`, `name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `class`";
			$sWhereWrk = "";
			$this->class_id->LookupFilters = array("dx1" => '`name`');
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->class_id, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
				$this->class_id->ViewValue = $this->class_id->DisplayValue($arwrk);
			} else {
				$this->class_id->ViewValue = $Language->Phrase("PleaseSelect");
			}
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->class_id->EditValue = $arwrk;

			// section_id
			$this->section_id->EditCustomAttributes = "";
			if (trim(strval($this->section_id->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`section_id`" . ew_SearchString("=", $this->section_id->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `section_id`, `name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `section`";
			$sWhereWrk = "";
			$this->section_id->LookupFilters = array("dx1" => '`name`');
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->section_id, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
				$this->section_id->ViewValue = $this->section_id->DisplayValue($arwrk);
			} else {
				$this->section_id->ViewValue = $Language->Phrase("PleaseSelect");
			}
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->section_id->EditValue = $arwrk;

			// parent_id
			$this->parent_id->EditCustomAttributes = "";
			if (trim(strval($this->parent_id->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`parent_id`" . ew_SearchString("=", $this->parent_id->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `parent_id`, `name` AS `DispFld`, `phone` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `parent`";
			$sWhereWrk = "";
			$this->parent_id->LookupFilters = array("dx1" => '`name`', "dx2" => '`phone`');
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->parent_id, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
				$arwrk[2] = ew_HtmlEncode($rswrk->fields('Disp2Fld'));
				$this->parent_id->ViewValue = $this->parent_id->DisplayValue($arwrk);
			} else {
				$this->parent_id->ViewValue = $Language->Phrase("PleaseSelect");
			}
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->parent_id->EditValue = $arwrk;

			// roll
			$this->roll->EditAttrs["class"] = "form-control";
			$this->roll->EditCustomAttributes = "";
			$this->roll->EditValue = ew_HtmlEncode($this->roll->CurrentValue);
			$this->roll->PlaceHolder = ew_RemoveHtml($this->roll->FldCaption());

			// transport_id
			$this->transport_id->EditCustomAttributes = "";
			if (trim(strval($this->transport_id->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`transport_id`" . ew_SearchString("=", $this->transport_id->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT DISTINCT `transport_id`, `route_name` AS `DispFld`, `number_of_vehicle` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `transport`";
			$sWhereWrk = "";
			$this->transport_id->LookupFilters = array("dx1" => '`route_name`', "dx2" => '`number_of_vehicle`');
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->transport_id, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
				$arwrk[2] = ew_HtmlEncode($rswrk->fields('Disp2Fld'));
				$this->transport_id->ViewValue = $this->transport_id->DisplayValue($arwrk);
			} else {
				$this->transport_id->ViewValue = $Language->Phrase("PleaseSelect");
			}
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->transport_id->EditValue = $arwrk;

			// dormitory_id
			$this->dormitory_id->EditCustomAttributes = "";
			if (trim(strval($this->dormitory_id->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`dormitory_id`" . ew_SearchString("=", $this->dormitory_id->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT DISTINCT `dormitory_id`, `name` AS `DispFld`, `number_of_room` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `dormitory`";
			$sWhereWrk = "";
			$this->dormitory_id->LookupFilters = array("dx1" => '`name`', "dx2" => '`number_of_room`');
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->dormitory_id, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
				$arwrk[2] = ew_HtmlEncode($rswrk->fields('Disp2Fld'));
				$this->dormitory_id->ViewValue = $this->dormitory_id->DisplayValue($arwrk);
			} else {
				$this->dormitory_id->ViewValue = $Language->Phrase("PleaseSelect");
			}
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->dormitory_id->EditValue = $arwrk;

			// dormitory_room_number
			$this->dormitory_room_number->EditAttrs["class"] = "form-control";
			$this->dormitory_room_number->EditCustomAttributes = "";
			$this->dormitory_room_number->EditValue = ew_HtmlEncode($this->dormitory_room_number->CurrentValue);
			$this->dormitory_room_number->PlaceHolder = ew_RemoveHtml($this->dormitory_room_number->FldCaption());

			// authentication_key
			$this->authentication_key->EditAttrs["class"] = "form-control";
			$this->authentication_key->EditCustomAttributes = "";
			$this->authentication_key->EditValue = ew_HtmlEncode($this->authentication_key->CurrentValue);
			$this->authentication_key->PlaceHolder = ew_RemoveHtml($this->authentication_key->FldCaption());

			// image
			$this->image->EditAttrs["class"] = "form-control";
			$this->image->EditCustomAttributes = "";
			$this->image->UploadPath = "..\images\student";
			if (!ew_Empty($this->image->Upload->DbValue)) {
				$this->image->ImageWidth = 0;
				$this->image->ImageHeight = 64;
				$this->image->ImageAlt = $this->image->FldAlt();
				$this->image->EditValue = $this->image->Upload->DbValue;
			} else {
				$this->image->EditValue = "";
			}
			if (!ew_Empty($this->image->CurrentValue))
					$this->image->Upload->FileName = $this->image->CurrentValue;
			if ($this->CurrentAction == "I" && !$this->EventCancelled) ew_RenderUploadField($this->image);

			// Edit refer script
			// student_id

			$this->student_id->LinkCustomAttributes = "";
			$this->student_id->HrefValue = "";

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

			// class_id
			$this->class_id->LinkCustomAttributes = "";
			$this->class_id->HrefValue = "";

			// section_id
			$this->section_id->LinkCustomAttributes = "";
			$this->section_id->HrefValue = "";

			// parent_id
			$this->parent_id->LinkCustomAttributes = "";
			$this->parent_id->HrefValue = "";

			// roll
			$this->roll->LinkCustomAttributes = "";
			$this->roll->HrefValue = "";

			// transport_id
			$this->transport_id->LinkCustomAttributes = "";
			$this->transport_id->HrefValue = "";

			// dormitory_id
			$this->dormitory_id->LinkCustomAttributes = "";
			$this->dormitory_id->HrefValue = "";

			// dormitory_room_number
			$this->dormitory_room_number->LinkCustomAttributes = "";
			$this->dormitory_room_number->HrefValue = "";

			// authentication_key
			$this->authentication_key->LinkCustomAttributes = "";
			$this->authentication_key->HrefValue = "";

			// image
			$this->image->LinkCustomAttributes = "";
			$this->image->UploadPath = "..\images\student";
			if (!ew_Empty($this->image->Upload->DbValue)) {
				$this->image->HrefValue = ew_GetFileUploadUrl($this->image, $this->image->Upload->DbValue); // Add prefix/suffix
				$this->image->LinkAttrs["target"] = ""; // Add target
				if ($this->Export <> "") $this->image->HrefValue = ew_FullUrl($this->image->HrefValue, "href");
			} else {
				$this->image->HrefValue = "";
			}
			$this->image->HrefValue2 = $this->image->UploadPath . $this->image->Upload->DbValue;
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
		if (!$this->class_id->FldIsDetailKey && !is_null($this->class_id->FormValue) && $this->class_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->class_id->FldCaption(), $this->class_id->ReqErrMsg));
		}
		if (!$this->section_id->FldIsDetailKey && !is_null($this->section_id->FormValue) && $this->section_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->section_id->FldCaption(), $this->section_id->ReqErrMsg));
		}
		if (!$this->parent_id->FldIsDetailKey && !is_null($this->parent_id->FormValue) && $this->parent_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->parent_id->FldCaption(), $this->parent_id->ReqErrMsg));
		}
		if (!$this->roll->FldIsDetailKey && !is_null($this->roll->FormValue) && $this->roll->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->roll->FldCaption(), $this->roll->ReqErrMsg));
		}
		if (!$this->transport_id->FldIsDetailKey && !is_null($this->transport_id->FormValue) && $this->transport_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->transport_id->FldCaption(), $this->transport_id->ReqErrMsg));
		}
		if (!$this->dormitory_id->FldIsDetailKey && !is_null($this->dormitory_id->FormValue) && $this->dormitory_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->dormitory_id->FldCaption(), $this->dormitory_id->ReqErrMsg));
		}
		if (!$this->dormitory_room_number->FldIsDetailKey && !is_null($this->dormitory_room_number->FormValue) && $this->dormitory_room_number->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->dormitory_room_number->FldCaption(), $this->dormitory_room_number->ReqErrMsg));
		}
		if (!$this->authentication_key->FldIsDetailKey && !is_null($this->authentication_key->FormValue) && $this->authentication_key->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->authentication_key->FldCaption(), $this->authentication_key->ReqErrMsg));
		}
		if ($this->image->Upload->FileName == "" && !$this->image->Upload->KeepFile) {
			ew_AddMessage($gsFormError, str_replace("%s", $this->image->FldCaption(), $this->image->ReqErrMsg));
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
			$this->image->OldUploadPath = "..\images\student";
			$this->image->UploadPath = $this->image->OldUploadPath;
			$rsnew = array();

			// name
			$this->name->SetDbValueDef($rsnew, $this->name->CurrentValue, "", $this->name->ReadOnly);

			// birthday
			$this->birthday->SetDbValueDef($rsnew, $this->birthday->CurrentValue, "", $this->birthday->ReadOnly);

			// sex
			$this->sex->SetDbValueDef($rsnew, $this->sex->CurrentValue, "", $this->sex->ReadOnly);

			// religion
			$this->religion->SetDbValueDef($rsnew, $this->religion->CurrentValue, "", $this->religion->ReadOnly);

			// blood_group
			$this->blood_group->SetDbValueDef($rsnew, $this->blood_group->CurrentValue, "", $this->blood_group->ReadOnly);

			// address
			$this->address->SetDbValueDef($rsnew, $this->address->CurrentValue, "", $this->address->ReadOnly);

			// phone
			$this->phone->SetDbValueDef($rsnew, $this->phone->CurrentValue, "", $this->phone->ReadOnly);

			// email
			$this->_email->SetDbValueDef($rsnew, $this->_email->CurrentValue, "", $this->_email->ReadOnly);

			// password
			$this->password->SetDbValueDef($rsnew, $this->password->CurrentValue, "", $this->password->ReadOnly);

			// class_id
			$this->class_id->SetDbValueDef($rsnew, $this->class_id->CurrentValue, "", $this->class_id->ReadOnly);

			// section_id
			$this->section_id->SetDbValueDef($rsnew, $this->section_id->CurrentValue, 0, $this->section_id->ReadOnly);

			// parent_id
			$this->parent_id->SetDbValueDef($rsnew, $this->parent_id->CurrentValue, 0, $this->parent_id->ReadOnly);

			// roll
			$this->roll->SetDbValueDef($rsnew, $this->roll->CurrentValue, "", $this->roll->ReadOnly);

			// transport_id
			$this->transport_id->SetDbValueDef($rsnew, $this->transport_id->CurrentValue, 0, $this->transport_id->ReadOnly);

			// dormitory_id
			$this->dormitory_id->SetDbValueDef($rsnew, $this->dormitory_id->CurrentValue, 0, $this->dormitory_id->ReadOnly);

			// dormitory_room_number
			$this->dormitory_room_number->SetDbValueDef($rsnew, $this->dormitory_room_number->CurrentValue, "", $this->dormitory_room_number->ReadOnly);

			// authentication_key
			$this->authentication_key->SetDbValueDef($rsnew, $this->authentication_key->CurrentValue, "", $this->authentication_key->ReadOnly);

			// image
			if ($this->image->Visible && !$this->image->ReadOnly && !$this->image->Upload->KeepFile) {
				$this->image->Upload->DbValue = $rsold['image']; // Get original value
				if ($this->image->Upload->FileName == "") {
					$rsnew['image'] = NULL;
				} else {
					$rsnew['image'] = $this->image->Upload->FileName;
				}
			}
			if ($this->image->Visible && !$this->image->Upload->KeepFile) {
				$this->image->UploadPath = "..\images\student";
				$OldFiles = ew_Empty($this->image->Upload->DbValue) ? array() : array($this->image->Upload->DbValue);
				if (!ew_Empty($this->image->Upload->FileName)) {
					$NewFiles = array($this->image->Upload->FileName);
					$NewFileCount = count($NewFiles);
					for ($i = 0; $i < $NewFileCount; $i++) {
						$fldvar = ($this->image->Upload->Index < 0) ? $this->image->FldVar : substr($this->image->FldVar, 0, 1) . $this->image->Upload->Index . substr($this->image->FldVar, 1);
						if ($NewFiles[$i] <> "") {
							$file = $NewFiles[$i];
							if (file_exists(ew_UploadTempPath($fldvar, $this->image->TblVar) . $file)) {
								$file1 = ew_UploadFileNameEx($this->image->PhysicalUploadPath(), $file); // Get new file name
								if ($file1 <> $file) { // Rename temp file
									while (file_exists(ew_UploadTempPath($fldvar, $this->image->TblVar) . $file1) || file_exists($this->image->PhysicalUploadPath() . $file1)) // Make sure no file name clash
										$file1 = ew_UniqueFilename($this->image->PhysicalUploadPath(), $file1, TRUE); // Use indexed name
									rename(ew_UploadTempPath($fldvar, $this->image->TblVar) . $file, ew_UploadTempPath($fldvar, $this->image->TblVar) . $file1);
									$NewFiles[$i] = $file1;
								}
							}
						}
					}
					$this->image->Upload->DbValue = empty($OldFiles) ? "" : implode(EW_MULTIPLE_UPLOAD_SEPARATOR, $OldFiles);
					$this->image->Upload->FileName = implode(EW_MULTIPLE_UPLOAD_SEPARATOR, $NewFiles);
					$this->image->SetDbValueDef($rsnew, $this->image->Upload->FileName, "", $this->image->ReadOnly);
				}
			}

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
					if ($this->image->Visible && !$this->image->Upload->KeepFile) {
						$OldFiles = ew_Empty($this->image->Upload->DbValue) ? array() : array($this->image->Upload->DbValue);
						if (!ew_Empty($this->image->Upload->FileName)) {
							$NewFiles = array($this->image->Upload->FileName);
							$NewFiles2 = array($rsnew['image']);
							$NewFileCount = count($NewFiles);
							for ($i = 0; $i < $NewFileCount; $i++) {
								$fldvar = ($this->image->Upload->Index < 0) ? $this->image->FldVar : substr($this->image->FldVar, 0, 1) . $this->image->Upload->Index . substr($this->image->FldVar, 1);
								if ($NewFiles[$i] <> "") {
									$file = ew_UploadTempPath($fldvar, $this->image->TblVar) . $NewFiles[$i];
									if (file_exists($file)) {
										if (@$NewFiles2[$i] <> "") // Use correct file name
											$NewFiles[$i] = $NewFiles2[$i];
										if (!$this->image->Upload->SaveToFile($NewFiles[$i], TRUE, $i)) { // Just replace
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
					$this->setFailureMessage($Language->Phrase("UpdateCancelled"));
				}
				$EditRow = FALSE;
			}
		}

		// Call Row_Updated event
		if ($EditRow)
			$this->Row_Updated($rsold, $rsnew);
		$rs->Close();

		// image
		ew_CleanUploadTempPath($this->image, $this->image->Upload->Index);
		return $EditRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("studentlist.php"), "", $this->TableVar, TRUE);
		$PageId = "edit";
		$Breadcrumb->Add("edit", $PageId, $url);
	}

	// Setup lookup filters of a field
	function SetupLookupFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		case "x_class_id":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `class_id` AS `LinkFld`, `name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `class`";
			$sWhereWrk = "{filter}";
			$fld->LookupFilters = array("dx1" => '`name`');
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`class_id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->class_id, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_section_id":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `section_id` AS `LinkFld`, `name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `section`";
			$sWhereWrk = "{filter}";
			$fld->LookupFilters = array("dx1" => '`name`');
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`section_id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->section_id, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_parent_id":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `parent_id` AS `LinkFld`, `name` AS `DispFld`, `phone` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `parent`";
			$sWhereWrk = "{filter}";
			$fld->LookupFilters = array("dx1" => '`name`', "dx2" => '`phone`');
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`parent_id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->parent_id, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_transport_id":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT DISTINCT `transport_id` AS `LinkFld`, `route_name` AS `DispFld`, `number_of_vehicle` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `transport`";
			$sWhereWrk = "{filter}";
			$fld->LookupFilters = array("dx1" => '`route_name`', "dx2" => '`number_of_vehicle`');
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`transport_id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->transport_id, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_dormitory_id":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT DISTINCT `dormitory_id` AS `LinkFld`, `name` AS `DispFld`, `number_of_room` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `dormitory`";
			$sWhereWrk = "{filter}";
			$fld->LookupFilters = array("dx1" => '`name`', "dx2" => '`number_of_room`');
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`dormitory_id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->dormitory_id, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
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
if (!isset($student_edit)) $student_edit = new cstudent_edit();

// Page init
$student_edit->Page_Init();

// Page main
$student_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$student_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = fstudentedit = new ew_Form("fstudentedit", "edit");

// Validate form
fstudentedit.Validate = function() {
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
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $student->name->FldCaption(), $student->name->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_birthday");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $student->birthday->FldCaption(), $student->birthday->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_sex");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $student->sex->FldCaption(), $student->sex->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_religion");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $student->religion->FldCaption(), $student->religion->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_blood_group");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $student->blood_group->FldCaption(), $student->blood_group->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_address");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $student->address->FldCaption(), $student->address->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_phone");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $student->phone->FldCaption(), $student->phone->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "__email");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $student->_email->FldCaption(), $student->_email->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_password");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $student->password->FldCaption(), $student->password->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_class_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $student->class_id->FldCaption(), $student->class_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_section_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $student->section_id->FldCaption(), $student->section_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_parent_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $student->parent_id->FldCaption(), $student->parent_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_roll");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $student->roll->FldCaption(), $student->roll->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_transport_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $student->transport_id->FldCaption(), $student->transport_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_dormitory_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $student->dormitory_id->FldCaption(), $student->dormitory_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_dormitory_room_number");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $student->dormitory_room_number->FldCaption(), $student->dormitory_room_number->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_authentication_key");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $student->authentication_key->FldCaption(), $student->authentication_key->ReqErrMsg)) ?>");
			felm = this.GetElements("x" + infix + "_image");
			elm = this.GetElements("fn_x" + infix + "_image");
			if (felm && elm && !ew_HasValue(elm))
				return this.OnError(felm, "<?php echo ew_JsEncode2(str_replace("%s", $student->image->FldCaption(), $student->image->ReqErrMsg)) ?>");

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
fstudentedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fstudentedit.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
fstudentedit.Lists["x_sex"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fstudentedit.Lists["x_sex"].Options = <?php echo json_encode($student_edit->sex->Options()) ?>;
fstudentedit.Lists["x_blood_group"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fstudentedit.Lists["x_blood_group"].Options = <?php echo json_encode($student_edit->blood_group->Options()) ?>;
fstudentedit.Lists["x_class_id"] = {"LinkField":"x_class_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_name","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"class"};
fstudentedit.Lists["x_class_id"].Data = "<?php echo $student_edit->class_id->LookupFilterQuery(FALSE, "edit") ?>";
fstudentedit.Lists["x_section_id"] = {"LinkField":"x_section_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_name","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"section"};
fstudentedit.Lists["x_section_id"].Data = "<?php echo $student_edit->section_id->LookupFilterQuery(FALSE, "edit") ?>";
fstudentedit.Lists["x_parent_id"] = {"LinkField":"x_parent_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_name","x_phone","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"parent"};
fstudentedit.Lists["x_parent_id"].Data = "<?php echo $student_edit->parent_id->LookupFilterQuery(FALSE, "edit") ?>";
fstudentedit.Lists["x_transport_id"] = {"LinkField":"x_transport_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_route_name","x_number_of_vehicle","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"transport"};
fstudentedit.Lists["x_transport_id"].Data = "<?php echo $student_edit->transport_id->LookupFilterQuery(FALSE, "edit") ?>";
fstudentedit.Lists["x_dormitory_id"] = {"LinkField":"x_dormitory_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_name","x_number_of_room","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"dormitory"};
fstudentedit.Lists["x_dormitory_id"].Data = "<?php echo $student_edit->dormitory_id->LookupFilterQuery(FALSE, "edit") ?>";

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $student_edit->ShowPageHeader(); ?>
<?php
$student_edit->ShowMessage();
?>
<form name="fstudentedit" id="fstudentedit" class="<?php echo $student_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($student_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $student_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="student">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<input type="hidden" name="modal" value="<?php echo intval($student_edit->IsModal) ?>">
<div class="ewEditDiv"><!-- page* -->
<?php if ($student->student_id->Visible) { // student_id ?>
	<div id="r_student_id" class="form-group">
		<label id="elh_student_student_id" class="<?php echo $student_edit->LeftColumnClass ?>"><?php echo $student->student_id->FldCaption() ?></label>
		<div class="<?php echo $student_edit->RightColumnClass ?>"><div<?php echo $student->student_id->CellAttributes() ?>>
<span id="el_student_student_id">
<span<?php echo $student->student_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $student->student_id->EditValue ?></p></span>
</span>
<input type="hidden" data-table="student" data-field="x_student_id" name="x_student_id" id="x_student_id" value="<?php echo ew_HtmlEncode($student->student_id->CurrentValue) ?>">
<?php echo $student->student_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($student->name->Visible) { // name ?>
	<div id="r_name" class="form-group">
		<label id="elh_student_name" for="x_name" class="<?php echo $student_edit->LeftColumnClass ?>"><?php echo $student->name->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $student_edit->RightColumnClass ?>"><div<?php echo $student->name->CellAttributes() ?>>
<span id="el_student_name">
<input type="text" data-table="student" data-field="x_name" name="x_name" id="x_name" placeholder="<?php echo ew_HtmlEncode($student->name->getPlaceHolder()) ?>" value="<?php echo $student->name->EditValue ?>"<?php echo $student->name->EditAttributes() ?>>
</span>
<?php echo $student->name->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($student->birthday->Visible) { // birthday ?>
	<div id="r_birthday" class="form-group">
		<label id="elh_student_birthday" for="x_birthday" class="<?php echo $student_edit->LeftColumnClass ?>"><?php echo $student->birthday->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $student_edit->RightColumnClass ?>"><div<?php echo $student->birthday->CellAttributes() ?>>
<span id="el_student_birthday">
<input type="text" data-table="student" data-field="x_birthday" name="x_birthday" id="x_birthday" placeholder="<?php echo ew_HtmlEncode($student->birthday->getPlaceHolder()) ?>" value="<?php echo $student->birthday->EditValue ?>"<?php echo $student->birthday->EditAttributes() ?>>
</span>
<?php echo $student->birthday->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($student->sex->Visible) { // sex ?>
	<div id="r_sex" class="form-group">
		<label id="elh_student_sex" for="x_sex" class="<?php echo $student_edit->LeftColumnClass ?>"><?php echo $student->sex->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $student_edit->RightColumnClass ?>"><div<?php echo $student->sex->CellAttributes() ?>>
<span id="el_student_sex">
<select data-table="student" data-field="x_sex" data-value-separator="<?php echo $student->sex->DisplayValueSeparatorAttribute() ?>" id="x_sex" name="x_sex"<?php echo $student->sex->EditAttributes() ?>>
<?php echo $student->sex->SelectOptionListHtml("x_sex") ?>
</select>
</span>
<?php echo $student->sex->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($student->religion->Visible) { // religion ?>
	<div id="r_religion" class="form-group">
		<label id="elh_student_religion" for="x_religion" class="<?php echo $student_edit->LeftColumnClass ?>"><?php echo $student->religion->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $student_edit->RightColumnClass ?>"><div<?php echo $student->religion->CellAttributes() ?>>
<span id="el_student_religion">
<input type="text" data-table="student" data-field="x_religion" name="x_religion" id="x_religion" placeholder="<?php echo ew_HtmlEncode($student->religion->getPlaceHolder()) ?>" value="<?php echo $student->religion->EditValue ?>"<?php echo $student->religion->EditAttributes() ?>>
</span>
<?php echo $student->religion->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($student->blood_group->Visible) { // blood_group ?>
	<div id="r_blood_group" class="form-group">
		<label id="elh_student_blood_group" for="x_blood_group" class="<?php echo $student_edit->LeftColumnClass ?>"><?php echo $student->blood_group->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $student_edit->RightColumnClass ?>"><div<?php echo $student->blood_group->CellAttributes() ?>>
<span id="el_student_blood_group">
<div class="ewDropdownList has-feedback">
	<span onclick="" class="form-control dropdown-toggle" aria-expanded="false"<?php if ($student->blood_group->ReadOnly) { ?> readonly<?php } else { ?>data-toggle="dropdown"<?php } ?>>
		<?php echo $student->blood_group->ViewValue ?>
	</span>
	<?php if (!$student->blood_group->ReadOnly) { ?>
	<span class="glyphicon glyphicon-remove form-control-feedback ewDropdownListClear"></span>
	<span class="form-control-feedback"><span class="caret"></span></span>
	<?php } ?>
	<div id="dsl_x_blood_group" data-repeatcolumn="1" class="dropdown-menu">
		<div class="ewItems" style="position: relative; overflow-x: hidden;">
<?php echo $student->blood_group->RadioButtonListHtml(TRUE, "x_blood_group") ?>
		</div>
	</div>
	<div id="tp_x_blood_group" class="ewTemplate"><input type="radio" data-table="student" data-field="x_blood_group" data-value-separator="<?php echo $student->blood_group->DisplayValueSeparatorAttribute() ?>" name="x_blood_group" id="x_blood_group" value="{value}"<?php echo $student->blood_group->EditAttributes() ?>></div>
</div>
</span>
<?php echo $student->blood_group->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($student->address->Visible) { // address ?>
	<div id="r_address" class="form-group">
		<label id="elh_student_address" for="x_address" class="<?php echo $student_edit->LeftColumnClass ?>"><?php echo $student->address->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $student_edit->RightColumnClass ?>"><div<?php echo $student->address->CellAttributes() ?>>
<span id="el_student_address">
<input type="text" data-table="student" data-field="x_address" name="x_address" id="x_address" placeholder="<?php echo ew_HtmlEncode($student->address->getPlaceHolder()) ?>" value="<?php echo $student->address->EditValue ?>"<?php echo $student->address->EditAttributes() ?>>
</span>
<?php echo $student->address->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($student->phone->Visible) { // phone ?>
	<div id="r_phone" class="form-group">
		<label id="elh_student_phone" for="x_phone" class="<?php echo $student_edit->LeftColumnClass ?>"><?php echo $student->phone->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $student_edit->RightColumnClass ?>"><div<?php echo $student->phone->CellAttributes() ?>>
<span id="el_student_phone">
<input type="text" data-table="student" data-field="x_phone" name="x_phone" id="x_phone" placeholder="<?php echo ew_HtmlEncode($student->phone->getPlaceHolder()) ?>" value="<?php echo $student->phone->EditValue ?>"<?php echo $student->phone->EditAttributes() ?>>
</span>
<?php echo $student->phone->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($student->_email->Visible) { // email ?>
	<div id="r__email" class="form-group">
		<label id="elh_student__email" for="x__email" class="<?php echo $student_edit->LeftColumnClass ?>"><?php echo $student->_email->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $student_edit->RightColumnClass ?>"><div<?php echo $student->_email->CellAttributes() ?>>
<span id="el_student__email">
<input type="text" data-table="student" data-field="x__email" name="x__email" id="x__email" placeholder="<?php echo ew_HtmlEncode($student->_email->getPlaceHolder()) ?>" value="<?php echo $student->_email->EditValue ?>"<?php echo $student->_email->EditAttributes() ?>>
</span>
<?php echo $student->_email->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($student->password->Visible) { // password ?>
	<div id="r_password" class="form-group">
		<label id="elh_student_password" for="x_password" class="<?php echo $student_edit->LeftColumnClass ?>"><?php echo $student->password->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $student_edit->RightColumnClass ?>"><div<?php echo $student->password->CellAttributes() ?>>
<span id="el_student_password">
<input type="password" data-field="x_password" name="x_password" id="x_password" value="<?php echo $student->password->EditValue ?>" placeholder="<?php echo ew_HtmlEncode($student->password->getPlaceHolder()) ?>"<?php echo $student->password->EditAttributes() ?>>
</span>
<?php echo $student->password->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($student->class_id->Visible) { // class_id ?>
	<div id="r_class_id" class="form-group">
		<label id="elh_student_class_id" for="x_class_id" class="<?php echo $student_edit->LeftColumnClass ?>"><?php echo $student->class_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $student_edit->RightColumnClass ?>"><div<?php echo $student->class_id->CellAttributes() ?>>
<span id="el_student_class_id">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x_class_id"><?php echo (strval($student->class_id->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $student->class_id->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($student->class_id->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_class_id',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"<?php echo (($student->class_id->ReadOnly || $student->class_id->Disabled) ? " disabled" : "")?>><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="student" data-field="x_class_id" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $student->class_id->DisplayValueSeparatorAttribute() ?>" name="x_class_id" id="x_class_id" value="<?php echo $student->class_id->CurrentValue ?>"<?php echo $student->class_id->EditAttributes() ?>>
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $student->class_id->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x_class_id',url:'classaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x_class_id"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $student->class_id->FldCaption() ?></span></button>
</span>
<?php echo $student->class_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($student->section_id->Visible) { // section_id ?>
	<div id="r_section_id" class="form-group">
		<label id="elh_student_section_id" for="x_section_id" class="<?php echo $student_edit->LeftColumnClass ?>"><?php echo $student->section_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $student_edit->RightColumnClass ?>"><div<?php echo $student->section_id->CellAttributes() ?>>
<span id="el_student_section_id">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x_section_id"><?php echo (strval($student->section_id->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $student->section_id->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($student->section_id->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_section_id',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"<?php echo (($student->section_id->ReadOnly || $student->section_id->Disabled) ? " disabled" : "")?>><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="student" data-field="x_section_id" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $student->section_id->DisplayValueSeparatorAttribute() ?>" name="x_section_id" id="x_section_id" value="<?php echo $student->section_id->CurrentValue ?>"<?php echo $student->section_id->EditAttributes() ?>>
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $student->section_id->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x_section_id',url:'sectionaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x_section_id"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $student->section_id->FldCaption() ?></span></button>
</span>
<?php echo $student->section_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($student->parent_id->Visible) { // parent_id ?>
	<div id="r_parent_id" class="form-group">
		<label id="elh_student_parent_id" for="x_parent_id" class="<?php echo $student_edit->LeftColumnClass ?>"><?php echo $student->parent_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $student_edit->RightColumnClass ?>"><div<?php echo $student->parent_id->CellAttributes() ?>>
<span id="el_student_parent_id">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x_parent_id"><?php echo (strval($student->parent_id->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $student->parent_id->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($student->parent_id->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_parent_id',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"<?php echo (($student->parent_id->ReadOnly || $student->parent_id->Disabled) ? " disabled" : "")?>><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="student" data-field="x_parent_id" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $student->parent_id->DisplayValueSeparatorAttribute() ?>" name="x_parent_id" id="x_parent_id" value="<?php echo $student->parent_id->CurrentValue ?>"<?php echo $student->parent_id->EditAttributes() ?>>
</span>
<?php echo $student->parent_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($student->roll->Visible) { // roll ?>
	<div id="r_roll" class="form-group">
		<label id="elh_student_roll" for="x_roll" class="<?php echo $student_edit->LeftColumnClass ?>"><?php echo $student->roll->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $student_edit->RightColumnClass ?>"><div<?php echo $student->roll->CellAttributes() ?>>
<span id="el_student_roll">
<input type="text" data-table="student" data-field="x_roll" name="x_roll" id="x_roll" placeholder="<?php echo ew_HtmlEncode($student->roll->getPlaceHolder()) ?>" value="<?php echo $student->roll->EditValue ?>"<?php echo $student->roll->EditAttributes() ?>>
</span>
<?php echo $student->roll->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($student->transport_id->Visible) { // transport_id ?>
	<div id="r_transport_id" class="form-group">
		<label id="elh_student_transport_id" for="x_transport_id" class="<?php echo $student_edit->LeftColumnClass ?>"><?php echo $student->transport_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $student_edit->RightColumnClass ?>"><div<?php echo $student->transport_id->CellAttributes() ?>>
<span id="el_student_transport_id">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x_transport_id"><?php echo (strval($student->transport_id->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $student->transport_id->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($student->transport_id->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_transport_id',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"<?php echo (($student->transport_id->ReadOnly || $student->transport_id->Disabled) ? " disabled" : "")?>><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="student" data-field="x_transport_id" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $student->transport_id->DisplayValueSeparatorAttribute() ?>" name="x_transport_id" id="x_transport_id" value="<?php echo $student->transport_id->CurrentValue ?>"<?php echo $student->transport_id->EditAttributes() ?>>
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $student->transport_id->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x_transport_id',url:'transportaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x_transport_id"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $student->transport_id->FldCaption() ?></span></button>
</span>
<?php echo $student->transport_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($student->dormitory_id->Visible) { // dormitory_id ?>
	<div id="r_dormitory_id" class="form-group">
		<label id="elh_student_dormitory_id" for="x_dormitory_id" class="<?php echo $student_edit->LeftColumnClass ?>"><?php echo $student->dormitory_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $student_edit->RightColumnClass ?>"><div<?php echo $student->dormitory_id->CellAttributes() ?>>
<span id="el_student_dormitory_id">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x_dormitory_id"><?php echo (strval($student->dormitory_id->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $student->dormitory_id->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($student->dormitory_id->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_dormitory_id',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"<?php echo (($student->dormitory_id->ReadOnly || $student->dormitory_id->Disabled) ? " disabled" : "")?>><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="student" data-field="x_dormitory_id" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $student->dormitory_id->DisplayValueSeparatorAttribute() ?>" name="x_dormitory_id" id="x_dormitory_id" value="<?php echo $student->dormitory_id->CurrentValue ?>"<?php echo $student->dormitory_id->EditAttributes() ?>>
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $student->dormitory_id->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x_dormitory_id',url:'dormitoryaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x_dormitory_id"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $student->dormitory_id->FldCaption() ?></span></button>
</span>
<?php echo $student->dormitory_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($student->dormitory_room_number->Visible) { // dormitory_room_number ?>
	<div id="r_dormitory_room_number" class="form-group">
		<label id="elh_student_dormitory_room_number" for="x_dormitory_room_number" class="<?php echo $student_edit->LeftColumnClass ?>"><?php echo $student->dormitory_room_number->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $student_edit->RightColumnClass ?>"><div<?php echo $student->dormitory_room_number->CellAttributes() ?>>
<span id="el_student_dormitory_room_number">
<input type="text" data-table="student" data-field="x_dormitory_room_number" name="x_dormitory_room_number" id="x_dormitory_room_number" placeholder="<?php echo ew_HtmlEncode($student->dormitory_room_number->getPlaceHolder()) ?>" value="<?php echo $student->dormitory_room_number->EditValue ?>"<?php echo $student->dormitory_room_number->EditAttributes() ?>>
</span>
<?php echo $student->dormitory_room_number->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($student->authentication_key->Visible) { // authentication_key ?>
	<div id="r_authentication_key" class="form-group">
		<label id="elh_student_authentication_key" for="x_authentication_key" class="<?php echo $student_edit->LeftColumnClass ?>"><?php echo $student->authentication_key->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $student_edit->RightColumnClass ?>"><div<?php echo $student->authentication_key->CellAttributes() ?>>
<span id="el_student_authentication_key">
<input type="text" data-table="student" data-field="x_authentication_key" name="x_authentication_key" id="x_authentication_key" placeholder="<?php echo ew_HtmlEncode($student->authentication_key->getPlaceHolder()) ?>" value="<?php echo $student->authentication_key->EditValue ?>"<?php echo $student->authentication_key->EditAttributes() ?>>
</span>
<?php echo $student->authentication_key->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($student->image->Visible) { // image ?>
	<div id="r_image" class="form-group">
		<label id="elh_student_image" class="<?php echo $student_edit->LeftColumnClass ?>"><?php echo $student->image->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $student_edit->RightColumnClass ?>"><div<?php echo $student->image->CellAttributes() ?>>
<span id="el_student_image">
<div id="fd_x_image">
<span title="<?php echo $student->image->FldTitle() ? $student->image->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($student->image->ReadOnly || $student->image->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="student" data-field="x_image" name="x_image" id="x_image"<?php echo $student->image->EditAttributes() ?>>
</span>
<input type="hidden" name="fn_x_image" id= "fn_x_image" value="<?php echo $student->image->Upload->FileName ?>">
<?php if (@$_POST["fa_x_image"] == "0") { ?>
<input type="hidden" name="fa_x_image" id= "fa_x_image" value="0">
<?php } else { ?>
<input type="hidden" name="fa_x_image" id= "fa_x_image" value="1">
<?php } ?>
<input type="hidden" name="fs_x_image" id= "fs_x_image" value="250">
<input type="hidden" name="fx_x_image" id= "fx_x_image" value="<?php echo $student->image->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_image" id= "fm_x_image" value="<?php echo $student->image->UploadMaxFileSize ?>">
</div>
<table id="ft_x_image" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php echo $student->image->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div><!-- /page* -->
<?php if (!$student_edit->IsModal) { ?>
<div class="form-group"><!-- buttons .form-group -->
	<div class="<?php echo $student_edit->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $student_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div><!-- /buttons offset -->
</div><!-- /buttons .form-group -->
<?php } ?>
</form>
<script type="text/javascript">
fstudentedit.Init();
</script>
<?php
$student_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$student_edit->Page_Terminate();
?>
