<template>
    <div class="list_wrapper" :class="{ hidden_info: playingVideo }">
        <div class="list_container">
            <div class="playlist_title">
                <p>{{ playlistTitle }}</p>
                <div class="playlist_menu" v-show="isPlaylist">
                    <i class="material-icons" title="プレイリスト名を編集" @click="playlistName = playlistTitle; listEditDialog = true">border_color</i>
                    <i class="material-icons" title="プレイリストを削除" @click="listDeleteDialog = true">delete</i>
                </div>
            </div>
            <table class="list_table">
                <tr class="list_header">
                    <th class="number_column"><p>#</p></th>
                    <th class="title_column"><p>タイトル</p></th>
                    <th class="artist_column"><p>アーティスト</p></th>
                    <th class="user_tag_column"><p>タグ</p></th>
                    <th class="duration_column"><p>時間</p></th>
                    <th class="menu_column"></th>
                </tr>
                <list-item v-for="(item, index) in videos" :key="index" :index="index" :video="item"/>
            </table>
            <div class="list_footer"></div>
        </div>
        <el-dialog title="プレイリストの編集" :visible.sync="listEditDialog">
            <el-input placeholder="プレイリスト名" v-model="playlistName"></el-input>
            <span slot="footer" class="dialog-footer">
                <el-button @click="listEditDialog = false; playlistName=''">キャンセル</el-button>
                <el-button type="primary" @click="editPlaylistTitle">編集</el-button>
            </span>
        </el-dialog>
        <el-dialog title="プレイリストの削除" :visible.sync="listDeleteDialog">
            <p style="font-size:20px;">"{{playlistTitle}}"を削除します。よろしいですか？</p>
            <span slot="footer" class="dialog-footer">
                <el-button @click="listDeleteDialog = false">キャンセル</el-button>
                <el-button type="primary" @click="deletePlaylist">削除</el-button>
            </span>
        </el-dialog>
    </div>
</template>

<script>
import { mapState } from 'vuex'

import ListItem from '../Form/ListItem.vue'

export default {
    components: {
        'list-item': ListItem
        
    }, 
    data() {
        return {
            listEditDialog: false, 
            listDeleteDialog: false, 
            playlistName: ''
        }
    }, 
    methods: {
        editPlaylistTitle() {
            this.$store.dispatch('playlists/editPlaylist', { playlistIndex: this.$store.state.system.currentList, title: this.playlistName})
            this.listEditDialog = false
            this.playlistName = ''
        }, 
        deletePlaylist() {
            this.$store.dispatch('playlists/delete', { playlistIndex: this.$store.state.system.currentList })
            this.listDeleteDialog = false
        }
    }, 
    computed: {
        // 表示する動画リスト
        videos() {
            // すべての動画
            if (this.$store.state.system.currentList == null) {
                return this.$store.state.videos.all
            // プレイリスト
            } else {
                return this.$store.state.playlists.videos;
            }
        }, 
        playingVideo() {
            return !Boolean(this.$store.getters.playingVideo)
        }, 
        playlistTitle() {
            if (this.$store.state.system.currentList == null) {
                return 'すべての動画'
            } else {
                return this.$store.state.playlists.all[this.$store.state.system.currentList].title
            }
        }, 
        isPlaylist() {
            return this.$store.state.system.currentList != null
        }
    }
}
</script>

<style lang="scss">
@import 'resources/assets/sass/variables';

.list_wrapper {
    width: calc(100% - #{$side-bar-width});
    height: calc(100vh - #{$contents-top});
    background-color: $color-list;
    color: $color-list-text;
    position: absolute;
    top: $contents-top;
    left: $side-bar-width;
    overflow: auto;
    transition-timing-function: ease-out;
    transition-duration: .2s;
}

.hidden_info {
    height: calc(100vh - #{$header-height});
    top: $header-height;
    transition-timing-function: ease-out;
    transition-duration: .2s;
}

.playlist_title { 
    height: 80px;
    padding: 16px;
    font-size: 30px;
    display: flex;
    justify-content: space-between;
}

.playlist_menu {
    width: 80px;
    padding: 12px;
    display: flex;
    justify-content: space-between;
    user-select: none;
    color: $color-background;

    i {
        cursor: pointer;
        transition-duration: .1s;

        &:hover {
            color: lighten($color-background, 10%);
            transform: scale(1.1);
        }

        &:active {
            color: darken($color-background, 5%);
            transform: scale(0.95);
        }
    }
}

.list_table {
    table-layout: fixed;
    width: 100%;
    user-select: none;
    text-align: left;
    font-size: 14px;

    p {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    th, td {
        padding: 10px;
    }

    td {
        border-top: 1px solid darken($color-list, 10%);
    }
}

.number_column {
    width: 20px;
}

.title_column {
    width: 60%;
}

.artist_column {
    width: 20%;
}

.user_tag_column {
    width: 20%;
}

.duration_column {
    text-align: right;
    width: 60px;
}

.menu_column {
    width: 40px;
}

.list_footer {
    width: 100%;
    height: 200px;
}

</style>
