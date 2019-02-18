
$(document).ready(function()
{
	CustomerSearch.init();
});

var CustomerSearch = CustomerSearch ||
{
	init: function()
	{
		CustomerSearch.customers = [];
		CustomerSearch.form = $('#form-search');
		CustomerSearch.table = $('#table-customers');
		CustomerSearch.tbody = CustomerSearch.table.find('tbody');
		CustomerSearch.datatables = newDataTables(CustomerSearch.table);
		CustomerSearch.initFormtSettings();
	},
	initFormtSettings: function()
	{
		CustomerSearch.form.validate({
			'rules': {
				'filter': {
					'required': true,
				},
				'value': {
					'required': true,
				},
			},
			submitHandler: function(form) {
				try {
					CustomerSearch.onSearch($(form));
				} catch (e) {
					alert(e.message);
					console.log(e.stack);
				}
				return false;
			},
		});
	},
	onSearch: function()
	{
		var form = CustomerSearch.form[0];
		var value = $(form.value).val();
		var filter = $(form.filter).val();

		ws.customer_search(filter, value, CustomerSearch.form, CustomerSearch.searchCustomers);
	},
	searchCustomers: function(customers)
	{
		CustomerSearch.datatables.clear();
		CustomerSearch.customers = customers.elements;
		CustomerSearch.customers.forEach(function(customer, index)
		{
			var customerRowData = CustomerSearch.newCustomerRowData(index, customer);
			CustomerSearch.datatables.row.add(customerRowData).draw();
		});
	},
	newCustomerRowData: function(index, customer)
	{
		var btnTemplate = '<button type="button" class="btn btn-sm {0}" onclick="CustomerSearch.{1}({2}, this)" title="{3}">{4}</button>';
		var btnView = btnTemplate.format(BTN_CLASS_VIEW, 'onButtonView', index, 'Ver Dados do Cliente', ICON_VIEW);
		var btnAddresses = btnTemplate.format(BTN_CLASS_VIEW, 'onButtonAddresses', index, 'Ver Endereรงos do Cliente', ICON_ADDRESSES);
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
		var customer = CustomerSearch.customers[index];
		Util.redirect('customer/view/{0}'.format(customer.id));
	},
	onButtonAddresses: function(index)
	{
		var customer = CustomerSearch.customers[index];
		Util.redirect('customer/viewAddresses/{0}'.format(customer.id));
	},
	onButtonProfiles: function(index)
	{
		var customer = CustomerSearch.customers[index];
		Util.redirect('customerProfile/list/{0}'.format(customer.id));
	},
	onButtonEnable: function(index, button)
	{
		var customer = CustomerSearch.customers[index];
		var tr = $(button).parents('tr');

		ws.customer_setActive(customer.id, tr, function(customer, message)
		{
			CustomerSearch.onCustomerChange(index, customer, message, tr);
		});
	},
	onButtonDisable: function(index, button)
	{
		var customer = CustomerSearch.customers[index];
		var tr = $(button).parents('tr');

		ws.customer_setInactive(customer.id, tr, function(customer, message)
		{
			CustomerSearch.onCustomerChange(index, customer, message, tr);
		});
	},
	onCustomerChange: function(index, customer, message, tr)
	{
		var customerRowData = CustomerSearch.newCustomerRowData(index, customer);
		CustomerSearch.datatables.row(tr).data(customerRowData);
		CustomerSearch.customers[index] = customer;
		Util.showSuccess(message);
	},
}
