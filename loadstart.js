
$(function() {
    $.ajax({
        type: "GET",
        url: "php/categories/"
    }).done(function(categories) {
        window.categories = categories;
        $.each(categories, function (key, value) {
            $("#myModal #inputSelectCategory").append(
                "<option value=" + value.product_id + ">" + value.category + "</option>"
            );
        });


        $.ajax({
            type: "GET",
            url: "php/products/"
        }).done(function(products) {
            for(var index in products) {
                insertRow(products[index]);
            }
        });

        var add = $(".table .add");
        bindElement(add, "add" ,{
            id : 0,
            name: "",
            category: 0,
            weight: ""
        });

        function bindElement(element, action, product){
            element.click( {product: product, action: action}, function(e) {
                var product = e.data.product;
                var action = e.data.action;
                modalAction(action,product);
            });
        }


        function modalAction(action,option){
            if (action == "edit" || action == "add") {
                loadDataModal(option);
                $('#myModal form').unbind( "submit" );
                $('#myModal form').submit(function(e) {
                    var data = $(this).serialize();
                    sendAction(action, data);
                    return false;
                });
                $('#myModal').modal('show');
            }
            else {
                $('#myModalDel form').unbind( "submit" );
                $('#myModalDel form').submit({option: option},function(e) {
                    var data = $.param( e.data.option );
                    sendAction(action, data );
                    return false;
                });
                $('#myModalDel').modal('show');
            }
        }

        function sendAction(action, data){
             switch (action){
                 case "edit":
                     $.ajax({
                         type: "PUT",
                         url: "php/products/",
                         data: data
                     }).done(function(data) {
                             updateRow(data);
                             $('#myModal').modal('hide');
                         });
                     break;
                 case "del":
                     $.ajax({
                         type: "DELETE",
                         url: "php/products/",
                         data: data
                     }).done(function(data) {
                             if (data)
                                deleteRow(data);
                             $('#myModalDel').modal('hide');
                         });
                     break;
                 case "add":
                     $.ajax({
                         type: "POST",
                         url: "php/products/",
                         data: data
                     }).done(function(data) {
                             insertRow(data);
                             $('#myModal').modal('hide');
                         });
                     break;
             }
        }
        function loadDataModal(data){
            $("#myModal #inputTextId").val(data.id);
            $("#myModal #inputTextName").val(data.name);
            $("#myModal #inputSelectCategory option:selected").removeAttr("selected");
            $("#myModal #inputSelectCategory option[value=" + data.category + "]").attr("selected", "selected");
            $("#myModal #inputTextWeight").val(data.weight);
        }
        function updateRow(option){
            var category = getCategoryById(option.category);
            var el = $(".table tbody #table-row-" + option.id );
            el.find('.table-row-id').text(option.id);
            el.find('.table-row-name').text(option.name);
            el.find('.table-row-category').text(category);
            el.find('.table-row-weight').text(option.weight);
            var el = el.find(".edit");
            bindElement(el, "edit", option);

        }
        function deleteRow(option){
            var el = $(".table tbody #table-row-" + option.id).remove();
        }

        function getCategoryById (id) {
            var category = "";
            for(var index in window.categories) {
                if (window.categories[index].id == id )  {
                    category = window.categories[index].category;
                    break;
                }
            }
            return category;
        }
        function insertRow(data){
            var category = getCategoryById(data.category);
            var append = $(".table tbody").append(
                "<tr id='table-row-" + data.id + "'>" +
                    "<td class='table-row-id'>" + data.id + "</td>" +
                    "<td class='table-row-name'>" + data.name + "</td>"  +
                    "<td class='table-row-category'>" + category + "</td>" +
                    "<td class='table-row-weight'>" + data.weight + "</td>" +
                    "<td>" +
                    "<button type='button' class='btn btn-danger del'>Удалить</button>"  + " " +
                    "<button type='button' class='btn btn-primary edit'>Редактировать</button>" +
                    "</td>"  +
                "</tr>");
            var edit = $(append[0].lastChild).find(".edit");
            bindElement(edit, "edit" ,data);
            var del = $(append[0].lastChild).find(".del");
            bindElement(del, "del" ,data);
        }
    });
});
