import Vue from 'vue'
import Vuex from 'vuex'
import users from "./users.js";
import posts from "./posts.js";
import messages from "./messages";
import images from "./images";
import comments from "./comments";
import axios from 'axios'

Vue.use(Vuex)

axios.defaults.baseURL = 'http://network2';

axios.defaults.headers.common["Authorization"] = `Bearer ` + localStorage.getItem('token')

export default new Vuex.Store({
  state: {
      serverUrl: 'http://network2/',
      socketUrl: 'ws://network2:8282'
  },
  mutations: {
  },
  actions: {
  },
  modules: {
        users,
        posts,
        messages,
        images,
        comments
    }
})
