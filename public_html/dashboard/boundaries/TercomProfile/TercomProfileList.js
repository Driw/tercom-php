
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
		var btnTemplate = '<button type="button" class="btn btn-sm {0}" onclick="TercomProfileList.{1}({2})" title="{3}">{4}</button>';
		var btnView = btnTemplate.format(BTN_CLASS_VIEW, 'onButtonView', index, 'Ver Dados do Perfil', ICON_VIEW);
		var btnEmployees = btnTemplate.format(BTN_CLASS_VIEW, 'onButtonEmployees', index, 'Listar Funcionários no Perfil', ICON_EMPLOYEES);
		var btnPermissions = btnTemplate.format(BTN_CLASS_VIEW, 'onButtonPermissions', index, 'Listar Permissões do Perfil', ICON_PROFILES);
		var btnRemove = btnTemplate.format(BTN_CLASS_REMOVE, 'onButtonRemove', index, 'Excluir Perfil TERCOM', ICON_REMOVE);

		return [
			tercomProfile.id,
			tercomProfile.name,
			tercomProfile.assignmentLevel,
			'<div class="btn-group">{0}{1}{2}{3}</div>'.format(btnView, btnEmployees, btnPermissions, btnRemove),
		];
	},
	onButtonView: function(index)
	{
		var tercomProfile = TercomProfileList.tercomProfiles[index];
		Util.redirect('tercomProfile/view/{0}'.format(tercomProfile.id));
	},
	onButtonRemove: function(index)
	{
		var tercomProfile = TercomProfileList.tercomProfiles[index];
		Util.redirect('tercomProfile/remove/{0}'.format(tercomProfile.id));
	},
	onButtonEmployees: function(index)
	{
		var tercomProfile = TercomProfileList.tercomProfiles[index];
		Util.redirect('tercomEmployee/listByProfile/{0}'.format(tercomProfile.id));
	},
	onButtonPermissions: function(index)
	{
		var tercomProfile = TercomProfileList.tercomProfiles[index];
		Util.redirect('tercomProfile/permissions/{0}'.format(tercomProfile.id));
	},
}
