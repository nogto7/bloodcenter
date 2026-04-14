let visible = '<div class="overflow_element" onclick="closeCategory()"></div>';
    menuBtn = '<div class="menu_small"><span></span><span></span><span></span></div>',
    menuWrap = '<div class="mobile_wrap"><div class="m_block"></div></div>';
    visibled = '<div class="visited" onclick="closeMenu()"></div>';
var topTo = $('#gotoTop');

mobileMenu = function () {
    if ($(window).innerWidth() < 1121) {
        if ($('.lang_wrap .menu_small').length === 0) {
            $('.lang_wrap a').after(menuBtn);
            if ($('.mobile_wrap').length === 0) {
                $('body').prepend(menuWrap);
            }
            $('.menu_wrap > ul').appendTo('.mobile_wrap .m_block');
        }
        closeMenu();
    } else {
        $('.mobile_wrap .m_block > ul').appendTo('.menu_wrap');
        $('.menu_small, .mobile_wrap').remove();
        $('html').removeClass('visible_page');
        $('body').removeClass('visibled');
    }
}

closeMenu = function(){
    $('.visited').remove();
    $('html').removeClass('visible_page');
    $('body').removeClass('visibled');
    $('.menu_small, .mobile_wrap').removeClass('open');
}

sliderHeight = function(){
    let bW = $('.slide_item').width();
    if($(window).innerWidth < 768){
        biw = ((bW * 3.554045)/10)
        $('#sps').css({height: biw});
    } else {
        $('#sps').css({height: '500'});
    }
}

closeCategory = function(){
    $('html').removeClass('visible');
    $('.overflow_element').remove();
    $('.cat_menu_wrap, .link_search_wrap, .link_example_list').removeClass('open');
}

dialogShow = function(){
    $('.sbemt_dialog').show();
}

dialogHide = function(){
    $('.sbemt_dialog').hide();
}

filterShow = function(){
    $('html').addClass('visible');
    $('.filter_wrap').addClass('show');
}
orderShow = function(){
    $('html').addClass('visible');
    $('.order_wrap').addClass('show');
}

closeFilter = function(){
    $('html').removeClass('visible');
    $('.filter_wrap, .order_wrap').removeClass('show');
}

headerFixed = function(){
    if(window.scrollY > 34) {
        $('body').addClass('fixed');
        topTo.addClass('show');
    } else {
        $('body').removeClass('fixed');
        topTo.removeClass('show');
    }
}

$(window).resize(function(){
    mobileMenu();
    closeMenu();
    sliderHeight();
});

$(window).scroll(function(){
    headerFixed();
});

let currentDragFile = null;
function dragFile(event, fileId) {
    currentDragFile = fileId;
    event.dataTransfer.setData("fileId", fileId);
    event.currentTarget.classList.add('dragging');
}

function allowDrop(event) {
    event.preventDefault();
    event.currentTarget.classList.add('drag-over');
}

function dropFile(event, folderId) {
    event.preventDefault();
    event.currentTarget.classList.remove('drag-over');

    const fileId = event.dataTransfer.getData("fileId");

    moveFileRequest(fileId, folderId);
}

document.addEventListener('dragleave', function(e){
    if(e.target.classList.contains('folder_item')){
        e.target.classList.remove('drag-over');
    }
});

function showLoader() {
    document.getElementById('moveLoader').style.display = 'block';
}

function hideLoader() {
    document.getElementById('moveLoader').style.display = 'none';
}

let lastMove = null;

function moveFileRequest(fileId, newFolderId) {

    showLoader();

    fetch(`/admin/files/${fileId}/move`, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document
                .querySelector('meta[name="csrf-token"]')
                .content
        },
        body: JSON.stringify({
            folder_id: newFolderId
        })
    })
    .then(res => res.json())
    .then(data => {

        hideLoader();

        if(data.success){

            // өмнөх folder id хадгална
            lastMove = {
                fileId: fileId,
                oldFolder: localStorage.getItem('activeFolder'),
                newFolder: newFolderId
            };

            // DOM-с устгана
            const row = document.querySelector(
                `.file_row[ondragstart*="${fileId}"]`
            );
            if(row) row.remove();

            showUndo();
        }
    })
    .catch(err => {
        hideLoader();
        console.error(err);
        alert('Алдаа гарлаа');
    });
}

function showUndo(){
    const toast = document.getElementById('undoToast');
    toast.style.display = 'block';

    setTimeout(()=>{
        toast.style.display = 'none';
        lastMove = null;
    }, 5000);
}

function undoMove(){
    if(!lastMove) return;

    fetch(`/admin/files/${lastMove.fileId}/move`, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document
                .querySelector('meta[name="csrf-token"]')
                .content
        },
        body: JSON.stringify({
            folder_id: lastMove.oldFolder
        })
    }).then(()=>{
        location.reload();
    });
}

function showLoader() {
    document.getElementById('moveLoader').style.display = 'block';
}

function hideLoader() {
    document.getElementById('moveLoader').style.display = 'none';
}

let selectedFile = null;

function folderView(el, folderId) {

    // parent li дээр open class
    const wrap = el.closest('li');
    if (wrap) {
        wrap.classList.toggle('open');
    }

    $('.file_options').css({
        'display': 'none'
    });
    // document.getElementById('btnRename').disabled = true;
    document.getElementById('btnDelete').disabled = true;

    document.getElementById('filePreview').innerHTML = '<p class="empty">Файл сонгоно уу</p>';

    // 👉 breadcrumb update
    const folderName = el.dataset.folderName;
    const breadcrumbItem = document.getElementById('active-folder-breadcrumb');

    if (breadcrumbItem) {
        breadcrumbItem.style.display = 'list-item';
        breadcrumbItem.querySelector('p').textContent = folderName;
    }

    // Active highlight
    document.querySelectorAll('.folder_item.active')
        .forEach(e => e.classList.remove('active'));
    el.classList.add('active');

    // debugger;

    // Active folder хадгалах
    localStorage.setItem('activeFolder', folderId);

    // AJAX URL
    const url = `/admin/folders/${folderId}/files`;;

    fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(res => {
            if (!res.ok) throw new Error(`HTTP error! status: ${res.status}`);
            return res.json();
        })
        .then(data => {

            // Files list
            let html = '';
            if (data.files.length === 0) {
                html = '<p class="not_file">Энэ хавтсанд файл алга</p>';
            } else {
                html = `<table class="table_content">
                            <thead>
                            <tr>
                                <th>Нэр</th>
                                <th>Төрөл</th>
                                <th>Хэмжээ</th>
                            </tr>
                            </thead>`;
                data.files.forEach(file => {
                    html += `
                    <tbody>
                        <tr class="file_row"
                            onclick="previewFile(${file.id})"
                            data-file='${JSON.stringify(file)}'
                            draggable="true"
                            ondragstart="dragFile(event, ${file.id})">
                            <td>
                                <span class="file_type"></span>
                                ${file.title}
                            </td>

                            <td>${file.mime_type ?? '-'}</td>
                            <td>${file.size ? (file.size / 1024).toFixed(2)+' KB' : '-'}</td>
                        </tr>
                    </tbody>`;
                });
                html += '</table>';
            }
            document.querySelector('.file_list').innerHTML = html;

            // // Breadcrumb
            // const bc = document.querySelector('.n_breadcrumb ul');
            // bc.innerHTML = `
            //     <li><a href="{{ route('admin.folders.index') }}"><span class="bread_home"></span>Эхлэл</a></li>
            //     <li><p>Файлын тохиргоо</p></li>
            //     <li id="active-folder-breadcrumb"><p>${data.folder.name}</p></li>
            // `;
        })
        .catch(err => {
            console.error('AJAX алдаа:', err);
        });

        document.addEventListener('DOMContentLoaded', () => {
            const activeId = localStorage.getItem('activeFolder');
            if(!activeId) return;
            const btn = document.querySelector(`.folder_item[onclick*="(${activeId})"]`);
            if(btn) folderView(btn, activeId);
        
            // Parent li-уудыг автоматаар open
            let li = btn.closest('li');
            while(li) { li.classList.add('open'); li = li.parentElement.closest('li'); }
        });
}

function previewFile(fileId) {
    const row = event.currentTarget;
    const file = JSON.parse(row.dataset.file);
    selectedFile = file; // 🔴 яг энэ файл сонгогдлоо

    document.querySelectorAll('.file_row.active')
        .forEach(r => r.classList.remove('active'));
    row.classList.add('active');

    // 🔹 Rename / Delete товч идэвхжүүлэх
    $('.file_options').css({
        'display': 'flex'
    });
    // document.getElementById('btnRename').disabled = false;
    document.getElementById('btnDelete').disabled = false;

    // document.getElementById('btnRename').onclick = () => {
    //     if (!selectedFile) return;
    //     window.location.href = `/admin/files/${selectedFile.id}/edit`;
    // };

    document.getElementById('btnDelete').onclick = () => {
        if (!selectedFile) return;
    
        if (!confirm(`"${selectedFile.title}" файлыг устгах уу?`)) return;
    
        fetch(`/admin/files/${selectedFile.id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        }).then(() => {
            selectedFile = null;
            location.reload();
        });
    };

    const createdAt = new Date(file.created_at);

    // YYYY-MM-DD HH:MM:SS форматаар хөрвүүлэх
    const formatted = createdAt.getFullYear() + '-' +
        String(createdAt.getMonth() + 1).padStart(2, '0') + '-' +
        String(createdAt.getDate()).padStart(2, '0') + ' ' +
        String(createdAt.getHours()).padStart(2, '0') + ':' +
        String(createdAt.getMinutes()).padStart(2, '0') + ':' +
        String(createdAt.getSeconds()).padStart(2, '0');

    let previewHtml = ``;

    if(file.mime_type.includes('pdf')) {
        previewHtml += `
            <iframe src="/storage/${file.path}" width="100%" height="360"></iframe>
        `;
    } else if(file.mime_type.startsWith('image')) {
        previewHtml += `
            <img src="/storage/${file.path}" style="max-width:100%">
        `;
    } else {
        previewHtml += `
            <a href="/storage/${file.path}" target="_blank">Файл нээх</a>
        `;
    }

    previewHtml += `<div class="file_preview_info">
        <h4>${file.title}</h4>
        <div class="dfc"><span>${file.mime_type}</span>-<span>${(file.size/1024).toFixed(2)} KB</span></div>
        <div class="prev_info"><h5>Мэдээлэл</h5><div class="dcsb">
            <p>Хавтас</p>
            <em>${file.folder?.name ?? '-'}</em>
        </div>

        <div class="dcsb">
            <p>Цэс</p>
            <em>${file.menu?.title ?? '-'}</em>
        </div><div class="dcsb bt1"><p>Үүсгэсэн огноо</p><em>${formatted}</em></div></div></div>
    `

    document.getElementById('filePreview').innerHTML = previewHtml;
}

function editFile(id) {
    window.location.href = `/admin/files/${id}/edit`;
}

function deleteFile(id) {
    if(!confirm('Файлыг устгах уу?')) return;

    fetch(`/admin/files/${id}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    }).then(() => location.reload());
}

function openFolderModal() {
    document.getElementById('folderModal').style.display = 'flex';
}
function closeModal(id) {
    const modal = document.getElementById(id);
    if (!modal) return;
    modal.classList.remove('active');
}

function reloadFolderTree(folder) {
    const ul = document.querySelector('.admin_page_sidebar_cont > ul');

    if (!folder.parent_id) {
        ul.insertAdjacentHTML('beforeend', `
            <li>
                <button class="folder_item"
                    onclick="folderView(this, ${folder.id})"
                    data-folder-name="${folder.name}">
                    <span></span>${folder.name}
                </button>
            </li>
        `);
    } else {
        const parentLi = document.querySelector(
            `.folder_item[onclick*="(${folder.parent_id})"]`
        )?.closest('li');

        if (parentLi) {
            let sub = parentLi.querySelector('ul');
            if (!sub) {
                sub = document.createElement('ul');
                parentLi.appendChild(sub);
                parentLi.classList.add('sub_folder','open');
            }
            sub.insertAdjacentHTML('beforeend', `
                <li>
                    <p class="folder_item"
                        onclick="folderView(this, ${folder.id})"
                        data-folder-name="${folder.name}">
                        <span></span>${folder.name}
                    </p>
                </li>
            `);
        }
    }
}

function deleteFolder(folderId) {
    // const folderId = this.dataset.id; // ✅ folderId авч байна уу шалгах
    // console.log(folderId); // debug

    // debugger;
    if(!confirm('Та энэ folder-ийг устгах уу?')) return;

    fetch(`/admin/folders/${folderId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document
                .querySelector('meta[name="csrf-token"]')
                .content,
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(res => {
        if (!res.ok) throw res;
        return res.json();
    })
    .then(() => {
        // const el = document.querySelector(`[data-folder="${folderId}"]`);
        $(this).classList.add('fade-out');

        setTimeout(() => $(this).remove(), 300);
    })
    .catch(err => {
        console.error(err);
        alert('Folder устгахад алдаа гарлаа');
    });
}

$(document).ready(function(){
    mobileMenu();
    closeMenu();
    headerFixed();

    $('.faq_question, .shilen_head').click(function(e) {
        e.preventDefault();
        let $this = $(this);
        if($this.parent().hasClass('show')){
            $this.parent().removeClass('show');
            $this.next().slideUp(300);
        } else {
            $('.faq_question, .shilen_head').parent().removeClass('show');
            $('.faq_answer, .shilen_content').slideUp(300);
            $this.parent().removeClass('show');
            $this.parent().toggleClass('show');
            $this.next().slideToggle(300);
        }
    });

    const folderForm = document.getElementById('folderForm'),
    menusForm = document.getElementById('menusForm'),
    departmentForm = document.getElementById('departmentForm'),
    groupForm = document.getElementById('groupForm'),
    itemForm = document.getElementById('itemForm'),
    newsForm = document.getElementById('newsForm'),
    fileForm = document.getElementById('fileForm'),
    sliderForm = document.getElementById('sliderForm'),
    userForm = document.getElementById('userForm'),
    videoForm = document.getElementById('videoForm');

    $(".ad_sidebar li a").removeClass("active");
    let path = window.location.pathname;

    $(".ad_sidebar li a").each(function () {
        if ($(this).attr("href") === path ||  $(this).attr("href") + '/create' === path) {
            $(this).addClass("active");
        }   
    });

    const imageInput = document.getElementById('highlight_image');
    const imageName  = document.getElementById('highlight_image_name');
    const deleteBtn =  document.querySelectorAll('.delete-folder');

    if (imageInput && imageName) {
        imageInput.addEventListener('change', () => {
            imageName.textContent = imageInput.files.length
                ? imageInput.files[0].name
                : 'Файл сонгоогүй';
        });
    }

    if (imageInput && imagePreview) {
        imageInput.addEventListener('change', () => {
            const file = imageInput.files[0];
            if (!file) return;
    
            imagePreview.src = URL.createObjectURL(file);
        });
    }    

    if(sliderForm){
        sliderForm.addEventListener('submit', function (e) {
            e.preventDefault();
        
            const formData = new FormData(this);
        
            fetch('/admin/slider', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
            .then(res => {
                if (!res.ok) throw res;
                return res.json();
            })
            .then(data => {
                bootstrap.Modal.getInstance(
                    document.getElementById('addSlider')
                ).hide();
        
                this.reset();
            })
            .catch(err => {
                console.error('Slider save error:', err);
                alert('Алдаа гарлаа');
            });
        });
    }

    if(videoForm){
        videoForm.addEventListener('submit', function (e) {
            e.preventDefault();
        
            const formData = new FormData(this);
        
            fetch('/admin/video', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
            .then(res => {
                if (!res.ok) throw res;
                return res.json();
            })
            .then(data => {
                bootstrap.Modal.getInstance(
                    document.getElementById('addVideo')
                ).hide();
        
                this.reset();
            })
            .catch(err => {
                console.error('Video save error:', err);
                alert('Алдаа гарлаа');
            });
        });
    }

    if(folderForm){
        folderForm.addEventListener('submit', function (e) {
            e.preventDefault();
        
            const formData = new FormData(this);
        
            fetch('/admin/folders', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
            .then(res => {
                if (!res.ok) throw res;
                return res.json();
            })
            .then(data => {
                bootstrap.Modal.getInstance(
                    document.getElementById('addFolder')
                ).hide();
        
                this.reset();
        
                // 🔥 Sidebar шинэчлэх
                reloadFolderTree(data.folder);
            })
            .catch(err => {
                console.error('Folder save error:', err);
                alert('Алдаа гарлаа');
            });
        });
    }

    if(fileForm){
        fileForm.addEventListener('submit', function (e) {
            e.preventDefault();
        
            const formData = new FormData(this);
        
            fetch('/admin/files', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
            .then(res => {
                if (!res.ok) throw res;
                return res.json();
            })
            .then(data => {
                bootstrap.Modal.getInstance(
                    document.getElementById('addFile')
                ).hide();
        
                this.reset();
        
                // 🔥 Sidebar шинэчлэх
                reloadFolderTree(data.files);
            })
            .catch(err => {
                console.error('Files save error:', err);
                alert('Алдаа гарлаа');
            });
        });
    }

    if(menusForm){
        menusForm.addEventListener('submit', function (e) {
            e.preventDefault();
        
            const formData = new FormData(this);
        
            fetch('/admin/menus', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
            .then(res => {
                if (!res.ok) throw res;
                return res.json();
            })
            .then(data => {
                bootstrap.Modal.getInstance(
                    document.getElementById('addMenus')
                ).hide();
        
                this.reset();
            })
            .catch(err => {
                console.error('Menus save error:', err);
                alert('Алдаа гарлаа');
            });
        });
    }

    if(departmentForm){
        departmentForm.addEventListener('submit', function (e) {
            e.preventDefault();
            const formData = new FormData(this);
        
            fetch('/admin/department', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
            .then(res => {
                if (!res.ok) throw res;
                return res.json();
            })
            .then(data => {
                bootstrap.Modal.getInstance(
                    document.getElementById('addDepartment')
                ).hide();
        
                this.reset();
            })
            .catch(err => {
                console.error('News save error:', err);
                alert('Алдаа гарлаа');
            });
        });
    }
    
    if (userForm) {
        userForm.addEventListener('submit', async function (e) {
            e.preventDefault();
    
            const formData = new FormData(this);
    
            // Password field-д утга байгаа эсэхийг шалга
            if (!formData.get('password')) {
                alert('Нууц үгээ оруулна уу');
                return;
            }
    
            try {
                const res = await fetch('/admin/users', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData,
                    credentials: 'include' // <- session cookie-г дамжуулна
                });
    
                const data = await res.json();
    
                if (data.success) {
                    // modal хаах
                    bootstrap.Modal.getInstance(
                        document.getElementById('addUser')
                    ).hide();
    
                    this.reset();
                    alert(data.message);
    
                    // Table-д шинэ row нэмж болно
                    // addRowToTable(data.data);
                } else {
                    alert('Алдаа гарлаа: ' + (data.message || ''));
                }
            } catch (err) {
                console.error('User save error:', err);
                alert('Алдаа гарлаа');
            }
        });
    }

    const employeeForm = document.getElementById('employeeForm');
    if(employeeForm){
        employeeForm.addEventListener('submit', function(e){
            e.preventDefault();
            const formData = new FormData(this);

            fetch('/admin/employee', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if(data.success){
                    alert('Ажилтан амжилттай нэмэгдлээ');
                    this.reset();
                    bootstrap.Modal.getInstance(document.getElementById('addEmployee')).hide();
                } else {
                    alert('Алдаа гарлаа');
                }
            })
            .catch(err => console.error(err));
        });
    }

    if(groupForm){
        groupForm.addEventListener('submit', function (e) {
            e.preventDefault();
    
            const formData = new FormData(this);
    
            fetch('/admin/groups', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if(data.success){
                    alert('Мэдээ амжилттай нэмэгдлээ');
                    this.reset();
                    bootstrap.Modal.getInstance(document.getElementById('addGroup')).hide();
    
                    // ⚡ TinyMCE-г ч бас reset хийх
                    if(editor){
                        editor.setContent('');
                    }
                } else {
                    alert('Алдаа гарлаа');
                }
            })
            .catch(err => {
                console.error('Group save error:', err);
                alert('Алдаа гарлаа');
            });
        });
    }
    if(itemForm){
        itemForm.addEventListener('submit', function (e) {
            e.preventDefault();
    
            const formData = new FormData(this);
    
            fetch('/admin/group-items', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if(data.success){
                    alert('Шил ажиллааны файл амжилттай нэмэгдлээ');
                    this.reset();
                    bootstrap.Modal.getInstance(document.getElementById('addItem')).hide();
    
                    // ⚡ TinyMCE-г ч бас reset хийх
                    if(editor){
                        editor.setContent('');
                    }
                } else {
                    alert('Алдаа гарлаа');
                }
            })
            .catch(err => {
                console.error('Item save error:', err);
                alert('Алдаа гарлаа');
            });
        });
    }

    if(newsForm){
        newsForm.addEventListener('submit', function (e) {
            e.preventDefault();
    
            const editor = tinymce.get('content');
    
            if(editor){
                let plain = editor.getContent({ format: 'text' }).trim();
    
                this.querySelector('textarea[name="content"]').value =
                    plain === '' ? '' : editor.getContent();
            }
    
            const formData = new FormData(this);
    
            fetch('/admin/news', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
            .then(async res => {
                let text = await res.text();
                console.log('RESPONSE:', text);
            
                try {
                    return JSON.parse(text);
                } catch (e) {
                    throw new Error('JSON parse error');
                }
            })
            .then(data => {
                if(data.success){
                    alert('Амжилттай');
                    location.reload();
                }
            })
            .catch(err => {
                console.error('ERROR:', err);
                alert('Алдаа гарлаа (console хар)');
            });
        });
    }
    
    $(document).on('click', '.menu_small', function(e){
        let mwrap = $('.mobile_wrap');
        e.preventDefault();
        $('html').toggleClass('visible_page');
        $(this).toggleClass('open');
        $('.__header_main').toggleClass('open');
        $(mwrap).toggleClass('open');
        if($('body').find('.visited').length === 0) {
            $(visibled).appendTo('body');
        } else {
            $('.visited').remove();
        }
    });

    $(topTo).on('click', function() {
        $('html').animate({scrollTop: 0}, 300)
        return false;
    });
    
    $(document).on('click', '.link_search_btn', function(e){
        e.preventDefault();
        if($('body').find('.overflow_element').length === 0){
            $('body').prepend(visible);
        }
        $('.link_search_wrap').addClass('open');
    });

    $(document).on('click', '#showSiteList', function(e){
        e.preventDefault();
        $('.link_search_wrap').removeClass('open');
        setTimeout(function(){
            $('.link_example_list').addClass('open');
        }, 400);
    });

    $('.collapse_item h3').on('click', function(){
        $(this).parent().toggleClass('open')
        $(this).next().slideToggle(200);
    });

    $('.select_btn').on('click', function(){
        $('.notification_list').toggleClass('remove');
    });
    $('.action_btn').on('click', function(){
        $('.list_item').toggleClass('action');
    });

    $('.sbemt_item>h3').click(function(e) {
        e.preventDefault();
        let $this = $(this);
        $this.parent().toggleClass('hidden');
        $this.next().slideToggle(300);
    });

    $('.order_btn').click(function(){
        $(this).toggleClass('select');
    });

    $('.cat_list_btn').on('click', function(){
        $(this).toggleClass('active');
        $(this).next().toggleClass('open');
    });

    $('.reg_text').on('click', function(){
        $('.register_words').removeClass('show');
        $(this).next().toggleClass('show');
    });

    $('.reg_words_grid > div').on('click', function(){
        console.log('aaa');
        $('.register_words').removeClass('show');
    });

    $('.thumbnail_big').on('click', function(){
        $('.zoom_wrap').addClass('show');
    });

    $('.zoom_close').on('click', function(){
        $('.zoom_wrap').removeClass('show');
    });

    var pnHeight = $('.popular_news').height();
    $('.other_news ul').css({
        height: pnHeight
    })

    sliderHeight();

    function updSwiperNumericPagination() {
        this.el.querySelector(".swiper-counter").innerHTML =
        '<span class="count">' +
        (this.realIndex + 1) +
        '</span>/<span class="total">' +
        this.el.slidesQuantity +
        "</span>";
    }

    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll(".swiper-container").forEach(function (node) {
            node.slidesQuantity = node.querySelectorAll(".swiper-slide").length;
            new Swiper(node, {
                speed: 1000,
                loop: true,
                autoplay: { delay: 1000 },
                pagination: { el: node.querySelector(".swiper-pagination") },
                on: {
                    init: updSwiperNumericPagination,
                    slideChange: updSwiperNumericPagination
                }
            });
        });
    });
});
