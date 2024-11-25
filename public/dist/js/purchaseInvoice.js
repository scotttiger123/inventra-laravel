function getPurchaseInvoiceDetails(customPurchaseId = null) {
    let purchaseId = customPurchaseId;

    $('#loader').show(); 
    
    // If customPurchaseId is not passed, retrieve it from the input field
    if (!purchaseId) {
        $('#loader').hide(); 
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
                    $('#loader').hide(); 
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
                    setTextContentById('amountDue', `${amountDue}`);
                    

                    // Update purchase items
                    const purchaseItems = (data.purchaseItems || []).map(item => `
                        <tr>
                            <td>${item.product_name || 'N/A'}</td>
                            <td>${item.quantity || '0'}</td>
                            <td>${item.uom_name || 'N/A'}</td>
                            <td>${parseFloat(item.unit_price || 0).toFixed(2)}</td>
                            <td>${item.discount_amount || 'N/A'}</td>
                            <td>${parseFloat(item.net_rate || 0).toFixed(2)}</td>
                            <td>${parseFloat(item.amount || 0).toFixed(2)}</td>
                        </tr>
                    `).join('');
                    document.getElementById('purchaseInvoiceItems').innerHTML = purchaseItems;

                    // Update summary amounts
                    setTextContentById('subtotalAmount', `${grossAmount}`);
                    setTextContentById('otherCharges', `${otherCharges}`);
                    // setTextContentById('totalAmount', `${netTotal}`);
                    setTextContentById('paidAmount', `${paidAmount}`);

                    $('#loader').hide(); 
                    $('#purchaseInvoiceModal').modal('show');
                }
            })
            .catch(error => {
                $('#loader').hide(); 
                console.error('Error fetching purchase invoice:', error.message || error);
                alert('An error occurred while fetching the purchase invoice.');
            });
    } else {
        $('#loader').hide(); 
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


async function printModalContent(modalSelector, customPurchaseId = null) {
    // Get the section content based on the provided selector
    const section = document.querySelector(modalSelector);

    // Check if the section exists
    if (!section) {
        console.error(`Section with selector "${modalSelector}" not found.`);
        return;
    }

    // Show the section before capturing it
    section.style.display = 'block';

    try {
        // Generate the canvas from the modal content (using section instead of modalContent)
        const canvas = await html2canvas(section, {
            useCORS: true,
            scale: 1, // Adjust for better resolution (increase if needed for higher quality)
            x: 0, // Optional: Adjust the x position to fit the content
            y: 0, // Optional: Adjust the y position
            width: section.offsetWidth, // Ensure the width matches the section's width
            height: section.offsetHeight, // Ensure the height matches the section's height
            scrollX: 0, // Disable horizontal scroll when capturing
            scrollY: 0, // Disable vertical scroll when capturing
            backgroundColor: null, // Transparent background (useful if modal has custom background)
        });

        // Convert the canvas to an image
        const image = canvas.toDataURL("image/png");

        // Open a new window for printing
        const printWindow = window.open('', '_blank');
        printWindow.document.write('<html><head><title>Print Invoice</title>');
        
        // Add CSS styles for the print layout
        printWindow.document.write('<style>');
        printWindow.document.write(`
            body {
                margin: 0;
                padding: 0;
                text-align: center;
            }
            img {
                width: 100%; /* Ensure the image takes full width */
                height: auto; /* Maintain aspect ratio */
                max-width: 100%; /* Prevent image from being stretched */
            }
        `);
        printWindow.document.write('</style>');
        
        printWindow.document.write('</head><body>');
        printWindow.document.write(`<img src="${image}" />`);
        printWindow.document.write('</body></html>');
        
        // Close the document to trigger the print window
        printWindow.document.close();

        // Wait for the new window to load before triggering the print
        printWindow.onload = () => {
            printWindow.print();
            printWindow.close();
        };

        // Hide the section after capturing it
        section.style.display = 'none';

    } catch (error) {
        console.error("Error generating image for printing:", error);
        alert("Error generating image for printing.");
    }
}






async function getPurchaseDataForSharing(customPurchaseId = null, action = 'print') {
    $('#loader').show(); 
    let purchaseId = customPurchaseId;

    // If customPurchaseId is not passed, retrieve it from the input field
    if (!purchaseId) {
        purchaseId = document.querySelector('input[name="custom_purchase_id"]').value;
        $('#loader').hide(); 
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
                    $('#loader').hide(); 
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
                    setTextContentById('invoiceDate-watsapp', purchaseDate);
                    setTextContentById('supplierName-watsapp', supplierName);
                    setTextContentById('supplierAddress-watsapp', supplierAddress);
                    setTextContentById('supplierPhone-watsapp', supplierPhone);
                    setTextContentById('supplierEmail-watsapp', supplierEmail);
                    setTextContentById('purchaseNote-watsapp', purchaseNote);
                    setTextContentById('purchaseOrderId-watsapp', purchaseOrderId);
                    setTextContentById('discountAmount-watsapp', `${parseFloat(discountAmount).toFixed(2)}`);
                    setTextContentById('amountDue-watsapp', `${amountDue}`);

                    // Update purchase items
                    const purchaseItems = (data.purchaseItems || []).map(item => `
                        <tr>
                            <td>${item.product_name || 'N/A'}</td>
                            <td>${item.quantity || '0'}</td>
                            <td>${item.uom_name || 'N/A'}</td>
                            <td>${parseFloat(item.unit_price || 0).toFixed(2)}</td>
                            <td>${item.discount_amount || 'N/A'}</td>
                            <td>${parseFloat(item.net_rate || 0).toFixed(2)}</td>
                            <td>${parseFloat(item.amount || 0).toFixed(2)}</td>
                        </tr>
                    `).join('');
                    document.getElementById('purchaseInvoiceItems-watsapp').innerHTML = purchaseItems;

                    // Update summary amounts
                    setTextContentById('subtotalAmount-watsapp', `${grossAmount}`);
                    setTextContentById('otherCharges-watsapp', `${otherCharges}`);
                    setTextContentById('paidAmount-watsapp', `${paidAmount}`);



                        // Perform the action (Share or Print)
                    if (action === 'print') {
                        $('#loader').hide(); 
                        printModalContent('.canva-section-watsapp');
                    } else if (action === 'share') {
                        $('#loader').hide(); 
                        shareSectionAsImage('.canva-section-watsapp', {
                            fileName: 'invoice.png',
                            title: 'Invoice Share',
                            text: 'Check out this invoice!',
                        });
                    }
                    
                         
                }
            })
            .catch(error => {
                $('#loader').hide(); 
                console.error('Error fetching purchase invoice:', error.message || error);
                alert('An error occurred while fetching the purchase invoice.');
            });
    } else {
        $('#loader').hide(); 
        alert('Please enter a Purchase ID');
    }
}


async function shareSectionAsImage(sectionSelector, options = {}) {
    const section = document.querySelector(sectionSelector);

    if (!section) {
        console.error(`Section with selector "${sectionSelector}" not found.`);
        return;
    }

    // Show the section before capturing it
    section.style.display = 'block';

    try {
        const canvas = await html2canvas(section, { useCORS: true });
        const image = canvas.toDataURL("image/png");

        // Check the canvas data
        console.log("Canvas image generated:", image);

        const blob = await fetch(image).then((res) => res.blob());

        // Check the blob size
        console.log(`Blob size: ${blob.size} bytes`);

        if (blob.size === 0) {
            alert("Failed to generate a valid image. Please check the section content.");
            return;
        }

        const file = new File([blob], options.fileName || "document.png", {
            type: "image/png",
        });

        if (navigator.canShare && navigator.canShare({ files: [file] })) {
            await navigator.share({
                files: [file],
                title: options.title || "Shared Content",
                text: options.text || "Check out this!",
            });

            console.log("Shared successfully!");
        } else {
            alert("Your browser does not support image sharing! Downloading the image instead.");
            const link = document.createElement("a");
            link.href = image;
            link.download = options.fileName || "document.png";
            link.click();
        }
    } catch (error) {
        console.error("Error during sharing:", error);
        alert(`Sharing failed: ${error.message}`);
    } finally {
        // Hide the section again after sharing
        section.style.display = 'none';
    }
}



