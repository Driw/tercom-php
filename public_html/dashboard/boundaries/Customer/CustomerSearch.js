
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
		CustomerSearch.datatables.rows().remove();
		CustomerSearch.customers = customers.elements;
		CustomerSearch.customers.forEach(function(customer, index)
		{
			var viewButton = '<button type="button" class="btn btn-info" data-index="{0}" onclick="CustomerSearch.onButtonSee(this)">Ver</button>'.format(index);
			var row = CustomerSearch.datatables.row.add([
				customer.id,
				customer.cnpj,
				customer.fantasyName,
				customer.email,
				'<div class="btn-group">{0}</div>'.format(viewButton),
			]).draw();
		});
	},
	onButtonSee: function(button)
	{
		var index = button.dataset.index;
		var customer = CustomerSearch.customers[index];

		if (customer !== undefined)
			Util.redirect('customer/view/{0}'.format(customer.id), true);
	},
}
