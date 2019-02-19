
$(document).ready(function()
{
	TercomEmployeeView.init();
});

var TercomEmployeeView = TercomEmployeeView ||
{
	init: function()
	{
		TercomEmployeeView.loaded = false;
		TercomEmployeeView.form = $('#form-tercom-employee-view');
		TercomEmployeeView.formPhone = $('#form-tercom-employee-phone');
		TercomEmployeeView.formCellphone = $('#form-tercom-employee-cellphone');
		TercomEmployeeView.selectProfiles = $(this.form[0].idTercomProfile);
		TercomEmployeeView.id = $(this.form[0].idTercomEmployee).val();
		TercomEmployeeView.initFormSettings();
		TercomEmployeeView.loadProfiles();
		TercomEmployeeView.loadTercomEmployee();
	},
	initFormSettings: function()
	{
		ws.tercomEmployee_settings(TercomEmployeeView.form, TercomEmployeeView.onSettings);
	},
	loadProfiles: function()
	{
		ws.tercomProfile_getAll(TercomEmployeeView.form, TercomEmployeeView.onLoadProfiles);
	},
	loadTercomEmployee: function()
	{
		ws.tercomEmployee_get(TercomEmployeeView.id, TercomEmployeeView.form, TercomEmployeeView.onTercomEmployeeLoaded);
	},
	onSettings: function(settings)
	{
		TercomEmployeeView.form.validate({
			'rules': {
				'cpf': {
					'required': true,
					'remoteapi': {
						'webservice': 'tercomEmployee/avaiable/cpf/{value}/{idTercomEmployee}',
						'replacePattern': [ /\D/g, '' ],
						'parameters': {
							'idTercomEmployee': function() { return TercomEmployeeView.id; },
						},
					},
				},
				'name': {
					'required': true,
					'rangelength': [ settings.minNameLen, settings.maxNameLen ],
				},
				'password': {
					'required': false,
					'rangelength': [ settings.minPasswordLen, settings.maxPasswordLen ],
				},
				'repassword': {
					'required': false,
					'equalTo': '#password',
				},
				'email': {
					'required': true,
					'maxlength': settings.maxEmailLen,
					'remoteapi': {
						'webservice': 'tercomEmployee/avaiable/email/{value}/{idTercomEmployee}',
						'parameters': {
							'idTercomEmployee': function() { return TercomEmployeeView.id; },
						},
					},
				},
			},
			'messages': {
				'repassword': {
					'equalTo': 'As senhas não conferem',
				},
			},
			submitHandler: function(form) {
				try {
					TercomEmployeeView.submit($(form));
				} catch (e) {
					console.log(e);
				}
				return false;
			}
		});

		TercomEmployeeView.onSettingsPhone(settings.phoneSettings);
		TercomEmployeeView.onSettingsCellphone(settings.phoneSettings);
	},
	onSettingsPhone: function(settings)
	{
		TercomEmployeeView.formPhone.validate({
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
		TercomEmployeeView.formCellphone.validate({
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
	onTercomEmployeeLoaded: function(tercomEmployee, message)
	{
		TercomEmployeeView.tercomEmployee = tercomEmployee;
		var form = TercomEmployeeView.form[0];
		$(form.cpf).val(tercomEmployee.cpf).trigger('input');
		$(form.name).val(tercomEmployee.name);
		$(form.email).val(tercomEmployee.email);
		TercomEmployeeView.tercomEmployee = tercomEmployee;
		TercomEmployeeView.selectProfiles.val(tercomEmployee.tercomProfile.id);
		TercomEmployeeView.formPhone.trigger('reset');
		TercomEmployeeView.formCellphone.trigger('reset');

		if (tercomEmployee.cellphone !== null && tercomEmployee.cellphone.id !== 0)
		{
			var formCellphone = TercomEmployeeView.formCellphone[0];
			$(formCellphone['cellphone[ddd]']).val(tercomEmployee.cellphone.ddd);
			$(formCellphone['cellphone[number]']).val(tercomEmployee.cellphone.number).trigger('input');
			$(formCellphone['cellphone[type]']).val(tercomEmployee.cellphone.type);
			$('#cellphone-group-add').fadeOut('fast');
			$('#cellphone-group-exist').fadeIn('fast');
		}

		else
		{
			$('#cellphone-group-add').fadeIn('fast');
			$('#cellphone-group-exist').fadeOut('fast');
		}

		if (tercomEmployee.phone !== null && tercomEmployee.phone.id !== 0)
		{
			var formPhone = TercomEmployeeView.formPhone[0];
			$(formPhone['phone[ddd]']).val(tercomEmployee.phone.ddd);
			$(formPhone['phone[number]']).val(tercomEmployee.phone.number).trigger('input');
			$(formPhone['phone[type]']).val(tercomEmployee.phone.type);
			$('#phone-group-add').fadeOut('fast');
			$('#phone-group-exist').fadeIn('fast');
		}

		else
		{
			$('#phone-group-add').fadeIn('fast');
			$('#phone-group-exist').fadeOut('fast');
		}

		if (TercomEmployeeView.loaded)
			Util.showSuccess(message);
		else
			Util.showInfo(message);

		TercomEmployeeView.loaded = true;
	},
	onLoadProfiles: function(tercomProfiles)
	{
		console.log(tercomProfiles);
		TercomEmployeeView.selectProfiles.empty();
		TercomEmployeeView.selectProfiles.append(Util.createElementOption('Selecione um Perfil', ''));
		TercomEmployeeView.tercomProfiles = tercomProfiles.elements;
		TercomEmployeeView.tercomProfiles.forEach((tercomProfile, index) => {
			var option = Util.createElementOption(tercomProfile.name, tercomProfile.id);
			TercomEmployeeView.selectProfiles.append(option);
		});

		if (TercomEmployeeView.tercomEmployee !== undefined)
			TercomEmployeeView.selectProfiles.val(TercomEmployeeView.tercomEmployee.tercomProfile.id);
	},
	submit: function(form)
	{
		ws.tercomEmployee_set(TercomEmployeeView.id, TercomEmployeeView.form, TercomEmployeeView.onSubmited);
	},
	addPhone: function()
	{
		if (TercomEmployeeView.formPhone.valid())
			ws.tercomEmployee_set(TercomEmployeeView.id, TercomEmployeeView.formPhone, TercomEmployeeView.onTercomEmployeeLoaded);
	},
	savePhone: function()
	{
		if (TercomEmployeeView.formPhone.valid())
			ws.tercomEmployee_set(TercomEmployeeView.id, TercomEmployeeView.formPhone, TercomEmployeeView.onTercomEmployeeLoaded);
	},
	removePhone: function()
	{
		ws.tercomEmployee_removePhone(TercomEmployeeView.id, TercomEmployeeView.formPhone, TercomEmployeeView.onTercomEmployeeLoaded);
	},
	addCellphone: function()
	{
		if (TercomEmployeeView.formCellphone.valid())
			ws.tercomEmployee_set(TercomEmployeeView.id, TercomEmployeeView.formCellphone, TercomEmployeeView.onTercomEmployeeLoaded);
	},
	saveCellphone: function()
	{
		if (TercomEmployeeView.formCellphone.valid())
			ws.tercomEmployee_set(TercomEmployeeView.id, TercomEmployeeView.formCellphone, TercomEmployeeView.onTercomEmployeeLoaded);
	},
	removeCellphone: function()
	{
		ws.tercomEmployee_removeCellphone(TercomEmployeeView.id, TercomEmployeeView.formCellphone, TercomEmployeeView.onTercomEmployeeLoaded);
	},
	onSubmited: function(tercomEmployee, message)
	{
		TercomEmployeeView.onTercomEmployeeLoaded(tercomEmployee, message);
	}
}
