
$(document).ready(function()
{
    AddressList.init();
});

var AddressList = AddressList ||
{
    init: function()
    {
        AddressList.addresses = [];
        AddressList.relationship = $('#relationship').val();
        AddressList.idRelationship = $('#idRelationship').val();
        AddressList.table = $('#table-addresses');
        AddressList.tbody = AddressList.table.find('tbody');
        AddressList.dataTable = newDataTables(AddressList.table);
        AddressList.loadAddresses();
    },
    loadAddresses: function()
    {
        ws.address_getAll(AddressList.relationship, AddressList.idRelationship, AddressList.tbody, AddressList.onAddressesLoaded);
    },
    onAddressesLoaded: function(addresses)
    {
        AddressList.addresses = addresses.elements;
        AddressList.addresses.forEach((address, index) =>
        {
            let addressRowData = AddressList.newAddressRowData(index, address);
            let row = AddressList.dataTable.row.add(addressRowData).draw();
        });
    },
    newAddressRowData: function(index, address)
    {
        let btnView = '<button type="button" class="btn btn-sm btn-primary" data-index="{0}" onclick="AddressList.onButtonView(this)">Ver</button>'.format(index);
        var btnRemove = '<button type="button" class="btn btn-sm btn-danger" data-index="{0}" onclick="AddressList.onButtonRemove(this)">Excluir</button>'.format(index);

        return [
            address.id,
            address.cep,
            '{0} - {1}'.format(address.city, address.state),
            '{0}, {1} - {2}'.format(address.street, address.number, address.neighborhood),
            address.complement,
            '<div class="btn-group">{0}{1}</div>'.format(btnView, btnRemove),
        ];
    },
    onButtonView: function(button)
    {
        let index = button.dataset.index;
        let address = AddressList.addresses[index];

        if (address !== undefined)
            Util.redirect('{0}/viewAddress/{1}/{2}'.format(AddressList.relationship, AddressList.idRelationship, address.id), true);
    },
    onButtonRemove: function(button)
	{
		var index = button.dataset.index;
		var address = AddressList.addresses[index];

		if (address !== undefined)
          Util.redirect('{0}/removeAddress/{1}/{2}'.format(AddressList.relationship, AddressList.idRelationship, address.id), true);
  	}
}
