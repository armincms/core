@extends('installer::main')
@section('step1')



	<?php 
		$version = (version_compare(PHP_VERSION, '7.1.3') >= 0);
		$openssl = extension_loaded('openssl'); 
		$pdo = extension_loaded('PDO');
		$mysql = extension_loaded('PDO_MySql');
		$mbstr = extension_loaded('Mbstring');
		$tokenizer = extension_loaded('Tokenizer');
		$xml = extension_loaded('Xml');
		$json = extension_loaded('json');
		$ctype = extension_loaded('Ctype');
		$bcMath = extension_loaded('BCMath');
	?>  
	<div class="field-block align-left">
		<h4 class="align-right">پیشنیاز های نصب :</h4>
		<p>
			<p dir="ltr"><i class="glyphicon glyphicon-{{ $version ? 'ok green' : 'remove red' }} mid-margin-right">  </i>PHP >= <b class="red">7.1.3</b> &ensp;<i class="blue"> [ {{ PHP_VERSION }} ]</i>
			</p>
			<p><b class="red">OpenSSL</b> PHP Extension <i class="glyphicon glyphicon-{{ $openssl ? 'ok green' : 'remove red' }} mid-margin-right"></i></p>
			<p><b class="red">PDO</b> PHP Extension<i class="glyphicon glyphicon-{{ $pdo ? 'ok green' : 'remove red' }} mid-margin-right"></i></p><p><b class="red">PDO_MySql</b> PHP Extension<i class="glyphicon glyphicon-{{ $mysql ? 'ok green' : 'remove red' }} mid-margin-right"></i></p>
			<p><b class="red">Mbstring</b> PHP Extension<i class="glyphicon glyphicon-{{ $mbstr ? 'ok green' : 'remove red' }} mid-margin-right"></i></p>
			<p><b class="red">Tokenizer</b> PHP Extension<i class="glyphicon glyphicon-{{ $tokenizer ? 'ok green' : 'remove red' }} mid-margin-right"></i></p>
			<p><b class="red">XML</b> PHP Extension<i class="glyphicon glyphicon-{{ $xml ? 'ok green' : 'remove orange' }} mid-margin-right"></i></p>
			<p><b class="red">Ctype</b> PHP Extension<i class="glyphicon glyphicon-{{ $ctype ? 'ok green' : 'remove orange' }} mid-margin-right"></i></p>
			<p><b class="red">JSON</b> PHP Extension<i class="glyphicon glyphicon-{{ $json ? 'ok green' : 'remove orange' }} mid-margin-right"></i></p>
			<p><b class="red">BCMath</b> PHP Extension<i class="glyphicon glyphicon-{{ $bcMath ? 'ok green' : 'remove orange' }} mid-margin-right"></i></p> 
		</p>
	</div>
@stop