<?php
/**
 * @package     Joomla.Site
 * @subpackage  pkg_agosms
 *
 * @copyright   Copyright (C) 2005 - 2019 Astrid Günther, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later;
 * @link        astrid-guenther.de
 */


defined('_JEXEC') or die;

$uri = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

$active = JRequest::getVar("columned") ? "columned" : "list";

?>

<style>
	.gsearch-toolbox > div { float: right; }
	.gsearch-layout { margin-left: 10px; }
	.gsearch-layout a { display: inline-block; width: 22px; height: 22px; margin-top: 4px;
		background: url(<?php echo JURI::root(); ?>plugins/system/plg_agosms_search/template/com_content/layout.png) no-repeat;
		background-position: top left;
		background-size: cover;
	}
	.gsearch-layout a.columns { background-position: top right; width: 21px; }
	.gsearch-layout a.list { margin: 0 5px 0 15px; }
	.gsearch-layout a.active { opacity: 0.6; }
	.gsearch-layout a span { display: block; text-indent: -10000px; overflow: hidden; }
</style>

<a href="#" class="list <?php echo $active == 'list' ? ' active' : ''; ?>">
	<span><?php echo JText::_("List"); ?></span>
</a>
<a href="#" class="columns <?php echo $active == 'columned' ? ' active' : ''; ?>">
	<span><?php echo JText::_("Columns"); ?></span>
</a>

<script>
	jQuery(document).ready(function($) {
		$(".gsearch-layout a").on("click", function() {
			if($(this).hasClass("active")) return false;
			var cols = 2;
			var uri = "<?php echo $uri; ?>";
			if(uri.indexOf('columned') === -1) {
				if(uri.indexOf('?') === -1) {
					uri += "?";
				}
				else {
					uri += "&";
				}
				uri += "columned=" + cols;
			}
			else {
				uri = uri.replace("&columned=" + cols, "");
				uri = uri.replace("?columned=" + cols, "");
			}
			uri = uri.replace("&search_mode=raw", "");
			window.location.href = uri;
			return false;
		});
	});
</script>
