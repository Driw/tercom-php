
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
		var btnPattern = '<button type="button" class="btn btn-sm btn-{0}" onclick="ServiceList.{1}(this, {2})">{3}</button>';
		var btnView = btnPattern.format('primary', 'onButtonView', index, 'Ver');
		var btnPrices = btnPattern.format('primary', 'onButtonPrices', index, 'Preços');
		var btnActive = btnPattern.format('primary', 'onButtonActive', index, 'Ativar');
		var btnDesactive = btnPattern.format('secondary', 'onButtonDesactive', index, 'Desativar');

		return [
			service.id,
			service.name,
			service.description,
			service.tags.elements.join(', '),
			'<div class="btn-group">{0}{1}{2}</div>'.format(btnView, btnPrices, service.inactive ? btnActive : btnDesactive),
		];
	},
	onButtonView: function(button, index)
	{
		var service = ServiceList.services[index];

		if (service !== undefined)
			Util.redirect('service/view/{0}'.format(service.id), true);
	},
	onButtonPrices: function(button, index)
	{
		var service = ServiceList.services[index];

		if (service !== undefined)
			Util.redirect('service/viewPrices/{0}'.format(service.id), true);
	},
	onButtonActive: function(button, index)
	{
		var tr = $(button).parents('tr');
		var service = ServiceList.services[index];
		ws.service_setInactive(service.id, false, tr, function(service) { ServiceList.onServiceInactive(tr, index, service); });
	},
	onButtonDesactive: function(button, index)
	{
		var tr = $(button).parents('tr');
		var service = ServiceList.services[index];
		ws.service_setInactive(service.id, true, tr, function(service) { ServiceList.onServiceInactive(tr, index, service); });
	},
	onServiceInactive: function(tr, index, service)
	{
		var serviceRowData = ServiceList.newServiceRowData(index, service);
		ServiceList.datatables.row(tr).data(serviceRowData);
	},
}
