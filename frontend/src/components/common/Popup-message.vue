<template>
  <div class="popup__wrapper" ref="popup__wrapper" style="z-index: 9999">
    <!--<span class="popup__close mdi mdi-close" @click="closePopup"></span>-->
    <div class="popup">

        <div class="popup__header">
          <p class="popup__header-new">Новое сообщение</p>
          <span class="popup__close mdi mdi-close" @click="closePopup"></span>
        </div>

        <main class="main">

          <div class="popup-content">
            <div class="popup-content__user">
              <img v-if="user.image"
                   class="popup-content__img"
                   :src="user.image ? $store.state.serverUrl + user.image : require(`../../assets/images/no_image.jpg`)">
              <div class="popup-content__info">
                <h4 class="popup-content__name">
                  {{user.name}} {{user.lastname}}
                </h4>
                <p class="popup-content__status">online</p>
              </div>
            </div>

            <div class="popup-content__message">
              <form class="popup-content__form" @submit.prevent="sendMessage">
                <textarea class="popup-content__text" v-model="message" placeholder="Введите сообщение"></textarea>
                <button class="popup-content__btn">Отправить</button>
              </form>

            </div>
          </div>

        </main>

    </div>
  </div>
</template>

<script>
export default {
  name: "Popup-message",
  data: () => ({
    message: ''

  }),
  props: {
    user: {
      type: Object,
      default: {}
    },
  },
  methods: {
    closePopup() {
      this.$emit('closePopup')
    },

    sendMessage() {
      this.$emit('sendMessage', this.message)
    }
  },
  mounted() {
    document.addEventListener('click', (item) => {

      if (item.target === this.$refs.popup__wrapper) {
        this.closePopup()
      }
    })
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
  top: 80px
  left: 0
  right: 0
  width: 590px
  height: 470px
  bottom: 80px
  background: #fff
  box-shadow: 0 0 17px #5A5A5A
  z-index: 999
  transition: all .5s ease-in
  &__close
    top: 20px
    position: absolute
    font-size: 30px
    right: 20px
    cursor: pointer
    color: aliceblue
  &__header
    background: #3A5FCD
    padding: 30px
  &__header-new
    color: #fff
  &-content__user
    display: flex
    padding: 20px 30px
  &-content__img
    width: 50px
    height: 50px
    border-radius: 50%
  &-content__info
    margin-left: 40px
  &-content__name
  &-content__status
  &-content__message
    padding: 20px 30px
  &-content__message
  &-content__form
    text-align: right
  &-content__text
    width: 100%
    height: 150px
    border: 1px silver solid
    resize: none
    padding: 10px
    &:focus
      border: 1px deepskyblue solid
      outline: 1px deepskyblue solid
      box-shadow: 10px 10px 10px silver
  &-content__btn
    background-color: deepskyblue
    border: deepskyblue
    padding: 10px 20px
    color: aliceblue
    cursor: pointer
    margin-top: 20px
    transition: all .5s ease-in
    &:hover
      opacity: .8
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
      padding: 15px
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
      overflow-y: auto
      scroll-snap-type: y
      scrollbar-color: #719ECE  #eeeeee    /* «цвет ползунка» «цвет полосы скроллбара» */
      scrollbar-width: thin !important
    &__comments
      font-size: 13px
      height:  calc(150px*2)
      overflow-y: auto
      scroll-snap-type: y
      scrollbar-color: #719ECE  #eeeeee    /* «цвет ползунка» «цвет полосы скроллбара» */
      scrollbar-width: thin !important
    .one-comment
      margin: 10px 0
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