<?php

// Global variable for table object
$student = NULL;

//
// Table class for student
//
class cstudent extends cTable {
	var $student_id;
	var $name;
	var $birthday;
	var $sex;
	var $religion;
	var $blood_group;
	var $address;
	var $phone;
	var $_email;
	var $password;
	var $class_id;
	var $section_id;
	var $parent_id;
	var $roll;
	var $transport_id;
	var $dormitory_id;
	var $dormitory_room_number;
	var $authentication_key;
	var $image;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'student';
		$this->TableName = 'student';
		$this->TableType = 'TABLE';

		// Update Table
		$this->UpdateTable = "`student`";
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

		// student_id
		$this->student_id = new cField('student', 'student', 'x_student_id', 'student_id', '`student_id`', '`student_id`', 3, -1, FALSE, '`student_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'NO');
		$this->student_id->Sortable = TRUE; // Allow sort
		$this->student_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['student_id'] = &$this->student_id;

		// name
		$this->name = new cField('student', 'student', 'x_name', 'name', '`name`', '`name`', 201, -1, FALSE, '`name`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->name->Sortable = TRUE; // Allow sort
		$this->fields['name'] = &$this->name;

		// birthday
		$this->birthday = new cField('student', 'student', 'x_birthday', 'birthday', '`birthday`', '`birthday`', 201, 2, FALSE, '`birthday`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->birthday->Sortable = TRUE; // Allow sort
		$this->fields['birthday'] = &$this->birthday;

		// sex
		$this->sex = new cField('student', 'student', 'x_sex', 'sex', '`sex`', '`sex`', 201, -1, FALSE, '`sex`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->sex->Sortable = TRUE; // Allow sort
		$this->sex->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->sex->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->sex->OptionCount = 2;
		$this->fields['sex'] = &$this->sex;

		// religion
		$this->religion = new cField('student', 'student', 'x_religion', 'religion', '`religion`', '`religion`', 201, -1, FALSE, '`religion`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->religion->Sortable = TRUE; // Allow sort
		$this->fields['religion'] = &$this->religion;

		// blood_group
		$this->blood_group = new cField('student', 'student', 'x_blood_group', 'blood_group', '`blood_group`', '`blood_group`', 201, -1, FALSE, '`blood_group`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->blood_group->Sortable = TRUE; // Allow sort
		$this->blood_group->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->blood_group->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->blood_group->OptionCount = 5;
		$this->fields['blood_group'] = &$this->blood_group;

		// address
		$this->address = new cField('student', 'student', 'x_address', 'address', '`address`', '`address`', 201, -1, FALSE, '`address`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->address->Sortable = TRUE; // Allow sort
		$this->fields['address'] = &$this->address;

		// phone
		$this->phone = new cField('student', 'student', 'x_phone', 'phone', '`phone`', '`phone`', 201, -1, FALSE, '`phone`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->phone->Sortable = TRUE; // Allow sort
		$this->fields['phone'] = &$this->phone;

		// email
		$this->_email = new cField('student', 'student', 'x__email', 'email', '`email`', '`email`', 201, -1, FALSE, '`email`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->_email->Sortable = TRUE; // Allow sort
		$this->fields['email'] = &$this->_email;

		// password
		$this->password = new cField('student', 'student', 'x_password', 'password', '`password`', '`password`', 201, -1, FALSE, '`password`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'PASSWORD');
		$this->password->Sortable = TRUE; // Allow sort
		$this->fields['password'] = &$this->password;

		// class_id
		$this->class_id = new cField('student', 'student', 'x_class_id', 'class_id', '`class_id`', '`class_id`', 201, -1, FALSE, '`EV__class_id`', TRUE, TRUE, TRUE, 'FORMATTED TEXT', 'SELECT');
		$this->class_id->Sortable = TRUE; // Allow sort
		$this->class_id->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->class_id->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->fields['class_id'] = &$this->class_id;

		// section_id
		$this->section_id = new cField('student', 'student', 'x_section_id', 'section_id', '`section_id`', '`section_id`', 3, -1, FALSE, '`EV__section_id`', TRUE, TRUE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->section_id->Sortable = TRUE; // Allow sort
		$this->section_id->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->section_id->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->section_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['section_id'] = &$this->section_id;

		// parent_id
		$this->parent_id = new cField('student', 'student', 'x_parent_id', 'parent_id', '`parent_id`', '`parent_id`', 3, -1, FALSE, '`parent_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->parent_id->Sortable = TRUE; // Allow sort
		$this->parent_id->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->parent_id->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->parent_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['parent_id'] = &$this->parent_id;

		// roll
		$this->roll = new cField('student', 'student', 'x_roll', 'roll', '`roll`', '`roll`', 201, -1, FALSE, '`roll`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->roll->Sortable = TRUE; // Allow sort
		$this->fields['roll'] = &$this->roll;

		// transport_id
		$this->transport_id = new cField('student', 'student', 'x_transport_id', 'transport_id', '`transport_id`', '`transport_id`', 3, -1, FALSE, '`EV__transport_id`', TRUE, TRUE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->transport_id->Sortable = TRUE; // Allow sort
		$this->transport_id->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->transport_id->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->transport_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['transport_id'] = &$this->transport_id;

		// dormitory_id
		$this->dormitory_id = new cField('student', 'student', 'x_dormitory_id', 'dormitory_id', '`dormitory_id`', '`dormitory_id`', 3, -1, FALSE, '`EV__dormitory_id`', TRUE, TRUE, TRUE, 'FORMATTED TEXT', 'SELECT');
		$this->dormitory_id->Sortable = TRUE; // Allow sort
		$this->dormitory_id->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->dormitory_id->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->dormitory_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['dormitory_id'] = &$this->dormitory_id;

		// dormitory_room_number
		$this->dormitory_room_number = new cField('student', 'student', 'x_dormitory_room_number', 'dormitory_room_number', '`dormitory_room_number`', '`dormitory_room_number`', 201, -1, FALSE, '`dormitory_room_number`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->dormitory_room_number->Sortable = TRUE; // Allow sort
		$this->fields['dormitory_room_number'] = &$this->dormitory_room_number;

		// authentication_key
		$this->authentication_key = new cField('student', 'student', 'x_authentication_key', 'authentication_key', '`authentication_key`', '`authentication_key`', 201, -1, FALSE, '`authentication_key`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->authentication_key->Sortable = TRUE; // Allow sort
		$this->fields['authentication_key'] = &$this->authentication_key;

		// image
		$this->image = new cField('student', 'student', 'x_image', 'image', '`image`', '`image`', 200, -1, TRUE, '`image`', FALSE, FALSE, FALSE, 'IMAGE', 'FILE');
		$this->image->Sortable = TRUE; // Allow sort
		$this->fields['image'] = &$this->image;
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
			$sSortFieldList = ($ofld->FldVirtualExpression <> "") ? $ofld->FldVirtualExpression : $sSortField;
			$this->setSessionOrderByList($sSortFieldList . " " . $sThisSort); // Save to Session
		} else {
			$ofld->setSort("");
		}
	}

	// Session ORDER BY for List page
	function getSessionOrderByList() {
		return @$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_ORDER_BY_LIST];
	}

	function setSessionOrderByList($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_ORDER_BY_LIST] = $v;
	}

	// Table level SQL
	var $_SqlFrom = "";

	function getSqlFrom() { // From
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`student`";
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
	var $_SqlSelectList = "";

	function getSqlSelectList() { // Select for List page
		$select = "";
		$select = "SELECT * FROM (" .
			"SELECT *, (SELECT `name` FROM `class` `EW_TMP_LOOKUPTABLE` WHERE `EW_TMP_LOOKUPTABLE`.`class_id` = `student`.`class_id` LIMIT 1) AS `EV__class_id`, (SELECT `name` FROM `section` `EW_TMP_LOOKUPTABLE` WHERE `EW_TMP_LOOKUPTABLE`.`section_id` = `student`.`section_id` LIMIT 1) AS `EV__section_id`, (SELECT DISTINCT CONCAT(COALESCE(`route_name`, ''),'" . ew_ValueSeparator(1, $this->transport_id) . "',COALESCE(`number_of_vehicle`,'')) FROM `transport` `EW_TMP_LOOKUPTABLE` WHERE `EW_TMP_LOOKUPTABLE`.`transport_id` = `student`.`transport_id` LIMIT 1) AS `EV__transport_id`, (SELECT DISTINCT CONCAT(COALESCE(`name`, ''),'" . ew_ValueSeparator(1, $this->dormitory_id) . "',COALESCE(`number_of_room`,'')) FROM `dormitory` `EW_TMP_LOOKUPTABLE` WHERE `EW_TMP_LOOKUPTABLE`.`dormitory_id` = `student`.`dormitory_id` LIMIT 1) AS `EV__dormitory_id` FROM `student`" .
			") `EW_TMP_TABLE`";
		return ($this->_SqlSelectList <> "") ? $this->_SqlSelectList : $select;
	}

	function SqlSelectList() { // For backward compatibility
		return $this->getSqlSelectList();
	}

	function setSqlSelectList($v) {
		$this->_SqlSelectList = $v;
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
		if ($this->UseVirtualFields()) {
			$sSelect = $this->getSqlSelectList();
			$sSort = $this->UseSessionForListSQL ? $this->getSessionOrderByList() : "";
		} else {
			$sSelect = $this->getSqlSelect();
			$sSort = $this->UseSessionForListSQL ? $this->getSessionOrderBy() : "";
		}
		return ew_BuildSelectSql($sSelect, $this->getSqlWhere(), $this->getSqlGroupBy(),
			$this->getSqlHaving(), $this->getSqlOrderBy(), $sFilter, $sSort);
	}

	// Get ORDER BY clause
	function GetOrderBy() {
		$sSort = ($this->UseVirtualFields()) ? $this->getSessionOrderByList() : $this->getSessionOrderBy();
		return ew_BuildSelectSql("", "", "", "", $this->getSqlOrderBy(), "", $sSort);
	}

	// Check if virtual fields is used in SQL
	function UseVirtualFields() {
		$sWhere = $this->UseSessionForListSQL ? $this->getSessionWhere() : $this->CurrentFilter;
		$sOrderBy = $this->UseSessionForListSQL ? $this->getSessionOrderByList() : "";
		if ($sWhere <> "")
			$sWhere = " " . str_replace(array("(",")"), array("",""), $sWhere) . " ";
		if ($sOrderBy <> "")
			$sOrderBy = " " . str_replace(array("(",")"), array("",""), $sOrderBy) . " ";
		if ($this->class_id->AdvancedSearch->SearchValue <> "" ||
			$this->class_id->AdvancedSearch->SearchValue2 <> "" ||
			strpos($sWhere, " " . $this->class_id->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if (strpos($sOrderBy, " " . $this->class_id->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if (strpos($sOrderBy, " " . $this->section_id->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if (strpos($sOrderBy, " " . $this->transport_id->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if ($this->dormitory_id->AdvancedSearch->SearchValue <> "" ||
			$this->dormitory_id->AdvancedSearch->SearchValue2 <> "" ||
			strpos($sWhere, " " . $this->dormitory_id->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if (strpos($sOrderBy, " " . $this->dormitory_id->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		return FALSE;
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
		if ($this->UseVirtualFields())
			$sql = ew_BuildSelectSql($this->getSqlSelectList(), $this->getSqlWhere(), $groupBy, $having, "", $filter, "");
		else
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
			$this->student_id->setDbValue($conn->Insert_ID());
			$rs['student_id'] = $this->student_id->DbValue;
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
			if (array_key_exists('student_id', $rs))
				ew_AddFilter($where, ew_QuotedName('student_id', $this->DBID) . '=' . ew_QuotedValue($rs['student_id'], $this->student_id->FldDataType, $this->DBID));
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
		return "`student_id` = @student_id@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->student_id->CurrentValue))
			return "0=1"; // Invalid key
		if (is_null($this->student_id->CurrentValue))
			return "0=1"; // Invalid key
		else
			$sKeyFilter = str_replace("@student_id@", ew_AdjustSql($this->student_id->CurrentValue, $this->DBID), $sKeyFilter); // Replace key value
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
			return "studentlist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// Get modal caption
	function GetModalCaption($pageName) {
		global $Language;
		if ($pageName == "studentview.php")
			return $Language->Phrase("View");
		elseif ($pageName == "studentedit.php")
			return $Language->Phrase("Edit");
		elseif ($pageName == "studentadd.php")
			return $Language->Phrase("Add");
		else
			return "";
	}

	// List URL
	function GetListUrl() {
		return "studentlist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("studentview.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("studentview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			$url = "studentadd.php?" . $this->UrlParm($parm);
		else
			$url = "studentadd.php";
		return $this->AddMasterUrl($url);
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		$url = $this->KeyUrl("studentedit.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
		return $this->AddMasterUrl($url);
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		$url = $this->KeyUrl("studentadd.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
		return $this->AddMasterUrl($url);
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("studentdelete.php", $this->UrlParm());
	}

	// Add master url
	function AddMasterUrl($url) {
		return $url;
	}

	function KeyToJson() {
		$json = "";
		$json .= "student_id:" . ew_VarToJson($this->student_id->CurrentValue, "number", "'");
		return "{" . $json . "}";
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->student_id->CurrentValue)) {
			$sUrl .= "student_id=" . urlencode($this->student_id->CurrentValue);
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
			if ($isPost && isset($_POST["student_id"]))
				$arKeys[] = $_POST["student_id"];
			elseif (isset($_GET["student_id"]))
				$arKeys[] = $_GET["student_id"];
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
			$this->student_id->CurrentValue = $key;
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
		$this->student_id->setDbValue($rs->fields('student_id'));
		$this->name->setDbValue($rs->fields('name'));
		$this->birthday->setDbValue($rs->fields('birthday'));
		$this->sex->setDbValue($rs->fields('sex'));
		$this->religion->setDbValue($rs->fields('religion'));
		$this->blood_group->setDbValue($rs->fields('blood_group'));
		$this->address->setDbValue($rs->fields('address'));
		$this->phone->setDbValue($rs->fields('phone'));
		$this->_email->setDbValue($rs->fields('email'));
		$this->password->setDbValue($rs->fields('password'));
		$this->class_id->setDbValue($rs->fields('class_id'));
		$this->section_id->setDbValue($rs->fields('section_id'));
		$this->parent_id->setDbValue($rs->fields('parent_id'));
		$this->roll->setDbValue($rs->fields('roll'));
		$this->transport_id->setDbValue($rs->fields('transport_id'));
		$this->dormitory_id->setDbValue($rs->fields('dormitory_id'));
		$this->dormitory_room_number->setDbValue($rs->fields('dormitory_room_number'));
		$this->authentication_key->setDbValue($rs->fields('authentication_key'));
		$this->image->Upload->DbValue = $rs->fields('image');
	}

	// Render list row values
	function RenderListRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

	// Common render codes
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

		// student_id
		$this->student_id->EditAttrs["class"] = "form-control";
		$this->student_id->EditCustomAttributes = "";
		$this->student_id->EditValue = $this->student_id->CurrentValue;
		$this->student_id->ViewCustomAttributes = "";

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
		$this->blood_group->EditCustomAttributes = "";
		$this->blood_group->EditValue = $this->blood_group->Options(TRUE);

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

		// class_id
		$this->class_id->EditAttrs["class"] = "form-control";
		$this->class_id->EditCustomAttributes = "";

		// section_id
		$this->section_id->EditAttrs["class"] = "form-control";
		$this->section_id->EditCustomAttributes = "";

		// parent_id
		$this->parent_id->EditAttrs["class"] = "form-control";
		$this->parent_id->EditCustomAttributes = "";

		// roll
		$this->roll->EditAttrs["class"] = "form-control";
		$this->roll->EditCustomAttributes = "";
		$this->roll->EditValue = $this->roll->CurrentValue;
		$this->roll->PlaceHolder = ew_RemoveHtml($this->roll->FldCaption());

		// transport_id
		$this->transport_id->EditAttrs["class"] = "form-control";
		$this->transport_id->EditCustomAttributes = "";

		// dormitory_id
		$this->dormitory_id->EditAttrs["class"] = "form-control";
		$this->dormitory_id->EditCustomAttributes = "";

		// dormitory_room_number
		$this->dormitory_room_number->EditAttrs["class"] = "form-control";
		$this->dormitory_room_number->EditCustomAttributes = "";
		$this->dormitory_room_number->EditValue = $this->dormitory_room_number->CurrentValue;
		$this->dormitory_room_number->PlaceHolder = ew_RemoveHtml($this->dormitory_room_number->FldCaption());

		// authentication_key
		$this->authentication_key->EditAttrs["class"] = "form-control";
		$this->authentication_key->EditCustomAttributes = "";
		$this->authentication_key->EditValue = $this->authentication_key->CurrentValue;
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
					if ($this->student_id->Exportable) $Doc->ExportCaption($this->student_id);
					if ($this->name->Exportable) $Doc->ExportCaption($this->name);
					if ($this->birthday->Exportable) $Doc->ExportCaption($this->birthday);
					if ($this->sex->Exportable) $Doc->ExportCaption($this->sex);
					if ($this->religion->Exportable) $Doc->ExportCaption($this->religion);
					if ($this->blood_group->Exportable) $Doc->ExportCaption($this->blood_group);
					if ($this->address->Exportable) $Doc->ExportCaption($this->address);
					if ($this->phone->Exportable) $Doc->ExportCaption($this->phone);
					if ($this->_email->Exportable) $Doc->ExportCaption($this->_email);
					if ($this->password->Exportable) $Doc->ExportCaption($this->password);
					if ($this->class_id->Exportable) $Doc->ExportCaption($this->class_id);
					if ($this->section_id->Exportable) $Doc->ExportCaption($this->section_id);
					if ($this->parent_id->Exportable) $Doc->ExportCaption($this->parent_id);
					if ($this->roll->Exportable) $Doc->ExportCaption($this->roll);
					if ($this->transport_id->Exportable) $Doc->ExportCaption($this->transport_id);
					if ($this->dormitory_id->Exportable) $Doc->ExportCaption($this->dormitory_id);
					if ($this->dormitory_room_number->Exportable) $Doc->ExportCaption($this->dormitory_room_number);
					if ($this->authentication_key->Exportable) $Doc->ExportCaption($this->authentication_key);
					if ($this->image->Exportable) $Doc->ExportCaption($this->image);
				} else {
					if ($this->student_id->Exportable) $Doc->ExportCaption($this->student_id);
					if ($this->name->Exportable) $Doc->ExportCaption($this->name);
					if ($this->sex->Exportable) $Doc->ExportCaption($this->sex);
					if ($this->phone->Exportable) $Doc->ExportCaption($this->phone);
					if ($this->_email->Exportable) $Doc->ExportCaption($this->_email);
					if ($this->section_id->Exportable) $Doc->ExportCaption($this->section_id);
					if ($this->parent_id->Exportable) $Doc->ExportCaption($this->parent_id);
					if ($this->transport_id->Exportable) $Doc->ExportCaption($this->transport_id);
					if ($this->dormitory_id->Exportable) $Doc->ExportCaption($this->dormitory_id);
					if ($this->image->Exportable) $Doc->ExportCaption($this->image);
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
						if ($this->student_id->Exportable) $Doc->ExportField($this->student_id);
						if ($this->name->Exportable) $Doc->ExportField($this->name);
						if ($this->birthday->Exportable) $Doc->ExportField($this->birthday);
						if ($this->sex->Exportable) $Doc->ExportField($this->sex);
						if ($this->religion->Exportable) $Doc->ExportField($this->religion);
						if ($this->blood_group->Exportable) $Doc->ExportField($this->blood_group);
						if ($this->address->Exportable) $Doc->ExportField($this->address);
						if ($this->phone->Exportable) $Doc->ExportField($this->phone);
						if ($this->_email->Exportable) $Doc->ExportField($this->_email);
						if ($this->password->Exportable) $Doc->ExportField($this->password);
						if ($this->class_id->Exportable) $Doc->ExportField($this->class_id);
						if ($this->section_id->Exportable) $Doc->ExportField($this->section_id);
						if ($this->parent_id->Exportable) $Doc->ExportField($this->parent_id);
						if ($this->roll->Exportable) $Doc->ExportField($this->roll);
						if ($this->transport_id->Exportable) $Doc->ExportField($this->transport_id);
						if ($this->dormitory_id->Exportable) $Doc->ExportField($this->dormitory_id);
						if ($this->dormitory_room_number->Exportable) $Doc->ExportField($this->dormitory_room_number);
						if ($this->authentication_key->Exportable) $Doc->ExportField($this->authentication_key);
						if ($this->image->Exportable) $Doc->ExportField($this->image);
					} else {
						if ($this->student_id->Exportable) $Doc->ExportField($this->student_id);
						if ($this->name->Exportable) $Doc->ExportField($this->name);
						if ($this->sex->Exportable) $Doc->ExportField($this->sex);
						if ($this->phone->Exportable) $Doc->ExportField($this->phone);
						if ($this->_email->Exportable) $Doc->ExportField($this->_email);
						if ($this->section_id->Exportable) $Doc->ExportField($this->section_id);
						if ($this->parent_id->Exportable) $Doc->ExportField($this->parent_id);
						if ($this->transport_id->Exportable) $Doc->ExportField($this->transport_id);
						if ($this->dormitory_id->Exportable) $Doc->ExportField($this->dormitory_id);
						if ($this->image->Exportable) $Doc->ExportField($this->image);
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
