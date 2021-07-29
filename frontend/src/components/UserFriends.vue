<template>
  <div class="all-users">
    <div class="near-user">
      <div class="container">
        <div class="users__inner">

          <user-left-bar :user="getCurrentUserOrFriend" :friends="getFriends"/>

          <main class="users">
            <!--Модальное окно-->
            <transition name="fade">
              <PopupMessage v-if="isPopupShow"
                            @closePopup="closePopup"
                            :user="user_data"
                            @sendMessage = sendNewMessage
              />
            </transition>

            <h2 class="users__title">Друзья  Пользователя {{getUserData.name}} {{getUserData.lastname}}</h2>

            <p class="no-friends" v-if="!getFriends.length">У вас нет друзей</p>

            <div v-else class="user" v-for="user in getFriends">
              <div class="user_image-wrap">
                <img class="user__image"
                     :src="user.image ?  $store.state.serverUrl + user.image
                   : require(`../assets/images/no_image.jpg`)" alt="user">
              </div>


              <div class="user-info">
                <h5 class="user-info__name">
                  <router-link class="user-info__link" :to="`/user/${user.id}`" >
                    {{user.name}} {{user.lastname}}
                  </router-link>
                </h5>
                <p class="user-info__position">Software Engineer</p>
                <p class="user-info__message" @click="openPopup(user)">Написать сообщение</p>
              </div>

              <div class="user-info__action">

              </div>
            </div>

            <!--Пагинация-->
            <div class="pagination">
              <div class="pagination-wrap">
                <div class="pagination__arrow"
                     :class="[{disabled: getPaginationForFriends.prev_page_url === null}]"
                     @click.prevent="fetchFriends({userId: current_user_id, page_url: getPaginationForFriends.prev_page_url})"
                >
                  <
                </div>
                <div v-for="(page, index) of getPaginationForFriends.pages"
                     class="pagination__page "
                     :class="[{pagination__page_active: Number(getPaginationForFriends.current_page) === Number(index + 1) }]"
                     @click.prevent="fetchFriends({userId: current_user_id, page_url: page})"
                >
                  {{ index + 1 }}
                </div>
                <!--<div class="pagination__page">2</div>-->
                <div class="pagination__arrow"
                     :class="[{disabled: getPaginationForFriends.next_page_url === null}]"
                     @click.prevent="fetchFriends({userId: current_user_id, page_url: getPaginationForFriends.next_page_url})"
                >
                  >
                </div>
              </div>
            </div>
          </main>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import LeftBar from "./common/LeftBar";
import RightBar from "./common/RightBar";
import {mapActions, mapGetters} from "vuex";
import PopupMessage from "./common/Popup-message";
import FriendAccept from "./FriendAccept";
import LeftSecondBar from "./common/LeftSecondBar";
import UserLeftBar from "./common/UserLeftBar";
export default {
  name: "UserFriends",
  components: {UserLeftBar, LeftSecondBar, FriendAccept, PopupMessage, RightBar, LeftBar},
  data: () => ({
    search_data: '',
    isPopupShow: false,
    user_data: {},
    current_user_id: '',

    friend_id: '',
    num: 5,

  }),
  watch: {
    '$route.params.id': {
      immediate: true,
      handler() {
        this.friend_id = window.location.href.split('/')[4]

      },
    },
  },
  methods: {
    ...mapActions([
      'fetchFriends',
      'deleteFromFriendsInBd',
      'addNewMessageAndNewDialog',
      'receiveCurrentUser',
      'acceptFriendInBd',
      'receiveUserOrFriendData',

    ]),

    openPopup(user) {
      this.isPopupShow = true
      this.user_data = user
    },

    closePopup() {
      this.isPopupShow  = false
    },

    sendNewMessage(message) {
      let formData = new FormData()
      formData.append('message', message)
      formData.append('target_user_id', this.user_data.id)
      formData.append('user_id', this.current_user_id)

      this.addNewMessageAndNewDialog(formData).then(resp => {
        if (resp === 200) {
          this.isPopupShow = false
        }
      })
    },



  },
  computed: {
    ...mapGetters([
      'getFriends',
      'getCurrentUser',
      'getLoginUserId',
      'getPaginationForFriends',
      'getAcceptFriends',
      "getUserData",
      'getCurrentUserOrFriend'

    ]),
  },
  created() {
    this.current_user_id = this.getLoginUserId
  },
  mounted() {
    let userId = this.friend_id
    this.fetchFriends({userId, page: 1})

    this.receiveUserOrFriendData({user_id: this.current_user_id, friend_id: userId})
  }
}
</script>

<style scoped lang="sass">
.users__inner
  display: grid
  grid-template-columns: 250px 3fr 200px
  .users
    display: flex
    flex-direction: column
    position: relative
    padding: 10px
    margin-bottom: 50px
    padding-bottom: 50px
    .users__title
      text-align: left
      margin-bottom: 30px
      font-size: 25px
      padding: 0 30px
    .no-friends
      margin: 0 auto
      font-size: 21px
    .user
      display: flex
      padding: 20px
      .user_image-wrap
        width: 100px
        height: 100px
        overflow: hidden
      .user__image
        height: 80px
        width: 80px
        border-radius: 50%
      .user-info
        margin-left: 40px
        width: 380px
        .user-info__name
        .user-info__link
          font-size: 25px
          font-weight: 800
          color: #149AC9
        .user-info__position
          line-height: 26px
          color: #6d6e71
          font-size: 13px
        .user-info__message
          color: #39b54a
          cursor: pointer
      .user-info__action
        text-align: center
        display: flex
        flex-direction: column
        justify-content: center
        align-items: center
        .user-info__btn
          display: block
          border: none
          background: #EE204D
          transition: all .5s ease-in
          border-radius: 40px
          padding: 7px 30px
          color: aliceblue
          cursor: pointer
          &:hover
            background-color: darkorchid
        .user-info2__btn
          border: none
          background: #149AC9
          transition: all .5s ease-in
          border-radius: 40px
          padding: 7px 30px
          color: aliceblue
          cursor: pointer
          margin-top: 5px
          &:hover
            background-color: #8dc63f
  .user-notfound
    text-align: center
  .fade-enter-active, .fade-leave-active
    transition: opacity .6s
  .fade-enter, .fade-leave-to
    opacity: 0


</style>