<template>
  <div class="dialogs">
    <div class="container">

      <div class="dialogs__inner">

        <LeftSecondBar />

        <main class="dialogs-main">
            <div class="dialog-users">
              <h1 class="dialog-title" style="margin: 0 0 10px 10px ">Чат</h1>

              <div class="dialogs__wrapper ">
                <ul class="dialogs__list">
                  <p class="no-dialogs" v-if="!getDialogs.length">У вас нет диалогов</p>

                  <li v-else class="dialogs__list-item"
                      v-for="(dialog, index) in getDialogs"
                      :class="{dialogs__list_item_active: activeDialog === index + 1}"
                      @click.prevent="showForm"
                  >
                    <a class="dialogs__list-link"
                       href="/"
                       @click.prevent="getDialogMessages(dialog.dialog_id, index)"
                    >
                      <div class="dialogs__list-contact">
                        <div class="dialogs__img-wrap">
                          <img class="dialogs__list-img"
                               :src="dialog.image ? $store.state.serverUrl + dialog.image
                                : require(`../assets/images/no_image.jpg`)" alt=""
                          >
                        </div>
                        <div class="dialogs__list-info">
                          <h6 class="dialogs__list-name">{{dialog.name}} {{dialog.lastname}}</h6>
                          <p class="dialogs__list-message" v-if="dialog.last_message.text">
                              {{dialog.last_message.text}}
                          </p>
                          <small class="dialogs__list-time" v-if="dialog.last_message.created_at">
                             {{ dialog.last_message.created_at.slice(0,-3)}}
                          </small>
                          <div v-if="dialog.unread" class="dialogs__list-alert">
                              {{dialog.unread}}
                          </div>
                        </div>
                      </div>
                    </a>
                  </li>

              </ul>
              </div>

            </div>

            <div class="dialog-message">

              <h1>Сообщения</h1>

                <div class="messages" >
                  <div class="tab-messages active">
                    <div class="chat">
                      <ul class="chat__list">
                        <p class="chat__no-message" v-if="!getMessages.length">В этом диалогке сообщения отсутствуют!</p>

                        <li class="chat__message" v-else
                            v-for="message in getMessages"
                            @click="deleteMessage(message)"
                        >
                          <img class="chat__message-image"
                               :src="message.image ? $store.state.serverUrl + message.image
                                      : require(`../assets/images/no_image.jpg`)" alt="">
                          <div class="chat__message-info"
                               :class = "[message.author_id == current_user_id ? 'chat__message_left' : 'chat__message_right']"
                          >
                            <div class="chat__message-header">
                              <h5 class="chat__message-name">{{message.name}} {{message.lastname}}</h5>
                              <small class="chat__message-date">{{message.created_at.slice(0,-3)}}</small>
                            </div>
                            <p class="chat__message-text">
                              {{message.text}}
                            </p>
                          </div>
                        </li>

                      </ul>
                    </div>
                  </div>
              </div>

              <!--//Форма отправки сообщения-->
              <div  class="send-message">
                <form class="send-form" @submit.prevent = "addMessage">
                  <input type="text" class="send-form__input" placeholder="Введите сообщение" v-model="message">
                  <span class="send-form__btn-wrap">
                        <button class="send-form__btn" type="submit" >
                            Send <span class="mdi mdi-send"></span>
                        </button>
                  </span>
                </form>
              </div>
            </div>
        </main>

      </div>

    </div>
  </div>

</template>

<script>
import LeftBar from "./common/LeftBar";
import RightBar from "./common/RightBar";
import {mapActions, mapGetters} from "vuex";
import LeftSecondBar from "./common/LeftSecondBar";
export default {
  name: "Dialogs",
  components: {LeftSecondBar, RightBar, LeftBar},
  data: () => ({
    dialog_id: '',
    message: '',
    activeDialog: 0,
    show_form: false,
    current_user_id: '',
    connection: false,

    chat_message: {}

  }),
  computed: {
      ...mapGetters([
          'getDialogs',
          'getMessages',
          'getLoginUserId'
      ])
  },
  watch: {
    chat_message(message) {


    }
  },
  methods: {
    ...mapActions([
        'fetchDialogs',
        'fetchMessagesForDialog',
        'addMessageToDialog',
        'delMessageFromDialog'
    ]),

    showForm() {
        this.show_form = true
    },

    deleteMessage(message) {
      if(message.user_id == this.current_user_id && confirm('Удалить сообщение??') ) {

        this.delMessageFromDialog(message.message_id)
      }
    },
    //Получить сообщения диалога

    getDialogMessages(dialog_id, index) {
      this.dialog_id = dialog_id
      this.activeDialog = index + 1

      this.fetchMessagesForDialog(dialog_id)
    },

    addMessage() {
      /*if (!this.message) return*/
      let user_id = this.current_user_id
      let formData = new FormData()
      formData.append('user_id', user_id)
      formData.append('message', this.message)
      formData.append('dialog_id', this.dialog_id)

      this.addMessageToDialog(formData).then(resp => {
        if (resp === 200) {
          this.message = ''
        }
      })
    },

    //Добавить сообщение через веб сокет
    addMessageSocket(connection) {
      const btn = document.querySelector('.send-form__btn')

      btn.addEventListener('click', (e) => {
        e.preventDefault()
        const data = {
          user_id: this.current_user_id,
          message: this.message,
          dialog_id: this.dialog_id,
        }
        connection.send(JSON.stringify(data));
      })

    }
  },
  created() {
    this.current_user_id = this.getLoginUserId
  },

  mounted() {
    this.fetchDialogs(this.current_user_id)

    const conn = new WebSocket(this.$store.state.socketUrl);

    conn.onopen = (e) => {

      console.log("Connection established!");

      this.addMessageSocket(conn)
    }

    conn.onmessage = (e) => {
      let data = JSON.parse(e.data);

      this.$store.commit('add_dialog_message', data)

      this.message = ''
    }

    conn.onclose = function(e) {
      console.log("Connection Closed!");
    }



  }
}
</script>

<style scoped lang="sass">

  .dialogs
  .dialogs__inner
    display: grid
    grid-template-columns: 250px 3fr
    .dialogs-main
      display: flex
      justify-content: space-between
      width: 100%
      margin-left: 20px
      padding: 15px
      background-color: #f8f8f8
      .dialogs__wrapper
        padding: 0 15px
        background-color: #f8f8f8
      .dialogs__list
        display: flex
        flex-direction: column
      .dialogs__list-item
        width: 280px
        padding: 7px 5px
        transition: all .4s ease-in
        border-bottom: 1px solid rgba(silver, .3)
        &:hover
          border: none
          border-bottom-color: currentcolor
          border-bottom-style: none
          border-bottom-width: medium
          border-bottom: 1px solid #8dc63f !important
          border-radius: 0
          cursor: pointer
      .dialogs__list_item_active
        border: none
        border-bottom-color: currentcolor
        border-bottom-style: none
        border-bottom-width: medium
        border-bottom: 1px solid #8dc63f !important
        border-radius: 0
        cursor: pointer
        background: rgba(39, 170, 225, 0.1)
      .dialogs__list-contact
        display: flex
        width: 100%
        justify-content: flex-start
        .dialogs__img-wrap
          width: 50px
          height: 50px
          overflow: hidden
        .dialogs__list-img
          height: 40px
          width: 40px
          border-radius: 50%
          display: block
        .dialogs__list-info
          margin-left: 10px
          position: relative
          width: 100%
        .dialogs__list-name
          font-size: 13px
          color: #27aae1
        .dialogs__list-message
          color: #939598
          font-size: 13px
          margin-bottom: 10px
        .dialogs__list-time
          position: absolute
          top: 0px
          right: 0px
          color: #939598
          font-size: 12px
        .dialogs__list-alert
          background: red
          text-align: center
          border-radius: 4px
          padding: 1px 6px
          position: absolute
          right: 0px
          bottom: 15px
          color: #fff
          font-size: 12px
          line-height: 16px
    .dialog-message
      .messages
        height: 530px
        overflow-y: auto
        scroll-snap-type: y
        scrollbar-color: #719ECE  #eeeeee    /* «цвет ползунка» «цвет полосы скроллбара» */
        scrollbar-width: thin !important
      .chat
        padding: 0 20px
      .chat__list
        margin-top: 10px
        .chat__no-message
          margin-top: 40px
        .chat__message
          display: flex
          padding: 10px
          flex-grow: 1
          margin-bottom: 10px
          cursor: pointer
        .chat__message-image
          height: 45px
          width: 45px
          border-radius: 50%
          display: block
        .chat__message-info
          margin-left: 0px
          position: relative
          width: 100%
        .chat__message-header
          border-bottom: 1px solid rgba(silver, .2)
          margin-bottom: 10px
        .chat__message-name
          font-size: 14px
        .chat__message-date
          font-size: 12px
          position: absolute
          right: 5px
          top: 0
        .chat__message-text
          font-size: 14px
        .chat__message_left
          background: rgba(141,198,63, .1)
          margin-left: 20px !important
          padding: 5px 10px
          position: relative
          border-radius: 10px
          &::after
            border-bottom: 10px solid transparent
            border-right: 8px solid rgba(141,198,63, .1)
            border-top: 10px solid transparent
            content: ""
            height: 0
            left: -8px
            position: absolute
            top: 10px
            width: 0
        .chat__message_right
          background: rgba(39,170,225, .1)
          margin-right: 20px
          padding: 5px 10px
          position: relative
          border-radius: 10px
          order: -1
          &:after
            border-bottom: 10px solid transparent
            border-left: 8px solid rgba(39,170,225, .1)
            border-top: 10px solid transparent
            content: ""
            height: 0
            right: -8px
            position: absolute
            top: 10px
            width: 0

  .users__inner
    display: flex
    .users
      display: flex
      flex-direction: column
      .user
        display: flex

  .send-message
    background: #f8f8f8
    padding: 20px
    border-radius: 4px
    .send-form
      .send-form__input
        border: 1px solid rgba(silver, .7)
        width: 230px
        padding: 4px 10px
        height: 38px
        &:focus
          outline: none !important
          border: 2px solid deepskyblue
          box-shadow: 0 0 10px #719ECE
      .send-form__btn
        background-color: deepskyblue
        border: 1px deepskyblue solid
        padding: 3px 15px 5px
        color: aliceblue
        cursor: pointer
        height: 38px
        transition: al .5s ease-in
        &:hover
          transition: al .5s ease-in
          opacity: .8
        .mdi
          color: aliceblue
          font-size: 18px

  /* полоса прокрутки (скроллбар) */
  ::-webkit-scrollbar
    width: 14px /* ширина для вертикального скролла */
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

  ::-webkit-scrollbar-button:vertical:start:decrement
    background: linear-gradient(120deg, #719ECE 40%, rgba(0, 0, 0, 0) 41%), linear-gradient(240deg, #719ECE 40%, rgba(0, 0, 0, 0) 41%), linear-gradient(0deg, #719ECE 30%, rgba(0, 0, 0, 0) 31%)
    background-color: #f6f8f4


  ::-webkit-scrollbar-button:vertical:end:increment
    background: linear-gradient(300deg, #719ECE 40%, rgba(0, 0, 0, 0) 41%), linear-gradient(60deg, #719ECE 40%, rgba(0, 0, 0, 0) 41%), linear-gradient(180deg, #719ECE 30%, rgba(0, 0, 0, 0) 31%)
    background-color: #f6f8f4


  ::-webkit-scrollbar-button:horizontal:start:decrement
    background: linear-gradient(30deg, #02141a 40%, rgba(0, 0, 0, 0) 41%), linear-gradient(150deg, #02141a 40%, rgba(0, 0, 0, 0) 41%),linear-gradient(270deg, #02141a 30%, rgba(0, 0, 0, 0) 31%)
    background-color: #f6f8f4


  ::-webkit-scrollbar-button:horizontal:end:increment
    background: linear-gradient(210deg, #02141a 40%, rgba(0, 0, 0, 0) 41%), linear-gradient(330deg, #02141a 40%, rgba(0, 0, 0, 0) 41%), linear-gradient(90deg, #02141a 30%, rgba(0, 0, 0, 0) 31%)
    background-color: #f6f8f4


</style>