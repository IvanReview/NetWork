<template>
  <aside class="sidebar">

    <div class="profile-card">
      <img class="profile-card__photo"
           :src="user.image ? $store.state.serverUrl + user.image
                : require(`../../assets/images/no_image.jpg`)" alt="user"
      >
      <h4 class="profile-card__name">
        <router-link class="profile-card__link" :to="`/user/${user.id}`" >
            {{user.name + " " + user.lastname}}
        </router-link>
      </h4>
      <router-link class="profile-card__followers" :to="`/friends-user/${user.id}`" >
          <span class="mdi mdi-account-group icon-lock"></span> {{getFriends.length}} followers
      </router-link>
      <router-link class="profile-card__image" :to="`/images-friends/${user.id}`">
          <span class=" mdi mdi-image-multiple"></span>
          Фото Пользователя
      </router-link>
    </div>

    <!--Модальное окно-->
    <transition name="fade">
      <PopupMessage v-if="isPopupShow"
                    @closePopup="closePopup"
                    :user="user"
                    @sendMessage = sendNewMessage
      />
    </transition>

    <div class="action">
      <div class="action__wrap">
          <a class="action__write" @click="openPopup">Написать</a>

          <a v-if="!user.relation_type" class="action__add" @click="addToFriends">Добавить</a>
          <a v-else-if="user.relation_type == 2" class="action__del" >Заявка отправлена</a>
          <a v-else class="action__del" @click="deleteFromFriends">Убрать</a>

      </div>
    </div>


    <div class="sidebar-friends">
      <div class="sidebar-friends__title">
        <router-link :to="`/friends-user/${user.id}`">Друзья</router-link>
      </div>
      <ul class="sidebar-friends__items">

        <li class="sidebar-friends__item" v-for="friend in getFriendsForBar">
          <router-link class="sidebar-friends__link" :to="`/user/${friend.id}`" >
            <img class="sidebar-friends__image"
                 :src="friend.image ? $store.state.serverUrl + friend.image : require(`../../assets/images/no_image.jpg`)"
                 alt="user">
            <p class="sidebar-friends__name">{{friend.name}}</p>
          </router-link>
        </li>

      </ul>
    </div>

    <div class="sidebar-online">
      <div class="sidebar-online__title">Кто онлайн</div>
      <ul class="sidebar-online__items">
        <li class="sidebar-online__item" v-for="friend in friends">
          <router-link class="sidebar-online__link" :to="`/user/${friend.id}`" >
            <img class="sidebar-online__image"
                 :src="friend.image ? $store.state.serverUrl + friend.image : require(`../../assets/images/no_image.jpg`)"
                 alt="user">
            <span class="sidebar-online__dote"></span>
            <p class="sidebar-online__name">{{friend.name}}</p>
          </router-link>
        </li>

      </ul>
    </div>

  </aside>
</template>

<script>
import {mapActions, mapGetters} from "vuex";
import PopupMessage from "./Popup-message";

export default {
  name: "UserLeftBar",
  data(){
    return {
      isPopupShow: false,
      user_data: {},
      userId: ''
    }
  },
  watch: {
    '$route.params.id': {
      immediate: true,
      handler() {
        this.$store.commit('post_reset')

        this.userId = window.location.href.split('/')[4]
      },
    },
  },
  components: {PopupMessage},
  props: {
    user: {
      type: Object,
      default: () => { return {}}
    },
    friends: {
      type: Array,
      default: function () {
        return []
      },
    }
  },
  methods: {
    ...mapActions([
      'fetchFriends',
      'receiveCurrentUser',
      'addNewMessageAndNewDialog',
      'fetchFriendsForLeftBar',
      'getOnlineFriends',
      'receiveUserOrFriendData',
      'deleteFromFriendsInBd',
      'addFriend'

    ]),

    addToFriends(friend_id) {

      this.addFriend({user_id: this.getLoginUserId, friend_id: this.userId}).then(resp => {

        if(resp.status === 200 ) {
          this.$toasted.show('Заявка в друзья отправлена',{
            type: 'success',
            position: "bottom-left",
          })
        }
      })
    },

    deleteFromFriends() {

      this.deleteFromFriendsInBd({user_id: this.getLoginUserId, friend_id: this.userId})
    },



    sendNewMessage(message) {
      let formData = new FormData()
      formData.append('message', message)
      formData.append('target_user_id', this.user.id)
      formData.append('user_id', this.getLoginUserId)

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

    closePopup() {
      this.isPopupShow  = false
    },


  },
  computed: {
    ...mapGetters([
      'getUserData',
      'getFriends',
      'getCurrentUser',
      'getLoginUserId',
      'getFriendsForBar'

    ]),
  },
  updated() {

  },

  mounted() {

    this.fetchFriendsForLeftBar(this.userId)
  }
}
</script>

<style scoped lang="sass">
.sidebar
  .profile-card
    background: linear-gradient(to bottom, rgba(39,170,225,.8), rgba(28,117,188,.8)), url(../../assets/images/bg-1.jpg) no-repeat
    background-size: auto, auto
    background-size: cover
    width: 100%
    min-height: 90px
    border-radius: 4px
    padding: 10px 20px
    color: #fff
    margin-bottom: 40px
    .profile-card__photo
      border: 7px solid #fff
      margin-right: 20px
      position: relative
      top: -30px
      min-height: 150px
      max-height: 350px
      max-width: 220px
      min-width: 150px
    .profile-card__link
      color: aliceblue
    .profile-card__followers
      color: aliceblue
    .profile-card__image
      display: flex
      font-size: 13px
      line-height: 15px
      margin-top: 10px
      color: darkorange
  .action
    &__wrap
      display: flex
      flex-wrap: wrap
      flex-direction: column
      text-align: center
    &__add
      display: block
      background-color: deepskyblue
      padding: 5px 15px
      color: #fff
      margin: 10px
      cursor: pointer
    &__del
      display: block
      background-color: orangered
      padding: 5px 15px
      color: #fff
      margin: 10px
      cursor: pointer
    &__write
      display: block
      background-color: cornflowerblue
      padding: 5px 15px
      color: #fff
      cursor: pointer
      margin: 10px
  .sidebar-online
    margin-top: 20px
    padding: 20px 0
    .sidebar-online__title
      background: #8dc63f
      text-align: center
      padding: 2px 20px
      width: 70%
      height: 30px
      border-radius: 15px
      position: relative
      margin: 0 auto 20px
      color: #fff
      font-size: 14px
      font-weight: 600
    .sidebar-online__items
      padding-left: 20px
      padding-right: 20px
      text-align: center
      margin: 0
      display: flex
      justify-content: space-between
      flex-wrap: wrap
      .sidebar-online__item
        position: relative
        margin: 10px 0
        .sidebar-online__image
          height: 58px
          width: 58px
          border-radius: 50%
        .sidebar-online__name
          font-size: 13px
          color: #363838
        .sidebar-online__dote
          position: absolute
          background: linear-gradient(to bottom, rgba(141,198,63, 1), rgba(0,148,68, 1))
          border: none
          height: 12px
          width: 12px
          border-radius: 50%
          bottom: -6px
          border: 2px solid #fff
          top: 19px
          left: 0
          right: 0
          margin: auto
  .sidebar-friends
    margin-top: 20px
    padding: 20px 0
    &__title
      background: deepskyblue
      text-align: center
      padding: 2px 20px
      width: 70%
      height: 30px
      border-radius: 15px
      position: relative
      margin: 0 auto 20px
      color: #fff
      font-size: 14px
      font-weight: 600
      a
        color: aliceblue
    &__items
      padding-left: 20px
      padding-right: 20px
      text-align: center
      margin: 0
      display: flex
      justify-content: space-between
      flex-wrap: wrap
    &__item
      position: relative
      margin: 10px 0
    &__image
      height: 58px
      width: 58px
      border-radius: 50%
    &__name
      font-size: 13px
      color: #363838
    &__dote
      position: absolute
      background: linear-gradient(to bottom, rgba(141,198,63, 1), rgba(0,148,68, 1))
      border: none
      height: 12px
      width: 12px
      border-radius: 50%
      bottom: -6px
      border: 2px solid #fff
      top: 19px
      left: 0
      right: 0
      margin: auto
</style>