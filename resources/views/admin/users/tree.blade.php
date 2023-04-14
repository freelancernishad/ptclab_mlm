@extends('admin.layouts.app')
@section('panel')
<style type="text/css">
    span.tf-nc {
        cursor: pointer;
        width: 50px !important;
        margin: 0 8px !important;
    }
</style>
    <div class="row justify-content-center">
        <div class="col-lg-12">




                    <div id="tree-container"></div>


        </div>
    </div>

@endsection
@push('style')

@endpush
@push('script')
<script type="text/javascript">




    const userId = {{ $id }};
    updateTree(userId);
    function updateTree(id) {
        const endpoint = '/user/user-tree-view/' + id;

        $.get(endpoint, function(data) {
            $('#tree-container').html(data);
        });
    }

</script>
@endpush
