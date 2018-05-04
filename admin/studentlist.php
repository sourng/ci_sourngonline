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

$student_list = NULL; // Initialize page object first

class cstudent_list extends cstudent {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = '{1F40EE59-17A4-45D3-96B6-AA2082B3D64A}';

	// Table name
	var $TableName = 'student';

	// Page object name
	var $PageObjName = 'student_list';

	// Grid form hidden field names
	var $FormName = 'fstudentlist';
	var $FormActionName = 'k_action';
	var $FormKeyName = 'k_key';
	var $FormOldKeyName = 'k_oldkey';
	var $FormBlankRowName = 'k_blankrow';
	var $FormKeyCountName = 'key_count';

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

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "studentadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "studentdelete.php";
		$this->MultiUpdateUrl = "studentupdate.php";

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

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

		// List options
		$this->ListOptions = new cListOptions();
		$this->ListOptions->TableVar = $this->TableVar;

		// Export options
		$this->ExportOptions = new cListOptions();
		$this->ExportOptions->Tag = "div";
		$this->ExportOptions->TagClassName = "ewExportOption";

		// Other options
		$this->OtherOptions['addedit'] = new cListOptions();
		$this->OtherOptions['addedit']->Tag = "div";
		$this->OtherOptions['addedit']->TagClassName = "ewAddEditOption";
		$this->OtherOptions['detail'] = new cListOptions();
		$this->OtherOptions['detail']->Tag = "div";
		$this->OtherOptions['detail']->TagClassName = "ewDetailOption";
		$this->OtherOptions['action'] = new cListOptions();
		$this->OtherOptions['action']->Tag = "div";
		$this->OtherOptions['action']->TagClassName = "ewActionOption";

		// Filter options
		$this->FilterOptions = new cListOptions();
		$this->FilterOptions->Tag = "div";
		$this->FilterOptions->TagClassName = "ewFilterOption fstudentlistsrch";

		// List actions
		$this->ListActions = new cListActions();
	}

	//
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsCustomExport, $gsExportFile, $UserProfile, $Language, $Security, $objForm;

		// User profile
		$UserProfile = new cUserProfile();

		// Security
		$Security = new cAdvancedSecurity();
		if (!$Security->IsLoggedIn()) $Security->AutoLogin();
		$Security->LoadCurrentUserLevel($this->ProjectID . $this->TableName);
		if (!$Security->CanList()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage(ew_DeniedMsg()); // Set no permission
			$this->Page_Terminate(ew_GetUrl("index.php"));
		}

		// NOTE: Security object may be needed in other part of the script, skip set to Nothing
		// 
		// Security = null;
		// 

		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action

		// Get grid add count
		$gridaddcnt = @$_GET[EW_TABLE_GRID_ADD_ROW_COUNT];
		if (is_numeric($gridaddcnt) && $gridaddcnt > 0)
			$this->GridAddRowCount = $gridaddcnt;

		// Set up list options
		$this->SetupListOptions();
		$this->student_id->SetVisibility();
		if ($this->IsAdd() || $this->IsCopy() || $this->IsGridAdd())
			$this->student_id->Visible = FALSE;
		$this->name->SetVisibility();
		$this->birthday->SetVisibility();
		$this->sex->SetVisibility();
		$this->blood_group->SetVisibility();
		$this->phone->SetVisibility();
		$this->_email->SetVisibility();
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

		// Setup other options
		$this->SetupOtherOptions();

		// Set up custom action (compatible with old version)
		foreach ($this->CustomActions as $name => $action)
			$this->ListActions->Add($name, $action);

		// Show checkbox column if multiple action
		foreach ($this->ListActions->Items as $listaction) {
			if ($listaction->Select == EW_ACTION_MULTIPLE && $listaction->Allow) {
				$this->ListOptions->Items["checkbox"]->Visible = TRUE;
				break;
			}
		}
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
			ew_SaveDebugMsg();
			header("Location: " . $url);
		}
		exit();
	}

	// Class variables
	var $ListOptions; // List options
	var $ExportOptions; // Export options
	var $SearchOptions; // Search options
	var $OtherOptions = array(); // Other options
	var $FilterOptions; // Filter options
	var $ListActions; // List actions
	var $SelectedCount = 0;
	var $SelectedIndex = 0;
	var $DisplayRecs = 20;
	var $StartRec;
	var $StopRec;
	var $TotalRecs = 0;
	var $RecRange = 10;
	var $Pager;
	var $AutoHidePager = EW_AUTO_HIDE_PAGER;
	var $AutoHidePageSizeSelector = EW_AUTO_HIDE_PAGE_SIZE_SELECTOR;
	var $DefaultSearchWhere = ""; // Default search WHERE clause
	var $SearchWhere = ""; // Search WHERE clause
	var $RecCnt = 0; // Record count
	var $EditRowCnt;
	var $StartRowCnt = 1;
	var $RowCnt = 0;
	var $Attrs = array(); // Row attributes and cell attributes
	var $RowIndex = 0; // Row index
	var $KeyCount = 0; // Key count
	var $RowAction = ""; // Row action
	var $RowOldKey = ""; // Row old key (for copy)
	var $RecPerRow = 0;
	var $MultiColumnClass;
	var $MultiColumnEditClass = "col-sm-12";
	var $MultiColumnCnt = 12;
	var $MultiColumnEditCnt = 12;
	var $GridCnt = 0;
	var $ColCnt = 0;
	var $DbMasterFilter = ""; // Master filter
	var $DbDetailFilter = ""; // Detail filter
	var $MasterRecordExists;
	var $MultiSelectKey;
	var $Command;
	var $RestoreSearch = FALSE;
	var $DetailPages;
	var $Recordset;
	var $OldRecordset;

	//
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError, $gsSearchError, $Security, $EW_EXPORT;

		// Search filters
		$sSrchAdvanced = ""; // Advanced search filter
		$sSrchBasic = ""; // Basic search filter
		$sFilter = "";

		// Get command
		$this->Command = strtolower(@$_GET["cmd"]);
		if ($this->IsPageRequest()) { // Validate request

			// Process list action first
			if ($this->ProcessListAction()) // Ajax request
				$this->Page_Terminate();

			// Handle reset command
			$this->ResetCmd();

			// Set up Breadcrumb
			if ($this->Export == "")
				$this->SetupBreadcrumb();

			// Hide list options
			if ($this->Export <> "") {
				$this->ListOptions->HideAllOptions(array("sequence"));
				$this->ListOptions->UseDropDownButton = FALSE; // Disable drop down button
				$this->ListOptions->UseButtonGroup = FALSE; // Disable button group
			} elseif ($this->CurrentAction == "gridadd" || $this->CurrentAction == "gridedit") {
				$this->ListOptions->HideAllOptions();
				$this->ListOptions->UseDropDownButton = FALSE; // Disable drop down button
				$this->ListOptions->UseButtonGroup = FALSE; // Disable button group
			}

			// Hide options
			if ($this->Export <> "" || $this->CurrentAction <> "") {
				$this->ExportOptions->HideAllOptions();
				$this->FilterOptions->HideAllOptions();
			}

			// Hide other options
			if ($this->Export <> "") {
				foreach ($this->OtherOptions as &$option)
					$option->HideAllOptions();
			}

			// Get default search criteria
			ew_AddFilter($this->DefaultSearchWhere, $this->BasicSearchWhere(TRUE));

			// Get basic search values
			$this->LoadBasicSearchValues();

			// Process filter list
			$this->ProcessFilterList();

			// Restore search parms from Session if not searching / reset / export
			if (($this->Export <> "" || $this->Command <> "search" && $this->Command <> "reset" && $this->Command <> "resetall") && $this->Command <> "json" && $this->CheckSearchParms())
				$this->RestoreSearchParms();

			// Call Recordset SearchValidated event
			$this->Recordset_SearchValidated();

			// Set up sorting order
			$this->SetupSortOrder();

			// Get basic search criteria
			if ($gsSearchError == "")
				$sSrchBasic = $this->BasicSearchWhere();
		}

		// Restore display records
		if ($this->Command <> "json" && $this->getRecordsPerPage() <> "") {
			$this->DisplayRecs = $this->getRecordsPerPage(); // Restore from Session
		} else {
			$this->DisplayRecs = 20; // Load default
		}

		// Load Sorting Order
		if ($this->Command <> "json")
			$this->LoadSortOrder();

		// Load search default if no existing search criteria
		if (!$this->CheckSearchParms()) {

			// Load basic search from default
			$this->BasicSearch->LoadDefault();
			if ($this->BasicSearch->Keyword != "")
				$sSrchBasic = $this->BasicSearchWhere();
		}

		// Build search criteria
		ew_AddFilter($this->SearchWhere, $sSrchAdvanced);
		ew_AddFilter($this->SearchWhere, $sSrchBasic);

		// Call Recordset_Searching event
		$this->Recordset_Searching($this->SearchWhere);

		// Save search criteria
		if ($this->Command == "search" && !$this->RestoreSearch) {
			$this->setSearchWhere($this->SearchWhere); // Save to Session
			$this->StartRec = 1; // Reset start record counter
			$this->setStartRecordNumber($this->StartRec);
		} elseif ($this->Command <> "json") {
			$this->SearchWhere = $this->getSearchWhere();
		}

		// Build filter
		$sFilter = "";
		ew_AddFilter($sFilter, $this->DbDetailFilter);
		ew_AddFilter($sFilter, $this->SearchWhere);

		// Set up filter
		if ($this->Command == "json") {
			$this->UseSessionForListSQL = FALSE; // Do not use session for ListSQL
			$this->CurrentFilter = $sFilter;
		} else {
			$this->setSessionWhere($sFilter);
			$this->CurrentFilter = "";
		}

		// Load record count first
		if (!$this->IsAddOrEdit()) {
			$bSelectLimit = $this->UseSelectLimit;
			if ($bSelectLimit) {
				$this->TotalRecs = $this->ListRecordCount();
			} else {
				if ($this->Recordset = $this->LoadRecordset())
					$this->TotalRecs = $this->Recordset->RecordCount();
			}
		}

		// Search options
		$this->SetupSearchOptions();
	}

	// Build filter for all keys
	function BuildKeyFilter() {
		global $objForm;
		$sWrkFilter = "";

		// Update row index and get row key
		$rowindex = 1;
		$objForm->Index = $rowindex;
		$sThisKey = strval($objForm->GetValue($this->FormKeyName));
		while ($sThisKey <> "") {
			if ($this->SetupKeyValues($sThisKey)) {
				$sFilter = $this->KeyFilter();
				if ($sWrkFilter <> "") $sWrkFilter .= " OR ";
				$sWrkFilter .= $sFilter;
			} else {
				$sWrkFilter = "0=1";
				break;
			}

			// Update row index and get row key
			$rowindex++; // Next row
			$objForm->Index = $rowindex;
			$sThisKey = strval($objForm->GetValue($this->FormKeyName));
		}
		return $sWrkFilter;
	}

	// Set up key values
	function SetupKeyValues($key) {
		$arrKeyFlds = explode($GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"], $key);
		if (count($arrKeyFlds) >= 1) {
			$this->student_id->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->student_id->FormValue))
				return FALSE;
		}
		return TRUE;
	}

	// Get list of filters
	function GetFilterList() {
		global $UserProfile;

		// Initialize
		$sFilterList = "";
		$sSavedFilterList = "";
		$sFilterList = ew_Concat($sFilterList, $this->student_id->AdvancedSearch->ToJson(), ","); // Field student_id
		$sFilterList = ew_Concat($sFilterList, $this->name->AdvancedSearch->ToJson(), ","); // Field name
		$sFilterList = ew_Concat($sFilterList, $this->birthday->AdvancedSearch->ToJson(), ","); // Field birthday
		$sFilterList = ew_Concat($sFilterList, $this->sex->AdvancedSearch->ToJson(), ","); // Field sex
		$sFilterList = ew_Concat($sFilterList, $this->religion->AdvancedSearch->ToJson(), ","); // Field religion
		$sFilterList = ew_Concat($sFilterList, $this->blood_group->AdvancedSearch->ToJson(), ","); // Field blood_group
		$sFilterList = ew_Concat($sFilterList, $this->address->AdvancedSearch->ToJson(), ","); // Field address
		$sFilterList = ew_Concat($sFilterList, $this->phone->AdvancedSearch->ToJson(), ","); // Field phone
		$sFilterList = ew_Concat($sFilterList, $this->_email->AdvancedSearch->ToJson(), ","); // Field email
		$sFilterList = ew_Concat($sFilterList, $this->password->AdvancedSearch->ToJson(), ","); // Field password
		$sFilterList = ew_Concat($sFilterList, $this->class_id->AdvancedSearch->ToJson(), ","); // Field class_id
		$sFilterList = ew_Concat($sFilterList, $this->section_id->AdvancedSearch->ToJson(), ","); // Field section_id
		$sFilterList = ew_Concat($sFilterList, $this->parent_id->AdvancedSearch->ToJson(), ","); // Field parent_id
		$sFilterList = ew_Concat($sFilterList, $this->roll->AdvancedSearch->ToJson(), ","); // Field roll
		$sFilterList = ew_Concat($sFilterList, $this->transport_id->AdvancedSearch->ToJson(), ","); // Field transport_id
		$sFilterList = ew_Concat($sFilterList, $this->dormitory_id->AdvancedSearch->ToJson(), ","); // Field dormitory_id
		$sFilterList = ew_Concat($sFilterList, $this->dormitory_room_number->AdvancedSearch->ToJson(), ","); // Field dormitory_room_number
		$sFilterList = ew_Concat($sFilterList, $this->authentication_key->AdvancedSearch->ToJson(), ","); // Field authentication_key
		$sFilterList = ew_Concat($sFilterList, $this->image->AdvancedSearch->ToJson(), ","); // Field image
		if ($this->BasicSearch->Keyword <> "") {
			$sWrk = "\"" . EW_TABLE_BASIC_SEARCH . "\":\"" . ew_JsEncode2($this->BasicSearch->Keyword) . "\",\"" . EW_TABLE_BASIC_SEARCH_TYPE . "\":\"" . ew_JsEncode2($this->BasicSearch->Type) . "\"";
			$sFilterList = ew_Concat($sFilterList, $sWrk, ",");
		}
		$sFilterList = preg_replace('/,$/', "", $sFilterList);

		// Return filter list in json
		if ($sFilterList <> "")
			$sFilterList = "\"data\":{" . $sFilterList . "}";
		if ($sSavedFilterList <> "") {
			if ($sFilterList <> "")
				$sFilterList .= ",";
			$sFilterList .= "\"filters\":" . $sSavedFilterList;
		}
		return ($sFilterList <> "") ? "{" . $sFilterList . "}" : "null";
	}

	// Process filter list
	function ProcessFilterList() {
		global $UserProfile;
		if (@$_POST["ajax"] == "savefilters") { // Save filter request (Ajax)
			$filters = @$_POST["filters"];
			$UserProfile->SetSearchFilters(CurrentUserName(), "fstudentlistsrch", $filters);

			// Clean output buffer
			if (!EW_DEBUG_ENABLED && ob_get_length())
				ob_end_clean();
			echo ew_ArrayToJson(array(array("success" => TRUE))); // Success
			$this->Page_Terminate();
			exit();
		} elseif (@$_POST["cmd"] == "resetfilter") {
			$this->RestoreFilterList();
		}
	}

	// Restore list of filters
	function RestoreFilterList() {

		// Return if not reset filter
		if (@$_POST["cmd"] <> "resetfilter")
			return FALSE;
		$filter = json_decode(@$_POST["filter"], TRUE);
		$this->Command = "search";

		// Field student_id
		$this->student_id->AdvancedSearch->SearchValue = @$filter["x_student_id"];
		$this->student_id->AdvancedSearch->SearchOperator = @$filter["z_student_id"];
		$this->student_id->AdvancedSearch->SearchCondition = @$filter["v_student_id"];
		$this->student_id->AdvancedSearch->SearchValue2 = @$filter["y_student_id"];
		$this->student_id->AdvancedSearch->SearchOperator2 = @$filter["w_student_id"];
		$this->student_id->AdvancedSearch->Save();

		// Field name
		$this->name->AdvancedSearch->SearchValue = @$filter["x_name"];
		$this->name->AdvancedSearch->SearchOperator = @$filter["z_name"];
		$this->name->AdvancedSearch->SearchCondition = @$filter["v_name"];
		$this->name->AdvancedSearch->SearchValue2 = @$filter["y_name"];
		$this->name->AdvancedSearch->SearchOperator2 = @$filter["w_name"];
		$this->name->AdvancedSearch->Save();

		// Field birthday
		$this->birthday->AdvancedSearch->SearchValue = @$filter["x_birthday"];
		$this->birthday->AdvancedSearch->SearchOperator = @$filter["z_birthday"];
		$this->birthday->AdvancedSearch->SearchCondition = @$filter["v_birthday"];
		$this->birthday->AdvancedSearch->SearchValue2 = @$filter["y_birthday"];
		$this->birthday->AdvancedSearch->SearchOperator2 = @$filter["w_birthday"];
		$this->birthday->AdvancedSearch->Save();

		// Field sex
		$this->sex->AdvancedSearch->SearchValue = @$filter["x_sex"];
		$this->sex->AdvancedSearch->SearchOperator = @$filter["z_sex"];
		$this->sex->AdvancedSearch->SearchCondition = @$filter["v_sex"];
		$this->sex->AdvancedSearch->SearchValue2 = @$filter["y_sex"];
		$this->sex->AdvancedSearch->SearchOperator2 = @$filter["w_sex"];
		$this->sex->AdvancedSearch->Save();

		// Field religion
		$this->religion->AdvancedSearch->SearchValue = @$filter["x_religion"];
		$this->religion->AdvancedSearch->SearchOperator = @$filter["z_religion"];
		$this->religion->AdvancedSearch->SearchCondition = @$filter["v_religion"];
		$this->religion->AdvancedSearch->SearchValue2 = @$filter["y_religion"];
		$this->religion->AdvancedSearch->SearchOperator2 = @$filter["w_religion"];
		$this->religion->AdvancedSearch->Save();

		// Field blood_group
		$this->blood_group->AdvancedSearch->SearchValue = @$filter["x_blood_group"];
		$this->blood_group->AdvancedSearch->SearchOperator = @$filter["z_blood_group"];
		$this->blood_group->AdvancedSearch->SearchCondition = @$filter["v_blood_group"];
		$this->blood_group->AdvancedSearch->SearchValue2 = @$filter["y_blood_group"];
		$this->blood_group->AdvancedSearch->SearchOperator2 = @$filter["w_blood_group"];
		$this->blood_group->AdvancedSearch->Save();

		// Field address
		$this->address->AdvancedSearch->SearchValue = @$filter["x_address"];
		$this->address->AdvancedSearch->SearchOperator = @$filter["z_address"];
		$this->address->AdvancedSearch->SearchCondition = @$filter["v_address"];
		$this->address->AdvancedSearch->SearchValue2 = @$filter["y_address"];
		$this->address->AdvancedSearch->SearchOperator2 = @$filter["w_address"];
		$this->address->AdvancedSearch->Save();

		// Field phone
		$this->phone->AdvancedSearch->SearchValue = @$filter["x_phone"];
		$this->phone->AdvancedSearch->SearchOperator = @$filter["z_phone"];
		$this->phone->AdvancedSearch->SearchCondition = @$filter["v_phone"];
		$this->phone->AdvancedSearch->SearchValue2 = @$filter["y_phone"];
		$this->phone->AdvancedSearch->SearchOperator2 = @$filter["w_phone"];
		$this->phone->AdvancedSearch->Save();

		// Field email
		$this->_email->AdvancedSearch->SearchValue = @$filter["x__email"];
		$this->_email->AdvancedSearch->SearchOperator = @$filter["z__email"];
		$this->_email->AdvancedSearch->SearchCondition = @$filter["v__email"];
		$this->_email->AdvancedSearch->SearchValue2 = @$filter["y__email"];
		$this->_email->AdvancedSearch->SearchOperator2 = @$filter["w__email"];
		$this->_email->AdvancedSearch->Save();

		// Field password
		$this->password->AdvancedSearch->SearchValue = @$filter["x_password"];
		$this->password->AdvancedSearch->SearchOperator = @$filter["z_password"];
		$this->password->AdvancedSearch->SearchCondition = @$filter["v_password"];
		$this->password->AdvancedSearch->SearchValue2 = @$filter["y_password"];
		$this->password->AdvancedSearch->SearchOperator2 = @$filter["w_password"];
		$this->password->AdvancedSearch->Save();

		// Field class_id
		$this->class_id->AdvancedSearch->SearchValue = @$filter["x_class_id"];
		$this->class_id->AdvancedSearch->SearchOperator = @$filter["z_class_id"];
		$this->class_id->AdvancedSearch->SearchCondition = @$filter["v_class_id"];
		$this->class_id->AdvancedSearch->SearchValue2 = @$filter["y_class_id"];
		$this->class_id->AdvancedSearch->SearchOperator2 = @$filter["w_class_id"];
		$this->class_id->AdvancedSearch->Save();

		// Field section_id
		$this->section_id->AdvancedSearch->SearchValue = @$filter["x_section_id"];
		$this->section_id->AdvancedSearch->SearchOperator = @$filter["z_section_id"];
		$this->section_id->AdvancedSearch->SearchCondition = @$filter["v_section_id"];
		$this->section_id->AdvancedSearch->SearchValue2 = @$filter["y_section_id"];
		$this->section_id->AdvancedSearch->SearchOperator2 = @$filter["w_section_id"];
		$this->section_id->AdvancedSearch->Save();

		// Field parent_id
		$this->parent_id->AdvancedSearch->SearchValue = @$filter["x_parent_id"];
		$this->parent_id->AdvancedSearch->SearchOperator = @$filter["z_parent_id"];
		$this->parent_id->AdvancedSearch->SearchCondition = @$filter["v_parent_id"];
		$this->parent_id->AdvancedSearch->SearchValue2 = @$filter["y_parent_id"];
		$this->parent_id->AdvancedSearch->SearchOperator2 = @$filter["w_parent_id"];
		$this->parent_id->AdvancedSearch->Save();

		// Field roll
		$this->roll->AdvancedSearch->SearchValue = @$filter["x_roll"];
		$this->roll->AdvancedSearch->SearchOperator = @$filter["z_roll"];
		$this->roll->AdvancedSearch->SearchCondition = @$filter["v_roll"];
		$this->roll->AdvancedSearch->SearchValue2 = @$filter["y_roll"];
		$this->roll->AdvancedSearch->SearchOperator2 = @$filter["w_roll"];
		$this->roll->AdvancedSearch->Save();

		// Field transport_id
		$this->transport_id->AdvancedSearch->SearchValue = @$filter["x_transport_id"];
		$this->transport_id->AdvancedSearch->SearchOperator = @$filter["z_transport_id"];
		$this->transport_id->AdvancedSearch->SearchCondition = @$filter["v_transport_id"];
		$this->transport_id->AdvancedSearch->SearchValue2 = @$filter["y_transport_id"];
		$this->transport_id->AdvancedSearch->SearchOperator2 = @$filter["w_transport_id"];
		$this->transport_id->AdvancedSearch->Save();

		// Field dormitory_id
		$this->dormitory_id->AdvancedSearch->SearchValue = @$filter["x_dormitory_id"];
		$this->dormitory_id->AdvancedSearch->SearchOperator = @$filter["z_dormitory_id"];
		$this->dormitory_id->AdvancedSearch->SearchCondition = @$filter["v_dormitory_id"];
		$this->dormitory_id->AdvancedSearch->SearchValue2 = @$filter["y_dormitory_id"];
		$this->dormitory_id->AdvancedSearch->SearchOperator2 = @$filter["w_dormitory_id"];
		$this->dormitory_id->AdvancedSearch->Save();

		// Field dormitory_room_number
		$this->dormitory_room_number->AdvancedSearch->SearchValue = @$filter["x_dormitory_room_number"];
		$this->dormitory_room_number->AdvancedSearch->SearchOperator = @$filter["z_dormitory_room_number"];
		$this->dormitory_room_number->AdvancedSearch->SearchCondition = @$filter["v_dormitory_room_number"];
		$this->dormitory_room_number->AdvancedSearch->SearchValue2 = @$filter["y_dormitory_room_number"];
		$this->dormitory_room_number->AdvancedSearch->SearchOperator2 = @$filter["w_dormitory_room_number"];
		$this->dormitory_room_number->AdvancedSearch->Save();

		// Field authentication_key
		$this->authentication_key->AdvancedSearch->SearchValue = @$filter["x_authentication_key"];
		$this->authentication_key->AdvancedSearch->SearchOperator = @$filter["z_authentication_key"];
		$this->authentication_key->AdvancedSearch->SearchCondition = @$filter["v_authentication_key"];
		$this->authentication_key->AdvancedSearch->SearchValue2 = @$filter["y_authentication_key"];
		$this->authentication_key->AdvancedSearch->SearchOperator2 = @$filter["w_authentication_key"];
		$this->authentication_key->AdvancedSearch->Save();

		// Field image
		$this->image->AdvancedSearch->SearchValue = @$filter["x_image"];
		$this->image->AdvancedSearch->SearchOperator = @$filter["z_image"];
		$this->image->AdvancedSearch->SearchCondition = @$filter["v_image"];
		$this->image->AdvancedSearch->SearchValue2 = @$filter["y_image"];
		$this->image->AdvancedSearch->SearchOperator2 = @$filter["w_image"];
		$this->image->AdvancedSearch->Save();
		$this->BasicSearch->setKeyword(@$filter[EW_TABLE_BASIC_SEARCH]);
		$this->BasicSearch->setType(@$filter[EW_TABLE_BASIC_SEARCH_TYPE]);
	}

	// Return basic search SQL
	function BasicSearchSQL($arKeywords, $type) {
		$sWhere = "";
		$this->BuildBasicSearchSQL($sWhere, $this->name, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->birthday, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->sex, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->religion, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->blood_group, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->address, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->phone, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->_email, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->password, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->class_id, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->roll, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->dormitory_room_number, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->authentication_key, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->image, $arKeywords, $type);
		return $sWhere;
	}

	// Build basic search SQL
	function BuildBasicSearchSQL(&$Where, &$Fld, $arKeywords, $type) {
		global $EW_BASIC_SEARCH_IGNORE_PATTERN;
		$sDefCond = ($type == "OR") ? "OR" : "AND";
		$arSQL = array(); // Array for SQL parts
		$arCond = array(); // Array for search conditions
		$cnt = count($arKeywords);
		$j = 0; // Number of SQL parts
		for ($i = 0; $i < $cnt; $i++) {
			$Keyword = $arKeywords[$i];
			$Keyword = trim($Keyword);
			if ($EW_BASIC_SEARCH_IGNORE_PATTERN <> "") {
				$Keyword = preg_replace($EW_BASIC_SEARCH_IGNORE_PATTERN, "\\", $Keyword);
				$ar = explode("\\", $Keyword);
			} else {
				$ar = array($Keyword);
			}
			foreach ($ar as $Keyword) {
				if ($Keyword <> "") {
					$sWrk = "";
					if ($Keyword == "OR" && $type == "") {
						if ($j > 0)
							$arCond[$j-1] = "OR";
					} elseif ($Keyword == EW_NULL_VALUE) {
						$sWrk = $Fld->FldExpression . " IS NULL";
					} elseif ($Keyword == EW_NOT_NULL_VALUE) {
						$sWrk = $Fld->FldExpression . " IS NOT NULL";
					} elseif ($Fld->FldIsVirtual) {
						$sWrk = $Fld->FldVirtualExpression . ew_Like(ew_QuotedValue("%" . $Keyword . "%", EW_DATATYPE_STRING, $this->DBID), $this->DBID);
					} elseif ($Fld->FldDataType != EW_DATATYPE_NUMBER || is_numeric($Keyword)) {
						$sWrk = $Fld->FldBasicSearchExpression . ew_Like(ew_QuotedValue("%" . $Keyword . "%", EW_DATATYPE_STRING, $this->DBID), $this->DBID);
					}
					if ($sWrk <> "") {
						$arSQL[$j] = $sWrk;
						$arCond[$j] = $sDefCond;
						$j += 1;
					}
				}
			}
		}
		$cnt = count($arSQL);
		$bQuoted = FALSE;
		$sSql = "";
		if ($cnt > 0) {
			for ($i = 0; $i < $cnt-1; $i++) {
				if ($arCond[$i] == "OR") {
					if (!$bQuoted) $sSql .= "(";
					$bQuoted = TRUE;
				}
				$sSql .= $arSQL[$i];
				if ($bQuoted && $arCond[$i] <> "OR") {
					$sSql .= ")";
					$bQuoted = FALSE;
				}
				$sSql .= " " . $arCond[$i] . " ";
			}
			$sSql .= $arSQL[$cnt-1];
			if ($bQuoted)
				$sSql .= ")";
		}
		if ($sSql <> "") {
			if ($Where <> "") $Where .= " OR ";
			$Where .= "(" . $sSql . ")";
		}
	}

	// Return basic search WHERE clause based on search keyword and type
	function BasicSearchWhere($Default = FALSE) {
		global $Security;
		$sSearchStr = "";
		$sSearchKeyword = ($Default) ? $this->BasicSearch->KeywordDefault : $this->BasicSearch->Keyword;
		$sSearchType = ($Default) ? $this->BasicSearch->TypeDefault : $this->BasicSearch->Type;

		// Get search SQL
		if ($sSearchKeyword <> "") {
			$ar = $this->BasicSearch->KeywordList($Default);

			// Search keyword in any fields
			if (($sSearchType == "OR" || $sSearchType == "AND") && $this->BasicSearch->BasicSearchAnyFields) {
				foreach ($ar as $sKeyword) {
					if ($sKeyword <> "") {
						if ($sSearchStr <> "") $sSearchStr .= " " . $sSearchType . " ";
						$sSearchStr .= "(" . $this->BasicSearchSQL(array($sKeyword), $sSearchType) . ")";
					}
				}
			} else {
				$sSearchStr = $this->BasicSearchSQL($ar, $sSearchType);
			}
			if (!$Default && in_array($this->Command, array("", "reset", "resetall"))) $this->Command = "search";
		}
		if (!$Default && $this->Command == "search") {
			$this->BasicSearch->setKeyword($sSearchKeyword);
			$this->BasicSearch->setType($sSearchType);
		}
		return $sSearchStr;
	}

	// Check if search parm exists
	function CheckSearchParms() {

		// Check basic search
		if ($this->BasicSearch->IssetSession())
			return TRUE;
		return FALSE;
	}

	// Clear all search parameters
	function ResetSearchParms() {

		// Clear search WHERE clause
		$this->SearchWhere = "";
		$this->setSearchWhere($this->SearchWhere);

		// Clear basic search parameters
		$this->ResetBasicSearchParms();
	}

	// Load advanced search default values
	function LoadAdvancedSearchDefault() {
		return FALSE;
	}

	// Clear all basic search parameters
	function ResetBasicSearchParms() {
		$this->BasicSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore basic search values
		$this->BasicSearch->Load();
	}

	// Set up sort parameters
	function SetupSortOrder() {

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = @$_GET["order"];
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->student_id); // student_id
			$this->UpdateSort($this->name); // name
			$this->UpdateSort($this->birthday); // birthday
			$this->UpdateSort($this->sex); // sex
			$this->UpdateSort($this->blood_group); // blood_group
			$this->UpdateSort($this->phone); // phone
			$this->UpdateSort($this->_email); // email
			$this->UpdateSort($this->image); // image
			$this->setStartRecordNumber(1); // Reset start position
		}
	}

	// Load sort order parameters
	function LoadSortOrder() {
		$sOrderBy = $this->getSessionOrderBy(); // Get ORDER BY from Session
		if ($sOrderBy == "") {
			if ($this->getSqlOrderBy() <> "") {
				$sOrderBy = $this->getSqlOrderBy();
				$this->setSessionOrderBy($sOrderBy);
			}
		}
	}

	// Reset command
	// - cmd=reset (Reset search parameters)
	// - cmd=resetall (Reset search and master/detail parameters)
	// - cmd=resetsort (Reset sort parameters)
	function ResetCmd() {

		// Check if reset command
		if (substr($this->Command,0,5) == "reset") {

			// Reset search criteria
			if ($this->Command == "reset" || $this->Command == "resetall")
				$this->ResetSearchParms();

			// Reset sorting order
			if ($this->Command == "resetsort") {
				$sOrderBy = "";
				$this->setSessionOrderBy($sOrderBy);
				$this->setSessionOrderByList($sOrderBy);
				$this->student_id->setSort("");
				$this->name->setSort("");
				$this->birthday->setSort("");
				$this->sex->setSort("");
				$this->blood_group->setSort("");
				$this->phone->setSort("");
				$this->_email->setSort("");
				$this->image->setSort("");
			}

			// Reset start position
			$this->StartRec = 1;
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Set up list options
	function SetupListOptions() {
		global $Security, $Language;

		// Add group option item
		$item = &$this->ListOptions->Add($this->ListOptions->GroupOptionName);
		$item->Body = "";
		$item->OnLeft = FALSE;
		$item->Visible = FALSE;

		// "view"
		$item = &$this->ListOptions->Add("view");
		$item->CssClass = "text-nowrap";
		$item->Visible = $Security->IsLoggedIn();
		$item->OnLeft = FALSE;

		// "edit"
		$item = &$this->ListOptions->Add("edit");
		$item->CssClass = "text-nowrap";
		$item->Visible = $Security->IsLoggedIn();
		$item->OnLeft = FALSE;

		// "copy"
		$item = &$this->ListOptions->Add("copy");
		$item->CssClass = "text-nowrap";
		$item->Visible = $Security->IsLoggedIn();
		$item->OnLeft = FALSE;

		// "delete"
		$item = &$this->ListOptions->Add("delete");
		$item->CssClass = "text-nowrap";
		$item->Visible = $Security->IsLoggedIn();
		$item->OnLeft = FALSE;

		// List actions
		$item = &$this->ListOptions->Add("listactions");
		$item->CssClass = "text-nowrap";
		$item->OnLeft = FALSE;
		$item->Visible = FALSE;
		$item->ShowInButtonGroup = FALSE;
		$item->ShowInDropDown = FALSE;

		// "checkbox"
		$item = &$this->ListOptions->Add("checkbox");
		$item->Visible = FALSE;
		$item->OnLeft = FALSE;
		$item->Header = "<input type=\"checkbox\" name=\"key\" id=\"key\" onclick=\"ew_SelectAllKey(this);\">";
		$item->ShowInDropDown = FALSE;
		$item->ShowInButtonGroup = FALSE;

		// Drop down button for ListOptions
		$this->ListOptions->UseImageAndText = TRUE;
		$this->ListOptions->UseDropDownButton = FALSE;
		$this->ListOptions->DropDownButtonPhrase = $Language->Phrase("ButtonListOptions");
		$this->ListOptions->UseButtonGroup = FALSE;
		if ($this->ListOptions->UseButtonGroup && ew_IsMobile())
			$this->ListOptions->UseDropDownButton = TRUE;
		$this->ListOptions->ButtonClass = "btn-sm"; // Class for button group

		// Call ListOptions_Load event
		$this->ListOptions_Load();
		$this->SetupListOptionsExt();
		$item = &$this->ListOptions->GetItem($this->ListOptions->GroupOptionName);
		$item->Visible = $this->ListOptions->GroupOptionVisible();
	}

	// Render list options
	function RenderListOptions() {
		global $Security, $Language, $objForm;
		$this->ListOptions->LoadDefault();

		// Call ListOptions_Rendering event
		$this->ListOptions_Rendering();

		// "view"
		$oListOpt = &$this->ListOptions->Items["view"];
		$viewcaption = ew_HtmlTitle($Language->Phrase("ViewLink"));
		if ($Security->IsLoggedIn()) {
			if (ew_IsMobile())
				$oListOpt->Body = "<a class=\"ewRowLink ewView\" title=\"" . $viewcaption . "\" data-caption=\"" . $viewcaption . "\" href=\"" . ew_HtmlEncode($this->ViewUrl) . "\">" . $Language->Phrase("ViewLink") . "</a>";
			else
				$oListOpt->Body = "<a class=\"ewRowLink ewView\" title=\"" . $viewcaption . "\" data-table=\"student\" data-caption=\"" . $viewcaption . "\" href=\"javascript:void(0);\" onclick=\"ew_ModalDialogShow({lnk:this,url:'" . ew_HtmlEncode($this->ViewUrl) . "',btn:null});\">" . $Language->Phrase("ViewLink") . "</a>";
		} else {
			$oListOpt->Body = "";
		}

		// "edit"
		$oListOpt = &$this->ListOptions->Items["edit"];
		$editcaption = ew_HtmlTitle($Language->Phrase("EditLink"));
		if ($Security->IsLoggedIn()) {
			if (ew_IsMobile())
				$oListOpt->Body = "<a class=\"ewRowLink ewEdit\" title=\"" . $editcaption . "\" data-caption=\"" . $editcaption . "\" href=\"" . ew_HtmlEncode($this->EditUrl) . "\">" . $Language->Phrase("EditLink") . "</a>";
			else
				$oListOpt->Body = "<a class=\"ewRowLink ewEdit\" title=\"" . $editcaption . "\" data-table=\"student\" data-caption=\"" . $editcaption . "\" href=\"javascript:void(0);\" onclick=\"ew_ModalDialogShow({lnk:this,btn:'SaveBtn',url:'" . ew_HtmlEncode($this->EditUrl) . "'});\">" . $Language->Phrase("EditLink") . "</a>";
		} else {
			$oListOpt->Body = "";
		}

		// "copy"
		$oListOpt = &$this->ListOptions->Items["copy"];
		$copycaption = ew_HtmlTitle($Language->Phrase("CopyLink"));
		if ($Security->IsLoggedIn()) {
			if (ew_IsMobile())
				$oListOpt->Body = "<a class=\"ewRowLink ewCopy\" title=\"" . $copycaption . "\" data-caption=\"" . $copycaption . "\" href=\"" . ew_HtmlEncode($this->CopyUrl) . "\">" . $Language->Phrase("CopyLink") . "</a>";
			else
				$oListOpt->Body = "<a class=\"ewRowLink ewCopy\" title=\"" . $copycaption . "\" data-table=\"student\" data-caption=\"" . $copycaption . "\" href=\"javascript:void(0);\" onclick=\"ew_ModalDialogShow({lnk:this,btn:'AddBtn',url:'" . ew_HtmlEncode($this->CopyUrl) . "'});\">" . $Language->Phrase("CopyLink") . "</a>";
		} else {
			$oListOpt->Body = "";
		}

		// "delete"
		$oListOpt = &$this->ListOptions->Items["delete"];
		if ($Security->IsLoggedIn())
			$oListOpt->Body = "<a class=\"ewRowLink ewDelete\"" . "" . " title=\"" . ew_HtmlTitle($Language->Phrase("DeleteLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("DeleteLink")) . "\" href=\"" . ew_HtmlEncode($this->DeleteUrl) . "\">" . $Language->Phrase("DeleteLink") . "</a>";
		else
			$oListOpt->Body = "";

		// Set up list action buttons
		$oListOpt = &$this->ListOptions->GetItem("listactions");
		if ($oListOpt && $this->Export == "" && $this->CurrentAction == "") {
			$body = "";
			$links = array();
			foreach ($this->ListActions->Items as $listaction) {
				if ($listaction->Select == EW_ACTION_SINGLE && $listaction->Allow) {
					$action = $listaction->Action;
					$caption = $listaction->Caption;
					$icon = ($listaction->Icon <> "") ? "<span class=\"" . ew_HtmlEncode(str_replace(" ewIcon", "", $listaction->Icon)) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\"></span> " : "";
					$links[] = "<li><a class=\"ewAction ewListAction\" data-action=\"" . ew_HtmlEncode($action) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({key:" . $this->KeyToJson() . "}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . $listaction->Caption . "</a></li>";
					if (count($links) == 1) // Single button
						$body = "<a class=\"ewAction ewListAction\" data-action=\"" . ew_HtmlEncode($action) . "\" title=\"" . ew_HtmlTitle($caption) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({key:" . $this->KeyToJson() . "}," . $listaction->ToJson(TRUE) . "));return false;\">" . $Language->Phrase("ListActionButton") . "</a>";
				}
			}
			if (count($links) > 1) { // More than one buttons, use dropdown
				$body = "<button class=\"dropdown-toggle btn btn-default btn-sm ewActions\" title=\"" . ew_HtmlTitle($Language->Phrase("ListActionButton")) . "\" data-toggle=\"dropdown\">" . $Language->Phrase("ListActionButton") . "<b class=\"caret\"></b></button>";
				$content = "";
				foreach ($links as $link)
					$content .= "<li>" . $link . "</li>";
				$body .= "<ul class=\"dropdown-menu" . ($oListOpt->OnLeft ? "" : " dropdown-menu-right") . "\">". $content . "</ul>";
				$body = "<div class=\"btn-group\">" . $body . "</div>";
			}
			if (count($links) > 0) {
				$oListOpt->Body = $body;
				$oListOpt->Visible = TRUE;
			}
		}

		// "checkbox"
		$oListOpt = &$this->ListOptions->Items["checkbox"];
		$oListOpt->Body = "<input type=\"checkbox\" name=\"key_m[]\" class=\"ewMultiSelect\" value=\"" . ew_HtmlEncode($this->student_id->CurrentValue) . "\" onclick=\"ew_ClickMultiCheckbox(event);\">";
		$this->RenderListOptionsExt();

		// Call ListOptions_Rendered event
		$this->ListOptions_Rendered();
	}

	// Set up other options
	function SetupOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
		$option = $options["addedit"];

		// Add
		$item = &$option->Add("add");
		$addcaption = ew_HtmlTitle($Language->Phrase("AddLink"));
		if (ew_IsMobile())
			$item->Body = "<a class=\"ewAddEdit ewAdd\" title=\"" . $addcaption . "\" data-caption=\"" . $addcaption . "\" href=\"" . ew_HtmlEncode($this->AddUrl) . "\">" . $Language->Phrase("AddLink") . "</a>";
		else
			$item->Body = "<a class=\"ewAddEdit ewAdd\" title=\"" . $addcaption . "\" data-table=\"student\" data-caption=\"" . $addcaption . "\" href=\"javascript:void(0);\" onclick=\"ew_ModalDialogShow({lnk:this,btn:'AddBtn',url:'" . ew_HtmlEncode($this->AddUrl) . "'});\">" . $Language->Phrase("AddLink") . "</a>";
		$item->Visible = ($this->AddUrl <> "" && $Security->IsLoggedIn());
		$option = $options["action"];

		// Set up options default
		foreach ($options as &$option) {
			$option->UseImageAndText = TRUE;
			$option->UseDropDownButton = FALSE;
			$option->UseButtonGroup = TRUE;
			$option->ButtonClass = "btn-sm"; // Class for button group
			$item = &$option->Add($option->GroupOptionName);
			$item->Body = "";
			$item->Visible = FALSE;
		}
		$options["addedit"]->DropDownButtonPhrase = $Language->Phrase("ButtonAddEdit");
		$options["detail"]->DropDownButtonPhrase = $Language->Phrase("ButtonDetails");
		$options["action"]->DropDownButtonPhrase = $Language->Phrase("ButtonActions");

		// Filter button
		$item = &$this->FilterOptions->Add("savecurrentfilter");
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"fstudentlistsrch\" href=\"#\">" . $Language->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"fstudentlistsrch\" href=\"#\">" . $Language->Phrase("DeleteFilter") . "</a>";
		$item->Visible = TRUE;
		$this->FilterOptions->UseDropDownButton = TRUE;
		$this->FilterOptions->UseButtonGroup = !$this->FilterOptions->UseDropDownButton;
		$this->FilterOptions->DropDownButtonPhrase = $Language->Phrase("Filters");

		// Add group option item
		$item = &$this->FilterOptions->Add($this->FilterOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;
	}

	// Render other options
	function RenderOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
			$option = &$options["action"];

			// Set up list action buttons
			foreach ($this->ListActions->Items as $listaction) {
				if ($listaction->Select == EW_ACTION_MULTIPLE) {
					$item = &$option->Add("custom_" . $listaction->Action);
					$caption = $listaction->Caption;
					$icon = ($listaction->Icon <> "") ? "<span class=\"" . ew_HtmlEncode($listaction->Icon) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\"></span> " : $caption;
					$item->Body = "<a class=\"ewAction ewListAction\" title=\"" . ew_HtmlEncode($caption) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({f:document.fstudentlist}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . "</a>";
					$item->Visible = $listaction->Allow;
				}
			}

			// Hide grid edit and other options
			if ($this->TotalRecs <= 0) {
				$option = &$options["addedit"];
				$item = &$option->GetItem("gridedit");
				if ($item) $item->Visible = FALSE;
				$option = &$options["action"];
				$option->HideAllOptions();
			}
	}

	// Process list action
	function ProcessListAction() {
		global $Language, $Security;
		$userlist = "";
		$user = "";
		$sFilter = $this->GetKeyFilter();
		$UserAction = @$_POST["useraction"];
		if ($sFilter <> "" && $UserAction <> "") {

			// Check permission first
			$ActionCaption = $UserAction;
			if (array_key_exists($UserAction, $this->ListActions->Items)) {
				$ActionCaption = $this->ListActions->Items[$UserAction]->Caption;
				if (!$this->ListActions->Items[$UserAction]->Allow) {
					$errmsg = str_replace('%s', $ActionCaption, $Language->Phrase("CustomActionNotAllowed"));
					if (@$_POST["ajax"] == $UserAction) // Ajax
						echo "<p class=\"text-danger\">" . $errmsg . "</p>";
					else
						$this->setFailureMessage($errmsg);
					return FALSE;
				}
			}
			$this->CurrentFilter = $sFilter;
			$sSql = $this->SQL();
			$conn = &$this->Connection();
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			$rs = $conn->Execute($sSql);
			$conn->raiseErrorFn = '';
			$this->CurrentAction = $UserAction;

			// Call row action event
			if ($rs && !$rs->EOF) {
				$conn->BeginTrans();
				$this->SelectedCount = $rs->RecordCount();
				$this->SelectedIndex = 0;
				while (!$rs->EOF) {
					$this->SelectedIndex++;
					$row = $rs->fields;
					$Processed = $this->Row_CustomAction($UserAction, $row);
					if (!$Processed) break;
					$rs->MoveNext();
				}
				if ($Processed) {
					$conn->CommitTrans(); // Commit the changes
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage(str_replace('%s', $ActionCaption, $Language->Phrase("CustomActionCompleted"))); // Set up success message
				} else {
					$conn->RollbackTrans(); // Rollback changes

					// Set up error message
					if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

						// Use the message, do nothing
					} elseif ($this->CancelMessage <> "") {
						$this->setFailureMessage($this->CancelMessage);
						$this->CancelMessage = "";
					} else {
						$this->setFailureMessage(str_replace('%s', $ActionCaption, $Language->Phrase("CustomActionFailed")));
					}
				}
			}
			if ($rs)
				$rs->Close();
			$this->CurrentAction = ""; // Clear action
			if (@$_POST["ajax"] == $UserAction) { // Ajax
				if ($this->getSuccessMessage() <> "") {
					echo "<p class=\"text-success\">" . $this->getSuccessMessage() . "</p>";
					$this->ClearSuccessMessage(); // Clear message
				}
				if ($this->getFailureMessage() <> "") {
					echo "<p class=\"text-danger\">" . $this->getFailureMessage() . "</p>";
					$this->ClearFailureMessage(); // Clear message
				}
				return TRUE;
			}
		}
		return FALSE; // Not ajax request
	}

	// Set up search options
	function SetupSearchOptions() {
		global $Language;
		$this->SearchOptions = new cListOptions();
		$this->SearchOptions->Tag = "div";
		$this->SearchOptions->TagClassName = "ewSearchOption";

		// Search button
		$item = &$this->SearchOptions->Add("searchtoggle");
		$SearchToggleClass = ($this->SearchWhere <> "") ? " active" : " active";
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"fstudentlistsrch\">" . $Language->Phrase("SearchLink") . "</button>";
		$item->Visible = TRUE;

		// Show all button
		$item = &$this->SearchOptions->Add("showall");
		$item->Body = "<a class=\"btn btn-default ewShowAll\" title=\"" . $Language->Phrase("ShowAll") . "\" data-caption=\"" . $Language->Phrase("ShowAll") . "\" href=\"" . $this->PageUrl() . "cmd=reset\">" . $Language->Phrase("ShowAllBtn") . "</a>";
		$item->Visible = ($this->SearchWhere <> $this->DefaultSearchWhere && $this->SearchWhere <> "0=101");

		// Button group for search
		$this->SearchOptions->UseDropDownButton = FALSE;
		$this->SearchOptions->UseImageAndText = TRUE;
		$this->SearchOptions->UseButtonGroup = TRUE;
		$this->SearchOptions->DropDownButtonPhrase = $Language->Phrase("ButtonSearch");

		// Add group option item
		$item = &$this->SearchOptions->Add($this->SearchOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;

		// Hide search options
		if ($this->Export <> "" || $this->CurrentAction <> "")
			$this->SearchOptions->HideAllOptions();
	}

	function SetupListOptionsExt() {
		global $Security, $Language;
	}

	function RenderListOptionsExt() {
		global $Security, $Language;
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

	// Load basic search values
	function LoadBasicSearchValues() {
		$this->BasicSearch->Keyword = @$_GET[EW_TABLE_BASIC_SEARCH];
		if ($this->BasicSearch->Keyword <> "" && $this->Command == "") $this->Command = "search";
		$this->BasicSearch->Type = @$_GET[EW_TABLE_BASIC_SEARCH_TYPE];
	}

	// Load recordset
	function LoadRecordset($offset = -1, $rowcnt = -1) {

		// Load List page SQL
		$sSql = $this->ListSQL();
		$conn = &$this->Connection();

		// Load recordset
		$dbtype = ew_GetConnectionType($this->DBID);
		if ($this->UseSelectLimit) {
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			if ($dbtype == "MSSQL") {
				$rs = $conn->SelectLimit($sSql, $rowcnt, $offset, array("_hasOrderBy" => trim($this->getOrderBy()) || trim($this->getSessionOrderByList())));
			} else {
				$rs = $conn->SelectLimit($sSql, $rowcnt, $offset);
			}
			$conn->raiseErrorFn = '';
		} else {
			$rs = ew_LoadRecordset($sSql, $conn);
		}

		// Call Recordset Selected event
		$this->Recordset_Selected($rs);
		return $rs;
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
		$this->ViewUrl = $this->GetViewUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->InlineEditUrl = $this->GetInlineEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->InlineCopyUrl = $this->GetInlineCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();

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

		// blood_group
		if (strval($this->blood_group->CurrentValue) <> "") {
			$this->blood_group->ViewValue = $this->blood_group->OptionCaption($this->blood_group->CurrentValue);
		} else {
			$this->blood_group->ViewValue = NULL;
		}
		$this->blood_group->ViewCustomAttributes = "";

		// phone
		$this->phone->ViewValue = $this->phone->CurrentValue;
		$this->phone->ViewCustomAttributes = "";

		// email
		$this->_email->ViewValue = $this->_email->CurrentValue;
		$this->_email->ViewCustomAttributes = "";

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

			// blood_group
			$this->blood_group->LinkCustomAttributes = "";
			$this->blood_group->HrefValue = "";
			$this->blood_group->TooltipValue = "";

			// phone
			$this->phone->LinkCustomAttributes = "";
			$this->phone->HrefValue = "";
			$this->phone->TooltipValue = "";

			// email
			$this->_email->LinkCustomAttributes = "";
			$this->_email->HrefValue = "";
			$this->_email->TooltipValue = "";

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
				$this->image->LinkAttrs["data-rel"] = "student_x" . $this->RowCnt . "_image";
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
		$url = preg_replace('/\?cmd=reset(all){0,1}$/i', '', $url); // Remove cmd=reset / cmd=resetall
		$Breadcrumb->Add("list", $this->TableVar, $url, "", $this->TableVar, TRUE);
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

	// ListOptions Load event
	function ListOptions_Load() {

		// Example:
		//$opt = &$this->ListOptions->Add("new");
		//$opt->Header = "xxx";
		//$opt->OnLeft = TRUE; // Link on left
		//$opt->MoveTo(0); // Move to first column

	}

	// ListOptions Rendering event
	function ListOptions_Rendering() {

		//$GLOBALS["xxx_grid"]->DetailAdd = (...condition...); // Set to TRUE or FALSE conditionally
		//$GLOBALS["xxx_grid"]->DetailEdit = (...condition...); // Set to TRUE or FALSE conditionally
		//$GLOBALS["xxx_grid"]->DetailView = (...condition...); // Set to TRUE or FALSE conditionally

	}

	// ListOptions Rendered event
	function ListOptions_Rendered() {

		// Example:
		//$this->ListOptions->Items["new"]->Body = "xxx";

	}

	// Row Custom Action event
	function Row_CustomAction($action, $row) {

		// Return FALSE to abort
		return TRUE;
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
if (!isset($student_list)) $student_list = new cstudent_list();

// Page init
$student_list->Page_Init();

// Page main
$student_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$student_list->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "list";
var CurrentForm = fstudentlist = new ew_Form("fstudentlist", "list");
fstudentlist.FormKeyCountName = '<?php echo $student_list->FormKeyCountName ?>';

// Form_CustomValidate event
fstudentlist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fstudentlist.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
fstudentlist.Lists["x_sex"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fstudentlist.Lists["x_sex"].Options = <?php echo json_encode($student_list->sex->Options()) ?>;
fstudentlist.Lists["x_blood_group"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fstudentlist.Lists["x_blood_group"].Options = <?php echo json_encode($student_list->blood_group->Options()) ?>;

// Form object for search
var CurrentSearchForm = fstudentlistsrch = new ew_Form("fstudentlistsrch");
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php if ($student_list->TotalRecs > 0 && $student_list->ExportOptions->Visible()) { ?>
<?php $student_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($student_list->SearchOptions->Visible()) { ?>
<?php $student_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php if ($student_list->FilterOptions->Visible()) { ?>
<?php $student_list->FilterOptions->Render("body") ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php
	$bSelectLimit = $student_list->UseSelectLimit;
	if ($bSelectLimit) {
		if ($student_list->TotalRecs <= 0)
			$student_list->TotalRecs = $student->ListRecordCount();
	} else {
		if (!$student_list->Recordset && ($student_list->Recordset = $student_list->LoadRecordset()))
			$student_list->TotalRecs = $student_list->Recordset->RecordCount();
	}
	$student_list->StartRec = 1;
	if ($student_list->DisplayRecs <= 0 || ($student->Export <> "" && $student->ExportAll)) // Display all records
		$student_list->DisplayRecs = $student_list->TotalRecs;
	if (!($student->Export <> "" && $student->ExportAll))
		$student_list->SetupStartRec(); // Set up start record position
	if ($bSelectLimit)
		$student_list->Recordset = $student_list->LoadRecordset($student_list->StartRec-1, $student_list->DisplayRecs);

	// Set no record found message
	if ($student->CurrentAction == "" && $student_list->TotalRecs == 0) {
		if ($student_list->SearchWhere == "0=101")
			$student_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$student_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
$student_list->RenderOtherOptions();
?>
<?php if ($Security->IsLoggedIn()) { ?>
<?php if ($student->Export == "" && $student->CurrentAction == "") { ?>
<form name="fstudentlistsrch" id="fstudentlistsrch" class="form-inline ewForm ewExtSearchForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($student_list->SearchWhere <> "") ? " in" : " in"; ?>
<div id="fstudentlistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="student">
	<div class="ewBasicSearch">
<div id="xsr_1" class="ewRow">
	<div class="ewQuickSearch input-group">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="form-control" value="<?php echo ew_HtmlEncode($student_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo ew_HtmlEncode($Language->Phrase("Search")) ?>">
	<input type="hidden" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" id="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="<?php echo ew_HtmlEncode($student_list->BasicSearch->getType()) ?>">
	<div class="input-group-btn">
		<button type="button" data-toggle="dropdown" class="btn btn-default"><span id="searchtype"><?php echo $student_list->BasicSearch->getTypeNameShort() ?></span><span class="caret"></span></button>
		<ul class="dropdown-menu pull-right" role="menu">
			<li<?php if ($student_list->BasicSearch->getType() == "") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this)"><?php echo $Language->Phrase("QuickSearchAuto") ?></a></li>
			<li<?php if ($student_list->BasicSearch->getType() == "=") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'=')"><?php echo $Language->Phrase("QuickSearchExact") ?></a></li>
			<li<?php if ($student_list->BasicSearch->getType() == "AND") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'AND')"><?php echo $Language->Phrase("QuickSearchAll") ?></a></li>
			<li<?php if ($student_list->BasicSearch->getType() == "OR") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'OR')"><?php echo $Language->Phrase("QuickSearchAny") ?></a></li>
		</ul>
	<button class="btn btn-primary ewButton" name="btnsubmit" id="btnsubmit" type="submit"><?php echo $Language->Phrase("SearchBtn") ?></button>
	</div>
	</div>
</div>
	</div>
</div>
</form>
<?php } ?>
<?php } ?>
<?php $student_list->ShowPageHeader(); ?>
<?php
$student_list->ShowMessage();
?>
<?php if ($student_list->TotalRecs > 0 || $student->CurrentAction <> "") { ?>
<div class="box ewBox ewGrid<?php if ($student_list->IsAddOrEdit()) { ?> ewGridAddEdit<?php } ?> student">
<form name="fstudentlist" id="fstudentlist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($student_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $student_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="student">
<div id="gmp_student" class="<?php if (ew_IsResponsiveLayout()) { ?>table-responsive <?php } ?>ewGridMiddlePanel">
<?php if ($student_list->TotalRecs > 0 || $student->CurrentAction == "gridedit") { ?>
<table id="tbl_studentlist" class="table ewTable">
<thead>
	<tr class="ewTableHeader">
<?php

// Header row
$student_list->RowType = EW_ROWTYPE_HEADER;

// Render list options
$student_list->RenderListOptions();

// Render list options (header, left)
$student_list->ListOptions->Render("header", "left");
?>
<?php if ($student->student_id->Visible) { // student_id ?>
	<?php if ($student->SortUrl($student->student_id) == "") { ?>
		<th data-name="student_id" class="<?php echo $student->student_id->HeaderCellClass() ?>"><div id="elh_student_student_id" class="student_student_id"><div class="ewTableHeaderCaption"><?php echo $student->student_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="student_id" class="<?php echo $student->student_id->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $student->SortUrl($student->student_id) ?>',1);"><div id="elh_student_student_id" class="student_student_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $student->student_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($student->student_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($student->student_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($student->name->Visible) { // name ?>
	<?php if ($student->SortUrl($student->name) == "") { ?>
		<th data-name="name" class="<?php echo $student->name->HeaderCellClass() ?>"><div id="elh_student_name" class="student_name"><div class="ewTableHeaderCaption"><?php echo $student->name->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="name" class="<?php echo $student->name->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $student->SortUrl($student->name) ?>',1);"><div id="elh_student_name" class="student_name">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $student->name->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($student->name->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($student->name->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($student->birthday->Visible) { // birthday ?>
	<?php if ($student->SortUrl($student->birthday) == "") { ?>
		<th data-name="birthday" class="<?php echo $student->birthday->HeaderCellClass() ?>"><div id="elh_student_birthday" class="student_birthday"><div class="ewTableHeaderCaption"><?php echo $student->birthday->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="birthday" class="<?php echo $student->birthday->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $student->SortUrl($student->birthday) ?>',1);"><div id="elh_student_birthday" class="student_birthday">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $student->birthday->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($student->birthday->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($student->birthday->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($student->sex->Visible) { // sex ?>
	<?php if ($student->SortUrl($student->sex) == "") { ?>
		<th data-name="sex" class="<?php echo $student->sex->HeaderCellClass() ?>"><div id="elh_student_sex" class="student_sex"><div class="ewTableHeaderCaption"><?php echo $student->sex->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="sex" class="<?php echo $student->sex->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $student->SortUrl($student->sex) ?>',1);"><div id="elh_student_sex" class="student_sex">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $student->sex->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($student->sex->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($student->sex->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($student->blood_group->Visible) { // blood_group ?>
	<?php if ($student->SortUrl($student->blood_group) == "") { ?>
		<th data-name="blood_group" class="<?php echo $student->blood_group->HeaderCellClass() ?>"><div id="elh_student_blood_group" class="student_blood_group"><div class="ewTableHeaderCaption"><?php echo $student->blood_group->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="blood_group" class="<?php echo $student->blood_group->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $student->SortUrl($student->blood_group) ?>',1);"><div id="elh_student_blood_group" class="student_blood_group">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $student->blood_group->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($student->blood_group->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($student->blood_group->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($student->phone->Visible) { // phone ?>
	<?php if ($student->SortUrl($student->phone) == "") { ?>
		<th data-name="phone" class="<?php echo $student->phone->HeaderCellClass() ?>"><div id="elh_student_phone" class="student_phone"><div class="ewTableHeaderCaption"><?php echo $student->phone->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="phone" class="<?php echo $student->phone->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $student->SortUrl($student->phone) ?>',1);"><div id="elh_student_phone" class="student_phone">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $student->phone->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($student->phone->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($student->phone->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($student->_email->Visible) { // email ?>
	<?php if ($student->SortUrl($student->_email) == "") { ?>
		<th data-name="_email" class="<?php echo $student->_email->HeaderCellClass() ?>"><div id="elh_student__email" class="student__email"><div class="ewTableHeaderCaption"><?php echo $student->_email->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_email" class="<?php echo $student->_email->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $student->SortUrl($student->_email) ?>',1);"><div id="elh_student__email" class="student__email">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $student->_email->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($student->_email->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($student->_email->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($student->image->Visible) { // image ?>
	<?php if ($student->SortUrl($student->image) == "") { ?>
		<th data-name="image" class="<?php echo $student->image->HeaderCellClass() ?>"><div id="elh_student_image" class="student_image"><div class="ewTableHeaderCaption"><?php echo $student->image->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="image" class="<?php echo $student->image->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $student->SortUrl($student->image) ?>',1);"><div id="elh_student_image" class="student_image">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $student->image->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($student->image->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($student->image->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php

// Render list options (header, right)
$student_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($student->ExportAll && $student->Export <> "") {
	$student_list->StopRec = $student_list->TotalRecs;
} else {

	// Set the last record to display
	if ($student_list->TotalRecs > $student_list->StartRec + $student_list->DisplayRecs - 1)
		$student_list->StopRec = $student_list->StartRec + $student_list->DisplayRecs - 1;
	else
		$student_list->StopRec = $student_list->TotalRecs;
}
$student_list->RecCnt = $student_list->StartRec - 1;
if ($student_list->Recordset && !$student_list->Recordset->EOF) {
	$student_list->Recordset->MoveFirst();
	$bSelectLimit = $student_list->UseSelectLimit;
	if (!$bSelectLimit && $student_list->StartRec > 1)
		$student_list->Recordset->Move($student_list->StartRec - 1);
} elseif (!$student->AllowAddDeleteRow && $student_list->StopRec == 0) {
	$student_list->StopRec = $student->GridAddRowCount;
}

// Initialize aggregate
$student->RowType = EW_ROWTYPE_AGGREGATEINIT;
$student->ResetAttrs();
$student_list->RenderRow();
while ($student_list->RecCnt < $student_list->StopRec) {
	$student_list->RecCnt++;
	if (intval($student_list->RecCnt) >= intval($student_list->StartRec)) {
		$student_list->RowCnt++;

		// Set up key count
		$student_list->KeyCount = $student_list->RowIndex;

		// Init row class and style
		$student->ResetAttrs();
		$student->CssClass = "";
		if ($student->CurrentAction == "gridadd") {
		} else {
			$student_list->LoadRowValues($student_list->Recordset); // Load row values
		}
		$student->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$student->RowAttrs = array_merge($student->RowAttrs, array('data-rowindex'=>$student_list->RowCnt, 'id'=>'r' . $student_list->RowCnt . '_student', 'data-rowtype'=>$student->RowType));

		// Render row
		$student_list->RenderRow();

		// Render list options
		$student_list->RenderListOptions();
?>
	<tr<?php echo $student->RowAttributes() ?>>
<?php

// Render list options (body, left)
$student_list->ListOptions->Render("body", "left", $student_list->RowCnt);
?>
	<?php if ($student->student_id->Visible) { // student_id ?>
		<td data-name="student_id"<?php echo $student->student_id->CellAttributes() ?>>
<span id="el<?php echo $student_list->RowCnt ?>_student_student_id" class="student_student_id">
<span<?php echo $student->student_id->ViewAttributes() ?>>
<?php echo $student->student_id->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($student->name->Visible) { // name ?>
		<td data-name="name"<?php echo $student->name->CellAttributes() ?>>
<span id="el<?php echo $student_list->RowCnt ?>_student_name" class="student_name">
<span<?php echo $student->name->ViewAttributes() ?>>
<?php echo $student->name->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($student->birthday->Visible) { // birthday ?>
		<td data-name="birthday"<?php echo $student->birthday->CellAttributes() ?>>
<span id="el<?php echo $student_list->RowCnt ?>_student_birthday" class="student_birthday">
<span<?php echo $student->birthday->ViewAttributes() ?>>
<?php echo $student->birthday->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($student->sex->Visible) { // sex ?>
		<td data-name="sex"<?php echo $student->sex->CellAttributes() ?>>
<span id="el<?php echo $student_list->RowCnt ?>_student_sex" class="student_sex">
<span<?php echo $student->sex->ViewAttributes() ?>>
<?php echo $student->sex->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($student->blood_group->Visible) { // blood_group ?>
		<td data-name="blood_group"<?php echo $student->blood_group->CellAttributes() ?>>
<span id="el<?php echo $student_list->RowCnt ?>_student_blood_group" class="student_blood_group">
<span<?php echo $student->blood_group->ViewAttributes() ?>>
<?php echo $student->blood_group->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($student->phone->Visible) { // phone ?>
		<td data-name="phone"<?php echo $student->phone->CellAttributes() ?>>
<span id="el<?php echo $student_list->RowCnt ?>_student_phone" class="student_phone">
<span<?php echo $student->phone->ViewAttributes() ?>>
<?php echo $student->phone->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($student->_email->Visible) { // email ?>
		<td data-name="_email"<?php echo $student->_email->CellAttributes() ?>>
<span id="el<?php echo $student_list->RowCnt ?>_student__email" class="student__email">
<span<?php echo $student->_email->ViewAttributes() ?>>
<?php echo $student->_email->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($student->image->Visible) { // image ?>
		<td data-name="image"<?php echo $student->image->CellAttributes() ?>>
<span id="el<?php echo $student_list->RowCnt ?>_student_image" class="student_image">
<span>
<?php echo ew_GetFileViewTag($student->image, $student->image->ListViewValue()) ?>
</span>
</span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$student_list->ListOptions->Render("body", "right", $student_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($student->CurrentAction <> "gridadd")
		$student_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($student->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($student_list->Recordset)
	$student_list->Recordset->Close();
?>
<div class="box-footer ewGridLowerPanel">
<?php if ($student->CurrentAction <> "gridadd" && $student->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($student_list->Pager)) $student_list->Pager = new cPrevNextPager($student_list->StartRec, $student_list->DisplayRecs, $student_list->TotalRecs, $student_list->AutoHidePager) ?>
<?php if ($student_list->Pager->RecordCount > 0 && $student_list->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($student_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $student_list->PageUrl() ?>start=<?php echo $student_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($student_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $student_list->PageUrl() ?>start=<?php echo $student_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $student_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($student_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $student_list->PageUrl() ?>start=<?php echo $student_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($student_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $student_list->PageUrl() ?>start=<?php echo $student_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $student_list->Pager->PageCount ?></span>
</div>
<?php } ?>
<?php if ($student_list->Pager->RecordCount > 0) { ?>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $student_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $student_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $student_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($student_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
</div>
</div>
<?php } ?>
<?php if ($student_list->TotalRecs == 0 && $student->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($student_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<script type="text/javascript">
fstudentlistsrch.FilterList = <?php echo $student_list->GetFilterList() ?>;
fstudentlistsrch.Init();
fstudentlist.Init();
</script>
<?php
$student_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$student_list->Page_Terminate();
?>
