<template>
    <div class="home">
      <main class="home__main">

        <LeftSecondBar :user = getCurrentUser />

        <div class="content">
          <div class="profile-info">
              <div class="profile-info__birth ">
                <div class="profile-info__birth-text">День рождения:</div>
                <div class="profile-info__birth-date">3 февраля {{getCurrentUser.date_birth}}</div>
              </div>
            <div class="profile-info__town ">
              <div class="profile-info__town-text">Город:</div>
              <div class="profile-info__town-name">Ульяновск</div>
            </div>
            <div class="profile-info__status">
              <div class="profile-info__status-text">Статус:</div>
              <div class="profile-info__status-name">Какой то</div>
            </div>
          </div>

          <div class="wall">

            <div class="create-post">
              <div class="post-form">
                <img v-if="getCurrentUser.image"  class="post-form__img"
                     :src="getCurrentUser.image ? $store.state.serverUrl + getCurrentUser.image : require(`../assets/images/no_image.jpg`)"
                     alt=""
                >
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


              <div class="one-post" v-else v-for="(post, index) in getPosts">

                <span v-if="current_user_id === getCurrentUser.id"
                      class="one-post__delete" @click="deletePost(post.id)"> x
                </span>

                <div class="post-header">

                  <div class="post-header__wrap">
                    <a class="post-header__link" href="/id212084634">
                      <img class="post-header__image"
                           :src="post.image ? $store.state.serverUrl + post.image : require(`../assets/images/no_image.jpg`)"
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

                  <div v-if="show_edit_text" class="post-detail__wrap-text" @click="showEditDescForm(post.id, index)">
                    <p class="post-detail__text">
                      {{post.text}}
                    </p>
                  </div>

                  <!--Форма редактирования поста-->
                  <form v-if="show_edit_form && post.id === current_post"
                        class="description__form"
                        @submit.prevent="editPostDescription(post.id)"
                  >
                    <textarea class="description__textarea" v-model="editPostText" placeholder="Описание"></textarea>
                    <button class="description__btn">редактировать</button>
                    <button class="description__cancel" @click="showEditDescForm">отменить</button>
                  </form>

                  <!--Комментариии к посту-->
                  <div class="post-comment" v-for="comment  in post.comments">
                    <span v-if="current_user_id === getCurrentUser.id"
                          class="one-comment__delete" @click="deleteComment(comment.id, post.id)"> x
                    </span>

                    <img class="post-comment__image"
                         :src="comment.image ? $store.state.serverUrl + comment.image : require(`../assets/images/no_image.jpg`)"
                         alt=""
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
                    <span class="mdi mdi-message-processing-outline icon-sidebar"></span>  Форма комментариев
                  </div>

                  <!--Форма комментариев-->
                  <div v-if="show_form && post.id === current_post" class="comment-form__wrap">
                    <img  class="comment-form__img"
                         :src="getCurrentUser.image ? $store.state.serverUrl + getCurrentUser.image
                            : require(`../assets/images/no_image.jpg`) " alt=""
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
import LeftSecondBar from "./common/LeftSecondBar";

export default {
  name: "HomePage",
  components: {LeftSecondBar, Loader, LeftBar},
  data: () => ({
    search: '',
    postText: '',
    comment: '',
    loader: true,
    current_user_id: '',
    editPostText: '',

    show_form: false,
    current_post: '',
    show_edit_form: false,
    show_edit_text: true,

  }),
  methods: {
    ...mapActions([
        'fetchPosts',
        'addPostInBd',
        'addCommentToPost',
        'addLikeToPost',
        'deletePostFromBd',
        'receiveCurrentUser',
        'deleteCommentFromBd',
        'editPostInBd'
    ]),
    showForm(post_id) {
      this.current_post = post_id
      this.show_form = !this.show_form
    },

    //Изменить текст поста
    editPostDescription(post_id) {
      let formData = new FormData()
      formData.append('text', this.editPostText)

      this.editPostInBd({formData, post_id: post_id})
          .then((resp)=>{
            if (resp === 200) this.editPostText = ''
            this.show_edit_text = !this.show_edit_text
            this.show_edit_form = !this.show_edit_form
          })
    },

    //Показать форму редактирования поста
    showEditDescForm(post_id, index) {
      this.show_edit_text = !this.show_edit_text
      this.show_edit_form = !this.show_edit_form
      this.current_post = post_id
      if (this.show_edit_form) {
        let el = document.querySelectorAll('.post-detail__text')
        let text = el[index].innerHTML

        this.editPostText = text
      }
    },

    // Добавить пост
    addNewPost(){
      if (!this.postText) return
      let formData = new FormData()
      formData.append('text', this.postText)

      this.addPostInBd({formData, userId: this.current_user_id, author_id: this.current_user_id})
          .then((resp)=>{
              if (resp === 200) this.postText = ''
          })
    },

    //Удалить пост
    deletePost(post_id){
      let formData = new FormData()
      formData.append('post_id', post_id)

      this.deletePostFromBd(formData)

    },

    //Удалить коммент
    deleteComment(comment_id, post_id) {
      let formData = new FormData()
      formData.append('comment_id', comment_id)
      formData.append('post_id', post_id)

      this.deleteCommentFromBd(formData)
    },

    //Добавить коммент
    addComment(post_id){
      let formData = new FormData()
      formData.append('text', this.comment)
      formData.append('post_id', post_id)

      this.addCommentToPost({formData, user_id: this.current_user_id}).then((resp) => {
        if (resp === 200){
          this.comment = ''
        }
      })
    },

    //Добавить лайк или дизлайк к посту
    addLikeDislikeToPost(status, post_id) {
        let formData = new FormData()
        formData.append('status', status)
        formData.append('post_id', post_id)
        formData.append('user_id', this.current_user_id)

        this.addLikeToPost(formData)
    },


    fetchData(){
      /*this.receiveCurrentUser(this.current_user_id)*/

      this.fetchPosts({offset: 0, userId: this.current_user_id}).then(resp => {
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
        this.fetchPosts({offset: this.getPosts.length, userId: this.current_user_id})
      }
    }
  },
  computed: {
    ...mapGetters([
      'getUserData',
      'getCurrentUser',
      'getPosts',
      'getLoginUserId'
    ]),

    delayHandler() {
      return  _.debounce(this.loadMorePosts, 400)
    }

  },
  created() {
    this.current_user_id = this.getLoginUserId

    //Подгрузка постов при скролинге

    document.addEventListener('scroll', this.delayHandler)

  },
  mounted() {

      this.fetchData()
  },
  destroyed() {

    document.removeEventListener('scroll', this.delayHandler)
    this.$store.commit('post_reset')
  }
}
</script>

<style scoped lang="sass">

  .home
    .home__main
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
          transition: all .8s ease
          &:hover .one-post__delete
              transition: all .8s ease
              opacity: 1
          &__delete
            position: absolute
            color: orangered
            left: 10px
            top: 0
            cursor: pointer
            opacity: 0
      .description__textarea
        border: 1px solid #ccc
        box-shadow: inset 0 1px 1px rgba(0,0,0,.075)
        border-radius: 4px
        padding: 6px 0 4px 10px
        font-size: 14px
        color: #5A5A5A
        height: 90px
        resize: none
        width: 100%
        &:focus
          outline: none !important
          border: 1px solid deepskyblue
          box-shadow: 0 0 10px #719ECE
      .description__btn
        border: none
        cursor: pointer
        background-color: deepskyblue
        color: #fff
        padding: 5px 10px
        border-radius: 3px
      .description__cancel
        border: none
        cursor: pointer
        background-color: orangered
        color: #fff
        padding: 5px 10px
        border-radius: 3px
        margin-left: 5px

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
            position: relative
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
            .one-comment__delete
              position: absolute
              color: deeppink
              right: 10px
              top: 0
              cursor: pointer
              opacity: 1
              z-index: 9999
          .comment-form__show
            font-size: 14px
            margin-top: 10px
            cursor: pointer
            .mdi
              font-size: 18px
              color: dimgray
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


</style>