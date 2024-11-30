window.onload = function() {
    
    var dateInput = document.getElementById('datetimepicker_dark1');
    
    var currentDate = new Date();
    var year = currentDate.getFullYear();
    var month = (currentDate.getMonth() + 1).toString().padStart(2, '0'); 
    var day = currentDate.getDate().toString().padStart(2, '0');
    var hours = currentDate.getHours().toString().padStart(2, '0');
    var minutes = currentDate.getMinutes().toString().padStart(2, '0');
    var formattedDate = `${year}-${month}-${day}T${hours}:${minutes}`;
    dateInput.value = formattedDate;
    
    document.getElementById('customer-name-input').focus();
};

$(function () {
    $('#order-listings').DataTable({
        'paging': true,
        'lengthChange': true,
        'searching': true,
        'ordering': true,
        'info': true,
        'autoWidth': true,
        'order': [[0, 'desc']] // Sorts by first column (Payment Date) in descending order
    });
});


// Handle the form submission with AJAX
document.getElementById('create-customer-form').addEventListener('submit', function (e) {
    e.preventDefault(); // Prevent the default form submission behavior

    const form = this;  // Get the form element
    const formData = new FormData(form);  // Create FormData object from the form
     // Hide messages before submitting the form
     document.getElementById('success-message-customer-save').style.display = 'none';
     document.getElementById('error-message-customer-save').style.display = 'none';
    // Send the form data using AJAX (fetch)
    fetch(form.action, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',  // Set CSRF token (for Laravel)
            'X-Requested-With': 'XMLHttpRequest'  // Indicate that it's an AJAX request
        },
        body: formData  // Attach the form data to the request
    })
    .then(response => response.json())  // Parse the JSON response
    .then(data => {
        console.log(data);
        // Check if the request was successful
        if (data.success) {
            document.getElementById('success-message-customer-save').style.display = 'block';
            document.getElementById('success-message-customer-save').innerText = data.message;
            form.reset();  // Reset the form after successful submission (optional)
            // Append the new customer to the dropdown
             // Check if customer data exists before appending
        if (data.customer_name && data.customer_id) {
            // Append the new customer to the customer dropdown
            const customerNamesList = document.getElementById('customer-names');
            const newOption = document.createElement('option');
            newOption.value = data.customer_name; // assuming the server response contains the customer name
            newOption.setAttribute('data-id', data.customer_id); // assuming the server response contains the customer ID
            customerNamesList.appendChild(newOption);

            // Set the input field value to the new customer's name
            document.getElementById('customer-name-input').value = data.customer_name;
            document.getElementById('customer-id').value = data.customer_id;
        } else {
            console.error('Missing customer data in response');
        }

        } else {
            document.getElementById('error-message-customer-save').style.display = 'block';
            document.getElementById('error-message-customer-save').innerHTML = data.message;
        }
    })
    .catch(error => {
        // Handle any errors that occurred during the fetch
        console.error("Error:", error);
        document.getElementById('error-message-customer-save').style.display = 'block';
        document.getElementById('error-message-customer-save').innerText = "An unexpected error occurred. Please try again later.";
    });
});




// Custoemr name validation & get id 
const customerNameInput = document.getElementById('customer-name-input');
const customerIdField = document.getElementById('customer-id');


customerNameInput.addEventListener('input', function() {
    const selectedOption = document.querySelector(`#customer-names option[value="${customerNameInput.value}"]`);
    if (selectedOption) {
        const customerId = selectedOption.getAttribute('data-id');
        customerIdField.value = customerId;
    } else {
        customerIdField.value = ''; // Clear customer ID if no valid selection
    }
});


// Salesperson name validation & get id
const salespersonNameInput = document.getElementById('salesperson-name-input');
const salespersonIdField = document.getElementById('salesperson-id');

salespersonNameInput.addEventListener('input', function() {
    const selectedOption = document.querySelector(`#salesperson-names option[value="${salespersonNameInput.value}"]`);
    if (selectedOption) {
        const salespersonId = selectedOption.getAttribute('data-id');
        salespersonIdField.value = salespersonId;
    } else {
        salespersonIdField.value = ''; // Clear salesperson ID if no valid selection
    }
});



// Product name validation & get product id
const productNameInput = document.getElementById('product-input');
const productIdField = document.getElementById('product-id'); // You should create a hidden input for product ID

productNameInput.addEventListener('input', function() {
    const selectedOption = document.querySelector(`#product_name option[value="${productNameInput.value}"]`);
    if (selectedOption) {
        const productId = selectedOption.getAttribute('data-id');
        productIdField.value = productId; 
    } else {
        productIdField.value = ''; // Clear product ID if no valid selection
    }
});


document.addEventListener('DOMContentLoaded', function () {
    const orderStatusInput = document.getElementById('order_status');
    const orderStatusIdField = document.getElementById('status-id');

    orderStatusInput.addEventListener('input', function() {
        const selectedOption = document.querySelector(`#orderStatusList option[value="${orderStatusInput.value}"]`);
        
        if (selectedOption) {
            const statusId = selectedOption.getAttribute('data-id');
            orderStatusIdField.value = statusId; 
        } else {
            orderStatusIdField.value = ''; 
        }
    });
});






function clearOrderItemsTable() {
    // Clear all rows from the order items table's body
    const tableBody = document.getElementById('orderItemsTable').getElementsByTagName('tbody')[0];
    tableBody.innerHTML = '';
}



//Savings order
document.getElementById('submitOrder').addEventListener('click', function () {
        // Get the form element
        const form = document.getElementById('orderForm');
        const formData = new FormData(form); // Create FormData object from the form
        
        var loader = document.getElementById('loader');
        var button = document.getElementById('submitOrder');
        
        const timeField = document.getElementById('datetimepicker_dark1');
        const customerField = document.getElementById('customer-id');
        
        // Validate existence of required fields
        if (!timeField) {
            console.error('Time field is missing.');
            showMessage('error', 'Time field is missing in the form.');
            return;
        }

        if (!customerField) {
            console.error('Customer field is missing.');
            showMessage('error', 'Customer field is missing in the form.');
            return;
        }

        // Validate Time field
        if (!timeField.value) {
            showMessage('error', 'Please select a valid time.');
            timeField.focus();
            return;
        }

        // Validate Customer field
        if (!customerField.value || customerField.value === '') {
            showMessage('error', 'Please select a valid customer.');
            customerField.focus();
            return;
        }

        // Disable the button to prevent further clicks
        button.disabled = true;
        $('#loader').show(); 


        var orderData = getOrderData();  // Get the order data
        formData.append('orderData', JSON.stringify(orderData));  // Add the order data as JSON
        
        // Log the entire formData including the appended orderData for debugging
            for (let pair of formData.entries()) {
                console.log(pair[0] + ': ' + pair[1]);
            }    
        fetch(form.action, {  // Use form.action to get the URL from the form's action attribute
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',  // Set CSRF token
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })
        .then(response => {
            $('#loader').fadeOut();
            button.disabled = false;

            if (!response.ok) {
                throw new Error(`Server responded with status ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            $('#loader').fadeOut();
            button.disabled = false;
            // Handle the success or error response
            if (data.success) {
                document.getElementById('success-message').style.display = 'block';
                showMessage('success',data.message);
                form.reset(); // Optionally reset the form
                clearOrderItemsTable();
                document.getElementById('datetimepicker_dark1').focus();
            } else {
                showMessage('error',data.message);
                document.getElementById('error-message').style.display = 'block';
                
                
            }
        })
        .catch(error => {
            $('#loader').fadeOut();
            button.disabled = false;
            console.error("Error:", error);
            document.getElementById('error-message').style.display = 'block';
            showMessage('error',data.message);
        });
    });

    function getOrderData() {
        var orderData = [];  // Array to store the order items data
    
        // Get the table rows
        var rows = document.getElementById('orderItemsTable').getElementsByTagName('tbody')[0].rows;
    
        // Loop through each row and get the data
        for (var i = 0; i < rows.length; i++) {
            var row = rows[i];
            
            var productId = row.getAttribute('data-product-id');  // Get product ID
            var productName = row.cells[0].innerText;  // Product name
            var qty = row.cells[1].querySelector('input').value || 0;  // Quantity input value
            var uomId = row.getAttribute('data-uom-id');
            var rate = parseFloat(row.cells[3].innerText);  // Rate
            var discountType = row.cells[4].innerText;  // Discount type (percentage or flat)
            var discountValue = parseFloat(row.cells[4].innerText) || 0;  // Discount value
            var netRate = parseFloat(row.cells[6].innerText);  // Net rate after discount
            var amount = parseFloat(row.cells[6].innerText);  // Amount after discount
            var exitWarehouse = row.cells[7].innerHTML === 'Yes' ? 1 : 0;
            
            
    
            // Add the item data to the orderData array
            orderData.push({
                product_id: productId,       // Store product ID for reference
                product_name: productName,   // Store product name for display
                qty: qty,
                uomId: uomId,
                rate: rate,
                discountType: discountType,
                discountValue: discountValue,
                netRate: netRate,
                amount: amount,
                exit_warehouse: exitWarehouse
            });
        }
    
        return orderData;  // Return the collected order data
    }

    
    function addItemToOrder() {

        // Get selected customer
        var customerInput = document.getElementById('customer-name-input');
        var selectedCustomer = customerInput.value;
    
        // Validate customer selection
        if (!selectedCustomer) {
            alert("Please select a valid customer before adding items.");
            customerInput.focus(); // Focus on the customer field for user to select
            return;
        }
    
        // Get product code from input
        var productCode = document.getElementById('product-input').value;
        
        // Locate the corresponding option in the datalist to retrieve the product ID and name
        var selectedOption = document.querySelector(`#product_name option[value="${productCode}"]`);
        if (!selectedOption) {
            alert("Product not found. Please select a valid product.");
            return;
        }
        
        var productId = selectedOption.getAttribute('data-id');
        var productName = selectedOption.textContent;
        
        // Get quantity, rate, discount values
        var qty = parseFloat(document.getElementById('qty_id').value) || 0;
        var rate = parseFloat(document.getElementById('price_id').value);
        var discountType = document.getElementById('discount_type').value; // Discount type (flat or percentage)
        var discountValue = parseFloat(document.getElementById('discount_value').value);
        var exitWarehouse = document.getElementById('exit_warehouse').checked ? 1 : 0;
        
        // Get the selected UOM value and UOM id (from data-id)
        var uomInput = document.getElementById('uom_id');
        var uomAbbreviation = uomInput.value;
        var selectedUomOption = document.querySelector(`#UOMList option[value="${uomAbbreviation}"]`);
        var uomId = selectedUomOption ? selectedUomOption.getAttribute('data-id') : null;
    
        // Validate price
        if (isNaN(rate) || rate <= 0) {
            alert("Please enter a valid price.");
            document.getElementById('price_id').focus(); // Focus the price field for the user to correct it
            return;
        }
    
        // Calculate the net rate based on the discount type
        var netRate = rate;
    
        // Apply discount if any
        if (discountType === 'percentage' && discountValue > 0) {
            netRate = rate - (rate * discountValue / 100); // Percentage discount
        } else if (discountType === 'flat' && discountValue > 0) {
            netRate = rate - discountValue; // Flat discount
        }
    
        // Ensure netRate is not negative
        netRate = Math.max(netRate, 0);
    
        // Create a new row in the order items table
        var table = document.getElementById('orderItemsTable').getElementsByTagName('tbody')[0];
        var newRow = table.insertRow();
        
        // Store productCode, productId, and UOM id as hidden attributes in the row for reference
        newRow.setAttribute('data-product-code', productCode);
        newRow.setAttribute('data-product-id', productId);
        newRow.setAttribute('data-uom-id', uomId);
        
        // Create the table cells
        var cell1 = newRow.insertCell(0); // Product Name
        var cell2 = newRow.insertCell(1); // Quantity (editable)
        var cell3 = newRow.insertCell(2); // UOM (after quantity)
        var cell4 = newRow.insertCell(3); // Rate
        var cell5 = newRow.insertCell(4); // Discount
        var cell6 = newRow.insertCell(5); // Net Rate
        var cell7 = newRow.insertCell(6); // Amount
        var cell8 = newRow.insertCell(7); // Exit Warehouse
        var cell9 = newRow.insertCell(8); // Delete button
        
        // Display product name
        cell1.innerHTML = productName;
        
        // Create editable input for quantity and add it to cell2
        var input = createEditableQuantityCell(qty);
        cell2.appendChild(input);
        
        // Set UOM in the next column
        cell3.innerHTML = uomAbbreviation;
        
        // Set rate
        cell4.innerHTML = rate.toFixed(2);
        
        // Set discount information
        if (discountType === 'percentage' && discountValue > 0) {
            cell5.innerHTML = discountValue.toFixed(2) + '%';
        } else if (discountType === 'flat' && discountValue > 0) {
            cell5.innerHTML = discountValue.toFixed(2);
        } else {
            cell5.innerHTML = '-';
        }
    
        // Set net rate and amount
        cell6.innerHTML = netRate.toFixed(2);
        var amount = (qty * netRate).toFixed(2);  // Amount based on netRate, not rate
        cell7.innerHTML = amount;
        
        // Display exit warehouse status (Yes or No)
        cell8.innerHTML = exitWarehouse ? 'Yes' : 'No';
        
        // Create delete button
        var deleteButton = document.createElement("button");
        deleteButton.type = "button";
        deleteButton.innerHTML = "Delete";
        deleteButton.classList.add("btn", "btn-danger", "btn-sm");
        deleteButton.onclick = function() { removeItem(deleteButton); };
        cell9.appendChild(deleteButton);
        
        // Clear the input fields after adding the item
        document.getElementById('product-input').value = '';
        document.getElementById('qty_id').value = '1';
        document.getElementById('price_id').value = '';
        document.getElementById('discount_value').value = '';
        
        // Recalculate totals after adding the item
        recalculateTotalsSale();
        document.getElementById('product-input').focus();
    }
    
    
    
  
    
    function createEditableQuantityCell(qty) {

        var input = document.createElement('input');
        input.type = 'number';
        input.classList.add('form-control', 'form-control-sm');
        input.value = qty || '';
        input.min = '0';
        input.step = '0.01';
    
        input.addEventListener('input', function() {
            var row = input.closest('tr');
            var newQty = parseFloat(input.value) || 0;
            var rate = parseFloat(row.cells[3].innerHTML);
            var discountInfo = row.cells[4].innerHTML;
            var discountType = discountInfo.includes('%') ? 'percentage' : 'flat';
            var discountValue = parseFloat(discountInfo) || 0;
    
            var netRate = rate;
            if (discountType === 'percentage' && discountValue > 0) {
                netRate -= (rate * discountValue) / 100;
            } else if (discountType === 'flat' && discountValue > 0) {
                netRate -= discountValue;
            }
    
            var amount = newQty * netRate;
            row.cells[5].innerHTML = netRate.toFixed(2);
            row.cells[6].innerHTML = amount.toFixed(2);
    
            recalculateTotalsSale();
        });
    
        return input;
    }


function recalculateTotalsSale() {

    var table = document.getElementById('orderItemsTable').getElementsByTagName('tbody')[0];
    var rows = table.rows;
    var grossAmount = 0;
    var orderDiscount = parseFloat(document.getElementById('order_discount_id').value) || 0; // Get order discount
    var otherCharges = parseFloat(document.getElementById('other_charges_id').value) || 0; // Get other charges
    var netAmount = 0;

    // Loop through the rows of the order items table
    for (var i = 0; i < rows.length; i++) {
        var amount = parseFloat(rows[i].cells[6].innerHTML);
        grossAmount += amount;
    }

    // Apply discount (flat or percentage)
    var discountType = document.querySelector('input[name="order_discount_type"]:checked').value;
    if (discountType === 'percentage') {
        netAmount = grossAmount - (grossAmount * (orderDiscount / 100));  // Apply discount percentage
    } else {
        netAmount = grossAmount - orderDiscount;  // Apply flat discount
    }
    
    // Get selected tax rate
    var taxRate = parseFloat(document.getElementById('tax_rate').value) || 0;
    var taxAmount = (netAmount * taxRate) / 100;
    netAmount += taxAmount;


    // Add other charges to the net amount
    netAmount += otherCharges;  // Add other charges

    // Update the total fields
    document.getElementById('gross_amount_id').value = grossAmount.toFixed(2);
    document.getElementById('gross_amount_label').textContent = grossAmount.toFixed(2); 
    
    
    
    document.getElementById('net_amount_id').value = netAmount.toFixed(2);
    document.getElementById('net_amount_label').textContent = netAmount.toFixed(2);
    
    document.getElementById('balance_id').value = (netAmount - parseFloat(document.getElementById('paid_amount_id').value || 0)).toFixed(2); 
    document.getElementById('balance_label').textContent = (netAmount - parseFloat(document.getElementById('paid_amount_id').value || 0)).toFixed(2); 
}   

    // Add event listeners to trigger recalculation when discount or other charges are modified
    document.getElementById('order_discount_id').addEventListener('input', recalculateTotalsSale);
    document.getElementById('other_charges_id').addEventListener('input', recalculateTotalsSale);
    document.getElementById('tax_rate').addEventListener('change', recalculateTotalsSale);
    document.querySelector('input[name="order_discount_type"]').addEventListener('change', recalculateTotalsSale);
    document.getElementById('paid_amount_id').addEventListener('input', recalculateTotalsSale);
    document.getElementById('updateOrder').addEventListener('click', updateOrder);
    document.getElementById('cancelOrder').addEventListener('click', cancelEditMode);

    
    // Function to remove an item from the table
    function removeItem(button) {
        var row = button.parentNode.parentNode;
        row.parentNode.removeChild(row);
        recalculateTotalsSale();
    }

 
        function showMessage(type, message) {
            var messageDiv;
            
            // Determine the div based on message type
            if (type === 'success') {
                messageDiv = $('#success-message');
            } else {
                messageDiv = $('#error-message');
            }

            // Set the message and show the div
            messageDiv.text(message).fadeIn();

            // Hide the message after 10 seconds with a fade-out effect
            setTimeout(function() {
                messageDiv.fadeOut();
            }, 5000); // 10000 milliseconds = 10 seconds
        }


function updateOrder() {

    const form = document.getElementById('orderForm');
    const formData = new FormData(form);
    const loader = document.getElementById('loader');
    const button = document.getElementById('submitOrder');

    const customOrderId = document.querySelector('input[name="custom_order_id"]').value.trim();

    // Validate custom_order_id
    if (!customOrderId || customOrderId.length === 0) {
        showMessage('error', 'Order ID is required.');
        return;
    }

        const timeField = document.getElementById('datetimepicker_dark1');
        const customerField = document.getElementById('customer-id');
        
        // Validate existence of required fields
        if (!timeField) {
            console.error('Time field is missing.');
            showMessage('error', 'Time field is missing in the form.');
            return;
        }

        if (!customerField) {
            console.error('Customer field is missing.');
            showMessage('error', 'Customer field is missing in the form.');
            return;
        }

        // Validate Time field
        if (!timeField.value) {
            showMessage('error', 'Please select a valid time.');
            timeField.focus();
            return;
        }

        // Validate Customer field
        if (!customerField.value || customerField.value === '') {
            showMessage('error', 'Please select a valid customer.');
            customerField.focus();
            return;
        }



    button.disabled = true;
    $('#loader').show();

    // Add `_method` to simulate a PUT request
    formData.append('_method', 'PUT');
    var orderData = getOrderData();  // Get the order data
    formData.append('orderData', JSON.stringify(orderData));  // Add the order data as JSON

    fetch(`/orders/${customOrderId}`, {
        method: 'POST', // Use POST as the base HTTP method
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        $('#loader').hide();
        button.disabled = false;

        if (data.success) {
            showMessage('success', data.message);
            form.reset();
            clearOrderItemsTable();

        } else {
            showMessage('error', data.message);
        }
    })
    .catch(error => {
        $('#loader').hide();
        button.disabled = false;
        console.error("Error:", error);
        showMessage('error', 'There was an error updating the order.');
    });
}

//Clear order
document.getElementById('cancelOrder').addEventListener('click', function () {
    // Get the form element
    const form = document.getElementById('orderForm');
    
    // Reset the form
    form.reset();

    // Clear the order items table
    clearOrderItemsTable();

    // Optionally, reset the loader and buttons
    const loader = document.getElementById('loader');
    const submitButton = document.getElementById('submitOrder');
    loader.style.display = 'none';
    submitButton.disabled = false;

    // Optionally show a message
    showMessage('info', 'Order form has been cleared.');
});

// Function to switch to create mode
function cancelEditMode() {
    document.getElementById('submitOrder').style.display = 'inline-block'; // Show Submit button
    document.getElementById('updateOrder').style.display = 'none'; // Hide Update button
    document.getElementById('cancelOrder').style.display = 'none'; // Hide Cancel button

    // Optionally clear the form or reset data
    document.getElementById('orderForm').reset(); // Reset form fields
    clearOrderItemsTable(); // Clear the items in the order table (if required)
}


// 

document.getElementById('product-input').addEventListener('change', function () {
    const productCode = this.value; // Get the entered product code
    const selectedOption = document.querySelector(`#product_name option[value="${productCode}"]`);

    if (selectedOption) {
        const productId = selectedOption.getAttribute('data-id'); // Get the product ID
           
        // Fetch product details using the product ID
        fetch(`/get-product-details/${productId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    
                    populateProductFields(data.product);
                } else {
                    alert(data.message);
                }
            })
            .catch(error => {
                console.error("Error fetching product details:", error);
                alert("Unable to fetch product details. Please try again.");
            });
    } else {
        alert("Invalid product selected. Please choose from the list.");
    }
});



function populateProductFields(product) {
    if (product) {
        // Set UOM abbreviation in the input field
        const uomInput = document.getElementById('uom_id');
        uomInput.value = product.uom_abbreviation;

        // Add UOM ID to data-id for further processing
        const uomOption = document.querySelector(`#UOMList option[value="${product.uom_abbreviation}"]`);
        if (uomOption) {
            uomOption.setAttribute('data-id', product.uom_id);
        }

        // Populate other fields
        document.getElementById('price_id').value = product.price || '';
        document.getElementById('qty_id').value = 1;
        
    } else {
        alert('Product details not found.');
    }
}


function confirmDeleteOrder(id) {
    if (confirm('Are you sure you want to delete this order ?')) {
        document.getElementById('deleteForm-' + id).submit();
    }
}



document.addEventListener('DOMContentLoaded', function() {
    if (window.location.pathname.includes('orders')) {
      
      const body = document.body;
      
      if (!body.classList.contains('sidebar-collapse')) {
        const sidebarToggle = document.querySelector('.sidebar-toggle');
        if (sidebarToggle) {
          sidebarToggle.click(); 
        }
      }
    }
  });