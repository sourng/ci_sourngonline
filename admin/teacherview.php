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

$teacher_view = NULL; // Initialize page object first

class cteacher_view extends cteacher {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = '{1F40EE59-17A4-45D3-96B6-AA2082B3D64A}';

	// Table name
	var $TableName = 'teacher';

	// Page object name
	var $PageObjName = 'teacher_view';

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

		// Table object (teacher)
		if (!isset($GLOBALS["teacher"]) || get_class($GLOBALS["teacher"]) == "cteacher") {
			$GLOBALS["teacher"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["teacher"];
		}
		$KeyUrl = "";
		if (@$_GET["teacher_id"] <> "") {
			$this->RecKey["teacher_id"] = $_GET["teacher_id"];
			$KeyUrl .= "&amp;teacher_id=" . urlencode($this->RecKey["teacher_id"]);
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
			define("EW_TABLE_NAME", 'teacher', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("teacherlist.php"));
			else
				$this->Page_Terminate(ew_GetUrl("login.php"));
		}

		// NOTE: Security object may be needed in other part of the script, skip set to Nothing
		// 
		// Security = null;
		// 

		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->teacher_id->SetVisibility();
		if ($this->IsAdd() || $this->IsCopy() || $this->IsGridAdd())
			$this->teacher_id->Visible = FALSE;
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
	var $subject_Count;
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
			if (@$_GET["teacher_id"] <> "") {
				$this->teacher_id->setQueryStringValue($_GET["teacher_id"]);
				$this->RecKey["teacher_id"] = $this->teacher_id->QueryStringValue;
			} elseif (@$_POST["teacher_id"] <> "") {
				$this->teacher_id->setFormValue($_POST["teacher_id"]);
				$this->RecKey["teacher_id"] = $this->teacher_id->FormValue;
			} else {
				$sReturnUrl = "teacherlist.php"; // Return to list
			}

			// Get action
			$this->CurrentAction = "I"; // Display form
			switch ($this->CurrentAction) {
				case "I": // Get a record to display
					if (!$this->LoadRow()) { // Load record based on key
						if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
							$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
						$sReturnUrl = "teacherlist.php"; // No matching record, return to list
					}
			}
		} else {
			$sReturnUrl = "teacherlist.php"; // Not page request, return to list
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

		// Set up detail parameters
		$this->SetupDetailParms();
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
		$option = &$options["detail"];
		$DetailTableLink = "";
		$DetailViewTblVar = "";
		$DetailCopyTblVar = "";
		$DetailEditTblVar = "";

		// "detail_subject"
		$item = &$option->Add("detail_subject");
		$body = $Language->Phrase("ViewPageDetailLink") . $Language->TablePhrase("subject", "TblCaption");
		$body .= str_replace("%c", $this->subject_Count, $Language->Phrase("DetailCount"));
		$body = "<a class=\"btn btn-default btn-sm ewRowLink ewDetail\" data-action=\"list\" href=\"" . ew_HtmlEncode("subjectlist.php?" . EW_TABLE_SHOW_MASTER . "=teacher&fk_teacher_id=" . urlencode(strval($this->teacher_id->CurrentValue)) . "") . "\">" . $body . "</a>";
		$links = "";
		if ($GLOBALS["subject_grid"] && $GLOBALS["subject_grid"]->DetailView && $Security->IsLoggedIn() && $Security->IsLoggedIn()) {
			$links .= "<li><a class=\"ewRowLink ewDetailView\" data-action=\"view\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailViewLink")) . "\" href=\"" . ew_HtmlEncode($this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=subject")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailViewLink")) . "</a></li>";
			if ($DetailViewTblVar <> "") $DetailViewTblVar .= ",";
			$DetailViewTblVar .= "subject";
		}
		if ($GLOBALS["subject_grid"] && $GLOBALS["subject_grid"]->DetailEdit && $Security->IsLoggedIn() && $Security->IsLoggedIn()) {
			$links .= "<li><a class=\"ewRowLink ewDetailEdit\" data-action=\"edit\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailEditLink")) . "\" href=\"" . ew_HtmlEncode($this->GetEditUrl(EW_TABLE_SHOW_DETAIL . "=subject")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailEditLink")) . "</a></li>";
			if ($DetailEditTblVar <> "") $DetailEditTblVar .= ",";
			$DetailEditTblVar .= "subject";
		}
		if ($GLOBALS["subject_grid"] && $GLOBALS["subject_grid"]->DetailAdd && $Security->IsLoggedIn() && $Security->IsLoggedIn()) {
			$links .= "<li><a class=\"ewRowLink ewDetailCopy\" data-action=\"add\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailCopyLink")) . "\" href=\"" . ew_HtmlEncode($this->GetCopyUrl(EW_TABLE_SHOW_DETAIL . "=subject")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailCopyLink")) . "</a></li>";
			if ($DetailCopyTblVar <> "") $DetailCopyTblVar .= ",";
			$DetailCopyTblVar .= "subject";
		}
		if ($links <> "") {
			$body .= "<button class=\"dropdown-toggle btn btn-default btn-sm ewDetail\" data-toggle=\"dropdown\"><b class=\"caret\"></b></button>";
			$body .= "<ul class=\"dropdown-menu\">". $links . "</ul>";
		}
		$body = "<div class=\"btn-group\">" . $body . "</div>";
		$item->Body = $body;
		$item->Visible = $Security->IsLoggedIn();
		if ($item->Visible) {
			if ($DetailTableLink <> "") $DetailTableLink .= ",";
			$DetailTableLink .= "subject";
		}
		if ($this->ShowMultipleDetails) $item->Visible = FALSE;

		// Multiple details
		if ($this->ShowMultipleDetails) {
			$body = $Language->Phrase("MultipleMasterDetails");
			$body = "<div class=\"btn-group\">";
			$links = "";
			if ($DetailViewTblVar <> "") {
				$links .= "<li><a class=\"ewRowLink ewDetailView\" data-action=\"view\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailViewLink")) . "\" href=\"" . ew_HtmlEncode($this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=" . $DetailViewTblVar)) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailViewLink")) . "</a></li>";
			}
			if ($DetailEditTblVar <> "") {
				$links .= "<li><a class=\"ewRowLink ewDetailEdit\" data-action=\"edit\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailEditLink")) . "\" href=\"" . ew_HtmlEncode($this->GetEditUrl(EW_TABLE_SHOW_DETAIL . "=" . $DetailEditTblVar)) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailEditLink")) . "</a></li>";
			}
			if ($DetailCopyTblVar <> "") {
				$links .= "<li><a class=\"ewRowLink ewDetailCopy\" data-action=\"add\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailCopyLink")) . "\" href=\"" . ew_HtmlEncode($this->GetCopyUrl(EW_TABLE_SHOW_DETAIL . "=" . $DetailCopyTblVar)) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailCopyLink")) . "</a></li>";
			}
			if ($links <> "") {
				$body .= "<button class=\"dropdown-toggle btn btn-default btn-sm ewMasterDetail\" title=\"" . ew_HtmlTitle($Language->Phrase("MultipleMasterDetails")) . "\" data-toggle=\"dropdown\">" . $Language->Phrase("MultipleMasterDetails") . "<b class=\"caret\"></b></button>";
				$body .= "<ul class=\"dropdown-menu ewMenu\">". $links . "</ul>";
			}
			$body .= "</div>";

			// Multiple details
			$oListOpt = &$option->Add("details");
			$oListOpt->Body = $body;
		}

		// Set up detail default
		$option = &$options["detail"];
		$options["detail"]->DropDownButtonPhrase = $Language->Phrase("ButtonDetails");
		$option->UseImageAndText = TRUE;
		$ar = explode(",", $DetailTableLink);
		$cnt = count($ar);
		$option->UseDropDownButton = ($cnt > 1);
		$option->UseButtonGroup = TRUE;
		$item = &$option->Add($option->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;

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
		if (!isset($GLOBALS["subject_grid"])) $GLOBALS["subject_grid"] = new csubject_grid;
		$sDetailFilter = $GLOBALS["subject"]->SqlDetailFilter_teacher();
		$sDetailFilter = str_replace("@teacher_id@", ew_AdjustSql($this->teacher_id->DbValue, "DB"), $sDetailFilter);
		$GLOBALS["subject"]->setCurrentMasterTable("teacher");
		$sDetailFilter = $GLOBALS["subject"]->ApplyUserIDFilters($sDetailFilter);
		$this->subject_Count = $GLOBALS["subject"]->LoadRecordCount($sDetailFilter);
	}

	// Return a row with default values
	function NewRow() {
		$row = array();
		$row['teacher_id'] = NULL;
		$row['name'] = NULL;
		$row['birthday'] = NULL;
		$row['sex'] = NULL;
		$row['religion'] = NULL;
		$row['blood_group'] = NULL;
		$row['address'] = NULL;
		$row['phone'] = NULL;
		$row['email'] = NULL;
		$row['password'] = NULL;
		$row['authentication_key'] = NULL;
		$row['photo'] = NULL;
		$row['active'] = NULL;
		$row['teacher_image'] = NULL;
		$row['twitter'] = NULL;
		$row['facebook'] = NULL;
		$row['google'] = NULL;
		$row['linkedin'] = NULL;
		$row['pinterest'] = NULL;
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

			// teacher_id
			$this->teacher_id->LinkCustomAttributes = "";
			$this->teacher_id->HrefValue = "";
			$this->teacher_id->TooltipValue = "";

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
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
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
				if ($GLOBALS["subject_grid"]->DetailView) {
					$GLOBALS["subject_grid"]->CurrentMode = "view";

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
if (!isset($teacher_view)) $teacher_view = new cteacher_view();

// Page init
$teacher_view->Page_Init();

// Page main
$teacher_view->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$teacher_view->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "view";
var CurrentForm = fteacherview = new ew_Form("fteacherview", "view");

// Form_CustomValidate event
fteacherview.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fteacherview.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
fteacherview.Lists["x_sex"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fteacherview.Lists["x_sex"].Options = <?php echo json_encode($teacher_view->sex->Options()) ?>;
fteacherview.Lists["x_active"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fteacherview.Lists["x_active"].Options = <?php echo json_encode($teacher_view->active->Options()) ?>;

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php $teacher_view->ExportOptions->Render("body") ?>
<?php
	foreach ($teacher_view->OtherOptions as &$option)
		$option->Render("body");
?>
<div class="clearfix"></div>
</div>
<?php $teacher_view->ShowPageHeader(); ?>
<?php
$teacher_view->ShowMessage();
?>
<form name="fteacherview" id="fteacherview" class="form-inline ewForm ewViewForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($teacher_view->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $teacher_view->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="teacher">
<input type="hidden" name="modal" value="<?php echo intval($teacher_view->IsModal) ?>">
<table class="table table-striped table-bordered table-hover table-condensed ewViewTable">
<?php if ($teacher->teacher_id->Visible) { // teacher_id ?>
	<tr id="r_teacher_id">
		<td class="col-sm-2"><span id="elh_teacher_teacher_id"><?php echo $teacher->teacher_id->FldCaption() ?></span></td>
		<td data-name="teacher_id"<?php echo $teacher->teacher_id->CellAttributes() ?>>
<span id="el_teacher_teacher_id" data-page="1">
<span<?php echo $teacher->teacher_id->ViewAttributes() ?>>
<?php echo $teacher->teacher_id->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($teacher->name->Visible) { // name ?>
	<tr id="r_name">
		<td class="col-sm-2"><span id="elh_teacher_name"><?php echo $teacher->name->FldCaption() ?></span></td>
		<td data-name="name"<?php echo $teacher->name->CellAttributes() ?>>
<span id="el_teacher_name" data-page="1">
<span<?php echo $teacher->name->ViewAttributes() ?>>
<?php echo $teacher->name->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($teacher->birthday->Visible) { // birthday ?>
	<tr id="r_birthday">
		<td class="col-sm-2"><span id="elh_teacher_birthday"><?php echo $teacher->birthday->FldCaption() ?></span></td>
		<td data-name="birthday"<?php echo $teacher->birthday->CellAttributes() ?>>
<span id="el_teacher_birthday" data-page="1">
<span<?php echo $teacher->birthday->ViewAttributes() ?>>
<?php echo $teacher->birthday->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($teacher->sex->Visible) { // sex ?>
	<tr id="r_sex">
		<td class="col-sm-2"><span id="elh_teacher_sex"><?php echo $teacher->sex->FldCaption() ?></span></td>
		<td data-name="sex"<?php echo $teacher->sex->CellAttributes() ?>>
<span id="el_teacher_sex" data-page="1">
<span<?php echo $teacher->sex->ViewAttributes() ?>>
<?php echo $teacher->sex->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($teacher->religion->Visible) { // religion ?>
	<tr id="r_religion">
		<td class="col-sm-2"><span id="elh_teacher_religion"><?php echo $teacher->religion->FldCaption() ?></span></td>
		<td data-name="religion"<?php echo $teacher->religion->CellAttributes() ?>>
<span id="el_teacher_religion" data-page="1">
<span<?php echo $teacher->religion->ViewAttributes() ?>>
<?php echo $teacher->religion->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($teacher->blood_group->Visible) { // blood_group ?>
	<tr id="r_blood_group">
		<td class="col-sm-2"><span id="elh_teacher_blood_group"><?php echo $teacher->blood_group->FldCaption() ?></span></td>
		<td data-name="blood_group"<?php echo $teacher->blood_group->CellAttributes() ?>>
<span id="el_teacher_blood_group" data-page="1">
<span<?php echo $teacher->blood_group->ViewAttributes() ?>>
<?php echo $teacher->blood_group->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($teacher->address->Visible) { // address ?>
	<tr id="r_address">
		<td class="col-sm-2"><span id="elh_teacher_address"><?php echo $teacher->address->FldCaption() ?></span></td>
		<td data-name="address"<?php echo $teacher->address->CellAttributes() ?>>
<span id="el_teacher_address" data-page="1">
<span<?php echo $teacher->address->ViewAttributes() ?>>
<?php echo $teacher->address->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($teacher->phone->Visible) { // phone ?>
	<tr id="r_phone">
		<td class="col-sm-2"><span id="elh_teacher_phone"><?php echo $teacher->phone->FldCaption() ?></span></td>
		<td data-name="phone"<?php echo $teacher->phone->CellAttributes() ?>>
<span id="el_teacher_phone" data-page="1">
<span<?php echo $teacher->phone->ViewAttributes() ?>>
<?php echo $teacher->phone->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($teacher->_email->Visible) { // email ?>
	<tr id="r__email">
		<td class="col-sm-2"><span id="elh_teacher__email"><?php echo $teacher->_email->FldCaption() ?></span></td>
		<td data-name="_email"<?php echo $teacher->_email->CellAttributes() ?>>
<span id="el_teacher__email" data-page="1">
<span<?php echo $teacher->_email->ViewAttributes() ?>>
<?php echo $teacher->_email->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($teacher->password->Visible) { // password ?>
	<tr id="r_password">
		<td class="col-sm-2"><span id="elh_teacher_password"><?php echo $teacher->password->FldCaption() ?></span></td>
		<td data-name="password"<?php echo $teacher->password->CellAttributes() ?>>
<span id="el_teacher_password" data-page="1">
<span<?php echo $teacher->password->ViewAttributes() ?>>
<?php echo $teacher->password->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($teacher->authentication_key->Visible) { // authentication_key ?>
	<tr id="r_authentication_key">
		<td class="col-sm-2"><span id="elh_teacher_authentication_key"><?php echo $teacher->authentication_key->FldCaption() ?></span></td>
		<td data-name="authentication_key"<?php echo $teacher->authentication_key->CellAttributes() ?>>
<span id="el_teacher_authentication_key" data-page="1">
<span<?php echo $teacher->authentication_key->ViewAttributes() ?>>
<?php echo $teacher->authentication_key->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($teacher->photo->Visible) { // photo ?>
	<tr id="r_photo">
		<td class="col-sm-2"><span id="elh_teacher_photo"><?php echo $teacher->photo->FldCaption() ?></span></td>
		<td data-name="photo"<?php echo $teacher->photo->CellAttributes() ?>>
<span id="el_teacher_photo" data-page="1">
<span>
<?php echo ew_GetFileViewTag($teacher->photo, $teacher->photo->ViewValue) ?>
</span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($teacher->active->Visible) { // active ?>
	<tr id="r_active">
		<td class="col-sm-2"><span id="elh_teacher_active"><?php echo $teacher->active->FldCaption() ?></span></td>
		<td data-name="active"<?php echo $teacher->active->CellAttributes() ?>>
<span id="el_teacher_active" data-page="1">
<span<?php echo $teacher->active->ViewAttributes() ?>>
<?php echo $teacher->active->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($teacher->teacher_image->Visible) { // teacher_image ?>
	<tr id="r_teacher_image">
		<td class="col-sm-2"><span id="elh_teacher_teacher_image"><?php echo $teacher->teacher_image->FldCaption() ?></span></td>
		<td data-name="teacher_image"<?php echo $teacher->teacher_image->CellAttributes() ?>>
<span id="el_teacher_teacher_image" data-page="1">
<span<?php echo $teacher->teacher_image->ViewAttributes() ?>>
<?php echo ew_GetFileViewTag($teacher->teacher_image, $teacher->teacher_image->ViewValue) ?>
</span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($teacher->twitter->Visible) { // twitter ?>
	<tr id="r_twitter">
		<td class="col-sm-2"><span id="elh_teacher_twitter"><?php echo $teacher->twitter->FldCaption() ?></span></td>
		<td data-name="twitter"<?php echo $teacher->twitter->CellAttributes() ?>>
<span id="el_teacher_twitter" data-page="1">
<span<?php echo $teacher->twitter->ViewAttributes() ?>>
<?php echo $teacher->twitter->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($teacher->facebook->Visible) { // facebook ?>
	<tr id="r_facebook">
		<td class="col-sm-2"><span id="elh_teacher_facebook"><?php echo $teacher->facebook->FldCaption() ?></span></td>
		<td data-name="facebook"<?php echo $teacher->facebook->CellAttributes() ?>>
<span id="el_teacher_facebook" data-page="1">
<span<?php echo $teacher->facebook->ViewAttributes() ?>>
<?php echo $teacher->facebook->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($teacher->google->Visible) { // google ?>
	<tr id="r_google">
		<td class="col-sm-2"><span id="elh_teacher_google"><?php echo $teacher->google->FldCaption() ?></span></td>
		<td data-name="google"<?php echo $teacher->google->CellAttributes() ?>>
<span id="el_teacher_google" data-page="1">
<span<?php echo $teacher->google->ViewAttributes() ?>>
<?php echo $teacher->google->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($teacher->linkedin->Visible) { // linkedin ?>
	<tr id="r_linkedin">
		<td class="col-sm-2"><span id="elh_teacher_linkedin"><?php echo $teacher->linkedin->FldCaption() ?></span></td>
		<td data-name="linkedin"<?php echo $teacher->linkedin->CellAttributes() ?>>
<span id="el_teacher_linkedin" data-page="1">
<span<?php echo $teacher->linkedin->ViewAttributes() ?>>
<?php echo $teacher->linkedin->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($teacher->pinterest->Visible) { // pinterest ?>
	<tr id="r_pinterest">
		<td class="col-sm-2"><span id="elh_teacher_pinterest"><?php echo $teacher->pinterest->FldCaption() ?></span></td>
		<td data-name="pinterest"<?php echo $teacher->pinterest->CellAttributes() ?>>
<span id="el_teacher_pinterest" data-page="1">
<span<?php echo $teacher->pinterest->ViewAttributes() ?>>
<?php echo $teacher->pinterest->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
<?php
	if (in_array("subject", explode(",", $teacher->getCurrentDetailTable())) && $subject->DetailView) {
?>
<?php if ($teacher->getCurrentDetailTable() <> "") { ?>
<h4 class="ewDetailCaption"><?php echo $Language->TablePhrase("subject", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "subjectgrid.php" ?>
<?php } ?>
</form>
<script type="text/javascript">
fteacherview.Init();
</script>
<?php
$teacher_view->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$teacher_view->Page_Terminate();
?>
