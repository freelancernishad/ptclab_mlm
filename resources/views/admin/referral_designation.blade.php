@extends('admin.layouts.app')
@section('panel')
<div class="row">

    <div class="col-md-6">

            <table class="table">
                <thead>
                    <tr>
                        <th>Level</th>
                        <th>Designation</th>
                        <th>User Requied</th>

                    </tr>
                </thead>
                <tbody id="addDesignation">


                    @foreach($designations as $designation)

                    <tr>
                        <td>Level-{{ $designation->id }}</td>
                        <td>{{ $designation->designation }}</td>
                        <td>{{ $designation->needUser }}</td>
                    </tr>
                    @endforeach

                </tbody>
            </table>




            <div class="card-body">
                <div class="form-group">
                    <label>@lang('Number of Designation')</label>
                    <div class="input-group">
                        <input type="number" name="level" min="1" placeholder="Type a number & hit ENTER ↵" class="form-control">
                        <button type="button" class="btn btn--primary generate">@lang('Generate')</button>
                    </div>
                    <span class="text--danger required-message d-none">@lang('Please enter a number')</span>
                </div>

                <form action="/admin/referrals/designation" method="POST">
                    @csrf
                        <h6 class="text--danger mb-3">@lang('The Old setting will be removed after generating new')</h6>
                        <div class="form-group">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Level</th>
                                        <th>Designation</th>
                                        <th>User Requied</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody class="referralLevels">

                                </tbody>
                            </table>

                            {{-- <div class="referralLevels"></div> --}}
                        </div>
                    <button type="submit" class="btn btn--primary h-45 w-100">@lang('Submit')</button>
                </form>

            </div>
    </div>



    <div class="col-md-6">
        <table class="table">
            <thead>
                <tr>

                    <th>Designation</th>
                    <th>Total User</th>
                </tr>
            </thead>
            <thead>
                @foreach($designationArray as $value)

                <tr>
                    <td>{{ $value['designation'] }}</td>
                    <td><a href="/admin/referrals/designation/user/{{ $value['id'] }}">{{ $value['totaluser'] }}</a></td>
                </tr>
                @endforeach
            </thead>
        </table>
    </div>

</div>



@endsection
@push('style')
    <style>
        .border-line-area {
            position: relative;
            text-align: center;
            z-index: 1;
        }
        .border-line-area::before {
            position: absolute;
            content: '';
            top: 50%;
            left: 0;
            width: 100%;
            height: 1px;
            background-color: #e5e5e5;
            z-index: -1;
        }
        .border-line-title {
            display: inline-block;
            padding: 3px 10px;
            background-color: #fff;
        }

    </style>
@endpush
@push('script')
<script>
    (function($){
        "use strict"

        $('[name="level"]').on('focus', function(){
            $(this).on('keyup', function(e){
                if(e.which == 13){
                    generrateLevels($(this));
                }
            });
        });

        $(".generate").on('click', function () {
            let $this = $(this).parents('.card-body').find('[name="level"]');
            generrateLevels($this);
        });

        $(document).on('click', '.deleteBtn', function () {
            $(this).closest('.inputRow').remove();
        });

        function generrateLevels($this){
            let numberOfLevel = $this.val();
            let parent = $this.parents('.card-body');
            let html = '';
            if (numberOfLevel && numberOfLevel > 0){
                parent.find('.levelForm').removeClass('d-none');
                parent.find('.required-message').addClass('d-none');

                for (i = 1; i <= numberOfLevel; i++){
                    html += `<tr class="inputRow">
                        <td>Level-${i}</td>
                        <td><input type="text" name="designation[]" class="form-control"></td>
                        <td><input type="tel" name="needUser[]" class="form-control"></td>
                        <td><button class="btn btn--danger input-group-text deleteBtn" type="button"><i class=\'la la-times\'></i></button></td>
                    </tr>`
                }

                parent.find('.referralLevels').html(html);
            }else {
                parent.find('.levelForm').addClass('d-none');
                parent.find('.required-message').removeClass('d-none');
            }
        }

    })(jQuery);
    </script>
@endpush
