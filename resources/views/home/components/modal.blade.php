<div class="modal fade content-center" id="{{ $id }}" tabindex="-1" role="dialog" style="display:none">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            @if(isset($title))
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">{{ $title }}</h4>
                </div>
            @endif
            <div class="modal-body">
                {{ $slot }}
            </div>
            <div class="modal-footer">
                <div class="text-center">
                    @if(isset($hasCancel) ? $hasCancel : false)
                        <button type="button" class="btn btn-danger" data-dismiss="modal">{{ $cancelText or '关闭' }}</button>
                    @endif

                    @if(isset($hasConfirm) ? $hasConfirm : false)
                        <button type="button" class="btn btn-success">{{ $confirmText or '确认' }}</button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>