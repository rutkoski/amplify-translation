@if(!isset($group))
    <div class="panel panel-primary">
        <div class="panel-heading">{{ trans('amplify-translation::panel.welcome.title') }}</div>
        <div class="panel-body">
            @if(sizeof($groups) == 0)
                <p>{{ trans('amplify-translation::panel.welcome.doImport') }}</p>
            @else
                <p>{{ trans('amplify-translation::panel.welcome.chooseGroup') }}</p>
            @endif
        </div>
    </div>
@else
    <div class="panel panel-primary">
        <div class="panel-heading">{{ $group }}</div>
        <div class="panel-body">
            @yield('translate_section') 
        </div>
    </div>
@endif