@extends('admin.layouts.app')
@section('panel')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <form role="form" method="POST" action="{{ route("admin.ptc.store") }}" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                       <div class="form-group col-md-8">
                            <label>@lang('Title of the Ad')</label>
                            <input type="text" name="title" class="form-control" value="{{ old('title') }}" placeholder="@lang('Title')" required>
                        </div>

                        <div class="form-group col-md-4">
                            <label>@lang('Amount')</label>
                            <div class="input-group">
                                <input type="number" step="any" name="amount" class="form-control" value="{{ old('amount') }}" placeholder="@lang('User will get ...')" required>
                                <div class="input-group-text"> {{ $general->cur_text }} </div>
                            </div>
                        </div>


                        <div class="form-group col-md-4">
                            <label>@lang('Duration')</label>
                            <div class="input-group">
                                <input type="number" name="duration" class="form-control" value="{{ old('duration') }}" placeholder="@lang('Duration')" required>
                                <div class="input-group-text">@lang('SECONDS')</div>
                            </div>
                        </div>

                        <div class="form-group col-md-4">
                            <label>@lang('Maximum Show')</label>
                            <div class="input-group">
                                <input type="number" name="max_show" class="form-control" value="{{ old('max_show') }}" placeholder="@lang('Maximum Show') " required>
                                <div class="input-group-text">@lang('Times')</div>
                            </div>
                        </div>

                        <div class="form-group col-md-4">
                            <label>@lang('Status')</label>
                            <input type="checkbox" data-width="100%" data-onstyle="-success" data-offstyle="-danger" data-bs-toggle="toggle" data-on="Active" data-off="Inactive" name="status">
                        </div>
                    </div>



                    <div class="row pt-5 mt-5 border-top">
                        <div class="form-group col-md-4">
                        <label>Iframe/Original</label>
                        <div class="input-group">
                            <select name="IfrOr" id="IfrOr" class="form-control" required>
                                <option value="">Select</option>
                                <option>Iframe</option>
                                <option>Original</option>
                            </select>
                        </div>
                    </div>
                    </div>

                    <div class="row pt-5 mt-5 border-top" id="TypeByFrom"></div>





                    <div class="form-group col-md-12 mt-3">
                        <button type="submit" class="btn btn--primary h-45 w-100">@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('breadcrumb-plugins')
<a href="{{ route('admin.ptc.index') }}" class="btn btn-outline--primary btn-sm"><i class="las la-undo"></i> @lang('Back') </a>
@endpush


@push('script')
<script src="https://cdn.tiny.cloud/1/nhnny39zzu3w0euy077ojdf9gk1n3mjpkobk25i228rt3qkz/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>script>
<script>








    (function($){
        "use strict";

        $('#IfrOr').change(function(){
            var IfrOr = $(this).val();
            if (IfrOr == 'Iframe') {
                ads_type('iframe');
            }else if(IfrOr == 'Original'){
                ads_type('original');
            }else{

            }
        });



    })(jQuery);




    function ads_type(type='Iframe',adType=1) {


        const endpoint = `/api/ads/component?type=${type}&adtype=${adType}`;


        $.get(endpoint, function(data) {
            // console.log(data)
            setTimeout(() => {

                $('#TypeByFrom').html(data);
            }, 1000);
        });

    }

</script>
@endpush
