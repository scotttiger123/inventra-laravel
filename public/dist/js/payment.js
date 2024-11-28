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
        var button = $(event.relatedTarget); 
        
        var payableName = button.data('payable_name');
        var amount = button.data('amount');
        var status = button.data('status');
        var paymentType = button.data('payment_type');
        var paymentMethod = button.data('payment_method');
        var note = button.data('note');
        var paymentDate = button.data('payment_date'); 
        
        var amountLabel = paymentType;
        var amountSectionColor = 'black'
        var imageSource = (paymentType == 'credit') ? '../../dist/img/currency-green.png' : (paymentType == 'debit') ? '../../dist/img/currency-red.png' : null; // Only green or red images

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

async function getPaymentDataForSharing(voucherId, action = 'print') {
    $('#loader').show();

    if (voucherId) {
        try {
            const response = await fetch(`/get-payment-details/${voucherId}`);

            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }

            const data = await response.json();

            if (data.error) {
                alert(data.error);
                $('#loader').hide();
                return;
            }

            const paymentData = data.payment;
            console.log("DATaa",paymentData);

            const paymentDate = paymentData.payment_date || 'N/A';
            document.getElementById('modal-payment-date-receipt-watsapp').innerText = `${paymentDate}`;

            const amount = paymentData.amount ? parseFloat(paymentData.amount).toFixed(2) : '0.00';
            
            document.getElementById('modal-amount-watsapp').innerText = `${paymentData.currency} ${amount}`;


            const paymentType = paymentData.payment_type || 'N/A';
            document.getElementById('modal-amount-label-receipt').innerText = paymentType;
            
            
            const currencyIcon = (paymentData.payment_type == 'credit') ? '../../dist/img/currency-green.png' : (paymentData.payment_type == 'debit') ? '../../dist/img/currency-red.png' : null; // Only green or red images
            document.getElementById('currency-icon-watsapp-receipt').src = currencyIcon;
            

            document.getElementById('modal-note-receipt-watsapp').innerText = paymentData.note || 'No additional notes';

            document.getElementById('modal-payable-name-receipt').innerText = paymentData.payable_name || '';

            document.querySelector('.canva-section-watsapp').style.display = 'block';
 
            
            


            if (action === 'print') {
                $('#loader').hide();
                printModalContent('.canva-section-watsapp');
            } else if (action === 'share') {
                $('#loader').hide();
                shareSectionAsImagePaymentReceipt('.canva-section-watsapp', {
                    fileName: 'payment-receipt.png',
                    title: 'Payment Receipt',
                    text: 'Check out this payment receipt!'
                });
            }

        } catch (error) {
            console.error('Error fetching payment details:', error.message || error);
            $('#loader').hide();
            alert('An error occurred while fetching payment details.');
        }
    } else {
        $('#loader').hide();
        alert('Please enter a voucher ID');
    }
}


async function shareSectionAsImagePaymentReceipt(sectionSelector, options = {}) {
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
        
        section.style.display = 'none';
    }
}


function confirmDeletePayment(id) {
    if (confirm('Are you sure you want to delete this payment ?')) {
        document.getElementById('deleteForm-' + id).submit();
    }
}


