@extends($activeTemplate.'layouts.master')
@section('content')
<div class="text-end mb-3">
    <a href="{{ route('user.ptc.ads') }}" class="btn btn--base btn-sm">@lang('My Advertisements')</a>
</div>
<div class="card custom--card">
    <div class="card-body">
        <form role="form" method="POST" action="{{ route("user.ptc.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="form-group col-md-12">
                    <label class="form-label">@lang('Title of the Ad')</label>
                    <input type="text" name="title" class="form-control form--control form-control form--control-lg" value="{{ old('title') }}" required>
                </div>

                <div class="form-group col-md-4">
                    <label for="ads_type" class="form-label">@lang('Advertisement Type')</label>
                    <div class="form--select">
                        <select name="IfrOr" id="IfrOr" class="form-control" required>
                            <option value="">Select</option>
                            <option>Iframe</option>
                            <option>Original</option>
                        </select>
                    </div>
                    <pre class="text--danger">@lang('Price per ad') <span class="price-per-ad"></span> {{ $general->cur_text }}</pre>
                </div>


                <div class="row pt-5 mt-5 border-top" id="TypeByFrom"></div>




                <div class="form-group col-md-6">
                    <label class="form-label">@lang('Duration')</label>
                    <div class="input-group">
                        <input type="number" name="duration" class="form-control form--control" value="{{ old('duration') }}" required>
                        <div class="input-group-text">@lang('SECONDS')</div>
                    </div>
                </div>

                <div class="form-group col-md-6">
                    <label class="form-label">@lang('Maximum Show')</label>
                    <div class="input-group">
                        <input type="number" name="max_show" class="form-control form--control" value="{{ old('max_show') }}" required>
                        <div class="input-group-text">@lang('Times')</div>
                    </div>
                    <pre class="text--danger"><span class="total-price"></span> {{ $general->cur_text }} @lang('will cut from your balance')</pre>
                </div>


                <div class="form-group col-md-6">
                    <label class="form-label">@lang('Prove Files')</label>

                    <div class="form-group form-check">
                        <input type="checkbox" class="form-check-input" id="exampleCheck1">
                        <label class="form-check-label" for="exampleCheck1">Check me out</label>
                     </div>


                    <pre class="text--danger"><span class="total-price"></span> {{ $general->cur_text }} @lang('will cut from your balance')</pre>
                </div>



            </div>
            <div class="form-group col-md-12">
                <button type="submit" class="btn btn--base btn--lg w-100">@lang('Submit')</button>
            </div>
        </form>
    </div>
</div>
@endsection


@push('script')
<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
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




    function ads_type(type='iframe',adType=1) {

        const endpoint = `/api/ads/component?type=${type}&adtype=${adType}`;
        $.get(endpoint, function(data) {
            // console.log(data)
            setTimeout(() => {

                $('#TypeByFrom').html(data);
            }, 3000);
        });


    }










    (function($){
        "use strict";
        var price = 0
        $('#ads_type').change(function(){
            var adType = $(this).val();
            if (adType == 1) {
                $("#websiteLink").removeClass('d-none');
                $("#bannerImage").addClass('d-none');
                $("#script").addClass('d-none');
                $("#youtube").addClass('d-none');
                price = {{ @$general->ads_setting->ad_price->url }}
            } else if (adType == 2) {
                $("#bannerImage").removeClass('d-none');
                $("#websiteLink").addClass('d-none');
                $("#script").addClass('d-none');
                $("#youtube").addClass('d-none');
                price = {{ @$general->ads_setting->ad_price->image }}
            } else if(adType == 3) {
                $("#bannerImage").addClass('d-none');
                $("#websiteLink").addClass('d-none');
                $("#script").removeClass('d-none');
                $("#youtube").addClass('d-none');
                price = {{ @$general->ads_setting->ad_price->script }}
            } else if(adType == 5) {
                $("#bannerImage").addClass('d-none');
                $("#websiteLink").addClass('d-none');
                $("#script").addClass('d-none');
                $("#youtube").addClass('d-none');
                $("#Facebook").removeClass('d-none');
                price = {{ @$general->ads_setting->ad_price->Facebook }}
            } else {
                $("#bannerImage").addClass('d-none');
                $("#websiteLink").addClass('d-none');
                $("#script").addClass('d-none');
                $("#youtube").removeClass('d-none');
                price = {{ @$general->ads_setting->ad_price->youtube ?? 0}}
            }
            $('.price-per-ad').text(price);
            $('[name=max_show]').trigger('input');
        }).change();

        $('[name=max_show]').on('input', function () {
            var maxShow = $(this).val();
            var totalPrice = price * maxShow;
            $('.total-price').text(totalPrice);
        });

    })(jQuery);
</script>
@endpush
