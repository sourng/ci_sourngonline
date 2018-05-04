<?php

// Global variable for table object
$_language = NULL;

//
// Table class for language
//
class c_language extends cTable {
	var $phrase_id;
	var $phrase;
	var $english;
	var $bengali;
	var $spanish;
	var $arabic;
	var $dutch;
	var $russian;
	var $chinese;
	var $turkish;
	var $portuguese;
	var $hungarian;
	var $french;
	var $greek;
	var $german;
	var $italian;
	var $thai;
	var $urdu;
	var $hindi;
	var $latin;
	var $indonesian;
	var $japanese;
	var $korean;
	var $_178117D2179817C2179A;
	var $Khmer;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = '_language';
		$this->TableName = 'language';
		$this->TableType = 'TABLE';

		// Update Table
		$this->UpdateTable = "`language`";
		$this->DBID = 'DB';
		$this->ExportAll = TRUE;
		$this->ExportPageBreakCount = 0; // Page break per every n record (PDF only)
		$this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)
		$this->ExportPageSize = "a4"; // Page size (PDF only)
		$this->ExportExcelPageOrientation = ""; // Page orientation (PHPExcel only)
		$this->ExportExcelPageSize = ""; // Page size (PHPExcel only)
		$this->ExportWordPageOrientation = "portrait"; // Page orientation (PHPWord only)
		$this->ExportWordColumnWidth = NULL; // Cell width (PHPWord only)
		$this->DetailAdd = FALSE; // Allow detail add
		$this->DetailEdit = FALSE; // Allow detail edit
		$this->DetailView = FALSE; // Allow detail view
		$this->ShowMultipleDetails = FALSE; // Show multiple details
		$this->GridAddRowCount = 5;
		$this->AllowAddDeleteRow = TRUE; // Allow add/delete row
		$this->UserIDAllowSecurity = 0; // User ID Allow
		$this->BasicSearch = new cBasicSearch($this->TableVar);

		// phrase_id
		$this->phrase_id = new cField('_language', 'language', 'x_phrase_id', 'phrase_id', '`phrase_id`', '`phrase_id`', 3, -1, FALSE, '`phrase_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'NO');
		$this->phrase_id->Sortable = TRUE; // Allow sort
		$this->phrase_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['phrase_id'] = &$this->phrase_id;

		// phrase
		$this->phrase = new cField('_language', 'language', 'x_phrase', 'phrase', '`phrase`', '`phrase`', 201, -1, FALSE, '`phrase`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->phrase->Sortable = TRUE; // Allow sort
		$this->fields['phrase'] = &$this->phrase;

		// english
		$this->english = new cField('_language', 'language', 'x_english', 'english', '`english`', '`english`', 201, -1, FALSE, '`english`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->english->Sortable = TRUE; // Allow sort
		$this->fields['english'] = &$this->english;

		// bengali
		$this->bengali = new cField('_language', 'language', 'x_bengali', 'bengali', '`bengali`', '`bengali`', 201, -1, FALSE, '`bengali`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->bengali->Sortable = TRUE; // Allow sort
		$this->fields['bengali'] = &$this->bengali;

		// spanish
		$this->spanish = new cField('_language', 'language', 'x_spanish', 'spanish', '`spanish`', '`spanish`', 201, -1, FALSE, '`spanish`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->spanish->Sortable = TRUE; // Allow sort
		$this->fields['spanish'] = &$this->spanish;

		// arabic
		$this->arabic = new cField('_language', 'language', 'x_arabic', 'arabic', '`arabic`', '`arabic`', 201, -1, FALSE, '`arabic`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->arabic->Sortable = TRUE; // Allow sort
		$this->fields['arabic'] = &$this->arabic;

		// dutch
		$this->dutch = new cField('_language', 'language', 'x_dutch', 'dutch', '`dutch`', '`dutch`', 201, -1, FALSE, '`dutch`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->dutch->Sortable = TRUE; // Allow sort
		$this->fields['dutch'] = &$this->dutch;

		// russian
		$this->russian = new cField('_language', 'language', 'x_russian', 'russian', '`russian`', '`russian`', 201, -1, FALSE, '`russian`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->russian->Sortable = TRUE; // Allow sort
		$this->fields['russian'] = &$this->russian;

		// chinese
		$this->chinese = new cField('_language', 'language', 'x_chinese', 'chinese', '`chinese`', '`chinese`', 201, -1, FALSE, '`chinese`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->chinese->Sortable = TRUE; // Allow sort
		$this->fields['chinese'] = &$this->chinese;

		// turkish
		$this->turkish = new cField('_language', 'language', 'x_turkish', 'turkish', '`turkish`', '`turkish`', 201, -1, FALSE, '`turkish`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->turkish->Sortable = TRUE; // Allow sort
		$this->fields['turkish'] = &$this->turkish;

		// portuguese
		$this->portuguese = new cField('_language', 'language', 'x_portuguese', 'portuguese', '`portuguese`', '`portuguese`', 201, -1, FALSE, '`portuguese`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->portuguese->Sortable = TRUE; // Allow sort
		$this->fields['portuguese'] = &$this->portuguese;

		// hungarian
		$this->hungarian = new cField('_language', 'language', 'x_hungarian', 'hungarian', '`hungarian`', '`hungarian`', 201, -1, FALSE, '`hungarian`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->hungarian->Sortable = TRUE; // Allow sort
		$this->fields['hungarian'] = &$this->hungarian;

		// french
		$this->french = new cField('_language', 'language', 'x_french', 'french', '`french`', '`french`', 201, -1, FALSE, '`french`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->french->Sortable = TRUE; // Allow sort
		$this->fields['french'] = &$this->french;

		// greek
		$this->greek = new cField('_language', 'language', 'x_greek', 'greek', '`greek`', '`greek`', 201, -1, FALSE, '`greek`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->greek->Sortable = TRUE; // Allow sort
		$this->fields['greek'] = &$this->greek;

		// german
		$this->german = new cField('_language', 'language', 'x_german', 'german', '`german`', '`german`', 201, -1, FALSE, '`german`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->german->Sortable = TRUE; // Allow sort
		$this->fields['german'] = &$this->german;

		// italian
		$this->italian = new cField('_language', 'language', 'x_italian', 'italian', '`italian`', '`italian`', 201, -1, FALSE, '`italian`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->italian->Sortable = TRUE; // Allow sort
		$this->fields['italian'] = &$this->italian;

		// thai
		$this->thai = new cField('_language', 'language', 'x_thai', 'thai', '`thai`', '`thai`', 201, -1, FALSE, '`thai`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->thai->Sortable = TRUE; // Allow sort
		$this->fields['thai'] = &$this->thai;

		// urdu
		$this->urdu = new cField('_language', 'language', 'x_urdu', 'urdu', '`urdu`', '`urdu`', 201, -1, FALSE, '`urdu`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->urdu->Sortable = TRUE; // Allow sort
		$this->fields['urdu'] = &$this->urdu;

		// hindi
		$this->hindi = new cField('_language', 'language', 'x_hindi', 'hindi', '`hindi`', '`hindi`', 201, -1, FALSE, '`hindi`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->hindi->Sortable = TRUE; // Allow sort
		$this->fields['hindi'] = &$this->hindi;

		// latin
		$this->latin = new cField('_language', 'language', 'x_latin', 'latin', '`latin`', '`latin`', 201, -1, FALSE, '`latin`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->latin->Sortable = TRUE; // Allow sort
		$this->fields['latin'] = &$this->latin;

		// indonesian
		$this->indonesian = new cField('_language', 'language', 'x_indonesian', 'indonesian', '`indonesian`', '`indonesian`', 201, -1, FALSE, '`indonesian`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->indonesian->Sortable = TRUE; // Allow sort
		$this->fields['indonesian'] = &$this->indonesian;

		// japanese
		$this->japanese = new cField('_language', 'language', 'x_japanese', 'japanese', '`japanese`', '`japanese`', 201, -1, FALSE, '`japanese`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->japanese->Sortable = TRUE; // Allow sort
		$this->fields['japanese'] = &$this->japanese;

		// korean
		$this->korean = new cField('_language', 'language', 'x_korean', 'korean', '`korean`', '`korean`', 201, -1, FALSE, '`korean`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->korean->Sortable = TRUE; // Allow sort
		$this->fields['korean'] = &$this->korean;

		// ខ្មែរ
		$this->_178117D2179817C2179A = new cField('_language', 'language', 'x__178117D2179817C2179A', 'ខ្មែរ', '`ខ្មែរ`', '`ខ្មែរ`', 201, -1, FALSE, '`ខ្មែរ`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->_178117D2179817C2179A->Sortable = TRUE; // Allow sort
		$this->fields['ខ្មែរ'] = &$this->_178117D2179817C2179A;

		// Khmer
		$this->Khmer = new cField('_language', 'language', 'x_Khmer', 'Khmer', '`Khmer`', '`Khmer`', 201, -1, FALSE, '`Khmer`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->Khmer->Sortable = TRUE; // Allow sort
		$this->fields['Khmer'] = &$this->Khmer;
	}

	// Field Visibility
	function GetFieldVisibility($fldparm) {
		global $Security;
		return $this->$fldparm->Visible; // Returns original value
	}

	// Column CSS classes
	var $LeftColumnClass = "col-sm-2 control-label ewLabel";
	var $RightColumnClass = "col-sm-10";
	var $OffsetColumnClass = "col-sm-10 col-sm-offset-2";

	// Set left column class (must be predefined col-*-* classes of Bootstrap grid system)
	function SetLeftColumnClass($class) {
		if (preg_match('/^col\-(\w+)\-(\d+)$/', $class, $match)) {
			$this->LeftColumnClass = $class . " control-label ewLabel";
			$this->RightColumnClass = "col-" . $match[1] . "-" . strval(12 - intval($match[2]));
			$this->OffsetColumnClass = $this->RightColumnClass . " " . str_replace($match[1], $match[1] + "-offset", $class);
		}
	}

	// Single column sort
	function UpdateSort(&$ofld) {
		if ($this->CurrentOrder == $ofld->FldName) {
			$sSortField = $ofld->FldExpression;
			$sLastSort = $ofld->getSort();
			if ($this->CurrentOrderType == "ASC" || $this->CurrentOrderType == "DESC") {
				$sThisSort = $this->CurrentOrderType;
			} else {
				$sThisSort = ($sLastSort == "ASC") ? "DESC" : "ASC";
			}
			$ofld->setSort($sThisSort);
			$this->setSessionOrderBy($sSortField . " " . $sThisSort); // Save to Session
		} else {
			$ofld->setSort("");
		}
	}

	// Table level SQL
	var $_SqlFrom = "";

	function getSqlFrom() { // From
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`language`";
	}

	function SqlFrom() { // For backward compatibility
		return $this->getSqlFrom();
	}

	function setSqlFrom($v) {
		$this->_SqlFrom = $v;
	}
	var $_SqlSelect = "";

	function getSqlSelect() { // Select
		return ($this->_SqlSelect <> "") ? $this->_SqlSelect : "SELECT * FROM " . $this->getSqlFrom();
	}

	function SqlSelect() { // For backward compatibility
		return $this->getSqlSelect();
	}

	function setSqlSelect($v) {
		$this->_SqlSelect = $v;
	}
	var $_SqlWhere = "";

	function getSqlWhere() { // Where
		$sWhere = ($this->_SqlWhere <> "") ? $this->_SqlWhere : "";
		$this->TableFilter = "";
		ew_AddFilter($sWhere, $this->TableFilter);
		return $sWhere;
	}

	function SqlWhere() { // For backward compatibility
		return $this->getSqlWhere();
	}

	function setSqlWhere($v) {
		$this->_SqlWhere = $v;
	}
	var $_SqlGroupBy = "";

	function getSqlGroupBy() { // Group By
		return ($this->_SqlGroupBy <> "") ? $this->_SqlGroupBy : "";
	}

	function SqlGroupBy() { // For backward compatibility
		return $this->getSqlGroupBy();
	}

	function setSqlGroupBy($v) {
		$this->_SqlGroupBy = $v;
	}
	var $_SqlHaving = "";

	function getSqlHaving() { // Having
		return ($this->_SqlHaving <> "") ? $this->_SqlHaving : "";
	}

	function SqlHaving() { // For backward compatibility
		return $this->getSqlHaving();
	}

	function setSqlHaving($v) {
		$this->_SqlHaving = $v;
	}
	var $_SqlOrderBy = "";

	function getSqlOrderBy() { // Order By
		return ($this->_SqlOrderBy <> "") ? $this->_SqlOrderBy : "";
	}

	function SqlOrderBy() { // For backward compatibility
		return $this->getSqlOrderBy();
	}

	function setSqlOrderBy($v) {
		$this->_SqlOrderBy = $v;
	}

	// Apply User ID filters
	function ApplyUserIDFilters($sFilter) {
		return $sFilter;
	}

	// Check if User ID security allows view all
	function UserIDAllow($id = "") {
		$allow = EW_USER_ID_ALLOW;
		switch ($id) {
			case "add":
			case "copy":
			case "gridadd":
			case "register":
			case "addopt":
				return (($allow & 1) == 1);
			case "edit":
			case "gridedit":
			case "update":
			case "changepwd":
			case "forgotpwd":
				return (($allow & 4) == 4);
			case "delete":
				return (($allow & 2) == 2);
			case "view":
				return (($allow & 32) == 32);
			case "search":
				return (($allow & 64) == 64);
			default:
				return (($allow & 8) == 8);
		}
	}

	// Get SQL
	function GetSQL($where, $orderby) {
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(),
			$this->getSqlGroupBy(), $this->getSqlHaving(), $this->getSqlOrderBy(),
			$where, $orderby);
	}

	// Table SQL
	function SQL() {
		$filter = $this->CurrentFilter;
		$filter = $this->ApplyUserIDFilters($filter);
		$sort = $this->getSessionOrderBy();
		return $this->GetSQL($filter, $sort);
	}

	// Table SQL with List page filter
	var $UseSessionForListSQL = TRUE;

	function ListSQL() {
		$sFilter = $this->UseSessionForListSQL ? $this->getSessionWhere() : "";
		ew_AddFilter($sFilter, $this->CurrentFilter);
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$this->Recordset_Selecting($sFilter);
		$sSelect = $this->getSqlSelect();
		$sSort = $this->UseSessionForListSQL ? $this->getSessionOrderBy() : "";
		return ew_BuildSelectSql($sSelect, $this->getSqlWhere(), $this->getSqlGroupBy(),
			$this->getSqlHaving(), $this->getSqlOrderBy(), $sFilter, $sSort);
	}

	// Get ORDER BY clause
	function GetOrderBy() {
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql("", "", "", "", $this->getSqlOrderBy(), "", $sSort);
	}

	// Try to get record count
	function TryGetRecordCount($sql) {
		$cnt = -1;
		$pattern = "/^SELECT \* FROM/i";
		if (($this->TableType == 'TABLE' || $this->TableType == 'VIEW' || $this->TableType == 'LINKTABLE') && preg_match($pattern, $sql)) {
			$sql = "SELECT COUNT(*) FROM" . preg_replace($pattern, "", $sql);
		} else {
			$sql = "SELECT COUNT(*) FROM (" . $sql . ") EW_COUNT_TABLE";
		}
		$conn = &$this->Connection();
		if ($rs = $conn->Execute($sql)) {
			if (!$rs->EOF && $rs->FieldCount() > 0) {
				$cnt = $rs->fields[0];
				$rs->Close();
			}
		}
		return intval($cnt);
	}

	// Get record count based on filter (for detail record count in master table pages)
	function LoadRecordCount($filter) {
		$origFilter = $this->CurrentFilter;
		$this->CurrentFilter = $filter;
		$this->Recordset_Selecting($this->CurrentFilter);
		$select = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlSelect() : "SELECT * FROM " . $this->getSqlFrom();
		$groupBy = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlGroupBy() : "";
		$having = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlHaving() : "";
		$sql = ew_BuildSelectSql($select, $this->getSqlWhere(), $groupBy, $having, "", $this->CurrentFilter, "");
		$cnt = $this->TryGetRecordCount($sql);
		if ($cnt == -1) {
			if ($rs = $this->LoadRs($this->CurrentFilter)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		$this->CurrentFilter = $origFilter;
		return intval($cnt);
	}

	// Get record count (for current List page)
	function ListRecordCount() {
		$filter = $this->getSessionWhere();
		ew_AddFilter($filter, $this->CurrentFilter);
		$filter = $this->ApplyUserIDFilters($filter);
		$this->Recordset_Selecting($filter);
		$select = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlSelect() : "SELECT * FROM " . $this->getSqlFrom();
		$groupBy = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlGroupBy() : "";
		$having = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlHaving() : "";
		$sql = ew_BuildSelectSql($select, $this->getSqlWhere(), $groupBy, $having, "", $filter, "");
		$cnt = $this->TryGetRecordCount($sql);
		if ($cnt == -1) {
			$conn = &$this->Connection();
			if ($rs = $conn->Execute($sql)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		return intval($cnt);
	}

	// INSERT statement
	function InsertSQL(&$rs) {
		$names = "";
		$values = "";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]) || $this->fields[$name]->FldIsCustom)
				continue;
			$names .= $this->fields[$name]->FldExpression . ",";
			$values .= ew_QuotedValue($value, $this->fields[$name]->FldDataType, $this->DBID) . ",";
		}
		$names = preg_replace('/,+$/', "", $names);
		$values = preg_replace('/,+$/', "", $values);
		return "INSERT INTO " . $this->UpdateTable . " ($names) VALUES ($values)";
	}

	// Insert
	function Insert(&$rs) {
		$conn = &$this->Connection();
		$bInsert = $conn->Execute($this->InsertSQL($rs));
		if ($bInsert) {

			// Get insert id if necessary
			$this->phrase_id->setDbValue($conn->Insert_ID());
			$rs['phrase_id'] = $this->phrase_id->DbValue;
		}
		return $bInsert;
	}

	// UPDATE statement
	function UpdateSQL(&$rs, $where = "", $curfilter = TRUE) {
		$sql = "UPDATE " . $this->UpdateTable . " SET ";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]) || $this->fields[$name]->FldIsCustom)
				continue;
			$sql .= $this->fields[$name]->FldExpression . "=";
			$sql .= ew_QuotedValue($value, $this->fields[$name]->FldDataType, $this->DBID) . ",";
		}
		$sql = preg_replace('/,+$/', "", $sql);
		$filter = ($curfilter) ? $this->CurrentFilter : "";
		if (is_array($where))
			$where = $this->ArrayToFilter($where);
		ew_AddFilter($filter, $where);
		if ($filter <> "")	$sql .= " WHERE " . $filter;
		return $sql;
	}

	// Update
	function Update(&$rs, $where = "", $rsold = NULL, $curfilter = TRUE) {
		$conn = &$this->Connection();
		$bUpdate = $conn->Execute($this->UpdateSQL($rs, $where, $curfilter));
		return $bUpdate;
	}

	// DELETE statement
	function DeleteSQL(&$rs, $where = "", $curfilter = TRUE) {
		$sql = "DELETE FROM " . $this->UpdateTable . " WHERE ";
		if (is_array($where))
			$where = $this->ArrayToFilter($where);
		if ($rs) {
			if (array_key_exists('phrase_id', $rs))
				ew_AddFilter($where, ew_QuotedName('phrase_id', $this->DBID) . '=' . ew_QuotedValue($rs['phrase_id'], $this->phrase_id->FldDataType, $this->DBID));
		}
		$filter = ($curfilter) ? $this->CurrentFilter : "";
		ew_AddFilter($filter, $where);
		if ($filter <> "")
			$sql .= $filter;
		else
			$sql .= "0=1"; // Avoid delete
		return $sql;
	}

	// Delete
	function Delete(&$rs, $where = "", $curfilter = TRUE) {
		$bDelete = TRUE;
		$conn = &$this->Connection();
		if ($bDelete)
			$bDelete = $conn->Execute($this->DeleteSQL($rs, $where, $curfilter));
		return $bDelete;
	}

	// Key filter WHERE clause
	function SqlKeyFilter() {
		return "`phrase_id` = @phrase_id@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->phrase_id->CurrentValue))
			return "0=1"; // Invalid key
		if (is_null($this->phrase_id->CurrentValue))
			return "0=1"; // Invalid key
		else
			$sKeyFilter = str_replace("@phrase_id@", ew_AdjustSql($this->phrase_id->CurrentValue, $this->DBID), $sKeyFilter); // Replace key value
		return $sKeyFilter;
	}

	// Return page URL
	function getReturnUrl() {
		$name = EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL;

		// Get referer URL automatically
		if (ew_ServerVar("HTTP_REFERER") <> "" && ew_ReferPage() <> ew_CurrentPage() && ew_ReferPage() <> "login.php") // Referer not same page or login page
			$_SESSION[$name] = ew_ServerVar("HTTP_REFERER"); // Save to Session
		if (@$_SESSION[$name] <> "") {
			return $_SESSION[$name];
		} else {
			return "_languagelist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// Get modal caption
	function GetModalCaption($pageName) {
		global $Language;
		if ($pageName == "_languageview.php")
			return $Language->Phrase("View");
		elseif ($pageName == "_languageedit.php")
			return $Language->Phrase("Edit");
		elseif ($pageName == "_languageadd.php")
			return $Language->Phrase("Add");
		else
			return "";
	}

	// List URL
	function GetListUrl() {
		return "_languagelist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("_languageview.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("_languageview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			$url = "_languageadd.php?" . $this->UrlParm($parm);
		else
			$url = "_languageadd.php";
		return $this->AddMasterUrl($url);
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		$url = $this->KeyUrl("_languageedit.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
		return $this->AddMasterUrl($url);
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		$url = $this->KeyUrl("_languageadd.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
		return $this->AddMasterUrl($url);
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("_languagedelete.php", $this->UrlParm());
	}

	// Add master url
	function AddMasterUrl($url) {
		return $url;
	}

	function KeyToJson() {
		$json = "";
		$json .= "phrase_id:" . ew_VarToJson($this->phrase_id->CurrentValue, "number", "'");
		return "{" . $json . "}";
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->phrase_id->CurrentValue)) {
			$sUrl .= "phrase_id=" . urlencode($this->phrase_id->CurrentValue);
		} else {
			return "javascript:ew_Alert(ewLanguage.Phrase('InvalidRecord'));";
		}
		return $sUrl;
	}

	// Sort URL
	function SortUrl(&$fld) {
		if ($this->CurrentAction <> "" || $this->Export <> "" ||
			in_array($fld->FldType, array(128, 204, 205))) { // Unsortable data type
				return "";
		} elseif ($fld->Sortable) {
			$sUrlParm = $this->UrlParm("order=" . urlencode($fld->FldName) . "&amp;ordertype=" . $fld->ReverseSort());
			return $this->AddMasterUrl(ew_CurrentPage() . "?" . $sUrlParm);
		} else {
			return "";
		}
	}

	// Get record keys from $_POST/$_GET/$_SESSION
	function GetRecordKeys() {
		global $EW_COMPOSITE_KEY_SEPARATOR;
		$arKeys = array();
		$arKey = array();
		if (isset($_POST["key_m"])) {
			$arKeys = $_POST["key_m"];
			$cnt = count($arKeys);
		} elseif (isset($_GET["key_m"])) {
			$arKeys = $_GET["key_m"];
			$cnt = count($arKeys);
		} elseif (!empty($_GET) || !empty($_POST)) {
			$isPost = ew_IsPost();
			if ($isPost && isset($_POST["phrase_id"]))
				$arKeys[] = $_POST["phrase_id"];
			elseif (isset($_GET["phrase_id"]))
				$arKeys[] = $_GET["phrase_id"];
			else
				$arKeys = NULL; // Do not setup

			//return $arKeys; // Do not return yet, so the values will also be checked by the following code
		}

		// Check keys
		$ar = array();
		if (is_array($arKeys)) {
			foreach ($arKeys as $key) {
				if (!is_numeric($key))
					continue;
				$ar[] = $key;
			}
		}
		return $ar;
	}

	// Get key filter
	function GetKeyFilter() {
		$arKeys = $this->GetRecordKeys();
		$sKeyFilter = "";
		foreach ($arKeys as $key) {
			if ($sKeyFilter <> "") $sKeyFilter .= " OR ";
			$this->phrase_id->CurrentValue = $key;
			$sKeyFilter .= "(" . $this->KeyFilter() . ")";
		}
		return $sKeyFilter;
	}

	// Load rows based on filter
	function &LoadRs($filter) {

		// Set up filter (SQL WHERE clause) and get return SQL
		//$this->CurrentFilter = $filter;
		//$sql = $this->SQL();

		$sql = $this->GetSQL($filter, "");
		$conn = &$this->Connection();
		$rs = $conn->Execute($sql);
		return $rs;
	}

	// Load row values from recordset
	function LoadListRowValues(&$rs) {
		$this->phrase_id->setDbValue($rs->fields('phrase_id'));
		$this->phrase->setDbValue($rs->fields('phrase'));
		$this->english->setDbValue($rs->fields('english'));
		$this->bengali->setDbValue($rs->fields('bengali'));
		$this->spanish->setDbValue($rs->fields('spanish'));
		$this->arabic->setDbValue($rs->fields('arabic'));
		$this->dutch->setDbValue($rs->fields('dutch'));
		$this->russian->setDbValue($rs->fields('russian'));
		$this->chinese->setDbValue($rs->fields('chinese'));
		$this->turkish->setDbValue($rs->fields('turkish'));
		$this->portuguese->setDbValue($rs->fields('portuguese'));
		$this->hungarian->setDbValue($rs->fields('hungarian'));
		$this->french->setDbValue($rs->fields('french'));
		$this->greek->setDbValue($rs->fields('greek'));
		$this->german->setDbValue($rs->fields('german'));
		$this->italian->setDbValue($rs->fields('italian'));
		$this->thai->setDbValue($rs->fields('thai'));
		$this->urdu->setDbValue($rs->fields('urdu'));
		$this->hindi->setDbValue($rs->fields('hindi'));
		$this->latin->setDbValue($rs->fields('latin'));
		$this->indonesian->setDbValue($rs->fields('indonesian'));
		$this->japanese->setDbValue($rs->fields('japanese'));
		$this->korean->setDbValue($rs->fields('korean'));
		$this->_178117D2179817C2179A->setDbValue($rs->fields('ខ្មែរ'));
		$this->Khmer->setDbValue($rs->fields('Khmer'));
	}

	// Render list row values
	function RenderListRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

	// Common render codes
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

		// Call Row Rendered event
		$this->Row_Rendered();

		// Save data for Custom Template
		$this->Rows[] = $this->CustomTemplateFieldValues();
	}

	// Render edit row values
	function RenderEditRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

		// phrase_id
		$this->phrase_id->EditAttrs["class"] = "form-control";
		$this->phrase_id->EditCustomAttributes = "";
		$this->phrase_id->EditValue = $this->phrase_id->CurrentValue;
		$this->phrase_id->ViewCustomAttributes = "";

		// phrase
		$this->phrase->EditAttrs["class"] = "form-control";
		$this->phrase->EditCustomAttributes = "";
		$this->phrase->EditValue = $this->phrase->CurrentValue;
		$this->phrase->PlaceHolder = ew_RemoveHtml($this->phrase->FldCaption());

		// english
		$this->english->EditAttrs["class"] = "form-control";
		$this->english->EditCustomAttributes = "";
		$this->english->EditValue = $this->english->CurrentValue;
		$this->english->PlaceHolder = ew_RemoveHtml($this->english->FldCaption());

		// bengali
		$this->bengali->EditAttrs["class"] = "form-control";
		$this->bengali->EditCustomAttributes = "";
		$this->bengali->EditValue = $this->bengali->CurrentValue;
		$this->bengali->PlaceHolder = ew_RemoveHtml($this->bengali->FldCaption());

		// spanish
		$this->spanish->EditAttrs["class"] = "form-control";
		$this->spanish->EditCustomAttributes = "";
		$this->spanish->EditValue = $this->spanish->CurrentValue;
		$this->spanish->PlaceHolder = ew_RemoveHtml($this->spanish->FldCaption());

		// arabic
		$this->arabic->EditAttrs["class"] = "form-control";
		$this->arabic->EditCustomAttributes = "";
		$this->arabic->EditValue = $this->arabic->CurrentValue;
		$this->arabic->PlaceHolder = ew_RemoveHtml($this->arabic->FldCaption());

		// dutch
		$this->dutch->EditAttrs["class"] = "form-control";
		$this->dutch->EditCustomAttributes = "";
		$this->dutch->EditValue = $this->dutch->CurrentValue;
		$this->dutch->PlaceHolder = ew_RemoveHtml($this->dutch->FldCaption());

		// russian
		$this->russian->EditAttrs["class"] = "form-control";
		$this->russian->EditCustomAttributes = "";
		$this->russian->EditValue = $this->russian->CurrentValue;
		$this->russian->PlaceHolder = ew_RemoveHtml($this->russian->FldCaption());

		// chinese
		$this->chinese->EditAttrs["class"] = "form-control";
		$this->chinese->EditCustomAttributes = "";
		$this->chinese->EditValue = $this->chinese->CurrentValue;
		$this->chinese->PlaceHolder = ew_RemoveHtml($this->chinese->FldCaption());

		// turkish
		$this->turkish->EditAttrs["class"] = "form-control";
		$this->turkish->EditCustomAttributes = "";
		$this->turkish->EditValue = $this->turkish->CurrentValue;
		$this->turkish->PlaceHolder = ew_RemoveHtml($this->turkish->FldCaption());

		// portuguese
		$this->portuguese->EditAttrs["class"] = "form-control";
		$this->portuguese->EditCustomAttributes = "";
		$this->portuguese->EditValue = $this->portuguese->CurrentValue;
		$this->portuguese->PlaceHolder = ew_RemoveHtml($this->portuguese->FldCaption());

		// hungarian
		$this->hungarian->EditAttrs["class"] = "form-control";
		$this->hungarian->EditCustomAttributes = "";
		$this->hungarian->EditValue = $this->hungarian->CurrentValue;
		$this->hungarian->PlaceHolder = ew_RemoveHtml($this->hungarian->FldCaption());

		// french
		$this->french->EditAttrs["class"] = "form-control";
		$this->french->EditCustomAttributes = "";
		$this->french->EditValue = $this->french->CurrentValue;
		$this->french->PlaceHolder = ew_RemoveHtml($this->french->FldCaption());

		// greek
		$this->greek->EditAttrs["class"] = "form-control";
		$this->greek->EditCustomAttributes = "";
		$this->greek->EditValue = $this->greek->CurrentValue;
		$this->greek->PlaceHolder = ew_RemoveHtml($this->greek->FldCaption());

		// german
		$this->german->EditAttrs["class"] = "form-control";
		$this->german->EditCustomAttributes = "";
		$this->german->EditValue = $this->german->CurrentValue;
		$this->german->PlaceHolder = ew_RemoveHtml($this->german->FldCaption());

		// italian
		$this->italian->EditAttrs["class"] = "form-control";
		$this->italian->EditCustomAttributes = "";
		$this->italian->EditValue = $this->italian->CurrentValue;
		$this->italian->PlaceHolder = ew_RemoveHtml($this->italian->FldCaption());

		// thai
		$this->thai->EditAttrs["class"] = "form-control";
		$this->thai->EditCustomAttributes = "";
		$this->thai->EditValue = $this->thai->CurrentValue;
		$this->thai->PlaceHolder = ew_RemoveHtml($this->thai->FldCaption());

		// urdu
		$this->urdu->EditAttrs["class"] = "form-control";
		$this->urdu->EditCustomAttributes = "";
		$this->urdu->EditValue = $this->urdu->CurrentValue;
		$this->urdu->PlaceHolder = ew_RemoveHtml($this->urdu->FldCaption());

		// hindi
		$this->hindi->EditAttrs["class"] = "form-control";
		$this->hindi->EditCustomAttributes = "";
		$this->hindi->EditValue = $this->hindi->CurrentValue;
		$this->hindi->PlaceHolder = ew_RemoveHtml($this->hindi->FldCaption());

		// latin
		$this->latin->EditAttrs["class"] = "form-control";
		$this->latin->EditCustomAttributes = "";
		$this->latin->EditValue = $this->latin->CurrentValue;
		$this->latin->PlaceHolder = ew_RemoveHtml($this->latin->FldCaption());

		// indonesian
		$this->indonesian->EditAttrs["class"] = "form-control";
		$this->indonesian->EditCustomAttributes = "";
		$this->indonesian->EditValue = $this->indonesian->CurrentValue;
		$this->indonesian->PlaceHolder = ew_RemoveHtml($this->indonesian->FldCaption());

		// japanese
		$this->japanese->EditAttrs["class"] = "form-control";
		$this->japanese->EditCustomAttributes = "";
		$this->japanese->EditValue = $this->japanese->CurrentValue;
		$this->japanese->PlaceHolder = ew_RemoveHtml($this->japanese->FldCaption());

		// korean
		$this->korean->EditAttrs["class"] = "form-control";
		$this->korean->EditCustomAttributes = "";
		$this->korean->EditValue = $this->korean->CurrentValue;
		$this->korean->PlaceHolder = ew_RemoveHtml($this->korean->FldCaption());

		// ខ្មែរ
		$this->_178117D2179817C2179A->EditAttrs["class"] = "form-control";
		$this->_178117D2179817C2179A->EditCustomAttributes = "";
		$this->_178117D2179817C2179A->EditValue = $this->_178117D2179817C2179A->CurrentValue;
		$this->_178117D2179817C2179A->PlaceHolder = ew_RemoveHtml($this->_178117D2179817C2179A->FldCaption());

		// Khmer
		$this->Khmer->EditAttrs["class"] = "form-control";
		$this->Khmer->EditCustomAttributes = "";
		$this->Khmer->EditValue = $this->Khmer->CurrentValue;
		$this->Khmer->PlaceHolder = ew_RemoveHtml($this->Khmer->FldCaption());

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Aggregate list row values
	function AggregateListRowValues() {
	}

	// Aggregate list row (for rendering)
	function AggregateListRow() {

		// Call Row Rendered event
		$this->Row_Rendered();
	}
	var $ExportDoc;

	// Export data in HTML/CSV/Word/Excel/Email/PDF format
	function ExportDocument(&$Doc, &$Recordset, $StartRec, $StopRec, $ExportPageType = "") {
		if (!$Recordset || !$Doc)
			return;
		if (!$Doc->ExportCustom) {

			// Write header
			$Doc->ExportTableHeader();
			if ($Doc->Horizontal) { // Horizontal format, write header
				$Doc->BeginExportRow();
				if ($ExportPageType == "view") {
					if ($this->phrase_id->Exportable) $Doc->ExportCaption($this->phrase_id);
					if ($this->phrase->Exportable) $Doc->ExportCaption($this->phrase);
					if ($this->english->Exportable) $Doc->ExportCaption($this->english);
					if ($this->bengali->Exportable) $Doc->ExportCaption($this->bengali);
					if ($this->spanish->Exportable) $Doc->ExportCaption($this->spanish);
					if ($this->arabic->Exportable) $Doc->ExportCaption($this->arabic);
					if ($this->dutch->Exportable) $Doc->ExportCaption($this->dutch);
					if ($this->russian->Exportable) $Doc->ExportCaption($this->russian);
					if ($this->chinese->Exportable) $Doc->ExportCaption($this->chinese);
					if ($this->turkish->Exportable) $Doc->ExportCaption($this->turkish);
					if ($this->portuguese->Exportable) $Doc->ExportCaption($this->portuguese);
					if ($this->hungarian->Exportable) $Doc->ExportCaption($this->hungarian);
					if ($this->french->Exportable) $Doc->ExportCaption($this->french);
					if ($this->greek->Exportable) $Doc->ExportCaption($this->greek);
					if ($this->german->Exportable) $Doc->ExportCaption($this->german);
					if ($this->italian->Exportable) $Doc->ExportCaption($this->italian);
					if ($this->thai->Exportable) $Doc->ExportCaption($this->thai);
					if ($this->urdu->Exportable) $Doc->ExportCaption($this->urdu);
					if ($this->hindi->Exportable) $Doc->ExportCaption($this->hindi);
					if ($this->latin->Exportable) $Doc->ExportCaption($this->latin);
					if ($this->indonesian->Exportable) $Doc->ExportCaption($this->indonesian);
					if ($this->japanese->Exportable) $Doc->ExportCaption($this->japanese);
					if ($this->korean->Exportable) $Doc->ExportCaption($this->korean);
					if ($this->_178117D2179817C2179A->Exportable) $Doc->ExportCaption($this->_178117D2179817C2179A);
					if ($this->Khmer->Exportable) $Doc->ExportCaption($this->Khmer);
				} else {
					if ($this->phrase_id->Exportable) $Doc->ExportCaption($this->phrase_id);
				}
				$Doc->EndExportRow();
			}
		}

		// Move to first record
		$RecCnt = $StartRec - 1;
		if (!$Recordset->EOF) {
			$Recordset->MoveFirst();
			if ($StartRec > 1)
				$Recordset->Move($StartRec - 1);
		}
		while (!$Recordset->EOF && $RecCnt < $StopRec) {
			$RecCnt++;
			if (intval($RecCnt) >= intval($StartRec)) {
				$RowCnt = intval($RecCnt) - intval($StartRec) + 1;

				// Page break
				if ($this->ExportPageBreakCount > 0) {
					if ($RowCnt > 1 && ($RowCnt - 1) % $this->ExportPageBreakCount == 0)
						$Doc->ExportPageBreak();
				}
				$this->LoadListRowValues($Recordset);

				// Render row
				$this->RowType = EW_ROWTYPE_VIEW; // Render view
				$this->ResetAttrs();
				$this->RenderListRow();
				if (!$Doc->ExportCustom) {
					$Doc->BeginExportRow($RowCnt); // Allow CSS styles if enabled
					if ($ExportPageType == "view") {
						if ($this->phrase_id->Exportable) $Doc->ExportField($this->phrase_id);
						if ($this->phrase->Exportable) $Doc->ExportField($this->phrase);
						if ($this->english->Exportable) $Doc->ExportField($this->english);
						if ($this->bengali->Exportable) $Doc->ExportField($this->bengali);
						if ($this->spanish->Exportable) $Doc->ExportField($this->spanish);
						if ($this->arabic->Exportable) $Doc->ExportField($this->arabic);
						if ($this->dutch->Exportable) $Doc->ExportField($this->dutch);
						if ($this->russian->Exportable) $Doc->ExportField($this->russian);
						if ($this->chinese->Exportable) $Doc->ExportField($this->chinese);
						if ($this->turkish->Exportable) $Doc->ExportField($this->turkish);
						if ($this->portuguese->Exportable) $Doc->ExportField($this->portuguese);
						if ($this->hungarian->Exportable) $Doc->ExportField($this->hungarian);
						if ($this->french->Exportable) $Doc->ExportField($this->french);
						if ($this->greek->Exportable) $Doc->ExportField($this->greek);
						if ($this->german->Exportable) $Doc->ExportField($this->german);
						if ($this->italian->Exportable) $Doc->ExportField($this->italian);
						if ($this->thai->Exportable) $Doc->ExportField($this->thai);
						if ($this->urdu->Exportable) $Doc->ExportField($this->urdu);
						if ($this->hindi->Exportable) $Doc->ExportField($this->hindi);
						if ($this->latin->Exportable) $Doc->ExportField($this->latin);
						if ($this->indonesian->Exportable) $Doc->ExportField($this->indonesian);
						if ($this->japanese->Exportable) $Doc->ExportField($this->japanese);
						if ($this->korean->Exportable) $Doc->ExportField($this->korean);
						if ($this->_178117D2179817C2179A->Exportable) $Doc->ExportField($this->_178117D2179817C2179A);
						if ($this->Khmer->Exportable) $Doc->ExportField($this->Khmer);
					} else {
						if ($this->phrase_id->Exportable) $Doc->ExportField($this->phrase_id);
					}
					$Doc->EndExportRow($RowCnt);
				}
			}

			// Call Row Export server event
			if ($Doc->ExportCustom)
				$this->Row_Export($Recordset->fields);
			$Recordset->MoveNext();
		}
		if (!$Doc->ExportCustom) {
			$Doc->ExportTableFooter();
		}
	}

	// Get auto fill value
	function GetAutoFill($id, $val) {
		$rsarr = array();
		$rowcnt = 0;

		// Output
		if (is_array($rsarr) && $rowcnt > 0) {
			$fldcnt = count($rsarr[0]);
			for ($i = 0; $i < $rowcnt; $i++) {
				for ($j = 0; $j < $fldcnt; $j++) {
					$str = strval($rsarr[$i][$j]);
					$str = ew_ConvertToUtf8($str);
					if (isset($post["keepCRLF"])) {
						$str = str_replace(array("\r", "\n"), array("\\r", "\\n"), $str);
					} else {
						$str = str_replace(array("\r", "\n"), array(" ", " "), $str);
					}
					$rsarr[$i][$j] = $str;
				}
			}
			return ew_ArrayToJson($rsarr);
		} else {
			return FALSE;
		}
	}

	// Table level events
	// Recordset Selecting event
	function Recordset_Selecting(&$filter) {

		// Enter your code here
	}

	// Recordset Selected event
	function Recordset_Selected(&$rs) {

		//echo "Recordset Selected";
	}

	// Recordset Search Validated event
	function Recordset_SearchValidated() {

		// Example:
		//$this->MyField1->AdvancedSearch->SearchValue = "your search criteria"; // Search value

	}

	// Recordset Searching event
	function Recordset_Searching(&$filter) {

		// Enter your code here
	}

	// Row_Selecting event
	function Row_Selecting(&$filter) {

		// Enter your code here
	}

	// Row Selected event
	function Row_Selected(&$rs) {

		//echo "Row Selected";
	}

	// Row Inserting event
	function Row_Inserting($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Inserted event
	function Row_Inserted($rsold, &$rsnew) {

		//echo "Row Inserted"
	}

	// Row Updating event
	function Row_Updating($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Updated event
	function Row_Updated($rsold, &$rsnew) {

		//echo "Row Updated";
	}

	// Row Update Conflict event
	function Row_UpdateConflict($rsold, &$rsnew) {

		// Enter your code here
		// To ignore conflict, set return value to FALSE

		return TRUE;
	}

	// Grid Inserting event
	function Grid_Inserting() {

		// Enter your code here
		// To reject grid insert, set return value to FALSE

		return TRUE;
	}

	// Grid Inserted event
	function Grid_Inserted($rsnew) {

		//echo "Grid Inserted";
	}

	// Grid Updating event
	function Grid_Updating($rsold) {

		// Enter your code here
		// To reject grid update, set return value to FALSE

		return TRUE;
	}

	// Grid Updated event
	function Grid_Updated($rsold, $rsnew) {

		//echo "Grid Updated";
	}

	// Row Deleting event
	function Row_Deleting(&$rs) {

		// Enter your code here
		// To cancel, set return value to False

		return TRUE;
	}

	// Row Deleted event
	function Row_Deleted(&$rs) {

		//echo "Row Deleted";
	}

	// Email Sending event
	function Email_Sending(&$Email, &$Args) {

		//var_dump($Email); var_dump($Args); exit();
		return TRUE;
	}

	// Lookup Selecting event
	function Lookup_Selecting($fld, &$filter) {

		//var_dump($fld->FldName, $fld->LookupFilters, $filter); // Uncomment to view the filter
		// Enter your code here

	}

	// Row Rendering event
	function Row_Rendering() {

		// Enter your code here
	}

	// Row Rendered event
	function Row_Rendered() {

		// To view properties of field class, use:
		//var_dump($this-><FieldName>);

	}

	// User ID Filtering event
	function UserID_Filtering(&$filter) {

		// Enter your code here
	}
}
?>
