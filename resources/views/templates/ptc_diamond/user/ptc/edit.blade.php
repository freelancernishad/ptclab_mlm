@extends($activeTemplate.'layouts.master')
@section('content')
<link rel="stylesheet" href="{{asset('assets/admin/css/vendor/select2.min.css')}}">
<div class="text-end mb-3">
<a href="{{ route('user.ptc.ads') }}" class="btn btn--base btn-sm">@lang('My Advertisements')</a>
</div>
<div class="card custom--card">
<div class="card-body">
    <form role="form" method="POST" action="{{ route("user.ptc.update",$ptc->id) }}" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="IfrOr" value="{{ $ptc->IfrOr }}">
        <div class="row">
            <div class="form-group col-md-6">
                <label class="form-label">@lang('Title of the Ad')</label>
                <input type="text" name="title" class="form-control form--control" value="{{ $ptc->title }}" required>
            </div>

            <div class="form-group col-md-6">
                <label class="form-label">@lang('Duration')</label>
                <div class="input-group">
                    <input type="number" name="duration" class="form-control form--control" value="{{ $ptc->duration }}" required>
                    <div class="input-group-text">@lang('SECONDS')</div>
                </div>
            </div>

            <div class="form-group col-md-4">
                <label for="ads_type">@lang('Advertisement Type')</label>
                <input type="hidden" name="ads_type" value="{{$ptc->ads_type}}">
                <div class="pt-3">
                    @php echo $ptc->typeBadge @endphp
                </div>
            </div>

            @if($ptc->IfrOr=='iframe')


            @if($ptc->ads_type == 1)

            <div class="form-group col-md-8">
                <label class="form-label">@lang('Link') <span class="text-danger">*</span></label>
                <input type="text" name="website_link" class="form-control form--control" value="{{ $ptc->ads_body }}" placeholder="@lang('http://example.com')">
            </div>
            @elseif($ptc->ads_type == 2)

            <div class="form-group col-md-4 ">
                <label class="form-label">@lang('Banner')</label>
                <input type="file" class="form-control form--control"  name="banner_image">
            </div>
                <div class="form-group col-md-4 ">
                <label class="form-label">@lang('Current Banner') <span class="text-danger">*</span></label>
                <img src="{{ getImage(getFilePath('ptc').'/'.$ptc->ads_body) }}" class="w-100">
            </div>

            @elseif($ptc->ads_type == 3)

            <div class="form-group col-md-8">
                <label class="form-label">@lang('Script') <span class="text-danger">*</span></label>
                <textarea  name="script" class="form-control form--control">{{ $ptc->ads_body }}</textarea>
            </div>

            @else
                <div class="form-group col-md-8">
                    <label class="form-label">@lang('Youtube Embaded Link') <span class="text-danger">*</span></label>
                    <input type="text" name="youtube" class="form-control form--control" value="{{ $ptc->ads_body }}">
                </div>
            @endif


            @else

            <div class="form-group col-md-12">
                <label>@lang('Description') <span class="text-danger">*</span></label>
                <textarea class="tinymce-editor" name="ads_body">{{ $ptc->ads_body }}</textarea>

            </div>

            @php
               $filesSupports = json_decode($ptc->filesSupports);
            @endphp

            <div class="form-group col-md-12">
                <label class="form-label">@lang('Prove Files')</label>
                <select class="select2-multi-select form-control" name="filesSupports[]" multiple>
                    <option @if(is_array($filesSupports))  @if(in_array('jpg', $filesSupports)) selected @endif @endif value="jpg">JPG</option>
                    <option @if(is_array($filesSupports))  @if(in_array('jpeg', $filesSupports)) selected @endif @endif value="jpeg">JPEG</option>
                    <option @if(is_array($filesSupports))  @if(in_array('png', $filesSupports)) selected @endif @endif value="png">PNG</option>
                    <option @if(is_array($filesSupports))  @if(in_array('mp4', $filesSupports)) selected @endif @endif value="mp4">MP4</option>
                    <option @if(is_array($filesSupports))  @if(in_array('pdf', $filesSupports)) selected @endif @endif value="pdf">PDF</option>
                    <option @if(is_array($filesSupports))  @if(in_array('doc', $filesSupports)) selected @endif @endif value="doc">DOC</option>
                    <option @if(is_array($filesSupports))  @if(in_array('docx', $filesSupports)) selected @endif @endif value="docx">DOCX</option>
                    <option @if(is_array($filesSupports))  @if(in_array('txt', $filesSupports)) selected @endif @endif value="txt">TXT</option>
                    <option @if(is_array($filesSupports))  @if(in_array('xlx', $filesSupports)) selected @endif @endif value="xlx">XLX</option>
                    <option @if(is_array($filesSupports))  @if(in_array('xlsx', $filesSupports)) selected @endif @endif value="xlsx">XLSX</option>
                    <option @if(is_array($filesSupports))  @if(in_array('csv', $filesSupports)) selected @endif @endif value="csv">CSV</option>
                </select>

            </div>


            @endif


        </div>
        <div class="form-group col-md-12">
            <button type="submit" class="btn btn--base btn--lg w-100">@lang('Submit')</button>
        </div>
    </form>
</div>
@endsection
@push('script')
<script src="{{asset('assets/admin/js/vendor/select2.min.js')}}"></script>
<script src="https://cdn.tiny.cloud/1/nhnny39zzu3w0euy077ojdf9gk1n3mjpkobk25i228rt3qkz/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
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
@endpush

