
@if($type == "original")

    <div class="form-group col-md-12">
        <label for="ads_type">@lang('Advertisement Type')</label>
        <select class="form-control" id="ads_type" name="ads_type" required>
            @foreach ($adssettings as $ads_setting)
            <option value="{{ $ads_setting->uploaded }}" {{ old('ads_type')==$ads_setting->adsType?'selected':'' }}>{{ $ads_setting->adsName }}</option>
            @endforeach

        </select>
    </div>

    <div class="form-group col-md-12">
        <label for="ads_type">@lang('Description')</label>
        <textarea class="tinymce-editor" name="ads_body"></textarea>
    </div>

    <script>
        tinymce.init({
            selector: 'textarea.tinymce-editor',
            height: 300,
            menubar: false,
            plugins: [
                'advlist autolink lists link image charmap print preview anchor',
                'searchreplace visualblocks code fullscreen',
                'insertdatetime media table paste code help wordcount', 'image'
            ],
            toolbar: 'undo redo | formatselect | ' +
                'bold italic backcolor | alignleft aligncenter ' +
                'alignright alignjustify | bullist numlist outdent indent | ' +
                'removeformat | help',
            content_css: '//www.tiny.cloud/css/codepen.min.css'
        });


    </script>



    @elseif($type == "iframe")


    <div class="form-group col-md-4">
        <label for="ads_type">@lang('Advertisement Type')</label>
        <select class="form-control" id="ads_type" name="ads_type"  required>
            <option value="">Select</option>
            @foreach ($adssettings as $ads_setting)
            <option value="{{ $ads_setting->uploaded }}" {{ old('ads_type')==$ads_setting->adsType?'selected':'' }}>{{ $ads_setting->adsName }}</option>
            @endforeach


        </select>
    </div>


    <div class="form-group col-md-8" id="inputField"></div>




<script>

    $('#ads_type').change(function(){
        var ads_type = $(this).val();
        if(ads_type==1){

            var html = `
            <label>@lang('Link')</label>
            <input type="text" name="website_link" class="form-control" value="{{ old('website_link') }}" placeholder="@lang('http://example.com')">
            `;
            $('#inputField').html(html);

        }else if(ads_type==2){

            var html = `
            <label>@lang('Banner')</label>
    <input type="file" class="form-control"  name="banner_image">
            `;
            $('#inputField').html(html);

        }else if(ads_type==3){

            var html = `
            <label>@lang('Script')</label>
    <textarea  name="script" class="form-control">{{ old('script') }}</textarea>
            `;
            $('#inputField').html(html);

        }else if(ads_type==4){

            var html = `
            <label>@lang('Youtube Embeded Link')</label>
            <input type="text" name="youtube" class="form-control" value="{{ old('youtube') }}" placeholder="@lang('https://www.youtube.com/embed/your_code')">
            `;
            $('#inputField').html(html);

        }

    });


</script>

@endif



