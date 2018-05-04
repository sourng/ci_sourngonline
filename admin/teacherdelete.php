<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "teacherinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$teacher_delete = NULL; // Initialize page object first

class cteacher_delete extends cteacher {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = '{1F40EE59-17A4-45D3-96B6-AA2082B3D64A}';

	// Table name
	var $TableName = 'teacher';

	// Page object name
	var $PageObjName = 'teacher_delete';

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
			define("EW_PAGE_ID", 'delete', TRUE);

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

		// User profile
		$UserProfile = new cUserProfile();

		// Security
		$Security = new cAdvancedSecurity();
		if (!$Security->IsLoggedIn()) $Security->AutoLogin();
		$Security->LoadCurrentUserLevel($this->ProjectID . $this->TableName);
		if (!$Security->CanDelete()) {
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
		$this->phone->SetVisibility();
		$this->_email->SetVisibility();
		$this->photo->SetVisibility();
		$this->active->SetVisibility();

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
			ew_SaveDebugMsg();
			header("Location: " . $url);
		}
		exit();
	}
	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
	var $StartRec;
	var $TotalRecs = 0;
	var $RecCnt;
	var $RecKeys = array();
	var $Recordset;
	var $StartRowCnt = 1;
	var $RowCnt = 0;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Load key parameters
		$this->RecKeys = $this->GetRecordKeys(); // Load record keys
		$sFilter = $this->GetKeyFilter();
		if ($sFilter == "")
			$this->Page_Terminate("teacherlist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in teacher class, teacherinfo.php

		$this->CurrentFilter = $sFilter;

		// Get action
		if (@$_POST["a_delete"] <> "") {
			$this->CurrentAction = $_POST["a_delete"];
		} elseif (@$_GET["a_delete"] == "1") {
			$this->CurrentAction = "D"; // Delete record directly
		} else {
			$this->CurrentAction = "I"; // Display record
		}
		if ($this->CurrentAction == "D") {
			$this->SendEmail = TRUE; // Send email on delete success
			if ($this->DeleteRows()) { // Delete rows
				if ($this->getSuccessMessage() == "")
					$this->setSuccessMessage($Language->Phrase("DeleteSuccess")); // Set up success message
				$this->Page_Terminate($this->getReturnUrl()); // Return to caller
			} else { // Delete failed
				$this->CurrentAction = "I"; // Display record
			}
		}
		if ($this->CurrentAction == "I") { // Load records for display
			if ($this->Recordset = $this->LoadRecordset())
				$this->TotalRecs = $this->Recordset->RecordCount(); // Get record count
			if ($this->TotalRecs <= 0) { // No record found, exit
				if ($this->Recordset)
					$this->Recordset->Close();
				$this->Page_Terminate("teacherlist.php"); // Return to list
			}
		}
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

		// phone
		$this->phone->ViewValue = $this->phone->CurrentValue;
		$this->phone->ViewCustomAttributes = "";

		// email
		$this->_email->ViewValue = $this->_email->CurrentValue;
		$this->_email->ViewCustomAttributes = "";

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

			// phone
			$this->phone->LinkCustomAttributes = "";
			$this->phone->HrefValue = "";
			$this->phone->TooltipValue = "";

			// email
			$this->_email->LinkCustomAttributes = "";
			$this->_email->HrefValue = "";
			$this->_email->TooltipValue = "";

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
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	//
	// Delete records based on current filter
	//
	function DeleteRows() {
		global $Language, $Security;
		$DeleteRows = TRUE;
		$sSql = $this->SQL();
		$conn = &$this->Connection();
		$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE) {
			return FALSE;
		} elseif ($rs->EOF) {
			$this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
			$rs->Close();
			return FALSE;
		}
		$rows = ($rs) ? $rs->GetRows() : array();
		$conn->BeginTrans();

		// Clone old rows
		$rsold = $rows;
		if ($rs)
			$rs->Close();

		// Call row deleting event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$DeleteRows = $this->Row_Deleting($row);
				if (!$DeleteRows) break;
			}
		}
		if ($DeleteRows) {
			$sKey = "";
			foreach ($rsold as $row) {
				$sThisKey = "";
				if ($sThisKey <> "") $sThisKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
				$sThisKey .= $row['teacher_id'];
				$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
				$DeleteRows = $this->Delete($row); // Delete
				$conn->raiseErrorFn = '';
				if ($DeleteRows === FALSE)
					break;
				if ($sKey <> "") $sKey .= ", ";
				$sKey .= $sThisKey;
			}
		}
		if (!$DeleteRows) {

			// Set up error message
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("DeleteCancelled"));
			}
		}
		if ($DeleteRows) {
			$conn->CommitTrans(); // Commit the changes
		} else {
			$conn->RollbackTrans(); // Rollback changes
		}

		// Call Row Deleted event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$this->Row_Deleted($row);
			}
		}
		return $DeleteRows;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("teacherlist.php"), "", $this->TableVar, TRUE);
		$PageId = "delete";
		$Breadcrumb->Add("delete", $PageId, $url);
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
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($teacher_delete)) $teacher_delete = new cteacher_delete();

// Page init
$teacher_delete->Page_Init();

// Page main
$teacher_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$teacher_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "delete";
var CurrentForm = fteacherdelete = new ew_Form("fteacherdelete", "delete");

// Form_CustomValidate event
fteacherdelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fteacherdelete.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
fteacherdelete.Lists["x_sex"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fteacherdelete.Lists["x_sex"].Options = <?php echo json_encode($teacher_delete->sex->Options()) ?>;
fteacherdelete.Lists["x_active"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fteacherdelete.Lists["x_active"].Options = <?php echo json_encode($teacher_delete->active->Options()) ?>;

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $teacher_delete->ShowPageHeader(); ?>
<?php
$teacher_delete->ShowMessage();
?>
<form name="fteacherdelete" id="fteacherdelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($teacher_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $teacher_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="teacher">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($teacher_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="box ewBox ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { ?>table-responsive <?php } ?>ewGridMiddlePanel">
<table class="table ewTable">
	<thead>
	<tr class="ewTableHeader">
<?php if ($teacher->teacher_id->Visible) { // teacher_id ?>
		<th class="<?php echo $teacher->teacher_id->HeaderCellClass() ?>"><span id="elh_teacher_teacher_id" class="teacher_teacher_id"><?php echo $teacher->teacher_id->FldCaption() ?></span></th>
<?php } ?>
<?php if ($teacher->name->Visible) { // name ?>
		<th class="<?php echo $teacher->name->HeaderCellClass() ?>"><span id="elh_teacher_name" class="teacher_name"><?php echo $teacher->name->FldCaption() ?></span></th>
<?php } ?>
<?php if ($teacher->birthday->Visible) { // birthday ?>
		<th class="<?php echo $teacher->birthday->HeaderCellClass() ?>"><span id="elh_teacher_birthday" class="teacher_birthday"><?php echo $teacher->birthday->FldCaption() ?></span></th>
<?php } ?>
<?php if ($teacher->sex->Visible) { // sex ?>
		<th class="<?php echo $teacher->sex->HeaderCellClass() ?>"><span id="elh_teacher_sex" class="teacher_sex"><?php echo $teacher->sex->FldCaption() ?></span></th>
<?php } ?>
<?php if ($teacher->phone->Visible) { // phone ?>
		<th class="<?php echo $teacher->phone->HeaderCellClass() ?>"><span id="elh_teacher_phone" class="teacher_phone"><?php echo $teacher->phone->FldCaption() ?></span></th>
<?php } ?>
<?php if ($teacher->_email->Visible) { // email ?>
		<th class="<?php echo $teacher->_email->HeaderCellClass() ?>"><span id="elh_teacher__email" class="teacher__email"><?php echo $teacher->_email->FldCaption() ?></span></th>
<?php } ?>
<?php if ($teacher->photo->Visible) { // photo ?>
		<th class="<?php echo $teacher->photo->HeaderCellClass() ?>"><span id="elh_teacher_photo" class="teacher_photo"><?php echo $teacher->photo->FldCaption() ?></span></th>
<?php } ?>
<?php if ($teacher->active->Visible) { // active ?>
		<th class="<?php echo $teacher->active->HeaderCellClass() ?>"><span id="elh_teacher_active" class="teacher_active"><?php echo $teacher->active->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$teacher_delete->RecCnt = 0;
$i = 0;
while (!$teacher_delete->Recordset->EOF) {
	$teacher_delete->RecCnt++;
	$teacher_delete->RowCnt++;

	// Set row properties
	$teacher->ResetAttrs();
	$teacher->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$teacher_delete->LoadRowValues($teacher_delete->Recordset);

	// Render row
	$teacher_delete->RenderRow();
?>
	<tr<?php echo $teacher->RowAttributes() ?>>
<?php if ($teacher->teacher_id->Visible) { // teacher_id ?>
		<td<?php echo $teacher->teacher_id->CellAttributes() ?>>
<span id="el<?php echo $teacher_delete->RowCnt ?>_teacher_teacher_id" class="teacher_teacher_id">
<span<?php echo $teacher->teacher_id->ViewAttributes() ?>>
<?php echo $teacher->teacher_id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($teacher->name->Visible) { // name ?>
		<td<?php echo $teacher->name->CellAttributes() ?>>
<span id="el<?php echo $teacher_delete->RowCnt ?>_teacher_name" class="teacher_name">
<span<?php echo $teacher->name->ViewAttributes() ?>>
<?php echo $teacher->name->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($teacher->birthday->Visible) { // birthday ?>
		<td<?php echo $teacher->birthday->CellAttributes() ?>>
<span id="el<?php echo $teacher_delete->RowCnt ?>_teacher_birthday" class="teacher_birthday">
<span<?php echo $teacher->birthday->ViewAttributes() ?>>
<?php echo $teacher->birthday->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($teacher->sex->Visible) { // sex ?>
		<td<?php echo $teacher->sex->CellAttributes() ?>>
<span id="el<?php echo $teacher_delete->RowCnt ?>_teacher_sex" class="teacher_sex">
<span<?php echo $teacher->sex->ViewAttributes() ?>>
<?php echo $teacher->sex->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($teacher->phone->Visible) { // phone ?>
		<td<?php echo $teacher->phone->CellAttributes() ?>>
<span id="el<?php echo $teacher_delete->RowCnt ?>_teacher_phone" class="teacher_phone">
<span<?php echo $teacher->phone->ViewAttributes() ?>>
<?php echo $teacher->phone->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($teacher->_email->Visible) { // email ?>
		<td<?php echo $teacher->_email->CellAttributes() ?>>
<span id="el<?php echo $teacher_delete->RowCnt ?>_teacher__email" class="teacher__email">
<span<?php echo $teacher->_email->ViewAttributes() ?>>
<?php echo $teacher->_email->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($teacher->photo->Visible) { // photo ?>
		<td<?php echo $teacher->photo->CellAttributes() ?>>
<span id="el<?php echo $teacher_delete->RowCnt ?>_teacher_photo" class="teacher_photo">
<span>
<?php echo ew_GetFileViewTag($teacher->photo, $teacher->photo->ListViewValue()) ?>
</span>
</span>
</td>
<?php } ?>
<?php if ($teacher->active->Visible) { // active ?>
		<td<?php echo $teacher->active->CellAttributes() ?>>
<span id="el<?php echo $teacher_delete->RowCnt ?>_teacher_active" class="teacher_active">
<span<?php echo $teacher->active->ViewAttributes() ?>>
<?php echo $teacher->active->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$teacher_delete->Recordset->MoveNext();
}
$teacher_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</div>
<div>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $teacher_delete->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
</div>
</form>
<script type="text/javascript">
fteacherdelete.Init();
</script>
<?php
$teacher_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$teacher_delete->Page_Terminate();
?>
