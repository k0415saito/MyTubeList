<template>
    <div class="side_wrapper" :class="{ hidden_info: playingVideo }">
        <div class="category_title">
            <p>ライブラリ</p>
        </div>
        <div class="sidebar_button" 
            :class="{ selected_button: currentList == null }" 
            @click="selectPlaylist(null)">
            <p>すべての動画</p>
        </div>
        <div class="category_title">
            <add-playlist-input/><p>プレイリスト</p>
        </div>
        <div class="sidebar_button" 
            v-for="(item, index) in playlists" 
            :key="item.id" 
            :class="{ selected_button: currentList == index}"
            :index="index"
            @click="selectPlaylist(index)"
            @dragover="dragOver"
            @drop="dropItem">
            {{ item.title }}
        </div>
        <div class="category_title">
            <p>ユーザー</p>
        </div>
        <el-popover placement="right" v-model="logoutPop" visible-arrow="">
            <p>本当にログアウトしますか？</p>
            <div style="text-align: right; margin-top: 8px">
                <el-button type="primary" size="mini" @click="logout">OK</el-button>
                <el-button size="mini" @click="logoutPop = false">キャンセル</el-button>
            </div>
            <div class="sidebar_button" @click="logoutPop = true" slot="reference">
                <i class="material-icons">exit_to_app</i><p>ログアウト</p>
            </div>
        </el-popover>
    </div>
</template>

<script>
import AddPlaylistInput from '../Form/AddPlaylistInput'

export default {
    components: {
        'add-playlist-input': AddPlaylistInput
    }, 
    data() {
        return {
            logoutPop: false
        }
    }, 
    methods: {
        selectPlaylist(index) {
            this.$store.dispatch('changeList', index)
        }, 
        dragOver(e) {
            e.preventDefault()
            e.dataTransfer.dropEffect = "videoToPlaylist"
        }, 
        dropItem(e) {
            e.preventDefault()
            const videoIndex = Number(e.dataTransfer.getData("text").match(/toPlaylist:(\d+)/)[1])
            const id = this.videos[videoIndex].id
            const playlistIndex = Number(e.target.attributes.index.value)
            this.$store.dispatch('playlists/addVideo', { playlistIndex: playlistIndex, videoId: id })
        }, 
        logout() {
            this.$store.dispatch('logout')
        }
    }, 
    computed: {
        playingVideo() {
            return !Boolean(this.$store.getters.playingVideo)
        }, 
        playlists() {
            return this.$store.state.playlists.all
        }, 
        currentList() {
            return this.$store.state.system.currentList
        }, 
        videos() {
            return this.$store.state.videos.all
        }
    }
}
</script>

<style lang="scss">
@import 'resources/assets/sass/variables';
@import 'resources/assets/sass/mixin';

.side_wrapper {
    width: $side-bar-width;
    height: calc(100vh - #{$contents-top});
    background-color: $color-background;
    position: absolute;
    top: $contents-top;
    transition-duration: .2s;
    transition-timing-function: ease-out;
    overflow: auto;
    overflow-x: hidden;
}

.hidden_info {
    height: calc(100vh - #{$header-height});
    top: $header-height;
    transition-timing-function: ease-out;
    transition-duration: .2s;
}

.sidebar_button {
    @include sidebar_button();

    &:hover {
        background-color: darken($color-background, 5%);
    }
}

.selected_button {
    @include sidebar_button_selected();
}

.category_title {
    width: 100%;
    height: 60px;
    background-color: darken($color-theme, 15%);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
}

</style>
