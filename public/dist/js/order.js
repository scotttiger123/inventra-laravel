

function addItemToOrder() {
    // Get the values from the form inputs
    var product = document.getElementById('Product').value;
    var qty = parseFloat(document.getElementById('qty_id').value);
    var rate = parseFloat(document.getElementById('Rate_id').value);
    var discountType = document.getElementById('discount_type').value;
    var discountValue = parseFloat(document.getElementById('discount_value').value);
    var uom = document.getElementById('uom_id').value;
    var saleNote = document.getElementById('sale_note').value;
    var staffNote = document.getElementById('staff_note').value;

    // Calculate net rate and amount based on discount type and value
    var netRate = rate;
    var amount = qty * netRate;

    if (discountType === "percentage" && !isNaN(discountValue) && discountValue > 0) {
        var discountAmount = (amount * discountValue) / 100;
    } else if (discountType === "flat" && !isNaN(discountValue) && discountValue > 0) {
        var discountAmount = discountValue; // For flat discount, directly subtract the discount value
    } else {
        var discountAmount = 0;
        discountValue = 0; // Reset discount value if there's no discount
    }

    amount = amount - discountAmount; // Apply the discount

    // Calculate net rate (including discount)
    netRate = netRate - discountAmount;

    // Round to 2 decimal places
    netRate = netRate.toFixed(2);
    amount = amount.toFixed(2);
    discountAmount = discountAmount.toFixed(2);

    // Add the item to the table
    var table = document.getElementById('orderItemsTable').getElementsByTagName('tbody')[0];
    var newRow = table.insertRow();

    // Create cells and append values
    var cell1 = newRow.insertCell(0);
    var cell2 = newRow.insertCell(1);
    var cell3 = newRow.insertCell(2);
    var cell4 = newRow.insertCell(3);
    var cell5 = newRow.insertCell(4);
    var cell6 = newRow.insertCell(5);
    var cell7 = newRow.insertCell(6);
    
    cell1.innerHTML = product;
    cell2.innerHTML = qty.toFixed(2);
    cell3.innerHTML = rate.toFixed(2);
    
    // For discount display, check the discount type and value
    if (discountType === 'percentage' && discountValue > 0) {
        cell4.innerHTML = discountValue.toFixed(2) + '%';
    } else if (discountType === 'flat' && discountValue > 0) {
        cell4.innerHTML = discountValue.toFixed(2);
    } else {
        cell4.innerHTML = '-'; // Display dash when there's no discount
    }
    
    cell5.innerHTML = netRate;
    cell6.innerHTML = amount;

    // Create a delete button using Bootstrap classes
    var deleteButton = document.createElement("button");
    deleteButton.type = "button";
    deleteButton.innerHTML = "Delete";
    
    // Apply Bootstrap classes for a red button
    deleteButton.classList.add("btn", "btn-danger", "btn-sm");
    
    // Call removeItem function on button click
    deleteButton.onclick = function() { removeItem(deleteButton); };
    
    // Append the delete button to the last cell
    cell7.appendChild(deleteButton);

    // Clear the input fields after adding the item
    document.getElementById('Product').value = '';
    document.getElementById('qty_id').value = '';
    document.getElementById('Rate_id').value = '';
    document.getElementById('discount_value').value = '';
    document.getElementById('uom_id').value = '';
    document.getElementById('sale_note').value = '';
    document.getElementById('staff_note').value = '';

    // Recalculate the totals (you will need to add a function for this)
    recalculateTotals();
}

function recalculateTotals() {
    var table = document.getElementById('orderItemsTable').getElementsByTagName('tbody')[0];
    var rows = table.rows;
    var grossAmount = 0;
    var discountAmount = 0;
    var netAmount = 0;

    for (var i = 0; i < rows.length; i++) {
        var amount = parseFloat(rows[i].cells[5].innerHTML);
        grossAmount += amount;
    }

    document.getElementById('GTotal').value = grossAmount.toFixed(2);
    document.getElementById('NeAmount_id').value = grossAmount.toFixed(2);  // Adjust this if needed
    document.getElementById('Balance_id').value = grossAmount.toFixed(2);  // Adjust this if needed
}

function removeItem(button) {
    var row = button.parentNode.parentNode;
    row.parentNode.removeChild(row);
    recalculateTotals();
}




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


    
    function deleteItem(button) {
        // Delete the row from the table
        const row = button.parentNode.parentNode;
        row.parentNode.removeChild(row);
    }


//  //iCheck for checkbox and radio inputs
//  $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
//     checkboxClass: 'icheckbox_minimal-blue',
//     radioClass   : 'iradio_minimal-blue'
//   })
//   //Red color scheme for iCheck
//   $('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
//     checkboxClass: 'icheckbox_minimal-red',
//     radioClass   : 'iradio_minimal-red'
//   })
//   //Flat red color scheme for iCheck
//   $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
//     checkboxClass: 'icheckbox_flat-green',
//     radioClass   : 'iradio_flat-green'
//   })

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
