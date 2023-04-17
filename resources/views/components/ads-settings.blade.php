
<div class="card mb-4">
    <div class="card-header bg--primary d-flex justify-content-between">
        <h5 class="text-white">{{ $adssetting->adsName }}</h5>

    </div>
    <div class="card-body">
        <div class="form-group">
            <label>Name of adds</label>
            <div class="input-group">
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
                    <option @php if($adssetting->uploaded=='Image')echo'selected'; @endphp>Image</option>
                    <option @php if($adssetting->uploaded=='Url')echo'selected'; @endphp>Url</option>
                    <option @php if($adssetting->uploaded=='Youtube link')echo'selected'; @endphp>Youtube link</option>
                    <option @php if($adssetting->uploaded=='Video')echo'selected'; @endphp>Video</option>
                    <option @php if($adssetting->uploaded=='Script/code')echo'selected'; @endphp>Script/code</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label>Iframe/Original</label>
            <div class="input-group">
                <select name="IfOr[]" class="form-control" value="{{ $adssetting->IfOr }}" required>
                    <option value="">Select</option>
                    <option @php if($adssetting->IfOr=='Iframe')echo'selected'; @endphp>Iframe</option>
                    <option @php if($adssetting->IfOr=='Original')echo'selected'; @endphp>Original</option>
                </select>
            </div>
        </div>
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
