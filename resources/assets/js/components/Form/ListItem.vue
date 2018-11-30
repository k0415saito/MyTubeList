<template>
    <tr class="item_row" :class="{ item_selected: isSelected }" @dblclick="selectVideo" @dragstart="dragStart" :draggable="isLibrary" :index="index">
        <td><p>{{ index + 1 }}</p></td>
        <td><p>{{ title }}</p></td>
        <td><p>{{ artist }}</p></td>
        <td><p>{{ userTag }}</p></td>
        <td style="text-align:right"><p>{{ duration }}</p></td>
        <td>
            <el-dropdown trigger="click" @command="handleCommand">
                <span class="el-dropdown-link">
                    <i class="el-icon-arrow-down el-icon--right"></i>
                </span>
                <el-dropdown-menu slot="dropdown">
                    <el-dropdown-item command="edit">編集</el-dropdown-item>
                    <el-dropdown-item command="openURL">URLを開く</el-dropdown-item>
                    <el-dropdown-item command="delete">削除</el-dropdown-item>
                </el-dropdown-menu>
            </el-dropdown>
        </td>
        <el-dialog title="動画情報の編集" :visible.sync="videoEditDialog">
            <el-form label-width="100px">
                <el-form-item label="タイトル">
                    <el-input placeholder="タイトル" v-model="editTitle"></el-input>
                </el-form-item>
                <el-form-item label="アーティスト">
                    <el-input placeholder="アーティスト" v-model="editArtist"></el-input>
                </el-form-item>
                <el-form-item label="ユーザータグ">
                    <el-input placeholder="ユーザータグ" v-model="editUsertag"></el-input>
                </el-form-item>
            </el-form>
            <span slot="footer" class="dialog-footer">
                <el-button @click="videoEditDialog = false">キャンセル</el-button>
                <el-button type="primary" @click="editVideoInfo">編集</el-button>
            </span>
        </el-dialog>
    </tr>
</template>

<script>
import { mapState } from 'vuex'

export default {
    props: ['video', 'index'], 
    data() {
        return {
            videoEditDialog: false, 
            editTitle: '', 
            editArtist: '', 
            editUsertag: ''
        }
    }, 
    methods: {
        selectVideo() {
            this.$store.commit('selectVideo', { index: this.index, currentList: true})
            this.$store.state.system.player.loadVideoById(this.video.video_id)
        }, 
        editVideoInfo() {
            this.$store.dispatch('videos/edit', { id: this.video.id, title: this.editTitle, artist: this.editArtist, usertag: this.editUsertag })
            this.videoEditDialog = false
        }, 
        dragStart(e) {
            var dt = e.dataTransfer
            if (typeof e.target.attributes.index !== 'undefined') {
                dt.setData("text", `toPlaylist:${e.target.attributes.index.value}`)
                dt.setDragImage(e.target, 0, 0)
                dt.effectAllowed = "videoToPlaylist"
            }
        }, 
        handleCommand(command) {
            switch(command) {
                case 'edit':    // 編集
                    this.editTitle = this.title
                    this.editArtist = this.artist
                    this.editUsertag = this.userTag
                    this.videoEditDialog = true
                    break;
                case 'openURL': // URLを開く
                    window.open(`https://youtube.com/watch?v=${this.video.video_id}`)
                    break;
                case 'delete':  // 削除
                    // 全動画リストから削除する場合
                    if (this.$store.state.system.currentList == null) {
                        this.$store.dispatch('videos/delete', this.video)
                    // プレイリストから削除する場合
                    } else {
                        this.$store.dispatch('playlists/deleteVideo', { playlistIndex: this.$store.state.system.currentList, videoIndex: this.index})
                    }
                    break;
            }
        }
    }, 
    computed: {
        title() {
            return this.video.title
        }, 
        artist() {
            return this.video.artist
        }, 
        userTag() {
            return this.video.user_tag
        }, 
        duration() {
            const match = this.video.duration.match(/(\d+)H(\d+)M(\d+)S/)
            if (match.length == 4) {
                return (match[1] == '0' ? '' : `${match[1]}:`) + `${match[2]}:${('00' + match[3]).slice(-2)}`
            }
            return this.video.duration
        }, 
        isLibrary() {
            return (this.currentList == null && !this.videoEditDialog)
        }, 
        isSelected() {
            return (this.currentList == this.playingList &&
            this.index == this.playingIndex)
        }, 
        ...mapState({
            videos: state => state.videos.all, 
            playingIndex: state => state.system.playingIndex, 
            currentList: state => state.system.currentList, 
            playingList: state => state.system.playingList
        })
    }
}
</script>

<style lang="scss">
@import 'resources/assets/sass/variables';

.item_row {
    color: $color-text-dark;

    &:hover {
        background-color: $color-list-highlight;
        color: $color-text-dark;
    }
}

.item_selected {
    background-color: $color-theme;
    color: $color-text-light;
    i {
        color: $color-text-light;
    }

    &:hover {
        background-color: darken($color-list-highlight, 20%);
        color: $color-text-dark;
    }
}

</style>
