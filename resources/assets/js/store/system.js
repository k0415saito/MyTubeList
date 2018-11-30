import ls from 'local-storage'

const state = {
    authenticated: false, 
    player: null, 
    currentList: null, 
    playingList: null, 
    playingIndex: -1, 
    playerState: -1, 
    volume: 50
}

const getters = {
    playingVideo (state, getters, rootState) {
        if (state.playingIndex >= 0) {
            if (state.playingList != null) {
                return rootState.playlists.all[state.playingList].videos[state.playingIndex]
            } else {
                return rootState.videos.all[state.playingIndex]
            }
        }
        return null
    }, 
    playingList (state, getters, rootState) {
        if (state.playingIndex >= 0) {
            if (state.playingList != null) {
                return rootState.playlists.all[state.playingList].videos
            } else {
                return rootState.videos.all
            }
        }
        return null
    }
}

const actions = {
    play({ state, getters }) {
        if (!state.player || !getters['playingVideo']) return
        if (state.playerState == '1') {
            state.player.pauseVideo()
        } else {
            state.player.playVideo()
        }
    }, 
    stop({ state, commit }) {
        if (!state.player) return
        state.player.stopVideo()
        commit('stopVideo')
    }, 
    next({ commit, state, getters}) {
        if (!state.player) return
        if (getters['playingList'] != null && 
        state.playingIndex + 1 < getters['playingList'].length) {
            commit('selectVideo', { index: state.playingIndex + 1 })
            state.player.loadVideoById(getters['playingVideo'].video_id)
        }
    }, 
    prev({ commit, state, getters}) {
        if (!state.player) return
        if (getters['playingList'] != null &&
        state.playingIndex > 0) {
            commit('selectVideo', { index: state.playingIndex - 1})
            state.player.loadVideoById(getters['playingVideo'].video_id)
        }
    }, 
    seekTo({ state }, seconds) {
        if (!state.player) return
        state.player.seekTo(seconds, true)
    }, 
    changeList({ commit}, index) {
        if (index != null) {
            commit('playlists/updateVideoList', { index : index })
        }
        commit('changeList', { index: index })
    },
    logout() {
        ls.clear()
        window.location.replace("./")
    }
}

const mutations = {
    authenticate(state, value) {
        state.authenticated = value
    }, 
    setPlayer(state, { player }) {
        state.player = player
    }, 
    changeState(state, { playerState }) {
        state.playerState = playerState
    }, 
    changeList(state, { index }) {
        state.currentList = index
    }, 
    selectVideo(state, { index, currentList = false }) {
        if (currentList) {
            state.playingList = state.currentList
        }
        state.playingIndex = index
    }, 
    setVolume(state, { value }) {
        state.volume = value
        ls.set('player-volume', value)
    }, 
    stopVideo(state) {
        state.playingList = null
        state.playingIndex = -1
    }
}

const vol = ls.get('player-volume')
if (vol) {
    state.volume = vol
}

export default {
    state, 
    getters, 
    actions, 
    mutations
}