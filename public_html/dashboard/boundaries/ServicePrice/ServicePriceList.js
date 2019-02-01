
$(document).ready(function()
{
	ServicePriceList.init();
});

var ServicePriceList = ServicePriceList ||
{
	init: function()
	{
		ServicePriceList.table = $('#table-service-prices');
		ServicePriceList.idService = $('#idService').val();
		ServicePriceList.tbody = ServicePriceList.table.find('tbody');
		ServicePriceList.datatables = newDataTables(ServicePriceList.table);
		ServicePriceList.loadServicePrices();
	},
	loadServicePrices: function()
	{
		ws.servicePrice_getAll(ServicePriceList.idService, ServicePriceList.tbody, ServicePriceList.onServicePriceLoaded);
	},
	onServicePriceLoaded: function(servicePrices)
	{
		ServicePriceList.servicePrices = servicePrices.elements;
		ServicePriceList.servicePrices.forEach((servicePrice, index) =>
		{
			var servicePriceRowData = ServicePriceList.newServicePriceRowData(index, servicePrice);
			ServicePriceList.datatables.row.add(servicePriceRowData).draw();
		});
	},
	newServicePriceRowData: function(index, servicePrice)
	{
		var btnTemplate = '<button type="button" class="btn btn-sm btn-{0}" onclick="ServicePriceList.{1}({2})">{3}</button>';
		var btnView = btnTemplate.format('primary', 'onButtonView', index, 'Ver');
		var btnRemove = btnTemplate.format('danger', 'onButtonRemove', index, 'Excluir');

		return [
			servicePrice.id,
			servicePrice.name,
			Util.formatMoney(servicePrice.price),
			servicePrice.service.description,
			'<div class="btn-group">{0}{1}</div>'.format(btnView, btnRemove),
		];
	},
	onButtonView: function(index)
	{
		var servicePrice = ServicePriceList.servicePrices[index];

		if (servicePrice !== undefined)
			Util.redirect('service/viewPrice/{0}'.format(servicePrice.id), true);
	},
	onButtonRemove: function(index)
	{
		var servicePrice = ServicePriceList.servicePrices[index];

		if (servicePrice !== undefined)
			Util.redirect('service/removePrice/{0}'.format(servicePrice.id), true);
	},
	onButtonAdd: function()
	{
		Util.redirect('service/addPrice/{0}'.format(ServicePriceList.idService), true);
	},
}
