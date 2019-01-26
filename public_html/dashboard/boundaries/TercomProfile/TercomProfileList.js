
$(document).ready(function()
{
	TercomProfileList.init();
});

var TercomProfileList = TercomProfileList ||
{
	init: function()
	{
		TercomProfileList.table = $('#table-tercom-profiles');
		TercomProfileList.tbody = TercomProfileList.table.find('tbody');
		TercomProfileList.datatables = newDataTables(TercomProfileList.table);
		TercomProfileList.loadProfiles();
	},
	loadProfiles: function()
	{
		ws.tercomProfile_getAll(TercomProfileList.tbody, TercomProfileList.onTercomProfilesLoaded);
	},
	onTercomProfilesLoaded: function(tercomProfiles)
	{
		TercomProfileList.tercomProfiles = tercomProfiles.elements;
		TercomProfileList.tercomProfiles.forEach((tercomProfile, index) =>
		{
			var tercomProfileRowData = TercomProfileList.newTercomProfileRowData(index, tercomProfile);
			TercomProfileList.datatables.row.add(tercomProfileRowData).draw();
		});
	},
	newTercomProfileRowData: function(index, tercomProfile)
	{
		var btnTemplate = '<button type="button" class="btn btn-sm btn-{0}" onclick="TercomProfileList.{1}({2})">{3}</button>';
		var btnView = btnTemplate.format('primary', 'onButtonView', index, 'Ver');
		var btnRemove = btnTemplate.format('danger', 'onButtonRemove', index, 'Remover');
		var btnEmployees = btnTemplate.format('primary', 'onButtonEmployees', index, 'Funcionários');

		return [
			tercomProfile.id,
			tercomProfile.name,
			tercomProfile.assignmentLevel,
			'<div class="btn-group">{0}{1}{2}</div>'.format(btnView, btnEmployees, btnRemove),
		];
	},
	onButtonView: function(index)
	{
		var tercomProfile = TercomProfileList.tercomProfiles[index];

		if (tercomProfile !== undefined)
			Util.redirect('tercomProfile/view/{0}'.format(tercomProfile.id), true);
	},
	onButtonEmployees: function(index)
	{
		var tercomProfile = TercomProfileList.tercomProfiles[index];

		if (tercomProfile !== undefined)
			Util.redirect('tercomEmployee/listByProfile/{0}'.format(tercomProfile.id), true);
	},
	onButtonRemove: function(index)
	{
		var tercomProfile = TercomProfileList.tercomProfiles[index];

		if (tercomProfile !== undefined)
			Util.redirect('tercomProfile/remove/{0}'.format(tercomProfile.id), true);
	},
}








//
