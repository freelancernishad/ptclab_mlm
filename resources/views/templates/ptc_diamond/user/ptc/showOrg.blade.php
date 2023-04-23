@extends($activeTemplate.'layouts.frontend')
@section('content')
@php
	$infos = getContent('contact.element');
	$contact = getContent('contact.content',true);
@endphp
<div class="section--sm">
	<div class="container">
		<div class="row g-4 align-items-center">
			<div class="col-lg-12">

                <h3>{{ $ptc->title }}</h3>

                <div class="card">
                    <div class="card-body">
                        <h5>Job Description :</h5>
                        <br/>
                        <br/>
                        {!! $ptc->ads_body !!}
                    </div>
                </div>

                <br/>
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('user.ptc.confirm', encrypt($ptc->id . '|' . auth()->user()->id)) }}" id="confirm-form" method="post" enctype="multipart/form-data">
                            @csrf


                        <h5>Submit Your Completed Job</h5>

                        <div class="form-group">
                            <label for="">Description</label>
                            <textarea name="description" id="" cols="30" rows="5" class="form-control" style="resize: :none"></textarea>
                        </div>
                        <h6>SCREENSHOT PROOF REQUIRED #1 </h6>

                        @php
                            $filesSupports = json_decode($ptc->filesSupports);
                        @endphp

                        <div class="form-group">
                            <label for="">Proof 1</label>
                            <input type="file" name="file[0]" accept="@if(is_array($filesSupports)) @foreach($filesSupports as $ext) .{{ $ext }}, @endforeach @else image/*  @endif" class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="">Proof 2</label>
                            <input type="file"  name="file[1]" accept="@if(is_array($filesSupports)) @foreach($filesSupports as $ext) .{{ $ext }}, @endforeach  @else image/* @endif" class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="">Proof 3</label>
                            <input type="file"  name="file[2]" accept="@if(is_array($filesSupports)) @foreach($filesSupports as $ext) .{{ $ext }}, @endforeach  @else image/* @endif" class="form-control">
                        </div>

                        <br/>
                        <br/>

                        <div class="form-group">

                            <input type="submit" class="btn btn-info">
                        </div>


                    </form>

                    </div>
                </div>

			</div>

		</div>
	</div>
</div>

@endsection
