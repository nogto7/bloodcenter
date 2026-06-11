{{-- Нэгдсэн TinyMCE тохиргоо. Хувьсагч: $selector (default '#content'), $uploadUrl (default admin.news.upload) --}}
@php
    $selector = $selector ?? '#content';
    $uploadUrl = $uploadUrl ?? route('admin.news.upload');
@endphp
<script src="/js/tinymce/tinymce.min.js"></script>
<script>
tinymce.init({
    selector: '{{ $selector }}',
    height: 500,
    license_key: 'gpl',
    plugins: 'image table lists link code',
    toolbar: `
        undo redo | bold italic underline |
        alignleft aligncenter alignright alignjustify |
        bullist numlist |
        table image link |
        code
    `,
    menubar: true,
    // URL-ыг хуудаснаас хамааралгүй root-relative (/storage/...) хэлбэрээр хадгална,
    // эс бөгөөс ../storage/... болж edit болон бусад хуудсанд зураг харагдахгүй
    relative_urls: false,
    remove_script_host: true,
    images_upload_url: "{{ $uploadUrl }}",
    automatic_uploads: true,
    file_picker_types: 'image',
    image_title: true,

    // AJAX form-ууд FormData(this)-аар textarea-г уншдаг тул editor-ийн
    // агуулгыг textarea руу байнга sync хийнэ
    setup: function (editor) {
        editor.on('change input undo redo SetContent', function () {
            editor.save();
        });
    },

    images_upload_handler: function (blobInfo, progress) {
        return new Promise((resolve, reject) => {
            let xhr = new XMLHttpRequest();
            xhr.open('POST', "{{ $uploadUrl }}");

            xhr.setRequestHeader(
                'X-CSRF-TOKEN',
                document.querySelector('meta[name="csrf-token"]').content
            );

            xhr.onload = function () {
                if (xhr.status !== 200) {
                    reject('HTTP Error: ' + xhr.status);
                    return;
                }

                let json = JSON.parse(xhr.responseText);

                if (!json.location) {
                    reject('Invalid response');
                    return;
                }

                resolve(json.location);
            };

            xhr.onerror = function () {
                reject('Network error');
            };

            let formData = new FormData();
            formData.append('file', blobInfo.blob(), blobInfo.filename());

            xhr.send(formData);
        });
    }
});
</script>
