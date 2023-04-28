@extends($activeTemplate.'layouts.master')
@section('content')

<style>
    .viewedImages{
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr) );
        grid-gap: 25px;
    }

    .viewedImages div {
    border: 1px solid #00000036;
    padding: 11px 8px;
}
.viewedImages div label {
    font-size: 20px;
    font-weight: 800;
    border-bottom: 1px solid #00000054;
    width: 100%;
    margin-bottom: 15px;
}
.viewedImages div {
    cursor: pointer;
}
.imagePopupOverlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: #00000080;
    z-index: 9999;
}
</style>

<div class="section--sm">
	<div class="container">
		<div class="row g-4 align-items-center">
			<div class="col-lg-12">

                <h3>{{ $ptc->ptc->title }}</h3>

                <div class="card">
                    <div class="card-body">
                        <h5>Job Description :</h5>
                        <br/>
                        <br/>
                        {!! $ptc->ptc->ads_body !!}
                    </div>
                </div>

                <br/>
                <div class="card">
                    <div class="card-body">





                        <div class="form-group">
                            <label for=""> Submited Description</label>
                            <p>{!! $ptc->description !!}</p>
                        </div>
                        <h6>SCREENSHOT PROOF REQUIRED #1 </h6>

                        <div class="viewedImages">
                        @foreach (json_decode($ptc->files) as $file)
                            <div class="form-group" title="Click to Zoom This Image" onclick="zoomImage('{{ getImage(getFilePath('ptcView').'/'.$file) }}')">
                                <label for="">Proof 1</label>

                                @php
                                    $ext = pathinfo($filename, PATHINFO_EXTENSION);


                                @endphp

                                @if($ext=='.mp4')
                                <video src="{{ getImage(getFilePath('ptcView').'/'.$file) }}" class="w-100"></video>
                                @else
                                <img src="{{ getImage(getFilePath('ptcView').'/'.$file) }}" class="w-100">
                                @endif



                            </div>
                        @endforeach
                    </div>

                        <br/>
                        <br/>

                        <div class="form-group" style="display: flex;grid-gap: 14px;">
                            <form action="{{ route('user.ptc.confirm.status', $ptc->id ) }}" id="confirm-form" method="post" enctype="multipart/form-data">
                                    @csrf
                                <input type="submit" name="status" class="btn btn-danger" value="Reject">
                            </form>
                            <form action="{{ route('user.ptc.confirm.status', $ptc->id ) }}" id="confirm-form" method="post" enctype="multipart/form-data">
                                @csrf
                                <input type="submit" name="status" class="btn btn-info" value="Approve">
                            </form>
                        </div>




                    </div>
                </div>

			</div>

		</div>
	</div>
</div>

    {{-- <div class="imagePopupOverlay" id="imagePopupOverlay" style="display: none">
        <div class="imagePopup">
            <img id="popImg" width="100%" src="" alt="">
        </div>
    </div> --}}



<script>
    function zoomImage(img) {
        window.open(
              `${img}`, "_blank");
    }

</script>
@endsection
