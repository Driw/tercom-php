
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
		ws.customerProfile_getAll(CustomerProfileList.idCustomer, CustomerProfileList.tbody, CustomerProfileList.onCustomerProfilesLoaded);
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
		var btnView = '<button type="button" class="btn btn-sm btn-primary" data-index="{0}" onclick="CustomerProfileList.onBtnView(this)">Ver</button>'.format(index);
		var btnRemove = '<button type="button" class="btn btn-sm btn-danger" data-index="{0}" onclick="CustomerProfileList.onBtnRemove(this)">Excluir</button>'.format(index);

		return [
			customerProfile.id,
			customerProfile.name,
			customerProfile.assignmentLevel,
			'<div class="btn-group">{0}{1}</div>'.format(btnView, btnRemove),
		];
	},
	onBtnView: function(button)
	{
		var index = button.dataset.index;
		var customerProfile = CustomerProfileList.customerProfiles[index];

		if (customerProfile !== undefined)
			Util.redirect('customerProfile/view/{0}/{1}'.format(customerProfile.id, CustomerProfileList.idCustomer), true);
	},
	onBtnRemove: function(button)
	{
		var index = button.dataset.index;
		var customerProfile = CustomerProfileList.customerProfiles[index];

		if (customerProfile !== undefined)
			Util.redirect('customerProfile/remove/{0}/{1}'.format(customerProfile.id, CustomerProfileList.idCustomer), true);
	},
}
