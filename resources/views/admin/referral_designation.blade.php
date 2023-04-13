@extends('admin.layouts.app')
@section('panel')
<div class="row">

    <div class="col-md-6">
        @php
            $i = 0;
        @endphp
        <form action="/admin/referrals/designation" method="POST">
            @csrf
            <table class="table">
                <thead>
                    <tr>
                        <th>Designation</th>
                        <th>User Requied</th>
                        <th><button type="button" class="btn btn-info" onclick="addNew()">Add New</button></th>
                    </tr>
                </thead>
                <tbody id="addDesignation">


                    @foreach($designations as $designation)



                    <tr id="row{{ $i }}">
                        <td><input type="text" name="designation[]" value="{{ $designation->designation }}" class="form-control"></td>
                        <td><input type="tel" name="needUser[]" value="{{ $designation->needUser }}"  class="form-control"></td>
                        <td><button type="button" class="btn btn-danger btn_remove"  id="${i}">Remove</button></td>
                    </tr>

                    @php
                        $i++;
                    @endphp
                    @endforeach

                </tbody>
            </table>
            <div class="text-center">
                <input type="submit" value="Update" class="btn btn-info">
            </div>

        </form>
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

<script>
		var i = <?php echo $i+1; ?>;

        console.log(i)

 function addNew(){
    $("#addDesignation").append(`
           <tr id="row${i}">
                    <td><input type="text" name="designation[]" class="form-control"></td>
                    <td><input type="tel" name="needUser[]" class="form-control"></td>
                    <td><button type="button" class="btn btn-danger btn_remove"  id="${i}">Remove</button></td>
                </tr>
                    `);
                    i++;
        }



</script>


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




$(document).ready(function(){
            $(document).on('click','.btn_remove',function(){
                var button_id = $(this).attr("id");
                $("#row"+button_id+"").remove();
            });
        });



    (function($){
        "use strict"



        $(".generate").on('click', function () {
            generrateLevels();
        });

        $(document).on('click', '.deleteBtn', function () {
            $(this).closest('.input-group').remove();
        });

        function generrateLevels(){

            let numberOfLevel = document.getElementById('designation').value;
            console.log(numberOfLevel)

            let parent = document.getElementById('addDesignation');
            let html = '';
            if (numberOfLevel && numberOfLevel > 0){
                parent.find('.levelForm').removeClass('d-none');
                parent.find('.required-message').addClass('d-none');

                for (i = 1; i <= numberOfLevel; i++){
                    html += `
                    <div class="input-group mb-3">
                        <span class="input-group-text justify-content-center">@lang('Level') ${i}</span>
                        <input type="hidden" name="level[]" value="${i}" required>
                        <input name="percent[]" class="form-control col-10" type="text" required placeholder="@lang('Commission Percentage')">
                        <button class="btn btn--danger input-group-text deleteBtn" type="button"><i class=\'la la-times\'></i></button>
                    </div>`
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
