<template>
  <aside class="sidebar">
    <div class="sidebar-menu">
      <ul class="sidebar-menu__items">
        <li class="sidebar-menu__item">
          <span class="mdi mdi-note-multiple icon-sidebar"></span>
          <router-link class="sidebar-menu__link" to="/feed">Моя страница</router-link>
        </li>
        <li class="sidebar-menu__item">
          <span class="mdi mdi-comment-edit icon-sidebar"></span>
          <router-link class="sidebar-menu__link" to="/feed-edit">Ред. страницу</router-link>
        </li>
        <li class="sidebar-menu__item">
          <span class="mdi mdi-account-group icon-sidebar"></span>
          <router-link class="sidebar-menu__link" to="/users">Все люди</router-link>
        </li>
        <li class="sidebar-menu__item">
          <span class="mdi mdi-account-group-outline icon-sidebar"></span>
          <router-link class="sidebar-menu__link" to="/friends">Друзья</router-link>
        </li>
        <li class="sidebar-menu__item">
          <span class="mdi mdi-forum"></span>
          <router-link class="sidebar-menu__link" to="/messages">Сообщения</router-link>
        </li>
        <li class="sidebar-menu__item">
          <span class="mdi mdi-image-multiple-outline"></span>
          <router-link class="sidebar-menu__link" to="/images">Фото</router-link>
        </li>
        <li class="sidebar-menu__item">
          <span class="mdi mdi-arrow-right-bold-circle-outline"></span>
          <span class="sidebar-menu__link" @click="logout">Выйти</span>
        </li>
        <!--<li class="sidebar-menu__item">
          <span class="mdi mdi-video"></span>
          <router-link class="sidebar-menu__link" to="/">Videos</router-link>
        </li>-->
      </ul>
    </div>

  </aside>
</template>

<script>
import {mapActions, mapGetters} from "vuex";
import axios from "axios";

export default {
  name: "LeftBar",
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

    ]),

    async logout() {
      try{
        let resp = await axios({
          method: 'POST',
          url: `/logout/${12}`,
        });

        if (resp.status === 200) {
          window.localStorage.removeItem('user')
          window.localStorage.removeItem('token')
          this.$router.push('/login')
        }

      } catch(e){
        console.log(e)
      }
    }

  },
  computed: {
    ...mapGetters([


    ]),
  },
  updated() {

  },
  created() {
    this.current_user_id = this.getLoginUserId

  },

  mounted() {


  }
}
</script>

<style scoped lang="sass">
.sidebar
  &-menu
    padding-left: 20px
    padding-right: 20px
    margin: 0 0 40px 0
    &__items
    &__item
      padding: 10px 0
      border-bottom: 1px solid rgba(#6d6e71, .1)
      font-size: 14px
      .mdi
        font-size: 18px
      .sidebar-menu__link
        margin-left: 20px
        color: #6d6e71
        transition: all .5s ease-in-out
        cursor: pointer
        &:hover
          color: #000
          font-weight: bold
      .mdi-note-multiple
        color: #8dc63f
      .mdi-account-group
        color: darkorchid
      .mdi-account-group-outline
        color: #ee2a7b
      .mdi-forum
        color: #f7941e
      .mdi-image-multiple-outline
        color: #1c75bc
      .mdi-video
        color: #9e1f63


</style>