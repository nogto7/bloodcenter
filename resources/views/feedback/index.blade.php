@php
    $contactAddress = \App\Models\SiteSetting::get('contact_address');
    $contactPhone = \App\Models\SiteSetting::get('contact_phone');
    $contactEmail = \App\Models\SiteSetting::get('contact_email');
    $contactWorkHours = \App\Models\SiteSetting::get('contact_work_hours');

    $feedbackTypes = \App\Models\SiteSetting::list('feedback_types', ['Санал', 'Хүсэлт', 'Талархал']);
    // Монгол улсын хэмжээний салбарууд — админы Холбоо барих тохиргооноос удирдана
    $feedbackPositions = \App\Models\SiteSetting::list('feedback_positions');
@endphp
<div class="dg g2 gap3">
    <div class="contact_info">
        @if($contactAddress || $contactPhone || $contactEmail || $contactWorkHours)
        <h2>Холбоо барих</h2>
        <ul>
            @if($contactAddress)
                <li>
                    <span class="contact_icon"><i class="fa-solid fa-location-dot"></i></span>
                    <div class="contact_text">
                        <h3>Хаяг</h3>
                        <p>{{ $contactAddress }}</p>
                    </div>
                </li>
            @endif
            @if($contactPhone)
                <li>
                    <span class="contact_icon"><i class="fa-solid fa-phone"></i></span>
                    <div class="contact_text">
                        <h3>Утас</h3>
                        <p>{{ $contactPhone }}</p>
                    </div>
                </li>
            @endif
            @if($contactEmail)
                <li>
                    <span class="contact_icon"><i class="fa-solid fa-envelope"></i></span>
                    <div class="contact_text">
                        <h3>И-мэйл</h3>
                        <p><a href="mailto:{{ $contactEmail }}">{{ $contactEmail }}</a></p>
                    </div>
                </li>
            @endif
            @if($contactWorkHours)
                <li>
                    <span class="contact_icon"><i class="fa-solid fa-clock"></i></span>
                    <div class="contact_text">
                        <h3>Цагийн хуваарь</h3>
                        @foreach(preg_split('/\r\n|\r|\n/', $contactWorkHours) as $line)
                            @if(trim($line) !== '')<p>{{ $line }}</p>@endif
                        @endforeach
                    </div>
                </li>
            @endif
        </ul>
        @endif
    </div>
    <div class="feedback_form">
        <h2>Санал, хүсэлт</h2>
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
                    <select name="feedback_type" class="form_select" required>
                        <option value="">Сонгох</option>
                        @foreach($feedbackTypes as $type)
                            <option value="{{ $type }}" {{ old('feedback_type') == $type ? 'selected' : '' }}>{{ $type }}</option>
                        @endforeach
                    </select>
                </div>
                @if(count($feedbackPositions))
                <div class="form_item required">
                    <label class="form_label">Саналын хүргүүлэх салбар</label>
                    <select name="feedback_position" class="form_select" required>
                        <option value="">Сонгох</option>
                        @foreach($feedbackPositions as $position)
                            <option value="{{ $position }}" {{ old('feedback_position') == $position ? 'selected' : '' }}>{{ $position }}</option>
                        @endforeach
                    </select>
                </div>
                @endif
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
    