@extends('layouts.base')

@section('content')
@livewire('back-link')
@livewire(':uc:table.create-:lc:table-modal')
@livewire(':uc:table.list-:lc:table')
@livewire(':uc:table.update-:lc:table-modal')
@livewire(':uc:table.delete-:lc:table-modal')
@endsection