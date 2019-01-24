
$(document).ready(function()
{
	CustomerEmployeeList.init();
});

var CustomerEmployeeList = CustomerEmployeeList ||
{
	init: function()
	{
		CustomerEmployeeList.idCustomer = $('#idCustomer').val();
		CustomerEmployeeList.table = $('#table-customer-employees');
		CustomerEmployeeList.tbody = CustomerEmployeeList.table.find('tbody');
		CustomerEmployeeList.datatables = newDataTables(CustomerEmployeeList.table);
		CustomerEmployeeList.loadCustomerEmployees();
	},
	loadCustomerEmployees: function()
	{
		ws.customerEmployee_getAll(CustomerEmployeeList.tbody, CustomerEmployeeList.onCustomerEmployeesLoaded);
	},
	onCustomerEmployeesLoaded: function(customerEmployees)
	{
		CustomerEmployeeList.customerEmployees = customerEmployees.elements;
		CustomerEmployeeList.customerEmployees.forEach((customerEmployee, index) =>
		{
			var customerEmployeeRowData = CustomerEmployeeList.newCustomerEmployeeRowData(index, customerEmployee);
			CustomerEmployeeList.datatables.row.add(customerEmployeeRowData).draw();
		});
	},
	newCustomerEmployeeRowData: function(index, customerEmployee)
	{
		var btnView = '<button type="button" class="btn btn-sm btn-primary" data-index="{0}" onclick="CustomerEmployeeList.onBtnView(this)">Ver</button>'.format(index);
		var btnEnable = '<button type="button" class="btn btn-sm btn-primary" data-index="{0}" onclick="CustomerEmployeeList.onBtnEnable(this)">Ativar</button>'.format(index);
		var btnDisable = '<button type="button" class="btn btn-sm btn-secondary" data-index="{0}" onclick="CustomerEmployeeList.onBtnDisable(this)">Desativar</button>'.format(index);
		console.log(customerEmployee);

		return [
			customerEmployee.id,
			customerEmployee.customerProfile.name,
			customerEmployee.name,
			customerEmployee.email,
			'<div class="btn-group">{0}{1}</div>'.format(btnView, customerEmployee.enabled ? btnDisable : btnEnable),
		];
	},
	onBtnView: function(button)
	{
		var index = button.dataset.index;
		var customerEmployee = CustomerEmployeeList.customerEmployees[index];

		if (customerEmployee !== undefined)
			Util.redirect('customerEmployee/view/{0}/{1}'.format(customerEmployee.id, customerEmployee.customerProfile.customer.id), true);
	},
	onBtnEnable: function(button)
	{
		var index = button.dataset.index;
		var customerEmployee = CustomerEmployeeList.customerEmployees[index];
		var tr = $(button).parents('tr');

		ws.customerEmployee_enabled(customerEmployee.id, true, tr, function(customerEmployee)
		{
			CustomerEmployeeList.onEnabled(tr, index, customerEmployee);
		});
	},
	onBtnDisable: function(button)
	{
		var index = button.dataset.index;
		var customerEmployee = CustomerEmployeeList.customerEmployees[index];
		var tr = $(button).parents('tr');

		ws.customerEmployee_enabled(customerEmployee.id, false, tr, function(customerEmployee)
		{
			CustomerEmployeeList.onEnabled(tr, index, customerEmployee);
		});
	},
	onEnabled: function(tr, index, customerEmployee)
	{
		CustomerEmployeeList.customerEmployees[index] = customerEmployee;
		var customerEmployeeRowData = CustomerEmployeeList.newCustomerEmployeeRowData(index, customerEmployee);
		CustomerEmployeeList.datatables.row(tr).data(customerEmployeeRowData);
	},
}








//
