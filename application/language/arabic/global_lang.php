<?php
/**
 * Global language file
 */
defined('BASEPATH') OR exit('No direct script access allowed');

$lang['submit']         = "إرسال";
$lang['update']         = "تحديث";
$lang['reset']          = "مسح النموذج";
$lang['create']         = "إنشاء";
$lang['search']         = "بحث";
$lang['action']         = "اجراء";
$lang['actions']        = "إجراءات";
$lang['show']           = "عرض";
$lang['edit']           = "تعديل";
$lang['delete']         = "حذف";
$lang['list']           = "عرض";
$lang['home']           = "الرئيسية";
$lang['view']           = "عرض";

$lang['login']          = "دخول";
$lang['logout']         = "خروج";
$lang['permissions']    = "الصلاحيات";
$lang['groups']         = "المجموعات";
$lang['profile']        = "بياناتي";
$lang['account']        = "الحساب";

$lang['language_switched']      = "تم تغيير اللغة";
$lang['see_all_notifications']  = "عرض جميع التنبيهات";
$lang['row_is_not_found']       = "السجل غير موجود";
$lang['row_has_been_created']   = "تم إنشاء السجل";
$lang['row_has_been_updated']   = "تم تحديث السجل";
$lang['row_has_been_deleted']   = "تم حذف السجل";
$lang['msg_are_yousure']        = "هل أنت متأكد من تنفيذ هذه العملية؟";

$lang['status/active']      = "فعال";
$lang['status/inactive']    = "غير فعال";

// auto include all language files started with global_
$lang_files = glob(APPPATH.'language/arabic/global_*_lang.php');
foreach ($lang_files as $key => $lang_file) {
    include($lang_file);
}
