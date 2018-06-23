@if(!empty($breadcrumbs))
<ul class="app-breadcrumb breadcrumb">
    <li class="breadcrumb-item"><a href="{!! route('admin.dashboard') !!}"><i class="fa fa-home fa-lg"></i></a></li>
    @foreach($breadcrumbs as $bread)
        @if(isset($bread['url']))
            <li class="breadcrumb-item">{!! link_to($bread['url'], $bread['name']) !!}</li>
        @else
            <li class="breadcrumb-item">{!! $bread['name'] !!}</li>
        @endif
    @endforeach
</ul>
@endif
