@extends('admin.layout')
@section('content')
<div class="admin_root folder_header">
    <div class="dcsb">
        <div class="n_breadcrumb dfc">
            <ul>
                <li><a href=""><span class="bread_home"></span>Эхлэл</a></li>
                <li><p>Албадын жагсаалт</p></li>
            </ul>
        </div>
        <div class="fline_header dfc">
            <div class="file_options dfc">
                <button class="f_f_button f_delete" id="btnDelete" disabled><span></span>Устгах</button>
            </div>
            <div class="folder_file dfc">
                <button class="f_f_button f_file" data-bs-toggle="modal" data-bs-target="#faqModal"><span></span>FAQ нэмэх</button>
            </div>
        </div>
    </div>
</div>
<div class="admin_file_wrap">
    <div class="file_content">
        <h2>Алба</h2>
        <div class="table_wrap">
            <table class="table_content">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Асуулт</th>
                        <th>Хариулт</th>
                        <th>Үйлдэл</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($faqs as $key => $faq)
                    <tr>
                        <td style="width: 45px">{{ $key+1 }}</td>
                        <td>{{ $faq->question }}</td>
                        <td>{{ strip_tags(Str::limit($faq->answer, 240)) }}</td>
                        <td style="width: 170px">
                            <div class="dfc">
                                <button type="button" class="f_f_button f_edit edit-faq-btn" data-id="{{ $faq->id }}"><span></span>Засах</button>
                                <button class="f_f_button f_delete" data-id="{{ $faq->id }}"><span></span>Утсгах</button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="faqModal">
    <div class="modal-dialog">
        <div class="core_modal">
            <div class="modal_header">
                <h2>FAQ</h2>
            </div>

            <form id="faqForm">
                @csrf
                <input type="hidden" id="faq_id">
                <div class="modal_main">
                    <div class="form_item">
                        <label class="form_label">Асуулт</label>
                        <input type="text" name="question" class="form_input">
                    </div>

                    <div class="form_item">
                        <label class="form_label">Хариулт</label>
                        <textarea name="answer" id="faq_answer"></textarea>
                    </div>
                </div>
                <div class="modal_footer">
                    <button type="submit" class="__btn btn_primary">
                        Хадгалах
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="faqEditModal" tabindex="-1" aria-labelledby="transfusionModal" aria-hidden="true" role="dialog">
    <div class="modal-dialog">
        <div class="core_modal">
            <div class="modal_header">
                <h2>FAQ</h2>
            </div>

            <form id="faqEditForm">
                @csrf
                <input type="hidden" id="faq_edit_id">
                <div class="modal_main">
                    <div class="form_item">
                        <label class="form_label">Асуулт</label>
                        <input type="text" name="question" class="form_input">
                    </div>

                    <div class="form_item">
                        <label class="form_label">Хариулт</label>
                        <textarea id="faq_edit_answer" name="answer"></textarea>
                    </div>
                </div>
                <div class="modal_footer">
                    <button type="submit" class="__btn btn_primary">
                        Хадгалах
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="/js/tinymce/tinymce.min.js"></script>

<script>
    tinymce.init({
        selector: '#faq_answer, #faq_edit_answer',
        height: 500,
        license_key: 'gpl',
        plugins: 'image table lists link code',
        toolbar: `
            undo redo | bold italic underline |
            alignleft aligncenter alignright |
            bullist numlist |
            table image link |
            code
        `,
        menubar: true
    });

    document.getElementById('faqForm').addEventListener('submit', function(e){
        e.preventDefault();

        const id = document.getElementById('faq_id').value;

        const formData = new FormData(this);

        // TinyMCE content
        formData.set('answer', tinymce.get('faq_answer').getContent());

        let url = '/admin/faq';
        if(id){
            url = `/admin/faq/${id}`;
            formData.append('_method', 'PUT');
        }

        fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: formData
        })
        .then(res => res.json())
        .then(() => location.reload());
    });
    
    document.getElementById('faqEditForm').addEventListener('submit', function(e){
        e.preventDefault();

        const id = document.getElementById('faq_edit_id').value;

        const formData = new FormData(this);
        formData.set('answer', tinymce.get('faq_edit_answer').getContent());

        let url = '/admin/faq';
        if(id){
            url = `/admin/faq/${id}`;
            formData.append('_method', 'PUT');
        }

        fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: formData
        })
        .then(res => res.json())
        .then(() => location.reload());
    });

    document.querySelectorAll('.edit-faq-btn').forEach(btn => {
        btn.addEventListener('click', function(){

            // document.getElementById('faq_edit_id').value = id;

            const id = this.dataset.id;

            // fetch(`/admin/faq/${id}/json`)
            fetch(`/admin/faq/${id}/json`)
                .then(res => {
                    if (!res.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return res.json(); // ⭐ ЭНД JSON болгоно
                })
                .then(data => {
                const form = document.getElementById('faqEditForm');

                document.getElementById('faq_edit_id').value = id; // ⭐ ЭНЭ ЧУХАЛ

                form.dataset.id = id;
                form.querySelector('[name="question"]').value = data.question;

                const modalEl = document.getElementById('faqEditModal');
                const modal = new bootstrap.Modal(modalEl);
                modal.show();

                modalEl.addEventListener('shown.bs.modal', function () {
                    if (tinymce.get('faq_edit_answer')) {
                        tinymce.get('faq_edit_answer').setContent(data.answer ?? '');
                    }
                }, { once: true });
            });
        });
    });

    document.querySelectorAll('.f_delete[data-id]').forEach(btn => {
        btn.addEventListener('click', function(){

            if(!confirm('Устгах уу?')) return;

            fetch(`/admin/faq/${this.dataset.id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(res => res.json())
            .then(() => location.reload());
        });
    });

    document.getElementById('faqModal').addEventListener('show.bs.modal', function () {
        document.getElementById('faq_id').value = '';
        document.querySelector('[name="question"]').value = '';
        tinymce.get('faq_answer').setContent('');
    });
</script>
@endsection
