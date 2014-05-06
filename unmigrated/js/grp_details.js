
var rulesTableRow 	= '<tr><td class="hierarchy"></td><td class="relation"></td><td class="rule"></tr>';
var incRuleCount 	= 0;
var excRuleCount 	= 0;

$(document).ready(function() {
	applyGroupRulesBehavior();

});

function applyGroupRulesBehavior() {
	$("button.addRule").click(function() {
		console.log("boom");
		var parentTable = $(this).parents("table.rules");
		var parentRow = $(this).parents("tr");

		if(parentTable.attr('id') == "inclusion") {
			incRuleCount++;
			var buttonText = 'Add Rule Set';
			var hierarchy = incRuleCount;
		}
		else if(parentTable.attr('id') == "exclusion") {
			excRuleCount++;
			var buttonText = 'Add Exclusion Rule';
			var hierarchy = excRuleCount;
		}

		//REMOVE ADD BUTTON ROW
		$(this).remove();
		parentRow.remove();

		//ADD THE RULE SET ROW
		parentTable.append(rulesTableRow);
		parentTable.find("tr").last().children("td.hierarchy").append(hierarchy + '.0.0');

		//RE-ADD THE 'ADD RULE' ROW WITH BUTTON
		parentTable.append(rulesTableRow);
		parentTable.find("tr").last().children("td.rule").append('<button class="addRule">' + buttonText + '</button></td>');

		applyGroupRulesBehavior();
	});
}