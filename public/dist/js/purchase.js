
function getPurchaseData() {

    const purchaseData = []; 
    const rows = document.getElementById('purchaseItemsTable').getElementsByTagName('tbody')[0].rows;

    for (let i = 0; i < rows.length; i++) {
        const row = rows[i];

        const productId = row.getAttribute('data-product-id'); 
        const productName = row.cells[0].innerText; 
        const qty = row.cells[1].querySelector('input').value || 0; 
        const uomId = row.getAttribute('data-uom-id'); 
        const rate = parseFloat(row.cells[3].innerText) || 0; 
        const discountType = row.cells[4].innerText; 
        const discountValue = parseFloat(row.cells[4]?.innerText || 0); 
        const netRate = parseFloat(row.cells[5]?.innerText || 0); 
        const amount = parseFloat(row.cells[6]?.innerText || 0); 
        
        const warehouseID = row.getAttribute('data-warehouse-id'); 

        
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

    return purchaseData; 
}

document.getElementById('submitPurchase').addEventListener('click', function () {

    const form = document.getElementById('purchaseForm');
    const formData = new FormData(form);
    const button = document.getElementById('submitPurchase');
    const loader = document.getElementById('loader');

    
    if (!validatePurchaseTimeField() || !validateSupplierField()) {
        return; 
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
                clearPurchaseOrderItemsTable();
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
    document.getElementById('gross_amount_label').textContent = grossAmount.toFixed(2); 

    document.getElementById('net_amount_id').value = netAmount.toFixed(2);
    document.getElementById('net_amount_label').textContent = netAmount.toFixed(2);
    
    document.getElementById('balance_id').value = (netAmount - parseFloat(document.getElementById('paid_amount_id').value || 0)).toFixed(2); 
    document.getElementById('balance_label').textContent = (netAmount - parseFloat(document.getElementById('paid_amount_id').value || 0)).toFixed(2); 
    
    document.getElementById('paid_amount_label').textContent = document.getElementById('paid_amount_id').value;

    
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
    
    if (type === 'success') {
        messageDiv = $('#success-message');
    } else {
        messageDiv = $('#error-message');
    }

    messageDiv.text(message).fadeIn();
  
    setTimeout(function() {
        messageDiv.fadeOut();
    }, 5000); 
}



function getPurchaseForEdit() {
    $('#loader').show();
    var purchaseId = document.querySelector('input[name="custom_purchase_order_id"]').value;
 
    if (purchaseId) {
        fetch(`/get-purchase-invoice/${purchaseId}`)
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    alert(data.error);
                    $('#loader').hide();
                } else {
                    
                    
                    populatePurchaseOrderDetails(data);
                    console.log(data);
                    document.getElementById('divSubmitPurchase').style.display = 'none'; 
                    document.getElementById('updatePurchaseOrder').style.display = 'inline-block'; 
                    document.getElementById('cancelPurchaseOrder').style.display = 'inline-block'; 
                    $('#loader').hide();
                    
                }
            })
            .catch(error => {
                $('#loader').hide();
                console.error("Error fetching order data:", error);
                alert("There was an error retrieving the order data.");
            });
    } else {
        $('#loader').hide();
        alert("Sale ID is missing or invalid.");
    }
}

function populatePurchaseOrderDetails(data) {
    
    const orderDateInput = document.querySelector('input[name="purchase_date"]');
    const customerNameInput = document.querySelector('input[name="vendor_name"]');
    const wareHouseName = document.querySelector('input[name="warehouse_name"]');

    const purchaseStatusId = document.querySelector('input[name="status_id"]');
    const purchaseStatusInput = document.querySelector('input[name="purchase_status"]');

    const customerIdInput = document.querySelector('input[name="vendor_id"]');

    const paidAmountInput = document.querySelector('input[name="paid_amount"]');
    document.getElementById('paid_amount_label').textContent = data.paidAmount; 

    
    const orderDiscountInput = document.querySelector('input[name="order_discount"]');
    const discountTypeFlat = document.querySelector('input[name="order_discount_type"][value="flat"]');
    const discountTypePercentage = document.querySelector('input[name="order_discount_type"][value="percentage"]');
    const otherChargesInput = document.querySelector('input[name="other_charges"]');

    const grossAmountInput = document.querySelector('input[name="gross_amount"]');
    

    const netTotalInput = document.querySelector('input[name="net_amount"]');
    const balanceInput = document.querySelector('input[name="balance"]');

    const remainingAmountInput = document.querySelector('input[name="remaining_amount"]');
    document.getElementById('balance_label').textContent = data.remainingAmount.toFixed(2); 

    const taxRateDropdown = document.querySelector('#tax_rate');

    if (taxRateDropdown) {
            const taxRate = data.taxRate;
            taxRateDropdown.value = String(taxRate);
    } 

    // Populate other charges field
    if (otherChargesInput) {
        const otherCharges = data.otherCharges || 0;
        otherChargesInput.value = otherCharges;
    }

    if (grossAmountInput) {
        
        grossAmountInput.disabled = false;
        grossAmountInput.value = data.grossAmount || 0;
        document.getElementById('gross_amount_label').textContent = data.grossAmount; 
        grossAmountInput.disabled = true;
    }
    
    
    if (netTotalInput) {
        netTotalInput.disabled = false;
        netTotalInput.value = data.netTotal.toFixed(2); 
        document.getElementById('net_amount_label').textContent = data.netTotal.toFixed(2); 
        netTotalInput.disabled = true;
    }

    if (balanceInput) {
        balanceInput.disabled = false;
        balanceInput.value = data.remainingAmount.toFixed(2);  
        balanceInput.disabled = true;  
    }

    if (orderDateInput && customerNameInput && wareHouseName && purchaseStatusInput) {

        orderDateInput.value = data.purchaseOrder.purchase_date || '';
        customerNameInput.value = data.purchaseOrder.supplier ? data.purchaseOrder.supplier.name : '';
        wareHouseName.value = data.purchaseOrder.warehouse_name || '';

        purchaseStatusInput.value = data.purchaseOrder.status_name || '';
        purchaseStatusId.value = data.purchaseOrder.status || '';

        if (customerIdInput) customerIdInput.value = data.purchaseOrder.supplier ? data.purchaseOrder.supplier.id : '';
        
        if (paidAmountInput) paidAmountInput.value = data.paidAmount || '';
        
        if (remainingAmountInput) remainingAmountInput.value = data.remainingAmount || '';

        // Populate order discount value
        const discountAmount = data.purchaseOrder.discount_amount || 0;
        const discountType = data.purchaseOrder.discount_type || '-';  // Default to flat

        orderDiscountInput.value = discountAmount;

        if (discountType === '-') {
            orderDiscountInput.value = discountAmount;
            discountTypeFlat.checked = true;
            discountTypePercentage.checked = false;
        } else if (discountType === '%') {
            orderDiscountInput.value = discountAmount;
            discountTypePercentage.checked = true;
            discountTypeFlat.checked = false;
        }

        // Populate purchase items
        var table = document.getElementById('purchaseItemsTable').getElementsByTagName('tbody')[0];

        data.purchaseItems.forEach(item => {
            var newRow = table.insertRow();
            // Set custom attributes on the new row
            newRow.setAttribute('data-product-id', item.product_id);
            newRow.setAttribute('data-uom-id', item.uom_id);
            newRow.setAttribute('data-warehouse-id', item.entry_warehouse);
                
            const cell1 = newRow.insertCell(0);
            const cell2 = newRow.insertCell(1);
            const cell3 = newRow.insertCell(2);
            const cell4 = newRow.insertCell(3);
            const cell5 = newRow.insertCell(4);
            const cell6 = newRow.insertCell(5);
            const cell7 = newRow.insertCell(6);
            const cell8 = newRow.insertCell(7);
            const cell9 = newRow.insertCell(8);

            // Populate cell content
            cell1.innerHTML = item.product_name;

            const input = createEditableQuantityCell(item.quantity);
            cell2.appendChild(input);

            cell3.innerHTML = item.uom_name;

            cell4.innerHTML = parseFloat(item.unit_price).toFixed(2);

            if (item.discount_amount.includes('%')) {
                cell5.innerHTML = item.discount_amount;
            } else {
                cell5.innerHTML = item.discount_amount;
            }

            cell6.innerHTML = parseFloat(item.net_rate).toFixed(2);
            const amount = (item.quantity * item.net_rate).toFixed(2);
            cell7.innerHTML = amount;

            cell8.innerHTML = item.warehouse_name ? item.warehouse_name : '';

            const deleteButton = document.createElement("button");
            deleteButton.type = "button";
            deleteButton.innerHTML = "Delete";
            deleteButton.classList.add("btn", "btn-danger", "btn-sm");
            deleteButton.onclick = function () { removeItem(deleteButton); };
            cell9.appendChild(deleteButton);
        });
        
    } else {
        
        console.error('Some required input elements are missing.');
    }
}



function updatePurchaseOrder() {
    
    const form = document.getElementById('purchaseForm');
    const formData = new FormData(form);
    const loader = document.getElementById('loader');
    const button = document.getElementById('submitPurchase');

    const customPurchaseOrderId = document.querySelector('input[name="custom_purchase_order_id"]').value.trim();

    
    if (!customPurchaseOrderId || customPurchaseOrderId.length === 0) {
        showMessage('error', 'Purchase Order ID is required.');
        return;
    }

    const purchaseDateField = document.getElementById('purchase-date-input');
    const supplierField = document.getElementById('vendor-id');
    
    
    if (!purchaseDateField) {
        console.error('Purchase date field is missing.');
        showMessage('error', 'Purchase date field is missing in the form.');
        return;
    }

    if (!supplierField) {
        console.error('Supplier field is missing.');
        showMessage('error', 'Supplier field is missing in the form.');
        return;
    }

    // Validate Purchase Date field
    if (!purchaseDateField.value) {
        showMessage('error', 'Please select a valid purchase date.');
        purchaseDateField.focus();
        return;
    }

    // Validate Supplier field
    if (!supplierField.value || supplierField.value === '') {
        showMessage('error', 'Please select a valid supplier.');
        supplierField.focus();
        return;
    }

    button.disabled = true;
    
    $('#loader').show();

    
    formData.append('_method', 'PUT');
    var purchaseOrderData = getPurchaseData();  
    
    formData.append('purchaseOrderData', JSON.stringify(purchaseOrderData));  
    
    for (let [key, value] of formData.entries()) {
        console.log(`${key}: ${value}`);
    }
    fetch(`/purchases/${customPurchaseOrderId}`, {
        method: 'POST', 
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
            clearPurchaseOrderItemsTable();
                    document.getElementById('divSubmitPurchase').style.display = 'block'; 
                    document.getElementById('updatePurchaseOrder').style.display = 'none'; 
                    document.getElementById('cancelPurchaseOrder').style.display = 'none'; 

        } else {
            showMessage('error', data.message);
        }
    })
    .catch(error => {
        $('#loader').hide();
        button.disabled = false;
        console.error("Error:", error);
        showMessage('error', 'There was an error updating the purchase order.');
    });
}


function clearPurchaseOrderItemsTable() {
    
    const tableBody = document.getElementById('purchaseItemsTable').getElementsByTagName('tbody')[0];
    tableBody.innerHTML = '';
    document.getElementById('gross_amount_label').textContent = '0.00';
    document.getElementById('net_amount_label').textContent = '0.00';
    document.getElementById('paid_amount_label').textContent = '0.00';
    document.getElementById('balance_label').textContent = '0.00'; 
}

document.getElementById('updatePurchaseOrder').addEventListener('click', updatePurchaseOrder);
document.getElementById('cancelOrder').addEventListener('click', cancelEditMode);