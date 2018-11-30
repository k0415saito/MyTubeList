import axios from 'axios'
import ls from 'local-storage'

export const http = {

    init() {
        // リクエストのヘッダーに常にトークンをつける
        axios.defaults.baseURL = `${location.protocol}//${location.host}/api`
        axios.interceptors.request.use(config => {
            config.headers.Authorization = `Bearer ${ls.get('jwt-token')}`
            return config
        })

        // レスポンスにトークンが含まれる場合はローカルストレージに保存する
        axios.interceptors.response.use(response => {
            const token = response.headers['Authorization'] || response.data.access_token
            token && ls.set('jwt-token', token)
            return response
        }, error => {
            return Promise.reject(error)
        })
    }, 
    
    ping() {
        return axios.get('ping')
    }
}