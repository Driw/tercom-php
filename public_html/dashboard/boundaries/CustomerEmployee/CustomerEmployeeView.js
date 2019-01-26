
$(document).ready(function()
{
		CustomerEmployeeView.init();
});

var CustomerEmployeeView = CustomerEmployeeView ||
{
	init: function()
	{
		CustomerEmployeeView.form = $('#form-customer-employee-view');
		CustomerEmployeeView.selectProfiles = $(CustomerEmployeeView.form[0].idCustomerProfile);
		CustomerEmployeeView.id = $(CustomerEmployeeView.form[0].idCustomerEmployee).val();
		CustomerEmployeeView.idCustomer = $(CustomerEmployeeView.form[0].idCustomer).val();
		CustomerEmployeeView.initFormSettings();
		CustomerEmployeeView.loadCustomerProfiles();
		CustomerEmployeeView.loadCustomerEmployee();
	},
	initFormSettings: function()
	{
		ws.customerEmployee_settings(CustomerEmployeeView.form, CustomerEmployeeView.onSettings);
	},
	loadCustomerProfiles: function()
	{
		ws.customerProfile_getAll(CustomerEmployeeView.idCustomer, CustomerEmployeeView.form, CustomerEmployeeView.onLoadCustomerProfiles);
	},
	loadCustomerEmployee: function()
	{
		ws.customerEmployee_get(CustomerEmployeeView.id, CustomerEmployeeView.form, CustomerEmployeeView.onCustomerEmployeeLoaded);
	},
	onSettings: function(settings)
	{
		CustomerEmployeeView.form.validate({
			'rules': {
				'idCustomerProfile': {
					'required': true,
				},
				'name': {
					'required': true,
					'rangelength': [ settings.minNameLen, settings.maxNameLen ],
				},
				'email': {
					'required': true,
					'maxlength': settings.maxEmailLen,
					'remoteapi': {
						'webservice': 'customerEmployee/avaiable/email/{value}/{idCustomerEmployee}',
						'parameters': {
							'idCustomerEmployee': function() { return CustomerEmployeeView.id; },
						},
					},
				},
				'password': {
					'required': false,
					'rangelength': [ settings.minPasswordLen, settings.maxPasswordLen ],
				},
				'repassword': {
					'required': false,
					'equalTo': '#password',
				},
			},
			'messages': {
				'repassword': {
					'equalTo': 'As senhas não conferem',
				},
			},
			submitHandler: function(form) {
				try {
					CustomerEmployeeView.submit($(form));
				} catch (e) {
					console.log(e);
				}
				return false;
			}
		});
	},
	onCustomerEmployeeLoaded: function(customerEmployee)
	{
		CustomerEmployeeView.customerEmployee = customerEmployee;
		var form = CustomerEmployeeView.form[0];
		$(form.name).val(customerEmployee.name);
		$(form.email).val(customerEmployee.email);
		CustomerEmployeeView.selectProfiles.val(CustomerEmployeeView.customerEmployee.customerProfile.id);
	},
	onLoadCustomerProfiles: function(customerProfiles)
	{
		CustomerEmployeeView.selectProfiles.empty();
		CustomerEmployeeView.selectProfiles.append(Util.createElementOption('Selecione um Perfil', ''));
		CustomerEmployeeView.customerProfiles = customerProfiles.elements;
		CustomerEmployeeView.customerProfiles.forEach((customerProfile, index) => {
			var option = Util.createElementOption(customerProfile.name, customerProfile.id);
			CustomerEmployeeView.selectProfiles.append(option);
		});
		if (CustomerEmployeeView.customerEmployee !== undefined)
			CustomerEmployeeView.selectProfiles.val(CustomerEmployeeView.customerEmployee.customerProfile.id);
	},
	submit: function(form)
	{
		ws.customerEmployee_set(CustomerEmployeeView.customerEmployee.id, CustomerEmployeeView.form, CustomerEmployeeView.onSubmited);
	},
	onSubmited: function(customerEmployee, message)
	{
		CustomerEmployeeView.customerEmployee = customerEmployee;

		$('#row-message').show(DEFAULT_FADE);
		$('#row-message-span').html(message);
		setTimeout(function() { $('#row-message').hide('slow'); }, 3000);
	}
}
