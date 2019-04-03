<ul class="c-translation-module__menu indent-{{ $indent }}">    
@foreach($groups as $key => $value)
    @if(is_array($value) == false)
    <li class="c-translation-module__file {!! $value == $group ? '  active' : '' !!}{!! in_array($value, $highlighted) ? ' highlight' : '' !!}">
        <a href="{{ route('amplify-translation.view', [$value]) }}">
            <span>{{ $key }}</span>
        </a>
    </li>
    @else
    <li class="c-translation-module__file">
        <a nohref>
            <span>{{ $key }}</span>
        </a>
        @include('amplify-translation::files_list', ['groups' => $value, 'indent' => $indent + 1])
    </li>
    @endif
@endforeach
</ul>