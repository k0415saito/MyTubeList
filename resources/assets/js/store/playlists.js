import {
    getPlaylists, 
    createPlaylist, 
    deletePlaylist, 
    addVideoToPlaylist, 
    getVideoInPlaylist, 
    deleteVideoInPlaylist, 
    editPlaylist
} from '../api/data'

const state = {
    all: [], 
    videos: []
}

const getters = {

}

const actions = {
    // ユーザーのプレイリスト一覧をデータベースから取得
    getAll({ commit }) {
        return new Promise((resolve, reject) => {
            getPlaylists()
            .then(response => {
                commit('setPlaylist', { playlists: response.data })
                resolve(response)
            })
            .catch(error => {
                reject(error)
            })
        })
    }, 
    // 新規プレイリストを作成
    create({ commit }, title) {
        return new Promise((resolve, reject) => {
            createPlaylist(title)
            .then(response => {
                commit('addPlaylist', { playlist: response.data })
                resolve(response)
            })
            .catch(error => {
                reject(error)
            })
        })
    }, 
    // プレイリストの削除
    delete({ commit, state, rootState, dispatch }, { playlistIndex }) {
        return new Promise((resolve, reject) => {
            deletePlaylist(state.all[playlistIndex].id)
            .then(response => {
                if (rootState.system.playingList == playlistIndex) {
                    dispatch({ type: 'stop' }, { root: true })
                }
                if (rootState.system.currentList == playlistIndex) {
                    dispatch('changeList',null , { root: true })
                }
                commit('delete', { index: playlistIndex })
                resolve(response)
            })
            .catch(error => {
                reject(error)
            })
        })
    }, 
    // プレイリストに動画を追加
    addVideo({ commit, state, rootState}, { playlistIndex, videoId }) {
        return new Promise((resolve, reject) => {
            addVideoToPlaylist(state.all[playlistIndex].id, videoId)
            .then(response => {
                commit('setVideos', { index: playlistIndex, videos: response.data})
                if (rootState.system.currentList == playlistIndex) {
                    commit('updateVideoList', { index: playlistIndex })
                }
                resolve(response)
            })
            .catch(error => {
                reject(error)
            })
        })
    }, 
    // プレイリストの動画をデータベースから取得
    getVideos({ commit, state }, { index }) {
        return new Promise((resolve, reject) => {
            getVideoInPlaylist(state.all[index].id)
            .then(response => {
                commit('setVideos', { index: index, videos: response.data })
                resolve(response)
            })
            .catch(error => {
                reject(error)
            })
        })
    }, 
    // プレイリストから動画を削除
    deleteVideo({ commit, rootState }, { playlistIndex, videoIndex }) {
        return new Promise((resolve, reject) => {
            deleteVideoInPlaylist(state.all[playlistIndex].id, videoIndex)
            .then(response => {
                commit('setVideos', { index: playlistIndex, videos: response.data })
                if (rootState.system.currentList == playlistIndex) {
                    commit('updateVideoList', { index: playlistIndex })
                }
                resolve(response)
            })
            .catch(error => {
                reject(error)
            })
        })
    }, 
    // プレイリストのタイトルを変更
    editPlaylist({ commit, state }, { playlistIndex, title }) {
        return new Promise((resolve, reject) => {
            editPlaylist(state.all[playlistIndex].id, title)
            .then(response => {
                commit('editTitle', { index: playlistIndex, title: title })
                resolve(response)
            })
            .catch(error => {
                reject(error)
            })
        })
    }
}

const mutations = {
    // 全プレイリストの値をセット
    setPlaylist(state, { playlists }) {
        state.all = playlists
        state.all.forEach(value => {
            if (typeof value.videos == 'undefined') {
                value.videos = []
            }
        })
    }, 
    addPlaylist(state, { playlist }) {
        state.all.push(playlist)
        if (typeof playlist.videos == 'undefined') {
            playlist.videos = []
        }

    }, 
    // 指定したプレイリストの動画リストをセット
    setVideos(state, { index, videos }) {
        state.all[index].videos = videos;
    }, 
    // 表示用動画リストを更新
    updateVideoList(state, { index }) {
        state.videos = state.all[index].videos;
    }, 
    // プレイリストのタイトルを変更
    editTitle(state, { index, title }) {
        state.all[index].title = title
    }, 
    // プレイリストを削除
    delete(state, { index }) {
        state.all.splice(index, 1);
    }, 
    // プレイリスト内の動画情報を編集
    editVideo(state, { playlistIndex, videoIndex, title, artist, usertag}) {
        state.all[playlistIndex].videos[videoIndex].title = title
        state.all[playlistIndex].videos[videoIndex].artist = artist
        state.all[playlistIndex].videos[videoIndex].user_tag = usertag
    }
}

export default {
    namespaced: true, 
    state, 
    getters, 
    actions, 
    mutations
}