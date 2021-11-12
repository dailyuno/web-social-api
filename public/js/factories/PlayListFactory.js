class PlayListFactory extends Factory {
    async get() {
        return super.get('/api/youtube-play-lists');
    }

    async create(params) {
        return super.create('/api/youtube-play-lists', params);
    }

    async update(id, params) {
        return super.update(`/api/youtube-play-lists/${id}`, params);
    }
}