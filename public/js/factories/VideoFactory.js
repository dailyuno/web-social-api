class VideoFactory extends Factory {
    async get() {
        return super.get('/api/youtube-videos');
    }

    async create(params) {
        return super.create('/api/youtube-videos', params);
    }

    async update(id, params) {
        return super.update(`/api/youtube-videos/${id}`, params);
    }
}