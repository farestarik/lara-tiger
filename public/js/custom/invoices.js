// Active NavBar

$("#invoicesList").addClass("active");

// Invoices App


// Create Page

let user_box = $("#user");
let user_address = $("textarea#user_address");
let user_phone = $("input#user_phone");

user_box.on('change', function(e) {
    let user_id = $(this).find(':selected').val();
    console.log(user_id);
    let user = getUser(user_id);
    user = JSON.parse(user);

    // Fill Inputs
    user_address.text(user.profile.address);
    user_phone.val(user.profile.phone);

});

function getUser(user_id = null) {
    if (user_id) {
        return $.ajax({
            url: "/dashboard/users/getSelected/" + user_id,
            type: "GET",
            data: { _token: token },
            async: false,
            success: function(response) {

            }
        }).responseText;
    }
}

function getProduct(product_id = null) {
    if (product_id) {
        let response = $.ajax({
            url: "/dashboard/products/getSelected/" + product_id,
            type: "GET",
            data: { _token: token },
            async: false,
        }).responseText;
        setTotal();
        updatePaid();
        return response;
    }
}


// Products Rows Creation


let i = 1;

$(document).on('click', '#add', function(e) {

    // let rows_len = $("#products_container tr").length;

    // let i = rows_len + 1;


    i++;

    let html = '';
    let product = '';

    html += `
        <tr id="row_${i}">
        <td>
            <select name="product[]" id="product${i}" class="form-control product" data-row-id="${i}">
                <option disabled="" selected="">~~SELECT~~</option>
                `;

    products_selections.forEach(function(product, index) {
        html += `<option value="${product.id}">${product.name}</option>`;
    });

    html += `
            </select>
        </td>
        <td>
            <input placeholder="${price_word}.." type="number" value="0" name="price[]" id="price${i}" class="form-control price" data-row-id='${i}'>
        </td>
        <td><input type="number" name="quantity[]" value="1" placeholder="${quantity_word}.." id="quantity${i}" data-row-id='${i}' class="form-control quantity"></td>
        <td><input type="number" name="total[]" required="" value="0" placeholder="${total_word}.." id="total${i}" data-row-id='${i}' readonly="" class="total form-control"></td>
        <td><button type="button" class="btn btn-danger remove" data-row-id="${i}">X</button></td>

    </tr>
        `;

    $("#products_container").append(html);
    run_products_filler();
    fix_rows();
});



// Fix Rows IDs

function fix_rows() {
    let rows_len = $("#products_container tr").length;
    let row = $("#products_container tr");
    console.log('Rows:' + rows_len);
    for (let i = 0; i < rows_len; i++) {
        x = (i + 1);
        console.log(i);
        $(row[i]).attr('id', 'row_' + x);
        // Fix Price
        let price = $(row[i]).find(".price");
        price.attr('data-row-id', x);
        price.attr('id', 'price' + x);

        // Fix Quantity
        let quantity = $(row[i]).find(".quantity");
        quantity.attr('data-row-id', x);
        quantity.attr('id', 'quantity' + x);

        // Fix Total
        let total = $(row[i]).find(".total");
        total.attr('data-row-id', x);
        total.attr('id', 'total' + x);

        // Fix Product
        let product = $(row[i]).find(".product");
        product.attr('data-row-id', x);
        product.attr('id', 'product' + x);

        // Fix Remove Button
        let remove = $(row[i]).find(".remove");
        if (remove.length > 1) {
            remove.attr('data-row-id', x);
        }

    }


}

// Products Price Filler

function run_products_filler() {
    let rows_len = $("#products_container tr").length;

    // console.log(rows_len);

    for (let i = 1; i <= rows_len; i++) {

        if (!$("#product" + i).length == 0) {
            $("#product" + i).on("change", function(e) {
                let product_id = $(this).find(":selected").val();
                let product = getProduct(product_id);
                product = JSON.parse(product);
                let quantity = parseInt($("#quantity" + i).val());
                let price = parseInt(product.stock.sell_price);
                $("#price" + i).val(price);
                let total = (quantity * price);
                $("#total" + i).val(total);
                $("#quantity" + i).on('change', function(e) {
                    let total = (quantity * price);
                    $("#total" + i).val(total);
                    setTotal();
                    update_all();
                });
            });
        }

    }

    // setTotal();
    // updatePaid();
}

run_products_filler();


// Remove Row

$(document).on('click', '.remove', function(e) {

    var row_id = $(this).attr('data-row-id');
    $("tr#row_" + row_id + "").remove();
    fix_rows();
    run_products_filler();
    setTotal();
    update_all();
});


$(document).on('change keyup', '.quantity', function(e) {
    let row_id = $(this).attr('data-row-id');
    let row_quantity = $("#quantity" + row_id).val();
    let row_price = $("#price" + row_id).val();
    let total = (parseInt(row_quantity) * parseInt(row_price));
    $("#total" + row_id).val(total);
    setTotal();
    update_all();
});

$(document).on('change keyup', '.price', function(e) {
    let row_id = $(this).attr('data-row-id');
    let row_quantity = $("#quantity" + row_id).val();
    let row_price = $("#price" + row_id).val();
    let total = (parseInt(row_quantity) * parseInt(row_price));
    $("#total" + row_id).val(total);
    setTotal();
    update_all();
});

$(document).on('change', '.product', function(e) {
    setTotal();
    update_all();
});


// Tax Percentage

$(document).on("change keyup", '#tax_percent', function(e) {
    let total_price = parseInt(getTotal()) - parseInt($("#discount_value").val());
    let tax = (Number(total_price) / 100) * $(this).val();
    tax = parseFloat(tax.toFixed(2));
    $("#total_price").val(parseInt(total_price) + parseInt(tax));
    $("#tax_value").attr('value', tax);
    updatePaid();
});

function updateTax() {
    let total_price = parseInt(getTotal()) - parseInt($("#discount_value").val());
    let tax = (Number(total_price) / 100) * $("#tax_percent").val();
    tax = parseFloat(tax.toFixed(2));
    $("#total_price").val(parseInt(total_price) + parseInt(tax));
    $("#tax_value").attr('value', tax);
}

// Discount Percentage

$(document).on("change keyup", '#discount_percent', function(e) {
    let total_price = parseInt(getTotal()) + parseInt($("#tax_value").val());
    let discount = (Number(total_price) / 100) * $(this).val();
    discount = parseFloat(discount.toFixed(2));
    $("#total_price").val(parseInt(total_price) - parseInt(discount));
    $("#discount_value").attr('value', discount);
    updatePaid();
});

function updateDiscount() {
    let total_price = parseInt(getTotal()) + parseInt($("#tax_value").val());
    let discount = (Number(total_price) / 100) * $("#discount_percent").val();
    discount = parseFloat(discount.toFixed(2));
    $("#total_price").val(parseInt(total_price) - parseInt(discount));
    $("#discount_value").attr('value', discount);
}

// Paid & Due

$(document).on("change keyup", '#paid', function(e) {
    let paid = $("#paid").val();
    let total_price = $("#total_price").val();
    paid = parseInt(paid);
    total_price = parseInt(total_price);
    let due = total_price - paid;
    $("#due").attr('value', due);
});

function updatePaid() {
    let paid = $("#paid").val();
    let total_price = $("#total_price").val();
    paid = parseInt(paid);
    total_price = parseInt(total_price);
    let due = total_price - paid;
    $("#due").attr('value', due);
}


// Update All Fields

function update_all() {
    updateTax();
    updateDiscount();
    updatePaid();
}

// Set Total Amount,Price

function setTotal() {

    let price_sum = 0;

    $(".total").each(function() {
        let total = parseInt($(this).val());
        total = Number(total);
        price_sum += Number(parseFloat(total));
    });



    let price = price_sum;

    let quantity_sum = 0;
    $(".quantity").each(function() {
        let quantity = parseInt($(this).val());
        quantity_sum += parseFloat(quantity);
    });

    let amount = quantity_sum;

    $("#total_amount").val(amount);
    $("#total_price").val(price);
}

// Get Total Amount,Price

function getTotal(target = 'price') {
    let price_sum = 0;
    $(".total").each(function() {
        let total = $(this).val();
        price_sum += parseFloat(total);
    });

    let price = price_sum;

    let quantity_sum = 0;
    $(".quantity").each(function() {
        let quantity = $(this).val();
        quantity_sum += parseFloat(quantity);
    });

    let amount = quantity_sum;

    if (target == 'price') {
        return price;
    } else if (target == 'amount') {
        return amount;
    }
}


// Print Page

$("#print").on('click', function() {
    var p = document.getElementById('products_container');
    var w = window.open('', '', 'width=900,height=700');
    w.document.write(p.outerHTML);
    e.document.close();
    w.focus();
    w.print();
    w.close();
});