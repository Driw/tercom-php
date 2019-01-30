
$(document).ready(function()
{
	CustomerEmployeeView.init();
});

var CustomerEmployeeView = CustomerEmployeeView ||
{
	init: function()
	{
		CustomerEmployeeView.form = $('#form-customer-employee-view');
		CustomerEmployeeView.formPhone = $('#form-customer-employee-phone');
		CustomerEmployeeView.formCellphone = $('#form-customer-employee-cellphone');
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

		CustomerEmployeeView.onSettingsPhone(settings.phoneSettings);
		CustomerEmployeeView.onSettingsCellphone(settings.phoneSettings);
	},
	onSettingsPhone: function(settings)
	{
		CustomerEmployeeView.formPhone.validate({
			'rules': {
				'phone[ddd]': {
					'required': true,
					'min': settings.minDDD,
					'max': settings.maxDDD,
				},
				'phone[number]': {
					'required': true,
					'rangelength': [ settings.minNumberLen + 1, settings.maxNumberLen + 1 ],
				},
				'phone[type]': {
					'required': true,
				},
			},
			submitHandler: function(form) {
				return false;
			},
		});
	},
	onSettingsCellphone: function(settings)
	{
		CustomerEmployeeView.formCellphone.validate({
			'rules': {
				'cellphone[ddd]': {
					'required': true,
					'min': settings.minDDD,
					'max': settings.maxDDD,
				},
				'cellphone[number]': {
					'required': true,
					'rangelength': [ settings.minNumberLen + 1, settings.maxNumberLen + 1 ],
				},
				'cellphone[type]': {
					'required': true,
				},
			},
			submitHandler: function(form) {
				return false;
			},
		});
	},
	onCustomerEmployeeLoaded: function(customerEmployee)
	{
		CustomerEmployeeView.customerEmployee = customerEmployee;
		var form = CustomerEmployeeView.form[0];
		$(form.name).val(customerEmployee.name);
		$(form.email).val(customerEmployee.email);
		CustomerEmployeeView.selectProfiles.val(CustomerEmployeeView.customerEmployee.customerProfile.id);
		CustomerEmployeeView.formPhone.trigger('reset');
		CustomerEmployeeView.formCellphone.trigger('reset');

		if (customerEmployee.cellphone !== null && customerEmployee.cellphone.id !== 0)
		{
			var formCellphone = CustomerEmployeeView.formCellphone[0];
			$(formCellphone['cellphone[ddd]']).val(customerEmployee.cellphone.ddd);
			$(formCellphone['cellphone[number]']).val(customerEmployee.cellphone.number).trigger('input');
			$(formCellphone['cellphone[type]']).val(customerEmployee.cellphone.type);
			$('#cellphone-group-add').fadeOut('fast');
			$('#cellphone-group-exist').fadeIn('fast');
		}

		else
		{
			$('#cellphone-group-add').fadeIn('fast');
			$('#cellphone-group-exist').fadeOut('fast');
		}

		if (customerEmployee.phone !== null && customerEmployee.phone.id !== 0)
		{
			var formPhone = CustomerEmployeeView.formPhone[0];
			$(formPhone['phone[ddd]']).val(customerEmployee.phone.ddd);
			$(formPhone['phone[number]']).val(customerEmployee.phone.number).trigger('input');
			$(formPhone['phone[type]']).val(customerEmployee.phone.type);
			$('#phone-group-add').fadeOut('fast');
			$('#phone-group-exist').fadeIn('fast');
		}

		else
		{
			$('#phone-group-add').fadeIn('fast');
			$('#phone-group-exist').fadeOut('fast');
		}
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
	addPhone: function()
	{
		if (CustomerEmployeeView.formPhone.valid())
			ws.customerEmployee_set(CustomerEmployeeView.id, CustomerEmployeeView.formPhone, CustomerEmployeeView.onCustomerEmployeeLoaded);
	},
	savePhone: function()
	{
		if (CustomerEmployeeView.formPhone.valid())
			ws.customerEmployee_set(CustomerEmployeeView.id, CustomerEmployeeView.formPhone, CustomerEmployeeView.onCustomerEmployeeLoaded);
	},
	removePhone: function()
	{
		ws.customerEmployee_removePhone(CustomerEmployeeView.id, CustomerEmployeeView.formPhone, CustomerEmployeeView.onCustomerEmployeeLoaded);
	},
	addCellphone: function()
	{
		if (CustomerEmployeeView.formCellphone.valid())
			ws.customerEmployee_set(CustomerEmployeeView.id, CustomerEmployeeView.formCellphone, CustomerEmployeeView.onCustomerEmployeeLoaded);
	},
	saveCellphone: function()
	{
		if (CustomerEmployeeView.formCellphone.valid())
			ws.customerEmployee_set(CustomerEmployeeView.id, CustomerEmployeeView.formCellphone, CustomerEmployeeView.onCustomerEmployeeLoaded);
	},
	removeCellphone: function()
	{
		ws.customerEmployee_removeCellphone(CustomerEmployeeView.id, CustomerEmployeeView.formCellphone, CustomerEmployeeView.onCustomerEmployeeLoaded);
	},
	onSubmited: function(customerEmployee, message)
	{
		CustomerEmployeeView.customerEmployee = customerEmployee;

		$('#row-message').show(DEFAULT_FADE);
		$('#row-message-span').html(message);
		setTimeout(function() { $('#row-message').hide('slow'); }, 3000);
	},
}
