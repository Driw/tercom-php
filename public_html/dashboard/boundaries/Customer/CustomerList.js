
$(document).ready(function()
{
	CustomerList.init();
});

var CustomerList = CustomerList ||
{
	init: function()
	{
		CustomerList.customers = [];
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
		var id = customer.id;
		var btnView = '<button type="button" class="btn btn-sm btn-info" data-index="{0}" onclick="CustomerList.onClickBtnView(this)">Ver</button>'.format(index);
		var btnAddresses = '<button type="button" class="btn btn-sm btn-info" data-index="{0}" onclick="CustomerList.onClickBtnAddresses(this)">Endereços</button>'.format(index);
		var btnActive = '<button type="button" class="btn btn-sm btn-primary" data-index="{0}" onclick="CustomerList.onClickBtnActive(this)">Ativar</button>'.format(index);
		var btnDesactive = '<button type="button" class="btn btn-sm btn-secondary" data-index="{0}" onclick="CustomerList.onClickBtnInactive(this)">Desativar</button>'.format(index);

		return [
			customer.id,
			customer.cnpj,
			customer.fantasyName,
            customer.email,
			'<div class="btn-group" id="customer-{0}">{1}{2}{3}</div>'.format(customer.id, btnView, btnAddresses, customer.inactive ? btnActive : btnDesactive),
		];
	},
	setCustomer: function(customer)
	{
		var index = CustomerList.lookCustomerIndex(customer.id);
		CustomerList.customers[index] = customer;

		return index;
	},
	lookCustomerIndex: function(idCustomer)
	{
		for (var i = 0; i < CustomerList.customers.length; i++)
			if (CustomerList.customers[i].id === idCustomer)
				return i;

		return -1;
	},
	onClickBtnView: function(button)
	{
		var index = button.dataset.index;
		var customer = CustomerList.customers[index];
		Util.redirect('customer/view/{0}'.format(customer.id), true);
	},
	onClickBtnAddresses: function(button)
	{
		var index = button.dataset.index;
		var customer = CustomerList.customers[index];
		Util.redirect('customer/viewAddresses/{0}'.format(customer.id), true);
	},
	onClickBtnActive: function(button)
	{
		var index = button.dataset.index;
		var customer = CustomerList.customers[index];
		var tr = $(this).parents('tr');

		ws.customer_setActive(customer.id, tr, CustomerList.onCustomerSetInactive);
	},
	onClickBtnInactive: function(button)
	{
		var index = button.dataset.index;
		var customer = CustomerList.customers[index];
		var tr = $(this).parents('tr');

		ws.customer_setInactive(customer.id, tr, CustomerList.onCustomerSetInactive);
	},
	onCustomerSetInactive: function(customer)
	{
		var tr = $('#customer-{0}'.format(customer.id)).parents('tr');
		var index = CustomerList.setCustomer(customer);
		var customerRowData = CustomerList.newCustomerRowData(index, customer);
		CustomerList.datatables.row(tr).data(customerRowData);
	},
}
