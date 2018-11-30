import Vue from 'vue'
import Vuex from 'vuex'
import system from './system'
import videos from './videos'
import playlists from './playlists'

Vue.use(Vuex)

export default new Vuex.Store({
    modules: {
        system, 
        videos, 
        playlists
    }
})