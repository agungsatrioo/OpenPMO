$(".datepicker-group").hide();
        $(".datepicker").datepicker({format: 'yyyy-mm-dd'});
    
        switch($('#status').val()) {
                case "Selesai":
                    $("#datepicker").attr('name', 'tgl_selesai');
                    $("#label-datepicker").empty();
                    $("#label-datepicker").append("Tanggal Selesai");
                    $(".datepicker-group").show();
                    $("input[name=tgl_selesai]").val("<?php echo @$result->tgl_selesai ?>");
                    break;
                case "Dikerjakan":
                    $("#datepicker").attr('name', 'deadline');
                    $("#label-datepicker").empty();
                    $("#label-datepicker").append("Deadline");
                    $(".datepicker-group").show();
                    $("input[name=deadline]").val("<?php echo @$result->deadline ?>");
                    break;
                default:
                    $(".datepicker-group").hide();
                break;    
            }
    
        $('#status').change(function() {
            switch($(this).val()) {
                case "Selesai":
                    $("#datepicker").attr('name', 'tgl_selesai');
                    $("#label-datepicker").empty();
                    $("#label-datepicker").append("Tanggal Selesai");
                    $(".datepicker-group").show();
                    break;
                case "Dikerjakan":
                    $("#datepicker").attr('name', 'deadline');
                    $("#label-datepicker").empty();
                    $("#label-datepicker").append("Deadline");
                    $(".datepicker-group").show();
                    break;
                default:
                    $(".datepicker-group").hide();
                break;    
            }
           
        });
    
        table = $('#data-table').DataTable({ 
            dom: 'Bfrtip',
            paging: false,
                buttons: [
                {
                    extend: 'pdfHtml5',
                    text: 'Save current page',
                   title: 'Proyek yang Ada',
                    pageSize: 'A4',
                    exportOptions: {
                        columns: [0,1,2,3,4,5]
                    
                    },
                    
                }
            ],

            "processing": true, //Feature control the processing indicator.
            "serverSide": true, //Feature control DataTables' server-side processing mode.
            "order": [], //Initial no order.

            // Load data for the table's content from an Ajax source
            "ajax": {
                "url": "<?php echo site_url('project/ajax_list')?>",
                "type": "POST"
            },

            //Set column definition initialisation properties.
             "columnDefs": [
        { 
            "targets": [ -1 ], //last column
            "orderable": false, //set not orderable
        },
        ],
            
              
        });