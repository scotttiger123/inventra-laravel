
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
        productIdField.value = productId; // Assuming you have a hidden input field to store the product ID
    } else {
        productIdField.value = ''; // Clear product ID if no valid selection
    }
});





//Savings order
document.getElementById('submitOrder').addEventListener('click', function () {
        // Get the form element
        const form = document.getElementById('orderForm');
        const formData = new FormData(form); // Create FormData object from the form
        
        var loader = document.getElementById('loader');
        var button = document.getElementById('submitOrder');
        
        // Disable the button to prevent further clicks
        button.disabled = true;
        loader.style.display = 'inline-block';


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
            loader.style.display = 'none';
            button.disabled = false;

            if (!response.ok) {
                throw new Error(`Server responded with status ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            loader.style.display = 'none';
            button.disabled = false;
            // Handle the success or error response
            if (data.success) {
                document.getElementById('success-message').style.display = 'block';
                showMessage('success',data.message);
                form.reset(); // Optionally reset the form
            } else {
                showMessage('error',data.message);
                document.getElementById('error-message').style.display = 'block';
                
                
            }
        })
        .catch(error => {
            loader.style.display = 'none';
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
            var rate = parseFloat(row.cells[2].innerText);  // Rate
            var discountType = row.cells[3].innerText;  // Discount type (percentage or flat)
            var discountValue = parseFloat(row.cells[3].innerText) || 0;  // Discount value
            var netRate = parseFloat(row.cells[4].innerText);  // Net rate after discount
            var amount = parseFloat(row.cells[5].innerText);  // Amount after discount
            var exitWarehouse = row.cells[6].innerHTML === 'Yes' ? 1 : 0;
            
    
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
    
        // Validate if UOM is selected
        if (!uomId) {
            alert("Please select a valid Unit of Measure (UOM).");
            return;
        }
    
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
        var netRate = rate.toFixed(2);
        var amount = (qty * rate).toFixed(2);
    
        cell6.innerHTML = netRate;
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
        recalculateTotals();
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
    
            recalculateTotals();
        });
    
        return input;
    }





    // // Function to recalculate the totals (you can modify this based on your exact needs)
    // function recalculateTotals() {
    //     var table = document.getElementById('orderItemsTable').getElementsByTagName('tbody')[0];
    //     var rows = table.rows;
    //     var grossAmount = 0;

    //     for (var i = 0; i < rows.length; i++) {
    //         var amount = parseFloat(rows[i].cells[5].innerHTML);
    //         grossAmount += amount;
    //     }

    //     // Update the total fields
    //     document.getElementById('GTotal').value = grossAmount.toFixed(2);
    //     document.getElementById('NeAmount_id').value = grossAmount.toFixed(2);
    //     document.getElementById('Balance_id').value = grossAmount.toFixed(2);
    // }
// Function to recalculate the totals (you can modify this based on your exact needs)
// Function to recalculate the totals (including Other Charges)
// Function to recalculate the totals (including Other Charges and Balance)
function recalculateTotals() {
    var table = document.getElementById('orderItemsTable').getElementsByTagName('tbody')[0];
    var rows = table.rows;
    var grossAmount = 0;
    var orderDiscount = parseFloat(document.getElementById('order_discount_id').value) || 0; // Get order discount
    var otherCharges = parseFloat(document.getElementById('other_charges_id').value) || 0; // Get other charges
    var netAmount = 0;

    // Loop through the rows of the order items table
    for (var i = 0; i < rows.length; i++) {
        var amount = parseFloat(rows[i].cells[5].innerHTML);
        grossAmount += amount;
    }

    // Apply discount (flat or percentage)
    var discountType = document.querySelector('input[name="order_discount_type"]:checked').value;
    if (discountType === 'percentage') {
        netAmount = grossAmount - (grossAmount * (orderDiscount / 100));  // Apply discount percentage
    } else {
        netAmount = grossAmount - orderDiscount;  // Apply flat discount
    }

    // Add other charges to the net amount
    netAmount += otherCharges;  // Add other charges

    // Update the total fields
    document.getElementById('gross_amount_id').value = grossAmount.toFixed(2);
    document.getElementById('net_amount_id').value = netAmount.toFixed(2);
    document.getElementById('balance_id').value = (netAmount - parseFloat(document.getElementById('paid_amount_id').value || 0)).toFixed(2); // Balance = Net Amount - Paid Amount
}

// Add event listeners to trigger recalculation when discount or other charges are modified
document.getElementById('order_discount_id').addEventListener('input', recalculateTotals);
document.getElementById('other_charges_id').addEventListener('input', recalculateTotals);
document.querySelector('input[name="order_discount_type"]').addEventListener('change', recalculateTotals);
document.getElementById('paid_amount_id').addEventListener('input', recalculateTotals);

    // Function to remove an item from the table
    function removeItem(button) {
        var row = button.parentNode.parentNode;
        row.parentNode.removeChild(row);
        recalculateTotals();
    }

 // Function to show message and hide it after 10 seconds
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
    }, 10000); // 10000 milliseconds = 10 seconds
}
