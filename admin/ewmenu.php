<?php

// Menu
$RootMenu = new cMenu("RootMenu", TRUE);
$RootMenu->AddMenuItem(1, "mi_admin", $Language->MenuPhrase("1", "MenuText"), "adminlist.php", -1, "", IsLoggedIn() || AllowListMenu('{1F40EE59-17A4-45D3-96B6-AA2082B3D64A}admin'), FALSE, FALSE, "");
$RootMenu->AddMenuItem(3, "mi_book", $Language->MenuPhrase("3", "MenuText"), "booklist.php", -1, "", IsLoggedIn() || AllowListMenu('{1F40EE59-17A4-45D3-96B6-AA2082B3D64A}book'), FALSE, FALSE, "");
$RootMenu->AddMenuItem(5, "mi_class", $Language->MenuPhrase("5", "MenuText"), "classlist.php?cmd=resetall", -1, "", IsLoggedIn() || AllowListMenu('{1F40EE59-17A4-45D3-96B6-AA2082B3D64A}class'), FALSE, FALSE, "");
$RootMenu->AddMenuItem(14, "mi_lessons", $Language->MenuPhrase("14", "MenuText"), "lessonslist.php", -1, "", IsLoggedIn() || AllowListMenu('{1F40EE59-17A4-45D3-96B6-AA2082B3D64A}lessons'), FALSE, FALSE, "");
$RootMenu->AddMenuItem(22, "mi_settings", $Language->MenuPhrase("22", "MenuText"), "settingslist.php", -1, "", IsLoggedIn() || AllowListMenu('{1F40EE59-17A4-45D3-96B6-AA2082B3D64A}settings'), FALSE, FALSE, "");
$RootMenu->AddMenuItem(23, "mi_slides", $Language->MenuPhrase("23", "MenuText"), "slideslist.php", -1, "", IsLoggedIn() || AllowListMenu('{1F40EE59-17A4-45D3-96B6-AA2082B3D64A}slides'), FALSE, FALSE, "");
$RootMenu->AddMenuItem(24, "mi_student", $Language->MenuPhrase("24", "MenuText"), "studentlist.php", -1, "", IsLoggedIn() || AllowListMenu('{1F40EE59-17A4-45D3-96B6-AA2082B3D64A}student'), FALSE, FALSE, "");
$RootMenu->AddMenuItem(25, "mi_subject", $Language->MenuPhrase("25", "MenuText"), "subjectlist.php?cmd=resetall", -1, "", IsLoggedIn() || AllowListMenu('{1F40EE59-17A4-45D3-96B6-AA2082B3D64A}subject'), FALSE, FALSE, "");
$RootMenu->AddMenuItem(26, "mi_teacher", $Language->MenuPhrase("26", "MenuText"), "teacherlist.php", -1, "", IsLoggedIn() || AllowListMenu('{1F40EE59-17A4-45D3-96B6-AA2082B3D64A}teacher'), FALSE, FALSE, "fa fa-user");
$RootMenu->AddMenuItem(28, "mi_users", $Language->MenuPhrase("28", "MenuText"), "userslist.php", -1, "", IsLoggedIn() || AllowListMenu('{1F40EE59-17A4-45D3-96B6-AA2082B3D64A}users'), FALSE, FALSE, "");
echo $RootMenu->ToScript();
?>
<div class="ewVertical" id="ewMenu"></div>
