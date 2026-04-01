<h1 class="title">Санал, хүсэлт</h1>
<div class="dg g2 gap3">
    <div class="">

    </div>
    <div class="feedback_form">
        <form action="{{ route('feedback.send') }}" method="POST">
            @csrf
            <div class="dg g2 gap2">
                <div class="form_item required">
                    <label class="form_label">Нэр</label>
                    <input type="text" name="name" class="form_input" id="" required>
                    @if ($errors->has('name'))
                        <div class="text_desc">
                            <p class="__error">{{ $errors->first('name') }}</p>
                        </div>
                    @endif
                </div>
                <div class="form_item required">
                    <label class="form_label">Утасны дугаар</label>
                    <input type="text" name="phone" class="form_input" id="" required>
                    @if ($errors->has('phone'))
                        <div class="text_desc">
                            <p class="__error">{{ $errors->first('phone') }}</p>
                        </div>
                    @endif
                </div>
            </div>
            <div class="form_item required">
                <label class="form_label">И-мэйл хаяг</label>
                <input type="email" name="email" class="form_input" id="" value="{{ old('email') }}" required>
                @if ($errors->has('email'))
                    <div class="text_desc">
                        <p class="__error">{{ $errors->first('email') }}</p>
                    </div>
                @endif
            </div>
            <div class="dg g2 gap2">
                <div class="form_item required">
                    <label class="form_label">Саналын төрөл</label>
                    <select name="feedback_type" class="form_select" id="" required>
                        <option value="">Сонгох</option>
                        <option value="Санал">Санал</option>
                        <option value="Хүсэлт">Хүсэлт</option>
                        <option value="Талархал">Талархал</option>
                    </select>
                </div>
                <div class="form_item required">
                    <label class="form_label">Саналын хүргүүлэх салбар</label>
                    <select name="feedback_position" class="form_select" id="" required>
                        <option value="">Сонгох</option>
                        <option value="Хүсэлт">Хүсэлт</option>
                    </select>
                </div>
            </div>
            <div class="col2to1">
                <div class="form_item required">
                    <label class="form_label">Санал, шийдлийн дэлгэрэнгүй</label>
                    <textarea name="message" class="form_textarea" id="" required></textarea>
                    @if ($errors->has('message'))
                        <div class="text_desc">
                            <p class="__error">{{ $errors->first('message') }}</p>
                        </div>
                    @endif
                </div>
                <button type="submit" class="__btn btn_primary mt4 w10">Санал илгээх</button>
            </div>
        </form>
    </div>
</div>
    