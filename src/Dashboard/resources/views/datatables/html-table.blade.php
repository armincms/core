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
        'searchDelay' => 2000,
        'proccessing' => true,
		'autoWidth' => true,
	])->table(['class' => 'table dataTable no-footer responsive-table'], true) 
!!} 
 
@push('scripts') 
	<script type="text/javascript" src="/admin/dashboard/datatables.min.js"></script>   

    <script type="text/javascript">
        // table facility 
        function reload_datatable() {
            $('#dataTableBuilder').DataTable().draw(false);
        }
        function processing_table(show = true) {
            $('#dataTableBuilder').DataTable().processing(show);
        }
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
                $(e.target).siblings('.dataTables_footer').find('.paginate_button.current').addClass('paginate_active')
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

    		$(document).on('click', '.destroy-content', function(event) {
    			event.preventDefault();
    			/* Act on the event */
    			var $action = $(this).attr('href');
                var $method = $(this).data('method') || 'post'; 
    			var $id = $(this).data('id');
                var $tr = $(this).closest('tr');
 

    			$(this).confirm({ 
    				onConfirm: function() {
                        var $data = {
                            '_token': '{{ csrf_token() }}', 
                            'id' : $id,
                        };

                        if($method == '_delete') {
                            $data['_method'] = $method;
                            $method = 'post';
                        }

    					$.ajax({
    						url: $action,
    						type: $method,
    						dataType: 'json',
    						data: $data,
    					})
    					.done(function(resp) {
                            reload_datatable();
    					})
    					.fail(function(err) {

    						$tr.css({backgroundColor: 'red'}).hide(1000, function() { 
                                $tr.show(1000, function(){
                                    $tr.css({backgroundColor: ''}) 
                                });
                            },); 

                            console.log(err);
    					}); 

                        return false;
    				}
    			});

                return false;
    		});
    	}); 
    </script>

    {{ $table->scripts() }}

@endpush