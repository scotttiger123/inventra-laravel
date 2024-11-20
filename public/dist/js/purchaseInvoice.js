function getPurchaseInvoiceDetails(customPurchaseId = null) {
    let purchaseId = customPurchaseId;
    alert(purchaseId);

    // If customPurchaseId is not passed, retrieve it from the input field
    if (!purchaseId) {
        purchaseId = document.querySelector('input[name="custom_purchase_id"]').value;
    }

    if (purchaseId) {
        fetch(`/get-purchase-invoice/${purchaseId}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.error) {
                    alert(data.error);
                } else {
                    // Extract values with defaults
                    const grossAmount = data.grossAmount ? parseFloat(data.grossAmount).toFixed(2) : '0.00';
                    const otherCharges = data.otherCharges ? parseFloat(data.otherCharges).toFixed(2) : '0.00';
                    const netTotal = data.netTotal ? parseFloat(data.netTotal).toFixed(2) : '0.00';
                    const paidAmount = data.paidAmount ? parseFloat(data.paidAmount).toFixed(2) : '0.00';
                    const amountDue = data.remainingAmount ? parseFloat(data.remainingAmount).toFixed(2) : '0.00';

                    const purchaseOrder = data.purchaseOrder || {};
                    const supplier = purchaseOrder.supplier || {};

                    const purchaseDate = purchaseOrder.purchase_date || 'N/A';
                    const purchaseOrderId = purchaseOrder.custom_purchase_id || 'N/A';
                    const supplierName = supplier.name || 'N/A';
                    const supplierAddress = supplier.address || 'N/A';
                    const supplierPhone = supplier.phone || 'N/A';
                    const supplierEmail = supplier.email || 'N/A';
                    const purchaseNote = purchaseOrder.purchase_note || 'N/A';
                    const discountAmount = purchaseOrder.discount_amount || '0.00';

                    // Populate purchase invoice details
                    setTextContentById('invoiceDate', purchaseDate);
                    setTextContentById('supplierName', supplierName);
                    setTextContentById('supplierAddress', supplierAddress);
                    setTextContentById('supplierPhone', supplierPhone);
                    setTextContentById('supplierEmail', supplierEmail);
                    setTextContentById('purchaseNote', purchaseNote);
                    setTextContentById('purchaseOrderId', purchaseOrderId);
                    setTextContentById('discountAmount', `${parseFloat(discountAmount).toFixed(2)}`);
                    setTextContentById('amountDue', `{amountDue}`);
                    setTextContentById('amountDueTop', `{amountDue}`);

                    // Update purchase items
                    const purchaseItems = (data.purchaseItems || []).map(item => `
                        <tr>
                            <td>${item.product_name || 'N/A'}</td>
                            <td>${item.quantity || '0'}</td>
                            <td>${item.uom_name || 'N/A'}</td>
                            <td>$${parseFloat(item.unit_price || 0).toFixed(2)}</td>
                            <td>${item.discount_amount || 'N/A'}</td>
                            <td>$${parseFloat(item.net_rate || 0).toFixed(2)}</td>
                            <td>$${parseFloat(item.amount || 0).toFixed(2)}</td>
                        </tr>
                    `).join('');
                    document.getElementById('purchaseInvoiceItems').innerHTML = purchaseItems;

                    // Update summary amounts
                    setTextContentById('subtotalAmount', `${grossAmount}`);
                    setTextContentById('otherCharges', `${otherCharges}`);
                    // setTextContentById('totalAmount', `${netTotal}`);
                    setTextContentById('paidAmount', `${paidAmount}`);

                    // Show the modal
                    $('#purchaseInvoiceModal').modal('show');
                }
            })
            .catch(error => {
                console.error('Error fetching purchase invoice:', error.message || error);
                alert('An error occurred while fetching the purchase invoice.');
            });
    } else {
        alert('Please enter a Purchase ID');
    }
}

// Utility function to set text content by element ID
function setTextContentById(elementId, text) {
    const element = document.getElementById(elementId);
    if (element) {
        element.textContent = text;
    } else {
        console.warn(`Element with ID '${elementId}' not found.`);
    }
}
