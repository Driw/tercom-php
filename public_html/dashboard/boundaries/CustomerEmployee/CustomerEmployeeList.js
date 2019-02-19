
$(document).ready(function()
{
	CustomerEmployeeList.init();
});

var CustomerEmployeeList = CustomerEmployeeList ||
{
	init: function()
	{
		CustomerEmployeeList.idCustomer = $('#idCustomer').val();
		CustomerEmployeeList.idCustomerProfile = $('#idCustomerProfile').val();
		CustomerEmployeeList.selectCustomer = $('#select-customer');
		CustomerEmployeeList.selectCustomer.change(CustomerEmployeeList.onChangeCustomer)
		CustomerEmployeeList.selectCustomerProfile = $('#select-customer-profile');
		CustomerEmployeeList.selectCustomerProfile.change(CustomerEmployeeList.onChangeCustomerProfile)
		CustomerEmployeeList.table = $('#table-customer-employees');
		CustomerEmployeeList.tbody = CustomerEmployeeList.table.find('tbody');
		CustomerEmployeeList.datatables = newDataTables(CustomerEmployeeList.table);
		CustomerEmployeeList.loadCustomers();
		CustomerEmployeeList.loadCustomerProfiles();
		CustomerEmployeeList.loadCustomerEmployees();
	},
	loadCustomers: function()
	{
		ws.customer_getAll(CustomerEmployeeList.selectCustomer, CustomerEmployeeList.onCustomerLoaded);
	},
	loadCustomerProfiles: function()
	{
		if (CustomerEmployeeList.idCustomer > 0)
		{
			ws.customerProfile_getByCustomer(CustomerEmployeeList.idCustomer, CustomerEmployeeList.selectCustomerProfile, CustomerEmployeeList.onCustomerProfileLoaded);
			CustomerEmployeeList.loadCustomerEmployees();
		}

		else
		{
			CustomerEmployeeList.datatables.clear().draw();
			CustomerEmployeeList.selectCustomerProfile.selectpicker();
			CustomerEmployeeList.selectCustomerProfile.empty();
			CustomerEmployeeList.selectCustomerProfile.selectpicker('refresh');
		}
	},
	loadCustomerEmployees: function()
	{
		if (CustomerEmployeeList.idCustomer != 0 && CustomerEmployeeList.idCustomerProfile == 0)
			ws.customerEmployee_getByCustomer(CustomerEmployeeList.idCustomer, CustomerEmployeeList.tbody, CustomerEmployeeList.onCustomerEmployeesLoaded);

		else if (CustomerEmployeeList.idCustomerProfile != 0 && CustomerEmployeeList.idCustomerProfile != 0)
			ws.customerEmployee_getByProfile(CustomerEmployeeList.idCustomerProfile, CustomerEmployeeList.tbody, CustomerEmployeeList.onCustomerEmployeesLoaded);

		else
			ws.customerEmployee_getAll(CustomerEmployeeList.tbody, CustomerEmployeeList.onCustomerEmployeesLoaded);
	},
	onCustomerLoaded: function(customers)
	{
		CustomerEmployeeList.selectCustomer.selectpicker();
		var option = Util.createElementOption('Selecione um Cliente', 0);
		CustomerEmployeeList.selectCustomer.append(option);

		CustomerEmployeeList.customers = customers.elements;
		CustomerEmployeeList.customers.forEach((customer) =>
		{
			var selected = customer.id == CustomerEmployeeList.idCustomer;
			var option = Util.createElementOption('{0} - {1}'.format(customer.fantasyName, Util.formatCnpj(customer.cnpj)), customer.id, selected);
			CustomerEmployeeList.selectCustomer.append(option);
		});
		CustomerEmployeeList.selectCustomer.selectpicker('refresh');
	},
	onCustomerProfileLoaded: function(customerProfiles)
	{
		CustomerEmployeeList.selectCustomerProfile.selectpicker();
		CustomerEmployeeList.selectCustomerProfile.empty();
		var option = Util.createElementOption('Todos', 0);
		CustomerEmployeeList.selectCustomerProfile.append(option);

		CustomerEmployeeList.customerProfiles = customerProfiles.elements;
		CustomerEmployeeList.customerProfiles.forEach((customerProfile) =>
		{
			var selected = customerProfile.id == CustomerEmployeeList.idCustomerProfile;
			var option = Util.createElementOption(customerProfile.name, customerProfile.id, selected);
			CustomerEmployeeList.selectCustomerProfile.append(option);
		});
		CustomerEmployeeList.selectCustomerProfile.selectpicker('refresh');
	},
	onCustomerEmployeesLoaded: function(customerEmployees)
	{
		CustomerEmployeeList.datatables.clear().draw();
		CustomerEmployeeList.customerEmployees = customerEmployees.elements;
		CustomerEmployeeList.customerEmployees.forEach((customerEmployee, index) =>
		{
			var customerEmployeeRowData = CustomerEmployeeList.newCustomerEmployeeRowData(index, customerEmployee);
			CustomerEmployeeList.datatables.row.add(customerEmployeeRowData).draw();
		});
	},
	newCustomerEmployeeRowData: function(index, customerEmployee)
	{
		var btnTemplate = '<button type="button" class="btn btn-sm {0}" onclick="CustomerEmployeeList.{1}({2}, this)" title="{3}">{4}</button>';
		var btnView = btnTemplate.format(BTN_CLASS_VIEW, 'onButtonView', index, 'Ver Dados do Funcionário de Cliente', ICON_VIEW);
		var btnEnable = btnTemplate.format(BTN_CLASS_ENABLE, 'onButtonEnable', index, 'Ativar Funcionário de Cliente', ICON_ENABLE);
		var btnDisable = btnTemplate.format(BTN_CLASS_DISABLE, 'onButtonDisable', index, 'Desativar Funcionário de Cliente', ICON_DISABLE);

		return [
			customerEmployee.id,
			customerEmployee.customerProfile.name,
			customerEmployee.name,
			customerEmployee.email,
			'<div class="btn-group">{0}{1}</div>'.format(btnView, customerEmployee.enabled ? btnDisable : btnEnable),
		];
	},
	onChangeCustomer: function(e)
	{
		CustomerEmployeeList.idCustomer = e.target.value;
		CustomerEmployeeList.idCustomerProfile = 0;
		CustomerEmployeeList.loadCustomerProfiles();
	},
	onChangeCustomerProfile: function(e)
	{
		CustomerEmployeeList.idCustomerProfile = e.target.value;
		CustomerEmployeeList.loadCustomerEmployees();
	},
	onButtonView: function(index)
	{
		var customerEmployee = CustomerEmployeeList.customerEmployees[index];
		Util.redirect('customerEmployee/view/{0}/{1}'.format(customerEmployee.id, CustomerEmployeeList.idCustomer));
	},
	onButtonEnable: function(index, button)
	{
		var customerEmployee = CustomerEmployeeList.customerEmployees[index];
		var tr = $(button).parents('tr');

		ws.customerEmployee_enabled(customerEmployee.id, true, tr, function(customerEmployee, message)
		{
			CustomerEmployeeList.onCustomerEmployeeChange(index, customerEmployee, message, tr);
		});
	},
	onButtonDisable: function(index, button)
	{
		var customerEmployee = CustomerEmployeeList.customerEmployees[index];
		var tr = $(button).parents('tr');

		ws.customerEmployee_enabled(customerEmployee.id, false, tr, function(customerEmployee, message)
		{
			CustomerEmployeeList.onCustomerEmployeeChange(index, customerEmployee, message, tr);
		});
	},
	onCustomerEmployeeChange: function(index, customerEmployee, message, tr)
	{
		var customerEmployeeRowData = CustomerEmployeeList.newCustomerEmployeeRowData(index, customerEmployee);
		CustomerEmployeeList.datatables.row(tr).data(customerEmployeeRowData);
		CustomerEmployeeList.customerEmployees[index] = customerEmployee;
		Util.showSuccess(message);
	},
}








//
