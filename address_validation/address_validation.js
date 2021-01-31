function start()
{
    $("#divEditInfo").hide();
    $("#addButton").click(add);
    $("#saveButton").click(save);
    $("#cancelButton").click(cancel);
    loadAddress();
}

function add()
{
    $("#updateButton").hide();
    $("#saveButton").show();
    $("#divEditInfo").show();
}

function valid()
{
    var isValid = 0;
    $(".info").each(function() {
        var e = $(this);
        var regex = new RegExp(e.attr("data-regex"));
        if(!regex.test(e[0].value)) {
            $("#"+e.attr("data-err")).text("*Invalid form");
            $("#"+e.attr("id")).val("");
            isValid++;
        }
    });

    return (isValid === 0);
}

function empty()
{
    var isValid = 0;
    $(".inErr").text("");
    $(".info").each(function() {
        var e = $(this);
        if (e.val() == "") {
            $("#"+e.attr("data-err")).text("*Field is empty");
            isValid++;
        }
    });

    return (isValid === 0);
}

function save()
{
    if(empty() && valid())
    {
        reqData = {
            firstname: $("#fname")[0].value,
            lastname: $("#lname")[0].value,
            street: $("#street")[0].value,
            city: $("#city")[0].value,
            state: $("#state")[0].value,
            zip: $("#zip")[0].value,
            phone: $("#phone")[0].value
        }
        
        var data_form = JSON.stringify(reqData);

        $.ajax({
            url: "http://andrewy.sgedu.site/address_validation/API/addresses/create.php",
            type : "POST",
            contentType : 'application/json',
            data : data_form,
            success : function(result) {
                // product was created, go back to products list
                loadAddress();
            },
            error: function(xhr, resp, text) {
                // show error to console
                console.log(xhr, resp, text);
            }
        });  
        
        $(".info").val("");
    }
}


function del(evt)
{
    var address_id = evt.target.getAttribute("data-id");
        // send delete request to api / remote server
    $.ajax({
        url: "http://andrewy.sgedu.site/address_validation/API/addresses/delete.php",
        type : "POST",
        dataType : 'json',
        data : JSON.stringify({ id: address_id }),
        success : function(result) {
    
            // re-load list of products
            loadAddress();
        },
        error: function(xhr, resp, text) {
            console.log(xhr, resp, text);
        }
    });

}

function update(evt)
{
    if(empty() && valid())
    {
        var iid = evt.data.id;
        reqData = {
            id: iid,
            firstname: $("#fname")[0].value,
            lastname: $("#lname")[0].value,
            street: $("#street")[0].value,
            city: $("#city")[0].value,
            state: $("#state")[0].value,
            zip: $("#zip")[0].value,
            phone: $("#phone")[0].value
        }
        

        var form_data = JSON.stringify(reqData);
        //var parseForm = JSON.parse(form_data);
        //console.log(form_data);
        $.ajax({
            url: "http://andrewy.sgedu.site/address_validation/API/addresses/update.php",
            type : "POST",
            contentType : 'application/json',
            data : form_data,
            success : function(result) {
                // product was created, go back to products list
                loadAddress();
                cancel();
            },
            error: function(xhr, resp, text) {
                // show error to console
                console.log(xhr, resp, text);
            }
        });
    }
}

function edit(evt)
{
    //console.log(evt);
    var address_id = evt.target.getAttribute("data-id");

    //console.log(address_id)

    $.getJSON("http://andrewy.sgedu.site/address_validation/API/addresses/read_one.php?id="+address_id, function(data){
        // read products button will be here
        $("#fname")[0].value = data["firstname"];
        $("#lname")[0].value = data["lastname"];
        $("#street")[0].value = data["street"];
        $("#city")[0].value = data["city"];
        $("#state")[0].value = data["state"];
        $("#zip")[0].value = data["zip"];
        $("#phone")[0].value = data["phone"];
        $("#saveButton").hide();
        $("#updateButton").show();
        $("#divEditInfo").show();
        $("#updateButton").click({id: address_id}, update);
    });

/*

*/
}

function cancel()
{
    //console.log("cancel");
    $(".info").val("");
    $(".inErr").text("");
    $("#divEditInfo").hide();
}

function loadAddress(){
    $("#tblAddressList").empty();
    $.get("http://andrewy.sgedu.site/address_validation/API/addresses/read.php", gotData, "json");
}

function gotData(data){
    for(var i = 0; i < data.records.length; i++)
    {
        address = data.records[i];
        //console.log(address);
        row = "<tr>";
        row += "<td>" + address["firstname"] + "</td>";
        row += "<td>" + address["lastname"] + "</td>";
        row += "<td>" + address["street"] + "</td>";
        row += "<td>" + address["city"] + "</td>";
        row += "<td>" + address["state"] + "</td>";
        row += "<td>" + address["zip"] + "</td>";
        row += "<td>" + address["phone"] + "</td>";
        row += "<td><input type='button' class='editButton' id='editButton" + address["id"] +"'value='Edit'data-id='" + address["id"] +"'>";
        row += "<input type='button' class='deleteButton' id='deleteButton" + address["id"] +"'value='Delete'data-id='" + address["id"] +"'></td>";
        row += "</tr>";
        $("#tblAddressList").append(row)
    }
    $(".editButton").click(edit);
    $(".deleteButton").click(del);
}

$(document).ready(start);
//divEditInfo
//divAddressList