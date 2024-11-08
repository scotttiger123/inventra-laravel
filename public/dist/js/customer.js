$(function () {
    
    $('#customer-listings').DataTable({
        'paging': true,
        'lengthChange': false,
        'searching': false,
        'ordering': true,
        'info': true,
        'autoWidth': false
    });
});

$('#viewCustomerModal').on('show.bs.modal', function (event) { 
    var button = $(event.relatedTarget); // Button that triggered the modal
    var modal = $(this);
    
    modal.find('#customer-name').text(button.data('name') || 'N/A');
    modal.find('#customer-email').text(button.data('email') || 'N/A');
    modal.find('#customer-phone').text(button.data('phone') || 'N/A');
    modal.find('#customer-address').text(button.data('address') || 'N/A');
    modal.find('#customer-city').text(button.data('city') || 'N/A');
    modal.find('#customer-po-box').text(button.data('po-box') || 'N/A');
    modal.find('#customer-initial-balance').text(button.data('initial-balance') || 'N/A');
    modal.find('#customer-tax-number').text(button.data('tax-number') || 'N/A');
    modal.find('#customer-discount-type').text(button.data('discount-type') || 'N/A');
    modal.find('#customer-discount-value').text(button.data('discount-value') || 'N/A');
});


function confirmDeleteCustomer(productId) {
    if (confirm('Are you sure you want to delete this customer?')) {
        var form = document.getElementById(`deleteForm-${productId}`);
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
                alert('Customer deleted successfully!');
                document.getElementById(`customerRow-${productId}`).remove();
            } else {
                alert('Failed to delete customer.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while deleting the product.');
        });
    }
}
