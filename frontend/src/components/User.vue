<template>
  <div class="home">

    <main class="user__main">

        <user-left-bar :user="getCurrentUserOrFriend" :friends="getFriends"/>

        <div class="content">
          <div class="profile-info">
            <div class="profile-info__birth ">
              <div class="profile-info__birth-text">День рождения:</div>
              <div class="profile-info__birth-date">3 февраля {{getCurrentUserOrFriend.date_birth}}</div>
            </div>
            <div class="profile-info__town ">
              <div class="profile-info__town-text">Город:</div>
              <div class="profile-info__town-name">Ульяновск</div>
            </div>
            <div class="profile-info__status">
              <div class="profile-info__status-text">Статус:</div>
              <div class="profile-info__status-name">Чето там</div>
            </div>
          </div>

          <div class="wall">

            <div class="create-post">
              <div class="post-form">
                <div>
                  <img class="post-form__img"
                       :src="getCurrentUser.image ? $store.state.serverUrl + getCurrentUser.image
                              : require(`../assets/images/no_image.jpg`)"
                       alt=""
                  >
                  <p style="padding-left: 6px; font-size: 13px">{{getCurrentUser.name}}</p>
                </div>
                <form class="post-form__content" @submit.prevent="addNewPost">
                    <textarea class="post-form__textarea" name="text"
                              cols="30" rows="2"
                              v-model="postText"
                              placeholder="Что нового?"></textarea>
                  <div>
                    <button class="post-form__btn">Опубликовать</button>
                  </div>
                </form>
              </div>

              <div class="tools">
                <ul class="publishing-tools ">
                  <li class="publishing-tools__item">
                    <a class="publishing-tools__link" href="#">
                      <span class="mdi mdi-image-multiple"></span>
                    </a>
                  </li>
                  <li class="publishing-tools__item">
                    <a class="publishing-tools__link" href="#">
                      <span class="mdi mdi-video"></span>
                    </a>
                  </li>
                  <li class="publishing-tools__item">
                    <a class="publishing-tools__link" href="#">
                      <span class="mdi mdi-map-legend"></span>
                    </a>
                  </li>
                  <li class="publishing-tools__item">
                    <a class="publishing-tools__link" href="#">
                      <span class="mdi mdi-image-multiple"></span>
                    </a>
                  </li>
                </ul>
              </div>
            </div>

            <div class="post-container">

              <Loader v-if="loader"/>

              <p v-else-if="!getPosts.length">Записей нет! Напишите что нибудь...</p>

              <div class="one-post" v-else v-for="post in getPosts">

                <div class="post-header">

                  <div class="post-header__wrap">
                    <a class="post-header__link" href="/id212084634">
                      <img class="post-header__image"
                           :src="post.image ? $store.state.serverUrl + post.image
                                : require(`../assets/images/no_image.jpg`)"
                           alt="Honor Never-Dies" width="50" height="50"
                      >
                    </a>
                    <div class="post-header__info">
                      <h5 class="post-author">
                        <router-link class="post-author__name" :to="`/user/${post.author_id}`" >
                          {{post.name}} {{post.lastname}}
                        </router-link>
                        <span class="post-author__recording "> following</span>
                      </h5>
                      <div class="post-date">
                        <span class="post_date__text" >{{post.created_at | dateFilter}}</span>
                      </div>
                    </div>
                  </div>

                  <div class="reaction">
                    <a class="reaction__link-like reaction__link" @click="addLikeDislikeToPost(1, post.id)">
                      <span class="mdi mdi-thumb-up"></span> {{post.likes_count}}
                    </a>
                    <a class="reaction__link-dislike reaction__link" @click="addLikeDislikeToPost(0, post.id)">
                      <span class="mdi mdi-thumb-down"></span> {{post.dislikes_count}}
                    </a>
                  </div>
                </div>

                <div class="post-detail">

                  <div class="post-detail__wrap-text">
                    <p class="post-detail__text">
                      <i class="em em-thumbsup"></i> <i class="em em-thumbsup"></i>
                      {{post.text}}
                    </p>
                  </div>

                  <!--Комментариии к посту-->
                  <div class="post-comment" v-for="comment  in post.comments">
                    <img class="post-comment__image"
                         :src="comment.image ? $store.state.serverUrl + comment.image
                              : require(`../assets/images/no_image.jpg`)" alt=""
                    >
                    <div class="post-comment__wrap">
                      <h4 class="post-comment__name">
                        {{comment.name}} {{comment.lastname}}
                      </h4>
                      <span class="post-comment__date">{{comment.created_at | dateFilter}}</span>

                      <p class="post-comment__text">
                        {{comment.text}}
                      </p>
                    </div>
                  </div>

                  <div class="comment-form__show" @click="showForm(post.id)">
                    <span class="mdi mdi-message-processing-outline icon-sidebar"></span>  Комментарий
                  </div>
                  <!--Форма добавлен комментария-->
                  <div v-if="show_form && post.id === current_post" class="comment-form__wrap">

                    <img class="comment-form__img"
                         :src="getCurrentUser.image ? $store.state.serverUrl + getCurrentUser.image
                          : require(`../assets/images/no_image.jpg`)" alt=""
                    >

                    <form class="comment-form" @submit.prevent = "addComment(post.id)">
                      <input class="comment-form__text" type="text" placeholder="Post a comment" v-model="comment">
                      <button class="comment-form__btn"><span class="mdi mdi-send"></span></button>
                    </form>
                  </div>

                </div>
              </div>

            </div>
          </div>
        </div>
    </main>

  </div>
</template>

<script>
import LeftBar from "./common/LeftBar";
import {mapGetters,mapActions} from 'vuex'
import _ from 'lodash'
import Loader from "./loader/Loader";
import UserLeftBar from "./common/UserLeftBar";

export default {
  name: "User",
  components: {UserLeftBar, Loader, LeftBar},
  data: () => ({
    search: '',
    postText: '',
    comment: '',
    loader: true,
    userId: '', //юзер к которому зашли
    logged_user_id: '',

    show_form: false,
    current_post: '',

  }),
  beforeRouteEnter(to, from, next){
    next(vm => {
      /*vm.userId = to.params.id*/

    })
  },
  watch: {
    '$route.params.id': {
      immediate: true,
      handler() {
        this.$store.commit('post_reset')
        this.logged_user_id = this.getLoginUserId

        this.userId = window.location.href.split('/')[4]

        this.fetchData()
      },
    },
  },
  methods: {
    ...mapActions([
      'fetchPosts',
      'addPostInBd',
      'addCommentToPost',
      'addLikeToPost',
      'deletePostFromBd',
      'fetchFriends',
      'receiveCurrentUser',
      'receiveUserOrFriendData'

    ]),

    showForm(post_id) {
      this.current_post = post_id
      this.show_form = !this.show_form
    },

    addNewPost(){
      if (!this.postText) return
      let formData = new FormData()
      formData.append('text', this.postText)

      this.addPostInBd({formData, userId: this.userId, author_id: this.logged_user_id})
          .then((resp)=>{
              if (resp === 200) this.postText = ''
          })
    },

    addComment(post_id){
      let formData = new FormData()
      formData.append('text', this.comment)
      formData.append('post_id', post_id)

      this.addCommentToPost({formData: formData, user_id: this.logged_user_id}).then((resp) => {
        if (resp === 200){
          this.comment = ''
        }
      })
    },

    addLikeDislikeToPost(status, post_id) {
      let formData = new FormData()
      formData.append('status', status)
      formData.append('post_id', post_id)
      formData.append('user_id', this.logged_user_id)

      this.addLikeToPost(formData)
    },


    fetchData() {

      /*this.receiveUser(this.userId)*/
      //получить пользователя к кому перешли
      this.receiveUserOrFriendData({user_id: this.logged_user_id, friend_id: this.userId})
      this.receiveCurrentUser(this.logged_user_id)//получить текущ авторез пользов

      this.fetchFriends({userId: this.userId})//друзья пользователя к которому перешли
      this.fetchPosts({offset: 0, userId: this.userId}).then(resp => {
        if (resp === 200) this.loader = false
      })
    },

    loadMorePosts() {
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
        this.fetchPosts({offset: this.getPosts.length, userId: this.userId})
      }
    }
  },
  computed: {
    ...mapGetters([
      'getUserData',
      'getPosts',
      'getFriends',
      'getCurrentUser',
      'getLoginUserId',
      'getCurrentUserOrFriend',

    ]),

    delayHandler() {
      return  _.debounce(this.loadMorePosts, 400)
    }
  },
  created() {
    this.logged_user_id = this.getLoginUserId
    //Подгрузка при скролинге

    document.addEventListener('scroll', this.delayHandler)

  },
  mounted() {

  },
  destroyed() {
    document.addEventListener('scroll', this.delayHandler)
    this.$store.commit('post_reset')
  }
}
</script>

<style scoped lang="sass">

  .user__main
    display: grid
    grid-template-columns: 250px 2fr 100px
    grid-gap: 30px
    .profile-info
      background-color: #f8f8f8
      padding: 20px
      font-size: 14px
      .profile-info__birth
        display: flex
        .profile-info__birth-text
          width: 150px
      .profile-info__town
        display: flex
        .profile-info__town-text
          width: 150px
      .profile-info__status
        display: flex
        .profile-info__status-text
          width: 150px
    .wall
      .create-post
        padding: 20px
        display: flex
        justify-content: space-between
        flex-wrap: wrap
        border-bottom: 1px solid rgba(silver, .3)
        margin-bottom: 30px
        .post-form
          display: flex
          .post-form__content
            display: flex
            flex-direction: column
          .post-form__img
            height: 50px
            width: 50px
            border-radius: 50%
            margin-right: 30px
          .post-form__textarea
            border: 1px solid #ccc
            box-shadow: inset 0 1px 1px rgba(0,0,0,.075)
            border-radius: 4px
            padding: 6px 0 4px 10px
            font-size: 14px
            color: #5A5A5A
            height: auto
            resize: none
            &:focus
              outline: none !important
              border: 1px solid deepskyblue
              box-shadow: 0 0 10px #719ECE
          .post-form__btn
            background-color: deepskyblue
            top: 0
            border-radius: 30px
            color: aliceblue
            padding: 7px 20px
            border: none
            cursor: pointer
            transition: all .5s ease-in
            margin-top: 20px
            &:hover
              opacity: .68
              text-decoration: none
        .tools
          .publishing-tools
            display: flex
            justify-content: space-evenly
            flex-wrap: wrap
            .publishing-tools__item
              padding: 0 5px
            .publishing-tools__link
              color: #6d6e71
              font-size: 18px
              &:hover
                transition: all .3s ease-in
                color: deepskyblue
                font-size: 19px
      .one-post
        background: #f8f8f8
        border-radius: 4px
        width: 100%
        border: 1px solid #f1f2f2
        margin-bottom: 20px
        overflow: hidden
        position: relative
        padding: 20px
        &__delete
          position: absolute
          color: orangered
          left: 10px
          top: 0
          cursor: pointer
    .post-container
      .post-header
        display: flex
        justify-content: space-between
        padding-bottom: 10px
        .post-header__wrap
          display: flex
        .post-header__image
          height: 50px
          width: 50px
          border-radius: 50%
        .post-header__info
          margin-left: 15px
          .post-author__name
            color: #27aae1
            font-size: 18px
          .post-author__recording
            color: #8dc63f
            font-size: 12px
            margin-left: 20px
          .post-date
            .post_date__text
              color: #939598
              font-size: 14px
        .reaction
          .reaction__link
            padding: 10px
          .reaction__link-like
            cursor: pointer
            .mdi
              transition: all .2s ease
              color: #8dc63f
              &:hover
                color: #39b54a
          .reaction__link-dislike
            cursor: pointer
            .mdi
              transition: all .2s ease
              color: orangered
              &:hover
                color: #d6100b
      .post-detail
        margin-left: 55px
        border-top: 1px solid rgba(#5a5a5a, .1)
        padding-top: 20px
        .post-detail__text
          color: #6d6e71
          font-size: 13px
          padding-bottom: 20px
          border-bottom: 1px solid rgba(#5a5a5a, .1)
        .post-comment
          display: flex
          margin-top: 20px
          border-bottom: 1px solid rgba(#5a5a5a, .1)
          .post-comment__wrap
            margin-left: 20px
            .post-comment__name
              color: #149AC9
            .post-comment__date
              font-size: 13px
            .post-comment__text
              font-size: 13px
              margin-top: 20px
          .post-comment__image
            width: 50px
            height: 50px
            border-radius: 50%
        .comment-form__show
          margin-top: 10px
          font-size: 14px
          cursor: pointer
        .comment-form__wrap
          margin-top: 25px
          display: flex
          .comment-form
            position: relative
            .comment-form__text
              padding: 5px 10px
              margin: 5px 15px
              height: 38px
              border: 1px solid #ccc
              box-shadow: inset 0 1px 1px rgba(0,0,0,.075)
              font-size: 14px
              border-radius: 4px
              color: #5A5A5A
              min-width: 290px
              max-width: 350px
              &:focus
                outline: none
                border: none
                border: 1px solid deepskyblue
            .comment-form__btn
              position: absolute
              top: 8px
              background: transparent
              outline: none
              border: none
              cursor: pointer
              font-size: 31px
              color: #939598
              .mdi
                margin-top: 10px
          .comment-form__img
            width: 50px
            height: 50px
            border-radius: 50%
    .sidebar
      width: 250px
      .profile-card
        background: linear-gradient(to bottom, rgba(39,170,225,.8), rgba(28,117,188,.8)), url(../assets/images/bg-1.jpg) no-repeat
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
          height: 90px
          width: 90px
          border-radius: 50%
        .profile-card__link
          color: aliceblue
        .profile-card__followers
          color: aliceblue
      .sidebar-menu
        padding-left: 20px
        padding-right: 20px
        margin: 0 0 40px 0
        .sidebar-menu__items
          .sidebar-menu__item
            padding: 10px 0
            border-bottom: 1px solid rgba(#6d6e71, .1)
            .mdi
              font-size: 18px
            .sidebar-menu__link
              margin-left: 20px
              color: #6d6e71
              transition: all .5s ease-in-out
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
      .sidebar-online
        margin-top: 40px
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
            .sidebar-online__dote
              background: linear-gradient(to bottom, rgba(141,198,63, 1), rgba(0,148,68, 1))
              border: none
              height: 12px
              width: 12px
              border-radius: 50%
              position: absolute
              bottom: -6px
              border: 2px solid #fff
              left: 0
              right: 0
              margin: auto
</style>