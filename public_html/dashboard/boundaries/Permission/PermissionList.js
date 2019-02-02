
$(document).ready(function()
{
	PermissionList.init();
});

var PermissionList = PermissionList ||
{
	init: function()
	{
		PermissionList.table = $('#table-permissions');
		PermissionList.relationship = $('#relationship').val();
		PermissionList.idRelationship = $('#idRelationship').val();
		PermissionList.tbody = PermissionList.table.find('tbody');
		PermissionList.datatables = newDataTables(this.table);
		PermissionList.loadPermissions();
	},
	loadPermissions: function()
	{
		ws.managePermissions_getAll(PermissionList.relationship, PermissionList.idRelationship, PermissionList.tbody, PermissionList.onPermissionsLoaded);
	},
	onPermissionsLoaded: function(permissions)
	{
		PermissionList.permissions = permissions.elements;

		for (var i = 0; i < PermissionList.permissions.length; i++)
		{
			var permission = PermissionList.permissions[i];
			var packet = permission.packet;
			var action = permission.action;

			if (!PermissionList.hasPermissionPacket(packet))
				PermissionList.permissions[packet] = {};

			PermissionList.permissions[packet][action] = true;
		}

		while (Dashboard.rawPermissions === undefined);

		Dashboard.rawPermissions.forEach((permission, index) => {
			var permissionRowData = PermissionList.newPermissionRowData(index, permission);
			PermissionList.datatables.row.add(permissionRowData).draw();
		});
	},
	hasPermissionPacket: function(packet)
	{
		return typeof(PermissionList.permissions[packet]) !== 'undefined';
	},
	hasPermissionAction: function(packet, action)
	{
		return PermissionList.hasPermissionPacket(packet) && typeof(PermissionList.permissions[packet][action]) !== 'undefined';
	},
	newPermissionRowData: function(index, permission)
	{
		var have = PermissionList.hasPermissionAction(permission.packet, permission.action);
		var btnTemplate = '<button class="btn btn-sm btn-{0}" onclick="PermissionList.{1}(this, {2})">{3}</button>';
		var btnAdd = btnTemplate.format('success', 'onButtonAdd', index, 'Adicionar');
		var btnRemove = btnTemplate.format('danger', 'onButtonRemove', index, 'Remover');

		return [
			permission.id,
			'{0}.{1}'.format(permission.packet, permission.action),
			'',//permission.description,
			permission.assignmentLevel,
			'<div class="btn-group">{0}</div>'.format(have ? btnRemove : btnAdd),
		];
	},
	onButtonAdd: function(button, index)
	{
		var tr = $(button).parents('tr');
		var permission = Dashboard.rawPermissions[index];
		var formData = PermissionList.newFormatData(permission);

		ws.managePermissions_add(PermissionList.relationship, PermissionList.idRelationship, permission.id, formData, tr, function(permission)
		{
			PermissionList.permissions[permission.packet][permission.action] = true;
			PermissionList.onPermissionChange(permission, tr, index);
		});
	},
	onButtonRemove: function(button, index)
	{
		var tr = $(button).parents('tr');
		var permission = Dashboard.rawPermissions[index];
		var formData = PermissionList.newFormatData(permission);

		ws.managePermissions_remove(PermissionList.relationship, PermissionList.idRelationship, permission.id, formData, tr, function(permission)
		{
			delete PermissionList.permissions[permission.packet][permission.action];
			PermissionList.onPermissionChange(permission, tr, index);
		});
	},
	onPermissionChange: function(permission, tr, index)
	{
		var permissionRowData = PermissionList.newPermissionRowData(index, permission);
		PermissionList.datatables.row(tr).data(permissionRowData);
	},
	newFormatData: function(permission)
	{
		var formData = new FormData();
		formData.append('idPermission', permission.id);
		return formData;
	}
}
