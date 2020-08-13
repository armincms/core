<!-- Side tabs shortcuts -->
<ul id="shortcuts" role="complementary" class="children-tooltip tooltip-left">
	<li class="current">
		<a href="{{ route('panel') }}" class="shortcut-dashboard" title="@trans('titles.dashboard')">@trans('titles.dashboard')</a>
	</li>
	<li class="disabled"><a href="#" class="shortcut-messages" title="@trans('titles.emails')">@trans('titles.emails')</a></li>
	<li class="disabled"><a href="#" class="shortcut-agenda" title="@trans('titles.agenda')">@trans('titles.agenda')</a></li>
	<li><a href="#!{{-- {{ route('ticketssuport') }} --}}" class="shortcut-contacts" title="@trans('titles.tickets')">@trans('titles.tickets')</a></li>
	<li class="disabled"><a href="#" class="shortcut-medias" title="@trans('titles.users')">@trans('titles.users')</a></li>
	<li class="disabled"><a href="#" class="shortcut-stats" title="@trans('titles.static')">@trans('titles.static')</a></li>
	<li><a href="{{ route('admin.profile.edit', request()->user()) }}" class="shortcut-settings" title="@trans('titles.profile')">@trans('titles.profile')</a></li>
	<li class="at-bottom disabled"><span class="shortcut-notes" title="@trans('titles.notes')">@trans('titles.notes')</span></li>
</ul>  

