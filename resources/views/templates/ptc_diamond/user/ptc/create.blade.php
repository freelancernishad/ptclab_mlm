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
            <input type="hidden" name="IfrOr" class="IfrOrInput">
            <div class="row">
                <div class="form-group col-md-12">
                    <label class="form-label">@lang('Title of the Ad')</label>
                    <input type="text" name="title" class="form-control form--control form-control form--control-lg" value="{{ old('title') }}" required>
                </div>

                <input type="hidden" name="ads_type" class="Getads_types" >



                <div class="form-group col-md-4">
                    <label for="ads_package">@lang('Ads Package')</label>
                    <select class="form-control" id="ads_package" onchange="adsPackage(this.value)" required>
                        <option value="">Select</option>
                        <option value="iframe">Iframe</option>
                        <option value="Task ads">Task ads</option>
                    </select>
                </div>




                <div class="form-group col-md-4">
                    <label for="ads_type">@lang('Advertisement Type')</label>
                    <select class="form-control" id="ads_types" class="ads_type" name="ads_types"    required>
                        <option value="">Select</option>



                    </select>
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





function adsPackage(params){
        var ads_settings = <?php echo json_encode($ads_settings); ?>;

        var filteredArray = ads_settings.filter((element)=> {
            return element.IfOr ==params;
        });
            var optionhtml = `<option value="">Select</option>`;
            filteredArray.forEach(el => {
                 optionhtml +=`<option value="${ el.id }">${ el.adsName }</option>`;
            });

            document.getElementById('ads_types').innerHTML=optionhtml

        }



var price = 0;




document.getElementById('ads_types').addEventListener('change', function() {

// console.log('You selected: ', this.value);

var requestOptions = {
method: 'GET',
redirect: 'follow'
};

fetch(`/api/adss/get/prices/${this.value}`, requestOptions)
.then(response => response.json())
.then(result => {

    console.log($('.IfrOrInput').val(result.IfOr));
    console.log($('.Getads_types').val(result.uploaded));

    price = result.ad_price

ads_typeChange(result.uploaded,result);





})
.catch(error => console.log('error', error));


});


function ads_typeChange(ads_type,result){


var price = result.ad_price;







if(result.IfOr=='iframe'){

            if(ads_type==1){
                var html = `
                <label>@lang('Link')</label>
                <input type="text" name="website_link" class="form-control" value="{{ old('website_link') }}" placeholder="@lang('http://example.com')">
                `;

                $('#TypeByFrom').html(html);



            }else if(ads_type==2){

                var html = `
                <label>@lang('Banner')</label>
                <input type="file" class="form-control"  name="banner_image">
                `;
                $('#TypeByFrom').html(html);

            }else if(ads_type==3){

                var html = `
                <label>@lang('Script')</label>
                <textarea  name="script" class="form-control">{{ old('script') }}</textarea>
                `;
                $('#TypeByFrom').html(html);

            }else if(ads_type==4){

                var html = `
                <label>@lang('Youtube Embeded Link')</label>
                <input type="text" name="youtube" class="form-control" value="{{ old('youtube') }}" placeholder="@lang('https://www.youtube.com/embed/your_code')">
                `;
                $('#TypeByFrom').html(html);

            }


    }else{

        const endpoint = `/api/adsss/component?type=${result.IfOr}&adtype=1`;
        $.get(endpoint, function(data) {
        $('#TypeByFrom').html(data);
        })

    }




$('.price-per-ad').text(price);
$('[name=max_show]').trigger('input');

};




$('[name=max_show]').on('input', function () {
            var maxShow = $(this).val();
            var totalPrice = price * maxShow;
            $('.total-price').text(totalPrice);
        });









</script>
@endpush
