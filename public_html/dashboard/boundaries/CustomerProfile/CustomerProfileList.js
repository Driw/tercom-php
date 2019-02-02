
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
		CustomerProfileList.dataTable = newDataTables(CustomerProfileList.table);
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
			var row = CustomerProfileList.dataTable.row.add(customerProfileRowData).draw();
		});
	},
	newAddressRowData: function(index, customerProfile)
	{
		var btnTemplate = '<button type="button" class="btn btn-sm btn-{0}" onclick="CustomerProfileList.{1}({2})">{3}</button>';
		var btnView = btnTemplate.format('info', 'onBtnView', index, 'Ver');
		var btnPermissions = btnTemplate.format('info', 'onBtnPermissions', index, 'Permissões');
		var btnRemove = btnTemplate.format('danger', 'onBtnRemove', index, 'Excluir');

		return [
			customerProfile.id,
			customerProfile.name,
			customerProfile.assignmentLevel,
			'<div class="btn-group">{0}{1}{2}</div>'.format(btnView, btnPermissions, btnRemove),
		];
	},
	onBtnView: function(index)
	{
		var customerProfile = CustomerProfileList.customerProfiles[index];

		if (customerProfile !== undefined)
			Util.redirect('customerProfile/view/{0}/{1}'.format(customerProfile.id, CustomerProfileList.idCustomer), true);
	},
	onBtnPermissions: function(index)
	{
		var customerProfile = CustomerProfileList.customerProfiles[index];

		if (customerProfile !== undefined)
			Util.redirect('customerProfile/permissions/{0}'.format(customerProfile.id), true);
	},
	onBtnRemove: function(index)
	{
		var customerProfile = CustomerProfileList.customerProfiles[index];

		if (customerProfile !== undefined)
			Util.redirect('customerProfile/remove/{0}/{1}'.format(customerProfile.id, CustomerProfileList.idCustomer), true);
	},
}
