<div class="sidebar_item">
    <h3>Бусад мэдээлэл</h3>
    <div class="sidebar_content">
        <ul>
            @foreach ($latestNews as $news)
            <li>
                <em><i class="fa fa-calendar-alt"></i>{{ $news->publish_at->format('Y-m-d') }}</em>
                <a href="{{ url('news/'.$news->slug) }}">{{ $news->title }}</a>
            </li>
            @endforeach
        </ul>
    </div>
</div>