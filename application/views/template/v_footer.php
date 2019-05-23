
<!-- jQuery 2.2.3 -->
<script src="<?php echo base_url("media")?>/plugins/jQuery/jquery-2.2.3.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="<?php echo base_url("media")?>/plugins/jQueryUI/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  
</script>
<!-- Bootstrap 3.3.6 -->
<script src="<?php echo base_url("media")?>/bootstrap/js/bootstrap.min.js"></script>
<!-- Morris.js charts -->
<script src="<?php echo base_url("media")?>/plugins/morris/morris.js"></script>
<script src="<?php echo base_url("media")?>/plugins/raphael/raphael-min.js"></script>
<!-- Sparkline -->
<script src="<?php echo base_url("media")?>/plugins/sparkline/jquery.sparkline.min.js"></script>
<!-- jvectormap -->
<script src="<?php echo base_url("media")?>/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
<script src="<?php echo base_url("media")?>/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
<!-- jQuery Knob Chart -->
<script src="<?php echo base_url("media")?>/plugins/knob/jquery.knob.js"></script>
<!-- daterangepicker -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
<script src="<?php echo base_url("media")?>/plugins/daterangepicker/daterangepicker.js"></script>
<!-- datepicker -->
<script src="<?php echo base_url("media")?>/plugins/datepicker/bootstrap-datepicker.js"></script>
<!-- Slimscroll -->
<script src="<?php echo base_url("media")?>/plugins/slimScroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="<?php echo base_url("media")?>/plugins/fastclick/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="<?php echo base_url("media")?>/dist/js/app.js"></script>
<script src="<?php echo base_url("media")?>/plugins/data-tables/datatables.js"></script>
<script src="<?php echo base_url("media")?>/plugins/bootbox/bootbox.min.js"></script>
<script src="<?php echo base_url("media")?>/plugins/tooltipster/js/tooltipster.bundle.js"></script>
<script src="<?php echo base_url("media")?>/plugins/dropzone.js/dropzone.js"></script>
<script>
    
    $(this).resize(function() {
         $("#data-table").width($('#data-table_wrapper').width());
    });
    
    $('#data-table>tbody').on('click', 'tr>td>a#delete', function (e){
        e.preventDefault();
        
        var hrefs = $('tr>td>a#delete').attr('href') +  $(this).parents('tr:first').find('td:first').text();
        console.log(hrefs);
         bootbox.confirm("Are you sure to delete this entry?", function(result){ 
             if(result==true) window.location = hrefs;
         });
      });
    
	$('.tipster').tooltipster();
	$('#tipster').tooltipster();

	$('.close').on('click', function(e) {
		$('.callout').hide();
	});
    //@$uploadimage==true?base_url('media/uploads/upload_image.php'):
	$('.dropzone').dropzone({
		  url: "<?php echo base_url('media/uploads/upload.php') ?>",
		 maxFilesize: 2, // MB
		 uploadMultiple: false,
		 <?php if(@$uploadimage==true) echo 'acceptedFiles: "image/*",' ?>
		 dictDefaultMessage: "<?php echo @$uploadlabel ?>",
		 maxFile:1,
		 maxfilesexceeded: function(file) {
			this.removeAllFiles();
			this.addFile(file);
		},
		 success: function(file) {
			 $('#<?php echo @$element ?>').val(file.name);
		 }
	});
	

       
        $(".datepicker-group").hide();
        $(".form-problem").hide();
        $("#datepicker").datepicker({format: 'yyyy-mm-dd'});
        $(".datepicker").datepicker({format: 'yyyy-mm-dd'});
    
        $('#datepicker').datepicker({
              onSelect: function(selectedDate) {
                    $('#to_date').datepicker('option', 'minDate', selectedDate || <?php if(!empty($range)) echo @$range['start_date'];else echo "''" ?>);
              }
        });
        $('#datepicker').datepicker({
              onSelect: function(selectedDate) {
                    $('#frm_date').datepicker('option', 'maxDate', selectedDate || <?php if(!empty($range)) echo @$range['deadline'];else echo "''" ?>);
              }
        }); 
        $('.datepicker').datepicker({
              onSelect: function(selectedDate) {
                    $('#to_date').datepicker('option', 'minDate', selectedDate || <?php if(!empty($range)) echo @$range['start_date'];else echo "''" ?>);
              }
        });
        $('.datepicker').datepicker({
              onSelect: function(selectedDate) {
                    $('#frm_date').datepicker('option', 'maxDate', selectedDate || <?php if(!empty($range)) echo @$range['deadline'];else echo "''" ?>);
              }
        }); 
    
    
        <?php if(@$isEdit) { ?>
            $("#daterange").val($('input[name=start_date]').val()+" - "+$('input[name=deadline]').val())
        <?php } ?>
        
        $("#daterange").daterangepicker({
        locale: {
            format: 'YYYY-MM-DD',
            cancelLabel: 'Clear'
        }
        });
    
        $("#daterange").change(function(e) {
            var str = $("#daterange").val();
            var arr = str.split(' - ');
            
            var start = arr[0]; // Gets the first part
            var end = arr[1];  // Gets the text part
            
            $("input[name=start_date]").val(start);
            $("input[name=deadline]").val(end);
        });
    
        switch($('#status option:selected').val()) {
                case "2":
                    $("#datepicker").attr('name', 'tgl_selesai');
                    $("#label-datepicker").empty();
                    $("#label-datepicker").append("Tanggal Selesai");
                    $(".datepicker-group").show();
                    $("input[name=tgl_selesai]").val("<?php echo @$result->tgl_selesai ?>");
                    break;
                case "4":
                    $("#datepicker").attr('name', 'deadline');
                    $("#label-datepicker").empty();
                    $("#label-datepicker").append("Deadline");
                    $(".datepicker-group").show();
                    $("input[name=deadline]").val("<?php echo @$result->deadline ?>");
                    break;
				case "6":
                    $(".form-problem").show();
                    $(".form-problem>input[name=problem_details]").val("<?php echo @$result->problem_details ?>");
                    break;
                default:
                    $(".datepicker-group").hide();
					$(".form-problem").hide();
                break;    
            }
    
        $('#status').change(function() {
            switch($('#status option:selected').val()) {
                case "2":
                    $("#datepicker").attr('name', 'tgl_selesai');
                    $("#label-datepicker").empty();
                    $("#label-datepicker").append("Tanggal Selesai");
                    $(".datepicker-group").show();
                    break;
                case "4":
                    $("#datepicker").attr('name', 'deadline');
                    $("#label-datepicker").empty();
                    $("#label-datepicker").append("Deadline");
                    $(".datepicker-group").show();
                    break;
				case "6":
                    $(".form-problem").show();
                    $(".form-problem>input[name=problem_details]").val("<?php echo @$result->problem_details ?>");
                    break;
                default:
                    $(".datepicker-group").hide();
					$(".form-problem").hide();
                break;    
            }
           
        });
		
		//$.fn.dataTableExt.sErrMode = 'none';


        table = $('#data-table').DataTable({ 
            dom: "Bfrtip",
            paging: false,

            buttons: [
                {
                    extend: 'pdfHtml5',
                    text: 'Make report...',
                   title: '<?php echo !empty(@$pdfHTML5_title)?@$pdfHTML5_title:"[0]" ?>',
                   message: '<?php echo "Generated on ".date("Y-m-d H:i:s")." by PMO Program" ?>',
                    pageSize: 'A4',
                    exportOptions: {
                        columns: <?php echo !empty(@$pdfHTML5_column)?@$pdfHTML5_column:"[0]" ?>
                    
                    },
					
                    
                }
            ],
                responsive: true,
            "processing": true, //Feature control the processing indicator.
            "serverSide": true, //Feature control DataTables' server-side processing mode.
            "order": [], //Initial no order.

            // Load data for the table's content from an Ajax source
            "ajax": {
                "url": "<?php echo @$ajax_url ?>",
                "type": "post",
                "data" : {"<?= $this->security->get_csrf_token_name(); ?>": "<?= $this->security->get_csrf_hash(); ?>"},
                "error": function(data) {
                    console.log(data.responseText)
                }
            },
            //Set column definition initialisation properties.
             "columnDefs": [
        { 
            "targets": <?php echo !empty(@$unorderable)?@$unorderable:"[0]" ?>,//[ -1,-2,0 ], //last column
            "orderable": false, //set not orderable
        },
        ],
            
              
        });

	  $.ajax({
		url: "<?php echo base_url('home/chartsource') ?>",
		method: "post",
          "data" : {"<?= $this->security->get_csrf_token_name(); ?>": "<?= $this->security->get_csrf_hash(); ?>"},
		success: function(data) {
			
		},
          
	});
	

	
	$.getJSON("<?php echo base_url('home/chartsource') ?>", function (json) { // callback function which gets called when your request completes. 
        Morris.Line({
		  element: 'chart-make',
		  data: json,
		  xkey: "tgl_selesai",
		  ykeys: ['hitungan'],
		  labels: ['hitungan']
		});
    });
	
	
	
</script>
</body>
</html>
