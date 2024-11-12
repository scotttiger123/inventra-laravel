$(function () {
    $('#warehouse-listings').DataTable({
        'paging': true,
        'lengthChange': true,
        'searching': true,
        'ordering': true,
        'info': true,
        'autoWidth': true
    });
});

$('#viewWarehouseModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget); // Button that triggered the modal
    var warehouseId = button.data('id');
    var name = button.data('name');
    var location = button.data('location');
    var managerName = button.data('manager-name');
    var contactNumber = button.data('contact-number');
   

    var modal = $(this);
    modal.find('#warehouse-name').text(name);
    modal.find('#warehouse-location').text(location);
    modal.find('#warehouse-manager-name').text(managerName);
    modal.find('#warehouse-contact-number').text(contactNumber);
   
});

function confirmDeleteWarehouse(warehouseId) {
    if (confirm('Are you sure you want to delete this warehouse?')) {
        var form = document.getElementById(`deleteForm-${warehouseId}`);
        var formData = new FormData(form);
        fetch(form.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest',
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Warehouse deleted successfully!');
                document.getElementById(`warehouseRow-${warehouseId}`).remove();
            } else {
                alert('Failed to delete warehouse.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while deleting the warehouse.');
        });
    }
}
