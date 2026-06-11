@extends('admin.layout')
@section('content')
<div class="section_container">
    <div class="section_line">
        <div class="admin_form">
            <h1 class="title">Холбоо барих мэдээлэл</h1>
            <p class="mb2">Нүүр хуудасны "Санал, хүсэлт" хэсгийн зүүн талд харагдана.</p>
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li class="__error">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form action="{{ route('admin.settings.contact.update') }}" method="POST">
                @csrf
                <div class="form_item">
                    <label class="form_label">Хаяг</label>
                    <input type="text" name="contact_address" class="form_input"
                        value="{{ old('contact_address', $settings['contact_address'] ?? '') }}"
                        placeholder="Жишээ: Улаанбаатар хот, ... дүүрэг, ... хороо, ... гудамж">
                </div>
                <div class="dg g2 gap1">
                    <div class="form_item">
                        <label class="form_label">Утас</label>
                        <input type="text" name="contact_phone" class="form_input"
                            value="{{ old('contact_phone', $settings['contact_phone'] ?? '') }}"
                            placeholder="Жишээ: (+976) 7000-0000">
                    </div>
                    <div class="form_item">
                        <label class="form_label">И-мэйл хаяг</label>
                        <input type="email" name="contact_email" class="form_input"
                            value="{{ old('contact_email', $settings['contact_email'] ?? '') }}"
                            placeholder="Жишээ: info@bloodcenter.mn">
                    </div>
                </div>
                <div class="form_item">
                    <label class="form_label">Цагийн хуваарь</label>
                    <textarea name="contact_work_hours" class="form_textarea"
                        placeholder="Жишээ:&#10;Даваа–Баасан: 08:00–17:00&#10;Бямба, Ням: Амарна">{{ old('contact_work_hours', $settings['contact_work_hours'] ?? '') }}</textarea>
                    <div class="__text_desc">
                        <small>Мөр бүр тусдаа харагдана</small>
                    </div>
                </div>

                <h1 class="title mt4">Санал, хүсэлтийн форм</h1>
                <div class="dg g2 gap1">
                    <div class="form_item">
                        <label class="form_label">Саналын төрөл</label>
                        <textarea name="feedback_types" class="form_textarea"
                            placeholder="Санал&#10;Хүсэлт&#10;Талархал">{{ old('feedback_types', $settings['feedback_types'] ?? '') }}</textarea>
                        <div class="__text_desc">
                            <small>Мөр бүр нэг сонголт болно. Хоосон орхивол: Санал, Хүсэлт, Талархал</small>
                        </div>
                    </div>
                    <div class="form_item">
                        <label class="form_label">Саналын хүргүүлэх салбар (улсын хэмжээнд)</label>
                        <textarea name="feedback_positions" class="form_textarea" rows="12"
                            placeholder="Салбар бүрийг шинэ мөрөнд бичнэ">{{ old('feedback_positions', $settings['feedback_positions'] ?? '') }}</textarea>
                        <div class="__text_desc">
                            <small>Мөр бүр нэг сонголт болно. Хоосон орхивол нүүрэн дээр энэ талбар харагдахгүй</small>
                        </div>
                    </div>
                </div>
                <button type="submit" class="__btn btn_primary">Хадгалах</button>
            </form>
        </div>
    </div>
</div>
@endsection
