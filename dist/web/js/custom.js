
function getCheckedCheckboxesFor(checkboxName) {
    var checkboxes = document.querySelectorAll('input[name="' + checkboxName + '"]:checked'), values = [];
    Array.prototype.forEach.call(checkboxes, function(el) {
        values.push(el.value);
    });
    return values;
}

Number.prototype.padLeft = function (base, chr) {
    var len = (String(base || 10).length - String(this).length)+1;
    return (len > 0)? new Array(len).join(chr || "0")+this : this;
}

/**
 * Function used by autoComplete widgets (event OnBlur)
 * @param object           This objet Autocomplete
 * @param nameDescription  Column description in basic tables
 * @param nameCode         Column code in basic tables
 */

function function_split(object, nameDescription, nameCode) {
    var mysplit = object.value;
    var odescription = document.getElementById(nameDescription);
    var ocode = document.getElementById(nameCode);
    var a=mysplit.split("|");

    if (!(a[0] === undefined || a[0] == null || a[0].length <= 0)){
        odescription.value = a[0];
    }
    if (!(a[1] === undefined || a[1] == null || a[1].length <= 0)){
        ocode.value  = a[1];
    }

}

/**
 * Function used by autoComplete widgets (event onChange)
 * Function used by reset hidden object in column Code in basic tables
 * @param nameCode name of Element by Id (column code)
 */
function function_reset(nameCode) {
    var ocode = document.getElementById(nameCode);
    ocode.value  = "-1";
}

/**
 * Set checked radio list
 * @param oRadio var radioList get document.getElementsByName('Documenttype[transactionType_ind]')
 * @param valItem  It is the item to be set checked from the Radio List
 */
function setRadioCheckedValue(oRadio, valItem) {

    var olength = oRadio.length;
    var i;
    for (i = 0; i < olength; i++) {
        if(oRadio[i].value === valItem) {
            oRadio[i].checked=true;
        }
    }
}

/**
 * Remote Alert messages with a setTimeOut
 */
window.setTimeout(function() {
    $(".alert").fadeTo(500, 0).slideUp(500, function(){
        $(this).remove();
    });
}, 4000);



function AjaxUpdate(event) {

    event.preventDefault();
    var link = $(event.target), callUrl = link.attr('href');

    $.ajax({
        async: true,
        type: 'post',
        url: callUrl,
        data: $('#form-ajax').serializeArray(),
        success: function(data) {
            $('#ajaxMsg').html(data.msg);
        },
        error: function(data) {
            $('#ajaxMsg').html(data.msg);
        },
    });

    //$.pjax.reload('#pjax-container');

}

/**
 *
 * @param valueColumnCode string value of column code
 * @param valueColumnDescription string description of column description
 * @param columnCode             string name of column code object in view
 * @param columnDescription      string name of column description object in view
 */

function selectItem(valueColumnCode, valueColumnDescription, columnCode, columnDescription) {

    var ocolumnCode = document.getElementById(columnCode);
    var ocolumnDescription = document.getElementById(columnDescription);

    ocolumnCode.value = valueColumnCode;
    ocolumnDescription.value = valueColumnDescription;

    $('[data-dismiss=modal]').trigger({ type: 'click' });
}
