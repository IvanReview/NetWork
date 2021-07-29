import axios from 'axios'
import * as auth from '../helpers/http_service';
import * as paginate from "../helpers/paginate";

let profile = auth.getProfile();
let isLogged = auth.isLoggedIn();


export default {
    state: {
        users: [],
        user: {},
        friend: {},
        currentUser: profile ? profile : {},
        users_online: [],
        friends: [],
        friends_left_bar: [],
        accept_friends: [],

        paginate_for_friend: {},
        userError: [],

        isLoggedIn: isLogged ? isLogged : null,

    },
    actions: {
        async login({commit, state}, userId) {
            try{
                let user = await axios(`/login/${userId}`);

                commit('set_user', user.data)

            } catch(e){
                console.log(e)
            }
        },


        //Данные пользователя к кому перешли на страницу
        async receiveUserOrFriendData({commit, state}, {user_id, friend_id}) {
            try{
                let user = await axios(`/user-friend/${user_id}/${friend_id}`);

                commit('set_user_or_friend', user.data)

            } catch(e){
                console.log(e)
            }
        },


        //Данные текущего залогированого пользователя
        async receiveCurrentUser({commit, state}, userId) {
            try{
                let user = await axios(`/main/${userId}`);

                commit('set_current_user', user.data)

            } catch(e){
                console.log(e)
            }
        },

        //Получить юзеров
        async fetchUsers({commit, state}, {offset, user_id}) {

            try{
                let users = await axios(`/users/${user_id}`, {
                    params: {offset}
                });

                commit('set_users', users.data)

            } catch(e){
                console.log(e)
            }
        },

        //Получить друзей
        async fetchFriends({commit, state}, {userId, page_url, itemsOnPage = 6}) {

            page_url = page_url || `/friends/${userId}?page=1`;
            try{
                let friends = await axios.get(page_url, {
                    params: {
                        items_on_page:   itemsOnPage,
                    }})

                commit('set_friends', friends.data.data)
                commit('set_accept_friends', friends.data.accept_friends)
                const pagination = paginate.makePagination(friends.data.pagination)
                commit('set_paginate', pagination)

            } catch(e){
                console.log(e)
            }
        },

        //Получить друзей для лефт бара
        async fetchFriendsForLeftBar({commit, state}, userId) {

            let page_url =  `/friends/${userId}?page=1`;
            try{
                let friends = await axios.get(page_url, {
                    params: {
                        items_on_page: 6
                    }})

                commit('set_friends_for_left', friends.data.data)

            } catch(e){
                console.log(e)
            }
        },

        //Получить друзей онлайн
        async getOnlineFriends({commit}, userId) {
            let page_url =  `/friends-online/${userId}`;
            try{
                let friends = await axios.get(page_url)

                commit('set_online_friends', friends.data.data)

            } catch(e){
                console.log(e)
            }
        },

        //Добавить в друзья
        async addFriend({commit}, {user_id, friend_id}) {
            try{
                let friend = await axios({
                    method: 'POST',
                    url: `/friend-add/${user_id}/${friend_id}`,
                });
                if (Object.keys(friend).length !== 0) {
                    commit('add_friend', friend_id)
                    commit('replace_user_in_users', friend.data)
                    commit('replace_user', friend.data)
                }
                return friend

            } catch(e){
                console.log(e)
            }
        },

        //Принять дружбу
        async acceptFriendInBd({commit}, {user_id, friend_id}) {
            try{
                let friend = await axios({
                    method: 'POST',
                    url: `/friend-accept/${user_id}/${friend_id}`,
                });

                commit('add_friend', friend.data)
                commit('clear_accept_friends', friend.data.id)

            } catch(e){
                console.log(e)
            }
        },

        //Редактировать профиль
        async editUserProfile({commit}, {user_id, formData}) {
            try{
                let user = await axios({
                    method: 'POST',
                    url: `/edit-user/${user_id}`,
                    data: formData,
                });

                commit('edit_user', user.data)
                return user.status

            } catch(e){
                console.log(e)
            }
        },

        //удалить из друзей
        async deleteFromFriendsInBd({commit}, {user_id, friend_id}) {
            try{
                let friend = await axios({
                    method: 'DELETE',
                    url: `/friend-delete/${user_id}/${friend_id}`,
                });

                commit('delete_friend', friend_id)
                commit('clear_accept_friends', friend_id)
                if (Object.keys(friend).length !== 0) {
                    commit('replace_user_in_users', friend.data)
                    commit('replace_user', friend.data)
                }

            } catch(e){
                console.log(e)
            }
        },

        //Найти пользователя
        async searchUserInBd({commit}, formData) {
            try {
                let users = await axios({
                    method: 'POST',
                    url: `/search-users/${formData.get('user_id')}`,
                    data: formData
                });

                commit('set_users_after_search', users.data)


            } catch (e) {
                console.log(e)
            }
        }


    },
    mutations: {

        set_user(state, user) {
            state.user = user
        },

        set_user_or_friend(state, user){
          state.friend = user
        },

        set_current_user(state, user) {
            /*window.localStorage.setItem('user', JSON.stringify(user))*/
            state.currentUser = user
        },

        set_users(state, users) {
           state.users =  state.users.concat(users)
        },

        replace_user_in_users(state, user) {

            state.users  = state.users.map(u => {
                if (u.id === user.id) return user
                else return u
            })

        },

        replace_user(state, user) {
            state.friend = user
        },

        set_users_after_search(state, users) {
            state.users =  users
        },

        set_friends(state, friends){
            state.friends = friends
        },

        set_accept_friends(state, friends) {
            state.accept_friends = friends
        },

        reset_users(state){
            state.users = []
        },

        delete_friend(state, friend_id){
            state.friends = state.friends.filter((friend) => friend.id !== friend_id)
        },

        add_friend(state, friend){
           state.friends.push(friend)
        },

        edit_user(state, user) {
            window.localStorage.setItem('user', JSON.stringify(user))
            state.currentUser = user
        },

        update_friend(state, friend) {

            let index = state.friends.findIndex(f => friend.id === f.id)
            state.friends.splice(index, 1, friend)
        },

        clear_accept_friends(state, friend_id) {
            state.accept_friends =  state.accept_friends.filter(f => friend_id !== f.id)
        },


        set_paginate(state, pagination) {
            state.paginate_for_friend = pagination
        },

        set_friends_for_left(state, friends) {
            state.friends_left_bar = friends
        },

        set_online_friends(state, users) {
            state.users_online = users
        }


    },

    getters: {
        getCurrentUser(state) {
            return state.currentUser
        },

        getCurrentUserOrFriend(state) {
            return state.friend
        },

        getUsers(state) {
            return state.users
        },

        getFriends(state) {
            return state.friends
        },

        getUserData(state) {
            return state.user
        },

        getLoginUserId(state) {
            return state.currentUser.id
        },


        getPaginationForFriends(state) {
            return state.paginate_for_friend
        },

        getFriendsForBar(state) {
            return state.friends_left_bar
        },

        getOnlineUsers(state) {
            return state.users_online
        },

        getAcceptFriends(state) {
            return state.accept_friends
        }


    }
}
