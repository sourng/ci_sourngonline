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

$users_list = NULL; // Initialize page object first

class cusers_list extends cusers {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = '{1F40EE59-17A4-45D3-96B6-AA2082B3D64A}';

	// Table name
	var $TableName = 'users';

	// Page object name
	var $PageObjName = 'users_list';

	// Grid form hidden field names
	var $FormName = 'fuserslist';
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

		// Table object (users)
		if (!isset($GLOBALS["users"]) || get_class($GLOBALS["users"]) == "cusers") {
			$GLOBALS["users"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["users"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "usersadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "usersdelete.php";
		$this->MultiUpdateUrl = "usersupdate.php";

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

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
		$this->FilterOptions->TagClassName = "ewFilterOption fuserslistsrch";

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
		$this->id->SetVisibility();
		if ($this->IsAdd() || $this->IsCopy() || $this->IsGridAdd())
			$this->id->Visible = FALSE;
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
			$this->id->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->id->FormValue))
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
		$sFilterList = ew_Concat($sFilterList, $this->id->AdvancedSearch->ToJson(), ","); // Field id
		$sFilterList = ew_Concat($sFilterList, $this->oauth_provider->AdvancedSearch->ToJson(), ","); // Field oauth_provider
		$sFilterList = ew_Concat($sFilterList, $this->oauth_uid->AdvancedSearch->ToJson(), ","); // Field oauth_uid
		$sFilterList = ew_Concat($sFilterList, $this->first_name->AdvancedSearch->ToJson(), ","); // Field first_name
		$sFilterList = ew_Concat($sFilterList, $this->last_name->AdvancedSearch->ToJson(), ","); // Field last_name
		$sFilterList = ew_Concat($sFilterList, $this->_email->AdvancedSearch->ToJson(), ","); // Field email
		$sFilterList = ew_Concat($sFilterList, $this->gender->AdvancedSearch->ToJson(), ","); // Field gender
		$sFilterList = ew_Concat($sFilterList, $this->locale->AdvancedSearch->ToJson(), ","); // Field locale
		$sFilterList = ew_Concat($sFilterList, $this->cover->AdvancedSearch->ToJson(), ","); // Field cover
		$sFilterList = ew_Concat($sFilterList, $this->picture->AdvancedSearch->ToJson(), ","); // Field picture
		$sFilterList = ew_Concat($sFilterList, $this->link->AdvancedSearch->ToJson(), ","); // Field link
		$sFilterList = ew_Concat($sFilterList, $this->created->AdvancedSearch->ToJson(), ","); // Field created
		$sFilterList = ew_Concat($sFilterList, $this->modified->AdvancedSearch->ToJson(), ","); // Field modified
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
			$UserProfile->SetSearchFilters(CurrentUserName(), "fuserslistsrch", $filters);

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

		// Field id
		$this->id->AdvancedSearch->SearchValue = @$filter["x_id"];
		$this->id->AdvancedSearch->SearchOperator = @$filter["z_id"];
		$this->id->AdvancedSearch->SearchCondition = @$filter["v_id"];
		$this->id->AdvancedSearch->SearchValue2 = @$filter["y_id"];
		$this->id->AdvancedSearch->SearchOperator2 = @$filter["w_id"];
		$this->id->AdvancedSearch->Save();

		// Field oauth_provider
		$this->oauth_provider->AdvancedSearch->SearchValue = @$filter["x_oauth_provider"];
		$this->oauth_provider->AdvancedSearch->SearchOperator = @$filter["z_oauth_provider"];
		$this->oauth_provider->AdvancedSearch->SearchCondition = @$filter["v_oauth_provider"];
		$this->oauth_provider->AdvancedSearch->SearchValue2 = @$filter["y_oauth_provider"];
		$this->oauth_provider->AdvancedSearch->SearchOperator2 = @$filter["w_oauth_provider"];
		$this->oauth_provider->AdvancedSearch->Save();

		// Field oauth_uid
		$this->oauth_uid->AdvancedSearch->SearchValue = @$filter["x_oauth_uid"];
		$this->oauth_uid->AdvancedSearch->SearchOperator = @$filter["z_oauth_uid"];
		$this->oauth_uid->AdvancedSearch->SearchCondition = @$filter["v_oauth_uid"];
		$this->oauth_uid->AdvancedSearch->SearchValue2 = @$filter["y_oauth_uid"];
		$this->oauth_uid->AdvancedSearch->SearchOperator2 = @$filter["w_oauth_uid"];
		$this->oauth_uid->AdvancedSearch->Save();

		// Field first_name
		$this->first_name->AdvancedSearch->SearchValue = @$filter["x_first_name"];
		$this->first_name->AdvancedSearch->SearchOperator = @$filter["z_first_name"];
		$this->first_name->AdvancedSearch->SearchCondition = @$filter["v_first_name"];
		$this->first_name->AdvancedSearch->SearchValue2 = @$filter["y_first_name"];
		$this->first_name->AdvancedSearch->SearchOperator2 = @$filter["w_first_name"];
		$this->first_name->AdvancedSearch->Save();

		// Field last_name
		$this->last_name->AdvancedSearch->SearchValue = @$filter["x_last_name"];
		$this->last_name->AdvancedSearch->SearchOperator = @$filter["z_last_name"];
		$this->last_name->AdvancedSearch->SearchCondition = @$filter["v_last_name"];
		$this->last_name->AdvancedSearch->SearchValue2 = @$filter["y_last_name"];
		$this->last_name->AdvancedSearch->SearchOperator2 = @$filter["w_last_name"];
		$this->last_name->AdvancedSearch->Save();

		// Field email
		$this->_email->AdvancedSearch->SearchValue = @$filter["x__email"];
		$this->_email->AdvancedSearch->SearchOperator = @$filter["z__email"];
		$this->_email->AdvancedSearch->SearchCondition = @$filter["v__email"];
		$this->_email->AdvancedSearch->SearchValue2 = @$filter["y__email"];
		$this->_email->AdvancedSearch->SearchOperator2 = @$filter["w__email"];
		$this->_email->AdvancedSearch->Save();

		// Field gender
		$this->gender->AdvancedSearch->SearchValue = @$filter["x_gender"];
		$this->gender->AdvancedSearch->SearchOperator = @$filter["z_gender"];
		$this->gender->AdvancedSearch->SearchCondition = @$filter["v_gender"];
		$this->gender->AdvancedSearch->SearchValue2 = @$filter["y_gender"];
		$this->gender->AdvancedSearch->SearchOperator2 = @$filter["w_gender"];
		$this->gender->AdvancedSearch->Save();

		// Field locale
		$this->locale->AdvancedSearch->SearchValue = @$filter["x_locale"];
		$this->locale->AdvancedSearch->SearchOperator = @$filter["z_locale"];
		$this->locale->AdvancedSearch->SearchCondition = @$filter["v_locale"];
		$this->locale->AdvancedSearch->SearchValue2 = @$filter["y_locale"];
		$this->locale->AdvancedSearch->SearchOperator2 = @$filter["w_locale"];
		$this->locale->AdvancedSearch->Save();

		// Field cover
		$this->cover->AdvancedSearch->SearchValue = @$filter["x_cover"];
		$this->cover->AdvancedSearch->SearchOperator = @$filter["z_cover"];
		$this->cover->AdvancedSearch->SearchCondition = @$filter["v_cover"];
		$this->cover->AdvancedSearch->SearchValue2 = @$filter["y_cover"];
		$this->cover->AdvancedSearch->SearchOperator2 = @$filter["w_cover"];
		$this->cover->AdvancedSearch->Save();

		// Field picture
		$this->picture->AdvancedSearch->SearchValue = @$filter["x_picture"];
		$this->picture->AdvancedSearch->SearchOperator = @$filter["z_picture"];
		$this->picture->AdvancedSearch->SearchCondition = @$filter["v_picture"];
		$this->picture->AdvancedSearch->SearchValue2 = @$filter["y_picture"];
		$this->picture->AdvancedSearch->SearchOperator2 = @$filter["w_picture"];
		$this->picture->AdvancedSearch->Save();

		// Field link
		$this->link->AdvancedSearch->SearchValue = @$filter["x_link"];
		$this->link->AdvancedSearch->SearchOperator = @$filter["z_link"];
		$this->link->AdvancedSearch->SearchCondition = @$filter["v_link"];
		$this->link->AdvancedSearch->SearchValue2 = @$filter["y_link"];
		$this->link->AdvancedSearch->SearchOperator2 = @$filter["w_link"];
		$this->link->AdvancedSearch->Save();

		// Field created
		$this->created->AdvancedSearch->SearchValue = @$filter["x_created"];
		$this->created->AdvancedSearch->SearchOperator = @$filter["z_created"];
		$this->created->AdvancedSearch->SearchCondition = @$filter["v_created"];
		$this->created->AdvancedSearch->SearchValue2 = @$filter["y_created"];
		$this->created->AdvancedSearch->SearchOperator2 = @$filter["w_created"];
		$this->created->AdvancedSearch->Save();

		// Field modified
		$this->modified->AdvancedSearch->SearchValue = @$filter["x_modified"];
		$this->modified->AdvancedSearch->SearchOperator = @$filter["z_modified"];
		$this->modified->AdvancedSearch->SearchCondition = @$filter["v_modified"];
		$this->modified->AdvancedSearch->SearchValue2 = @$filter["y_modified"];
		$this->modified->AdvancedSearch->SearchOperator2 = @$filter["w_modified"];
		$this->modified->AdvancedSearch->Save();
		$this->BasicSearch->setKeyword(@$filter[EW_TABLE_BASIC_SEARCH]);
		$this->BasicSearch->setType(@$filter[EW_TABLE_BASIC_SEARCH_TYPE]);
	}

	// Return basic search SQL
	function BasicSearchSQL($arKeywords, $type) {
		$sWhere = "";
		$this->BuildBasicSearchSQL($sWhere, $this->oauth_provider, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->oauth_uid, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->first_name, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->last_name, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->_email, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->gender, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->locale, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->cover, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->picture, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->link, $arKeywords, $type);
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
			$this->UpdateSort($this->id); // id
			$this->UpdateSort($this->oauth_provider); // oauth_provider
			$this->UpdateSort($this->oauth_uid); // oauth_uid
			$this->UpdateSort($this->first_name); // first_name
			$this->UpdateSort($this->last_name); // last_name
			$this->UpdateSort($this->_email); // email
			$this->UpdateSort($this->gender); // gender
			$this->UpdateSort($this->locale); // locale
			$this->UpdateSort($this->cover); // cover
			$this->UpdateSort($this->picture); // picture
			$this->UpdateSort($this->link); // link
			$this->UpdateSort($this->created); // created
			$this->UpdateSort($this->modified); // modified
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
				$this->id->setSort("");
				$this->oauth_provider->setSort("");
				$this->oauth_uid->setSort("");
				$this->first_name->setSort("");
				$this->last_name->setSort("");
				$this->_email->setSort("");
				$this->gender->setSort("");
				$this->locale->setSort("");
				$this->cover->setSort("");
				$this->picture->setSort("");
				$this->link->setSort("");
				$this->created->setSort("");
				$this->modified->setSort("");
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
			$oListOpt->Body = "<a class=\"ewRowLink ewView\" title=\"" . $viewcaption . "\" data-caption=\"" . $viewcaption . "\" href=\"" . ew_HtmlEncode($this->ViewUrl) . "\">" . $Language->Phrase("ViewLink") . "</a>";
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
				$oListOpt->Body = "<a class=\"ewRowLink ewEdit\" title=\"" . $editcaption . "\" data-table=\"users\" data-caption=\"" . $editcaption . "\" href=\"javascript:void(0);\" onclick=\"ew_ModalDialogShow({lnk:this,btn:'SaveBtn',url:'" . ew_HtmlEncode($this->EditUrl) . "'});\">" . $Language->Phrase("EditLink") . "</a>";
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
				$oListOpt->Body = "<a class=\"ewRowLink ewCopy\" title=\"" . $copycaption . "\" data-table=\"users\" data-caption=\"" . $copycaption . "\" href=\"javascript:void(0);\" onclick=\"ew_ModalDialogShow({lnk:this,btn:'AddBtn',url:'" . ew_HtmlEncode($this->CopyUrl) . "'});\">" . $Language->Phrase("CopyLink") . "</a>";
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
		$oListOpt->Body = "<input type=\"checkbox\" name=\"key_m[]\" class=\"ewMultiSelect\" value=\"" . ew_HtmlEncode($this->id->CurrentValue) . "\" onclick=\"ew_ClickMultiCheckbox(event);\">";
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
			$item->Body = "<a class=\"ewAddEdit ewAdd\" title=\"" . $addcaption . "\" data-table=\"users\" data-caption=\"" . $addcaption . "\" href=\"javascript:void(0);\" onclick=\"ew_ModalDialogShow({lnk:this,btn:'AddBtn',url:'" . ew_HtmlEncode($this->AddUrl) . "'});\">" . $Language->Phrase("AddLink") . "</a>";
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
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"fuserslistsrch\" href=\"#\">" . $Language->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"fuserslistsrch\" href=\"#\">" . $Language->Phrase("DeleteFilter") . "</a>";
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
					$item->Body = "<a class=\"ewAction ewListAction\" title=\"" . ew_HtmlEncode($caption) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({f:document.fuserslist}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . "</a>";
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
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"fuserslistsrch\">" . $Language->Phrase("SearchLink") . "</button>";
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
				$rs = $conn->SelectLimit($sSql, $rowcnt, $offset, array("_hasOrderBy" => trim($this->getOrderBy()) || trim($this->getSessionOrderBy())));
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
		$row = array();
		$row['id'] = NULL;
		$row['oauth_provider'] = NULL;
		$row['oauth_uid'] = NULL;
		$row['first_name'] = NULL;
		$row['last_name'] = NULL;
		$row['email'] = NULL;
		$row['gender'] = NULL;
		$row['locale'] = NULL;
		$row['cover'] = NULL;
		$row['picture'] = NULL;
		$row['link'] = NULL;
		$row['created'] = NULL;
		$row['modified'] = NULL;
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
		$this->ViewUrl = $this->GetViewUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->InlineEditUrl = $this->GetInlineEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->InlineCopyUrl = $this->GetInlineCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();

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

			// id
			$this->id->LinkCustomAttributes = "";
			$this->id->HrefValue = "";
			$this->id->TooltipValue = "";

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
if (!isset($users_list)) $users_list = new cusers_list();

// Page init
$users_list->Page_Init();

// Page main
$users_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$users_list->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "list";
var CurrentForm = fuserslist = new ew_Form("fuserslist", "list");
fuserslist.FormKeyCountName = '<?php echo $users_list->FormKeyCountName ?>';

// Form_CustomValidate event
fuserslist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fuserslist.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
// Form object for search

var CurrentSearchForm = fuserslistsrch = new ew_Form("fuserslistsrch");
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php if ($users_list->TotalRecs > 0 && $users_list->ExportOptions->Visible()) { ?>
<?php $users_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($users_list->SearchOptions->Visible()) { ?>
<?php $users_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php if ($users_list->FilterOptions->Visible()) { ?>
<?php $users_list->FilterOptions->Render("body") ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php
	$bSelectLimit = $users_list->UseSelectLimit;
	if ($bSelectLimit) {
		if ($users_list->TotalRecs <= 0)
			$users_list->TotalRecs = $users->ListRecordCount();
	} else {
		if (!$users_list->Recordset && ($users_list->Recordset = $users_list->LoadRecordset()))
			$users_list->TotalRecs = $users_list->Recordset->RecordCount();
	}
	$users_list->StartRec = 1;
	if ($users_list->DisplayRecs <= 0 || ($users->Export <> "" && $users->ExportAll)) // Display all records
		$users_list->DisplayRecs = $users_list->TotalRecs;
	if (!($users->Export <> "" && $users->ExportAll))
		$users_list->SetupStartRec(); // Set up start record position
	if ($bSelectLimit)
		$users_list->Recordset = $users_list->LoadRecordset($users_list->StartRec-1, $users_list->DisplayRecs);

	// Set no record found message
	if ($users->CurrentAction == "" && $users_list->TotalRecs == 0) {
		if ($users_list->SearchWhere == "0=101")
			$users_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$users_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
$users_list->RenderOtherOptions();
?>
<?php if ($Security->IsLoggedIn()) { ?>
<?php if ($users->Export == "" && $users->CurrentAction == "") { ?>
<form name="fuserslistsrch" id="fuserslistsrch" class="form-inline ewForm ewExtSearchForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($users_list->SearchWhere <> "") ? " in" : " in"; ?>
<div id="fuserslistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="users">
	<div class="ewBasicSearch">
<div id="xsr_1" class="ewRow">
	<div class="ewQuickSearch input-group">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="form-control" value="<?php echo ew_HtmlEncode($users_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo ew_HtmlEncode($Language->Phrase("Search")) ?>">
	<input type="hidden" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" id="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="<?php echo ew_HtmlEncode($users_list->BasicSearch->getType()) ?>">
	<div class="input-group-btn">
		<button type="button" data-toggle="dropdown" class="btn btn-default"><span id="searchtype"><?php echo $users_list->BasicSearch->getTypeNameShort() ?></span><span class="caret"></span></button>
		<ul class="dropdown-menu pull-right" role="menu">
			<li<?php if ($users_list->BasicSearch->getType() == "") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this)"><?php echo $Language->Phrase("QuickSearchAuto") ?></a></li>
			<li<?php if ($users_list->BasicSearch->getType() == "=") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'=')"><?php echo $Language->Phrase("QuickSearchExact") ?></a></li>
			<li<?php if ($users_list->BasicSearch->getType() == "AND") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'AND')"><?php echo $Language->Phrase("QuickSearchAll") ?></a></li>
			<li<?php if ($users_list->BasicSearch->getType() == "OR") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'OR')"><?php echo $Language->Phrase("QuickSearchAny") ?></a></li>
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
<?php $users_list->ShowPageHeader(); ?>
<?php
$users_list->ShowMessage();
?>
<?php if ($users_list->TotalRecs > 0 || $users->CurrentAction <> "") { ?>
<div class="box ewBox ewGrid<?php if ($users_list->IsAddOrEdit()) { ?> ewGridAddEdit<?php } ?> users">
<form name="fuserslist" id="fuserslist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($users_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $users_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="users">
<div id="gmp_users" class="<?php if (ew_IsResponsiveLayout()) { ?>table-responsive <?php } ?>ewGridMiddlePanel">
<?php if ($users_list->TotalRecs > 0 || $users->CurrentAction == "gridedit") { ?>
<table id="tbl_userslist" class="table ewTable">
<thead>
	<tr class="ewTableHeader">
<?php

// Header row
$users_list->RowType = EW_ROWTYPE_HEADER;

// Render list options
$users_list->RenderListOptions();

// Render list options (header, left)
$users_list->ListOptions->Render("header", "left");
?>
<?php if ($users->id->Visible) { // id ?>
	<?php if ($users->SortUrl($users->id) == "") { ?>
		<th data-name="id" class="<?php echo $users->id->HeaderCellClass() ?>"><div id="elh_users_id" class="users_id"><div class="ewTableHeaderCaption"><?php echo $users->id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id" class="<?php echo $users->id->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $users->SortUrl($users->id) ?>',1);"><div id="elh_users_id" class="users_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $users->id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($users->id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($users->id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($users->oauth_provider->Visible) { // oauth_provider ?>
	<?php if ($users->SortUrl($users->oauth_provider) == "") { ?>
		<th data-name="oauth_provider" class="<?php echo $users->oauth_provider->HeaderCellClass() ?>"><div id="elh_users_oauth_provider" class="users_oauth_provider"><div class="ewTableHeaderCaption"><?php echo $users->oauth_provider->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="oauth_provider" class="<?php echo $users->oauth_provider->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $users->SortUrl($users->oauth_provider) ?>',1);"><div id="elh_users_oauth_provider" class="users_oauth_provider">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $users->oauth_provider->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($users->oauth_provider->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($users->oauth_provider->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($users->oauth_uid->Visible) { // oauth_uid ?>
	<?php if ($users->SortUrl($users->oauth_uid) == "") { ?>
		<th data-name="oauth_uid" class="<?php echo $users->oauth_uid->HeaderCellClass() ?>"><div id="elh_users_oauth_uid" class="users_oauth_uid"><div class="ewTableHeaderCaption"><?php echo $users->oauth_uid->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="oauth_uid" class="<?php echo $users->oauth_uid->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $users->SortUrl($users->oauth_uid) ?>',1);"><div id="elh_users_oauth_uid" class="users_oauth_uid">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $users->oauth_uid->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($users->oauth_uid->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($users->oauth_uid->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($users->first_name->Visible) { // first_name ?>
	<?php if ($users->SortUrl($users->first_name) == "") { ?>
		<th data-name="first_name" class="<?php echo $users->first_name->HeaderCellClass() ?>"><div id="elh_users_first_name" class="users_first_name"><div class="ewTableHeaderCaption"><?php echo $users->first_name->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="first_name" class="<?php echo $users->first_name->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $users->SortUrl($users->first_name) ?>',1);"><div id="elh_users_first_name" class="users_first_name">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $users->first_name->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($users->first_name->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($users->first_name->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($users->last_name->Visible) { // last_name ?>
	<?php if ($users->SortUrl($users->last_name) == "") { ?>
		<th data-name="last_name" class="<?php echo $users->last_name->HeaderCellClass() ?>"><div id="elh_users_last_name" class="users_last_name"><div class="ewTableHeaderCaption"><?php echo $users->last_name->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="last_name" class="<?php echo $users->last_name->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $users->SortUrl($users->last_name) ?>',1);"><div id="elh_users_last_name" class="users_last_name">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $users->last_name->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($users->last_name->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($users->last_name->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($users->_email->Visible) { // email ?>
	<?php if ($users->SortUrl($users->_email) == "") { ?>
		<th data-name="_email" class="<?php echo $users->_email->HeaderCellClass() ?>"><div id="elh_users__email" class="users__email"><div class="ewTableHeaderCaption"><?php echo $users->_email->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_email" class="<?php echo $users->_email->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $users->SortUrl($users->_email) ?>',1);"><div id="elh_users__email" class="users__email">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $users->_email->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($users->_email->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($users->_email->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($users->gender->Visible) { // gender ?>
	<?php if ($users->SortUrl($users->gender) == "") { ?>
		<th data-name="gender" class="<?php echo $users->gender->HeaderCellClass() ?>"><div id="elh_users_gender" class="users_gender"><div class="ewTableHeaderCaption"><?php echo $users->gender->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="gender" class="<?php echo $users->gender->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $users->SortUrl($users->gender) ?>',1);"><div id="elh_users_gender" class="users_gender">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $users->gender->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($users->gender->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($users->gender->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($users->locale->Visible) { // locale ?>
	<?php if ($users->SortUrl($users->locale) == "") { ?>
		<th data-name="locale" class="<?php echo $users->locale->HeaderCellClass() ?>"><div id="elh_users_locale" class="users_locale"><div class="ewTableHeaderCaption"><?php echo $users->locale->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="locale" class="<?php echo $users->locale->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $users->SortUrl($users->locale) ?>',1);"><div id="elh_users_locale" class="users_locale">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $users->locale->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($users->locale->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($users->locale->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($users->cover->Visible) { // cover ?>
	<?php if ($users->SortUrl($users->cover) == "") { ?>
		<th data-name="cover" class="<?php echo $users->cover->HeaderCellClass() ?>"><div id="elh_users_cover" class="users_cover"><div class="ewTableHeaderCaption"><?php echo $users->cover->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="cover" class="<?php echo $users->cover->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $users->SortUrl($users->cover) ?>',1);"><div id="elh_users_cover" class="users_cover">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $users->cover->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($users->cover->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($users->cover->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($users->picture->Visible) { // picture ?>
	<?php if ($users->SortUrl($users->picture) == "") { ?>
		<th data-name="picture" class="<?php echo $users->picture->HeaderCellClass() ?>"><div id="elh_users_picture" class="users_picture"><div class="ewTableHeaderCaption"><?php echo $users->picture->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="picture" class="<?php echo $users->picture->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $users->SortUrl($users->picture) ?>',1);"><div id="elh_users_picture" class="users_picture">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $users->picture->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($users->picture->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($users->picture->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($users->link->Visible) { // link ?>
	<?php if ($users->SortUrl($users->link) == "") { ?>
		<th data-name="link" class="<?php echo $users->link->HeaderCellClass() ?>"><div id="elh_users_link" class="users_link"><div class="ewTableHeaderCaption"><?php echo $users->link->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="link" class="<?php echo $users->link->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $users->SortUrl($users->link) ?>',1);"><div id="elh_users_link" class="users_link">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $users->link->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($users->link->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($users->link->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($users->created->Visible) { // created ?>
	<?php if ($users->SortUrl($users->created) == "") { ?>
		<th data-name="created" class="<?php echo $users->created->HeaderCellClass() ?>"><div id="elh_users_created" class="users_created"><div class="ewTableHeaderCaption"><?php echo $users->created->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="created" class="<?php echo $users->created->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $users->SortUrl($users->created) ?>',1);"><div id="elh_users_created" class="users_created">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $users->created->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($users->created->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($users->created->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($users->modified->Visible) { // modified ?>
	<?php if ($users->SortUrl($users->modified) == "") { ?>
		<th data-name="modified" class="<?php echo $users->modified->HeaderCellClass() ?>"><div id="elh_users_modified" class="users_modified"><div class="ewTableHeaderCaption"><?php echo $users->modified->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="modified" class="<?php echo $users->modified->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $users->SortUrl($users->modified) ?>',1);"><div id="elh_users_modified" class="users_modified">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $users->modified->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($users->modified->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($users->modified->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php

// Render list options (header, right)
$users_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($users->ExportAll && $users->Export <> "") {
	$users_list->StopRec = $users_list->TotalRecs;
} else {

	// Set the last record to display
	if ($users_list->TotalRecs > $users_list->StartRec + $users_list->DisplayRecs - 1)
		$users_list->StopRec = $users_list->StartRec + $users_list->DisplayRecs - 1;
	else
		$users_list->StopRec = $users_list->TotalRecs;
}
$users_list->RecCnt = $users_list->StartRec - 1;
if ($users_list->Recordset && !$users_list->Recordset->EOF) {
	$users_list->Recordset->MoveFirst();
	$bSelectLimit = $users_list->UseSelectLimit;
	if (!$bSelectLimit && $users_list->StartRec > 1)
		$users_list->Recordset->Move($users_list->StartRec - 1);
} elseif (!$users->AllowAddDeleteRow && $users_list->StopRec == 0) {
	$users_list->StopRec = $users->GridAddRowCount;
}

// Initialize aggregate
$users->RowType = EW_ROWTYPE_AGGREGATEINIT;
$users->ResetAttrs();
$users_list->RenderRow();
while ($users_list->RecCnt < $users_list->StopRec) {
	$users_list->RecCnt++;
	if (intval($users_list->RecCnt) >= intval($users_list->StartRec)) {
		$users_list->RowCnt++;

		// Set up key count
		$users_list->KeyCount = $users_list->RowIndex;

		// Init row class and style
		$users->ResetAttrs();
		$users->CssClass = "";
		if ($users->CurrentAction == "gridadd") {
		} else {
			$users_list->LoadRowValues($users_list->Recordset); // Load row values
		}
		$users->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$users->RowAttrs = array_merge($users->RowAttrs, array('data-rowindex'=>$users_list->RowCnt, 'id'=>'r' . $users_list->RowCnt . '_users', 'data-rowtype'=>$users->RowType));

		// Render row
		$users_list->RenderRow();

		// Render list options
		$users_list->RenderListOptions();
?>
	<tr<?php echo $users->RowAttributes() ?>>
<?php

// Render list options (body, left)
$users_list->ListOptions->Render("body", "left", $users_list->RowCnt);
?>
	<?php if ($users->id->Visible) { // id ?>
		<td data-name="id"<?php echo $users->id->CellAttributes() ?>>
<span id="el<?php echo $users_list->RowCnt ?>_users_id" class="users_id">
<span<?php echo $users->id->ViewAttributes() ?>>
<?php echo $users->id->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($users->oauth_provider->Visible) { // oauth_provider ?>
		<td data-name="oauth_provider"<?php echo $users->oauth_provider->CellAttributes() ?>>
<span id="el<?php echo $users_list->RowCnt ?>_users_oauth_provider" class="users_oauth_provider">
<span<?php echo $users->oauth_provider->ViewAttributes() ?>>
<?php echo $users->oauth_provider->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($users->oauth_uid->Visible) { // oauth_uid ?>
		<td data-name="oauth_uid"<?php echo $users->oauth_uid->CellAttributes() ?>>
<span id="el<?php echo $users_list->RowCnt ?>_users_oauth_uid" class="users_oauth_uid">
<span<?php echo $users->oauth_uid->ViewAttributes() ?>>
<?php echo $users->oauth_uid->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($users->first_name->Visible) { // first_name ?>
		<td data-name="first_name"<?php echo $users->first_name->CellAttributes() ?>>
<span id="el<?php echo $users_list->RowCnt ?>_users_first_name" class="users_first_name">
<span<?php echo $users->first_name->ViewAttributes() ?>>
<?php echo $users->first_name->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($users->last_name->Visible) { // last_name ?>
		<td data-name="last_name"<?php echo $users->last_name->CellAttributes() ?>>
<span id="el<?php echo $users_list->RowCnt ?>_users_last_name" class="users_last_name">
<span<?php echo $users->last_name->ViewAttributes() ?>>
<?php echo $users->last_name->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($users->_email->Visible) { // email ?>
		<td data-name="_email"<?php echo $users->_email->CellAttributes() ?>>
<span id="el<?php echo $users_list->RowCnt ?>_users__email" class="users__email">
<span<?php echo $users->_email->ViewAttributes() ?>>
<?php echo $users->_email->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($users->gender->Visible) { // gender ?>
		<td data-name="gender"<?php echo $users->gender->CellAttributes() ?>>
<span id="el<?php echo $users_list->RowCnt ?>_users_gender" class="users_gender">
<span<?php echo $users->gender->ViewAttributes() ?>>
<?php echo $users->gender->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($users->locale->Visible) { // locale ?>
		<td data-name="locale"<?php echo $users->locale->CellAttributes() ?>>
<span id="el<?php echo $users_list->RowCnt ?>_users_locale" class="users_locale">
<span<?php echo $users->locale->ViewAttributes() ?>>
<?php echo $users->locale->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($users->cover->Visible) { // cover ?>
		<td data-name="cover"<?php echo $users->cover->CellAttributes() ?>>
<span id="el<?php echo $users_list->RowCnt ?>_users_cover" class="users_cover">
<span<?php echo $users->cover->ViewAttributes() ?>>
<?php echo $users->cover->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($users->picture->Visible) { // picture ?>
		<td data-name="picture"<?php echo $users->picture->CellAttributes() ?>>
<span id="el<?php echo $users_list->RowCnt ?>_users_picture" class="users_picture">
<span<?php echo $users->picture->ViewAttributes() ?>>
<?php echo $users->picture->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($users->link->Visible) { // link ?>
		<td data-name="link"<?php echo $users->link->CellAttributes() ?>>
<span id="el<?php echo $users_list->RowCnt ?>_users_link" class="users_link">
<span<?php echo $users->link->ViewAttributes() ?>>
<?php echo $users->link->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($users->created->Visible) { // created ?>
		<td data-name="created"<?php echo $users->created->CellAttributes() ?>>
<span id="el<?php echo $users_list->RowCnt ?>_users_created" class="users_created">
<span<?php echo $users->created->ViewAttributes() ?>>
<?php echo $users->created->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($users->modified->Visible) { // modified ?>
		<td data-name="modified"<?php echo $users->modified->CellAttributes() ?>>
<span id="el<?php echo $users_list->RowCnt ?>_users_modified" class="users_modified">
<span<?php echo $users->modified->ViewAttributes() ?>>
<?php echo $users->modified->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$users_list->ListOptions->Render("body", "right", $users_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($users->CurrentAction <> "gridadd")
		$users_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($users->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($users_list->Recordset)
	$users_list->Recordset->Close();
?>
<div class="box-footer ewGridLowerPanel">
<?php if ($users->CurrentAction <> "gridadd" && $users->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($users_list->Pager)) $users_list->Pager = new cPrevNextPager($users_list->StartRec, $users_list->DisplayRecs, $users_list->TotalRecs, $users_list->AutoHidePager) ?>
<?php if ($users_list->Pager->RecordCount > 0 && $users_list->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($users_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $users_list->PageUrl() ?>start=<?php echo $users_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($users_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $users_list->PageUrl() ?>start=<?php echo $users_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $users_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($users_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $users_list->PageUrl() ?>start=<?php echo $users_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($users_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $users_list->PageUrl() ?>start=<?php echo $users_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $users_list->Pager->PageCount ?></span>
</div>
<?php } ?>
<?php if ($users_list->Pager->RecordCount > 0) { ?>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $users_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $users_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $users_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($users_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
</div>
</div>
<?php } ?>
<?php if ($users_list->TotalRecs == 0 && $users->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($users_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<script type="text/javascript">
fuserslistsrch.FilterList = <?php echo $users_list->GetFilterList() ?>;
fuserslistsrch.Init();
fuserslist.Init();
</script>
<?php
$users_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$users_list->Page_Terminate();
?>
