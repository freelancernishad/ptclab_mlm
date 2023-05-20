@extends('admin.layouts.app')
@section('panel')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <form role="form" method="POST" action="{{ route("admin.ptc.store") }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="IfrOr" id="IfrOrInput">
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
                    <input type="hidden" name="ads_type" id="Getads_types" >



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
                        <select class="form-control" id="ads_type" name="ads_types"    required>
                            <option value="">Select</option>
                        </select>
                        <pre class="text--danger">@lang('Price per ad') <span class="price-per-ad"></span> {{ $general->cur_text }}</pre>
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

    var ads_settings = <?php echo json_encode($ads_settings); ?>;
        function adsPackage(params){
        var filteredArray = ads_settings.filter((element)=> {
            return element.IfOr ==params;
        });
            var optionhtml = `<option value="">Select</option>`;
            filteredArray.forEach(el => {
                 optionhtml +=`<option value="${ el.id }">${ el.adsName }</option>`;
            });
            document.getElementById('ads_type').innerHTML=optionhtml
        }






    (function($){
        "use strict";

        $('#IfrOr').change(function(){
            var IfrOr = $(this).val();
            if (IfrOr == 'iframe') {
                ads_type('iframe');
            }else if(IfrOr == 'Task ads'){
                ads_type('Task ads');
            }else{
                ads_type('');
            }
        });



    })(jQuery);





    function ads_type(type='iframe',adType=1) {

var price = 0
const endpoint = `/api/adsss/component?type=${type}&adtype=${adType}`;
$.get(endpoint, function(data) {
    // console.log(data)
    // setTimeout(() => {

        $('#TypeByFrom').html(data);



    document.getElementById('ads_type').addEventListener('change', function() {

    // console.log('You selected: ', this.value);

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



    // }, 3000);
});


}










document.getElementById('ads_type').addEventListener('change', function() {

// console.log('You selected: ', this.value);

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


function ads_typeChange(ads_type,result){
    console.log(ads_type);

var price = result.ad_price;

document.getElementById('Getads_types').value=ads_type
document.getElementById('IfrOrInput').value=result.IfOr

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


</script>
@endpush
