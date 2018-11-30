<template>
    <div class="app">
        <div class="main" v-if="authenticated">
            <transition name="fade">
                <div class="overlay" v-if="overlay">
                    <i class="el-icon-loading"></i>
                    <p>loading...</p>
                </div>
            </transition>
            <app-header/>
            <transition name="el-zoom-in-top">
                <video-info-bar v-show="playingVideo"/>
            </transition>
            <side-bar/>
            <video-list/>
        </div>
        <template v-else>
            <login @loginSuccess="loggedIn"/>
        </template>
    </div>
</template>

<script>
import ls from 'local-storage'
import { mapState, mapActions } from 'vuex'
import { http } from './services/http'

import AppHeader    from './components/Layout/AppHeader.vue'
import VideoInfoBar from './components/Layout/VideoInfoBar.vue'
import SideBar      from './components/Layout/SideBar.vue'
import VideoList    from './components/Layout/VideoList.vue'
import Login        from './components/Auth/Login.vue'

export default {
    components: {
        'app-header'    : AppHeader, 
        'video-info-bar': VideoInfoBar, 
        'side-bar'      : SideBar, 
        'video-list'    : VideoList, 
        'login'         : Login
    }, 
    data() {
        return {
            overlay: false
        }
    }, 
    created() {
        // ローカルストレージにトークンがある場合は認証チェック
        if (ls.get('jwt-token')) {
            this.overlay = true
            this.authenticated = true
            this.authCheck()
        }
    }, 
    methods: {
        // トークンチェック
        async authCheck() {
            await http.ping()
            .then(response => {
                if (response.data.access_token) {
                    this.authenticated = true
                    this.init()
                }
            })
            .catch(error => {
                this.authenticated = false
                ls.remove('jwt-token')
            })
        }, 
        // ログイン後の初期化処理
        async init() {
            this.overlay = true
            // 全動画とプレイリスト一覧をデータベースから取得
            await this.$store.dispatch('videos/getAll')
            await this.$store.dispatch('playlists/getAll')
           
            // プレイリストの中身をデータベースから取得
            Promise.all(this.$store.state.playlists.all.map(async (list, index) => {
                await this.$store.dispatch('playlists/getVideos', { index: index })
            }))
            .then(() => {
                this.overlay = false
            })
        }, 

        loggedIn() {
            this.authenticated = true
            this.init()
        }
    }, 
    computed: {
        videos() {
            return this.$store.state.videos.all
        }, 
        playingVideo() {
            return Boolean(this.$store.getters.playingVideo)
        }, 
        authenticated: {
            get: function () {
                return this.$store.state.system.authenticated
            }, 
            set: function (value) {
                this.$store.commit('authenticate', value)
            }
        }
    }
}
</script>

<style lang="scss">
@import 'resources/assets/sass/variables';

.overlay {
    width: 100vw;
    height: 100vh;
    background-color: $color-background;
    display: flex;
    align-items: center;
    justify-content: center;
    position: fixed;
    z-index: 100;
    p {
        margin-left: 8px;
    }
}

.fade-leave-active {
    transition: opacity .5s;
}

.fade-leave-to {
    opacity: 0;
}
</style>
