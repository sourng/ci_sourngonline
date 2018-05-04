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

$p_language_view = NULL; // Initialize page object first

class cp_language_view extends c_language {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = '{1F40EE59-17A4-45D3-96B6-AA2082B3D64A}';

	// Table name
	var $TableName = 'language';

	// Page object name
	var $PageObjName = 'p_language_view';

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

		// Table object (_language)
		if (!isset($GLOBALS["_language"]) || get_class($GLOBALS["_language"]) == "c_language") {
			$GLOBALS["_language"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["_language"];
		}
		$KeyUrl = "";
		if (@$_GET["phrase_id"] <> "") {
			$this->RecKey["phrase_id"] = $_GET["phrase_id"];
			$KeyUrl .= "&amp;phrase_id=" . urlencode($this->RecKey["phrase_id"]);
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
			define("EW_TABLE_NAME", 'language', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("_languagelist.php"));
			else
				$this->Page_Terminate(ew_GetUrl("login.php"));
		}

		// NOTE: Security object may be needed in other part of the script, skip set to Nothing
		// 
		// Security = null;
		// 

		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->phrase_id->SetVisibility();
		if ($this->IsAdd() || $this->IsCopy() || $this->IsGridAdd())
			$this->phrase_id->Visible = FALSE;
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
			if (@$_GET["phrase_id"] <> "") {
				$this->phrase_id->setQueryStringValue($_GET["phrase_id"]);
				$this->RecKey["phrase_id"] = $this->phrase_id->QueryStringValue;
			} elseif (@$_POST["phrase_id"] <> "") {
				$this->phrase_id->setFormValue($_POST["phrase_id"]);
				$this->RecKey["phrase_id"] = $this->phrase_id->FormValue;
			} else {
				$sReturnUrl = "_languagelist.php"; // Return to list
			}

			// Get action
			$this->CurrentAction = "I"; // Display form
			switch ($this->CurrentAction) {
				case "I": // Get a record to display
					if (!$this->LoadRow()) { // Load record based on key
						if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
							$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
						$sReturnUrl = "_languagelist.php"; // No matching record, return to list
					}
			}
		} else {
			$sReturnUrl = "_languagelist.php"; // Not page request, return to list
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
		$row = array();
		$row['phrase_id'] = NULL;
		$row['phrase'] = NULL;
		$row['english'] = NULL;
		$row['bengali'] = NULL;
		$row['spanish'] = NULL;
		$row['arabic'] = NULL;
		$row['dutch'] = NULL;
		$row['russian'] = NULL;
		$row['chinese'] = NULL;
		$row['turkish'] = NULL;
		$row['portuguese'] = NULL;
		$row['hungarian'] = NULL;
		$row['french'] = NULL;
		$row['greek'] = NULL;
		$row['german'] = NULL;
		$row['italian'] = NULL;
		$row['thai'] = NULL;
		$row['urdu'] = NULL;
		$row['hindi'] = NULL;
		$row['latin'] = NULL;
		$row['indonesian'] = NULL;
		$row['japanese'] = NULL;
		$row['korean'] = NULL;
		$row['ខ្មែរ'] = NULL;
		$row['Khmer'] = NULL;
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

			// phrase_id
			$this->phrase_id->LinkCustomAttributes = "";
			$this->phrase_id->HrefValue = "";
			$this->phrase_id->TooltipValue = "";

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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("_languagelist.php"), "", $this->TableVar, TRUE);
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
if (!isset($p_language_view)) $p_language_view = new cp_language_view();

// Page init
$p_language_view->Page_Init();

// Page main
$p_language_view->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$p_language_view->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "view";
var CurrentForm = f_languageview = new ew_Form("f_languageview", "view");

// Form_CustomValidate event
f_languageview.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
f_languageview.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php $p_language_view->ExportOptions->Render("body") ?>
<?php
	foreach ($p_language_view->OtherOptions as &$option)
		$option->Render("body");
?>
<div class="clearfix"></div>
</div>
<?php $p_language_view->ShowPageHeader(); ?>
<?php
$p_language_view->ShowMessage();
?>
<form name="f_languageview" id="f_languageview" class="form-inline ewForm ewViewForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($p_language_view->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $p_language_view->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="_language">
<input type="hidden" name="modal" value="<?php echo intval($p_language_view->IsModal) ?>">
<table class="table table-striped table-bordered table-hover table-condensed ewViewTable">
<?php if ($_language->phrase_id->Visible) { // phrase_id ?>
	<tr id="r_phrase_id">
		<td class="col-sm-2"><span id="elh__language_phrase_id"><?php echo $_language->phrase_id->FldCaption() ?></span></td>
		<td data-name="phrase_id"<?php echo $_language->phrase_id->CellAttributes() ?>>
<span id="el__language_phrase_id">
<span<?php echo $_language->phrase_id->ViewAttributes() ?>>
<?php echo $_language->phrase_id->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($_language->phrase->Visible) { // phrase ?>
	<tr id="r_phrase">
		<td class="col-sm-2"><span id="elh__language_phrase"><?php echo $_language->phrase->FldCaption() ?></span></td>
		<td data-name="phrase"<?php echo $_language->phrase->CellAttributes() ?>>
<span id="el__language_phrase">
<span<?php echo $_language->phrase->ViewAttributes() ?>>
<?php echo $_language->phrase->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($_language->english->Visible) { // english ?>
	<tr id="r_english">
		<td class="col-sm-2"><span id="elh__language_english"><?php echo $_language->english->FldCaption() ?></span></td>
		<td data-name="english"<?php echo $_language->english->CellAttributes() ?>>
<span id="el__language_english">
<span<?php echo $_language->english->ViewAttributes() ?>>
<?php echo $_language->english->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($_language->bengali->Visible) { // bengali ?>
	<tr id="r_bengali">
		<td class="col-sm-2"><span id="elh__language_bengali"><?php echo $_language->bengali->FldCaption() ?></span></td>
		<td data-name="bengali"<?php echo $_language->bengali->CellAttributes() ?>>
<span id="el__language_bengali">
<span<?php echo $_language->bengali->ViewAttributes() ?>>
<?php echo $_language->bengali->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($_language->spanish->Visible) { // spanish ?>
	<tr id="r_spanish">
		<td class="col-sm-2"><span id="elh__language_spanish"><?php echo $_language->spanish->FldCaption() ?></span></td>
		<td data-name="spanish"<?php echo $_language->spanish->CellAttributes() ?>>
<span id="el__language_spanish">
<span<?php echo $_language->spanish->ViewAttributes() ?>>
<?php echo $_language->spanish->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($_language->arabic->Visible) { // arabic ?>
	<tr id="r_arabic">
		<td class="col-sm-2"><span id="elh__language_arabic"><?php echo $_language->arabic->FldCaption() ?></span></td>
		<td data-name="arabic"<?php echo $_language->arabic->CellAttributes() ?>>
<span id="el__language_arabic">
<span<?php echo $_language->arabic->ViewAttributes() ?>>
<?php echo $_language->arabic->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($_language->dutch->Visible) { // dutch ?>
	<tr id="r_dutch">
		<td class="col-sm-2"><span id="elh__language_dutch"><?php echo $_language->dutch->FldCaption() ?></span></td>
		<td data-name="dutch"<?php echo $_language->dutch->CellAttributes() ?>>
<span id="el__language_dutch">
<span<?php echo $_language->dutch->ViewAttributes() ?>>
<?php echo $_language->dutch->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($_language->russian->Visible) { // russian ?>
	<tr id="r_russian">
		<td class="col-sm-2"><span id="elh__language_russian"><?php echo $_language->russian->FldCaption() ?></span></td>
		<td data-name="russian"<?php echo $_language->russian->CellAttributes() ?>>
<span id="el__language_russian">
<span<?php echo $_language->russian->ViewAttributes() ?>>
<?php echo $_language->russian->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($_language->chinese->Visible) { // chinese ?>
	<tr id="r_chinese">
		<td class="col-sm-2"><span id="elh__language_chinese"><?php echo $_language->chinese->FldCaption() ?></span></td>
		<td data-name="chinese"<?php echo $_language->chinese->CellAttributes() ?>>
<span id="el__language_chinese">
<span<?php echo $_language->chinese->ViewAttributes() ?>>
<?php echo $_language->chinese->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($_language->turkish->Visible) { // turkish ?>
	<tr id="r_turkish">
		<td class="col-sm-2"><span id="elh__language_turkish"><?php echo $_language->turkish->FldCaption() ?></span></td>
		<td data-name="turkish"<?php echo $_language->turkish->CellAttributes() ?>>
<span id="el__language_turkish">
<span<?php echo $_language->turkish->ViewAttributes() ?>>
<?php echo $_language->turkish->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($_language->portuguese->Visible) { // portuguese ?>
	<tr id="r_portuguese">
		<td class="col-sm-2"><span id="elh__language_portuguese"><?php echo $_language->portuguese->FldCaption() ?></span></td>
		<td data-name="portuguese"<?php echo $_language->portuguese->CellAttributes() ?>>
<span id="el__language_portuguese">
<span<?php echo $_language->portuguese->ViewAttributes() ?>>
<?php echo $_language->portuguese->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($_language->hungarian->Visible) { // hungarian ?>
	<tr id="r_hungarian">
		<td class="col-sm-2"><span id="elh__language_hungarian"><?php echo $_language->hungarian->FldCaption() ?></span></td>
		<td data-name="hungarian"<?php echo $_language->hungarian->CellAttributes() ?>>
<span id="el__language_hungarian">
<span<?php echo $_language->hungarian->ViewAttributes() ?>>
<?php echo $_language->hungarian->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($_language->french->Visible) { // french ?>
	<tr id="r_french">
		<td class="col-sm-2"><span id="elh__language_french"><?php echo $_language->french->FldCaption() ?></span></td>
		<td data-name="french"<?php echo $_language->french->CellAttributes() ?>>
<span id="el__language_french">
<span<?php echo $_language->french->ViewAttributes() ?>>
<?php echo $_language->french->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($_language->greek->Visible) { // greek ?>
	<tr id="r_greek">
		<td class="col-sm-2"><span id="elh__language_greek"><?php echo $_language->greek->FldCaption() ?></span></td>
		<td data-name="greek"<?php echo $_language->greek->CellAttributes() ?>>
<span id="el__language_greek">
<span<?php echo $_language->greek->ViewAttributes() ?>>
<?php echo $_language->greek->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($_language->german->Visible) { // german ?>
	<tr id="r_german">
		<td class="col-sm-2"><span id="elh__language_german"><?php echo $_language->german->FldCaption() ?></span></td>
		<td data-name="german"<?php echo $_language->german->CellAttributes() ?>>
<span id="el__language_german">
<span<?php echo $_language->german->ViewAttributes() ?>>
<?php echo $_language->german->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($_language->italian->Visible) { // italian ?>
	<tr id="r_italian">
		<td class="col-sm-2"><span id="elh__language_italian"><?php echo $_language->italian->FldCaption() ?></span></td>
		<td data-name="italian"<?php echo $_language->italian->CellAttributes() ?>>
<span id="el__language_italian">
<span<?php echo $_language->italian->ViewAttributes() ?>>
<?php echo $_language->italian->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($_language->thai->Visible) { // thai ?>
	<tr id="r_thai">
		<td class="col-sm-2"><span id="elh__language_thai"><?php echo $_language->thai->FldCaption() ?></span></td>
		<td data-name="thai"<?php echo $_language->thai->CellAttributes() ?>>
<span id="el__language_thai">
<span<?php echo $_language->thai->ViewAttributes() ?>>
<?php echo $_language->thai->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($_language->urdu->Visible) { // urdu ?>
	<tr id="r_urdu">
		<td class="col-sm-2"><span id="elh__language_urdu"><?php echo $_language->urdu->FldCaption() ?></span></td>
		<td data-name="urdu"<?php echo $_language->urdu->CellAttributes() ?>>
<span id="el__language_urdu">
<span<?php echo $_language->urdu->ViewAttributes() ?>>
<?php echo $_language->urdu->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($_language->hindi->Visible) { // hindi ?>
	<tr id="r_hindi">
		<td class="col-sm-2"><span id="elh__language_hindi"><?php echo $_language->hindi->FldCaption() ?></span></td>
		<td data-name="hindi"<?php echo $_language->hindi->CellAttributes() ?>>
<span id="el__language_hindi">
<span<?php echo $_language->hindi->ViewAttributes() ?>>
<?php echo $_language->hindi->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($_language->latin->Visible) { // latin ?>
	<tr id="r_latin">
		<td class="col-sm-2"><span id="elh__language_latin"><?php echo $_language->latin->FldCaption() ?></span></td>
		<td data-name="latin"<?php echo $_language->latin->CellAttributes() ?>>
<span id="el__language_latin">
<span<?php echo $_language->latin->ViewAttributes() ?>>
<?php echo $_language->latin->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($_language->indonesian->Visible) { // indonesian ?>
	<tr id="r_indonesian">
		<td class="col-sm-2"><span id="elh__language_indonesian"><?php echo $_language->indonesian->FldCaption() ?></span></td>
		<td data-name="indonesian"<?php echo $_language->indonesian->CellAttributes() ?>>
<span id="el__language_indonesian">
<span<?php echo $_language->indonesian->ViewAttributes() ?>>
<?php echo $_language->indonesian->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($_language->japanese->Visible) { // japanese ?>
	<tr id="r_japanese">
		<td class="col-sm-2"><span id="elh__language_japanese"><?php echo $_language->japanese->FldCaption() ?></span></td>
		<td data-name="japanese"<?php echo $_language->japanese->CellAttributes() ?>>
<span id="el__language_japanese">
<span<?php echo $_language->japanese->ViewAttributes() ?>>
<?php echo $_language->japanese->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($_language->korean->Visible) { // korean ?>
	<tr id="r_korean">
		<td class="col-sm-2"><span id="elh__language_korean"><?php echo $_language->korean->FldCaption() ?></span></td>
		<td data-name="korean"<?php echo $_language->korean->CellAttributes() ?>>
<span id="el__language_korean">
<span<?php echo $_language->korean->ViewAttributes() ?>>
<?php echo $_language->korean->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($_language->_178117D2179817C2179A->Visible) { // ខ្មែរ ?>
	<tr id="r__178117D2179817C2179A">
		<td class="col-sm-2"><span id="elh__language__178117D2179817C2179A"><?php echo $_language->_178117D2179817C2179A->FldCaption() ?></span></td>
		<td data-name="_178117D2179817C2179A"<?php echo $_language->_178117D2179817C2179A->CellAttributes() ?>>
<span id="el__language__178117D2179817C2179A">
<span<?php echo $_language->_178117D2179817C2179A->ViewAttributes() ?>>
<?php echo $_language->_178117D2179817C2179A->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($_language->Khmer->Visible) { // Khmer ?>
	<tr id="r_Khmer">
		<td class="col-sm-2"><span id="elh__language_Khmer"><?php echo $_language->Khmer->FldCaption() ?></span></td>
		<td data-name="Khmer"<?php echo $_language->Khmer->CellAttributes() ?>>
<span id="el__language_Khmer">
<span<?php echo $_language->Khmer->ViewAttributes() ?>>
<?php echo $_language->Khmer->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
</form>
<script type="text/javascript">
f_languageview.Init();
</script>
<?php
$p_language_view->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$p_language_view->Page_Terminate();
?>
