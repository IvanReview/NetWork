<template>
  <div class="wrapper">
    <div class="container">
      <div class="login__inner">

        <div class="intro">
          <h2 class="intro__title">Make Cool Friends !!!</h2>
          <p class="intro__text">
            Friend Finder is a social network template that can be used to connect people.
            The template offers Landing pages, News Feed, Image/Video Feed, Chat Box, Timeline and lot more.
          </p>
          <p class="intro__text">
            Why are you waiting for? Buy it now.
          </p>
          <button class="intro__btn">learn more</button>
        </div>

          <div class="reg-options">
            <ul class="reg-option__nav">
              <li class="reg-option__nav-item">
                <router-link :class="{active: ActiveClass}" to="/login">Войти</router-link>
              </li>
              <li class="reg-option__nav-item">
                <router-link :class="{active: 0}" to="/register">Регистрация</router-link>
              </li>
            </ul>
          </div>

          <div class="login-form">
            <h4 class="login-form__title">Авторизация</h4>
            <!--<p class="login-form__subtitle">Войти в свой аккаунт</p>-->
            <form class="login-form__form" @submit.prevent="login">

              <!--Эмаил-->
              <div class="login-form__item login-form__item-email">
                <label for="email">Email</label>
                <input id="email" class="login-form__input"
                       type="email"
                       name="email"
                       placeholder="Введите email"
                       v-model="form.email"
                       :class="{invalid: ($v.form.email.$dirty && !$v.form.email.required)
                            || ($v.form.email.$dirty && !$v.form.email.email)}"
                >
                <span class="mdi mdi-account icon-email"
                      :class="{invalid_icon: ($v.form.email.$dirty && !$v.form.email.required)
                          || ($v.form.email.$dirty && !$v.form.email.email)}"
                ></span>
              </div>
              <div class="error" v-if="$v.form.email.$dirty && !$v.form.email.required">Поле обязательно для заполнения</div>
              <div class="error" v-else-if="$v.form.email.$dirty && !$v.form.email.email">
                Поле должно быть валидным емейл адресом
              </div>
              <div class="error" v-if="errors.email"> {{errors.email[0]}} </div>

              <!--//Пароль-->
              <div class="login-form__item login-form__item-password">
                <label for = "password">Password</label>
                <input id="password" class="login-form__input"
                       type="password"
                       name="password"
                       placeholder="Введите пароль"
                       v-model="form.password"
                       :class="{invalid: ($v.form.password.$dirty && !$v.form.password.required)
                              || ($v.form.password.$dirty && !$v.form.password.minLength)}"
                >
                <span class="mdi mdi-lock icon-lock"
                      :class="{invalid_icon: ($v.form.password.$dirty && !$v.form.password.required)
                              || ($v.form.password.$dirty && !$v.form.password.minLength)}"
                ></span>
              </div>
              <div class="error" v-if="$v.form.password.$dirty && !$v.form.password.required">Поле обязательно для заполнения</div>
              <div class="error" v-else-if="$v.form.password.$dirty && !$v.form.password.minLength">
                Мин. длинна поля {{ $v.form.password.$params.minLength.min }} символов(а)
              </div>
              <div class="error" v-if="errors.password">{{errors.password[0]}}</div>

              <p class="login-form__forgot">
                <router-link class="login-form__forgot-link" to="/">
                  Забыли пароль?
                </router-link>
              </p>

              <button type="submit" class="login-form__btn">Войти</button>
            </form>

            <div class="alter-login">
              <p class="alter-login__title">Or Sign Up Using</p>
              <div class="alter-login__icons">
                <a href="https://ru-ru.facebook.com" class="alter-login__item bg1">
                  <span class="mdi mdi-facebook "></span>
                </a>

                <a href="https://twitter.com" class="alter-login__item bg2">
                  <span class="mdi mdi-twitter "></span>
                </a>

                <a href="https://google.com" class="alter-login__item bg3">
                  <span class="mdi mdi-google"></span>
                </a>
              </div>
            </div>

          </div>
        </div>

    </div>
  </div>
</template>

<script>
import {email, minLength, required, sameAs} from "vuelidate/lib/validators";
import {mapActions} from "vuex";

export default {
  name: "Login",
  data: () => ({
    ActiveClass: false,
    form: {
      email: '',
      password: '',
    },
    errors: {
      email: '',
      password: '',
    }

  }),
  validations: {
    //валидаторы из библиотеки vuelidate
    form: {
      email: {required, email},
      password: {required, minLength: minLength(6)},
    }

  },
  methods: {
    ...mapActions([
        'receiveCurrentUser'
    ]),

    login(){
      if (this.$v.$invalid) {
        this.$v.$touch()//активизировать валид
        return
      }

      let formData = new FormData
      formData.append('email', this.form.email)
      formData.append('password', this.form.password)

      this.axios({
          method: 'POST',
          url: '/login',
          data: formData,
      })
          .then(response => {

            if (response.status === 200) {
              window.localStorage.setItem('user', JSON.stringify(response.data.user))
              window.localStorage.setItem('token', response.data.token)

              this.$store.commit('set_current_user', response.data.user)
              this.form = {}
              this.$router.push('/feed')
            }
          })
          .catch(errors => {

            switch (errors.response && errors.response.status) {
              case 422:
                this.errors = errors.response.data.errors;
                //неверное сочетание логин пароль

                break;
              case 500:
                this.text = errors.response.data.message
                break;
              default:

                break;
            }
          }).finally(() => this.isLoading = false)
    }
  },
  mounted() {
    if (this.$route.name === 'Login'){
        this.ActiveClass = true
    }

    /*this.receiveCurrentUser(2)*/

  }
}
</script>

<style scoped lang="sass">
  .active
    font-weight: 700 !important
    color: teal !important
  .wrapper
    position: relative
    background: linear-gradient(to right, rgba(0,0,0, 0.7) , rgba(0,0,0, 0.7)), url(../../assets/images/bg-3.jpg) fixed no-repeat
    background-size: cover
    width: 100%
    min-height: 100vh
    color: #333
    overflow-y: hidden
  .login__inner
    font-family: inherit
    padding: 10px 0
    height: 100vh
    display: flex
    justify-content: space-between
    align-items: center
    .reg-options
      position: relative
      z-index: 9999
      &:before
        content: ""
        background: linear-gradient(to right, rgba(255,255,255, 0.2) , rgba(255,255,255, 0.05))
        height: 400px
        width: 60px
        position: absolute
        left: 60px
        top: 50%
        transform: translateX(-100%) translateY(-50%)
        border-radius: 6px 0 0 6px
        z-index: -1
      .reg-option__nav
        position: absolute
        display: flex
        left: 60px
        top: 50%
        transform: translateY(-50%) translateX(-60%) rotate(270deg)
        border: none
        .reg-option__nav-item
          float: left
          margin-bottom: 10px
          margin-left: 20px
          margin-right: 10px
          position: relative
          z-index: 9999
          a
            color: aliceblue
    .intro
      margin-top: 100px
      max-width: 550px
      padding: 10px 30px
      color: aliceblue
      .intro__title
        margin-bottom: 40px
      .intro__text
        margin-bottom: 10px
      .intro__btn
        font-weight: 600
        outline: none
        border: none
        border-radius: 30px
        background-color: darkslateblue
        color: aliceblue
        padding: 8px 30px
        cursor: pointer
        transition: all .4s ease-in
        margin-top: 20px
        &:hover
          background-color: teal
    .login-form
      max-width: 360px
      min-height: 620px
      min-width: 350px
      max-height: 680px
      background: #fff
      box-shadow: 0 0 35px rgba(0,0,0, .4)
      padding: 40px 40px 40px 40px
      border-radius: 6px
      position: relative
      z-index: 10
      margin-left: 60px
      .login-form__title
        text-align: center
        margin: 30px 0 50px
        color: #333
        font-family: 'Amatic SC', cursive
        font-weight: 700
        font-size: 54px
      .login-form__subtitle
        color: #5A5A5A
        margin-bottom: 30px
        font-size: 13px
      .login-form__item
        position: relative
      .login-form__input
        background: #fff
        border: none
        box-shadow: none
        width: 100%
        border-bottom: 2px solid rgba(#363838, .4)
        margin-bottom: 10px
        color: #333
        line-height: 1.2
        height: 55px
        background: 0 0
        padding: 0 7px 0 33px
        transition: all .3s ease-in-out
        &:focus
          outline: none
          border: none
          color: #000
          border-bottom: 3px solid rgba(#363838, .9)
      .icon-email
        position: absolute
        left: 0
        bottom: 25px
        font-size: 25px
        color: #adadad
      .icon-lock
        position: absolute
        left: 0
        bottom: 25px
        font-size: 25px
        color: #adadad
      .login-form__forgot
        margin-bottom: 25px
        font-size: 14px
        display: flex
        justify-content: flex-end
        .login-form__forgot-link
          color: #666
      .login-form__btn
        color: #fff
        border-radius: 30px
        line-height: 1.2
        text-transform: uppercase
        display: flex
        justify-content: center
        align-items: center
        padding: 0 20px
        width: 100%
        height: 50px
        outline: none !important
        border: none
        cursor: pointer
        background: #a64bf4
        background: linear-gradient(45deg, #00dbde,#00dbde, #fc00ff,#fc00ff)
        &:hover
          background: -webkit-linear-gradient(left,#fc00ff,#fc00ff,#00dbde,#00dbde)
    .alter-login
      margin-top: 30px
      text-align: center
    .alter-login__icons
      display: flex
      justify-content: center
      margin-top: 20px
      font-size: 40px
      .alter-login__item + .alter-login__item
        margin-left: 20px
        color: deepskyblue
      .alter-login__item:last-child
        color: orangered
      .alter-login__item:first-child
        color: blue
  input:focus + .mdi
    transition: all .5s
    color: #000 !important
</style>