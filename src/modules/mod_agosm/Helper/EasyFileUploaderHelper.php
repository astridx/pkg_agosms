<?php
/**
 * @package     Joomla.Site
 * @subpackage  pkg_agosms
 *
 * @copyright   Copyright (C) 2005 - 2019 Astrid Günther, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later;
 * @link        astrid-guenther.de
 */
namespace AG\Module\Agosms\Site\Helper;

// no direct access
defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Language\Text;
use Joomla\CMS\Filesystem\File;
use Joomla\CMS\Factory;

//import joomla file helper class
\JLoader::import('joomla.filesystem.file');


class EasyFileUploaderHelper
{
	public static function getFileToUpload(&$params)
	{
		$result = array();
		
		//get the Joomla Path and trim whitespace and slashes from the end
		$jpath = JPATH_SITE;
		$jpath = rtrim($jpath, "/\\ \t\n\r\0\x0B");		
		
		//get the parent folder and trim whitespace and slashes from both ends
		$parent = $params->get('ag_parent');
		$parent = trim($parent, "/\\ \t\n\r\0\x0B");
		
		//get the folder location and trim whitespace and slashes from both ends
		$folder = $params->get('ag_folder');
		$folder = trim($folder, "/\\ \t\n\r\0\x0B");
		$folder = str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $folder);
		
		//compile the full absolute path
		$path = $jpath.DIRECTORY_SEPARATOR.$parent.DIRECTORY_SEPARATOR.$folder;
		$path = rtrim($path, "/\\ \t\n\r\0\x0B");		

		//compile the full relative path
		$relativepath = $parent.DIRECTORY_SEPARATOR.$folder;
		$relativepath = rtrim($relativepath, "/\\ \t\n\r\0\x0B");		
		
		if ($params->get('ag_user') == true)
		{
			//get the user data
			$user = Factory::getUser();
			if ($user->guest == false)
			{
				$path.= DIRECTORY_SEPARATOR.$user->username;
				$relativepath.= DIRECTORY_SEPARATOR.$user->username;
			}
			else
			{
				//Todo
			}
		}
		
		//check to see if the upload process has started
		if (isset($_FILES[$params->get('ag_variable')]))
		{
			//now, we're going to check each of the uploaded files
			$total = intval($params->get('ag_multiple'));
			for ($i = 0; $i < $total; $i++)
			{
				$result[$i]['show'] = true;
				$result[$i]['rpath'] = $relativepath;
				$result[$i]['path'] = $path;
				//so, now, check for any other errors
				if ($_FILES[$params->get('ag_variable')]["error"][$i] > 0)
				{
					//error was found. Show the return code.
					$error_text = Text::_('MOD_AG_RETURN_CODE').": ".$_FILES[$params->get('ag_variable')]["error"][$i]."<br />";
					$error_text.= EasyFileUploaderHelper::fileUploadErrorMessage($_FILES[$params->get('ag_variable')]["error"][$i]);
					
					$result[$i]['type'] = 'error';
					$result[$i]['text'] = $error_text;
					
					//Note that UPLOAD_ERR_NO_FILE = 4
					if ($_FILES[$params->get('ag_variable')]["error"][$i] == UPLOAD_ERR_NO_FILE)
					{
						//set the result type to warning instead of error
						$result[$i]['type'] = 'warning';
						
						//get the value for 'ag_shownofile', the default is 1
						$shownofile = $params->get('ag_shownofile', 1);
						if ($shownofile == false)
						{
							$result[$i]['show'] = false;
						}
					}
				}
				else
				{
					//no errors found.
					//check to see if the file type is correct
					//but first, we have to store the file types in a variable. I was getting an issue with empty()
					if (EasyFileUploaderHelper::isValidFileType($params, $i))
					{
						//the file type is permitted
						//so, check for the right size
						if ($_FILES[$params->get('ag_variable')]["size"][$i] < $params->get('ag_maxsize'))
						{
							//file is an acceptable size
							//check to see if file already exists in the destination folder
							if (file_exists($path.DIRECTORY_SEPARATOR.$_FILES[$params->get('ag_variable')]["name"][$i]))
							{
								//file already exists
								//check whether the user wants to replace the file or not.
								if ($params->get('ag_default_replace') == true || 
									($params->get('ag_replace') == true && $_POST["answer"] == true))
								{
									//yep, the user wants to replace the file, so just delete the existing file
									File::delete($path.DIRECTORY_SEPARATOR.$_FILES[$params->get('ag_variable')]["name"][$i]);
									EasyFileUploaderHelper::storeUploadedFile($path, $params, $result, $i, true);
								}
								else
								{
									$result[$i]['type'] = 'info';
									$result[$i]['text'] = $_FILES[$params->get('ag_variable')]["name"][$i]." ".Text::_('MOD_AG_ALREADY_EXISTS');
								}
							}
							else
							{
								//Check to see if the file meets the safety standards
								$is_safe = EasyFileUploaderHelper::checkFileSafety($params, $result, $i);
								if ($is_safe)
								{
									EasyFileUploaderHelper::storeUploadedFile($path, $params, $result, $i);
								}
							}
						}
						else
						{
							//file is too large
							$result[$i]['type'] = 'warning';
							$result[$i]['text'] = Text::_('MOD_AG_TOO_LARGE_ERROR').EasyFileUploaderHelper::sizeToText($params->get('ag_maxsize')).".";
						}
					}
					else
					{
						//the file type is not permitted
						$fakeMIME = $_FILES[$params->get('ag_variable')]["type"][$i];
						$trueMIME = EasyFileUploaderHelper::actualMIME($_FILES[$params->get('ag_variable')]["tmp_name"][$i]);
						$result[$i]['type'] = 'error';
						$result[$i]['text'] = Text::_('MOD_AG_INVALID_ERROR')."<br />".Text::_('MOD_AG_PHP_MIME_ERROR').($trueMIME!==false?"(".$trueMIME.")":"")."<br />".Text::_('MOD_AG_BROWSER_MIME_ERROR').$fakeMIME;
					}
				}
			}
		}
		
		return $result;
	}
	
	private static function isValidFileType(&$params, &$i)
	{
		$valid = false;
		
		$filetypes = $params->get('ag_filetypes');
		$actualMIME = EasyFileUploaderHelper::actualMIME($_FILES[$params->get('ag_variable')]["tmp_name"][$i]);
		if ($filetypes == "*" || 
			(stripos($filetypes, $_FILES[$params->get('ag_variable')]["type"][$i]) !== false &&
			$actualMIME !== false &&
			stripos($filetypes, $actualMIME) !== false))
		{
			$valid = true;
		}
		
		return $valid;
	}
	
	private static function actualMIME($file)
	{
		if (!file_exists($file))
		{
			return false;
		}
		
		$mime = false;
		// try to use recommended functions
		if (defined('FILEINFO_MIME_TYPE') &&
			function_exists('finfo_open') && is_callable('finfo_open') && 
			function_exists('finfo_file') && is_callable('finfo_file') && 
			function_exists('finfo_close') && is_callable('finfo_close'))
		{
			$finfo = finfo_open(FILEINFO_MIME_TYPE);
			$mime = finfo_file($finfo, $file);
			if ($mime === '')
			{
				$mime = false;
			}
			finfo_close($finfo);
		}
		else if (strtoupper(substr(PHP_OS,0,3)) !== 'WIN')
		{
			$f = "'".$file."'";
			if (function_exists('escapeshellarg') && is_callable('escapeshellarg'))
			{
				//prefer to use escapeshellarg if it is available
				$f = escapeshellarg($file);
			}
			
			if (function_exists('exec') && is_callable('exec'))
			{
				//didn't like how 'system' flushes output to browser. replaced with 'exec'
				//note: You can change this to: shell_exec("file -b --mime-type $f"); if you get
				//      "regular file" as the mime type
				$mime = exec("file -bi $f");
				//this removes the charset value if it was returned with the mime type. mime is first.
				$mime = strtok($mime, '; ');
				$mime = trim($mime); //remove any remaining whitespace
			}
			else if (function_exists('shell_exec') && is_callable('shell_exec'))
			{
				//note: You can change this to: shell_exec("file -b --mime-type $f"); if you get
				//      "regular file" as the mime type
				$mime = shell_exec("file -bi $f");
				//this removes the charset value if it was returned with the mime type. mime is first.
				$mime = strtok($mime, '; ');
				$mime = trim($mime); //remove any remaining whitespace
			}
		}
		else if (function_exists('mime_content_type') && is_callable('mime_content_type'))
		{
			//test using mime_content_type last, since it sometimes detects the mime incorrectly
			$mime = mime_content_type($file);
		}
		
		return $mime;
	}
	
	private static function storeUploadedFile($filepath, &$params, &$result, &$i, $replaced = false)
	{
		$result_text = '';
		
		//move the file to the destination folder
		$success = move_uploaded_file($_FILES[$params->get('ag_variable')]["tmp_name"][$i], $filepath.DIRECTORY_SEPARATOR.$_FILES[$params->get('ag_variable')]["name"][$i]);
		
		if ($replaced)
		{
			$result_text.= Text::_('MOD_AG_REPLACEMENT_APPROVED')." ";
		}
		
		if ($success)
		{
			//Upload was successful.
			$result_text.= Text::_('MOD_AG_UPLOAD_SUCCESSFUL')."<br />";
			$result_text.= Text::_('MOD_AG_NAME').": ".$_FILES[$params->get('ag_variable')]["name"][$i]."<br />";
			$result_text.= Text::_('MOD_AG_TYPE').": ".$_FILES[$params->get('ag_variable')]["type"][$i]."<br />";
			$result_text.= Text::_('MOD_AG_SIZE').": ".EasyFileUploaderHelper::sizeToText($_FILES[$params->get('ag_variable')]["size"][$i])."<br />";
			//$result_text.= "Temp file: ".$_FILES[$params->get('ag_variable')]["tmp_name"][$i]."<br />";
			//$result_text.= "Stored in: ".$filepath;
			
			$result[$i]['type'] = 'success';
			$result[$i]['text'] = $result_text;
		}
		else
		{
			$result_text.= Text::_('MOD_AG_UPLOAD_UNSUCCESSFUL');
			
			$result[$i]['type'] = 'error';
			$result[$i]['text'] = $result_text;
		}
	}
	
	protected static function fileUploadErrorMessage($error_code)
	{
		switch ($error_code)
		{
			case UPLOAD_ERR_INI_SIZE:
				$message = Text::_('MOD_AG_INI_SIZE_ERROR'); 
				break;
			case UPLOAD_ERR_FORM_SIZE: 
				$message = Text::_('MOD_AG_FORM_SIZE_ERROR'); 
				break;
			case UPLOAD_ERR_PARTIAL: 
				$message = Text::_('MOD_AG_PARTIAL_ERROR'); 
				break;
			case UPLOAD_ERR_NO_FILE: 
				$message = Text::_('MOD_AG_NO_FILE_ERROR'); 
				break;
			case UPLOAD_ERR_NO_TMP_DIR: 
				$message = Text::_('MOD_AG_NO_TMP_DIR_ERROR'); 
				break;
			case UPLOAD_ERR_CANT_WRITE: 
				$message = Text::_('MOD_AG_CANT_WRITE_ERROR'); 
				break;
			case UPLOAD_ERR_EXTENSION: 
				$message = Text::_('MOD_AG_EXTENSION_ERROR'); 
				break;
			default: 
				$message = Text::_('MOD_AG_UNKNOWN_ERROR');
				break;
		}
		return $message;
	}
	
	protected static function sizeToText($size)
	{
		$text = "";
		$kb = 1024;
		$mb = $kb * $kb;
		$gb = $mb * $kb;
		
		if ($size >= $gb)
		{
			$size = round($size / $gb, 2);
			$text = $size."GB";
		}
		elseif ($size >= $mb)
		{
			$size = round($size / $mb, 2);
			$text = $size."MB";
		}
		elseif ($size >= $kb)
		{
			$size = round($size / $kb, 2);
			$text = $size."KB";
		}
		else
		{
			$text = $size.Text::_('MOD_AG_BYTES');
		}
		return $text;
	}
	
	/**
	 * Checks an uploaded for suspicious naming and potential PHP contents which could indicate a hacking attempt.
	 *
	 *
	 * @return  boolean  True of the file is safe
	 */
	public static function checkFileSafety(&$params, &$result, &$i, $forbidden = array('php', 'phps', 'php5', 'php3', 'php4', 'inc', 'pl', 'cgi', 'fcgi', 'java', 'jar', 'py'))
	{
		$safe = true;
		
		/**
		 * 1. Prevent buffer overflow attack by checking for null byte in the file name
		 */
		$null_byte = "\x00";
		if (stripos($_FILES[$params->get('ag_variable')]["name"][$i], $null_byte) !== false)
		{
			$result[$i]['type'] = 'error';
			$result[$i]['text'] = Text::_('MOD_AG_NULL_BYTE_FOUND');
			
			return false;
		}
		
		/**
		 * 2. Prevent uploading forbidden script files (based on file extension)
		 */ 
		$filename = $_FILES[$params->get('ag_variable')]["name"][$i];
		$split = explode('.', $filename);
		array_shift($split);
		$only_extensions = array_map('strtolower', $split);
		
		foreach ($forbidden as $script)
		{
			if (in_array($script, $only_extensions))
			{
				$result[$i]['type'] = 'error';
				$result[$i]['text'] = Text::_('MOD_AG_FORBIDDEN_SCRIPT_FOUND');
			
				return false;
			}
		}
		
		/**
		 * 3. Check the contents of the uploaded file for the following:
		 *      a. Presence of the PHP tag, <?php
		 *      b. Presence of PHP short tag, <?, but only if file is a script file
		 *      c. Presence of script files in archives (if they are not allowed)
		 */
		$buffer = 1024 * 8;
		$fp = @fopen($_FILES[$params->get('ag_variable')]["tmp_name"][$i], 'r');
		if ($fp !== false)
		{
			$data = '';
			
			while (!feof($fp) && $safe === true)
			{
				$data.= @fread($fp, $buffer);
				
				/**
				 * a. Check for the presence of the PHP tag, <?php
				 */
				if (stripos($data, '<?php') !== false)
				{
					$result[$i]['type'] = 'error';
					$result[$i]['text'] = Text::_('MOD_AG_PHP_TAG_FOUND');
					
					$safe = false;
					continue;
				}
				
				/**
				 * b. Check for the presence of the PHP short tag, <?, but only if file is a script text file
				 */
				$script_files = array('php', 'phps', 'php3', 'php4', 'php5', 'class', 'inc', 'txt', 'dat', 'tpl', 'tmpl');
				$is_script = false;
				foreach ($script_files as $script)
				{
					//check to see if uploaded file is a script file
					if (in_array($script, $only_extensions))
					{
						$is_script = true;
					}
				}
				
				if ($is_script)
				{
					//search for the short tag
					if (stripos($data, '<?') !== false)
					{
						$result[$i]['type'] = 'error';
						$result[$i]['text'] = Text::_('MOD_AG_SHORT_TAG_FOUND');
					
						$safe = false;
						continue;
					}
				}
				
				/**
				 * c. Check for the presence of forbidden script files in archives (if they are not allowed)
				 */
				$allow_scripts_in_archive = $params->get('ag_scriptsinarchives');
				if (!$allow_scripts_in_archive)
				{
					$archive_exts = array('zip', '7z', 'jar', 'rar', 'tar', 'gz', 'tgz', 'bz2', 'tbz', 'jpa');
					$is_archive = false;
					foreach ($archive_exts as $archive)
					{
						//check to see if uploaded file is an archive file
						if (in_array($archive, $only_extensions))
						{
							$is_archive = true;
						}
					}
				
					if ($is_archive)
					{
						foreach ($forbidden as $ext)
						{
							//search for the short tag
							if (stripos($data, '.'.$ext) !== false)
							{
								$result[$i]['type'] = 'error';
								$result[$i]['text'] = Text::_('MOD_AG_FORBIDDEN_IN_ARCHIVE_FOUND');
					
								$safe = false;
								continue;
							}
						}
					}
				}
				//start the next loop with the last 10 bytes just in case the PHP tag was split up 
				$data = substr($data, -10);
			}
			//close the file handle
			fclose($fp);
		}
		
		return $safe;
	}
}
?>