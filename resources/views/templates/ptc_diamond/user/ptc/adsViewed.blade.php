@extends($activeTemplate.'layouts.master')
@section('content')

<div class="text-end mb-3">
    <a href="{{ route('user.ptc.create') }}" class="btn btn--base btn-sm">@lang('Create Advertisement')</a>
</div>

<div class="custom--table-container table-responsive--md">
    <table class="table table custom--table">
        <thead>
            <tr>
                <th scope="col">@lang('Date')</th>
                <th scope="col">@lang('Username')</th>
                <th scope="col">@lang('Status')</th>
                <th scope="col">@lang('Action')</th>
            </tr>
        </thead>
        <tbody>
            @forelse($ads as $ptc)
            <tr>
                <td>{{ date('Y-m-d H:i', strtotime($ptc->created_at)) }}</td>
                <td>{{ $ptc->user->username }}</td>
                <td>
                    @if ($ptc->status=='pending')
                    <span class="btn btn-warning">Pending</span>
                @elseif ($ptc->status=='Rejected')
                    <span class="btn btn-danger">Rejected</span>
                @else
                    <span class="btn btn-success">Completed</span>
                @endif
                </td>



                <td>
                    <a class="btn btn--success btn-sm" href="{{route('user.ptc.viewed.status',$ptc->id)}}">View</a>
                </td>

            </tr>
            @empty
            <tr>
                <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>

@if($ads->hasPages())
    <div class="d-flex justify-content-end mt-4">
        {{ $ads->links() }}
    </div>
@endif

<div id="detailModal" class="modal custom--modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Details')</h5>
                <span type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="las la-times"></i>
                </span>
            </div>
            <div class="modal-body">
                <ul class="list-group adData mb-2">

                </ul>
                <div class="feedback"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-dark btn-sm" data-bs-dismiss="modal">@lang('Close')</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('script')
    <script>
        (function ($) {
            "use strict";
            $('.detailBtn').on('click', function () {
                var modal = $('#detailModal');
                var html = '';

                var title = $(this).data('title');
                html += `<li class="list-group-item d-flex justify-content-between align-items-center">
                        <span>@lang('Title')</span>
                        <span">${title}</span>
                    </li>`;

                var duration = $(this).data('duration');
                html += `<li class="list-group-item d-flex justify-content-between align-items-center">
                        <span>@lang('Duration')</span>
                        <span">${duration}</span>
                    </li>`;

                var maxShow = $(this).data('max_show');
                html += `<li class="list-group-item d-flex justify-content-between align-items-center">
                        <span>@lang('Maximum Show')</span>
                        <span">${maxShow}</span>
                    </li>`;

                var viewed = $(this).data('viewed');
                html += `<li class="list-group-item d-flex justify-content-between align-items-center">
                        <span>@lang('Viewed')</span>
                        <span">${viewed}</span>
                    </li>`;

                var remain = $(this).data('remain');
                html += `<li class="list-group-item d-flex justify-content-between align-items-center">
                        <span>@lang('Remain')</span>
                        <span">${remain}</span>
                    </li>`;

                var amount = $(this).data('amount');
                html += `<li class="list-group-item d-flex justify-content-between align-items-center">
                        <span>@lang('Amount')</span>
                        <span">${amount}</span>
                    </li>`;

                modal.find('.adData').html(html);
                modal.modal('show');
            });
        })(jQuery);

    </script>
@endpush
