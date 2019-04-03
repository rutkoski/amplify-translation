@extends('amplify-translation::layout')

@section('translate_section')
    @if($canManage)
        @include('amplify-translation::index_manage')
    @endif  
@endsection