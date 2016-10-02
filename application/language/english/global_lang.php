<?php
/**
 * Global language file
 */
defined('BASEPATH') OR exit('No direct script access allowed');

$lang['submit']         = "Submit";
$lang['update']         = "Update";
$lang['reset']          = "Reset";
$lang['create']         = "Create";
$lang['search']         = "Search";
$lang['action']         = "Action";
$lang['actions']        = "Actions";
$lang['show']           = "Show";
$lang['edit']           = "Edit";
$lang['delete']         = "Delete";
$lang['list']           = "List";
$lang['home']           = "Home";
$lang['view']           = "View";

$lang['login']          = "Login";
$lang['logout']         = "Logout";
$lang['permissions']    = "Permissions";
$lang['groups']         = "Groups";
$lang['profile']        = "Profile";
$lang['account']        = "Account";

$lang['language_switched']      = "Language switched";
$lang['see_all_notifications']  = "See All Notifications";
$lang['row_is_not_found']       = "Row is not found";
$lang['row_has_been_created']   = "Row has been created";
$lang['row_has_been_updated']   = "Row has been updated";
$lang['row_has_been_deleted']   = "Row has been deleted";
$lang['msg_are_yousure']        = "Are you sure you want to do this action?";

$lang['status/active']  = "Active";
$lang['status/inactive']    = "Inactive";

// auto include all language files started with global_
$lang_files = glob(APPPATH.'language/english/global_*_lang.php');
foreach ($lang_files as $key => $lang_file) {
    include($lang_file);
}
