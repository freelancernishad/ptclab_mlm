
@if($type == "Task ads")
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


    <div class="form-group col-md-8" id="inputField"></div>






<script>






</script>

@endif



