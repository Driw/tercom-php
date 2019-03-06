
$(document).ready(function()
{
	OrderRequestView.init();
});

var OrderRequestView = OrderRequestView ||
{
	init: function()
	{
		OrderRequestView.loaded = false;
		OrderRequestView.idOrderRequest = $('#idOrderRequest').val();
		OrderRequestView.form = $('#form-order-request-view');
		OrderRequestView.buttonOrderRequestItemProductAdd = $('#order-request-btn-item-product-add');
		OrderRequestView.buttonOrderRequestItemServiceAdd = $('#order-request-btn-item-service-add');
		OrderRequestView.buttonOrderRequestUpdate = $('#order-request-btn-update');
		OrderRequestView.buttonOrderRequestQueue = $('#order-request-btn-queue');
		OrderRequestView.buttonOrderRequestNext = $('#order-request-btn-next');

		OrderRequestView.tableItensProduct = $('#table-itens-product');
		OrderRequestView.tableItensService = $('#table-itens-service');
		OrderRequestView.datatablesItensProduct = newDataTables(OrderRequestView.tableItensProduct);
		OrderRequestView.datatablesItensService = newDataTables(OrderRequestView.tableItensService);

		OrderRequestView.modalItemProduct = $('#modal-item-product');
		OrderRequestView.modalItemService = $('#modal-item-service');
		OrderRequestView.modalItemProductLabel = $('#modal-item-product-label');
		OrderRequestView.modalItemServiceLabel = $('#modal-item-service-label');
		OrderRequestView.idOrderItemProductInput = $('#idOrderItemProduct');
		OrderRequestView.idOrderItemServiceInput = $('#idOrderItemService');
		OrderRequestView.formItemProduct = $('#form-order-item-product');
		OrderRequestView.formItemService = $('#form-order-item-service');
		OrderRequestView.buttonItemProductAdd = $('#btn-item-product-add');
		OrderRequestView.buttonItemProductSet = $('#btn-item-product-set');
		OrderRequestView.buttonItemServiceAdd = $('#btn-item-service-add');
		OrderRequestView.buttonItemServiceSet = $('#btn-item-service-set');
		OrderRequestView.selectProduct = $(OrderRequestView.formItemProduct[0].idProduct);
		OrderRequestView.selectProductProvider = $(OrderRequestView.formItemProduct[0].idProvider);
		OrderRequestView.selectProductManufacturer = $(OrderRequestView.formItemProduct[0].idManufacturer);
		OrderRequestView.selectService = $(OrderRequestView.formItemService[0].idService);
		OrderRequestView.selectServiceProvider = $(OrderRequestView.formItemService[0].idProvider);
		OrderRequestView.selectProduct.change(OrderRequestView.onProductChange);
		OrderRequestView.selectService.change(OrderRequestView.onServiceChange);
		OrderRequestView.orderItemProduct = null;
		OrderRequestView.orderItemService = null;

		OrderRequestView.initFormSettings();
		OrderRequestView.initFormProductSettings();
		OrderRequestView.initFormServiceSettings();
		OrderRequestView.loadOrderRequest();
		OrderRequestView.loadOrderItensProduct();
		OrderRequestView.loadOrderItensService();
		OrderRequestView.loadProducts();
		OrderRequestView.loadServices();
	},
	initFormSettings: function()
	{
		OrderRequestView.form.validate({
			'rules': {
				'expiration': {
					'required': true,
				},
				'budget': {
					'required': true,
				},
			},
			submitHandler: function(form) {
				try {
					OrderRequestView.submit($(form));
				} catch (e) {
					Util.showError(e.stack);
				}
				return false;
			}
		});
	},
	initFormProductSettings: function()
	{
		OrderRequestView.formItemProduct.validate({
			'rules': {
				'idProduct': {
					'required': true,
				},
				'idProvider': {
					'required': false,
				},
				'idManufacturer': {
					'required': false,
				},
			},
			submitHandler: function(form) {
				return false;
			}
		});
	},
	initFormServiceSettings: function()
	{
		OrderRequestView.formItemService.validate({
			'rules': {
				'idService': {
					'required': true,
				},
				'idProvider': {
					'required': false,
				},
			},
			submitHandler: function(form) {
				return false;
			}
		});
	},
	loadOrderRequest: function()
	{
		ws.orderRequest_get(OrderRequestView.idOrderRequest, OrderRequestView.form, OrderRequestView.onOrderRequestLoaded);
	},
	loadOrderItensProduct: function()
	{
		ws.orderItemProduct_getAll(OrderRequestView.idOrderRequest, OrderRequestView.tableItensProduct, OrderRequestView.onOrderItensProductLoaded);
	},
	loadOrderItensService: function()
	{
		ws.orderItemService_getAll(OrderRequestView.idOrderRequest, OrderRequestView.tableItensService, OrderRequestView.onOrderItensServiceLoaded);
	},
	loadProducts: function()
	{
		ws.product_getAll(OrderRequestView.selectProduct, OrderRequestView.onProductsLoaded);
	},
	loadServices: function()
	{
		ws.service_getAll(OrderRequestView.selectService, OrderRequestView.onServicesLoaded);
	},
	onProductChange: function()
	{
		var idProduct = OrderRequestView.selectProduct.val();
		ws.provider_getByProduct(idProduct, OrderRequestView.selectProductProvider, OrderRequestView.onProductProvidersLoaded);
		ws.manufacturer_getByProduct(idProduct, OrderRequestView.selectProductManufacturer, OrderRequestView.onProductManufacturersLoaded);
	},
	onServiceChange: function()
	{
		var idService = OrderRequestView.selectService.val();
		ws.provider_getByService(idService, OrderRequestView.selectServiceProvider, OrderRequestView.onServiceProvidersLoaded);
	},
	onOrderRequestLoaded: function(orderRequest, message)
	{
		var form = OrderRequestView.form[0];
		$(form.statusDescription).val(orderRequest.statusDescription);
		$(form.statusMessage).val(orderRequest.statusMessage);
		$(form.expiration).val(orderRequest.expiration.date);
		$(form.budget).val(Util.formatMoney(orderRequest.budget)).trigger('input');

		OrderRequestView.buttonOrderRequestItemProductAdd.prop('disabled', orderRequest.status !== ORS_NONE);
		OrderRequestView.buttonOrderRequestItemServiceAdd.prop('disabled', orderRequest.status !== ORS_NONE);
		OrderRequestView.buttonOrderRequestUpdate.prop('disabled', orderRequest.status !== ORS_NONE);

		switch (orderRequest.status)
		{
			case ORS_NONE:
				OrderRequestView.buttonOrderRequestQueue.prop('disabled', false);
				OrderRequestView.buttonOrderRequestNext.prop('disabled', true);
				break;

			case ORS_QUEUED:
			case ORS_CANCEL_BY_CUSTOMER:
			case ORS_CANCEL_BY_TERCOM:
			case ORS_QUOTING:
				OrderRequestView.buttonOrderRequestQueue.prop('disabled', true);
				OrderRequestView.buttonOrderRequestNext.prop('disabled', true);
				break;

			case ORS_QUOTED:
				OrderRequestView.buttonOrderRequestQueue.prop('disabled', true);
				OrderRequestView.buttonOrderRequestNext.prop('disabled', false);
				break;

			case ORS_DONE:
				OrderRequestView.buttonOrderRequestQueue.prop('disabled', true);
				OrderRequestView.buttonOrderRequestNext.prop('disabled', true);
				break;
		}

		if (OrderRequestView.loaded)
			Util.showSuccess(message);
		else
			Util.showInfo(message);
	},
	onOrderItensProductLoaded: function(orderItensProduct)
	{
		OrderRequestView.datatablesItensProduct.clear();
		OrderRequestView.orderItensProduct = orderItensProduct.elements;
		OrderRequestView.orderItensProduct.forEach((orderItemProduct, index) =>
		{
			var orderItemProductRowData = OrderRequestView.newOrderItemProductRowData(index, orderItemProduct);
			OrderRequestView.datatablesItensProduct.row.add(orderItemProductRowData).draw();
		});
	},
	newOrderItemProductRowData: function(index, orderItemProduct)
	{
		var btnTemplate = '<button class="btn btn-sm {0}" onclick="OrderRequestView.{1}({2}, this)" title="{3}">{4}</button>';
		var btnUpdate = btnTemplate.format(BTN_CLASS_VIEW, 'onButtonItemProductUpdate', index, 'Alterar Item de Produto', ICON_EDIT);
		var btnRemove = btnTemplate.format(BTN_CLASS_REMOVE, 'onButtonItemProductRemove', index, 'Excluir Item de Produto', ICON_REMOVE);

		return [
			orderItemProduct.product.id,
			orderItemProduct.product.name,
			orderItemProduct.provider === null ? '-' : orderItemProduct.provider.fantasyName,
			orderItemProduct.manufacturer === null ? '-' : orderItemProduct.manufacturer.fantasyName,
			Util.showChecked(orderItemProduct.betterPrice),
			'<div class="btn-group">{0}{1}</div>'.format(btnUpdate, btnRemove),
		];
	},
	onOrderItensServiceLoaded: function(orderItensService)
	{
		OrderRequestView.datatablesItensService.clear();
		OrderRequestView.orderItensService = orderItensService.elements;
		OrderRequestView.orderItensService.forEach((orderItemService, index) =>
		{
			var orderItemServiceRowData = OrderRequestView.newOrderItemServiceRowData(index, orderItemService);
			OrderRequestView.datatablesItensService.row.add(orderItemServiceRowData).draw();
		});
	},
	newOrderItemServiceRowData: function(index, orderItemService)
	{
		var btnTemplate = '<button class="btn btn-sm {0}" onclick="OrderRequestView.{1}({2}, this)" title="{3}">{4}</button>';
		var btnUpdate = btnTemplate.format(BTN_CLASS_VIEW, 'onButtonItemServiceUpdate', index, 'Alterar Item de Serviço', ICON_EDIT);
		var btnRemove = btnTemplate.format(BTN_CLASS_REMOVE, 'onButtonItemServiceRemove', index, 'Excluir Item de Serviço', ICON_REMOVE);

		return [
			orderItemService.service.id,
			orderItemService.service.name,
			orderItemService.provider === null ? '-' : orderItemService.provider.fantasyName,
			Util.showChecked(orderItemService.betterPrice),
			'<div class="btn-group">{0}{1}</div>'.format(btnUpdate, btnRemove),
		];
	},
	onProductsLoaded: function(products)
	{
		OrderRequestView.selectProduct.selectpicker();
		OrderRequestView.selectProduct.append(Util.createElementOption('Selecione um produto', ''));
		{
			OrderRequestView.products = products.elements;
			OrderRequestView.products.forEach((product) =>
			{
				var option = Util.createElementOption('{1} ({0})'.format(product.id, product.name), product.id);
				OrderRequestView.selectProduct.append(option);
			});
		}
		OrderRequestView.selectProduct.selectpicker('refresh');
	},
	onServicesLoaded: function(services)
	{
		OrderRequestView.selectService.selectpicker();
		OrderRequestView.selectService.append(Util.createElementOption('Selecione um serviço', ''));
		{
			OrderRequestView.services = services.elements;
			OrderRequestView.services.forEach((service) =>
			{
				var option = Util.createElementOption('[{0}] {1}'.format(service.id, service.name), service.id);
				OrderRequestView.selectService.append(option);
			});
		}
		OrderRequestView.selectService.selectpicker('refresh');
	},
	onProductProvidersLoaded: function(providers)
	{
		OrderRequestView.selectProductProvider.empty();
		OrderRequestView.selectProductProvider.append(Util.createElementOption('Selecione um fornecedor', 0));
		{
			OrderRequestView.productProviders = providers.elements;
			OrderRequestView.productProviders.forEach((provider) =>
			{
				var selected = OrderRequestView.getItemProductProviderId() === provider.id;
				var option = Util.createElementOption(provider.fantasyName, provider.id, selected);
				OrderRequestView.selectProductProvider.append(option);
			});
		}
		OrderRequestView.selectProductProvider.selectpicker('refresh');
	},
	onProductManufacturersLoaded: function(manufacturers)
	{
		OrderRequestView.selectProductManufacturer.empty();
		OrderRequestView.selectProductManufacturer.append(Util.createElementOption('Selecione um fabricante', 0));
		{
			OrderRequestView.productManufacturers = manufacturers.elements;
			OrderRequestView.productManufacturers.forEach((manufacturer) =>
			{
				var selected = OrderRequestView.getItemProductManufacturerId() === manufacturer.id;
				var option = Util.createElementOption(manufacturer.fantasyName, manufacturer.id, selected);
				OrderRequestView.selectProductManufacturer.append(option);
			});
		}
		OrderRequestView.selectProductManufacturer.selectpicker('refresh');
	},
	getItemProductProviderId: function()
	{
		return OrderRequestView.orderItemProduct !== null && OrderRequestView.orderItemProduct.provider !== null ? OrderRequestView.orderItemProduct.provider.id : 0;
	},
	getItemProductManufacturerId: function()
	{
		return OrderRequestView.orderItemProduct !== null && OrderRequestView.orderItemProduct.manufacturer !== null ? OrderRequestView.orderItemProduct.manufacturer.id : 0;
	},
	onServiceProvidersLoaded: function(providers)
	{
		OrderRequestView.selectServiceProvider.empty();
		OrderRequestView.selectServiceProvider.append(Util.createElementOption('Selecione um fornecedor', 0));
		{
			OrderRequestView.serviceProviders = providers.elements;
			OrderRequestView.serviceProviders.forEach((provider) =>
			{
				var selected = OrderRequestView.getItemServiceProviderId() === provider.id;
				var option = Util.createElementOption(provider.fantasyName, provider.id, selected);
				OrderRequestView.selectServiceProvider.append(option);
			});
		}
		OrderRequestView.selectServiceProvider.selectpicker('refresh');
	},
	getItemServiceProviderId: function()
	{
		return OrderRequestView.orderItemService !== null && OrderRequestView.orderItemService.provider !== null ? OrderRequestView.orderItemService.provider.id : 0;
	},
	submit: function(form)
	{
		ws.orderRequest_set(OrderRequestView.idOrderRequest, form, OrderRequestView.onSubmited);
	},
	onSubmited: function(orderRequest, message)
	{
		OrderRequestView.onOrderRequestLoaded(orderRequest, message);
	},
	onButtonItemProductUpdate: function(index, button)
	{
		var orderItemProduct = OrderRequestView.orderItensProduct[index];
		OrderRequestView.openItemProductModal(orderItemProduct, index, button);
	},
	onButtonItemProductRemove: function(index, button)
	{
		var orderItemProduct = OrderRequestView.orderItensProduct[index];
		var tr = $(button).parents('tr');
		ws.orderItemProduct_remove(OrderRequestView.idOrderRequest, orderItemProduct.id, tr, function(orderItemProduct, message)
		{
			OrderRequestView.datatablesItensProduct.row(tr).remove().draw();
			Util.showSuccess(message);
		});
	},
	onButtonItemServiceUpdate: function(index, button)
	{
		var orderItemService = OrderRequestView.orderItensService[index];
		OrderRequestView.openItemServiceModal(orderItemService, index, button);
	},
	onButtonItemServiceRemove: function(index, button)
	{
		var orderItemService = OrderRequestView.orderItensService[index];
		var tr = $(button).parents('tr');
		ws.orderItemService_remove(OrderRequestView.idOrderRequest, orderItemService.id, tr, function(orderItemService, message)
		{
			OrderRequestView.datatablesItensService.row(tr).remove().draw();
			Util.showSuccess(message);
		});
	},
	openItemProductModal: function(orderItemProduct, index, button)
	{
		var exist = orderItemProduct !== undefined;
		var form = OrderRequestView.formItemProduct[0];
		OrderRequestView.modalItemProduct.modal('show');
		OrderRequestView.modalItemProductLabel.html(exist ? 'Atualizar Item de Produto' : 'Adicionar Item de Produto');
		OrderRequestView.buttonItemProductAdd.prop('disabled', exist);
		OrderRequestView.buttonItemProductSet.prop('disabled', !exist);

		if (exist)
		{
			$(form.betterPrice).attr('checked', orderItemProduct.betterPrice);
			OrderRequestView.selectProduct.prop('disabled', true);
			OrderRequestView.selectProduct.val(orderItemProduct.product.id);
			OrderRequestView.selectProduct.selectpicker('refresh');
			OrderRequestView.onProductChange();
			OrderRequestView.orderItemProduct = orderItemProduct;
			OrderRequestView.buttonItemProductAdd.hide('fast');
			OrderRequestView.buttonItemProductSet.show('fast');
			OrderRequestView.orderItemProductIndex = index;
			OrderRequestView.orderItemProductTr = $(button).parents('tr');
			OrderRequestView.idOrderItemProductInput.val(orderItemProduct.id);
		}

		else
		{
			OrderRequestView.buttonItemProductAdd.show('fast');
			OrderRequestView.buttonItemProductSet.hide('fast');
			OrderRequestView.orderItemProduct = null;
			OrderRequestView.orderItemProductIndex = null;
			OrderRequestView.orderItemProductTr = null;
			OrderRequestView.idOrderItemProductInput.val('');
			OrderRequestView.selectProduct.prop('disabled', false);
			OrderRequestView.selectProduct.val('');
			OrderRequestView.selectProduct.selectpicker('refresh');
			OrderRequestView.selectProductProvider.val(0);
			OrderRequestView.selectProductProvider.selectpicker('refresh');
			OrderRequestView.selectProductManufacturer.val(0);
			OrderRequestView.selectProductManufacturer.selectpicker('refresh');
			$(form.betterPrice).attr('checked', false);
		}
	},
	onAddItemProduct: function()
	{
		if (OrderRequestView.formItemProduct.valid())
			ws.orderItemProduct_add(OrderRequestView.idOrderRequest, OrderRequestView.formItemProduct, OrderRequestView.onAddProductSubmited);
	},
	onAddProductSubmited: function(orderItemProduct, message)
	{
		var index = OrderRequestView.orderItensProduct.push(orderItemProduct) - 1;
		var orderItemProductRowData = OrderRequestView.newOrderItemProductRowData(index, orderItemProduct);
		OrderRequestView.datatablesItensProduct.row.add(orderItemProductRowData).draw();
		OrderRequestView.modalItemProduct.modal('hide');
		Util.showSuccess(message);
	},
	onSetItemProduct: function()
	{
		if (!OrderRequestView.formItemProduct.valid())
			return;

		var idOrderItemProduct = OrderRequestView.idOrderItemProductInput.val();
		ws.orderItemProduct_set(OrderRequestView.idOrderRequest, idOrderItemProduct, OrderRequestView.formItemProduct, OrderRequestView.onSetProductSubmited);
	},
	onSetProductSubmited: function(orderItemProduct, message)
	{
		var index = OrderRequestView.orderItemProductIndex;
		var tr = OrderRequestView.orderItemProductTr;
		var orderItemProductRowData = OrderRequestView.newOrderItemProductRowData(index, orderItemProduct);
		OrderRequestView.datatablesItensProduct.row(tr).data(orderItemProductRowData).draw();
		OrderRequestView.orderItensProduct[index] = orderItemProduct;
		OrderRequestView.modalItemProduct.modal('hide');
		Util.showSuccess(message);
	},
	openItemServiceModal: function(orderItemService, index, button)
	{
		var exist = orderItemService !== undefined;
		var form = OrderRequestView.formItemService[0];
		OrderRequestView.modalItemService.modal('show');
		OrderRequestView.modalItemServiceLabel.html(exist ? 'Atualizar Item de Serviço' : 'Adicionar Item de Serviço');
		OrderRequestView.buttonItemServiceAdd.prop('disabled', exist);
		OrderRequestView.buttonItemServiceSet.prop('disabled', !exist);

		if (exist)
		{
			$(form.betterPrice).attr('checked', orderItemService.betterPrice);
			OrderRequestView.selectService.prop('disabled', true);
			OrderRequestView.selectService.val(orderItemService.service.id);
			OrderRequestView.selectService.selectpicker('refresh');
			OrderRequestView.onServiceChange();
			OrderRequestView.orderItemService = orderItemService;
			OrderRequestView.buttonItemServiceAdd.hide('fast');
			OrderRequestView.buttonItemServiceSet.show('fast');
			OrderRequestView.orderItemServiceIndex = index;
			OrderRequestView.orderItemServiceTr = $(button).parents('tr');
			OrderRequestView.idOrderItemServiceInput.val(orderItemService.id);
		}

		else
		{
			OrderRequestView.buttonItemServiceAdd.show('fast');
			OrderRequestView.buttonItemServiceSet.hide('fast');
			OrderRequestView.orderItemService = null;
			OrderRequestView.orderItemServiceIndex = null;
			OrderRequestView.orderItemServiceTr = null;
			OrderRequestView.idOrderItemServiceInput.val('');
			OrderRequestView.selectService.prop('disabled', false);
			OrderRequestView.selectService.val('');
			OrderRequestView.selectService.selectpicker('refresh');
			OrderRequestView.selectServiceProvider.val(0);
			OrderRequestView.selectServiceProvider.selectpicker('refresh');
			$(form.betterPrice).attr('checked', false);
		}
	},
	onAddItemService: function()
	{
		if (OrderRequestView.formItemService.valid())
			ws.orderItemService_add(OrderRequestView.idOrderRequest, OrderRequestView.formItemService, OrderRequestView.onServiceSubmited);
	},
	onServiceSubmited: function(orderItemService, message)
	{
		var index = OrderRequestView.orderItensService.push(orderItemService) - 1;
		var orderItemServiceRowData = OrderRequestView.newOrderItemServiceRowData(index, orderItemService);
		OrderRequestView.datatablesItensService.row.add(orderItemServiceRowData).draw();
		OrderRequestView.modalItemService.modal('hide');
		Util.showSuccess(message);
	},
	onSetItemService: function()
	{
		if (!OrderRequestView.formItemService.valid())
			return;

		var idOrderItemService = OrderRequestView.idOrderItemServiceInput.val();
		ws.orderItemService_set(OrderRequestView.idOrderRequest, idOrderItemService, OrderRequestView.formItemService, OrderRequestView.onSetServiceSubmited);
	},
	onSetServiceSubmited: function(orderItemService, message)
	{
		var index = OrderRequestView.orderItemServiceIndex;
		var tr = OrderRequestView.orderItemServiceTr;
		var orderItemServiceRowData = OrderRequestView.newOrderItemServiceRowData(index, orderItemService);
		OrderRequestView.datatablesItensService.row(tr).data(orderItemServiceRowData).draw();
		OrderRequestView.orderItensService[index] = orderItemService;
		OrderRequestView.modalItemService.modal('hide');
		Util.showSuccess(message);
	},
	onButtonQueue: function()
	{
		var message =	'Ao concluir o pedido o mesmo será alocado à fila de espera para cotação e não poderá ser alterado até o fim da cotação. '+
						'Você tem certeza que deseja concluir o seu pedido?';
		Util.showConfirm('Confirmação - Concluir Pedido', message, OrderRequestView.onSetQueueConfirm);
	},
	onSetQueueConfirm: function(confirm)
	{
		if (!confirm)
			return true;

		ws.orderRequest_setQueued(OrderRequestView.idOrderRequest, $('#order-request-section'), OrderRequestView.onSetQueued);
		return false;
	},
	onSetQueued: function(orderRequest, message)
	{
		Util.closeConfirmModal();
		OrderRequestView.onOrderRequestLoaded(orderRequest, message);
	},
	onButtonNext: function()
	{

	},
}
