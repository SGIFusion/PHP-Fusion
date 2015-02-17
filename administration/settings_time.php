<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) PHP-Fusion Inc
| https://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: settings_time.php
| Author: Nick Jones (Digitanium)
+--------------------------------------------------------+
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
+--------------------------------------------------------*/
require_once "../maincore.php";

if (!checkrights("S2") || !defined("iAUTH") || !isset($_GET['aid']) || $_GET['aid'] != iAUTH) {
	redirect("../index.php");
}

require_once THEMES."templates/admin_header.php";
include LOCALE.LOCALESET."admin/settings.php";

if (isset($_GET['error']) && isnum($_GET['error']) && !isset($message)) {
	if ($_GET['error'] == 0) {
		$message = $locale['900'];
	} elseif ($_GET['error'] == 1) {
		$message = $locale['901'];
	}
	if (isset($message)) {
		echo "<div id='close-message'><div class='admin-message alert alert-info m-t-10'>".$message."</div></div>\n";
	}
}

if (isset($_POST['savesettings'])) {
	$error = 0;
	if (!defined('FUSION_NULL')) {
		$result = dbquery("UPDATE ".DB_SETTINGS." SET settings_value='".stripinput($_POST['shortdate'])."' WHERE settings_name='shortdate'");
		if (!$result) {
			$error = 1;
		}
		$result = dbquery("UPDATE ".DB_SETTINGS." SET settings_value='".stripinput($_POST['longdate'])."' WHERE settings_name='longdate'");
		if (!$result) {
			$error = 1;
		}
		$result = dbquery("UPDATE ".DB_SETTINGS." SET settings_value='".stripinput($_POST['forumdate'])."' WHERE settings_name='forumdate'");
		if (!$result) {
			$error = 1;
		}
		$result = dbquery("UPDATE ".DB_SETTINGS." SET settings_value='".stripinput($_POST['newsdate'])."' WHERE settings_name='newsdate'");
		if (!$result) {
			$error = 1;
		}
		$result = dbquery("UPDATE ".DB_SETTINGS." SET settings_value='".stripinput($_POST['subheaderdate'])."' WHERE settings_name='subheaderdate'");
		if (!$result) {
			$error = 1;
		}
		$result = dbquery("UPDATE ".DB_SETTINGS." SET settings_value='".stripinput($_POST['timeoffset'])."' WHERE settings_name='timeoffset'");
		if (!$result) {
			$error = 1;
		}
		$result = dbquery("UPDATE ".DB_SETTINGS." SET settings_value='".stripinput($_POST['serveroffset'])."' WHERE settings_name='serveroffset'");
		if (!$result) {
			$error = 1;
		}
		$result = dbquery("UPDATE ".DB_SETTINGS." SET settings_value='".stripinput($_POST['default_timezone'])."' WHERE settings_name='default_timezone'");
		if (!$result) {
			$error = 1;
		}
		redirect(FUSION_SELF.$aidlink."&error=".$error);
	}
}

$settings2 = array();
$result = dbquery("SELECT * FROM ".DB_SETTINGS);
while ($data = dbarray($result)) {
	$settings2[$data['settings_name']] = $data['settings_value'];
}

$timezones = timezone_abbreviations_list();
$timezoneArray = array();
foreach ($timezones as $zones) {
	foreach ($zones as $zone) {
		if (preg_match('/^(America|Antartica|Arctic|Asia|Atlantic|Europe|Indian|Pacific)\//', $zone['timezone_id'])) {
			if (!in_array($zone['timezone_id'], $timezoneArray)) {
				$timezoneArray[$zone['timezone_id']] = $zone['timezone_id'];
			}
		}
	}
}

unset($dummy);
unset($timezones);

$timestamp = time()+($settings2['timeoffset']*3600);

$date_opts = array();
foreach ($locale['dateformats'] as $dateformat) {
	$date_opts[$dateformat] = strftime($dateformat, $timestamp);
}
unset($dateformat);

opentable($locale['400']);

echo openform('settingsform', 'settingsform', 'post', FUSION_SELF.$aidlink, array('downtime' => 1));
echo "<table class='table table-responsive center'>\n<tbody>\n<tr>\n";
echo "<td valign='top' width='40%' class='tbl'><strong>".$locale['458']." (".$locale['459']."):</strong></td>\n";
echo "<td width='60%' class='tbl'>".strftime($settings2['longdate'], (time())+($settings2['serveroffset']*3600))."</td>\n";
echo "</tr>\n<tr>\n";
echo "<td valign='top' class='tbl'><strong>".$locale['458']." (".$locale['460']."):</strong></td>\n";
echo "<td class='tbl'>".showdate("longdate", time())."</td>\n";
echo "</tr>\n<tr>\n";
echo "<td valign='top' class='tbl'><strong>".$locale['458']." (".$locale['461']."):</strong></td>\n";
echo "<td class='tbl'>".strftime($settings2['longdate'], time()+(($settings2['serveroffset']+$settings2['timeoffset'])*3600))."</td>\n";
echo "</tr>\n<tr>\n";
echo "<td class='tbl2' colspan='2'>\n<strong>".$locale['400']." - ".$locale['450']."</strong>\n</td>\n";
echo "</tr>\n<tr>\n";
echo "<td valign='top' class='tbl'>\n<label for='shortdate'>".$locale['451']."</label></td>\n";
echo "<td class='tbl'>\n";
echo form_select('', 'shortdate', 'shortdate', $date_opts, $settings2['shortdate'], array('placeholder' => $locale['455']));
echo "</td>\n</tr>\n<tr>\n";
echo "<td valign='top' class='tbl'>\n<label for='longdate'>".$locale['452']."</label></td>\n";
echo "<td class='tbl'>\n";
echo form_select('', 'longdate', 'longdate', $date_opts, $settings2['longdate'], array('placeholder' => $locale['455']));
echo "</td>\n</tr>\n<tr>\n";
echo "<td valign='top' class='tbl'>\n<label for='forumdatetext'>".$locale['453']."</label></td>\n";
echo "<td class='tbl'>\n";
echo form_select('', 'forumdate', 'forumdate', $date_opts, $settings2['forumdate'], array('placeholder' => $locale['455']));
echo "</tr>\n<tr>\n";
echo "<td valign='top' class='tbl'>\n<label for='newsdate'>".$locale['457']."</label></td>\n";
echo "<td class='tbl'>\n";
echo form_select('', 'newsdate', 'newsdate', $date_opts, $settings2['newsdate'], array('placeholder' => $locale['455']));
echo "</tr>\n<tr>\n";
echo "<td valign='top' class='tbl'>\n<label for='subheaderdate'>".$locale['454']."</label>\n</td>\n";
echo "<td class='tbl'>\n";
echo form_select('', 'subheaderdate', 'subheaderdate', $date_opts, $settings2['subheaderdate'], array('placeholder' => $locale['455']));
echo "</tr>\n<tr>\n";
echo "<td class='tbl'>\n<label for='serveroffset'>".$locale['462']."</label>\n<br /><span class='small'>(".$locale['463'].")</span></td>\n";
echo "<td class='tbl'>\n";
echo form_select('', 'serveroffset', 'serveroffset', $timezoneArray, $settings2['serveroffset']);
echo "</td>\n</tr>\n<tr>\n";
echo "<td class='tbl'>\n<label for='timeoffset'>".$locale['456']."</label>\n</td>\n";
echo "<td class='tbl'>\n";
echo form_select('', 'timeoffset', 'timeoffset', $timezoneArray, $settings2['timeoffset'], array('width' => '100%'));
echo "</tr>\n<tr>\n";
echo "<td class='tbl'>\n<label for='default_timezone'>".$locale['464']."</label>\n</td>\n";
echo "<td class='tbl'>\n";
echo form_select('', 'default_timezone', 'default_timezone', $timezoneArray, $settings2['default_timezone']);
echo "</td>\n</tr>\n<tr>\n";
echo "<td align='center' colspan='2' class='tbl'><br />\n";
echo form_button($locale['750'], 'savesettings', 'savesettings', $locale['750'], array('class' => 'btn-primary'));
echo "</td>\n</tr>\n</tbody>\n</table>\n</form>\n";
closetable();

require_once THEMES."templates/footer.php";
?>
