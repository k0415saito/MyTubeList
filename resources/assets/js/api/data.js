import axios from 'axios'

export function addVideo(url) {
    return axios.post('video', { url: url })
}

export function editVideo(id, title, artist, usertag) {
    return axios.put('video', { id: id, title: title, artist: artist, usertag: usertag })
}

export function deleteVideo(videoId) {
    return axios.delete('video', { data: { video_id: videoId } })
}

export function getAllVideos() {
    return axios.get('video')
}

export function getPlaylists() {
    return axios.get('playlist')
}

export function createPlaylist(title) {
    return axios.post('playlist', { title: title })
}

export function deletePlaylist(playlistId) {
    return axios.delete('playlist', { data: { playlist_id: playlistId }})
}

export function addVideoToPlaylist(playlistId, videoId) {
    return axios.post('playlist/add', { playlist_id: playlistId, video_id: videoId })
}

export function getVideoInPlaylist(playlistId) {
    return axios.get(`playlist/${playlistId}`)
}

export function deleteVideoInPlaylist(playlistId, index) {
    return axios.delete('playlist/delete', { data: { playlist_id: playlistId, index: index}})
}

export function editPlaylist(playlistId, title) {
    return axios.put('playlist', { playlist_id: playlistId, title: title })
}