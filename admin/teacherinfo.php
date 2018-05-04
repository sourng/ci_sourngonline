<?php

// Global variable for table object
$teacher = NULL;

//
// Table class for teacher
//
class cteacher extends cTable {
	var $teacher_id;
	var $name;
	var $birthday;
	var $sex;
	var $religion;
	var $blood_group;
	var $address;
	var $phone;
	var $_email;
	var $password;
	var $authentication_key;
	var $photo;
	var $active;
	var $teacher_image;
	var $twitter;
	var $facebook;
	var $google;
	var $linkedin;
	var $pinterest;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'teacher';
		$this->TableName = 'teacher';
		$this->TableType = 'TABLE';

		// Update Table
		$this->UpdateTable = "`teacher`";
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
		$this->DetailView = TRUE; // Allow detail view
		$this->ShowMultipleDetails = FALSE; // Show multiple details
		$this->GridAddRowCount = 5;
		$this->AllowAddDeleteRow = TRUE; // Allow add/delete row
		$this->UserIDAllowSecurity = 0; // User ID Allow
		$this->BasicSearch = new cBasicSearch($this->TableVar);

		// teacher_id
		$this->teacher_id = new cField('teacher', 'teacher', 'x_teacher_id', 'teacher_id', '`teacher_id`', '`teacher_id`', 3, -1, FALSE, '`teacher_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'NO');
		$this->teacher_id->Sortable = TRUE; // Allow sort
		$this->teacher_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['teacher_id'] = &$this->teacher_id;

		// name
		$this->name = new cField('teacher', 'teacher', 'x_name', 'name', '`name`', '`name`', 201, -1, FALSE, '`name`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->name->Sortable = TRUE; // Allow sort
		$this->fields['name'] = &$this->name;

		// birthday
		$this->birthday = new cField('teacher', 'teacher', 'x_birthday', 'birthday', '`birthday`', '`birthday`', 201, 7, FALSE, '`birthday`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->birthday->Sortable = TRUE; // Allow sort
		$this->fields['birthday'] = &$this->birthday;

		// sex
		$this->sex = new cField('teacher', 'teacher', 'x_sex', 'sex', '`sex`', '`sex`', 201, -1, FALSE, '`sex`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->sex->Sortable = TRUE; // Allow sort
		$this->sex->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->sex->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->sex->OptionCount = 2;
		$this->fields['sex'] = &$this->sex;

		// religion
		$this->religion = new cField('teacher', 'teacher', 'x_religion', 'religion', '`religion`', '`religion`', 201, -1, FALSE, '`religion`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->religion->Sortable = TRUE; // Allow sort
		$this->fields['religion'] = &$this->religion;

		// blood_group
		$this->blood_group = new cField('teacher', 'teacher', 'x_blood_group', 'blood_group', '`blood_group`', '`blood_group`', 201, -1, FALSE, '`blood_group`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->blood_group->Sortable = TRUE; // Allow sort
		$this->fields['blood_group'] = &$this->blood_group;

		// address
		$this->address = new cField('teacher', 'teacher', 'x_address', 'address', '`address`', '`address`', 201, -1, FALSE, '`address`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->address->Sortable = TRUE; // Allow sort
		$this->fields['address'] = &$this->address;

		// phone
		$this->phone = new cField('teacher', 'teacher', 'x_phone', 'phone', '`phone`', '`phone`', 201, -1, FALSE, '`phone`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->phone->Sortable = TRUE; // Allow sort
		$this->fields['phone'] = &$this->phone;

		// email
		$this->_email = new cField('teacher', 'teacher', 'x__email', 'email', '`email`', '`email`', 201, -1, FALSE, '`email`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->_email->Sortable = TRUE; // Allow sort
		$this->fields['email'] = &$this->_email;

		// password
		$this->password = new cField('teacher', 'teacher', 'x_password', 'password', '`password`', '`password`', 201, -1, FALSE, '`password`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'PASSWORD');
		$this->password->Sortable = TRUE; // Allow sort
		$this->fields['password'] = &$this->password;

		// authentication_key
		$this->authentication_key = new cField('teacher', 'teacher', 'x_authentication_key', 'authentication_key', '`authentication_key`', '`authentication_key`', 201, -1, FALSE, '`authentication_key`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->authentication_key->Sortable = TRUE; // Allow sort
		$this->fields['authentication_key'] = &$this->authentication_key;

		// photo
		$this->photo = new cField('teacher', 'teacher', 'x_photo', 'photo', '`photo`', '`photo`', 200, -1, TRUE, '`photo`', FALSE, FALSE, FALSE, 'IMAGE', 'FILE');
		$this->photo->Sortable = TRUE; // Allow sort
		$this->fields['photo'] = &$this->photo;

		// active
		$this->active = new cField('teacher', 'teacher', 'x_active', 'active', '`active`', '`active`', 16, -1, FALSE, '`active`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'RADIO');
		$this->active->Sortable = TRUE; // Allow sort
		$this->active->OptionCount = 2;
		$this->active->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['active'] = &$this->active;

		// teacher_image
		$this->teacher_image = new cField('teacher', 'teacher', 'x_teacher_image', 'teacher_image', '`teacher_image`', '`teacher_image`', 205, -1, TRUE, '`teacher_image`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'FILE');
		$this->teacher_image->Sortable = TRUE; // Allow sort
		$this->fields['teacher_image'] = &$this->teacher_image;

		// twitter
		$this->twitter = new cField('teacher', 'teacher', 'x_twitter', 'twitter', '`twitter`', '`twitter`', 200, -1, FALSE, '`twitter`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->twitter->Sortable = TRUE; // Allow sort
		$this->fields['twitter'] = &$this->twitter;

		// facebook
		$this->facebook = new cField('teacher', 'teacher', 'x_facebook', 'facebook', '`facebook`', '`facebook`', 200, -1, FALSE, '`facebook`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->facebook->Sortable = TRUE; // Allow sort
		$this->fields['facebook'] = &$this->facebook;

		// google
		$this->google = new cField('teacher', 'teacher', 'x_google', 'google', '`google`', '`google`', 200, -1, FALSE, '`google`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->google->Sortable = TRUE; // Allow sort
		$this->fields['google'] = &$this->google;

		// linkedin
		$this->linkedin = new cField('teacher', 'teacher', 'x_linkedin', 'linkedin', '`linkedin`', '`linkedin`', 200, -1, FALSE, '`linkedin`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->linkedin->Sortable = TRUE; // Allow sort
		$this->fields['linkedin'] = &$this->linkedin;

		// pinterest
		$this->pinterest = new cField('teacher', 'teacher', 'x_pinterest', 'pinterest', '`pinterest`', '`pinterest`', 200, -1, FALSE, '`pinterest`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->pinterest->Sortable = TRUE; // Allow sort
		$this->fields['pinterest'] = &$this->pinterest;
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

	// Current detail table name
	function getCurrentDetailTable() {
		return @$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_DETAIL_TABLE];
	}

	function setCurrentDetailTable($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_DETAIL_TABLE] = $v;
	}

	// Get detail url
	function GetDetailUrl() {

		// Detail url
		$sDetailUrl = "";
		if ($this->getCurrentDetailTable() == "subject") {
			$sDetailUrl = $GLOBALS["subject"]->GetListUrl() . "?" . EW_TABLE_SHOW_MASTER . "=" . $this->TableVar;
			$sDetailUrl .= "&fk_teacher_id=" . urlencode($this->teacher_id->CurrentValue);
		}
		if ($sDetailUrl == "") {
			$sDetailUrl = "teacherlist.php";
		}
		return $sDetailUrl;
	}

	// Table level SQL
	var $_SqlFrom = "";

	function getSqlFrom() { // From
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`teacher`";
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
			$this->teacher_id->setDbValue($conn->Insert_ID());
			$rs['teacher_id'] = $this->teacher_id->DbValue;
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

		// Cascade Update detail table 'subject'
		$bCascadeUpdate = FALSE;
		$rscascade = array();
		if (!is_null($rsold) && (isset($rs['teacher_id']) && $rsold['teacher_id'] <> $rs['teacher_id'])) { // Update detail field 'teacher_id'
			$bCascadeUpdate = TRUE;
			$rscascade['teacher_id'] = $rs['teacher_id']; 
		}
		if ($bCascadeUpdate) {
			if (!isset($GLOBALS["subject"])) $GLOBALS["subject"] = new csubject();
			$rswrk = $GLOBALS["subject"]->LoadRs("`teacher_id` = " . ew_QuotedValue($rsold['teacher_id'], EW_DATATYPE_NUMBER, 'DB')); 
			while ($rswrk && !$rswrk->EOF) {
				$rskey = array();
				$fldname = 'subject_id';
				$rskey[$fldname] = $rswrk->fields[$fldname];
				$rsdtlold = &$rswrk->fields;
				$rsdtlnew = array_merge($rsdtlold, $rscascade);

				// Call Row_Updating event
				$bUpdate = $GLOBALS["subject"]->Row_Updating($rsdtlold, $rsdtlnew);
				if ($bUpdate)
					$bUpdate = $GLOBALS["subject"]->Update($rscascade, $rskey, $rswrk->fields);
				if (!$bUpdate) return FALSE;

				// Call Row_Updated event
				$GLOBALS["subject"]->Row_Updated($rsdtlold, $rsdtlnew);
				$rswrk->MoveNext();
			}
		}
		$bUpdate = $conn->Execute($this->UpdateSQL($rs, $where, $curfilter));
		return $bUpdate;
	}

	// DELETE statement
	function DeleteSQL(&$rs, $where = "", $curfilter = TRUE) {
		$sql = "DELETE FROM " . $this->UpdateTable . " WHERE ";
		if (is_array($where))
			$where = $this->ArrayToFilter($where);
		if ($rs) {
			if (array_key_exists('teacher_id', $rs))
				ew_AddFilter($where, ew_QuotedName('teacher_id', $this->DBID) . '=' . ew_QuotedValue($rs['teacher_id'], $this->teacher_id->FldDataType, $this->DBID));
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

		// Cascade delete detail table 'subject'
		if (!isset($GLOBALS["subject"])) $GLOBALS["subject"] = new csubject();
		$rscascade = $GLOBALS["subject"]->LoadRs("`teacher_id` = " . ew_QuotedValue($rs['teacher_id'], EW_DATATYPE_NUMBER, "DB")); 
		$dtlrows = ($rscascade) ? $rscascade->GetRows() : array();

		// Call Row Deleting event
		foreach ($dtlrows as $dtlrow) {
			$bDelete = $GLOBALS["subject"]->Row_Deleting($dtlrow);
			if (!$bDelete) break;
		}
		if ($bDelete) {
			foreach ($dtlrows as $dtlrow) {
				$bDelete = $GLOBALS["subject"]->Delete($dtlrow); // Delete
				if ($bDelete === FALSE)
					break;
			}
		}

		// Call Row Deleted event
		if ($bDelete) {
			foreach ($dtlrows as $dtlrow) {
				$GLOBALS["subject"]->Row_Deleted($dtlrow);
			}
		}
		if ($bDelete)
			$bDelete = $conn->Execute($this->DeleteSQL($rs, $where, $curfilter));
		return $bDelete;
	}

	// Key filter WHERE clause
	function SqlKeyFilter() {
		return "`teacher_id` = @teacher_id@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->teacher_id->CurrentValue))
			return "0=1"; // Invalid key
		if (is_null($this->teacher_id->CurrentValue))
			return "0=1"; // Invalid key
		else
			$sKeyFilter = str_replace("@teacher_id@", ew_AdjustSql($this->teacher_id->CurrentValue, $this->DBID), $sKeyFilter); // Replace key value
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
			return "teacherlist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// Get modal caption
	function GetModalCaption($pageName) {
		global $Language;
		if ($pageName == "teacherview.php")
			return $Language->Phrase("View");
		elseif ($pageName == "teacheredit.php")
			return $Language->Phrase("Edit");
		elseif ($pageName == "teacheradd.php")
			return $Language->Phrase("Add");
		else
			return "";
	}

	// List URL
	function GetListUrl() {
		return "teacherlist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("teacherview.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("teacherview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			$url = "teacheradd.php?" . $this->UrlParm($parm);
		else
			$url = "teacheradd.php";
		return $this->AddMasterUrl($url);
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("teacheredit.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("teacheredit.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
		return $this->AddMasterUrl($url);
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("teacheradd.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("teacheradd.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
		return $this->AddMasterUrl($url);
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("teacherdelete.php", $this->UrlParm());
	}

	// Add master url
	function AddMasterUrl($url) {
		return $url;
	}

	function KeyToJson() {
		$json = "";
		$json .= "teacher_id:" . ew_VarToJson($this->teacher_id->CurrentValue, "number", "'");
		return "{" . $json . "}";
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->teacher_id->CurrentValue)) {
			$sUrl .= "teacher_id=" . urlencode($this->teacher_id->CurrentValue);
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
			if ($isPost && isset($_POST["teacher_id"]))
				$arKeys[] = $_POST["teacher_id"];
			elseif (isset($_GET["teacher_id"]))
				$arKeys[] = $_GET["teacher_id"];
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
			$this->teacher_id->CurrentValue = $key;
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
		$this->teacher_id->setDbValue($rs->fields('teacher_id'));
		$this->name->setDbValue($rs->fields('name'));
		$this->birthday->setDbValue($rs->fields('birthday'));
		$this->sex->setDbValue($rs->fields('sex'));
		$this->religion->setDbValue($rs->fields('religion'));
		$this->blood_group->setDbValue($rs->fields('blood_group'));
		$this->address->setDbValue($rs->fields('address'));
		$this->phone->setDbValue($rs->fields('phone'));
		$this->_email->setDbValue($rs->fields('email'));
		$this->password->setDbValue($rs->fields('password'));
		$this->authentication_key->setDbValue($rs->fields('authentication_key'));
		$this->photo->Upload->DbValue = $rs->fields('photo');
		$this->active->setDbValue($rs->fields('active'));
		$this->teacher_image->Upload->DbValue = $rs->fields('teacher_image');
		$this->twitter->setDbValue($rs->fields('twitter'));
		$this->facebook->setDbValue($rs->fields('facebook'));
		$this->google->setDbValue($rs->fields('google'));
		$this->linkedin->setDbValue($rs->fields('linkedin'));
		$this->pinterest->setDbValue($rs->fields('pinterest'));
	}

	// Render list row values
	function RenderListRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

	// Common render codes
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

		// teacher_id
		$this->teacher_id->EditAttrs["class"] = "form-control";
		$this->teacher_id->EditCustomAttributes = "";
		$this->teacher_id->EditValue = $this->teacher_id->CurrentValue;
		$this->teacher_id->ViewCustomAttributes = "";

		// name
		$this->name->EditAttrs["class"] = "form-control";
		$this->name->EditCustomAttributes = "";
		$this->name->EditValue = $this->name->CurrentValue;
		$this->name->PlaceHolder = ew_RemoveHtml($this->name->FldCaption());

		// birthday
		$this->birthday->EditAttrs["class"] = "form-control";
		$this->birthday->EditCustomAttributes = "";
		$this->birthday->EditValue = $this->birthday->CurrentValue;
		$this->birthday->PlaceHolder = ew_RemoveHtml($this->birthday->FldCaption());

		// sex
		$this->sex->EditAttrs["class"] = "form-control";
		$this->sex->EditCustomAttributes = "";
		$this->sex->EditValue = $this->sex->Options(TRUE);

		// religion
		$this->religion->EditAttrs["class"] = "form-control";
		$this->religion->EditCustomAttributes = "";
		$this->religion->EditValue = $this->religion->CurrentValue;
		$this->religion->PlaceHolder = ew_RemoveHtml($this->religion->FldCaption());

		// blood_group
		$this->blood_group->EditAttrs["class"] = "form-control";
		$this->blood_group->EditCustomAttributes = "";
		$this->blood_group->EditValue = $this->blood_group->CurrentValue;
		$this->blood_group->PlaceHolder = ew_RemoveHtml($this->blood_group->FldCaption());

		// address
		$this->address->EditAttrs["class"] = "form-control";
		$this->address->EditCustomAttributes = "";
		$this->address->EditValue = $this->address->CurrentValue;
		$this->address->PlaceHolder = ew_RemoveHtml($this->address->FldCaption());

		// phone
		$this->phone->EditAttrs["class"] = "form-control";
		$this->phone->EditCustomAttributes = "";
		$this->phone->EditValue = $this->phone->CurrentValue;
		$this->phone->PlaceHolder = ew_RemoveHtml($this->phone->FldCaption());

		// email
		$this->_email->EditAttrs["class"] = "form-control";
		$this->_email->EditCustomAttributes = "";
		$this->_email->EditValue = $this->_email->CurrentValue;
		$this->_email->PlaceHolder = ew_RemoveHtml($this->_email->FldCaption());

		// password
		$this->password->EditAttrs["class"] = "form-control";
		$this->password->EditCustomAttributes = "";
		$this->password->EditValue = $this->password->CurrentValue;
		$this->password->PlaceHolder = ew_RemoveHtml($this->password->FldCaption());

		// authentication_key
		$this->authentication_key->EditAttrs["class"] = "form-control";
		$this->authentication_key->EditCustomAttributes = "";
		$this->authentication_key->EditValue = $this->authentication_key->CurrentValue;
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

		// twitter
		$this->twitter->EditAttrs["class"] = "form-control";
		$this->twitter->EditCustomAttributes = "";
		$this->twitter->EditValue = $this->twitter->CurrentValue;
		$this->twitter->PlaceHolder = ew_RemoveHtml($this->twitter->FldCaption());

		// facebook
		$this->facebook->EditAttrs["class"] = "form-control";
		$this->facebook->EditCustomAttributes = "";
		$this->facebook->EditValue = $this->facebook->CurrentValue;
		$this->facebook->PlaceHolder = ew_RemoveHtml($this->facebook->FldCaption());

		// google
		$this->google->EditAttrs["class"] = "form-control";
		$this->google->EditCustomAttributes = "";
		$this->google->EditValue = $this->google->CurrentValue;
		$this->google->PlaceHolder = ew_RemoveHtml($this->google->FldCaption());

		// linkedin
		$this->linkedin->EditAttrs["class"] = "form-control";
		$this->linkedin->EditCustomAttributes = "";
		$this->linkedin->EditValue = $this->linkedin->CurrentValue;
		$this->linkedin->PlaceHolder = ew_RemoveHtml($this->linkedin->FldCaption());

		// pinterest
		$this->pinterest->EditAttrs["class"] = "form-control";
		$this->pinterest->EditCustomAttributes = "";
		$this->pinterest->EditValue = $this->pinterest->CurrentValue;
		$this->pinterest->PlaceHolder = ew_RemoveHtml($this->pinterest->FldCaption());

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
					if ($this->teacher_id->Exportable) $Doc->ExportCaption($this->teacher_id);
					if ($this->name->Exportable) $Doc->ExportCaption($this->name);
					if ($this->birthday->Exportable) $Doc->ExportCaption($this->birthday);
					if ($this->sex->Exportable) $Doc->ExportCaption($this->sex);
					if ($this->religion->Exportable) $Doc->ExportCaption($this->religion);
					if ($this->blood_group->Exportable) $Doc->ExportCaption($this->blood_group);
					if ($this->address->Exportable) $Doc->ExportCaption($this->address);
					if ($this->phone->Exportable) $Doc->ExportCaption($this->phone);
					if ($this->_email->Exportable) $Doc->ExportCaption($this->_email);
					if ($this->password->Exportable) $Doc->ExportCaption($this->password);
					if ($this->authentication_key->Exportable) $Doc->ExportCaption($this->authentication_key);
					if ($this->photo->Exportable) $Doc->ExportCaption($this->photo);
					if ($this->active->Exportable) $Doc->ExportCaption($this->active);
					if ($this->teacher_image->Exportable) $Doc->ExportCaption($this->teacher_image);
					if ($this->twitter->Exportable) $Doc->ExportCaption($this->twitter);
					if ($this->facebook->Exportable) $Doc->ExportCaption($this->facebook);
					if ($this->google->Exportable) $Doc->ExportCaption($this->google);
					if ($this->linkedin->Exportable) $Doc->ExportCaption($this->linkedin);
					if ($this->pinterest->Exportable) $Doc->ExportCaption($this->pinterest);
				} else {
					if ($this->teacher_id->Exportable) $Doc->ExportCaption($this->teacher_id);
					if ($this->photo->Exportable) $Doc->ExportCaption($this->photo);
					if ($this->active->Exportable) $Doc->ExportCaption($this->active);
					if ($this->twitter->Exportable) $Doc->ExportCaption($this->twitter);
					if ($this->facebook->Exportable) $Doc->ExportCaption($this->facebook);
					if ($this->google->Exportable) $Doc->ExportCaption($this->google);
					if ($this->linkedin->Exportable) $Doc->ExportCaption($this->linkedin);
					if ($this->pinterest->Exportable) $Doc->ExportCaption($this->pinterest);
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
						if ($this->teacher_id->Exportable) $Doc->ExportField($this->teacher_id);
						if ($this->name->Exportable) $Doc->ExportField($this->name);
						if ($this->birthday->Exportable) $Doc->ExportField($this->birthday);
						if ($this->sex->Exportable) $Doc->ExportField($this->sex);
						if ($this->religion->Exportable) $Doc->ExportField($this->religion);
						if ($this->blood_group->Exportable) $Doc->ExportField($this->blood_group);
						if ($this->address->Exportable) $Doc->ExportField($this->address);
						if ($this->phone->Exportable) $Doc->ExportField($this->phone);
						if ($this->_email->Exportable) $Doc->ExportField($this->_email);
						if ($this->password->Exportable) $Doc->ExportField($this->password);
						if ($this->authentication_key->Exportable) $Doc->ExportField($this->authentication_key);
						if ($this->photo->Exportable) $Doc->ExportField($this->photo);
						if ($this->active->Exportable) $Doc->ExportField($this->active);
						if ($this->teacher_image->Exportable) $Doc->ExportField($this->teacher_image);
						if ($this->twitter->Exportable) $Doc->ExportField($this->twitter);
						if ($this->facebook->Exportable) $Doc->ExportField($this->facebook);
						if ($this->google->Exportable) $Doc->ExportField($this->google);
						if ($this->linkedin->Exportable) $Doc->ExportField($this->linkedin);
						if ($this->pinterest->Exportable) $Doc->ExportField($this->pinterest);
					} else {
						if ($this->teacher_id->Exportable) $Doc->ExportField($this->teacher_id);
						if ($this->photo->Exportable) $Doc->ExportField($this->photo);
						if ($this->active->Exportable) $Doc->ExportField($this->active);
						if ($this->twitter->Exportable) $Doc->ExportField($this->twitter);
						if ($this->facebook->Exportable) $Doc->ExportField($this->facebook);
						if ($this->google->Exportable) $Doc->ExportField($this->google);
						if ($this->linkedin->Exportable) $Doc->ExportField($this->linkedin);
						if ($this->pinterest->Exportable) $Doc->ExportField($this->pinterest);
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
