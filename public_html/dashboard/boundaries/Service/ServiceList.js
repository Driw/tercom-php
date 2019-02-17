
$(document).ready(function()
{
	ServiceList.init();
});

var ServiceList = ServiceList ||
{
	init: function()
	{
		ServiceList.table = $('#table-services');
		ServiceList.tbody = ServiceList.table.find('tbody');
		ServiceList.datatables = newDataTables(ServiceList.table);
		ServiceList.loadServices();
	},
	loadServices: function()
	{
		ws.service_getAll(this.tbody, ServiceList.onServicesLoaded);
	},
	onServicesLoaded: function(services)
	{
		ServiceList.services = services.elements;
		ServiceList.services.forEach((service, index) =>
		{
			var serviceRowData = ServiceList.newServiceRowData(index, service);
			ServiceList.datatables.row.add(serviceRowData).draw();
		});
	},
	newServiceRowData: function(index, service)
	{
		var btnTemplate = '<button type="button" class="btn btn-sm {0}" onclick="ServiceList.{1}(this, {2})" title="{3}">{4}</button>';
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
		var service = ServiceList.services[index];
		Util.redirect('service/view/{0}'.format(service.id));
	},
	onButtonPrices: function(button, index)
	{
		var service = ServiceList.services[index];
		Util.redirect('service/viewPrices/{0}'.format(service.id));
	},
	onButtonEnable: function(button, index)
	{
		var tr = $(button).parents('tr');
		var service = ServiceList.services[index];
		ws.service_setInactive(service.id, false, tr, function(service, message)
		{
			ServiceList.onChangeEnable(tr, index, service);
			Util.showSuccess(message);
		});
	},
	onButtonDisable: function(button, index)
	{
		var tr = $(button).parents('tr');
		var service = ServiceList.services[index];
		ws.service_setInactive(service.id, true, tr, function(service, message)
		{
			ServiceList.onChangeEnable(tr, index, service);
			Util.showSuccess(message);
		});
	},
	onChangeEnable: function(tr, index, service)
	{
		var serviceRowData = ServiceList.newServiceRowData(index, service);
		ServiceList.datatables.row(tr).data(serviceRowData);
	},
}
