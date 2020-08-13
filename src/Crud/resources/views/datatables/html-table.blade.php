{!! 
	$table->parameters([
		'sPaginationType' => 'full_numbers',
		'sDom' => '<"dataTables_header"lfr>t<"dataTables_footer"ip>',
		"language" => trans('dashboard::table'),
		'pageLength' => 25,
		'fnInitComplete' =>  "function(e) {  
				$(this).closest('.dataTables_wrapper')
                        .find('.dataTables_length select').addClass('select blue-gradient glossy')
                        .styleSelect();

                $(this).find('.paginate_button.current').addClass('.paginate_active')
				tableStyled = true;

                this.api().columns().every(function () { 
                    $(this.footer()).addClass(
                        $(this.header()).attr('class')
                    ).text(
                        $(this.header()).text()
                    ); 
                });
		}",  
        'searchDelay' => 3000,
        'processing' => true,
		'autoWidth' => true,
        'createdRow' => 'function(row, d) { 
            $(row).find("a.publication-status").menuTooltip(
                $(row).find(".publication-select").hide(), {
                    classes: ["no-padding"]
                }
            );

            $(row).find("td").addClass("vertical-center");

            if(d.deleted_at === undefined || null === d.deleted_at) return; 
            
            return $(row).addClass("red").find("td:first").addClass("red-gradient"); 

        }',
        "order" => [[ 1, "desc" ]], 
	])->table(['class' => 'table dataTable no-footer responsive-table'], true) 
!!} 
 
@push('scripts') 
    <script type="text/javascript" src="/admin/dashboard/datatables.min.js"></script>   
    
    <script type="text/javascript">
        jQuery.fn.dataTable.Api.register('processing()', function (show) {
            return this.iterator('table', function (ctx) {
                ctx.oApi._fnProcessingDisplay(ctx, show);
            });
        });
    </script> 
    <script type="text/javascript">   
    	jQuery(document).ready(function($) {  
            var $typing = false;
            var $timeout = null;  
            
            $(document).on('keydown', 'input[type=search]', function(event) { 
                /* Act on the event */
                $typing = true; 
                var $input = $(this);

                if(this.value.trim().length == 0) {
                    $typing = false;
                    clearTimeout($timeout);
                    $input.change(); 
                }

                if($timeout != null) { 
                    clearTimeout($timeout);
                } 

                $timeout = setTimeout(function() {
                    $typing = false; 
                    var $val = $input.val(); 
                    $input.val(''); 
                    $input.val($val); 

                }, 50, $input, $typing);
            }); 

            $(document).on( 'draw.dt', function (e, settings) { 
                $(e.target).siblings('.dataTables_footer').find('.paginate_button.current').addClass('paginate_active'); 
            }).on('preXhr.dt', function(event) {
                event.preventDefault();
                /* Act on the event */
                $(event.target).parent().addClass('disabled');
                
            }).on('xhr.dt', function(event) {
                event.preventDefault();
                /* Act on the event */
                $(event.target).parent().removeClass('disabled');
            }).on('preDraw.dt', function(event) {
                event.preventDefault();
                /* Act on the event */    

                return ! $typing;
            });

    		$(document).on('click', '.confirm-resource', function(event) {
    			event.preventDefault();
    			/* Act on the event */ 
    			var $action = $(this).attr('href');
    			var $id = $(this).data('id');
                var $tr = $(this).closest('tr');
                var $type = $(this).data('method') == 'get' ? 'get' : 'post';
                var $data = $(this).data('input') || []; 

    			$(this).confirm({ 
    				onConfirm: function() {
                        processing_table();

                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': "{{ csrf_token() }}"
                            }
                        });
                        

    					$.ajax({
    						url: $action,
    						type: $type,
    						dataType: 'json',
    						data: $data,
    					})
    					.done(function() {  
    						reload_datatable();
    					})
    					.fail(function(err) {

    						$tr.css({backgroundColor: 'red'}).hide(1000, function() { 
                                $tr.show(1000, function(){
                                    $tr.css({backgroundColor: ''}) 
                                });
                            }); 

                            processing_table(false);
                            console.log(err);
    					}); 

                        return false;
    				}
    			});

                return false;
    		});
    	}); 
    </script>
    {{-- start resource bulc-actions --}}
    <script type="text/javascript">
        jQuery(document).ready(function($) { 
            $(this).on('change', 'input[role=bulk-check]', function(event) { 
                $(this).closest('table').find('input[role=bulk-item]').trigger('click');
            });
        });
    </script>
    {{-- stop resource bulc-actions --}} 
    <script type="text/javascript">
        // table facility 
        function reload_datatable() {
            $('#dataTableBuilder').DataTable().draw(false);
        }
        function processing_table(show = true) {
            $('#dataTableBuilder').DataTable().processing(show);
        }
    </script>
    
    {{-- publication script --}}
    <script type="text/javascript">
        jQuery(document).ready(function($) {
            $(this).on('change', 'select[role=publication-status]', function(event) {
                /* Act on the event */
                var $action = $(this).data('href');
                var $status = $(this).val(); 
                var $this   = $(this); 

                processing_table(true);

                $.ajax({
                    url: $action,
                    type: 'post',
                    dataType: 'json',
                    data: {
                        status : $status,
                        _method: 'put',
                        _token : "{{ csrf_token() }}"
                    },
                })
                .done(function() { 
                })
                .fail(function(err) { 
                    var $tr = $this.closest('tr')
                    $tr.css({backgroundColor: 'red'}).hide(1000, function() { 
                        $tr.show(1000, function(){
                            $tr.css({backgroundColor: ''}) 
                        });
                    }); 
                    console.log(err);
                })
                .always(function() {
                    processing_table(false);
                    reload_datatable();
                });
                
            });
        });
    </script>

    {{ $table->scripts() }}

@endpush