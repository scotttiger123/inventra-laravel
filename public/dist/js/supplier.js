$(function () {
    $('#supplier-listings').DataTable({
        'paging': true,
        'lengthChange': false,
        'searching': false,
        'ordering': true,
        'info': true,
        'autoWidth': false
    });
});

$('#viewSupplierModal').on('show.bs.modal', function (event) { 
    var button = $(event.relatedTarget); // Button that triggered the modal
    var modal = $(this);
    
    modal.find('#supplier-name').text(button.data('name') || 'N/A');
    modal.find('#supplier-email').text(button.data('email') || 'N/A');
    modal.find('#supplier-phone').text(button.data('phone') || 'N/A');
    modal.find('#supplier-address').text(button.data('address') || 'N/A');
    modal.find('#supplier-city').text(button.data('city') || 'N/A');
    modal.find('#supplier-po-box').text(button.data('po-box') || 'N/A');
    modal.find('#supplier-initial-balance').text(button.data('initial-balance') || 'N/A');
    modal.find('#supplier-tax-number').text(button.data('tax-number') || 'N/A');
    modal.find('#supplier-discount-type').text(button.data('discount-type') || 'N/A');
    modal.find('#supplier-discount-value').text(button.data('discount-value') || 'N/A');
});


function confirmDeleteSupplier(supplierId) {
    if (confirm('Are you sure you want to delete this supplier?')) {
        var form = document.getElementById(`deleteForm-${supplierId}`);
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
                alert('Supplier deleted successfully!');
                document.getElementById(`supplierRow-${supplierId}`).remove();
            } else {
                alert('Failed to delete supplier.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while deleting the supplier.');
        });
    }
}
