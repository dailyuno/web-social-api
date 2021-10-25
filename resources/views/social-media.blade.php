<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.23.0/axios.min.js"></script>
    <script>
        class SimpleDate {
            constructor(number = Date.now()) {
                this.date = new Date(number);
                return this;
            }

            subtract(number, type) {
                const { date } = this;
                switch (type) {
                    case 'days':
                        date.setDate(date.getDate() - number);
                        break;
                    case 'months':
                        date.setMonth(date.getMonth() - number);
                        break;
                    case 'years':
                        date.setYear(date.getFullYear() - number);
                        break;
                }
                return this;
            }

            format(str) {
                const { date } = this;

                return str
                    .replace(/Y/, date.getFullYear())
                    .replace(/m/, (date.getMonth() + 1).toString().padStart(2, 0))
                    .replace(/d/, date.getDate().toString().padStart(2, 0))
                    .replace(/H/, date.getHours().toString().padStart(2, 0))
                    .replace(/i/, date.getMinutes().toString().padStart(2, 0));
            }
        }

        class YoutubeApi {
            static channelId = 'UCTaOU9vqcFgVl0Spiw-pwTQ';
            static key = 'AIzaSyBOh-ajNYbL3GIPvdyFMe6AmWnbt2jk0bI';            

            /**
             * 1일 전을 기준으로
             * 유튜브에 업로드 된 영상이 있는지 가지고 오는 함수
             */
            static async getRecentVideoItems(date) {
                try {
                    const { channelId, key } = YoutubeApi;
                    const url = `https://www.googleapis.com/youtube/v3/search?channelId=${channelId}&key=${key}&part=snippet&order=date&publishedAfter=${date}`;
                    const response = await axios.get(url);
                    const { data } = response;
                    const { items } = data;

                    return items;
                } catch (e) {
                    console.log(e.response);
                }
            }

            /**
             * 유튜브 채널 - 재생목록 리스트 가지고 오기
             */
            static async getPlayLists() {
                const { channelId, key } = YoutubeApi;

                try {
                    const url = `https://www.googleapis.com/youtube/v3/playlists?channelId=${channelId}&key=${key}&part=snippet&maxResults=20`;
                    const response = await axios.get(url);
                    const { data } = response;
                    const { items } = data;
                    return items;
                } catch (e) {
                    console.log(e.response);
                }
            }

            /**
             * 유튜브 재생목록의 영상들 가지고 오는 함수
             */
            static async getPlayListItems(id) {
                try {
                    const { key } = YoutubeApi;

                    const url = `https://www.googleapis.com/youtube/v3/playlistItems?playlistId=${id}&key=${key}&part=snippet&maxResults=50`;
                    const response = await axios.get(url);
                    const { data } = response;
                    const { items } = data;
                    return items;
                } catch (e) {
                    console.log(e.response);
                }
            }
        }

        class App {
            constructor() {
                this.date = "2018-10-01T00:00:00Z";
                // this.date = (new SimpleDate).subtract(1, 'days').format('Y-m-dT00:00:00Z');
                this.init();
            }

            async init() {
                const { date } = this;
                this.storedPlayLists = await this.getPlayLists(date);
                this.storedPlayListItems = await this.getPlayListItems(date);
                this.storedThumbnails = await this.getThumbnails(date);
                const recentVideoItems = await YoutubeApi.getRecentVideoItems(date);

                console.log(`1일 전 기준 업로드 된 ${recentVideoItems.length}개 영상 발견`);

                if (recentVideoItems.length > 0) {
                    await this.setItems();
                }
                
                console.log(this.storedPlayLists);
                console.log(this.storedPlayListItems);
                console.log(this.storedThumbnails);
                console.log('done');
            }

            isAfterDate(publishedAt) {
                const { date } = this;
                const currentTime = (new Date(date)).getTime();
                const time = (new Date(publishedAt)).getTime();

                if (currentTime <= time) {
                    return true;
                }

                return false;
            }

            existsItem(items, item) {
                const { id } = item;

                if (items.map(x => x.id).indexOf(id) < 0) {
                    return false;
                }
                
                return true;
            }

            async getPlayLists(date) {
                try {
                    const response = await axios.get(`/api/youtube-play-lists?date=${date}`);
                    const { data } = response;
                    const { items } = data;
                    return items;
                } catch (e) {
                    console.log(e.response);
                }
            }

            async getPlayListItems(date) {
                try {
                    const response = await axios.get(`/api/youtube-play-list-items?date=${date}`);
                    const { data } = response;
                    const { items } = data;
                    return items;
                } catch (e) {
                    console.log(e.response);
                }
            }

            async getThumbnails(date) {
                try {
                    const response = await axios.get(`/api/youtube-thumbnails?date=${date}`);
                    const { data } = response;
                    const { items } = data;
                    return items;
                } catch (e) {
                    console.log(e.response);
                }
            }

            /**
             * 유튜브에 업로드 된 영상이 있을 경우,
             * 채널 재생목록 리스트를 가지고 오고,
             * 데이터들 DB에 넣는 작업
             */
            async setItems() {
                try {
                    const { storedPlayLists } = this;
                    const playLists = await YoutubeApi.getPlayLists();

                    console.log(`${playLists.length}개 채널 발견`);

                    for (let i = 0; i < playLists.length; i++) {
                        const playList = playLists[i];
                        const { id, snippet } = playList;
                        const { thumbnails } = snippet;

                        if (!this.existsItem(storedPlayLists, playList))  {
                            await this.setPlayList(playList);
                        }

                        await this.setThumbnailList(thumbnails, id, 'youtube_play_lists');
                        await this.setPlayListItems(id);
                    }
                } catch (e) {
                    console.log(e.response);
                }
            }

            /**
             * 유튜브 채널 - 재생목록 리스트를 DB에 추가
             */
            async setPlayList(playList) {
                try {
                    const { id, snippet } = playList;
                    const { title, publishedAt } = snippet;

                    if (this.isAfterDate(publishedAt)) {
                        await axios.post('/api/youtube-play-lists', {
                            id, 
                            title, 
                            publishedAt
                        });
                    }
                } catch (e) {
                    console.log(e.response);
                }
            }

            /**
             * 유튜브 썸네일 세팅
             */
            async setThumbnailList(thumbnails, youtube_thumbnailable_id, youtube_thumbnailable_type) {
                const { storedThumbnails } = this;
                for (let type in thumbnails) {
                    const { url, width, height } = thumbnails[type];
                    
                    if (!storedThumbnails.find(x => x.youtube_thumbnailable_id === youtube_thumbnailable_id && x.url === url)) {
                        await this.setThumbnail({ youtube_thumbnailable_id, youtube_thumbnailable_type, url, type, width, height });
                    }
                }
            }

            /**
             * 유튜브 썸네일 정보를 DB에 추가
             */
            async setThumbnail({ youtube_thumbnailable_id, youtube_thumbnailable_type, url, type, width, height }) {
                try {
                    await axios.post('/api/youtube-thumbnails', {
                        youtube_thumbnailable_id,
                        youtube_thumbnailable_type,
                        url,
                        type,
                        width,
                        height
                    });
                } catch (e) {
                    console.log(e.response, type);
                }
            }

            /**
             * 유튜브 재생목록의 영상들 가지고 와서 세팅
             */
            async setPlayListItems(id) {
                try {
                    const { storedPlayListItems } = this;
                    const playListItems = await YoutubeApi.getPlayListItems(id);

                    console.log(`동영상 ${playListItems.length}개 발견`)

                    for (let i = 0; i < playListItems.length; i++) {
                        const playListItem = playListItems[i];
                        const { id, snippet } = playListItem;
                        const { thumbnails } = snippet;

                        if (!this.existsItem(storedPlayListItems, playListItem)) {
                            await this.setPlayListItem(playListItem);
                        }

                        await this.setThumbnailList(thumbnails, id, 'youtube_play_list_items');
                    }
                } catch (e) {
                    console.log(e.response);
                }
            }

            /**
             * 유튜브 재생목록의 영상을 DB에 넣는 함수
             */
            async setPlayListItem(playListItem) {
                try {
                    const { id, snippet } = playListItem;
                    const { title, description, publishedAt, playlistId: youtube_play_lists_id, thumbnails } = snippet;

                    if (this.isAfterDate(publishedAt)) {
                        await axios.post('/api/youtube-play-list-items', {
                            id, 
                            youtube_play_lists_id,
                            title, 
                            description, 
                            publishedAt
                        });
                    }
                } catch (e) {
                    console.log(e.response);
                }
            }
        }

        window.onload = function() {
            new App();
        }
    </script>

</body>
</html>