
$(document).ready(function()
{
	ProviderView.init();
	ProviderView.initValidation();
	ProviderView.loadProvider();
});

var ProviderView = ProviderView ||
{
	init: function()
	{
		ProviderView.form = $('#form-view');
		ProviderView.formCommercial = $('#form-commercial');
		ProviderView.formOtherphone = $('#form-otherphone');
		ProviderView.idProvider = ProviderView.form[0].id.value;
		ProviderView.provider = {};
		ProviderView.loaded = false;

		ProviderView.modalContact = $('#modal-contact');
		ProviderView.formContact = $('#form-contact');
		ProviderView.formContactCommercial = $('#form-contact-commercial');
		ProviderView.formContactOtherphone = $('#form-contact-otherphone');
		ProviderView.tableConctacts = $('#table-contacts');
		ProviderView.tbodyContacts = ProviderView.tableConctacts.find('tbody');
		ProviderView.dataTableContacts = newDataTables(ProviderView.tableConctacts);
	},
	initValidation: function()
	{
		ws.provider_settings(ProviderView.form, ProviderView.onSettings);
	},
	onSettings: function(settings)
	{
		ProviderView.initFormValidation(settings);
		ProviderView.initPhoneValidation(settings.phoneSettings);
	},
	initFormValidation: function(settings)
	{
		ProviderView.form.validate({
			'rules': {
				'cnpj': {
					'required': true,
					'remoteapi': {
						'webservice': 'provider/avaiable/cnpj/{value}/{idProvider}',
						'replacePattern': [ /\D/g, '' ],
						'parameters' : {
							'idProvider': function() { return ProviderView.form[0].id.value; },
						}
					},
				},
				'companyName': {
					'required': true,
					'rangelength': [ settings.minCompanyNameLen, settings.maxCompanyNameLen ],
				},
				'fantasyName': {
					'required': true,
					'rangelength': [ settings.minFantasyNameLen, settings.maxFantasyNameLen ],
				},
				'spokesman': {
					'required': false,
					'rangelength': [ settings.minSpokesmanLen, settings.maxSpokesmanLen ],
				},
				'site': {
					'required': false,
					'maxlength': settings.maxSiteLen,
				},
				'inactive': {
					'required': false,
				},
			},
		});
	},
	initPhoneValidation: function(settings)
	{
		ProviderView.formCommercial.validate({
			'rules': {
				'commercial[ddd]': {
					'required': true,
					'min': settings.minDDD,
					'max': settings.maxDDD,
				},
				'commercial[number]': {
					'required': true,
					'rangelength': [ settings.minNumberLen + 1, settings.maxNumberLen + 1 ],
				},
				'commercial[type]': {
					'required': true,
				},
			},
		});
		ProviderView.formOtherphone.validate({
			'rules': {
				'otherphone[ddd]': {
					'required': true,
					'min': settings.minDDD,
					'max': settings.maxDDD,
				},
				'otherphone[number]': {
					'required': true,
					'rangelength': [ settings.minNumberLen + 1, settings.maxNumberLen + 1 ],
				},
				'otherphone[type]': {
					'required': true,
				},
			},
		});
	},
	loadProvider: function()
	{
		ws.provider_get(ProviderView.idProvider, ProviderView.form, ProviderView.setProvider);
		ws.providerContact_getAll(ProviderView.idProvider, ProviderView.tbodyContacts, ProviderView.setContacts);
	},
	saveProvider: function()
	{
		ws.provider_set(ProviderView.idProvider, ProviderView.form, ProviderView.setProvider);
	},
	setProvider: function(provider, message, loaded)
	{
		$(ProviderView.form[0].cnpj).val(provider.cnpj).trigger('input');
		ProviderView.idProvider = provider.id;
		ProviderView.form[0].companyName.value = provider.companyName;
		ProviderView.form[0].fantasyName.value = provider.fantasyName;
		ProviderView.form[0].spokesman.value = provider.spokesman;
		ProviderView.form[0].site.value = provider.site;
		ProviderView.setCommercial(provider.commercial);
		ProviderView.setOtherphone(provider.otherphone);
		ProviderView.setContacts(provider.contacts);

		if (!ProviderView.loaded)
			Util.showInfo(message);
		else
			Util.showSuccess(message);

		ProviderView.loaded = true;
	},
	setCommercial: function(phone)
	{
		ProviderView.onCommercialExists(phone !== null);

		if (phone === null)
		{
			ProviderView.formCommercial.trigger('reset');
			return;
		}

		$(ProviderView.formCommercial[0]['commercial[ddd]']).val(phone.ddd);
		$(ProviderView.formCommercial[0]['commercial[type]']).val(phone.type);
		$(ProviderView.formCommercial[0]['commercial[number]']).val(phone.number).trigger('input');
		$(ProviderView.formCommercial[0]['commercial[idPhone]']).val(phone.id);
	},
	setOtherphone: function(phone)
	{
		ProviderView.onOtherphoneExists(phone !== null);

		if (phone === null)
		{
			ProviderView.formOtherphone.trigger('reset');
			return;
		}

		$(ProviderView.formOtherphone[0]['otherphone[ddd]']).val(phone.ddd);
		$(ProviderView.formOtherphone[0]['otherphone[type]']).val(phone.type);
		$(ProviderView.formOtherphone[0]['otherphone[number]']).val(phone.number).trigger('input');
		$(ProviderView.formOtherphone[0]['otherphone[idPhone]']).val(phone.id);
	},
	setContacts: function(contacts)
	{
		if (contacts === null)
			return;

		ProviderView.contacts = [];
		contacts.elements.forEach(function(contact)
		{
			ProviderView.addRowContact(contact);
		});
	},
	addRowContact: function(contact)
	{
		var index = ProviderView.contacts.length;
		contact.index = index;
		contact.row = ProviderView.dataTableContacts.row.add(ProviderView.newDataTablesRowContact(contact)).draw();
		ProviderView.contacts[index] = contact;
	},
	addCommercial: function()
	{
		if (ProviderView.formCommercial.valid())
			ws.provider_setPhones(ProviderView.idProvider, ProviderView.formCommercial, ProviderView.setProvider);
	},
	addOtherphone: function()
	{
		if (ProviderView.formOtherphone.valid())
			ws.provider_setPhones(ProviderView.idProvider, ProviderView.formOtherphone, ProviderView.setProvider);
	},
	saveCommercial: function()
	{
		if (ProviderView.formCommercial.valid())
			ws.provider_setPhones(ProviderView.idProvider, ProviderView.formCommercial, ProviderView.setProvider);
	},
	saveOtherphone: function()
	{
		if (ProviderView.formOtherphone.valid())
			ws.provider_setPhones(ProviderView.idProvider, ProviderView.formOtherphone, ProviderView.setProvider);
	},
	removeCommercial: function()
	{
		if (ProviderView.formCommercial.valid())
			ws.provider_removeCommercial(ProviderView.idProvider, ProviderView.formCommercial, ProviderView.setProvider);
	},
	removeOtherphone: function()
	{
		if (ProviderView.formOtherphone.valid())
			ws.provider_removeOtherphone(ProviderView.idProvider, ProviderView.formOtherphone, ProviderView.setProvider);
	},
	newContact: function()
	{
		$('#modal-contact-add').show();
		$('#modal-contact-save').hide();
		$(ProviderView.formContact[0].id).val('');
		ProviderView.formContact.trigger('reset');
		ProviderView.formContactCommercial.trigger('reset');
		ProviderView.formContactOtherphone.trigger('reset');
		ProviderView.modalContact.modal('show');
		ProviderView.formContactCommercial.hide();
		ProviderView.formContactOtherphone.hide();
	},
	newDataTablesRowContact: function(contact)
	{
		var index = contact.index;
		var detailButton = '<button type="button" class="btn btn-primary" data-index="' +index+ '" onclick="ProviderView.openContact(this)">Detalhes</button>';
		var removeButton = '<button type="button" class="btn btn-danger" data-index="' +index+ '" onclick="ProviderView.removeContact(this)">Excluir</button>';

		return [
			contact.name,
			contact.position,
			(contact.commercial === null ? '-' : contact.commercial.ddd+ ' ' +contact.commercial.number),
			'<div class="btn-group">' +detailButton+removeButton+ '</div>',
		];
	},
	setContact: function(contact)
	{
		if (ProviderView.providerContact !== undefined && ProviderView.providerContact.id === contact.id)
		{
			contact.index = ProviderView.providerContact.index;
			contact.row = ProviderView.providerContact.row;
			contact.row.data(ProviderView.newDataTablesRowContact(contact));
			ProviderView.contacts[contact.index] = contact;
		}

		ProviderView.providerContact = contact;

		var form = ProviderView.formContact[0];
		var formCommercial = ProviderView.formContactCommercial[0];
		var formOtherphone = ProviderView.formContactOtherphone[0];

		$(form.id).val(contact.id);
		$(form.name).val(contact.name);
		$(form.email).val(contact.email);
		$(form.position).val(contact.position);
		$(formCommercial.id).val(contact.id);
		$(formOtherphone.id).val(contact.id);
		ProviderView.formContactCommercial.trigger('reset');
		ProviderView.formContactOtherphone.trigger('reset');

		if (contact.commercial !== null)
		{
			$(formCommercial['commercial[ddd]']).val(contact.commercial.ddd);
			$(formCommercial['commercial[type]']).val(contact.commercial.type);
			$(formCommercial['commercial[number]']).val(contact.commercial.number).trigger('input');
			$(formCommercial['commercial[idPhone]']).val(contact.commercial.id);
			$('#contact-commercial-group-add').hide();
			$('#contact-commercial-group-exist').show();
		}
		else
		{
			$('#contact-commercial-group-add').show();
			$('#contact-commercial-group-exist').hide();
		}

		if (contact.otherphone !== null)
		{
			$(formOtherphone['otherphone[ddd]']).val(contact.otherphone.ddd);
			$(formOtherphone['otherphone[type]']).val(contact.otherphone.type);
			$(formOtherphone['otherphone[number]']).val(contact.otherphone.number).trigger('input');
			$(formOtherphone['otherphone[idPhone]']).val(contact.otherphone.id);
			$('#contact-otherphone-group-add').hide();
			$('#contact-otherphone-group-exist').show();
		}
		else
		{
			$('#contact-otherphone-group-add').show();
			$('#contact-otherphone-group-exist').hide();
		}

		ProviderView.formContactCommercial.show();
		ProviderView.formContactOtherphone.show();
		$('#modal-contact-add').hide();
		$('#modal-contact-save').show();
	},
	addContact: function()
	{
		ws.providerContact_add(ProviderView.idProvider, ProviderView.formContact, ProviderView.onContactAdded);
	},
	onContactAdded: function(contact)
	{
		ProviderView.addRowContact(contact);
		ProviderView.setContact(contact);
	},
	saveContact: function()
	{
		ws.providerContact_set(ProviderView.idProvider, ProviderView.formContact, ProviderView.setContact);
	},
	addContactPhoneCommercial: function()
	{
		ws.providerContact_setPhones(ProviderView.idProvider, ProviderView.formContactCommercial, ProviderView.setContact);
	},
	saveContactPhoneCommercial: function()
	{
		ws.providerContact_setPhones(ProviderView.idProvider, ProviderView.formContactCommercial, ProviderView.setContact);
	},
	removeContactPhoneCommercial: function()
	{
		ws.providerContact_removeCommercial(ProviderView.idProvider, ProviderView.formContactCommercial, ProviderView.setContact);
	},
	addContactPhoneOtherphone: function()
	{
		ws.providerContact_setPhones(ProviderView.idProvider, ProviderView.formContactOtherphone, ProviderView.setContact);
	},
	saveContactPhoneOtherphone: function()
	{
		ws.providerContact_setPhones(ProviderView.idProvider, ProviderView.formContactOtherphone, ProviderView.setContact);
	},
	removeContactPhoneOtherphone: function()
	{
		ws.providerContact_removeOtherphone(ProviderView.idProvider, ProviderView.formContactOtherphone, ProviderView.setContact);
	},
	onCommercialExists: function(exist)
	{
		if (exist)
			$.when($('#commercial-group-add').fadeOut('slow')).done(function() { $('#commercial-group-exist').fadeIn('slow'); });
		else
			$.when($('#commercial-group-exist').fadeOut('slow')).done(function() { $('#commercial-group-add').fadeIn('slow'); });
	},
	onOtherphoneExists: function(exist)
	{
		if (exist)
			$.when($('#otherphone-group-add').fadeOut('slow')).done(function() { $('#otherphone-group-exist').fadeIn('slow'); });
		else
			$.when($('#otherphone-group-exist').fadeOut('slow')).done(function() { $('#otherphone-group-add').fadeIn('slow'); });
	},
	openContact: function(button)
	{
		var contact = ProviderView.contacts[button.dataset.index];
		ProviderView.setContact(contact);
		ProviderView.modalContact.modal('show');
	},
	removeContact: function(button)
	{
		var tr = $(button).parents('tr');
		var contact = ProviderView.contacts[button.dataset.index];
		var formData = new FormData();
		formData.append('id', contact.id);

		ws.providerContact_removeContact(ProviderView.idProvider, formData, tr, function() { ProviderView.onContactRemoved(tr); });
	},
	onContactRemoved: function(tr)
	{
		ProviderView.dataTableContacts.row(tr).remove().draw();
	},
}
