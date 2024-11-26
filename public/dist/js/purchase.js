
$(function () {
    $('#purchase-listings').DataTable({
        'paging': true,
        'lengthChange': true,
        'searching': true,
        'ordering': true,
        'info': true,
        'autoWidth': true,
        'order': [[0, 'desc']] // Sorts by first column (Payment Date) in descending order
    });
});


function getPurchaseData() {

    const purchaseData = []; // Array to store the purchase items data
    const rows = document.getElementById('purchaseItemsTable').getElementsByTagName('tbody')[0].rows;

    for (let i = 0; i < rows.length; i++) {
        const row = rows[i];

        const productId = row.getAttribute('data-product-id'); // Product ID
        const productName = row.cells[0].innerText; // Product name
        const qty = row.cells[1].querySelector('input').value || 0; // Quantity
        const uomId = row.getAttribute('data-uom-id'); // Unit of Measure ID
        const rate = parseFloat(row.cells[3].innerText) || 0; // Rate
        const discountType = row.cells[4].innerText; // Discount type
        const discountValue = parseFloat(row.cells[4]?.innerText || 0); // Discount value
        const netRate = parseFloat(row.cells[5]?.innerText || 0); // Net rate
        const amount = parseFloat(row.cells[6]?.innerText || 0); // Amount after discount
        
        const warehouseID = row.getAttribute('data-warehouse-id'); // Product ID

        // Add item to purchaseData array
        purchaseData.push({
            product_id: productId,
            product_name: productName,
            qty: parseInt(qty, 10),
            uom_id: uomId,
            rate: rate,
            discount_type: discountType,
            discount_value: discountValue,
            net_rate: netRate,
            amount: amount,
            inward_warehouse_id: warehouseID ,
        });
    }

    return purchaseData; // Return collected purchase data
}

document.getElementById('submitPurchase').addEventListener('click', function () {

    const form = document.getElementById('purchaseForm');
    const formData = new FormData(form);
    const button = document.getElementById('submitPurchase');
    const loader = document.getElementById('loader');

    // Validate required fields
    if (!validatePurchaseTimeField() || !validateSupplierField()) {
        return; // Stop submission if validation fails
    }

    button.disabled = true;
    $('#loader').show();

    const purchaseData = getPurchaseData();
    formData.append('purchaseData', JSON.stringify(purchaseData));
     // Log the entire formData including the appended orderData for debugging
     for (let pair of formData.entries()) {
        console.log(pair[0] + ': ' + pair[1]);
    }   

    fetch(form.action, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'X-Requested-With': 'XMLHttpRequest',
        },
        body: formData,
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

            if (data.success) {
                
                showMessage('success', data.message);
                form.reset();
                clearPurchaseItemsTable();
                document.getElementById('vendor-name-input').focus();
                
            } else {
                showMessage('error', data.message || 'Purchase submission failed.');
            }
        })
        .catch(error => {
            $('#loader').fadeOut();
            button.disabled = false;
            showMessage('error', error.message || 'An error occurred.');
        });
});

function clearPurchaseItemsTable() {
    // Clear all rows from the order items table's body
    const tableBody = document.getElementById('purchaseItemsTable').getElementsByTagName('tbody')[0];
    tableBody.innerHTML = '';
}

// Validation functions
function validatePurchaseTimeField() {
    const timeField = document.getElementById('purchase-date-input');
    if (!timeField) {
        showMessage('error', 'Time field is missing in the form.');
        return false;
    }
    if (!timeField.value) {
        showMessage('error', 'Please select a valid time.');
        timeField.focus();
        return false;
    }
    return true;
}

function validateSupplierField() {
    const supplierField = document.getElementById('vendor-id');
    if (!supplierField) {
        showMessage('error', 'Supplier field is missing in the form.');
        return false;
    }
    if (!supplierField.value) {
        showMessage('error', 'Please select a valid supplier.');
        supplierField.focus();
        return false;
    }
    return true;
}

    function addItemToPurchase() {
        
        // Get selected customer
        var vendorInput = document.getElementById('vendor-id');
        var selectedvendor = vendorInput.value;
    
        // Validate customer selection
        if (!selectedvendor) {
            alert("Please select a valid vendor before adding items.");
            document.getElementById('vendor-name-input').focus();
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


        // Get UOM code from input
        var uomCode = document.getElementById('uom-name-input').value;

        // Locate the corresponding option in the datalist to retrieve the UOM ID and name
        var selectedUOMOption = document.querySelector(`#UOMList option[value="${uomCode}"]`);
        if (!selectedUOMOption) {
            alert("UOM not found. Please select a valid UOM.");
            return;
        }
        
        
        var productId = selectedOption.getAttribute('data-id');
        var productName = selectedOption.textContent;
        
        // Get quantity, rate, discount values
        var qty = parseFloat(document.getElementById('qty_id').value) || 0;
        var rate = parseFloat(document.getElementById('price_id').value);
        var discountType = document.getElementById('discount_type').value; // Discount type (flat or percentage)
        var discountValue = parseFloat(document.getElementById('discount_value').value);
        var inwardWarehouse = document.getElementById('warehouse-id').value;
        
        // Get the selected UOM value and UOM id (from data-id)
        var uomInput = document.getElementById('uom-name-input');
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
        var table = document.getElementById('purchaseItemsTable').getElementsByTagName('tbody')[0];
        var newRow = table.insertRow();
        
        // Store productCode, productId, and UOM id as hidden attributes in the row for reference
        newRow.setAttribute('data-product-code', productCode);
        newRow.setAttribute('data-product-id', productId);
        newRow.setAttribute('data-uom-id', uomId);
        newRow.setAttribute('data-warehouse-id', inwardWarehouse);
        
        
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
        cell8.innerHTML = inwardWarehouse;
        
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
    
    


    
function recalculateTotals() {

    var table = document.getElementById('purchaseItemsTable').getElementsByTagName('tbody')[0];
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
    document.getElementById('net_amount_id').value = netAmount.toFixed(2);
    document.getElementById('balance_id').value = (netAmount - parseFloat(document.getElementById('paid_amount_id').value || 0)).toFixed(2); // Balance = Net Amount - Paid Amount
}

    
  // Vendor name validation & get id 
const vendorNameInput = document.getElementById('vendor-name-input');
const vendorIdField = document.getElementById('vendor-id');


vendorNameInput.addEventListener('input', function() {
    const selectedOption = document.querySelector(`#vendor-names option[value="${vendorNameInput.value}"]`);
    if (selectedOption) {
        const vendorId = selectedOption.getAttribute('data-id');
        vendorIdField.value = vendorId;
    } else {
        vendorIdField.value = ''; 
    }
});

// Warehouse name validation & get ID
const warehouseNameInput = document.getElementById('warehouse-name-input');
const warehouseIdField = document.getElementById('warehouse-id');

warehouseNameInput.addEventListener('input', function() {
    const selectedOption = document.querySelector(`#warehouse-names option[value="${warehouseNameInput.value}"]`);
    if (selectedOption) {
        const warehouseId = selectedOption.getAttribute('data-id');
        warehouseIdField.value = warehouseId;
    } else {
        warehouseIdField.value = ''; 
    }
});


// Status selection validation and ID assignment
const statusNameInput = document.getElementById('purchase-status-input');
const statusIdField = document.getElementById('status-id');

statusNameInput.addEventListener('input', function () {
    const selectedOption = document.querySelector(`#orderStatusList option[value="${statusNameInput.value}"]`);
    if (selectedOption) {
        const statusId = selectedOption.getAttribute('data-id');
        statusIdField.value = statusId;
    } else {
        statusIdField.value = '';  // Reset if no match
    }
});








// UOM selection validation and ID assignment
const uomNameInput = document.getElementById('uom-name-input');
const uomIdField = document.getElementById('uom-id');

uomNameInput.addEventListener('input', function() {
    const selectedOption = document.querySelector(`#UOMList option[value="${uomNameInput.value}"]`);
    if (selectedOption) {
        const uomId = selectedOption.getAttribute('data-id');
        uomIdField.value = uomId;
    } else {
        uomIdField.value = '';  // Reset if no match
    }
});




window.onload = function() {
    
    var dateInput = document.getElementById('purchase-date-input');
    
    var currentDate = new Date();
    var year = currentDate.getFullYear();
    var month = (currentDate.getMonth() + 1).toString().padStart(2, '0'); 
    var day = currentDate.getDate().toString().padStart(2, '0');
    var hours = currentDate.getHours().toString().padStart(2, '0');
    var minutes = currentDate.getMinutes().toString().padStart(2, '0');
    var formattedDate = `${year}-${month}-${day}T${hours}:${minutes}`;
    dateInput.value = formattedDate;
    
    document.getElementById('vendor-name-input').focus();
};



    document.getElementById('order_discount_id').addEventListener('input', recalculateTotals);
    document.getElementById('other_charges_id').addEventListener('input', recalculateTotals);
    document.getElementById('tax_rate').addEventListener('change', recalculateTotals);
    document.querySelector('input[name="order_discount_type"]').addEventListener('change', recalculateTotals);
    document.getElementById('paid_amount_id').addEventListener('input', recalculateTotals);
    
    
    
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