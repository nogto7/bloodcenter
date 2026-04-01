@if (Session::has('toast'))
    @php
        $toasts = session('toast');
        if (isset($toasts[0]) && $toasts[0] == 1) {
            //if success
            $toasts[0] = ['type' => 'success', 'message' => 'Амжилттай хадгаллаа'];
        } elseif (isset($toasts['type'])) {
            //if error
            $errorToast = $toasts;
            $toasts = [];
            $toasts[0] = $errorToast;
        }
    @endphp
    @foreach ($toasts as $key => $toast)
        <div id="form_toast{{ $key }}" class="form_toast {{ $toast['type'] }} show"
            style="margin-top: {{ $key * 90 }}px">
            <div class="toast_content">
                <div class="toast-icon"><span></span></div>
                <div class="toast_desc">
                    <h3 class="dcsb">
                        {{ $toast['type'] == 'info' ? 'Анхааруулга' : ($toast['type'] == 'danger' ? 'Алдаа' : 'Амжилттай') }}
                        <div class="toast_close" onclick="closeToast('form_toast{{ $key }}')"><span></span></div>
                    </h3>
                    <p>{{ $toast['message'] }}</p>
                </div>
            </div>
        </div>
    @endforeach
@endif
{{-- Dynamic toast --}}
<div id="toast" class="form_toast">
    <div class="toast_content">
        <div class="toast-icon"><span></span></div>
        <div class="toast_desc">
            <h3>
                <span id="toastTitle"></span>
            </h3>
            <p id="toastMessage"></p>
        </div>
        <div class="toast_close" onclick="closeToast('toast')"><span></span></div>
    </div>
</div>
{{-- End of dynamic toast --}}
<script>
    $(document).ready(function() {
        if ($('.form_toast').length) {
            $('.form_toast').each(function() {
                if ($(this).hasClass('show')) {
                    const id = ($(this).attr('id'));
                    const timeoutDuration = $(this).hasClass('success') ? 3000 : 120000;
                    setTimeout(() => {
                        closeToast(id);
                    }, timeoutDuration);
                }
            });
        }
    });

    function closeToast(id) {
        $("#" + id).removeClass("show");
    }
</script>
