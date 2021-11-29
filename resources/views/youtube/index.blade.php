<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+KR:wght@300;400;500;700&display=swap" rel="stylesheet">
<style>
    * {
        margin: 0;
        padding: 0;
        font-family: 'Noto Sans KR', sans-serif;
    }

    .item-list {
        display: grid;
        gap: 20px;
        grid-template-columns: repeat(5, 320px);
    }

    @media (max-width: 1700px) {
        .item-list {
            grid-template-columns: repeat(4, 320px);
        }
    }

    @media (max-width: 1360px) {
        .item-list {
            grid-template-columns: repeat(3, 320px);
        }
    }

    @media (max-width: 1020px) {
        .item-list {
            grid-template-columns: repeat(2, 320px);
        }
    }

    .item {
        position: relative;
    }

    .item-header {
        position: relative;
    }

    .item-title {
        font-size: 16px;
        margin-bottom: 4px;
    }

    .item-lang {
        padding: 8px 12px;
        font-size: 12px;
        color: #fff;
        background-color: #0170f4;
        position: absolute;
        top: 0;
        left: 0;
    }

    .item-date {
        color: #777;
        font-size: 14px;
    }

    .item-count {
        position: absolute;
        right: 0;
        bottom: 0;
        top: 0;
        width: 140px;
        background-color: rgba(0, 0, 0, 0.6);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        color: #fff;
    }
</style>

<div class="item-list">
    @foreach ($youtubePlayLists as $youtubePlayList)
        <div class="item">
            <div class="item-header">
                <div class="item-img">
                    <img src="{{ $youtubePlayList->thumbnail }}">
                </div>
                <div class="item-lang">
                    @switch ($youtubePlayList->lang)
                        @case ('kr')
                            국문
                        @break
                        @case ('en')
                            영문
                        @break
                    @endswitch
                </div>
                <div class="item-count">
                    {{ $youtubePlayList->play_list_items_count }}개
                </div>
            </div>
            <div class="item-body">
            <div class="item-title">{{ $youtubePlayList->title }}</div>
            <div class="item-date">
                {{ $youtubePlayList->published_at }}
            </div>
            </div>
        </div>
    @endforeach
</div>