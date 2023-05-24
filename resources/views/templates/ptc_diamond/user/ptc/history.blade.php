@extends($activeTemplate.'layouts.master')
@section('content')
<div class="custom--table-container table-responsive--md">
    <table class="table table custom--table">
        <thead>
          <tr>
              <th scope="col">@lang('Date')</th>
              <th scope="col">@lang('Total Earn')</th>
              <th scope="col">@lang('Status')</th>
              {{-- <th scope="col">@lang('Reject Reson')</th> --}}

          </tr>
      </thead>
        <tbody>
           @forelse($viewads as $view)
           <tr>
                <td class=""> {{ showDateTime($view->created_at, 'd M, Y | h:i A') }} </td>

                <td>
                    {{ showAmount($view->amount) }} {{ $general->cur_text }}
                </td>
               <td>
                        @if ($view->status=='pending')
                        <span class="btn btn-warning">Pending</span>
                        @elseif ($view->status=='Rejected')
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#exampleModal" data-bs-reson="{{ $view->rejectReason }}">Rejected</button>
                            {{-- <span class="btn btn-danger">Rejected</span> <br> --}}

                        @else
                            <span class="btn btn-success">Completed</span>
                        @endif

                </td>
                {{-- <td> @if ($view->status=='Rejected') <p>{{ $view->rejectReason }}</p>  @endif</td> --}}
            </tr>
          @empty
              <tr>
                  <td class="text-center" colspan="100%">{{ __($emptyMessage) }}</td>
              </tr>
          @endforelse
      </tbody>
  </table>
</div>

<div class="d-flex justify-content-end mt-4">
    {{paginateLinks($viewads)}}
</div>





<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Reason of Reject</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body" id="formModalId">

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>

        </div>
      </div>
    </div>
  </div>





  <script>

var exampleModal = document.getElementById('exampleModal')
exampleModal.addEventListener('show.bs.modal', function (event) {
  // Button that triggered the modal
  var button = event.relatedTarget
  // Extract info from data-bs-* attributes
  var reson = button.getAttribute('data-bs-reson')

  var formModalId = exampleModal.querySelector('#formModalId');


  formModalId.innerHTML=reson

})
  </script>



@endsection
