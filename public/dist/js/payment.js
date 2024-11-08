<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
$(function () {
    
    $('#payment-listings').DataTable({
        'paging': true,
        'lengthChange': false,
        'searching': false,
        'ordering': true,
        'info': true,
        'autoWidth': false
    });
});

    $(document).ready(function() {
        // When the payment head changes, update the payable_id dropdown
        $('#payment_head').change(function() {
            var head = $(this).val(); 
            
            // Clear the Payable ID dropdown
            $('#payable_id').empty().append('<option value="">Select...</option>');
            
            // Update label text based on selected payment head
            const label = document.getElementById('payableLabel');
            const label2 = document.getElementById('saleOrPurchase');
            
            if (head === 'customer') {
                label.textContent = 'Customer  *';
                label2.textContent = 'Sale Order Id';
                // Automatically set Payable Type to 'customer'
                $('input[name="payable_type"]').val('customer');
            } else if (head === 'supplier') {
                label.textContent = 'Supplier  *';
                label2.textContent = 'Purchase Order Id';
                // Automatically set Payable Type to 'supplier'
                $('input[name="payable_type"]').val('supplier');
            }

            if (head != '') {
                // Make an AJAX request to fetch customers or suppliers based on the selected head
                $.ajax({
                    url: '/payments/get-payable-options/' + head, 
                    type: 'GET',
                    success: function(response) {
                        console.log(response);
                        // Populate the Payable ID dropdown with the appropriate options
                        if (response.customers.length > 0) {
                            $.each(response.customers, function(index, customer) {
                                $('#payable_id').append('<option value="' + customer.id + '">' + customer.name + '</option>');
                            });
                        } else if (response.suppliers.length > 0) {
                            $.each(response.suppliers, function(index, supplier) {
                                $('#payable_id').append('<option value="' + supplier.id + '">' + supplier.name + '</option>');
                            });
                        }
                    }
                });
            }
        });
    });

