@extends('installer::main')
@section('step3')  
	<div class="field-block" dir=""> 
		<label class="label">نام کاربری</label>
		<input type="text" name="username" dir="ltr" class="input full-width" 
			value="{{ old('username', 'administrator') }}">
	</div>
	<div class="field-block" dir=""> 
		<label class="label">گذرواژه</label>
		<input type="password" name="password" required pattern="[.*]{8,20}" class="input full-width">
	</div>
	<div class="field-block" dir=""> 
		<label class="label">تکرار گذرواژه</label> 
		<input type="password" name="password_confirmation" required pattern="[.*]{8,20}"  
				class="input full-width">
	</div>
@stop 