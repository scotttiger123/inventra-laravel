<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>POS</title>
    <meta
      content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no"
      name="viewport"
    />
    <link rel="stylesheet" href="in.css" />
    <link
      rel="stylesheet"
      href="../../bower_components/bootstrap/dist/css/bootstrap.min.css"
    />
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css"
    />

    <link rel="stylesheet" href="in.css" />
    <link
      rel="stylesheet"
      href="../../bower_components/font-awesome/css/font-awesome.min.css"
    />

    <link
      rel="stylesheet"
      href="../../bower_components/Ionicons/css/ionicons.min.css"
    />
    <link
      rel="stylesheet"
      href="../../bower_components/bootstrap-daterangepicker/daterangepicker.css"
    />
    <link
      rel="stylesheet"
      href="../../bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css"
    />
    <link rel="stylesheet" href="../../plugins/iCheck/all.css" />
    <link
      rel="stylesheet"
      href="../../bower_components/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css"
    />
    <link
      rel="stylesheet"
      href="../../plugins/timepicker/bootstrap-timepicker.min.css"
    />

    <link
      rel="stylesheet"
      href="../../bower_components/select2/dist/css/select2.min.css"
    />
    <link rel="stylesheet" href="../../dist/css/AdminLTE.min.css" />
    <link rel="stylesheet" href="../../dist/css/skins/_all-skins.min.css" />

    <link
      rel="stylesheet"
      href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic"
    />
  </head>
  <body>
    <div class="main">
    <div class="responsive">
      <div class="table-responsive transaction-list">
        <div class="fixed">
       <div class="select">
        <select style="width: 22vw;" id="options" name="option">
  <option value="option1">Option 1</option>
  <option value="option2">Option 2</option>
  <option value="option3">Option 3</option>
  <option value="option4">Option 4</option>
</select>
<button><i class="bi bi-plus-lg"></i> New Customer</button>
       </div>
       <div class="shop">
        <button><i class="bi bi-bag"></i> Shopping</button>
       </div>
       </div>
        <table
          id="myTable"
          class="table table-hover table-striped order-list table-fixed"
          style="
            color: rgb(33, 65, 65);
            background-color: rgb(235, 235, 243);
            width: 98%;
            border-collapse: collapse;
          "
        >
          <thead class="d-none d-md-block">
            <tr>
              <th class="col-sm-5 col-6">Product</th>
              <th class="col-sm-2" style="text-align: center">Price</th>
              <th class="col-sm-3" style="text-align: center">Quantity</th>
              <th class="col-sm-2" style="text-align: center">SubTotal</th>
            </tr>
          </thead>
          <tbody 
            style="background-color: rgb(242, 245, 248) "
            id="tbody-id"
          ></tbody>
        </table>
      </div>

       <div class="col">
        <div class="btns">
          <div class="menu"><i class="bi bi-menu-button-wide"></i></div>
<select id="options" name="option">
  <option value="option1">Option 1</option>
  <option value="option2">Option 2</option>
  <option value="option3">Option 3</option>
  <option value="option4">Option 4</option>
</select>
 <div class="search">
  <input type="search" placeholder="Search Product">
  <i class="bi bi-search"></i>
</div>
<div class="menu" style="background-color: gold;"><i class="bi bi-menu-button-wide"></i></div>

        </div>
        <div class="scroll">
        <table
          border="1"
          style="
            cursor: pointer;
            border-color: rgb(223, 211, 211);
            border-collapse: collapse;
          "
          class="tab"
        >
          <tr style="font-size: 14px; text-align: center">
            <td class="product-cell" data-product="Laptop 2024">
              <img src="lapt.jpg" alt="" />Laptop 2024
              <p>Rs.20</p>
            </td>

            <td class="product-cell" data-product="Laptop 2024">
              <img src="lapt.jpg" alt="" />Laptop 2024
              <p>Rs.300</p>
            </td>
            <td class="product-cell" data-product="Laptop 2024">
              <img src="lapt.jpg" alt="" />Laptop 2024
              <p>Rs.300</p>
            </td>
            <td class="product-cell" data-product="Laptop 2024">
              <img src="lapt.jpg" alt="" />Laptop 2024
              <p>Rs.300</p>
            </td>
            <td class="product-cell" data-product="Laptop 2024">
              <img src="lapt.jpg" alt="" />Laptop 2024
              <p>Rs.2000</p>
            </td>
            <td class="product-cell" data-product="Laptop 2024" d>
              <img src="lapt.jpg" alt="" />Laptop 2024
              <p>Rs.5</p>
            </td>
          </tr>
          <tr style="font-size: 14px; text-align: center">
            <td class="product-cell" data-product="Laptop 2024">
              <img src="lapt.jpg" alt="" />Laptop 2024
              <p>Rs.20</p>
            </td>

            <td class="product-cell" data-product="Laptop 2024">
              <img src="lapt.jpg" alt="" />Laptop 2024
              <p>Rs.300</p>
            </td>
            <td class="product-cell" data-product="Laptop 2024">
              <img src="lapt.jpg" alt="" />Laptop 2024
              <p>Rs.300</p>
            </td>
            <td class="product-cell" data-product="Laptop 2024">
              <img src="lapt.jpg" alt="" />Laptop 2024
              <p>Rs.300</p>
            </td>
            <td class="product-cell" data-product="Laptop 2024">
              <img src="lapt.jpg" alt="" />Laptop 2024
              <p>Rs.2000</p>
            </td>
            <td class="product-cell" data-product="Laptop 2024" d>
              <img src="lapt.jpg" alt="" />Laptop 2024
              <p>Rs.5</p>
            </td>
          </tr>
          <tr style="font-size: 14px; text-align: center">
            <td class="product-cell" data-product="Laptop 2024">
              <img src="lapt.jpg" alt="" />Laptop 2024
              <p>Rs.20</p>
            </td>

            <td class="product-cell" data-product="Laptop 2024">
              <img src="lapt.jpg" alt="" />Laptop 2024
              <p>Rs.300</p>
            </td>
            <td class="product-cell" data-product="Laptop 2024">
              <img src="lapt.jpg" alt="" />Laptop 2024
              <p>Rs.300</p>
            </td>
            <td class="product-cell" data-product="Laptop 2024">
              <img src="lapt.jpg" alt="" />Laptop 2024
              <p>Rs.300</p>
            </td>
            <td class="product-cell" data-product="Laptop 2024">
              <img src="lapt.jpg" alt="" />Laptop 2024
              <p>Rs.2000</p>
            </td>
            <td class="product-cell" data-product="Laptop 2024" d>
              <img src="lapt.jpg" alt="" />Laptop 2024
              <p>Rs.5</p>
            </td>
          </tr>
          <tr style="font-size: 14px; text-align: center">
            <td class="product-cell" data-product="Laptop 2024">
              <img src="lapt.jpg" alt="" />Laptop 2024
              <p>Rs.20</p>
            </td>

            <td class="product-cell" data-product="Laptop 2024">
              <img src="lapt.jpg" alt="" />Laptop 2024
              <p>Rs.300</p>
            </td>
            <td class="product-cell" data-product="Laptop 2024">
              <img src="lapt.jpg" alt="" />Laptop 2024
              <p>Rs.300</p>
            </td>
            <td class="product-cell" data-product="Laptop 2024">
              <img src="lapt.jpg" alt="" />Laptop 2024
              <p>Rs.300</p>
            </td>
            <td class="product-cell" data-product="Laptop 2024">
              <img src="lapt.jpg" alt="" />Laptop 2024
              <p>Rs.2000</p>
            </td>
            <td class="product-cell" data-product="Laptop 2024" d>
              <img src="lapt.jpg" alt="" />Laptop 2024
              <p>Rs.5</p>
            </td>
          </tr>
          <tr style="font-size: 14px; text-align: center">
            <td class="product-cell" data-product="Laptop 2024">
              <img src="lapt.jpg" alt="" />Laptop 2024
              <p>Rs.20</p>
            </td>

            <td class="product-cell" data-product="Laptop 2024">
              <img src="lapt.jpg" alt="" />Laptop 2024
              <p>Rs.300</p>
            </td>
            <td class="product-cell" data-product="Laptop 2024">
              <img src="lapt.jpg" alt="" />Laptop 2024
              <p>Rs.300</p>
            </td>
            <td class="product-cell" data-product="Laptop 2024">
              <img src="lapt.jpg" alt="" />Laptop 2024
              <p>Rs.300</p>
            </td>
            <td class="product-cell" data-product="Laptop 2024">
              <img src="lapt.jpg" alt="" />Laptop 2024
              <p>Rs.2000</p>
            </td>
            <td class="product-cell" data-product="Laptop 2024" d>
              <img src="lapt.jpg" alt="" />Laptop 2024
              <p>Rs.5</p>
            </td>
          </tr>
        </table>
        </div>
</div>
    </div>
    <div class="summary">
      <div class="scontent">
        <p>Items <span>0</span></p>
        <p style="margin-left: 2.5rem">Total <span>0.00</span></p>
      
      </div>
      <div" class="g-total">
        <strong class="totals-title">Grand Total </strong
        ><strong id="grand-total">0.00</strong>
      </div>    
    </div>
    <div class="order">
      <button>PLACE ORDER</button>
      <button><i class="bi bi-arrow-up-left"></i></button>
      <button><i class="bi bi-x-lg"></i></button>
    </div>
    </div>
    <script>
      document.querySelectorAll(".product-cell").forEach((cell) => {
        const priceText = cell.querySelector("p").innerText;
        const priceValue = priceText.replace("Rs.", "").trim(); // Remove 'Rs.' and any spaces
        cell.setAttribute("data-price", priceValue); // Set data-price to the cleaned-up price value
      });
      document.querySelectorAll(".product-cell").forEach((cell) => {
        cell.addEventListener("click", function () {
          // Get the product details from the clicked cell
          const productName = this.getAttribute("data-product");
          const productPrice = parseFloat(this.getAttribute("data-price"));
          const quantity = 1;
          const subtotal = productPrice * quantity;

          // Create a new row to add to the table
          const newRow = document.createElement("tr");

          newRow.innerHTML = `
    <td style="text-align: left; vertical-align: middle; padding: 8px;">${productName}</td>

    <td style="text-align: center; vertical-align: middle; padding: 8px;">${productPrice.toFixed(
      2
    )}</td>
    <td style="text-align: center; vertical-align: middle; padding: 8px;">
        <div style="display: flex; align-items: center; justify-content: center;">
            <button type="button" class="quantity-btn" data-action="decrease" style="padding: 0px 10px; font-size: 24px; border:none; background-color: rgb(212, 230, 227); border-radius:6px">-</button>
         <input type="text" class="quantity-input" value="${quantity}" min="1" style="width: 50px; text-align: center; padding: 5px; margin: 0 5px; font-size: 16px; border: 1px solid lightblue;">

            <button type="button" class="quantity-btn" data-action="increase" style="padding: 0px 10px; font-size: 24px; border:none; background-color: rgb(212, 230, 227);border-radius:6px" >+</button>
        </div>
    </td>
    <td class="subtotal" style="text-align: center; vertical-align: middle; padding: 8px;">${subtotal.toFixed(
      2
    )}</td>
    <td style="text-align: center; vertical-align: middle; padding: 8px;">
        <button class="delete-btn" style="padding: 4px 6px; font-size: 16px;border-radius:6px"><i class="bi bi-trash3"></i></button>
    </td>
`;

          // Append the new row to the table body
          const tbody = document.getElementById("tbody-id");
          tbody.appendChild(newRow);

          // Add event listener to the delete button
          const deleteButton = newRow.querySelector(".delete-btn");
          deleteButton.addEventListener("click", function () {
            // Remove the row when the delete button is clicked
            tbody.removeChild(newRow);
            // Update the grand total after deleting the row
            updateGrandTotal();
            updateTotals();
          });

          // Add event listeners to the quantity buttons
          const quantityInput = newRow.querySelector(".quantity-input");
          const increaseBtn = newRow.querySelector('[data-action="increase"]');
          const decreaseBtn = newRow.querySelector('[data-action="decrease"]');

          increaseBtn.addEventListener("click", function (event) {
            event.preventDefault(); // Prevent the default action of the button
            let currentQuantity = parseInt(quantityInput.value);
            quantityInput.value = currentQuantity + 1;
            updateSubtotalAndTotals();
          });

          decreaseBtn.addEventListener("click", function (event) {
            event.preventDefault(); // Prevent the default action of the button
            let currentQuantity = parseInt(quantityInput.value);
            if (currentQuantity > 1) {
              quantityInput.value = currentQuantity - 1;
              updateSubtotalAndTotals();
            }
          });

          // Add event listener to quantity input to update subtotal
          quantityInput.addEventListener("input", function () {
            updateSubtotalAndTotals();
          });

          // Update the grand total and totals after adding a new product
          updateGrandTotal();
          updateTotals();
        });
      });

      function updateSubtotalAndTotals() {
        const rows = document.querySelectorAll("#tbody-id tr");
        rows.forEach((row) => {
          const price = parseFloat(row.cells[1].textContent);
          const quantity = parseInt(row.querySelector(".quantity-input").value);
          const subtotal = price * quantity;
          row.querySelector(".subtotal").textContent = subtotal.toFixed(2);
        });

        updateGrandTotal();
        updateTotals();
      }

      function updateGrandTotal() {
        const rows = document.querySelectorAll("#tbody-id tr");
        let grandTotal = 0;

        rows.forEach((row) => {
          const subtotal = parseFloat(
            row.querySelector(".subtotal").textContent
          );
          grandTotal += subtotal;
        });

        // Update the grand total
        document.getElementById("grand-total").textContent =
          grandTotal.toFixed(2);
      }

      function updateTotals() {
        const rows = document.querySelectorAll("#tbody-id tr");
        const itemCount = rows.length;
        let total = 0;

        rows.forEach((row) => {
          const price = parseFloat(row.cells[1].textContent);
          const quantity = parseInt(row.querySelector(".quantity-input").value);
          total += price * quantity;
        });

        document.getElementById(
          "item"
        ).textContent = `${itemCount} (${total.toFixed(2)})`;
        document.getElementById("subtotal").textContent = total.toFixed(2);
      }

      function updateGrandTotal() {
        const rows = document.querySelectorAll("#tbody-id tr");
        let grandTotal = 0;

        rows.forEach((row) => {
          const subtotal = parseFloat(
            row.querySelector(".subtotal").textContent
          );
          grandTotal += subtotal;
        });

        // Update the grand total
        document.querySelector(".summary .scontent p span").textContent =
          grandTotal.toFixed(2);
      }

      function updateTotals() {
        const rows = document.querySelectorAll("#tbody-id tr");
        const itemCount = rows.length;
        let total = 0;

        rows.forEach((row) => {
          const price = parseFloat(row.cells[1].textContent);
          const quantity = parseInt(row.querySelector(".quantity-input").value);
          total += price * quantity;
        });

        // Update the Items and Total in summary
        document.querySelector(".summary .scontent p span").textContent =
          itemCount;
        document.querySelector(
          ".summary .scontent p:nth-child(2) span"
        ).textContent = total.toFixed(2);
      }

      document.querySelectorAll(".product-cell").forEach((cell) => {
        cell.addEventListener("click", function () {
          // Get product details and add the product to the table as already implemented
          // (code for adding the row here...)

          // Call update functions to refresh summary
          updateGrandTotal();
          updateTotals();
        });
      });

      // When the delete button is clicked
      const deleteButton = newRow.querySelector(".delete-btn");
      deleteButton.addEventListener("click", function () {
        // Remove the row when the delete button is clicked
        tbody.removeChild(newRow);

        // Update totals after deleting the row
        updateGrandTotal();
        updateTotals();
      });

      increaseBtn.addEventListener("click", function (event) {
        event.preventDefault(); // Prevent the default action
        let currentQuantity = parseInt(quantityInput.value);
        quantityInput.value = currentQuantity + 1;

        // Update totals after quantity change
        updateSubtotalAndTotals();
        updateGrandTotal();
        updateTotals();
      });

      decreaseBtn.addEventListener("click", function (event) {
        event.preventDefault(); // Prevent the default action
        let currentQuantity = parseInt(quantityInput.value);
        if (currentQuantity > 1) {
          quantityInput.value = currentQuantity - 1;

          // Update totals after quantity change
          updateSubtotalAndTotals();
          updateGrandTotal();
          updateTotals();
        }
      });
      function updateGrandTotal() {
        const rows = document.querySelectorAll("#tbody-id tr");
        let grandTotal = 0;

        rows.forEach((row) => {
          const subtotal = parseFloat(
            row.querySelector(".subtotal").textContent
          );
          grandTotal += subtotal;
        });

        // Update the grand total
        document.getElementById("grand-total").textContent =
          grandTotal.toFixed(2); // Ensure the grand total is updated
      }
    </script>
  </body>
</html>
<style>
  body {
  font-family: "Inter", sans-serif;
}
.bootstrap-select-sm .btn {
  font-size: 13px;
  padding: 3px 25px 3px 10px;
  height: 30px !important;
}
.main {
  padding-inline: 2rem;
}
.minus,
.plus {
  padding: 0.35rem 0.75rem;
}
.numkey.qty {
  font-size: 13px;
  padding: 0 0;
  max-width: 50px;
  text-align: center;
}
.sub-total {
  font-weight: 500;
}
.flex {
  display: flex;
}
.tab {
  height: 1000px;
  width: 100%;
}
.scroll {
  overflow-y: auto;
  max-height: 650px;
  margin-top: 3rem;
  width: 100%;
}
.scroll::-webkit-scrollbar {
  width: 6px;
}

.scroll::-webkit-scrollbar-thumb {
  background-color: gold;
  border-radius: 10px;
}

.col-sm-4 {
  background-color: rgb(189, 228, 189);
  padding: 1rem;
  text-align: center;
  color: black;
  font-size: 3rem;
}
img {
  width: 65px;
  height: 65px;
  margin-left: auto;
  margin-right: auto;
  display: flex;
  flex-direction: column;
}
td img {
  display: flex;
  flex-direction: column;
  align-items: center;
  text-align: center;
  padding: 5px 10px;
  margin-top: -2rem;
}
.pos-page .container-fluid {
  padding: 0 15px;
}
.pos-page .side-navbar {
  top: 0;
}
section.pos-section {
  padding: 10px 0;
}
.pos-page .table-fixed {
  margin-bottom: 0;
}
.pos-text {
  line-height: 1.8;
}
.pos-page section header {
  padding: 0 0 5px;
}
.pos .bootstrap-select button {
  padding-right: 21px !important;
}
.pos .bootstrap-select.form-control:not([class*="col-"]) {
  width: 100px;
}
.pos-page .order-list .btn {
  padding: 2px 5px;
}
.pos-page [class="row"] {
  margin-left: -10px;
  margin-right: -10px;
}
.pos-page [class*="col-"] {
  padding: 0 10px;
}
.pos-page #myTable [class*="col-"] {
  padding: 0.5rem;
}
.pos-page #myTable tr th {
  background: #f5f6f7;
  color: #5e5873;
}
.product-btns {
  margin: 5px -5px;
}
.edit-product {
  white-space: break-spaces;
  font-size: 13px;
  font-weight: 500;
  text-align: left;
  padding: 0 0 !important;
}
.edit-product i {
  color: #00cec9;
}
.product-title span {
  font-size: 12px;
}
.more-options {
  box-shadow: -5px 0px 10px 0px rgba(44, 44, 44, 0.3);
  font-size: 12px;
  margin: 10px 0;
  padding-left: 3px;
  padding-right: 3px;
}
label {
  font-size: 13px;
}
#tbody-id tr td {
  font-size: 13px;
  padding: 0;
}
table,
tr,
td {
  border-collapse: collapse;
}
table {
  margin-top: 1rem;
}
.top-fields {
  margin-top: 10px;
  position: relative;
}
.top-fields label {
  background: #fff;
  font-size: 11px;
  margin-left: 10px;
  padding: 0 3px;
  position: absolute;
  top: -8px;
  z-index: 9;
}
.top-fields input,
.top-fields .btn {
  font-size: 13px;
  height: 37px;
  border-radius: 6px !important;
}
.product-grid {
  display: flex;
  flex-wrap: wrap;
  padding: 0;
  margin: 0;
  width: 100%;
}
.product-grid > div {
  border: 1px solid #e4e6fc;
  overflow: hidden;
  padding: 0.5rem;
  position: relative;
  max-width: 300px;
  min-width: 100px;
  vertical-align: top;
  width: calc(100% / 4);
}
.product-grid > div p {
  color: #5e5873;
  font-size: 12px;
  font-weight: 500;
  margin: 10px 0 0;
  min-height: 36px;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  overflow: hidden;
  text-overflow: ellipsis;
  -webkit-box-orient: vertical;
}
.product-grid > div span {
  font-size: 12px;
}
.more-payment-options.column-5 {
  margin: 0;
  padding: 0;
}
.ui-helper-hidden-accessible {
  display: none;
}
#print-layout {
  padding: 0 0;
  margin: 0 0;
}
.category-img p,
.brand-img p {
  color: #5e5873;
  font-size: 12px;
  font-weight: 500;
}
.brand-img,
.category-img {
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
}
.brand-img img {
  max-width: 70%;
}
.load-more {
  margin-top: 15px;
}
.load-more:disabled {
  opacity: 0.5;
}
.ui-helper-hidden-accessible {
  display: none !important;
}
.btns {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 1rem;
  margin-top: 3rem;
}
.search {
  position: relative;
  width: 60%;
  border: 1px solid rgb(171, 165, 165);
}
.search input {
  width: 100%;
  padding: 1rem;
  background-color: white;
  border: none;
}
.search i {
  font-size: 20px;
}
.select {
  display: flex;
  align-items: center;
  gap: 6px;
  width: 100%;
}
.shop {
  margin-top: 10px;
}
.shop button {
  background-color: black;
  color: white;
  font-weight: 700;
  padding: 6px 16px;
  border-radius: 6px;
}
.select button {
  background-color: gold;
  border-radius: 6px;
  font-weight: 700;
  width: 135px;
  height: 40px;
}
.col {
  width: 100%;
  display: flex;
  flex-direction: column;
  margin-top: 3rem;
}
#options {
  width: 30%;
  padding: 1rem;
  background-color: rgb(244, 250, 250);
  border-color: rgb(171, 165, 165);
}
#options:focus {
  background-color: white;
  border-color: black;
}
.menu {
  border: 1px solid gray;
  height: 42px;
  width: 42px;
  border-radius: 6px;
  text-align: center;
  align-content: center;
  cursor: pointer;
}
.search i {
  position: absolute;
  right: 1rem;
  top: 50%;
  transform: translateY(-50%);
}
.menu i {
  font-size: 16px;
}
.responsive {
  display: flex;
  flex-direction: row-reverse;
  gap: 2rem;
  align-items: center;
}

.delete-btn {
  background-color: crimson;
  color: white;
  border: none;
  padding: 5px 10px;
  cursor: pointer;
  transition: all 0.2s;
}
.delete-btn:hover {
  background-color: rgb(186, 5, 41);
}
.fixed {
  position: fixed;
  margin-top: -8.5rem;
}
.table-responsive {
  overflow-y: auto;
  width: 50%;
  height: 490px;
}
.table-responsive::-webkit-scrollbar {
  width: 6px;
}
.table-responsive::-webkit-scrollbar-thumb {
  background-color: gold;
  border-radius: 10px;
}

.summary {
  width: 34%;
  background-color: black;
  color: white;
  float: right;
  margin-top: -13rem;
}
.g-total {
  font-size: 2rem;
  margin-left: 1rem;
}
.order {
  display: flex;
  align-self: center;
  justify-content: flex-end; /* Moves items to the right */
  gap: 6px;
  width: 100%;
  margin-top: -4rem;
  margin-left: -2rem;
}

.order button:first-child {
  background-color: gold;
  font-weight: 700;
  border: none;
  border-radius: 6px;
  width: 27%;
  height: 40px;
}
.order button:nth-child(2) {
  background-color: black;
  color: white;
  font-weight: 700;
  border: none;
  border-radius: 6px;
  width: 40px;
  height: 40px;
}
.order button:nth-child(3) {
  background-color: crimson;
  color: white;
  font-weight: 700;
  border: none;
  border-radius: 6px;
  width: 40px;
  height: 40px;
}
.scontent {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 2px 10px;
}
.scontent p {
  font-size: 1.8rem;
  margin-top: 10px;
}
.scontent span {
  margin-left: 2rem;
}

@media (max-width: 500px) {
  .product-grid > div {
    width: calc(100% / 3);
  }
}
@media (max-width: 375px) {
  .product-grid > div {
    width: calc(100% / 2);
  }
}
@media all and (max-width: 767px) {
  section.pos-section {
    padding: 0 5px;
  }
  nav.navbar {
    margin: 0 -10px;
  }
  .pos-form {
    padding: 0 0 !important;
  }
  .payment-options {
    padding: 5px 0;
  }
  .payment-options .column-5 {
    margin: 5px 0;
  }
  .payment-options .btn-sm {
    font-size: 12px;
  }
  .more-payment-options,
  .more-payment-options .btn-group {
    width: 100%;
  }
  .more-payment-options.column-5 {
    padding: 0 5px;
  }
  .product-btns {
    margin: 0 -15px 10px -15px;
  }
  .product-btns .btn {
    font-size: 12px;
  }
  .more-options {
    margin-top: 0;
  }
  .transaction-list {
    height: 35vh;
  }
  .filter-window {
    position: fixed;
  }
}

@media print {
  .hidden-print {
    display: none !important;
  }
}

#print-layout * {
  font-size: 10px;
  line-height: 20px;
  font-family: "Ubuntu", sans-serif;
  text-transform: capitalize;
}
#print-layout .btn {
  padding: 7px 10px;
  text-decoration: none;
  border: none;
  display: block;
  text-align: center;
  margin: 7px;
  cursor: pointer;
}

#print-layout .btn-info {
  background-color: #999;
  color: #fff;
}

#print-layout .btn-primary {
  background-color: #6449e7;
  color: #fff;
  width: 100%;
}
#print-layout td,
#print-layout th,
#print-layout tr,
#print-layout table {
  border-collapse: collapse;
}
#print-layout tr {
  border-bottom: 1px dotted #ddd;
}
#print-layout td,
#print-layout th {
  padding: 7px 0;
  width: 50%;
}

#print-layout table {
  width: 100%;
}

#print-layout .centered {
  display: block;
  text-align: center;
  align-content: center;
}
#print-layout small {
  font-size: 10px;
}
@media print {
  #print-layout * {
    font-size: 10px !important;
    line-height: 20px;
  }
  #print-layout table {
    width: 100%;
    margin: 0 0;
  }
  #print-layout td,
  #print-layout th {
    padding: 5px 0;
  }
}
@media (min-width: 992px) {
  .col-md-7 {
    width: 100%;
  }
  #tbody-id tr td {
    font-size: 16px;
    padding: 0;
  }
}
@media (max-width: 1200px) {
  .responsive {
    display: flex;
    flex-direction: column-reverse;
  }
  .scroll {
    overflow-y: auto;
    max-height: 500px;
    float: right;
    margin-top: 6rem;
  }
  .table-responsive {
    overflow-y: auto;
    width: 100%;
    margin-top: 4rem;
    height: 400px;
  }
  .paybtns {
    display: flex;
    flex-wrap: wrap;
  }
}
@media (max-width: 540px) {
  .tab {
    width: 350px;
    height: 850px;
  }
  .table-responsive {
    overflow-y: auto;
    width: 100%;
    margin-top: 4rem;
    height: 400px;
  }
  .totals {
    width: 95vw;
  }

  #tbody-id tr td {
    font-size: 10px;
    padding: 0;
  }
  .col-sm-4 {
    font-size: 2rem;
  }
}

</style>