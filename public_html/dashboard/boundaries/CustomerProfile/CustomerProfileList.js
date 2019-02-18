
$(document).ready(function()
{
	CustomerProfileList.init();
});

var CustomerProfileList = CustomerProfileList ||
{
	init: function()
	{
		CustomerProfileList.idCustomer = $('#idCustomer').val();
		CustomerProfileList.table = $('#table-customer-profiles');
		CustomerProfileList.tbody = CustomerProfileList.table.find('tbody');
		CustomerProfileList.datatables = newDataTables(CustomerProfileList.table);
		CustomerProfileList.loadCustomerProfiles();
	},
	loadCustomerProfiles: function()
	{
		ws.customerProfile_getByCustomer(CustomerProfileList.idCustomer, CustomerProfileList.tbody, CustomerProfileList.onCustomerProfilesLoaded);
	},
	onCustomerProfilesLoaded: function(customerProfiles)
	{
		CustomerProfileList.customerProfiles = customerProfiles.elements;
		CustomerProfileList.customerProfiles.forEach((customerProfile, index) =>
		{
			var customerProfileRowData = CustomerProfileList.newAddressRowData(index, customerProfile);
			var row = CustomerProfileList.datatables.row.add(customerProfileRowData).draw();
		});
	},
	newAddressRowData: function(index, customerProfile)
	{
		var btnTemplate = '<button type="button" class="btn btn-sm {0}" onclick="CustomerProfileList.{1}({2})" title="{3}">{4}</button>';
		var btnView = btnTemplate.format(BTN_CLASS_VIEW, 'onButtonView', index, 'Ver Dados do Perfil', ICON_VIEW);
		var btnPermissions = btnTemplate.format(BTN_CLASS_VIEW, 'onButtonPermissions', index, 'Listar Permissões do Perfil', ICON_PERMISSIONS);
		var btnEmployees = btnTemplate.format(BTN_CLASS_VIEW, 'onButtonEmployees', index, 'Listar Funcionários no Perfil', ICON_EMPLOYEES);
		var btnRemove = btnTemplate.format(BTN_CLASS_REMOVE, 'onButtonRemove', index, 'Excluir Perfil', ICON_REMOVE);

		return [
			customerProfile.id,
			customerProfile.name,
			customerProfile.assignmentLevel,
			'<div class="btn-group">{0}{1}{2}{3}</div>'.format(btnView, btnPermissions, btnEmployees, btnRemove),
		];
	},
	onButtonView: function(index)
	{
		var customerProfile = CustomerProfileList.customerProfiles[index];
		Util.redirect('customerProfile/view/{0}/{1}'.format(customerProfile.id, CustomerProfileList.idCustomer));
	},
	onButtonPermissions: function(index)
	{
		var customerProfile = CustomerProfileList.customerProfiles[index];
		Util.redirect('customerProfile/permissions/{0}'.format(customerProfile.id));
	},
	onButtonEmployees: function(index)
	{
		var customerProfile = CustomerProfileList.customerProfiles[index];
		Util.redirect('customerEmployee/list/{0}/{1}'.format(customerProfile.id, CustomerProfileList.idCustomer));
	},
	onButtonRemove: function(index)
	{
		var customerProfile = CustomerProfileList.customerProfiles[index];
		Util.redirect('customerProfile/remove/{0}/{1}'.format(customerProfile.id, CustomerProfileList.idCustomer));
	},
	onButtonAdd: function()
	{
		Util.redirect('customerProfile/add/{0}'.format(CustomerProfileList.idCustomer));
	}
}
