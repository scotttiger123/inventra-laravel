$(function () {
    $('#payment-listings').DataTable({
        'paging': true,
        'lengthChange': true,
        'searching': true,
        'ordering': true,
        'info': true,
        'autoWidth': true,
        'order': [[0, 'desc']] // Sorts by first column (Payment Date) in descending order
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
            $('input[name="payable_type"]').val('customer');
        } else if (head === 'supplier') {
            label.textContent = 'Supplier  *';
            label2.textContent = 'Purchase Order Id';
            $('input[name="payable_type"]').val('supplier');
        }

        if (head !== '') {
            // Make an AJAX request to fetch customers or suppliers based on the selected head
            $.ajax({
                url: '/payments/get-payable-options/' + head, 
                type: 'GET',
                success: function(response) {
                    console.log(response);
                    // Check if the response contains customers or suppliers data
                    if (response.customers && response.customers.length > 0) {
                        $.each(response.customers, function(index, customer) {
                            $('#payable_id').append('<option value="' + customer.id + '">' + customer.name + '</option>');
                        });
                    } else if (response.suppliers && response.suppliers.length > 0) {
                        $.each(response.suppliers, function(index, supplier) {
                            $('#payable_id').append('<option value="' + supplier.id + '">' + supplier.name + '</option>');
                        });
                    }
                }
            });
        }
    });
});

document.addEventListener('DOMContentLoaded', function () {
    $('#viewPaymentModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Button that triggered the modal

        // Extract data from data-* attributes
        
        var payableName = button.data('payable_name');
        var amount = button.data('amount');
        var status = button.data('status');
        var paymentType = button.data('payment_type');
        var paymentMethod = button.data('payment_method');
        var note = button.data('note');
        var paymentDate = button.data('payment_date'); // Make sure this is in a readable format (e.g., Y-m-d)
        
        var amountLabel = (paymentType == 'credit') ? 'Got' : (paymentType == 'debit') ? 'Paid' : 'Amount';
        var amountSectionColor = (paymentType == 'credit') ? '#28a745' : (paymentType == 'debit') ? '#dc3545' : '#007bff'; // Green for credit, Red for debit, Blue for default
        var imageSource = (paymentType == 'credit') ? '../../dist/img/currency-red.png' : (paymentType == 'debit') ? '../../dist/img/currency-green.png' : null; // Only green or red images

        
        

        // Update the modal's content
        var modal = $(this);
        
        modal.find('#modal-payable-name').text(payableName);
        modal.find('#modal-amount').text(amount);
        modal.find('#modal-status').text(status);
        modal.find('#modal-payment-type').text(paymentType);
        modal.find('#modal-payment-method').text(paymentMethod);
        modal.find('#modal-note').text(note);
        modal.find('#modal-payment-date').text(paymentDate);
        modal.find('#modal-amount-label').text(amountLabel);
        modal.find('#amount-section').css('background-color', amountSectionColor);
        $('#currency-icon').attr('src', imageSource);  // Update the image source by ID

        
    });
});
