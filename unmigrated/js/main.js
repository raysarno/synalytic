// CRM MAIN JAVASCRIPT FUNCTIONS
$(document).ready(function() {
	//TABS BEHAVIOR
	$(".tab").click(function() {
		if(!$(this).hasClass('selected')) {
			$(".tab").removeClass('selected');
			$(this).addClass('selected');

			$(".listWrapper").addClass('hidden');

			$('#' + $(this).attr('id').substring(0,3) + '_listWrapper').removeClass('hidden');
		}
	});

	//EDIT INFO BUTTON BEHAVIOR
	$("#editInfoButton").click(function() {
		$(this).addClass('hidden');
		$("#saveInfoButton").removeClass("hidden");
		$("*[form='editInfo']").removeProp('disabled').removeAttr("disabled").addClass("inputEditMode");
	});

	//LOCATIONATOR BEHAVIOR
	applyLocationatorBehavior();
	
	window.next_tel_ID = $("#next_tel_ID").val();
	$("button#tel_addNew").click(function() {
		$(this).parent().parent().before(
			'<tr class="telList">' +
				'<td class="telDelete"><input type="checkbox" form="locationator" name="telDelete-' + next_tel_ID + '" value="' + next_tel_ID + '" /></td>' +
				'<td class="tel_assoc_cmp"><input type="radio" form="locationator" name="tel4cmpORlcn-' + next_tel_ID + '" value="cmp" /></td>' +
				'<td class="tel_assoc_lcn"><input type="radio" form="locationator" name="tel4cmpORlcn-' + next_tel_ID + '" value="lcn" checked="checked" /></td>' +
				'<td class="tel_number">' +
					'<input type="text" form="locationator" name="tel_number-' + next_tel_ID + '" value="" placeholder="Phone Number Required" />' +
					'<input type="text" form="locationator" name="tel_is_new-' + next_tel_ID + '" value="1" style="display:none;" />' +
				'</td>' +
			'</tr>'
		);
		$("#tel_ID_list").attr('value', $("#tel_ID_list").val() + "-" + next_tel_ID);

		next_tel_ID++;
		applyLocationatorBehavior();
	});

	//DATA HEALTH BEHAVIOR
	$("input.ctt_cmp_select").click(function() {
		if($(this).attr('id') == 'add_cmp_select') {
			$("table#add_cmp_list").show();
			$("#lcn_list_title").empty().html('No Locations (Add New Company Selected)');
			$("#lcn_suggested_list").empty();
			$("#lcn_list").empty();
		}
		else {
			$("table#add_cmp_list").hide();
			$("#lcn_list_title").empty().html('Other Locations for Selected Company: (Loading...)');

			$("#lcn_suggested_list")
				.empty().prepend('<div class="ajaxLoader"><img src="img/ajax-loader.gif" /></div>')
				.load('./include/dataHealth_ajax.php',{reqType:'lcn_suggested',cmp_ID:$(this).val(),lcn_raw:$("#ctt_lcn_name").text().trim()}, function() {
					$("#lcn_suggested_list div.ajaxLoader").remove();
				});

			$("#lcn_list")
				.empty().prepend('<div class="ajaxLoader"><img src="img/ajax-loader.gif" /></div>')
				.load('./include/dataHealth_ajax.php',{reqType:'lcn',cmp_ID:$(this).val()}, function() {
					$("#lcn_list_title").empty().html('Other Locations for Selected Company: (' + $("#lcn_list").find("tr").length + ')');
					$("#lcn_list div.ajaxLoader").remove();
				});
		}
	});
	$("input.ctt_lcn_select").click(function() {
		console.log("boom");
		if($(this).attr('id') == 'add_lcn_select') {
			$("table#add_lcn_list").show();
		}
	});
});



function applyLocationatorBehavior() {
	//.innerList BEHAVIOR
	$("input[type='checkbox'][name^='telDelete']").click(function() {
		var associatedNumber = $(this).parent().siblings("td.tel_number").children("input");

		if($(this).prop("checked")) {
			associatedNumber.css({'color':'#FF0000','text-decoration':'line-through'});
		}
		else {
			associatedNumber.removeAttr('style');
		}
	});
}

function loadList(options, target) {
	var defaults = {
		listType 	: 'ctt',
		pagination 	: true,
		entPrPg 	: 50,
		curPg 		: 1,
		showFilters : true,
		listSorters : true,
		sortField 	: 'None',
		sortDir 	: 'None',
		listSize    : 'full',
		filters 	: '',
	}
	options = $.extend({}, defaults, options);

	target = typeof target !== 'undefined' ? target : getListTarget(options.listType);

	//EMPTY THE TARGET DIV AND DISPLAY THE LOADER
	$(target).empty().prepend('<div class="ajaxLoader"><img src="img/ajax-loader.gif" /></div>');

	//AJAX LOAD list.php
	$(target).load("./list/list.php", options, function() {
		$(".ajaxLoader").fadeOut(200);
		applyListEventHandlers();
	});
}

//LIST FILTER BAR BEHAVIOR
function applyFilters(listType) {
	var filters = new Array();
	$('.' + listType + '-filterField').each(function() {
		filters.push([$(this).attr('name'),$(this).val()]);
	});

	loadList({listType : listType, filters : filters});
}

//LIST PAGINATION BAR BEHAVIOR
function applyPagination(listType) {
	var options = {
		listType 	: listType,
		entPrPg 	: $("#" + listType + "_pagination #countSubmit").val(),
		curPg 		: $("#" + listType + "_pagination input.pgSubmit").val()
	}

	loadList(options);
}

function applyListEventHandlers() {
	//PAGINATION BEHAVIOR
	$("#countSubmit").change(function()
	{
		listType = $(this).parents(".pagination").attr('listtype');
		applyPagination(listType);
	});
	$("#pgScroller a").click(function()
	{
		listType = $(this).parents(".pagination").attr('listtype');

		var options = {
			listType 	: listType,
			entPrPg 	: $("#" + listType + "_pagination #countSubmit").val(),
			curPg 		: $(this).attr('data-val')
		}
		loadList(options);
	});
	$("#pgSubmit").change(function()
	{
		listType = $(this).parents(".pagination").attr('listtype');
		applyPagination(listType);	
	});

	//QUICKACTION BEHAVIOR
	$("a#QA-newPhoneEvent.actionItem").click(function() {

		 var contact_id = $(this).parents(".lineitem").attr('contactid');

		//High quickAction menu and add close button
		$(this).parents(".actionSelect").hide();
		$(this).parents(".QA_Wrapper").children(".actionClose").show();

		//'Open' quickAction box (Adjust height of quickAction box and lineitem)
		$(this).parents(".lineitem").animate({'height':'200px'},1000);
		$(this).parents(".lineitem").children(".quickAction").animate({'height':'150px'},1000, function() {

			$(this).parents(".lineitem").children(".quickAction").load(
				"./list/quickAction/phoneEvent_quickAction.php",
				{"contact_id":contact_id},
				function() {
					$(".phoneQA").fadeIn(400);
			});

		});
	});

	$("div.actionClose").click(function() {
		var targetObj = $(this);
		quickActionClose(targetObj);
	});

	//LISTSORTERS BEHAVIOR
	$("img.listSorter").click(function() {
		var sortDirection 	= 'ASC';
		var sortField 		= $(this).attr('sortField');

		if($(this).attr('src') == "img/sortASC.png") {
			sortDirection = 'DESC';
		}
		else if($(this).attr('src') == "img/sortDESC.png") {
			sortDirection 	= 'None';
			sortField 		= 'None';
		}

		var options = {
			listType 	: $("img.listSorter").eq(0).parents("#listSorters").attr('listtype'),
			listSorters : true,
			sortField 	: sortField,
			sortDir 	: sortDirection
		}

		loadList(options);
	});
}

function getListTarget(listType) {
	var target = '#' + listType + '_listWrapper';
	if(!$(".listWrapper").length) {
		target = "#content";
	}

	return target;
}

function quickActionClose(targetObj) {
	$(targetObj).parents(".lineitem").animate({'height':'64px'},1000);
	$(targetObj).parents(".lineitem").children(".quickAction").animate({'height':'0px'},1000, function() {
		$(".quickAction").empty();
	});

	$(targetObj).hide();
	$(targetObj).parents(".QA_Wrapper").children(".actionSelect").show();
}