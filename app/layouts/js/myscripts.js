jQuery(function() {
	$( "#sortable" ).sortable({
		placeholder: "fantom",
		update : function(event, ui) {
			var list = ui.item.parent('ul');
			var pos = 0;
			$(list.find("li")).each(function() {
				pos++;
				$(this).find("input.positioninput").val(pos);
			});
		}
	});
});