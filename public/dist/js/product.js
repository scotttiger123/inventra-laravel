$(function () {
    
    $('#product-listings').DataTable({
        'paging': true,
        'lengthChange': true,
        'searching': true,
        'ordering': true,
        'info': true,
        'autoWidth': true
    });
});

$('#viewProductModal').on('show.bs.modal', function (event) {

    var button = $(event.relatedTarget); 
    var modal = $(this);
    console.log(button.data());

    
    // Populate modal fields with data from the button's data-* attributes
    modal.find('#product-code').text(button.data('code') || 'N/A');
    modal.find('#product-name').text(button.data('name') || 'N/A');
    modal.find('#product-cost').text(button.data('cost') || 'N/A');
    modal.find('#product-price').text(button.data('price') || 'N/A');
    modal.find('#product-uom').text(button.data('uom') || 'N/A');  // Display UOM name
    modal.find('#product-details').text(button.data('details') || 'N/A');
    modal.find('#product-initial-stock').text(button.data('initial-stock') || 'N/A');
    modal.find('#product-alert-quantity').text(button.data('alert-quantity') || 'N/A');
    
    modal.find('#product-image').attr('src', button.data('image') || 'default-image-url.jpg');
});


function confirmDelete(productId) {
    if (confirm('Are you sure you want to delete this product?')) {
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
                alert('Product deleted successfully!');
                document.getElementById(`customerRow-${productId}`).remove();
            } else {
                alert('Failed to delete product.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while deleting the product.');
        });
    }
}
