<?php
defined('_JEXEC') or die('Restricted access');

// Include the syndicate functions only once
require_once (dirname(__FILE__).DIRECTORY_SEPARATOR.'Helper'.DIRECTORY_SEPARATOR.'EasyFileUploaderHelper.php');

use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Factory;
use Joomla\CMS\Helper\ModuleHelper;
use AG\Module\EasyFileUploader\Site\Helper\EasyFileUploaderHelper;

if (isset($_FILES[$params->get('ag_variable')]))
{
	$result = EasyFileUploaderHelper::getFileToUpload($params);
}

require(ModuleHelper::getLayoutPath('mod_agosmsfileuploader', $params->get('layout', 'default')));
