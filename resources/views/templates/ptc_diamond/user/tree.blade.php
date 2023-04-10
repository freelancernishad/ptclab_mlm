@extends($activeTemplate.'layouts.master')
@section('content')

<div id="tree-container"></div>
    {{-- <x-user-tree-view id="{{ $user->id }}"/> --}}


@endsection
@push('style')
<style type="text/css">
    .copytextDiv{
        border:1px solid #00000021;
        cursor: pointer;
    }
    #referralURL{
        border-right: 1px solid #00000021;
    }
    .bg-success-custom{
        background-color: #28a7456e!important;
    }
    .brd-success-custom{
        border: 1px dashed #28a745;
    }
</style>
@endpush
@push('script')
<script type="text/javascript">




    const userId = {{ $user->id }};
    updateTree(userId);
    function updateTree(id) {
        const endpoint = '/user/user-tree-view/' + id;

        $.get(endpoint, function(data) {
            $('#tree-container').html(data);
        });
    }

</script>
@endpush
