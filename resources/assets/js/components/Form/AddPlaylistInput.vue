<template>
    <div class="add_playlist_container">
        <span class="add_list_loading" v-if="creating"><i class="el-icon-loading"></i></span>
        <span class="add_list_icon" v-else title="プレイリストを追加" @click="addPlaylistDialog = true"><i class="material-icons">playlist_add</i></span>
        <el-dialog title="プレイリストの作成" :visible.sync="addPlaylistDialog">
            <el-input placeholder="プレイリスト名" v-model="playlistName"></el-input>
            <span slot="footer" class="dialog-footer">
                <el-button @click="addPlaylistDialog = false; playlistName=''">キャンセル</el-button>
                <el-button type="primary" @click="createPlaylist">作成</el-button>
            </span>
        </el-dialog>
    </div>
</template>

<script>
export default {
    data() {
        return {
            addPlaylistDialog: false, 
            playlistName: "", 
            creating: false
        }
    }, 
    methods: {
        createPlaylist() {
            if (this.creating || this.playlistName == '') return
            this.creating = true;
            const name = this.playlistName
            this.playlistName = ''
            this.addPlaylistDialog = false
            this.$store.dispatch('playlists/create', name)
            .then(response => {
                this.creating  = false
            })
            .catch(error => {
                this.creating = false
            })
        }
    }
}
</script>

<style lang="scss">
@import 'resources/assets/sass/variables';

.add_playlist_container {
    right: 0px;
    width: 40px;
    height: 40px;
    position: absolute;
    display: flex;
    align-items: center;
    user-select: none;
    color: $color-text-dark;

    i {
        color: $color-text-light;
    }
}

.add_list_icon {
    cursor: pointer;

    
    &:hover {
        transition-duration: .1s;
        transform: scale(1.1);
    }

    &:active {
        transform: scale(0.95);
        i {
            color: darken($color-text-light, 10%);
        }
    }
}
</style>
