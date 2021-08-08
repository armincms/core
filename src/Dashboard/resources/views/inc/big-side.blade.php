@var($user = request()->user())
<section id="menu" role="complementary"> 
	<!-- This wrapper is used by several responsive layouts -->
	<div id="menu-content"> 
		<header>@trans('titles.welcome') &ensp;<b class="white">{{ $user->fullname() }}</b></header> 
		<div id="profile">   
			<img src="{!! $user->avatar !!}" width="64" height="64" alt="User name" class="user-icon" style="border-radius: 10px">  
			<h4 class="red margin-top">{{ $user->fullname() }}</h4> 
		</div>
		<!-- By default, this section is made for 4 icons, see the doc to learn how to change this, in "basic markup explained" -->
		<ul id="access" class="children-tooltip">  
			<li><a href="{{ url('/') }}" target="_blank" title=""><span class="icon-eye"></span></a></li>  
			<li><a href="#!!" title="@trans('titles.profile')"><span class="icon-user"></span></a></li> 
			<li><a href="{{ '#!'}}" title="@trans('titles.report')"><span class="icon-megaphone"></span></a></li> 


			<li>
				<form method="post" action="{{ route('admin.logout') }}">
					{!! csrf_field() !!} 
					<a href="javascript:void(0);" title="@trans('user::title.logout')" onclick="$(this).closest('form').submit();"><span class="icon-logout"></span></a> 
				</form>
				</li> 				  
		</ul> 
		{{-- <p class="button-height margin-top margin-left margin-right disabled">
			<span class="input full-width">
				<label for="pseudo-input-1" class="button blue-gradient icon-search"></label>
				<input type="text" name="pseudo-input-1" id="pseudo-input-1" class="input-unstyled" value="" placeholder="@trans('titles.search')">
			</span>
		</p> --}}
		<section class="navigable">
			{!! 
				$bigMenu->sortBy('level')->asUl(
					['class' => 'big-menu'], ['class' => 'big-menu'], [], function($item) {
						if($item->children()->count()) { 
		                    $item->attr('class', 'with-left-arrow');
		                }

		                if($icon = $item->data('icon')) { 
		                    $item->append("<span class='icon-{$icon}'></span>");
		                }
		                $item->title = armin_trans($item->title); 
		                
					}
				) 
			!!}  
		</section> 
	</div>
	<!-- End content wrapper -->

	<!-- This is optional -->
	<footer id="menu-footer">
		<p class="button-height">
			<input type="checkbox" name="auto-refresh" id="auto-refresh" checked="checked" class="switch float-left">
			<label for="auto-refresh">ریفرش اتوماتیک</label>
		</p>
	</footer>

</section>
<!-- End sidebar/drop-down menu -->