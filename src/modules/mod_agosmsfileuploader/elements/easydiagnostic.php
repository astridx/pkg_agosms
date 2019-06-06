<?php
defined('JPATH_BASE') or die();

\JLoader::import('joomla.form.formfield');


class JFormFieldEasyDiagnostic extends \JFormField
{
	//specify the custom field type
	protected $type = 'easydiagnostic';
	
	//setup the custom field's details
	protected function getInput()
	{
		$html = '<p style="color:black;">PHP Version: '.PHP_VERSION.'.</p>';
		if (defined('FILEINFO_MIME_TYPE'))
		{	
			$html.= '<p style="color:green;">FILEINFO_MIME_TYPE is defined.</p>';
			if (function_exists('finfo_open'))
			{
				if (is_callable('finfo_open'))
				{
					$html.= '<p style="color:green;">finfo_open is callable.</p>';
				}
				else
				{
					$html.= '<p style="color:blue;">finfo_open is NOT callable.</p>';
				}
			}
			else
			{
				$html.= '<p style="color:red;">finfo_open doesn\'t exist.</p>';
			}
			
			if (function_exists('finfo_file'))
			{
				if (is_callable('finfo_file'))
				{
					$html.= '<p style="color:green;">finfo_file is callable.</p>';
				}
				else
				{
					$html.= '<p style="color:blue;">finfo_file is NOT callable.</p>';
				}
			}
			else
			{
				$html.= '<p style="color:red;">finfo_file doesn\'t exist.</p>';
			}
			if (function_exists('finfo_close'))
			{
				if (is_callable('finfo_close'))
				{
					$html.= '<p style="color:green;">finfo_close is callable.</p>';
				}
				else
				{
					$html.= '<p style="color:blue;">finfo_close is NOT callable.</p>';
				}
			}
			else
			{
				$html.= '<p style="color:red;">finfo_close doesn\'t exist.</p>';
			}
		}
		else
		{
			$html.= '<p style="color:red;">FILEINFO_MIME_TYPE is NOT defined.</p>';
		}
		
		if (function_exists('mime_content_type'))
		{
			if (is_callable('mime_content_type'))
			{
				$html.= '<p style="color:green;">mime_content_type is callable.</p>';
			}
			else
			{
				$html.= '<p style="color:blue;">mime_content_type is NOT callable.</p>';
			}
		}
		else
		{
			$html.= '<p style="color:red;">mime_content_type doesn\'t exist.</p>';
		}
		
		if (strtoupper(substr(PHP_OS,0,3)) !== 'WIN')
		{
			$html.= '<p style="color:black;">This is a NON-Windows OS: '.PHP_OS.'.</p>';
			if (function_exists('escapeshellarg'))
			{
				if (is_callable('escapeshellarg'))
				{
					$html.= '<p style="color:green;">escapeshellarg is callable.</p>';
				}
				else
				{
					$html.= '<p style="color:blue;">escapeshellarg is NOT callable.</p>';
				}
			}
			else
			{
				$html.= '<p style="color:red;">escapeshellarg doesn\'t exist.</p>';
			}
			
			if (function_exists('exec'))
			{
				if (is_callable('exec'))
				{
					$html.= '<p style="color:green;">exec is callable.</p>';
				}
				else
				{
					$html.= '<p style="color:blue;">exec is NOT callable.</p>';
				}
			}
			else
			{
				$html.= '<p style="color:red;">exec doesn\'t exist.</p>';
			}
			
			if (function_exists('shell_exec'))
			{
				if (is_callable('shell_exec'))
				{
					$html.= '<p style="color:green;">shell_exec is callable.</p>';
				}
				else
				{
					$html.= '<p style="color:blue;">shell_exec is NOT callable.</p>';
				}
			}
			else
			{
				$html.= '<p style="color:red;">shell_exec doesn\'t exist.</p>';
			}
		}
		else
		{
			$html.= '<p style="color:red;">This is Windows OS: '.PHP_OS.'.</p>';
		}
		
		return '<hr style="clear:both;" />'.$html.'<hr />';
	}
}
