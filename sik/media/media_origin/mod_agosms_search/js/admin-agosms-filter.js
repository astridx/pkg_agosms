$ = jQuery.noConflict();
$(document).ready(function () {
	var selectedVal = $(".ValueSelectVal");
	var type_select =
			"<select class='field_type_select'>" +
			//	"<option value=''>Type (auto)</option>" +
				"<option class='multifield-available' value='text'>Text</option>" +
			//	"<option class='multifield-available' value='text_range'>Text range</option>" +
				"<option value='select'>Drop-down Select Box</option>" +
			//	"<option value='multi'>Multiple Select Box</option>" +
				"<option value='checkboxes'>Checkboxes</option>" +
				"<option value='radio'>Radio</option>" +
				"<option value='agosmsaddressmarker'>AgosmsAdressMarker</option>" +
			//	"<option class='multifield-available' value='slider-range'>Slider range</option>" +
			//	"<option class='multifield-available' value='calendar'>Calendar</option>" +
			//	"<option class='multifield-available' value='calendar_range'>Calendar range</option>" +
			//	"<option value='custom_select'>Custom select</option>" +
			"</select>";

	selectedVal.each(function () {
		if ($(this).val() != '') {
			var init_select = $(this);
			var selectedValues = $(this).val().split("\n");
			for (var i = 0; i < selectedValues.length; i++) {
				var title = '';
				init_select.parent().find('select.ValueSelect').find("option").each(function () {
					if (selectedValues[i].split(":")[0] == "field") {
						if ($(this).val().split(":")[1] == selectedValues[i].split(":")[1]) {
							title = $(this).text();
						}
					}
					else {
						if ($(this).val() == selectedValues[i]) {
							title = $(this).text();
						}
					}
				}
				);

				// adds type select for custom fields
				var type_selected = $(type_select);
				if (selectedValues[i].split(":")[0] == "field") {
					var selected = selectedValues[i].split(":")[2];

					//added for compatibility with radical multifield
					//added for compatibility with repeatable field
					if (selectedValues[i].split('{')[1]) {
						var json = '{' + selectedValues[i].split(/\{(.+)/)[1];
						var extra_params = JSON.parse(json);
						selected = extra_params['type'];
					}

					type_selected.find("option").each(function () {
						if ($(this).val() == selected) {
							$(this).attr("selected", "selected");
						}
					}
					);
					type_selected = "<select class='field_type_select'>" + type_selected.html() + "</select>";
				}
				else {
					type_selected = '';
				}

				//added for compatibility with radical multifield
				//added for compatibility with repeatable field
				var extra = '';
				if (selectedValues[i].split('{')[1]) {
					var json = '{' + selectedValues[i].split(/\{(.+)/)[1];
					var extra_params = JSON.parse(json);
					if (extra_params['radicalmultifield_fields']) {
						extra = '<select class="multifield_select">';
						extra += '<option value="">Field</option>';
						$.each(extra_params['radicalmultifield_fields'], function (k, field) {
							extra += '<option value="' + field.name + '"';
							if (extra_params['selected'] == field.name) {
								extra += ' selected="selected"';
							}

							extra += '>' + field.title + '</option>';
						}
						);
						extra += '</select>';
						//show only available filter types
						type_selected = $(type_selected).find('option.multifield-available').wrapAll('<div class="dummy" />').parents('.dummy');
						type_selected = "<select class='field_type_select'>" + type_selected.html() + "</select>";
					}

					if (extra_params['repeatable_fields']) {
						extra = '<select class="multifield_select">';
						extra += '<option value="">Field</option>';
						$.each(extra_params['repeatable_fields'], function (k, field) {
							extra += '<option value="' + field.name + '"';
							if (extra_params['selected'] == field.name) {
								extra += ' selected="selected"';
							}

							extra += '>' + field.title + '</option>';
						}
						);
						extra += '</select>';
						//show only available filter types
						type_selected = $(type_selected).find('option.multifield-available').wrapAll('<div class="dummy" />').parents('.dummy');
						type_selected = "<select class='field_type_select'>" + type_selected.html() + "</select>";
					}
				}

				init_select.parent().find(".sortableFields").append("<li><span class='val' rel='" + selectedValues[i] + "'>" +
					title + "</span><span class='sortableRightBlock'>" + extra + type_selected + "<span class='deleteFilter icon-cancel'></span></span></li>"
				);
			}
		}
	}
	);

	$(".sortableFields").sortable({
		update: function (event, ui) {
			updateFiltersVal(ui.item.parents(".controls"));
		},
		}
	);

	$("body").on('click', '.sortableFields .deleteFilter', function (event) {
		init_field = $(this).parents(".controls");
		$(this).parent().parent().remove();
		updateFiltersVal(init_field);
	}
	);

	$("body").on('change', '.sortableFields .field_type_select', function () {
		var selected = $(this).find("option:selected").val();
		var value = $(this).parent().siblings(".val").attr("rel").split(":");

		//added for compatibility with radical multifield
		//added for compatibility with repeatable field
		if (value[2] == 'radicalmultifield'
			|| value[2] == 'repeatable'
		) {
			var json = '{' + $(this).parent().siblings(".val").attr("rel").split(/\{(.+)/)[1];
			var extra_params = JSON.parse(json);
			extra_params['type'] = selected;
			$(this).parent().siblings(".val").attr("rel", value[0] + ":" + value[1] + ":" + value[2] + ":" + JSON.stringify(extra_params));
		}
		else {
			$(this).parent().siblings(".val").attr("rel", value[0] + ":" + value[1] + ":" + selected);
		}

		init_field = $(this).parents(".controls");
		updateFiltersVal(init_field);
	}
	);

	//added for compatibility with radical multifield
	//added for compatibility with repatable field
	$("body").on('change', '.sortableFields .multifield_select', function () {
		var selected = $(this).find("option:selected").val();
		var value = $(this).parent().siblings(".val").attr("rel").split(":");
		var json = '{' + $(this).parent().siblings(".val").attr("rel").split(/\{(.+)/)[1];
		var extra_params = JSON.parse(json);
		extra_params['selected'] = selected;
		$(this).parent().siblings(".val").attr("rel", value[0] + ":" + value[1] + ":" + value[2] + ":" + JSON.stringify(extra_params));
		init_field = $(this).parents(".controls");
		updateFiltersVal(init_field);
	}
	);

	$('.ValueSelect').on('change', function () {
		var init_box = $(this);
		var selected = $(this).find('option:selected');
		if (selected.val() != '' && selected.val() != 0) {
			var type_selected = type_select;
			if (selected.val().split(":")[0] != "field") {
				type_selected = '';
			}

			//added for compatibility with radical multifield
			//added for compatibility with repatable field
			var extra = '';
			if (selected.val().split('{')[1]) {
				var json = '{' + selected.val().split(/\{(.+)/)[1];
				var extra_params = JSON.parse(json);
				if (extra_params['radicalmultifield_fields']) {
					extra = '<select class="multifield_select">';
					extra += '<option value="">Field</option>';
					$.each(extra_params['radicalmultifield_fields'], function (k, field) {
						extra += '<option value="' + field.name + '">' + field.title + '</option>';
					}
					);
					extra += '</select>';
					//show only available filter types
					type_selected = $(type_selected).find('option.multifield-available').wrapAll('<div class="dummy" />').parents('.dummy');
					type_selected = "<select class='field_type_select'>" + type_selected.html() + "</select>";
				}

				if (extra_params['repeatable_fields']) {
					extra = '<select class="multifield_select">';
					extra += '<option value="">Field</option>';
					$.each(extra_params['repeatable_fields'], function (k, field) {
						extra += '<option value="' + field.name + '">' + field.title + '</option>';
					}
					);
					extra += '</select>';
					//show only available filter types
					type_selected = $(type_selected).find('option.multifield-available').wrapAll('<div class="dummy" />').parents('.dummy');
					type_selected = "<select class='field_type_select'>" + type_selected.html() + "</select>";
				}
			}

			init_box.parent().find(".sortableFields").append("<li><span class='val' rel='" + selected.val() + "'>" +
				selected.text() + "</span><span class='sortableRightBlock'>" + extra + type_selected + "<span class='deleteFilter icon-cancel'></span></span></li>"
			);

			init_field = $(this).parents(".controls");
			updateFiltersVal(init_field);
		}

		$('.ValueSelect').val(0).trigger('liszt:updated');
	}
	);
    }
);

function updateFiltersVal(init_field)
{
	var FiltersVal = '';
	init_field.find(".sortableFields li span.val").each(function (count) {
		if (count > 0) {
			FiltersVal = FiltersVal + "\r\n";
		}

		FiltersVal = FiltersVal + $(this).attr("rel");
	}
	);
	init_field.find(".ValueSelectVal").val(FiltersVal);
}
