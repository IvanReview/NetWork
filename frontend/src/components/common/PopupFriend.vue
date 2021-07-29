<template>
  <div class="popup__wrapper" ref="popup__wrapper" style="z-index: 9999">
    <span class="popup__close mdi mdi-close" @click="closePopup"></span>
    <div class="popup">
      <div class="popup__inner">
        <aside class="side">
          <div class="side__header">
            <img class="side__header-img" :src="$store.state.serverUrl + user.image">
            <div class="side__header-info">
              <div class="side__header-title">{{user.name}} {{user.lastname}}</div>
              <div  class="side__header-date">Дата {{image_data.created_at}}</div>
            </div>
          </div>

          <div class="side__main">

            <div class="side__reaction">
              <a class="side__reaction-like side__reaction-link" >
                <span class="mdi mdi-thumb-up"></span> {{66}}
              </a>
              <a class="side__reaction-dislike side__reaction-link" >
                <span class="mdi mdi-thumb-down"></span> {{99}}
              </a>
            </div>

            <div class="side__description">
              <p v-if="show_edit_text" class="description__text">
                {{image_data.description}}
              </p>

            </div>

            <div class="side__comments">
              <div class="one-comment"  v-for="comment in getComments">
                  <img :src="comment.image ? $store.state.serverUrl + comment.image : require(`../../assets/images/no_image.jpg`)"
                       alt="" class="one-comment__img">

                  <div class="one-comment__info">
                    <h4 class="one-comment__title">
                      <router-link to="/user/2" class="profile-link">
                        {{comment.name}} {{comment.lastname}}
                      </router-link></h4>
                    <div class="one-comment__text">
                      {{comment.text}}
                    </div>
                  </div>
              </div>
            </div>
          </div>


          <!--форма для комментариев-->
          <div class="popup-form__wrap">
            <form class="popup-form" @submit.prevent = "addComment">
              <input class="popup-form__input" v-model="comment" type="text" placeholder="Добавить комментарий">
              <button class="popup-form__btn" ><span class="mdi mdi-send"></span></button>
            </form>
          </div>
        </aside>

        <main class="main">
          <!--Главное изображение-->

          <div class="popup__content">
            <img class="popup__image" :src="$store.state.serverUrl + image.name">
          </div>

          <div class="popup__footer">

          </div>
        </main>
      </div>
    </div>
  </div>
</template>

<script>
import {mapActions, mapGetters} from "vuex";

export default {
  name: "Popup",
  data: function () {
    return {
      comment: '',
      image_desc: '',
      show_edit_text: true,
      show_edit_form: false,
      image_data: this.image
    }
  },
  props: {
    image: {
      type: Object,
      default: ''
    },
    user: {
      type: Object,
      default: ''
    },
  },
  computed: {
    ...mapGetters([
        'getComments'
    ])
  },
  methods: {
    ...mapActions([
        'deleteImage',
        'editImageDesc',
        'fetchCommentsForImage',
        'addCommentToImage'
    ]),

    addComment() {
      this.$emit('addComment', this.image.id, this.comment)
      this.comment = ''
    },


    closePopup() {
      this.$emit('closePopup')
    },


  },
  created() {

  },
  mounted() {
    /*let textarea = document.querySelector('.description__textarea');
    textarea.addEventListener('keydown', autosize);
    function autosize(){
      let el = this;
      setTimeout(function(){
        el.style.cssText = 'height:auto; padding:0';
        el.style.cssText = 'height:' + el.scrollHeight + 'px';
      },0);
    }*/

    document.addEventListener('click', (item) => {

      if (item.target === this.$refs.popup__wrapper) {
        this.closePopup()
      }
    })

    this.fetchCommentsForImage(this.image.id)
  }
}
</script>

<style scoped lang="sass">
  .popup__wrapper
    background: rgba(64,64,64, .8)
    justify-content: center
    align-items: center
    position: fixed
    right: 0
    left: 0
    top: 0
    bottom: 0
  .popup
    padding: 0px
    position: fixed
    margin: 0 auto
    top: 30px
    left: 0
    right: 0
    width: 1090px
    bottom: 30px
    background: #fff
    box-shadow: 0 0 17px #5A5A5A
    z-index: 999
    transition: all .5s ease-in
    &__close
      top: 10px
      position: absolute
      font-size: 40px
      right: 10px
      cursor: pointer
      color: aliceblue
    &__inner
      display: flex
      height: 100%
    .side
      order: 2
      width: 320px
      display: flex
      flex-direction: column
      height: 100%
      position: relative
      &__header
        display: flex
        padding: 15px
        border-bottom: 1px solid rgba(64,64,64, .2)
      &__header-img
        width: 45px
        height: 45px
        border-radius: 50%
      &__header-info
        padding-left: 15px
      &__header-title
        color: #149AC9
        font-size: 17px
        font-weight: 800
      &__header-date
        font-size: 13px
      .popup-form__wrap
        padding: 14px
        border-top: 1px solid rgba(64,64,64, .2)
        display: flex
        flex: 0 0 auto
      &__main
        flex: 1 0 auto
        padding: 0 15px
      &__reaction
        padding: 10px 0
      &__reaction-link
        padding: 15px
        cursor: pointer
      &__reaction-like
        color: teal
      &__reaction-dislike
        color: orangered
      &__description
        padding: 10px 0
        font-size: 13px
        margin-bottom: 20px
        line-height: 18px
        overflow-y: auto
        scroll-snap-type: y
        scrollbar-color: #719ECE  #eeeeee    /* «цвет ползунка» «цвет полосы скроллбара» */
        scrollbar-width: thin !important
      .description__edit
        margin: 10px 0
        cursor: pointer
        color: orangered
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
        position: relative
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
      &__comments
        font-size: 13px
        line-height: 18px
        height:  calc(150px*2)
        overflow-y: auto
        scroll-snap-type: y
        scrollbar-color: #719ECE  #eeeeee    /* «цвет ползунка» «цвет полосы скроллбара» */
        scrollbar-width: thin !important
      .one-comment
        margin: 10px 0
        padding: 5px 0
        display: grid
        grid-template-columns: 40px 1fr
        grid-gap: 5px
        border-bottom: 1px solid rgba(64,64,64, .1)
      .one-comment__img
        width: 35px
        height: 35px
        border-radius: 50%
      .one-comment__info
        padding-left: 15px
      .popup-form__wrap
        position: relative
      .popup-form
      .popup-form__input
        border: none
        font-size: 13px
        padding: 5px 10px
        width: 120%
        &:focus
          outline: none !important
          border: 1px solid #939598
          box-shadow: 0 0 10px #719ECE
          border-radius: 5px
      .popup-form__btn
        position: absolute
        top: 18px
        right: 19px
        background: transparent
        border: none
        font-size: 32px
        color: #939598
        cursor: pointer
    .main
      position: relative
      padding-top: 0
    &__header, &__footer
      display: flex
      justify-content: space-between
      align-items: center
    &__content
      display: flex
      justify-content: center
      align-items: center
    &__footer
      background-color: rgba(34, 34, 34, 0.88)
      position: relative
      display: block
      z-index: 999
      width: 100%
      height: 5%
    &__content
      position: relative
      width: 100%
      height: 95.2%
      background-color: rgba(34, 34, 34, 0.9)
    &__image
      position: absolute
      height: auto
      width: auto
      vertical-align: top
      max-height: 100%
      max-width: 100%
  .popup__footer
    &-delete
      background-color: transparent
      border: none
      color: darkorange
      cursor: pointer

  /* полоса прокрутки (скроллбар) */
  ::-webkit-scrollbar
    width: 9px /* ширина для вертикального скролла */
    height: 8px /* высота для горизонтального скролла */
    background-color: #eeeeee


  /* ползунок скроллбара */
  ::-webkit-scrollbar-thumb
    background-color: #719ECE
    border-radius: 9em
    box-shadow: inset 1px 1px 10px #f3faf7


  ::-webkit-scrollbar-thumb:hover
    background-color: silver


  /* Стрелки */



</style>