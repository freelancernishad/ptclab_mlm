@extends('admin.layouts.app')
@section('panel')
    <div class="row justify-content-center">
        <div class="col-lg-12">




                    <div id="tree-container"></div>


        </div>
    </div>

@endsection
@push('style')
<style type="text/css">
.tf-tree li {
    padding: 0 !important;
}

</style>
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
