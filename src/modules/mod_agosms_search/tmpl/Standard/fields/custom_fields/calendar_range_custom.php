<?php
/**
 * @package     Articles Good Search
 *
 * @copyright   Copyright (C) 2017 Joomcar extensions. All rights reserved.
 * @license     GNU General Public License version 2 or later.
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

$field_id_from = 16; // Need to set fields Id
$field_id_to   = 17;

$active = JRequest::getVar('field-date-custom'.$field_id_from);
$parts = explode(":", $active);
list($active_from, $active_to) = explode(",", $parts[1]);
if($active_from) {
	$active_from = DateTime::createFromFormat(date_format_to($params->get('date_format'), 'date'), $active_from)->format('m/d/Y'); // custom format
}
if($active_to) {
	$active_to = DateTime::createFromFormat(date_format_to($params->get('date_format'), 'date'), $active_to)->format('m/d/Y'); // custom format
}
?>

<div class="gsearch-field-calendar range custom-field calendar-custom<?php echo $field_id_from; ?>">	
	<h3>
		<?php echo JText::_("Date"); ?>
	</h3>

	<div class="gsearch-field-calendar-wrapper custom">
		<input type="text" class="datepicker from" value="<?php echo $active_from; ?>" />
		<input type="text" class="datepicker to" value="<?php echo $active_to; ?>" />
		
		<input type="hidden" name="field-date-custom<?php echo $field_id_from; ?>" value="<?php echo $active; ?>" id="field-date-custom-<?php echo $field_id_from; ?>" />
	</div>

	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/css/bootstrap-datepicker.min.css" />	
	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/js/bootstrap-datepicker.min.js"></script>
	<script>
		jQuery(document).ready(function($) {
			$(".gsearch-field-calendar-wrapper.custom").find(".datepicker").each(function(k, el) {
				$(el).datepicker({
					format: 'mm/dd/yyyy', //custom format
				}).on('changeDate', function(e) {
					prepareDates<?php echo $field_id_from; ?>();
				});
			});
		});
		function prepareDates<?php echo $field_id_from; ?>() {
			$ = jQuery.noConflict();
			var el_from = $(".calendar-custom<?php echo $field_id_from; ?>").find(".datepicker.from");
			var el_to = $(".calendar-custom<?php echo $field_id_from; ?>").find(".datepicker.to");
			var date_from = el_from.datepicker('getDate');
			if(date_from) {
				date_from = date_from.getFullYear() + '-' + ('0' + (date_from.getMonth()+1)).slice(-2) + '-' + ('0' + date_from.getDate()).slice(-2);
			}
			else {
				date_from = '';
			}
			var date_to = el_to.datepicker('getDate');
			if(date_to) {
				date_to = date_to.getFullYear() + '-' + ('0' + (date_to.getMonth()+1)).slice(-2) + '-' + ('0' + date_to.getDate()).slice(-2);
			}
			else {
				date_to = '';
			}
			var val = "<?php echo $field_id_from; ?>,<?php echo $field_id_to; ?>:" + date_from + "," + date_to;
			$('input#field-date-custom-<?php echo $field_id_from; ?>').val(val);
		}
	</script>
</div>

<?php
	if(!function_exists('date_format_to')) {
		function date_format_to($format, $syntax) {
			// http://php.net/manual/en/function.strftime.php
			$strf_syntax = [
				// Day - no strf eq : S (created one called %O)
				'%O', '%d', '%a', '%e', '%A', '%u', '%w', '%j',
				// Week - no date eq : %U, %W
				'%V',
				// Month - no strf eq : n, t
				'%B', '%m', '%b', '%-m',
				// Year - no strf eq : L; no date eq : %C, %g
				'%G', '%Y', '%y',
				// Time - no strf eq : B, G, u; no date eq : %r, %R, %T, %X
				'%P', '%p', '%l', '%I', '%H', '%M', '%S',
				// Timezone - no strf eq : e, I, P, Z
				'%z', '%Z',
				// Full Date / Time - no strf eq : c, r; no date eq : %c, %D, %F, %x
				'%s'
			];
			// http://php.net/manual/en/function.date.php
			$date_syntax = [
				'S', 'd', 'D', 'j', 'l', 'N', 'w', 'z',
				'W',
				'F', 'm', 'M', 'n',
				'o', 'Y', 'y',
				'a', 'A', 'g', 'h', 'H', 'i', 's',
				'O', 'T',
				'U'
			];
			switch ($syntax) {
				case 'date':
					$from = $strf_syntax;
					$to   = $date_syntax;
					break;
				case 'strf':
					$from = $date_syntax;
					$to   = $strf_syntax;
					break;
				default:
					return false;
			}
			$pattern = array_map(
				function ( $s ) {
					return '/(?<!\\\\|\%)' . $s . '/';
				},
				$from
			);
			return preg_replace($pattern, $to, $format);
		}
	}
?>