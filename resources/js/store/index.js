import Vue from "vue";
import Vuex from "vuex";

Vue.use(Vuex);

const store = new Vuex.Store({
    state: {
        message: null,
        name: null,
        email: null,
        token: null,
    },
    mutations: {
        update(state) {
            state.message = '書き換え後のメッセージ'
        },
        message(state,message){
            state.message = message
        },
        resetMessage(state){
            state.message = null
        },
        updateUser(state, user) {
            state.name = user.name
            state.email = user.email
        },
        checkLogin(state, userInfo) {
            //vuexに格納
            state.name = userInfo.name
            state.email = userInfo.email
            state.token = userInfo.token

            //更新時にも残すようにローカルストレージに格納
            localStorage.setItem("token", state.token)
            localStorage.setItem("userName", state.name)
            localStorage.setItem("userEmail", state.email)
        },
        logout(state) {//vuexの内容を削除
            state.name = null
            state.email = null
            state.token = null            
        },
    }
}, );
export default store;
