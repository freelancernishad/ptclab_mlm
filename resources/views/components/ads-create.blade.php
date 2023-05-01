
@if($type == "Task ads")

    <div class="form-group col-md-12">
        <label for="ads_type">@lang('Advertisement Type')</label>
        <select class="form-control" id="ads_type" name="ads_type" required>
            @foreach ($adssettings as $ads_setting)
            <option value="{{ $ads_setting->id }}" {{ old('ads_type')==$ads_setting->id?'selected':'' }}>{{ $ads_setting->adsName }}</option>
            @endforeach

        </select>
    </div>

    <div class="form-group col-md-12">
        <label for="ads_type">@lang('Description')</label>
        <textarea class="tinymce-editor" name="ads_body"></textarea>
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



    <script>
        $('.select2-multi-select').select2();
        tinymce.init({
            selector: 'textarea.tinymce-editor',
            height: 300,
            menubar: false,
            plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
            toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
        });


    </script>



    @elseif($type == "iframe")


    <div class="form-group col-md-4">
        <label for="ads_type">@lang('Advertisement Type')</label>
        <select class="form-control" id="ads_type" name="ads_type"   required>
            <option value="">Select</option>
            @foreach ($adssettings as $ads_setting)
            <option value="{{ $ads_setting->id }}" {{ old('ads_type')==$ads_setting->id?'selected':'' }}>{{ $ads_setting->adsName }}</option>
            @endforeach


        </select>
        <pre class="text--danger">@lang('Price per ad') <span class="price-per-ad"></span> {{ $general->cur_text }}</pre>
    </div>


    <div class="form-group col-md-8" id="inputField"></div>






<script>




   function ads_typeChange(ads_type,result){

        var price = result.ad_price;

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

        $('.price-per-ad').text(price);
        $('[name=max_show]').trigger('input');
    };



</script>

@endif



