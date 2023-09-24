/*!***************************************************!*\
!***                                               ***!
  !*** Helper para ISO-ONE                         ***!
!***                                               ***!  
  \***************************************************/



/*!***************************************************!*\
  !*** NOTIFICACIONES DE RESULTADOS DE LOS PROCESOS  ***!
\***************************************************/

function setSimpleNotification(text) {
    $("#notification-message").html(text);
    $("#basic-non-sticky-notification-toggle").click();
}

function setSuccessNotification(type, text1, text2) {
    var icon;
    $("#success-message-1").html(text1);
    $("#success-message-2").html(text2);
    if( type == 'success') {
        icon = 'check-circle';
    } else {
        icon = 'slash';
    }
    $("#success-message-icon").data('lucide', icon);
    $("#success-notification-toggle").click();
}

/*!***************************************************!*\
  !*** ERRORES AJAX                              ***!
\***************************************************/

 function seAjaxError(x, exception, dir) {
    var message;
    var statusErrorMap = {
        '400' : "Server understood the request, but request content was invalid.",
        '401' : "Unauthorized access.",
        '403' : "Forbidden resource can't be accessed.",
        '500' : "Internal server error.",
        '503' : "Service unavailable."
    };
    if (x.status) {
        if( x.status === 0) {
            message = 'Not connect.\n Verify Network.';
        } else {
            message = statusErrorMap[x.status];
            if(!message){
                message="Error desconocido";
            }
        }
    }else if(exception=='parsererror'){
        message="Error.\nParsing JSON Request failed.";
    }else if(exception=='timeout'){
        message="Request Time out.";
    }else if(exception=='abort'){
        message="Request was aborted by the server";
    }else {
        message="Error : " + x.responseText;
    }
    console.error(dir+' : '+message);
    setSuccessNotification('error', 'Error Lado Cliente', message);  
 }

/*!***************************************************!*\
  !*** FORMS                                        ***!
\***************************************************/

/**
 * Genera un select con optgroups (la respuesta del ajax debe contener las variables, id, name, group y selected)
 * @param  string url url para el ajax con parámetros de selección
 * @param  string id identificador del selector del tag select
 * @param  string/null placeholder texto para el placeholder del select o null si no se requiere de placeholder
 * @return boolean  Reenderiza una cadena de texto en el selector id
 */ 
function setSelectGroup(url, id, placeholder) {        
    $.ajax({
        url: url,
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            console.log('=== AJAX GET');
            console.dir(data);
            var output = (placeholder !== null) ? '<option value="">'+placeholder+'</option>' : '';
            var previous = '';

            if( !$.isEmptyObject(data) ) {
                $.each(data, function(i, item) {
                    if( previous != item.group ) {
                        if( i != 0 ) {
                            output += '</optgroup>';
                        }
                        output += '<optgroup label="'+item.group+'">';
                        previous = item.group;
                    }
                    output += '<option value='+item.id;
                    output += ( item.selected ) ? ' selected' : ''; 
                    output += '>'+item.name+'</option>';
                });
                output += '</optgroup>';
            }
            //console.log(output);
            $(id).html(output);
        } // success
    }); // ajax           
} // setSelectGroupByJobs

/**
 * Genera un select sencillo (la respuesta del ajax debe contener las variables, id, name y selected)
 * @param  string url url para el ajax con parámetros de selección
 * @param  string id identificador del selector del tag select
 * @param  string/null placeholder texto para el placeholder del select o null si no se requiere de placeholder
 * @return boolean  Reenderiza una cadena de texto en el selector id
 */ 
function setSelectSingle(url, id, placeholder) {        
    $.ajax({
        url: url,
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            console.log('=== AJAX GET');
            console.dir(data);
            var output = (placeholder !== null) ? '<option value="">'+placeholder+'</option>' : '';
            if( !$.isEmptyObject(data) ) {
                $.each(data, function(i, item) {
                    output += '<option value='+item.id;
                    output += ( item.selected ) ? ' selected' : ''; 
                    output += '>'+item.name+'</option>';
                });
            }
            console.log(output);
            $(id).html(output);
        } // success
    }); // ajax           
} // setSelectGroupByJobs


/*!***************************************************!*\
  !*** GRID DE DATATABLES                           ***!
\***************************************************/

/**
 * Genera el FOOT de la tabla
 * @param  string $id Identificador de la tabla
 * @param  array $columnsDef Definición de la tabla
 * @return boolean    Positivo cuando agrega foot al final de la tabla
 */ 
function setFooter(id, columnsDef) {
    var footer = '<tfoot><tr>';

    $.each(columnsDef, function(j, item) {
        if( typeof item.visible !== 'undefined' && item.visible === false ) {
            footer += '<td>&nbsp;</td>';
        } else {
            if( typeof item.filterable !== 'undefined' && item.filterable === true ) {
                footer += '<td><select id="filter-' + item.data + '" class="col-filter select-filter"><option value="">Seleccione '+item.title+'</option></select></td>';
            } else {
                if( typeof item.searchable !== 'undefined' && item.searchable === true ) {
                    footer += '<td><input id="filter-' + item.data + '" type="text" class="col-filter input-filter" placeholder="Buscar ' + item.title + '" /></td>';
                } else {
                    footer += '<td>&nbsp;</td>';
                }
            }
        }
    }); // each columnDef

    footer += '</tr></tfoot>';
    //console.log(footer)
    $("#"+id).append(footer);
    return true;        
} // setFooter

/**
 * Genera los filtros de columna con los datos de la tabla (primero debe ejecutarse setFooter() )
 * @param  object $api objeto API de datatables
 * @param  array $columnsDef Definición de la tabla
 * @return boolean    Positivo cuando completa los filtros
 */ 
function setFilters(api, columnsDef) {
    api.columns().every(function (i) {
        var column = this;
        var name =  columnsDef[i].data;
        var visible =  columnsDef[i].visible;
        if( typeof visible !== 'undefined' && visible === false ) {
            //console.log(name +' NO VISIBLE');
        } else {
            var filterable =  columnsDef[i].filterable;
            if( typeof filterable !== 'undefined' && filterable === true ) {
                //console.log(name +' FILTERABLE');
                var select = $('<select id="filter-'+name+'" class="col-filter select-filter"><option value="">Todo</option></select>').appendTo($(column.footer()).empty()).on('change', function () {
                    var val = $.fn.dataTable.util.escapeRegex($(this).val());
                    column.search(val ? '^' + val + '$' : '', true, false).draw();
                });

                column.data().unique().sort().each(function (d, j) {
                    select.append('<option value="' + d + '">' + d + '</option>');
                }); 
            } else {
                var searchable =  columnsDef[i].searchable;
                if( typeof searchable !== 'undefined' && searchable === true ) {
                    //console.log(name +' SEARCHABLE');
                    $('input', this.footer() ).on( 'keyup change clear', function () {
                        if ( column.search() !== this.value ) {
                            column.search( this.value ).draw();
                        }
                    });                                 
                }
            }                        
        }
        return true; 
    });    
} // set Filter

/**
* Genera las opciones de un select para los datos de una columna del Grid
* @param  object $column  valores de la columna 
* @return string  opciones
*/ 
function setSelectFilter(column) {
    var newfilter = [];
    var output = '';
    $.each(column, function(h, value) {
        console.log(value);
        if (value.indexOf('/') > -1) {
            var arr = value.split('/');
            $.each(arr, function(i, item) {
                newfilter.push(item);
            });
        } else {                            
            newfilter.push(value);
        }
    });
    newfilter = sortUnique(newfilter);
    output = '<option value="">Todos</option>';
    $.each(newfilter, function(i, value) {
        output += '<option value="'+value+'">'+value+'</option>';
    });
    console.log(output);
    return output;   
} // setSelectFilter

function sortUnique(array) {
    "use strict";
    var table = {}, key, i;
    for (i = 0; i < array.length; i++) {
        table[[array[i]]] = '';
    }
    i = 0;
    for (key in table) {
        array[i++] = key;
    }
    array.length = i;
    return array.sort();
} // sortUnique

/**
 * Genera los filtros de columna desde el initComplete (¡IMPORTANT! tFood formado en el HTML)
 * @param  object $api objeto API de datatables
 * @param  array $selects Ordinal de las columnas para generar selects
 * @param  array $inputs Ordinal de las columnas para generar imputs
 * @return boolean    Positivo cuando completa los filtros
 */
function setBottomFilter($this, selects, inputs) {
    $this.columns().every( function (i) {
        var column = this;
        //console.log('i: '+i);
        if( $.inArray(i, selects) !== -1 ) {   
            //console.log('inArray selects: '+i);
            var select = $('<select class="col-filter"><option value="" class="col-filter">Todo</option></select>')
                .appendTo( $(column.footer()).empty() )
                .on( 'change', function () {
                    var val = $.fn.dataTable.util.escapeRegex(
                        $(this).val()
                    );
                    column.search( val ? '^'+val+'$' : '', true, false ).draw();
                });
            column.data().unique().sort().each( function ( d, j ) {
                select.append( '<option value="'+d+'">'+d+'</option>')
            } );                                            
        } else if( $.inArray(i, inputs) !== -1 ) {
            //console.log('inArray inputs: '+i);
            $( 'input', this.footer() ).on( 'keyup change clear', function () {
                if ( column.search() !== this.value ) {
                    column.search( this.value ).draw();
                }
            }); 
        }
    });
} // setBottomFilter