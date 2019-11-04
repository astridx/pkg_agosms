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

defined('_JEXEC') or die;

// No direct access
defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;
use Joomla\CMS\Filesystem\File;
use Joomla\CMS\Factory;

// Import joomla file helper class
\JLoader::import('joomla.filesystem.file');

/**
 * Help uploading files
 *
 * @since  1.0.40
 */
class EasyFileUploaderHelper
{

	/**
	 * Method to get file uplaod.
	 *
	 * @param   boolean  &$params  If true, the view output will be cached
	 *
	 * @return  array  This object to support chaining.
	 *
	 * @since   1.0.40
	 */
	public static function getFileToUpload(&$params)
	{
		$result = array();

		// Get the Joomla Path and trim whitespace and slashes from the end
		$jpath = JPATH_SITE;
		$jpath = rtrim($jpath, "/\\ \t\n\r\0\x0B");

		// Get the parent folder and trim whitespace and slashes from both ends
		$parent = $params->get('ag_parent');
		$parent = trim($parent, "/\\ \t\n\r\0\x0B");

		// Get the folder location and trim whitespace and slashes from both ends
		$folder = $params->get('ag_folder');
		$folder = trim($folder, "/\\ \t\n\r\0\x0B");
		$folder = str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $folder);

		// Compile the full absolute path
		$path = $jpath . DIRECTORY_SEPARATOR . $parent . DIRECTORY_SEPARATOR . $folder;
		$path = rtrim($path, "/\\ \t\n\r\0\x0B");

		// Compile the full relative path
		$relativepath = $parent . DIRECTORY_SEPARATOR . $folder;
		$relativepath = rtrim($relativepath, "/\\ \t\n\r\0\x0B");

		if ($params->get('ag_user') == true)
		{
			// Get the user data
			$user = Factory::getUser();

			if ($user->guest == false)
			{
				$path .= DIRECTORY_SEPARATOR . $user->username;
				$relativepath .= DIRECTORY_SEPARATOR . $user->username;
			}
			else
			{
				// Todo
			}
		}

		// Check to see if the upload process has started
		if (isset($_FILES[$params->get('ag_variable')]))
		{
			// Now, we're going to check each of the uploaded files
			$total = intval($params->get('ag_multiple'));

			for ($i = 0; $i < $total; $i++)
			{
				$result[$i]['show'] = true;
				$result[$i]['rpath'] = $relativepath;
				$result[$i]['path'] = $path;

				// So, now, check for any other errors
				if ($_FILES[$params->get('ag_variable')]["error"][$i] > 0)
				{
					// Error was found. Show the return code.
					$error_text = Text::_('MOD_AG_RETURN_CODE') . ": " . $_FILES[$params->get('ag_variable')]["error"][$i] . "<br />";
					$error_text .= self::fileUploadErrorMessage($_FILES[$params->get('ag_variable')]["error"][$i]);

					$result[$i]['type'] = 'error';
					$result[$i]['text'] = $error_text;

					// Note that UPLOAD_ERR_NO_FILE = 4
					if ($_FILES[$params->get('ag_variable')]["error"][$i] == UPLOAD_ERR_NO_FILE)
					{
						// Set the result type to warning instead of error
						$result[$i]['type'] = 'warning';

						// Get the value for 'ag_shownofile', the default is 1
						$shownofile = $params->get('ag_shownofile', 1);

						if ($shownofile == false)
						{
							$result[$i]['show'] = false;
						}
					}
				}
				else
				{
					/*
					 No errors found.
					 Check to see if the file type is correct
					 But first, we have to store the file types in a variable. I was getting an issue with empty() */
					if (self::isValidFileType($params, $i))
					{
						// The file type is permitted
						// So, check for the right size
						if ($_FILES[$params->get('ag_variable')]["size"][$i] < $params->get('ag_maxsize'))
						{
							// File is an acceptable size
							// Check to see if file already exists in the destination folder
							if (file_exists($path . DIRECTORY_SEPARATOR . $_FILES[$params->get('ag_variable')]["name"][$i]))
							{
								// File already exists
								// Check whether the user wants to replace the file or not.
								if ($params->get('ag_default_replace') == true
									|| ($params->get('ag_replace') == true && $_POST["answer"] == true))
								{
									// Yep, the user wants to replace the file, so just delete the existing file
									File::delete($path . DIRECTORY_SEPARATOR . $_FILES[$params->get('ag_variable')]["name"][$i]);
									self::storeUploadedFile($path, $params, $result, $i, true);
								}
								else
								{
									$result[$i]['type'] = 'info';
									$result[$i]['text'] = $_FILES[$params->get('ag_variable')]["name"][$i] . " " . Text::_('MOD_AG_ALREADY_EXISTS');
								}
							}
							else
							{
								// Check to see if the file meets the safety standards
								$is_safe = self::checkFileSafety($params, $result, $i);

								if ($is_safe)
								{
									self::storeUploadedFile($path, $params, $result, $i);
								}
							}
						}
						else
						{
							// File is too large
							$result[$i]['type'] = 'warning';
							$result[$i]['text'] = Text::_('MOD_AG_TOO_LARGE_ERROR') . self::sizeToText($params->get('ag_maxsize')) . ".";
						}
					}
					else
					{
						// The file type is not permitted
						$fakeMIME = $_FILES[$params->get('ag_variable')]["type"][$i];
						$trueMIME = self::actualMIME($_FILES[$params->get('ag_variable')]["tmp_name"][$i]);
						$result[$i]['type'] = 'error';
						$result[$i]['text'] = Text::_('MOD_AG_INVALID_ERROR')
							. "<br />"
							. Text::_('MOD_AG_PHP_MIME_ERROR')
							. ($trueMIME !== false ? "("
							. $trueMIME . ")" : "")
							. "<br />" . Text::_('MOD_AG_BROWSER_MIME_ERROR')
							. $fakeMIME;
					}
				}
			}
		}

		return $result;
	}

	/**
	 * Method to get file uplaod.
	 *
	 * @param   boolean  &$params  If true, the view output will be cached
	 * @param   boolean  &$i       If true, the view output will be cached
	 *
	 * @return  array  This object to support chaining.
	 *
	 * @since   1.0.40
	 */
	private static function isValidFileType(&$params, &$i)
	{
		$valid = false;

		$filetypes = $params->get('ag_filetypes');
		$actualMIME = self::actualMIME($_FILES[$params->get('ag_variable')]["tmp_name"][$i]);

		if ($filetypes == "*"
			|| (stripos($filetypes, $_FILES[$params->get('ag_variable')]["type"][$i]) !== false
			&& $actualMIME !== false
			&& stripos($filetypes, $actualMIME) !== false))
		{
			$valid = true;
		}

		return $valid;
	}

	/**
	 * Method to get file mime.
	 *
	 * @param   boolean  $file  If file
	 *
	 * @return  boolean  This object
	 *
	 * @since   1.0.40
	 */
	private static function actualMIME($file)
	{
		if (!file_exists($file))
		{
			return false;
		}

		$mime = false;

		// Try to use recommended functions
		if (defined('FILEINFO_MIME_TYPE')
			&& function_exists('finfo_open')
			&& is_callable('finfo_open')
			&& function_exists('finfo_file')
			&& is_callable('finfo_file')
			&& function_exists('finfo_close')
			&& is_callable('finfo_close'))
		{
			$finfo = finfo_open(FILEINFO_MIME_TYPE);
			$mime = finfo_file($finfo, $file);

			if ($mime === '')
			{
				$mime = false;
			}

			finfo_close($finfo);
		}
		elseif (strtoupper(substr(PHP_OS, 0, 3)) !== 'WIN')
		{
			$f = "'" . $file . "'";

			if (function_exists('escapeshellarg') && is_callable('escapeshellarg'))
			{
				// Prefer to use escapeshellarg if it is available
				$f = escapeshellarg($file);
			}

			if (function_exists('exec') && is_callable('exec'))
			{
				/*
				 Didn't like how 'system' flushes output to browser. replaced with 'exec'
				 Note: You can change this to: shell_exec("file -b --mime-type $f"); if you get
				 "Regular file" as the mime type */
				$mime = exec("file -bi $f");

				// This removes the charset value if it was returned with the mime type. mime is first.
				$mime = strtok($mime, '; ');

				// Remove any remaining whitespace
				$mime = trim($mime);
			}
			elseif (function_exists('shell_exec') && is_callable('shell_exec'))
			{
				// Note: You can change this to: shell_exec("file -b --mime-type $f"); if you get
				// "Regular file" as the mime type
				$mime = shell_exec("file -bi $f");

				// This removes the charset value if it was returned with the mime type.
				// Mime is first.
				$mime = strtok($mime, '; ');

				// Remove any remaining whitespace
				$mime = trim($mime);
			}
		}
		elseif (function_exists('mime_content_type') && is_callable('mime_content_type'))
		{
			// Test using mime_content_type last, since it sometimes detects the mime incorrectly
			$mime = mime_content_type($file);
		}

		return $mime;
	}

	/**
	 * Method to get file uplaod.
	 *
	 * @param   boolean  $filepath  If true, the view output will be cached
	 * @param   boolean  &$params   If true, the view output will be cached
	 * @param   boolean  &$result   If true, the view output will be cached
	 * @param   boolean  &$i        If true, the view output will be cached
	 * @param   boolean  $replaced  If true, the view output will be cached
	 *
	 * @return  void
	 *
	 * @since   1.0.40
	 */
	private static function storeUploadedFile($filepath, &$params, &$result, &$i, $replaced = false)
	{
		$result_text = '';
		$success = false;

		// Move the file to the destination folder
		if (file_exists($filepath))
		{
			$success = move_uploaded_file(
				$_FILES[$params->get('ag_variable')]["tmp_name"][$i], $filepath . DIRECTORY_SEPARATOR . $_FILES[$params->get('ag_variable')]["name"][$i]
			);
		}
		else
		{
			$result_text .= Text::_('MOD_AG_FOLDER_NOT_EXISTS') . " ";
		}

		if ($replaced)
		{
			$result_text .= Text::_('MOD_AG_REPLACEMENT_APPROVED') . " ";
		}

		if ($success)
		{
			// Upload was successful.
			$result_text .= Text::_('MOD_AG_UPLOAD_SUCCESSFUL') . "<br />";
			$result_text .= Text::_('MOD_AG_NAME') . ": " . $_FILES[$params->get('ag_variable')]["name"][$i] . "<br />";
			$result_text .= Text::_('MOD_AG_TYPE') . ": " . $_FILES[$params->get('ag_variable')]["type"][$i] . "<br />";
			$result_text .= Text::_('MOD_AG_SIZE') . ": " . self::sizeToText($_FILES[$params->get('ag_variable')]["size"][$i]) . "<br />";

			$result[$i]['type'] = 'success';
			$result[$i]['text'] = $result_text;
		}
		else
		{
			$result_text .= Text::_('MOD_AG_UPLOAD_UNSUCCESSFUL');

			$result[$i]['type'] = 'error';
			$result[$i]['text'] = $result_text;
		}
	}

	/**
	 * Method to get the error code.
	 *
	 * @param   string  $error_code  If true, the view output will be cached
	 *
	 * @return  string  The message
	 *
	 * @since   1.0.40
	 */
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

	/**
	 * Method to get file uplaod.
	 *
	 * @param   string  $size  If true, the view output will be cached
	 *
	 * @return  string
	 *
	 * @since   1.0.40
	 */
	protected static function sizeToText($size)
	{
		$text = "";
		$kb = 1024;
		$mb = $kb * $kb;
		$gb = $mb * $kb;

		if ($size >= $gb)
		{
			$size = round($size / $gb, 2);
			$text = $size . "GB";
		}
		elseif ($size >= $mb)
		{
			$size = round($size / $mb, 2);
			$text = $size . "MB";
		}
		elseif ($size >= $kb)
		{
			$size = round($size / $kb, 2);
			$text = $size . "KB";
		}
		else
		{
			$text = $size . Text::_('MOD_AG_BYTES');
		}

		return $text;
	}

	/**
	 * Checks an uploaded for suspicious naming and potential PHP contents which could indicate a hacking attempt.
	 *
	 * @param   boolean  &$params    If true, the view output will be cached
	 * @param   boolean  &$result    If true, the view output will be cached
	 * @param   boolean  &$i         If true, the view output will be cached
	 * @param   boolean  $forbidden  If true, the view output will be cached
	 *
	 * @return  boolean  True of the file is safe
	 */
	public static function checkFileSafety(
		&$params,
		&$result,
		&$i,
		$forbidden = array('php', 'phps', 'php5', 'php3', 'php4', 'inc', 'pl', 'cgi', 'fcgi', 'java', 'jar', 'py')
	)
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
				$data .= @fread($fp, $buffer);

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
					// Check to see if uploaded file is a script file
					if (in_array($script, $only_extensions))
					{
						$is_script = true;
					}
				}

				if ($is_script)
				{
					// Search for the short tag
					if (stripos($data, '<?') !== false)
					{
						$result[$i]['type'] = 'error';
						$result[$i]['text'] = Text::_('MOD_AG_SHORT_TAG_FOUND');

						$safe = false;
						continue;
					}
				}

				/**
				 * C. Check for the presence of forbidden script files in archives (if they are not allowed)
				 */
				$allow_scripts_in_archive = $params->get('ag_scriptsinarchives');

				if (!$allow_scripts_in_archive)
				{
					$archive_exts = array('zip', '7z', 'jar', 'rar', 'tar', 'gz', 'tgz', 'bz2', 'tbz', 'jpa');
					$is_archive = false;

					foreach ($archive_exts as $archive)
					{
						// Check to see if uploaded file is an archive file
						if (in_array($archive, $only_extensions))
						{
							$is_archive = true;
						}
					}

					if ($is_archive)
					{
						foreach ($forbidden as $ext)
						{
							// Search for the short tag
							if (stripos($data, '.' . $ext) !== false)
							{
								$result[$i]['type'] = 'error';
								$result[$i]['text'] = Text::_('MOD_AG_FORBIDDEN_IN_ARCHIVE_FOUND');

								$safe = false;
								continue;
							}
						}
					}
				}

				// Start the next loop with the last 10 bytes just in case the PHP tag was split up
				$data = substr($data, -10);
			}

			// Close the file handle
			fclose($fp);
		}

		return $safe;
	}
}
