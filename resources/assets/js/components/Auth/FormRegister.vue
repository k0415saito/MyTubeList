<template>
    <div class="form_window">
        <div class="form_title">
            <h2>新規登録</h2>
        </div>
        <div class="form_content">
            <el-form :model="registerForm" ref="registerForm" :rules="rules" >
                <el-form-item prop="email">
                    <el-input placeholder="メールアドレス" type="email" v-model="registerForm.email"></el-input>
                </el-form-item>
                <el-form-item prop="password" ref="password">
                    <el-input placeholder="パスワード" type="password" v-model="registerForm.password"></el-input>
                </el-form-item>
                <el-form-item prop="password_confirmation" ref="password_confirmation">
                    <el-input placeholder="確認用パスワード" type="password" v-model="registerForm.password_confirmation"></el-input>
                </el-form-item>
                <el-form-item>
                    <el-button type="primary" @click="submitForm('registerForm')" :loading="awaitRegister">新規登録</el-button>
                </el-form-item>
                
            </el-form>
            <div class="change_mode"><a @click="$emit('modeChange')">>>ログイン</a></div>
        </div>
    </div>
</template>

<script>
import axios from 'axios'


export default {
    data () {
        return {
            awaitRegister: false, 
            registerForm: {
                email: '', 
                password: '', 
                password_confirmation: ''
            }, 
            rules: {
                email: [
                    { required: true, message: 'メールアドレスを入力してください', trigger: 'blur' }, 
                    { type: 'email', message: '正しいメールアドレスを入力してください', trigger: 'change' }
                ], 
                password: [
                    { trigger: 'blur', validator: (rule, value, callback) => {
                        if (value === '') {
                            callback(new Error('パスワードを入力してください'))
                        } else if (!value.match(/^[a-zA-Z0-9]{6,12}$/)) {
                            callback(new Error('6～12文字の半角英数字で入力してください'))
                        } else {
                            callback()
                        }
                    }}
                ], 
                password_confirmation: [
                    { trigger: 'blur', validator: (rule, value, callback) => {
                        if (value === '') {
                            callback(new Error('確認用パスワードを入力してください'))
                        } else if (value != this.registerForm.password){
                            callback(new Error('パスワードと一致しません'))
                        } else {
                            callback()
                        }
                    }}
                ]
            }
        }
    }, 
    methods: {
        submitForm(formName) {
            this.$refs[formName].validate(valid => {
                if (valid) {
                    this.register()
                }
            })
        }, 

        async register() {
            this.awaitRegister = true
            await axios.post('auth/register', {
                email: this.registerForm.email, 
                password: this.registerForm.password, 
                password_confirmation: this.registerForm.password_confirmation
            })
            .then(response => {
                this.$emit('loginSuccess')
            })
            .catch(error => {
                if (error.response.data.email){
                    this.$message({ message: 'そのメールアドレスは既に使用されています', type: 'error'})
                } else {
                    this.$message({ message: 'アカウント登録に失敗しました', type: 'error' })
                }
            })
            this.awaitRegister = false
        }
    }
}
</script>

<style lang="scss">
@import 'resources/assets/sass/mixin';

.form_window {
    @include form_window();
}

.form_title {
    @include form_title();
}

.form_content {
    @include form_content();
}

.change_mode {
    @include form_change();
}
</style>