
$(document).ready(function()
{
	CustomerList.init();
});

var CustomerList = CustomerList ||
{
	init: function()
	{
		CustomerList.table = $('#table-customers');
		CustomerList.tbody = CustomerList.table.children('tbody');
		CustomerList.datatables = newDataTables(CustomerList.table);
		CustomerList.loadCustomers();
	},
	loadCustomers: function()
	{
		ws.customer_getAll(CustomerList.tbody, CustomerList.onLoadCustomers);
	},
	onLoadCustomers: function(customers)
	{
		CustomerList.customers = customers.elements;
		CustomerList.customers.forEach(function(customer)
		{
			CustomerList.addCustomerRow(customer);
		});
	},
	addCustomerRow: function(customer)
	{
		var index = CustomerList.customers.push(customer) - 1;
		var customerRowData = CustomerList.newCustomerRowData(index, customer);
		CustomerList.datatables.row.add(customerRowData).draw();
	},
	newCustomerRowData: function(index, customer)
	{
		var btnTemplate = '<button type="button" class="btn btn-sm {0}" onclick="CustomerList.{1}({2}, this)" title="{3}">{4}</button>';
		var btnView = btnTemplate.format(BTN_CLASS_VIEW, 'onButtonView', index, 'Ver Dados do Cliente', ICON_VIEW);
		var btnAddresses = btnTemplate.format(BTN_CLASS_VIEW, 'onButtonAddresses', index, 'Ver Endereços do Cliente', ICON_ADDRESSES);
		var btnProfiles = btnTemplate.format(BTN_CLASS_VIEW, 'onButtonProfiles', index, 'Ver Perfis do Cliente', ICON_PROFILES);
		var btnEnable = btnTemplate.format(BTN_CLASS_ENABLE, 'onButtonEnable', index, 'Ativar Cliente', ICON_ENABLE);
		var btnDisable = btnTemplate.format(BTN_CLASS_DISABLE, 'onButtonDisable', index, 'Desativar Cliente', ICON_DISABLE);

		return [
			customer.id,
			customer.stateRegistry,
			customer.cnpj,
			customer.fantasyName,
			customer.email,
			'<div class="btn-group">{1}{2}{3}{4}</div>'.format(customer.id, btnView, btnProfiles, btnAddresses, customer.inactive ? btnEnable : btnDisable),
		];
	},
	onButtonView: function(index)
	{
		var customer = CustomerList.customers[index];
		Util.redirect('customer/view/{0}'.format(customer.id));
	},
	onButtonAddresses: function(index)
	{
		var customer = CustomerList.customers[index];
		Util.redirect('customer/viewAddresses/{0}'.format(customer.id));
	},
	onButtonProfiles: function(index)
	{
		var customer = CustomerList.customers[index];
		Util.redirect('customerProfile/list/{0}'.format(customer.id));
	},
	onButtonEnable: function(index, button)
	{
		var customer = CustomerList.customers[index];
		var tr = $(button).parents('tr');

		ws.customer_setActive(customer.id, tr, function(customer, message)
		{
			CustomerList.onCustomerChange(index, customer, message, tr);
		});
	},
	onButtonDisable: function(index, button)
	{
		var customer = CustomerList.customers[index];
		var tr = $(button).parents('tr');

		ws.customer_setInactive(customer.id, tr, function(customer, message)
		{
			CustomerList.onCustomerChange(index, customer, message, tr);
		});
	},
	onCustomerChange: function(index, customer, message, tr)
	{
		var customerRowData = CustomerList.newCustomerRowData(index, customer);
		CustomerList.datatables.row(tr).data(customerRowData);
		CustomerList.customers[index] = customer;
		Util.showSuccess(message);
	},
}
