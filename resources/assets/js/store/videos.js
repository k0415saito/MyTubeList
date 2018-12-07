import { getAllVideos, addVideo, editVideo, deleteVideo} from '../api/data'

const state = {
    all: [],
    playlists: []
}

const getters = {
    // 現在再生中の動画の再生時間の秒数を取得
    currentDurationSeconds(state, getters, rootState, rootGetters) {
        const video = rootGetters['playingVideo']
        if (video) {
            const m = video.duration.match(/(\d+)H(\d+)M(\d+)S/)
            if (m.length == 4) {
                return Number(m[1]) * 3600 + Number(m[2]) * 60 + Number(m[3])
            }
        }
        return 0
    }
}

const actions = {
    // ユーザーの全動画を取得
    getAll({ commit }) {
        return new Promise((resolve, reject) => {
            getAllVideos()
            .then(response => {
                commit('setVideos', { videos: response.data })
                resolve(response)
            })
            .catch(error => {
                reject(error)
            })
        })
    }, 
    // 動画を追加
    add({ commit }, url) {
        return new Promise((resolve, reject) => {
            addVideo(url)
            .then(response => {
                commit('addVideo', { video: response.data})
                resolve(response)
            })
            .catch(error => {
                reject(error)
            })
        })
    }, 
    // 動画情報を編集
    edit({ commit, state, rootState }, { id, title, artist, usertag }) {
        return new Promise((resolve, reject) => {
            editVideo(id, title, artist, usertag)
            .then(response => {
                state.all.forEach((value, index) => {
                    if (value.id == id) {
                        commit('editVideo', { index: index, title: title, artist: artist, usertag: usertag })
                    }
                })
                rootState.playlists.all.forEach((value, index) => {
                    for(var i = 0; i < value.videos.length; i++) {
                        if (value.videos[i].id == id) {
                            commit('playlists/editVideo', 
                            { playlistIndex: index, videoIndex: i, title: title, artist: artist, usertag: usertag }, 
                            { root: true })
                        }
                    }
                })
                resolve(response)
            })
            .catch(error => {
                reject(error)
            })
        })
    }, 
    // 動画を削除
    delete({ commit, rootGetters, state, rootState, dispatch }, video) {
        return new Promise((resolve, reject) => {
            const deleteId = video.id
            const playingId = rootGetters.playingVideo && rootGetters.playingVideo.id
            deleteVideo(video.id)
            .then(response => {
                if (playingId == deleteId) {
                    dispatch({ type: 'stop' }, { root: true })
                }
                commit('setVideos', { videos: response.data })
                // プレイリスト内の動画も削除
                rootState.playlists.all.forEach((value) => {
                    for(var i = 0; i < value.videos.length; i++) {
                        if (value.videos[i].id == deleteId) {
                            value.videos.splice(i--, 1)
                        }
                    }
                })
                resolve(response)
            })
            .catch(error => {
                reject(error)
            })
        })
    }
}

const mutations = {
    // 全動画リストの値をセット
    setVideos(state, { videos }) {
        state.all = videos
    }, 
    // 全動画リストに追加
    addVideo(state, { video }) {
        state.all.push(video)
    }, 
    // 動画の情報を編集
    editVideo(state, { index, title, artist, usertag }) {
        state.all[index].title = title
        state.all[index].artist = artist
        state.all[index].user_tag = usertag
    }
}

export default {
    namespaced: true, 
    state, 
    getters, 
    actions, 
    mutations
}