@extends('admin.layouts.app')
@section('panel')

<style>
    .addmoreAds {
    width: 100%;
    height: 100%;
    text-align: center;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1px dashed black;
    cursor: pointer;
}
</style>

<div class="row mb-none-30">
    <div class="col-lg-12">
        <div class="card">
            <form action="" method="post">
                @csrf
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('User Ads Post')</label>
                                <input type="checkbox" data-width="100%" data-height="50" data-onstyle="-success" data-offstyle="-danger" data-bs-toggle="toggle" data-on="@lang('Enable')" data-off="@lang('Disabled')" @if(@$general->user_ads_post) checked @endif name="user_ads_post">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Auto Approve')</label>
                                <input type="checkbox" data-width="100%" data-height="50" data-onstyle="-success" data-offstyle="-danger" data-bs-toggle="toggle" data-on="@lang('Enable')" data-off="@lang('Disabled')" @if(@$general->ad_auto_approve) checked @endif name="ad_auto_approve">
                            </div>
                        </div>
                    </div>
                    <div class="row" id="parent">


                        @foreach ($ads_settings as $ads_setting)




                            <div class="col-md-3 adscolums">
                                <x-ads-settings :adssetting="$ads_setting" />
                            </div>
                        @endforeach




                            <div class="col-md-3 adscolums">
                                <div class="addmoreAds" id="add-button">
                                    Add More Ads
                                </div>
                            </div>




                        {{-- <div class="col-md-3">
                            <div class="card mb-4">
                                <div class="card-header bg--primary d-flex justify-content-between">
                                    <h5 class="text-white">@lang('Script Ads')</h5>
                                    <input type="hidden" name="adsName[script]" value="script">
                                    <input type="hidden" name="adsType[]" value="script">
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label>@lang('Ads Price')</label>
                                        <div class="input-group">
                                            <input type="number" step="any" class="form-control" name="ad_price[script]" value="{{ @$general->ads_setting->ad_price->script }}" required>
                                            <span class="input-group-text">{{ $general->cur_text }}</span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>@lang('Amount For User')</label>
                                        <div class="input-group">
                                            <input type="number" step="any" class="form-control" value="{{ @$general->ads_setting->amount_for_user->script }}" name="amount_for_user[script]" required>
                                            <span class="input-group-text">{{ $general->cur_text }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>



                        <div class="col-md-3">
                            <div class="card mb-4">
                                <div class="card-header bg--primary d-flex justify-content-between">
                                    <h5 class="text-white">@lang('Image Ads')</h5>
                                    <input type="hidden" name="adsName[image]" value="image">
                                    <input type="hidden" name="adsType[]" value="image">
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label>@lang('Ads Price')</label>
                                        <div class="input-group">
                                            <input type="number" step="any" class="form-control" name="ad_price[image]" value="{{ @$general->ads_setting->ad_price->image }}" required>
                                            <span class="input-group-text">{{ $general->cur_text }}</span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>@lang('Amount For User')</label>
                                        <div class="input-group">
                                            <input type="number" step="any" class="form-control" name="amount_for_user[image]" value="{{ @$general->ads_setting->amount_for_user->image }}" required>
                                            <span class="input-group-text">{{ $general->cur_text }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card mb-4">
                                <div class="card-header bg--primary d-flex justify-content-between">
                                    <h5 class="text-white">@lang('URL Ads')</h5>
                                    <input type="hidden" name="adsName[url]" value="url">
                                    <input type="hidden" name="adsType[]" value="url">
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label>@lang('Ads Price')</label>
                                        <div class="input-group">
                                            <input type="number" step="any" class="form-control" name="ad_price[url]" value="{{ @$general->ads_setting->ad_price->url }}" required>
                                            <span class="input-group-text">{{ $general->cur_text }}</span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>@lang('Amount For User')</label>
                                        <div class="input-group">
                                            <input type="number" step="any" class="form-control" name="amount_for_user[url]" value="{{ @$general->ads_setting->amount_for_user->url }}" required>
                                            <span class="input-group-text">{{ $general->cur_text }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="col-md-3">
                            <div class="card mb-4">
                                <div class="card-header bg--primary d-flex justify-content-between">
                                    <h5 class="text-white">@lang('Youtube Video Ads')</h5>
                                    <input type="hidden" name="adsName[youtube]" value="image">
                                    <input type="hidden" name="adsType[]" value="image">
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label>@lang('Ads Price')</label>
                                        <div class="input-group">
                                            <input type="number" step="any" class="form-control" name="ad_price[youtube]" value="{{ @$general->ads_setting->ad_price->youtube }}" required>
                                            <span class="input-group-text">{{ $general->cur_text }}</span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>@lang('Amount For User')</label>
                                        <div class="input-group">
                                            <input type="number" step="any" class="form-control" name="amount_for_user[youtube]" value="{{ @$general->ads_setting->amount_for_user->youtube }}" required>
                                            <span class="input-group-text">{{ $general->cur_text }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="col-md-3">
                            <div class="card mb-4">
                                <div class="card-header bg--primary d-flex justify-content-between">
                                    <h5 class="text-white">@lang('Facebook Ads')</h5>
                                    <input type="hidden" name="adsName[Facebook]" value="image">
                                    <input type="hidden" name="adsType[]" value="image">
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label>@lang('Ads Price')</label>
                                        <div class="input-group">
                                            <input type="number" step="any" class="form-control" name="ad_price[Facebook]" value="{{ @$general->ads_setting->ad_price->Facebook }}" required>
                                            <span class="input-group-text">{{ $general->cur_text }}</span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>@lang('Amount For User')</label>
                                        <div class="input-group">
                                            <input type="number" step="any" class="form-control" name="amount_for_user[Facebook]" value="{{ @$general->ads_setting->amount_for_user->Facebook }}" required>
                                            <span class="input-group-text">{{ $general->cur_text }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> --}}


                    </div>

{{--
                    <div id="parent">
                        <div>Child 1</div>
                        <div>Child 2</div>
                        <div>Child 3</div>
                        <button id="add-button">Add New Div</button>
                      </div> --}}





                    <div class="card-footer">
                        <button type="submit" class="btn btn--primary w-100 h-45">@lang('Submit')</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@push('script')
    <script>
    (function($){
    "use strict"
        $('[name=user_ads_post]').on('change', function () {
            var isCheck = $(this).prop('checked');
            if (isCheck == true) {
                $('[type=number]').removeAttr('readonly')
            } else {
                $('[type=number]').attr('readonly',true)
            }
        }).change();
    })(jQuery);


    const addButton = document.getElementById("add-button");
const parent = document.getElementById("parent");

addButton.addEventListener("click", () => {
    const elements = document.getElementsByClassName("adscolums");
const count = elements.length;
console.log(count);

  const newDiv = document.createElement("div");
  newDiv.classList.add("col-md-3");
  newDiv.classList.add("adscolums");
  newDiv.innerHTML = `

      @php
        $defaltData = [
          'adsName'=>'',
          'adsType'=>'',
          'uploaded'=>'',
          'IfOr'=>'',
          'ad_price'=>'',
          'amount_for_user'=>''
        ];
        $defaltData = json_decode(json_encode($defaltData));
      @endphp
      <x-ads-settings :adssetting="$defaltData" />

  `;

  // Get the third child element
  const child3 = parent.children[count-1];

  // Insert the new div before the third child
  parent.insertBefore(newDiv, child3);
});

    </script>
@endpush
