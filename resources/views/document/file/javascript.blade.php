<script document="text/javascript">
    $(function () {

        // *** SELECTS
        $('body').on('change', '#location-select', function (e) {
            var lid = $(this).val();
            console.log('LID: '+lid);        
            if( lid != '' ) {
                setDepartmentsAjax(lid);
            } else {
                $('input[name=code]').val('');  
                $("#department-select").html('<option value="">{{ trans("document/file.form.department.placeholder") }}</option>');
            }
        }); // change #location-select Event

        $('body').on('change', '#department-select', function (e)  {
            e.preventDefault();
            var did = this.value;
            console.log('DID: '+did);                       
            if( did != '' ) {
                setTopicsAjax(did);
                setJobsAjax(did);          
            } else {
                $('input[name=code]').val('');
                $("#topic-select").html('<option value="">{{ trans("document/file.form.topic.placeholder1") }}</option>');
            }          
        }); // change #department-select Event

        $('body').on('change', '#topic-select', function (e)  {
            e.preventDefault();
            var tid = this.value;
            console.log('TID: '+tid);
            $('input[name="code"]').val('');                        
            if( tid != '' ) {
                setSubtopicsAjax(tid);                            
            } else {
                $("#subtopic-select").html('<option value="">{{ trans("document/file.form.subtopic.placeholder1") }}</option>');
            }          
        }); // change #topic-select Event

        $('body').on('change', '#subtopic-select', function (e)  {
            e.preventDefault();
            var sid = this.value;
            if( sid != '' ) {
                setCode();
            } else {
                $('input[name=code]').val('');   
            }                                              
        }); // change #subtopic-select Event

        // *** BOTONES
        $('body').on('click', '#btn-new-index', function (e)  {
            e.preventDefault();
            var txt = $("#input-new-index").val();
            if( txt.length == 0 ) {
                setSuccessNotification('error', 'Oops!', '{{ trans("document/file.error.index.empty") }}');
                $("#input-new-index").focus(); 
            } else {
                setIndexAjax(txt);
            }
        }); // click #btn-new-index Event 
        
        $('body').on('click', '#btn-new-disposal', function (e)  {
            e.preventDefault();
            var txt = $("#input-new-disposal").val();
            if( txt.length == 0 ) {
                setSuccessNotification('error', 'Oops!', '{{ trans("document/file.error.disposal.empty") }}');
                $("#input-new-disposal").focus(); 
            } else {
                setDisposalAjax(txt);
            }
        }); // click #btn-new-disposal Event

        $('body').on('click', '#btn-new-topic', function (e)  {
            e.preventDefault();
            var txt = $("#input-new-topic").val();
            var did = $("#department-select option:selected").val();
            console.log('TXT: '+txt+' DID: '+did);
            if( did == '' ) {
                setSuccessNotification('error', 'Oops!', '{{ trans("document/file.error.topic.no-department") }}');
                $("#department-select").focus(); 
            } else if( txt.length == 0 ) {
                setSuccessNotification('error', 'Oops!', '{{ trans("document/file.error.topic.empty") }}');
                $("#input-new-topic").focus(); 
            } else {
                 $('input[name=code]').val('');
                setTopicAjax(did, txt);
            }
        }); // click #btn-new-topic Event        
        
        $('body').on('click', '#btn-new-subtopic', function (e)  {
            e.preventDefault();
            var txt = $("#input-new-subtopic").val();
            var tid = $("#topic-select option:selected").val();
            console.log('TXT: '+txt+' TID: '+tid);
            if( tid == '' ) {
                setSuccessNotification('error', 'Oops!', '{{ trans("document/file.error.subtopic.no-topic") }}');
                $("#topic-select").focus(); 
            } else if( txt.length == 0 ) {
                setSuccessNotification('error', 'Oops!', '{{ trans("document/file.error.subtopic.empty") }}');
                $("#input-new-subtopic").focus(); 
            } else {
                 $('input[name=code]').val('');
                setSubtopicAjax(tid, txt);
            }
        }); // click #btn-new-subtopic Event            

        // *** DATE PICKER
        $('input[name="dwell_date"]').daterangepicker({
            locale: {
                format: '{{ $DATA->dateFormat }}',
                daysOfWeek: ['Do','Lu','Ma','Mi','Ju','Vi','Sa'],
                monthNames: ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'],
                applyLabel: "Aplicar",
                cancelLabel: "Cancelar",
                customRangeLabel: "-"
            },            
            singleDatePicker: true,
            showDropdowns: true,
            minYear: parseInt(moment().subtract(10, 'years').format('YYYY'),10),
            maxYear: parseInt(moment().add(10, 'years').format('YYYY'),10)
        });
        $('input[name="dead_date"]').daterangepicker({
            locale: {
                format: '{{ $DATA->dateFormat }}',
                daysOfWeek: ['Do','Lu','Ma','Mi','Ju','Vi','Sa'],
                monthNames: ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'],
                applyLabel: "Aplicar",
                cancelLabel: "Cancelar",
                customRangeLabel: "-"
            },            
            singleDatePicker: true,
            showDropdowns: true,
            minYear: parseInt(moment().subtract(10, 'years').format('YYYY'),10),
            maxYear: parseInt(moment().add(10, 'years').format('YYYY'),10)
        });                         

    }); // document
    


    function setDepartmentsAjax(id) {
        var url = "{{ route('files.list.departments', ':id') }}";
        console.log('setDepartmentAjax');
        $.ajax({
            url: url.replace(':id', id),
            type: 'GET',
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },                                             
            success: function(json) {  
                //console.dir(json);
                if( json.success ) {
                    generateDepartmentsSelect('', json.n, json.departments);
                    $("#topic-select").val('').trigger('change');
                    setCode();
                } else {
                    setSuccessNotification('error', 'Oops!', json.message);
                }
            } // success
        }); // ajax         
    } // setDepartmentsAjax   
    
    function generateDepartmentsSelect(id, n, departments) {
        var output = '<option value="">{{ trans("document/file.form.department.placeholder") }}</option>';
        if( n == 0 ) {
            setSuccessNotification('error', 'Oops!', '{{ trans("document/file.error.department.no-exist") }}'); 
        } else if( n == 1 ) {
           //$.each(departments, function(i, department) {
                output = '<option value='+departments.department_id+' selected>'+departments.name+'</option>';
            //});            
        } else {
            $.each(departments, function(i, department) {
                output += '<option value='+department.department_id;
                output += ( department.department_id == id ) ? ' selected' : '';
                output += '>'+department.name+'</option>';
            });
        }
        $("#department-select").html(output);
    } // generateDepartmentsSelect     

    function setTopicsAjax(id) {
        var url = "{{ route('files.list.topics', ':id') }}";
        console.log('setTopicAjax');
        $.ajax({
            url: url.replace(':id', id),
            type: 'GET',
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },                                             
            success: function(json) {  
                //console.dir(json);
                if( json.success ) {
                    var tid = $("#topic-is").val();
                    console.log('TID*: '+tid);
                    generateTopicsSelect(tid, json.topics);
                    $("#subtopic-select").val('').change(); 
                     $("#topic-is").val('');
                     setCode();
                } else {
                    setSuccessNotification('error', 'Oops!', json.message);
                }
            } // success
        }); // ajax         
    } // setTopicsAjax

    function generateTopicsSelect(id, topics) {
        var output = '<option value="">{{ trans("document/file.form.topic.placeholder1") }}</option>';
        if( topics.length == 0 ) {
            setSuccessNotification('error', 'Oops!', '{{ trans("document/file.error.topic.no-exist") }}'); 
        } else {
            $.each(topics, function(i, topic) {
                output += '<option value='+topic.topic_id;
                output += ( topic.topic_id == id ) ? ' selected' : '';
                output += '>'+topic.name+'</option>';
            });
        }
        $("#topic-select").html(output);
    } // generateTopicsSelect 
    
    function setSubtopicsAjax(id) {
        var url = "{{ route('files.list.subtopics', ':id') }}";
        console.log('setSubtopicAjax');
        $.ajax({
            url: url.replace(':id', id),
            type: 'GET',
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },                                             
            success: function(json) {  
                //console.dir(json);
                if( json.success ) {
                    var sid = $("#subtopic-is").val();
                    console.log('SIP*: '+sid);
                    generateSubtopicsSelect(sid, json.subtopics);                           
                    $("#subtopic-is").val('');
                    setCode();
                } else {
                    setSuccessNotification('error', 'Oops!', json.message);
                    $("#subtopic-select").html('<option value="">{{ trans("document/file.form.subtopic.placeholder1") }}</option>');                 
                }
            } // success
        }); // ajax         
    } // setSubtopicsAjax

    function generateSubtopicsSelect(id, subtopics) {
        var output = '<option value="">{{ trans("document/file.form.subtopic.placeholder1") }}</option>';
        if( subtopics.length == 0 ) {
            setSuccessNotification('error', 'Oops!', '{{ trans("document/file.error.subtopic.no-exist") }}'); 
        } else {
            $.each(subtopics, function(i, subtopic) {
                output += '<option value='+subtopic.subtopic_id;
                output += ( subtopic.subtopic_id == id ) ? ' selected' : '';
                output += '>'+subtopic.name+'</option>';
            });
        }
        $("#subtopic-select").html(output);
        $("#loading-image").hide(); 
    } // generateSubtopicsSelect

    function setJobsAjax(id) {
        var url = "{{ route('files.list.jobs', ':id') }}";
        //console.log(url.replace(':id', id));
        $.ajax({
            url: url.replace(':id', id),
            type: 'GET',
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },                                             
            success: function(json) {  
                //console.dir(json);
                if( json.success ) {
                    var jid = $("#job-is").val();
                    console.log('JIP: '+jid);
                    generateJobsSelect(jid, json.jobs);
                } else {
                    setSuccessNotification('error', 'Oops!', json.message);
                }
            } // success
        }); // ajax         
    } // setJobsAjax

    function generateJobsSelect(id, jobs) {
        var output = '<option value="">{{ trans("document/file.form.job.placeholder") }}</option>';
        if( jobs.length == 0 ) {
            setSuccessNotification('error', 'Oops!', '{{ trans("document/file.error.job.no-exist") }}'); 
        } else {
            $.each(jobs, function(i, job) {
                output += '<option value='+job.job_id;
                output += ( job.job_id == id ) ? ' selected' : '';
                output += '>'+job.name+'</option>';
            });
        }
        $("#job-select").html(output);
    } // generateJobsSelect        

    function setIndexAjax(txt) {
        var route = "{{ route('files.save.index') }}";
        var str = $.trim(txt);
        //console.log('Running setIndexAjax with route: '+route);
        $.ajax({
            url: route,
            type: 'POST',
            data: {'txt': str},
            dataType: 'json',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },    
            success: function(json) {
                //console.dir(json);
                if( json.success ) {
                    // Mensaje
                    setSuccessNotification('success', '', json.message);
                    // Limpiar input
                    $("#input-new-index").val('');
                    // Generar Select
                    var output  = '<option value="">{{ trans("document/file.form.index.placeholder1") }}</option>';  
                    $.each(json.data, function(i, option) {
                        output += '<option value='+option.index_id;
                        output += ( option.name == str ) ? ' selected' : '';
                        output += '>'+option.name+'</option>';
                    });
                    $("select[name='index_id']").html(output);
                } else {
                    setSuccessNotification('error', 'Oops!', json.message);
                }                
            } // success
        }); // ajax 
    } // setIndexAjax Fx

    function setDisposalAjax(txt) {
        var route = "{{ route('files.save.disposal') }}";
        var str = $.trim(txt);
        //console.log('Running setDisposalAjax with route: '+route);
        $.ajax({
            url: route,
            type: 'POST',
            data: {'txt': str},
            dataType: 'json',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },    
            success: function(json) {
                //console.dir(json);
                if( json.success ) {
                    // Mensaje
                    setSuccessNotification('success', '', json.message);
                    // Limpiar input
                    $("#input-new-disposal").val('');
                    // Generar Select
                    var output  = '<option value="">{{ trans("document/file.form.disposal.placeholder1") }}</option>';  
                    $.each(json.data, function(i, option) {
                        output += '<option value='+option.disposal_id;
                        output += ( option.name == str ) ? ' selected' : '';
                        output += '>'+option.name+'</option>';
                    });
                    $("select[name='disposal_id']").html(output);
                } else {
                    setSuccessNotification('error', 'Oops!', json.message);
                }                
            } // success
        }); // ajax 
    } // setDisposalAjax Fx    

    function setTopicAjax(id, txt) {
        var route = "{{ route('files.save.topic') }}";
        var str = $.trim(txt);
        //console.log('Running setTopicAjax with route: '+route);
        $.ajax({
            url: route,
            type: 'POST',
            data: {'department_id': id, 'topic': str, 'filter': true},
            dataType: 'json',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },    
            success: function(json) {
                //console.dir(json);
                if( json.success ) {
                    // Mensaje
                    setSuccessNotification('success', '', json.message);
                    // Limpiar input
                    $("#input-new-topic").val('');
                    // Generar select de temas
                    generateTopicsSelect(json.tid, json.topics);
                    // Enfocar
                    $("#topic-select").focus();                    
                    // Resetea select de subtemas
                    generateSubtopicsSelect(0, []);
                } else {
                    setSuccessNotification('error', 'Oops!', json.message);
                }                
            } // success
        }); // ajax 
    } // setTopiclAjax Fx
    
    function setSubtopicAjax(id, txt) {
        var route = "{{ route('files.save.subject') }}";
        var str = $.trim(txt);
        //console.log('Running setSubtopicAjax with route: '+route);
        $.ajax({
            url: route,
            type: 'POST',
            data: {'topic_id': id, 'subject': str},
            dataType: 'json',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },    
            success: function(json) {
                //console.dir(json);
                if( json.success ) {
                    // Mensaje
                    setSuccessNotification('success', '', json.message);
                    // Limpiar input
                    $("#input-new-subtopic").val('');
                    // Generar select de subtemas
                    generateSubtopicsSelect(json.sid, json.subtopics);
                    // Enfocar
                    $("#subtopic-select").focus();
                    // Generar código
                    setCode(); 
                } else {
                    setSuccessNotification('error', 'Oops!', json.message);
                }                
            } // success
        }); // ajax 
    } // setSubtopiclAjax Fx 
    
    function setCode() {        
        var route = "{{ route('files.get.code') }}";
        var lid = $('#location-select option:selected').val();
        var did = $('#department-select option:selected').val();
        var tid = $('#topic-select option:selected').val();
        var sid = $('#subtopic-select option:selected').val();
        var fid = $('input[name="file_id"]').val();
        console.log('SET CODE :: fid:'+fid+' lid:'+lid+' did:'+did+' tid:'+tid+' sid:'+sid);
        $('input[name=code]').val('');
        if( lid > 0 && did > 0 && tid > 0 && sid > 0 )  {
            console.log(':: Searching by code...');
            $("#loading-image").show();
            $('input[name=code]').val('');
            $.ajax({
                url: route,
                type: 'POST',
                data: {'fid':fid,'lid':lid,'did':did,'tid':tid,'sid':sid},
                dataType: 'json',
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },    
                success: function(json) {
                    console.dir(json);
                    $("#loading-image").hide();
                    if( json.success ) {
                        // Establecer código
                        $('input[name=code]').val(json.code).css('color', 'black');                        
                    } else {
                        $('input[name=code]').val(json.code).css('color', 'red').focus();
                        setSuccessNotification('error', 'Oops!', json.message);
                    }                
                } // success
            }); // ajax 
        } else {
            $('input[name=code]').val('');
        }        
    } // setCode

</script>