@extends('installer::main')
@section('step3')
	<div class="field-block hidden migratin">
		در حال نصب پایگاه داده ها.<br>
		لطفا تا اتمام نصب منتظر بمانید.
		<span class="loader green"></span>
	</div> 
	<div class="field-block hidden migratin-success green">
		پایگاه داده ها بخوبی نصب شد. <i class="icon icon-tick"></i>
	</div> 
	<div class="field-block hidden migratin-error">
		نصب پایگاه داده ها نام وفق بود. <i class="icon icon-warning"></i>
		<br>
		<a class="retry-migrate red" href="javascript::void();" onclick="migrateDB();">دوباره تلاش نمایید</a>. 
	</div>  
@stop