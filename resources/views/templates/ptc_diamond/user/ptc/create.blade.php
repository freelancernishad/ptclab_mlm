@extends($activeTemplate.'layouts.master')
@section('content')

<link rel="stylesheet" href="{{asset('assets/admin/css/vendor/select2.min.css')}}">

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
                            <option>Task ads</option>
                        </select>
                    </div>

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


                <div class="form-group col-md-12">
                    <label class="form-label">@lang('Prove Files')</label>
                    <select class="select2-multi-select form-control" name="filesSupports[]" multiple>
                        <option value="jpg">JPG</option>
                        <option value="jpeg">JPEG</option>
                        <option value="png">PNG</option>
                        <option value="mp4">MP4</option>
                        <option value="pdf">PDF</option>
                        <option value="doc">DOC</option>
                        <option value="docx">DOCX</option>
                        <option value="txt">TXT</option>
                        <option value="xlx">XLX</option>
                        <option value="xlsx">XLSX</option>
                        <option value="csv">CSV</option>
                    </select>

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

<script src="{{asset('assets/admin/js/vendor/select2.min.js')}}"></script>
<script src="https://cdn.tiny.cloud/1/nhnny39zzu3w0euy077ojdf9gk1n3mjpkobk25i228rt3qkz/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
$('.select2-multi-select').select2();

var price = 0;

(function($){
        "use strict";

        $('#IfrOr').change(function(){
            var IfrOr = $(this).val();
            if (IfrOr == 'Iframe') {
                ads_type('iframe');
            }else if(IfrOr == 'Task ads'){
                ads_type('Task ads');
            }else{

            }
        });



    })(jQuery);




    function ads_type(type='iframe',adType=1) {

        var price = 0
        const endpoint = `/api/ads/component?type=${type}&adtype=${adType}`;
        $.get(endpoint, function(data) {
            // console.log(data)
            setTimeout(() => {

                $('#TypeByFrom').html(data);



            document.getElementById('ads_type').addEventListener('change', function() {

            console.log('You selected: ', this.value);

            var requestOptions = {
            method: 'GET',
            redirect: 'follow'
            };

            fetch(`/api/adss/get/prices/${this.value}`, requestOptions)
            .then(response => response.json())
            .then(result => {
                price = result.ad_price

            ads_typeChange(result.uploaded,result);
            })
            .catch(error => console.log('error', error));


            });





            $('.price-per-ad').text(price);
            $('[name=max_show]').trigger('input');


            $('[name=max_show]').on('input', function () {
            var maxShow = $(this).val();
            var totalPrice = price * maxShow;
            $('.total-price').text(totalPrice);
        });



            }, 3000);
        });


    }









    (function($){
        "use strict";

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



    })(jQuery);
</script>
@endpush
