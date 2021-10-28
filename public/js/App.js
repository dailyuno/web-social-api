class App {
    constructor() {
        this.date = (new SimpleDate).subtract(7, 'days').format('Y-m-dT00:00:00Z');
        this.init();
    }

    async init() {
        const { date } = this;
        this.storedPlayLists = await this.getPlayLists();
        this.storedPlayListItems = await this.getPlayListItems(date);
        this.storedVideos = await this.getVideos(date);

        console.log((new SimpleDate).format('Y-m-d H:i:s'));

        await this.createItems();

        console.log('done');
        console.log((new SimpleDate).format('Y-m-d H:i:s'));
    }

    isBeforeDate(publishedAt) {
        if (!publishedAt) {
            return false;
        }

        const { date } = this;
        const currentTime = (new Date(date)).getTime();
        const time = (new Date(publishedAt)).getTime();

        if (currentTime > time) {
            return true;
        }

        return false;
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

    async getVideos(date) {
        try {
            const response = await axios.get(`/api/youtube-videos?date=${date}`);
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
    async createItems() {
        try {
            const playLists = await YoutubeApi.getPlayLists();

            console.log(playLists)
            console.log(`${playLists.length}개 채널 발견`);

            for (let i = 0; i < playLists.length; i++) {
                const playList = playLists[i];
                const { id, snippet } = playList;
                const { title, publishedAt } = snippet;

                await this.createPlayList(id, title, publishedAt);
                await this.createPlayListItems(id);
            }

            const playListsId = playLists.map(x => x.id);
            const filterPlayLists = this.storedPlayLists.filter(x => playListsId.indexOf(x.id) < 0);

            for (let i = 0; i < filterPlayLists.length; i++) {
                const playList = filterPlayLists[i];
                const { id } = playList;
                await this.createPlayListItems(id);
            }
        } catch (e) {
            console.log(e.response);
        }
    }

    /**
     * 유튜브 채널 - 재생목록 리스트를 DB에 추가
     */
    async createPlayList(id, title, publishedAt) {
        try {
            if (this.existsPlayList(id)) {
                return;
            }

            const response = await axios.post('/api/youtube-play-lists', {
                id,
                title,
                published_at: publishedAt
            });
            const { data } = response;

            this.storedPlayLists.push(data);
        } catch (e) {
            console.log(e.response);
        }
    }

    /**
     * DB에 재생목록 리스트가 있는지 확인
     * @param {*} id 
     * @returns 
     */
    existsPlayList(id) {
        const { storedPlayLists } = this;

        if (storedPlayLists.map(x => x.id).indexOf(id) < 0) {
            return false;
        }

        return true;
    }

    /**
     * 유튜브 재생목록의 영상들 가지고 와서 세팅
     * @param {String} id (play_list_id)
     */
    async createPlayListItems(id) {
        try {
            const playListItems = await YoutubeApi.getPlayListItems(id);

            console.log(`동영상 ${playListItems.length}개 발견`)

            for (let i = 0; i < playListItems.length; i++) {
                const playListItem = playListItems[i];
                const { snippet, contentDetails } = playListItem;
                const { title, description, thumbnails, publishedAt, playlistId } = snippet;
                const { videoId, videoPublishedAt } = contentDetails;

                await this.createVideo(videoId, title, description, videoPublishedAt, Object.keys(thumbnails));
                await this.createPlayListItem(playlistId, videoId, publishedAt);
            }
        } catch (e) {
            console.log(e.response);
        }
    }

    async createVideo(id, title, description, publishedAt, thumbnails) {
        try {
            if (this.existsVideo(id)) {
                return;
            }

            if (this.isBeforeDate(publishedAt)) {
                return;
            }

            const response = await axios.post('/api/youtube-videos', {
                id,
                title,
                description,
                published_at: publishedAt,
                thumbnails
            });
            const { data } = response;
            this.storedVideos.push(data);

            return data;
        } catch (e) {
            console.log(id, title, description, publishedAt, thumbnails);
            console.log(e.response);
        }
    }

    /**
     * DB에 영상이 이미 삽입 되어있는지 확인
     * @param {String} id
     * @returns
     */
    existsVideo(id) {
        const { storedVideos } = this;

        if (storedVideos.map(x => x.id).indexOf(id) < 0) {
            return false;
        }

        return true;
    }

    /**
     * 유튜브 재생목록의 영상을 DB에 넣는 함수
     */
    async createPlayListItem(playlistId, videoId, publishedAt) {
        try {
            if (this.existsPlayListItem(playlistId, videoId)) {
                return;
            }

            if (this.isBeforeDate(publishedAt)) {
                return;
            }

            const response = await axios.post('/api/youtube-play-list-items', {
                play_list_id: playlistId,
                video_id: videoId,
                published_at: publishedAt
            });
            const { data } = response;

            this.storedPlayListItems.push(data);
        } catch (e) {
            console.log(e.response);
        }
    }

    /**
     * 
     * @param {*} id 
     * @returns 
     */
    existsPlayListItem(playlistId, videoId) {
        const { storedPlayListItems } = this;
        const playListItem = storedPlayListItems.find(x => x.play_list_id === playlistId && x.video_id === videoId);

        if (playListItem) {
            return true;
        }

        return false;
    }
}