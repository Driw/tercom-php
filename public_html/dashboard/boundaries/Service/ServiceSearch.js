
$(document).ready(function()
{
	ServiceSearch.init();
});

var ServiceSearch = ServiceSearch ||
{
	init: function()
	{
		ServiceSearch.services = [];
		ServiceSearch.form = $('#form-search');
		ServiceSearch.table = $('#table-services');
		ServiceSearch.tbody = ServiceSearch.table.find('tbody');
		ServiceSearch.datatables = newDataTables(ServiceSearch.table);
		ServiceSearch.initFormtSettings();
	},
	initFormtSettings: function()
	{
		ServiceSearch.form.validate({
			'rules': {
				'filter': {
					'required': true,
				},
				'value': {
					'required': true,
				},
			},
			submitHandler: function(form) {
				ServiceSearch.onSearch($(form));
				return false;
			},
		});
	},
	onSearch: function()
	{
		var form = ServiceSearch.form[0];
		var value = $(form.value).val();
		var filter = $(form.filter).val();

		ws.service_search(filter, value, ServiceSearch.form, ServiceSearch.onServicesLoaded);
	},
	onServicesLoaded: function(services)
	{
		ServiceSearch.datatables.clear().draw();
		ServiceSearch.services = services.elements;
		ServiceSearch.services.forEach((service, index) =>
		{
			var serviceRowData = ServiceSearch.newServiceRowData(index, service);
			ServiceSearch.datatables.row.add(serviceRowData).draw();
		});
	},
	newServiceRowData: function(index, service)
	{
		var btnTemplate = '<button type="button" class="btn btn-sm {0}" onclick="ServiceSearch.{1}(this, {2})" title="{3}">{4}</button>';
		var btnView = btnTemplate.format(BTN_CLASS_VIEW, 'onButtonView', index, 'Ver', ICON_VIEW);
		var btnPrices = btnTemplate.format(BTN_CLASS_VIEW, 'onButtonPrices', index, 'Preços', ICON_PRICES);
		var btnEnable = btnTemplate.format(BTN_CLASS_ENABLE, 'onButtonEnable', index, 'Ativar', ICON_ENABLE);
		var btnDisable = btnTemplate.format(BTN_CLASS_DISABLE, 'onButtonDisable', index, 'Desativar', ICON_DISABLE);

		return [
			service.id,
			service.name,
			service.description,
			service.tags.elements.join(', '),
			'<div class="btn-group">{0}{1}{2}</div>'.format(btnView, btnPrices, service.inactive ? btnEnable : btnDisable),
		];
	},
	onButtonView: function(button, index)
	{
		var service = ServiceSearch.services[index];
		Util.redirect('service/view/{0}'.format(service.id));
	},
	onButtonPrices: function(button, index)
	{
		var service = ServiceSearch.services[index];
		Util.redirect('service/viewPrices/{0}'.format(service.id));
	},
	onButtonEnable: function(button, index)
	{
		var tr = $(button).parents('tr');
		var service = ServiceSearch.services[index];
		ws.service_setInactive(service.id, false, tr, function(service, message)
		{
			ServiceSearch.onChangeEnable(tr, index, service);
			Util.showSuccess(message);
		});
	},
	onButtonDisable: function(button, index)
	{
		var tr = $(button).parents('tr');
		var service = ServiceSearch.services[index];
		ws.service_setInactive(service.id, true, tr, function(service, message)
		{
			ServiceSearch.onChangeEnable(tr, index, service);
			Util.showSuccess(message);
		});
	},
	onChangeEnable: function(tr, index, service)
	{
		var serviceRowData = ServiceSearch.newServiceRowData(index, service);
		ServiceSearch.datatables.row(tr).data(serviceRowData);
	},
}
