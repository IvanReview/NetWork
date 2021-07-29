<template>
  <div class="all-users">
    <div class="near-user">
      <div class="container">
        <div class="users__inner">

          <LeftSecondBar />

          <main class="users">
            <!--Модальное окно-->
            <transition name="fade">
              <PopupMessage v-if="isPopupShow"
                            @closePopup="closePopup"
                            :user="user_data"
                            @sendMessage = sendNewMessage
              />
            </transition>

            <h2 class="users__title">Все пользователи</h2>

            <div class="search-form__wrap">
              <form class="search-form" @submit.prevent="searchUser">
                <input type="text" class="search-form__input" v-model="search_data" placeholder="Поиск пользователя...">
                <button class="search-form__btn">Найти</button>
              </form>
            </div>

            <p class="user-notfound" v-if="!getUsers.length">Пользователи не найдены </p>

            <div v-else class="user" v-for="user in getUsers">

              <div class="user__image-wrap">
                <img class="user__image" :src="user.image ? $store.state.serverUrl + user.image
                            : require(`../assets/images/no_image.jpg`)" alt="user">
              </div>

              <div class="user-info">
                <h5 class="user-info__name">
                  <router-link class="user-info__link" :to="`/user/${user.id}`" >
                    {{user.name}} {{user.lastname}}
                  </router-link>
                </h5>
                <p class="user-info__position">Software Engineer</p>

                <p class="user-info__action" @click="openPopup(user)">
                  Написать сообщение
                </p>
              </div>

              <div v-if="user.relation_type == 1" class="user-info__btn">
                <button class="user-del__btn" @click.prevent="deleteFromFriends(user.id)"> </button>
              </div>
              <div v-else-if="user.relation_type == 2" class="user-info__btn">
                <button class="user-send__btn" >Запрос отправлен</button>
              </div>
              <div v-else class="user-info__btn">
                <button class="user-add__btn" @click.prevent="addToFriends(user.id)">Добавить</button>
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
import _ from "lodash";
import PopupMessage from "./common/Popup-message";
import LeftSecondBar from "./common/LeftSecondBar";

export default {
  name: "AllUsers",
  components: {LeftSecondBar, PopupMessage, RightBar, LeftBar},
  data: () => ({
    search_data: '',
    page: 1,
    isPopupShow: false,
    user_data: {},

    current_user_id: ''

  }),
  methods: {
    ...mapActions([
        'fetchUsers',
        'searchUserInBd',
        'addNewMessageAndNewDialog',
        'receiveCurrentUser',
        'addFriend',
        'deleteFromFriendsInBd'

    ]),

    addToFriends(friend_id) {
      let user_id = this.current_user_id
      this.addFriend({user_id, friend_id}).then(resp => {
          if(resp.status === 200 ) {
            this.$toasted.show('Заявка в рузья отправлена',{
              type: 'success',
              position: "bottom-left",
            })
          }
      })
    },

    deleteFromFriends(friend_id) {
      let user_id = this.current_user_id
      this.deleteFromFriendsInBd({user_id, friend_id})
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

    openPopup(user) {
      this.isPopupShow = true
      this.user_data = user
    },

    searchUser() {
      let formData  =  new FormData()
      formData.append('search_data', this.search_data)
      formData.append('user_id', this.current_user_id)

      this.searchUserInBd(formData)
    },

    closePopup() {
      this.isPopupShow  = false
    },

    loadMoreUsers() {
      //вся высота контента
      let totalHeight = Math.max(
          document.body.scrollHeight, document.documentElement.scrollHeight,
          document.body.offsetHeight, document.documentElement.offsetHeight,
          document.body.clientHeight, document.documentElement.clientHeight
      );
      const scrollTop  =  document.documentElement.scrollTop //расстояние до верха
      const viewportHeight = window.innerHeight //высота видимой части

      //достигли дна
      const atTheBottom = totalHeight - Math.ceil(scrollTop + viewportHeight) < 10

      if (atTheBottom) {
        this.fetchUsers({offset: this.getUsers.length, user_id: this.current_user_id})
      }
    }

  },
  computed: {
    ...mapGetters([
      'getUsers',
      'getCurrentUser',
      'getLoginUserId'
    ]),

    delayHandler() {
      return  _.debounce(this.loadMoreUsers, 400)
    }
  },
  created() {
    this.current_user_id = this.getLoginUserId
    //Подгрузка при скролинге



    document.addEventListener('scroll', this.delayHandler)

  },
  mounted() {

    this.fetchUsers({offset: 0, user_id: this.current_user_id})

    this.receiveCurrentUser(this.current_user_id)
  },
  destroyed() {

    document.removeEventListener('scroll', this.delayHandler)
    this.$store.commit('reset_users')
  }
}
</script>

<style scoped lang="sass">
  .users__inner
    display: grid
    grid-template-columns: 250px 2fr
    grid-gap: 20px
    padding-bottom: 60px
    .users
      display: flex
      flex-direction: column
      position: relative
      padding: 10px
      .users__title
        text-align: left
        margin-bottom: 30px
        font-size: 25px
        padding: 0 30px
      .user
        display: flex
        padding: 10px
        width: 100%
        .user__image-wrap
          height: 100px
          width: 100px
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
        .user-info__action
          color: #39b54a
          cursor: pointer
        .user-info__btn
          text-align: center
        .user-add__btn
          border: none
          background: #149AC9
          transition: all .5s ease-in
          border-radius: 40px
          padding: 7px 30px
          color: aliceblue
          cursor: pointer
          &:hover
            background-color: darkorchid
        .user-send__btn
          border: none
          background: #8dc63f
          opacity: .6
          transition: all .5s ease-in
          border-radius: 40px
          padding: 7px 30px
          color: aliceblue
          cursor: pointer
          line-height: 15px
          font-size: 12px
        .user-del__btn
          border: none
          background: #EE204D
          transition: all .5s ease-in
          border-radius: 40px
          padding: 7px 30px
          color: aliceblue
          cursor: pointer
          &:hover
            background-color: darkorchid
          &:before
            content: "В друзьях"
          &:hover:before
            content: "Удалить"
    .search-form__wrap
      padding: 20px
      text-align: left
      .search-form
        display: flex
        flex-wrap: nowrap
      .search-form__input
        padding: 5px 10px
        width: 80%
        border: #ccc 1px solid
        &:focus
          outline: deepskyblue 1px solid
          border: deepskyblue 1px solid
          box-shadow: 0 0 10px #363838
      .search-form__btn
        background: #8dc63f
        border: none
        cursor: pointer
        color: #fff
        padding: 7px 15px
  .user-notfound
    text-align: center
  .fade-enter-active, .fade-leave-active
    transition: opacity .6s
  .fade-enter, .fade-leave-to
    opacity: 0


</style>