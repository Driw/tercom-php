
$(document).ready(function()
{
	TercomEmployeeList.init();
});

var TercomEmployeeList = TercomEmployeeList ||
{
	init: function()
	{
		TercomEmployeeList.idTercomProfile = $('#idTercomProfile').val();
		TercomEmployeeList.table = $('#table-tercom-employees');
		TercomEmployeeList.tbody = this.table.find('tbody');
		TercomEmployeeList.datatables = newDataTables(this.table);
		TercomEmployeeList.loadTercomEmployees();
	},
	loadTercomEmployees: function()
	{
		if (TercomEmployeeList.idTercomProfile == 0)
			ws.tercomEmployee_getAll(TercomEmployeeList.tbody, TercomEmployeeList.onTercomEmployeesLoaded);
		else
			ws.tercomEmployee_getByProfile(TercomEmployeeList.idTercomProfile, TercomEmployeeList.tbody, TercomEmployeeList.onTercomEmployeesLoaded);
	},
	onTercomEmployeesLoaded: function(tercomEmployees)
	{
		TercomEmployeeList.tercomEmployees = tercomEmployees.elements;
		TercomEmployeeList.tercomEmployees.forEach((tercomEmployee, index) =>
		{
			var tercomEmployeeRowData = TercomEmployeeList.newTercomEmployeeRowData(index, tercomEmployee);
			TercomEmployeeList.datatables.row.add(tercomEmployeeRowData).draw();
		});
	},
	newTercomEmployeeRowData: function(index, tercomEmployee)
	{
		var btnTemplate = '<button type="button" class="btn btn-sm {0}" onclick="TercomEmployeeList.{1}(this, {2})" title="{3}">{4}</button>';
		var btnView = btnTemplate.format(BTN_CLASS_VIEW, 'onButtonClickView', index, 'Ver Dados do Funcionário', ICON_VIEW);
		var btnEnable = btnTemplate.format(BTN_CLASS_ENABLE, 'onButtonClickEnable', index, 'Habilitar Funcionário', ICON_ENABLE);
		var btnDisable = btnTemplate.format(BTN_CLASS_DISABLE, 'onButtonClickDisable', index, 'Desabilitar Funcionário', ICON_DISABLE);

		return [
			tercomEmployee.id,
			tercomEmployee.cpf,
			tercomEmployee.name,
			tercomEmployee.email,
			'<div class="btn-group">{0}{1}</div>'.format(btnView, tercomEmployee.enabled ? btnDisable : btnEnable),
		];
	},
	onButtonClickView: function(button, index)
	{
		var tercomEmployee = TercomEmployeeList.tercomEmployees[index];
		Util.redirect('tercomEmployee/view/{0}'.format(tercomEmployee.id));
	},
	onButtonClickEnable: function(button, index)
	{
		var tercomEmployee = TercomEmployeeList.tercomEmployees[index];
		var tr = $(button).parents('tr');

		ws.tercomEmployee_enable(tercomEmployee.id, true, tr, function(tercomEmployee, message)
		{
			TercomEmployeeList.onEnabled(tercomEmployee, index, message);
		});
	},
	onButtonClickDisable: function(button, index)
	{
		var tercomEmployee = TercomEmployeeList.tercomEmployees[index];
		var tr = $(button).parents('tr');

		ws.tercomEmployee_enable(tercomEmployee.id, false, tr, function(tercomEmployee, message)
		{
			TercomEmployeeList.onEnabled(tercomEmployee, index, message);
		});
	},
	onEnabled: function(tercomEmployee, index, message)
	{
		TercomEmployeeList.tercomEmployees[index] = tercomEmployee;
		var tercomEmployeeRowData = TercomEmployeeList.newTercomEmployeeRowData(index, tercomEmployee);
		TercomEmployeeList.datatables.row(index).data(tercomEmployeeRowData);
		Util.showSuccess(message);
	},
}
