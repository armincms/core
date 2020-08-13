@extends('installer::main')
@section('step2')
	<div class="field-block hidden wating-db">
		در حال برقراری ارتباط با پایگاه داده. لطفا منتظر بمانید
		<span class="loader"></span>
	</div> 
	<div class="field-block hidden red error-db hidden">
		برقراری ارتباط با پایگاه داده ناموفق بود.
		اطلاعات خودرا دوباره بررسی نمایید.
		<span class="icon icon-warning"></span>
	</div> 
	<div class="field-block hidden green success-db hidden">
		برقراری ارتباط با پایگاه داده موفق آمیز بود.
		<br> 
		برای <b class="red">نصب پایگاه داده</b> بر روی ادامه کلیک نمایید.
		<span class="icon icon-tick"></span>
	</div> 
	<div class="field-block align-left">   
		<label class="label">connection</label>
		<select class="select full-width databse-config" name="DB_CONNECTION">
			<option value="mysql" selected>mysql</option>
			<option value="sqlite">sqlite</option>
			<option value="pgsql">pgsql</option> 
		</select> 
	</div>
	@var($config = Config::get('database.connections.mysql'))
	<div class="field-block" dir=""> 
		<label class="label">host</label>
		<input type="text" name="DB_HOST" dir="ltr" class="input databse-config full-width" value="{{ $config['host'] }}">
	</div>
	<div class="field-block" dir="">  
		<label class="label">port</label>
		<input type="text" name="DB_PORT" dir="ltr" class="input databse-config full-width" value="{{ $config['port'] }}"> 
	</div>
	<div class="field-block" dir="">  
		<label class="label">نام پایگاه داده</label>
		<input type="text" name="DB_DATABASE" dir="ltr" class="input databse-config full-width" value="{{ $config['database'] }}"> 
	</div>
	<div class="field-block" dir="">  
		<label class="label">نام کاربری پایگاه داده</label>
		<input type="text" name="DB_USERNAME" dir="ltr" class="input databse-config full-width" value="{{ $config['username'] }}"> 
	</div>
	<div class="field-block" dir="">  
		<label class="label">پسوورد پایگاه داده</label>
		<input type="text" name="DB_PASSWORD" dir="ltr" class="input databse-config full-width" value="{{ $config['password'] }}"> 
	</div>
	<div class="field-block" dir="">  
		<label class="label">پیشوند جداول</label>
		<input type="text" name="DB_PREFIX" dir="ltr" class="input databse-config full-width" value="{{ $config['prefix'] }}"> 
	</div>
@stop