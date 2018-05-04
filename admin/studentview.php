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

$student_view = NULL; // Initialize page object first

class cstudent_view extends cstudent {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = '{1F40EE59-17A4-45D3-96B6-AA2082B3D64A}';

	// Table name
	var $TableName = 'student';

	// Page object name
	var $PageObjName = 'student_view';

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

	// Page URLs
	var $AddUrl;
	var $EditUrl;
	var $CopyUrl;
	var $DeleteUrl;
	var $ViewUrl;
	var $ListUrl;

	// Export URLs
	var $ExportPrintUrl;
	var $ExportHtmlUrl;
	var $ExportExcelUrl;
	var $ExportWordUrl;
	var $ExportXmlUrl;
	var $ExportCsvUrl;
	var $ExportPdfUrl;

	// Custom export
	var $ExportExcelCustom = FALSE;
	var $ExportWordCustom = FALSE;
	var $ExportPdfCustom = FALSE;
	var $ExportEmailCustom = FALSE;

	// Update URLs
	var $InlineAddUrl;
	var $InlineCopyUrl;
	var $InlineEditUrl;
	var $GridAddUrl;
	var $GridEditUrl;
	var $MultiDeleteUrl;
	var $MultiUpdateUrl;

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
		$KeyUrl = "";
		if (@$_GET["student_id"] <> "") {
			$this->RecKey["student_id"] = $_GET["student_id"];
			$KeyUrl .= "&amp;student_id=" . urlencode($this->RecKey["student_id"]);
		}
		$this->ExportPrintUrl = $this->PageUrl() . "export=print" . $KeyUrl;
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html" . $KeyUrl;
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel" . $KeyUrl;
		$this->ExportWordUrl = $this->PageUrl() . "export=word" . $KeyUrl;
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml" . $KeyUrl;
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv" . $KeyUrl;
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf" . $KeyUrl;

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'view', TRUE);

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

		// Export options
		$this->ExportOptions = new cListOptions();
		$this->ExportOptions->Tag = "div";
		$this->ExportOptions->TagClassName = "ewExportOption";

		// Other options
		$this->OtherOptions['action'] = new cListOptions();
		$this->OtherOptions['action']->Tag = "div";
		$this->OtherOptions['action']->TagClassName = "ewActionOption";
		$this->OtherOptions['detail'] = new cListOptions();
		$this->OtherOptions['detail']->Tag = "div";
		$this->OtherOptions['detail']->TagClassName = "ewDetailOption";
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
		if (!$Security->CanView()) {
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
	var $ExportOptions; // Export options
	var $OtherOptions = array(); // Other options
	var $DisplayRecs = 1;
	var $DbMasterFilter;
	var $DbDetailFilter;
	var $StartRec;
	var $StopRec;
	var $TotalRecs = 0;
	var $RecRange = 10;
	var $RecCnt;
	var $RecKey = array();
	var $IsModal = FALSE;
	var $Recordset;

	//
	// Page main
	//
	function Page_Main() {
		global $Language, $gbSkipHeaderFooter, $EW_EXPORT;

		// Check modal
		if ($this->IsModal)
			$gbSkipHeaderFooter = TRUE;
		$sReturnUrl = "";
		$bMatchRecord = FALSE;
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET["student_id"] <> "") {
				$this->student_id->setQueryStringValue($_GET["student_id"]);
				$this->RecKey["student_id"] = $this->student_id->QueryStringValue;
			} elseif (@$_POST["student_id"] <> "") {
				$this->student_id->setFormValue($_POST["student_id"]);
				$this->RecKey["student_id"] = $this->student_id->FormValue;
			} else {
				$sReturnUrl = "studentlist.php"; // Return to list
			}

			// Get action
			$this->CurrentAction = "I"; // Display form
			switch ($this->CurrentAction) {
				case "I": // Get a record to display
					if (!$this->LoadRow()) { // Load record based on key
						if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
							$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
						$sReturnUrl = "studentlist.php"; // No matching record, return to list
					}
			}
		} else {
			$sReturnUrl = "studentlist.php"; // Not page request, return to list
		}
		if ($sReturnUrl <> "")
			$this->Page_Terminate($sReturnUrl);

		// Set up Breadcrumb
		if ($this->Export == "")
			$this->SetupBreadcrumb();

		// Render row
		$this->RowType = EW_ROWTYPE_VIEW;
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Set up other options
	function SetupOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
		$option = &$options["action"];

		// Add
		$item = &$option->Add("add");
		$addcaption = ew_HtmlTitle($Language->Phrase("ViewPageAddLink"));
		if ($this->IsModal) // Modal
			$item->Body = "<a class=\"ewAction ewAdd\" title=\"" . $addcaption . "\" data-caption=\"" . $addcaption . "\" href=\"javascript:void(0);\" onclick=\"ew_ModalDialogShow({lnk:this,url:'" . ew_HtmlEncode($this->AddUrl) . "'});\">" . $Language->Phrase("ViewPageAddLink") . "</a>";
		else
			$item->Body = "<a class=\"ewAction ewAdd\" title=\"" . $addcaption . "\" data-caption=\"" . $addcaption . "\" href=\"" . ew_HtmlEncode($this->AddUrl) . "\">" . $Language->Phrase("ViewPageAddLink") . "</a>";
		$item->Visible = ($this->AddUrl <> "" && $Security->IsLoggedIn());

		// Edit
		$item = &$option->Add("edit");
		$editcaption = ew_HtmlTitle($Language->Phrase("ViewPageEditLink"));
		if ($this->IsModal) // Modal
			$item->Body = "<a class=\"ewAction ewEdit\" title=\"" . $editcaption . "\" data-caption=\"" . $editcaption . "\" href=\"javascript:void(0);\" onclick=\"ew_ModalDialogShow({lnk:this,url:'" . ew_HtmlEncode($this->EditUrl) . "'});\">" . $Language->Phrase("ViewPageEditLink") . "</a>";
		else
			$item->Body = "<a class=\"ewAction ewEdit\" title=\"" . $editcaption . "\" data-caption=\"" . $editcaption . "\" href=\"" . ew_HtmlEncode($this->EditUrl) . "\">" . $Language->Phrase("ViewPageEditLink") . "</a>";
		$item->Visible = ($this->EditUrl <> "" && $Security->IsLoggedIn());

		// Copy
		$item = &$option->Add("copy");
		$copycaption = ew_HtmlTitle($Language->Phrase("ViewPageCopyLink"));
		if ($this->IsModal) // Modal
			$item->Body = "<a class=\"ewAction ewCopy\" title=\"" . $copycaption . "\" data-caption=\"" . $copycaption . "\" href=\"javascript:void(0);\" onclick=\"ew_ModalDialogShow({lnk:this,btn:'AddBtn',url:'" . ew_HtmlEncode($this->CopyUrl) . "'});\">" . $Language->Phrase("ViewPageCopyLink") . "</a>";
		else
			$item->Body = "<a class=\"ewAction ewCopy\" title=\"" . $copycaption . "\" data-caption=\"" . $copycaption . "\" href=\"" . ew_HtmlEncode($this->CopyUrl) . "\">" . $Language->Phrase("ViewPageCopyLink") . "</a>";
		$item->Visible = ($this->CopyUrl <> "" && $Security->IsLoggedIn());

		// Delete
		$item = &$option->Add("delete");
		if ($this->IsModal) // Handle as inline delete
			$item->Body = "<a onclick=\"return ew_ConfirmDelete(this);\" class=\"ewAction ewDelete\" title=\"" . ew_HtmlTitle($Language->Phrase("ViewPageDeleteLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("ViewPageDeleteLink")) . "\" href=\"" . ew_HtmlEncode(ew_UrlAddQuery($this->DeleteUrl, "a_delete=1")) . "\">" . $Language->Phrase("ViewPageDeleteLink") . "</a>";
		else
			$item->Body = "<a class=\"ewAction ewDelete\" title=\"" . ew_HtmlTitle($Language->Phrase("ViewPageDeleteLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("ViewPageDeleteLink")) . "\" href=\"" . ew_HtmlEncode($this->DeleteUrl) . "\">" . $Language->Phrase("ViewPageDeleteLink") . "</a>";
		$item->Visible = ($this->DeleteUrl <> "" && $Security->IsLoggedIn());

		// Set up action default
		$option = &$options["action"];
		$option->DropDownButtonPhrase = $Language->Phrase("ButtonActions");
		$option->UseImageAndText = TRUE;
		$option->UseDropDownButton = FALSE;
		$option->UseButtonGroup = TRUE;
		$item = &$option->Add($option->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;
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

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		$this->AddUrl = $this->GetAddUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();
		$this->ListUrl = $this->GetListUrl();
		$this->SetupOtherOptions();

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
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("studentlist.php"), "", $this->TableVar, TRUE);
		$PageId = "view";
		$Breadcrumb->Add("view", $PageId, $url);
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

	// Page Exporting event
	// $this->ExportDoc = export document object
	function Page_Exporting() {

		//$this->ExportDoc->Text = "my header"; // Export header
		//return FALSE; // Return FALSE to skip default export and use Row_Export event

		return TRUE; // Return TRUE to use default export and skip Row_Export event
	}

	// Row Export event
	// $this->ExportDoc = export document object
	function Row_Export($rs) {

		//$this->ExportDoc->Text .= "my content"; // Build HTML with field value: $rs["MyField"] or $this->MyField->ViewValue
	}

	// Page Exported event
	// $this->ExportDoc = export document object
	function Page_Exported() {

		//$this->ExportDoc->Text .= "my footer"; // Export footer
		//echo $this->ExportDoc->Text;

	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($student_view)) $student_view = new cstudent_view();

// Page init
$student_view->Page_Init();

// Page main
$student_view->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$student_view->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "view";
var CurrentForm = fstudentview = new ew_Form("fstudentview", "view");

// Form_CustomValidate event
fstudentview.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fstudentview.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
fstudentview.Lists["x_sex"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fstudentview.Lists["x_sex"].Options = <?php echo json_encode($student_view->sex->Options()) ?>;
fstudentview.Lists["x_blood_group"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fstudentview.Lists["x_blood_group"].Options = <?php echo json_encode($student_view->blood_group->Options()) ?>;
fstudentview.Lists["x_class_id"] = {"LinkField":"x_class_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_name","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"class"};
fstudentview.Lists["x_class_id"].Data = "<?php echo $student_view->class_id->LookupFilterQuery(FALSE, "view") ?>";
fstudentview.Lists["x_section_id"] = {"LinkField":"x_section_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_name","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"section"};
fstudentview.Lists["x_section_id"].Data = "<?php echo $student_view->section_id->LookupFilterQuery(FALSE, "view") ?>";
fstudentview.Lists["x_parent_id"] = {"LinkField":"x_parent_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_name","x_phone","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"parent"};
fstudentview.Lists["x_parent_id"].Data = "<?php echo $student_view->parent_id->LookupFilterQuery(FALSE, "view") ?>";
fstudentview.Lists["x_transport_id"] = {"LinkField":"x_transport_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_route_name","x_number_of_vehicle","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"transport"};
fstudentview.Lists["x_transport_id"].Data = "<?php echo $student_view->transport_id->LookupFilterQuery(FALSE, "view") ?>";
fstudentview.Lists["x_dormitory_id"] = {"LinkField":"x_dormitory_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_name","x_number_of_room","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"dormitory"};
fstudentview.Lists["x_dormitory_id"].Data = "<?php echo $student_view->dormitory_id->LookupFilterQuery(FALSE, "view") ?>";

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php $student_view->ExportOptions->Render("body") ?>
<?php
	foreach ($student_view->OtherOptions as &$option)
		$option->Render("body");
?>
<div class="clearfix"></div>
</div>
<?php $student_view->ShowPageHeader(); ?>
<?php
$student_view->ShowMessage();
?>
<form name="fstudentview" id="fstudentview" class="form-inline ewForm ewViewForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($student_view->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $student_view->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="student">
<input type="hidden" name="modal" value="<?php echo intval($student_view->IsModal) ?>">
<table class="table table-striped table-bordered table-hover table-condensed ewViewTable">
<?php if ($student->student_id->Visible) { // student_id ?>
	<tr id="r_student_id">
		<td class="col-sm-2"><span id="elh_student_student_id"><?php echo $student->student_id->FldCaption() ?></span></td>
		<td data-name="student_id"<?php echo $student->student_id->CellAttributes() ?>>
<span id="el_student_student_id">
<span<?php echo $student->student_id->ViewAttributes() ?>>
<?php echo $student->student_id->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($student->name->Visible) { // name ?>
	<tr id="r_name">
		<td class="col-sm-2"><span id="elh_student_name"><?php echo $student->name->FldCaption() ?></span></td>
		<td data-name="name"<?php echo $student->name->CellAttributes() ?>>
<span id="el_student_name">
<span<?php echo $student->name->ViewAttributes() ?>>
<?php echo $student->name->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($student->birthday->Visible) { // birthday ?>
	<tr id="r_birthday">
		<td class="col-sm-2"><span id="elh_student_birthday"><?php echo $student->birthday->FldCaption() ?></span></td>
		<td data-name="birthday"<?php echo $student->birthday->CellAttributes() ?>>
<span id="el_student_birthday">
<span<?php echo $student->birthday->ViewAttributes() ?>>
<?php echo $student->birthday->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($student->sex->Visible) { // sex ?>
	<tr id="r_sex">
		<td class="col-sm-2"><span id="elh_student_sex"><?php echo $student->sex->FldCaption() ?></span></td>
		<td data-name="sex"<?php echo $student->sex->CellAttributes() ?>>
<span id="el_student_sex">
<span<?php echo $student->sex->ViewAttributes() ?>>
<?php echo $student->sex->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($student->religion->Visible) { // religion ?>
	<tr id="r_religion">
		<td class="col-sm-2"><span id="elh_student_religion"><?php echo $student->religion->FldCaption() ?></span></td>
		<td data-name="religion"<?php echo $student->religion->CellAttributes() ?>>
<span id="el_student_religion">
<span<?php echo $student->religion->ViewAttributes() ?>>
<?php echo $student->religion->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($student->blood_group->Visible) { // blood_group ?>
	<tr id="r_blood_group">
		<td class="col-sm-2"><span id="elh_student_blood_group"><?php echo $student->blood_group->FldCaption() ?></span></td>
		<td data-name="blood_group"<?php echo $student->blood_group->CellAttributes() ?>>
<span id="el_student_blood_group">
<span<?php echo $student->blood_group->ViewAttributes() ?>>
<?php echo $student->blood_group->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($student->address->Visible) { // address ?>
	<tr id="r_address">
		<td class="col-sm-2"><span id="elh_student_address"><?php echo $student->address->FldCaption() ?></span></td>
		<td data-name="address"<?php echo $student->address->CellAttributes() ?>>
<span id="el_student_address">
<span<?php echo $student->address->ViewAttributes() ?>>
<?php echo $student->address->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($student->phone->Visible) { // phone ?>
	<tr id="r_phone">
		<td class="col-sm-2"><span id="elh_student_phone"><?php echo $student->phone->FldCaption() ?></span></td>
		<td data-name="phone"<?php echo $student->phone->CellAttributes() ?>>
<span id="el_student_phone">
<span<?php echo $student->phone->ViewAttributes() ?>>
<?php echo $student->phone->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($student->_email->Visible) { // email ?>
	<tr id="r__email">
		<td class="col-sm-2"><span id="elh_student__email"><?php echo $student->_email->FldCaption() ?></span></td>
		<td data-name="_email"<?php echo $student->_email->CellAttributes() ?>>
<span id="el_student__email">
<span<?php echo $student->_email->ViewAttributes() ?>>
<?php echo $student->_email->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($student->password->Visible) { // password ?>
	<tr id="r_password">
		<td class="col-sm-2"><span id="elh_student_password"><?php echo $student->password->FldCaption() ?></span></td>
		<td data-name="password"<?php echo $student->password->CellAttributes() ?>>
<span id="el_student_password">
<span<?php echo $student->password->ViewAttributes() ?>>
<?php echo $student->password->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($student->class_id->Visible) { // class_id ?>
	<tr id="r_class_id">
		<td class="col-sm-2"><span id="elh_student_class_id"><?php echo $student->class_id->FldCaption() ?></span></td>
		<td data-name="class_id"<?php echo $student->class_id->CellAttributes() ?>>
<span id="el_student_class_id">
<span<?php echo $student->class_id->ViewAttributes() ?>>
<?php echo $student->class_id->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($student->section_id->Visible) { // section_id ?>
	<tr id="r_section_id">
		<td class="col-sm-2"><span id="elh_student_section_id"><?php echo $student->section_id->FldCaption() ?></span></td>
		<td data-name="section_id"<?php echo $student->section_id->CellAttributes() ?>>
<span id="el_student_section_id">
<span<?php echo $student->section_id->ViewAttributes() ?>>
<?php echo $student->section_id->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($student->parent_id->Visible) { // parent_id ?>
	<tr id="r_parent_id">
		<td class="col-sm-2"><span id="elh_student_parent_id"><?php echo $student->parent_id->FldCaption() ?></span></td>
		<td data-name="parent_id"<?php echo $student->parent_id->CellAttributes() ?>>
<span id="el_student_parent_id">
<span<?php echo $student->parent_id->ViewAttributes() ?>>
<?php echo $student->parent_id->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($student->roll->Visible) { // roll ?>
	<tr id="r_roll">
		<td class="col-sm-2"><span id="elh_student_roll"><?php echo $student->roll->FldCaption() ?></span></td>
		<td data-name="roll"<?php echo $student->roll->CellAttributes() ?>>
<span id="el_student_roll">
<span<?php echo $student->roll->ViewAttributes() ?>>
<?php echo $student->roll->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($student->transport_id->Visible) { // transport_id ?>
	<tr id="r_transport_id">
		<td class="col-sm-2"><span id="elh_student_transport_id"><?php echo $student->transport_id->FldCaption() ?></span></td>
		<td data-name="transport_id"<?php echo $student->transport_id->CellAttributes() ?>>
<span id="el_student_transport_id">
<span<?php echo $student->transport_id->ViewAttributes() ?>>
<?php echo $student->transport_id->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($student->dormitory_id->Visible) { // dormitory_id ?>
	<tr id="r_dormitory_id">
		<td class="col-sm-2"><span id="elh_student_dormitory_id"><?php echo $student->dormitory_id->FldCaption() ?></span></td>
		<td data-name="dormitory_id"<?php echo $student->dormitory_id->CellAttributes() ?>>
<span id="el_student_dormitory_id">
<span<?php echo $student->dormitory_id->ViewAttributes() ?>>
<?php echo $student->dormitory_id->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($student->dormitory_room_number->Visible) { // dormitory_room_number ?>
	<tr id="r_dormitory_room_number">
		<td class="col-sm-2"><span id="elh_student_dormitory_room_number"><?php echo $student->dormitory_room_number->FldCaption() ?></span></td>
		<td data-name="dormitory_room_number"<?php echo $student->dormitory_room_number->CellAttributes() ?>>
<span id="el_student_dormitory_room_number">
<span<?php echo $student->dormitory_room_number->ViewAttributes() ?>>
<?php echo $student->dormitory_room_number->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($student->authentication_key->Visible) { // authentication_key ?>
	<tr id="r_authentication_key">
		<td class="col-sm-2"><span id="elh_student_authentication_key"><?php echo $student->authentication_key->FldCaption() ?></span></td>
		<td data-name="authentication_key"<?php echo $student->authentication_key->CellAttributes() ?>>
<span id="el_student_authentication_key">
<span<?php echo $student->authentication_key->ViewAttributes() ?>>
<?php echo $student->authentication_key->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($student->image->Visible) { // image ?>
	<tr id="r_image">
		<td class="col-sm-2"><span id="elh_student_image"><?php echo $student->image->FldCaption() ?></span></td>
		<td data-name="image"<?php echo $student->image->CellAttributes() ?>>
<span id="el_student_image">
<span>
<?php echo ew_GetFileViewTag($student->image, $student->image->ViewValue) ?>
</span>
</span>
</td>
	</tr>
<?php } ?>
</table>
</form>
<script type="text/javascript">
fstudentview.Init();
</script>
<?php
$student_view->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$student_view->Page_Terminate();
?>
