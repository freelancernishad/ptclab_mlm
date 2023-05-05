
<div class="card mb-4"  >
    <div class="card-header bg--primary d-flex justify-content-between">
        <h5 class="text-white d-flex justify-content-between w-100">{{ $adssetting->adsName }} <span style="cursor: pointer;padding: 0 5px;color: red;" onclick="removethisItem('adsCard{{ $adssetting->id }}')">X</span></h5>

    </div>
    <div class="card-body">
        <div class="form-group">
            <label>Name of adds</label>
            <div class="input-group">
                <input type="hidden"  class="form-control" name="id[]" value="{{ $adssetting->id }}" required>
                <input type="text"  class="form-control" name="adsName[]" value="{{ $adssetting->adsName }}" required>

            </div>
        </div>

        <div class="form-group">
            <label>Type of adds</label>
            <div class="input-group">
                <input type="text"  class="form-control" name="adsType[]" value="{{ $adssetting->adsType }}" required>

            </div>
        </div>

        <div class="form-group">
            <label>Uploaded</label>
            <div class="input-group">
                <select name="uploaded[]" class="form-control" value="{{ $adssetting->uploaded }}" required>
                    <option value="">Select</option>
                    <option value="1" @php if($adssetting->uploaded==1)echo'selected'; @endphp>Url</option>
                    <option value="2" @php if($adssetting->uploaded==2)echo'selected'; @endphp>Image</option>
                    <option value="3" @php if($adssetting->uploaded==3)echo'selected'; @endphp>Script/code</option>
                    <option value="4" @php if($adssetting->uploaded==4)echo'selected'; @endphp>Youtube link</option>
                </select>
            </div>
        </div>
{{--
        <div class="form-group">
            <label>Iframe/Task ads</label>
            <div class="input-group">
                <select name="IfOr[]" class="form-control" value="{{ $adssetting->IfOr }}" required>
                    <option value="">Select</option>
                    <option @php if($adssetting->IfOr=='Iframe')echo'selected'; @endphp>Iframe</option>
                    <option @php if($adssetting->IfOr=='Task ads')echo'selected'; @endphp>Task ads</option>
                </select>
            </div>
        </div> --}}
        <div class="form-group">
            <label>@lang('Ads Price')</label>
            <div class="input-group">
                <input type="number" step="any" class="form-control" name="ad_price[]" value="{{ $adssetting->ad_price }}" required>
                <span class="input-group-text">{{ $general->cur_text }}</span>
            </div>
        </div>
        <div class="form-group">
            <label>@lang('Amount For User')</label>
            <div class="input-group">
                <input type="number" step="any" class="form-control" value="{{ $adssetting->amount_for_user }}" name="amount_for_user[]" required>
                <span class="input-group-text">{{ $general->cur_text }}</span>
            </div>
        </div>
    </div>
</div>
