@extends('installer::main')
@section('step4')
	<div class="field-block" dir=""> 
		<label class="label">نام کاربری</label>
		<input type="text" name="username" dir="ltr" required class="input full-width" 
			value="{{ old('username', 'administrator') }}">
	</div>
	<div class="field-block" dir=""> 
		<label class="label">گذرواژه</label>
		<input type="password" name="password" required class="input full-width">
 		<br>
		<button class="button green-gradient pull-left" type="submit">login</button>
		<br>
	</div>
@stop