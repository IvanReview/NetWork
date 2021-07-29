<template>
  <aside class="sidebar">
    <div class="profile-card">
      <img  class="profile-card__photo"
            :src="getCurrentUser.image ? $store.state.serverUrl + getCurrentUser.image
                  : require(`../../assets/images/no_image.jpg`)" alt="user"
      >
      <h4 class="profile-card__name">
        <a class="profile-card__link" href="/" >{{getCurrentUser.name + " " + getCurrentUser.lastname}}</a>
      </h4>
      <a class="profile-card__followers" href="#" >
        <span class="mdi mdi-account-group icon-lock"></span> 1,299 followers
      </a>
    </div>

    <div class="action">
      <div class="action__wrap">
        <router-link class="action__edit-info" to="/feed-edit">Ред. страницу</router-link>
      </div>
    </div>

    <div class="sidebar-friends">
      <div class="sidebar-friends__title">
        <router-link to="/friends">Друзья</router-link>
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

        <li class="sidebar-online__item" v-for="friend in getOnlineUsers">
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
export default {
  name: "LeftSecondBar",
  data: () => ({
    current_user_id: ''
  }),
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
      'receiveCurrentUser',
      'fetchFriendsForLeftBar',
      'getOnlineFriends'
    ]),


  },
  computed: {
    ...mapGetters([
      'getUserData',
      'getCurrentUser',
      'getLoginUserId',
      'getFriendsForBar',
      'getOnlineUsers'

    ]),
  },
  updated() {

  },
  created() {
    this.current_user_id = this.getLoginUserId

  },

  mounted() {

    this.receiveCurrentUser(this.current_user_id)
    this.fetchFriendsForLeftBar(this.current_user_id)
    this.getOnlineFriends(this.current_user_id)
  }
}
</script>

<style scoped lang="sass">
.sidebar
  background-color: #fff
  .profile-card
    background: linear-gradient(to bottom, rgba(39,170,225,.8), rgba(28,117,188,.8)), url(../../assets/images/bg-1.jpg) no-repeat
    background-size: auto, auto
    background-size: cover
    width: 100%
    min-height: 90px
    border-radius: 5px
    padding: 10px 20px
    color: #fff
    .profile-card__photo
      border: 8px solid #fff
      position: relative
      top: -30px
      min-height: 150px
      min-width: 150px
      max-height: 350px
      max-width: 220px
    .profile-card__link
      color: aliceblue
    .profile-card__followers
      color: aliceblue
  .action
    margin: 20px 0
    padding: 40px 0
    background-color: #f8f8f8
    &__wrap
      display: block
      width: 100%
      margin: 0 auto
      text-align: center
    &__edit-info
      padding: 8px 25px
      background-color: cornflowerblue
      color: #fff
  .sidebar-online
    margin-top: 40px
    padding: 20px 0
    background-color: #f8f8f8
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
        margin: 10px 3px
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
    padding: 20px 0
    background-color: #f8f8f8
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
      margin: 10px 3px
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