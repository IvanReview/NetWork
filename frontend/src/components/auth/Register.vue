<template>
  <div class="wrapper">
    <div class="container">
      <div class="register__inner">

        <div class="intro">
          <h2 class="intro__title">Регистрируйся и получай бонусы у надежного букмекера</h2>
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
              <router-link  to="/login">Войти</router-link>
            </li>
            <li class="reg-option__nav-item">
              <router-link :class="{active: ActiveClass}" to="/register">Регистрация</router-link>
            </li>
          </ul>
        </div>

        <div class="register-form">
          <h4 class="register-form__title">Регистрация</h4>
          <!--<p class="register-form__subtitle">Создайте свой аккаунт</p>-->
          <form class="register-form__form" @submit.prevent="register">

            <div class="register-form__item">
              <input class="register-form__input"
                     type="text"
                     name="name"
                     placeholder="Ваше имя"
                     v-model.trim="name"
                     :class="{invalid: ($v.name.$dirty && !$v.name.required) || ($v.name.$dirty && !$v.name.minLength)}"
              >
              <span class="mdi mdi-account icon-lock"
                    :class="{invalid_icon: ($v.name.$dirty && !$v.name.required) || ($v.name.$dirty && !$v.name.minLength)}"
              ></span>
            </div>
            <div class="error" v-if="$v.name.$dirty && !$v.name.required">Поле обязательно для заполнения</div>
            <div class="error" v-else-if="$v.name.$dirty && !$v.name.minLength">
             Мин. длинна поля {{ $v.name.$params.minLength.min }} символов(а)
            </div>
            <div class="error" v-if="errors.name"> {{errors.name}} </div>

            <div class="register-form__item">
              <input class="register-form__input"
                     type="text"
                     name="lastname"
                     placeholder="Ваша фамилия"
                     v-model="lastname"
                     :class="{invalid: ($v.lastname.$dirty && !$v.lastname.required)
                              || ($v.lastname.$dirty && !$v.lastname.minLength)}"
              >
              <span class="mdi mdi-account-box-multiple icon-lock"
                    :class="{invalid_icon: ($v.lastname.$dirty && !$v.lastname.required)
                              || ($v.lastname.$dirty && !$v.lastname.minLength)}"
              ></span>
            </div>
            <div class="error" v-if="$v.lastname.$dirty && !$v.lastname.required">Поле обязательно для заполнения</div>
            <div class="error" v-else-if="$v.lastname.$dirty && !$v.lastname.minLength">
              Мин. длинна поля {{ $v.lastname.$params.minLength.min }} символов(а)
            </div>

            <div class="register-form__item">
              <input class="register-form__input"
                     type="email"
                     name="email"
                     placeholder="Ваш email"
                     v-model="email"
                     :class="{invalid: ($v.email.$dirty && !$v.email.required) || ($v.email.$dirty && !$v.email.email)}"
              >
              <span class="mdi mdi-email icon-lock"
                    :class="{invalid_icon: ($v.email.$dirty && !$v.email.required) || ($v.email.$dirty && !$v.email.email)}"
                ></span>
            </div>
            <div class="error" v-if="$v.email.$dirty && !$v.email.required">Поле обязательно для заполнения</div>
            <div class="error" v-else-if="$v.email.$dirty && !$v.email.email">
              Поле должно быть валидным емейл адресом
            </div>
            <div class="error" v-if="errors.email"> {{errors.email}} </div>

            <div class="register-form__item">
              <input class="register-form__input"
                     type="password"
                     name="password"
                     placeholder="Придумайте пароль"
                     v-model="password"
                     :class="{invalid: ($v.password.$dirty && !$v.password.required)
                              || ($v.password.$dirty && !$v.password.minLength)}"
              >
              <span class="mdi mdi-lock icon-lock"
                    :class="{invalid_icon: ($v.password.$dirty && !$v.password.required)
                              || ($v.password.$dirty && !$v.password.minLength)}"
              ></span>
            </div>
            <div class="error" v-if="$v.password.$dirty && !$v.password.required">Поле обязательно для заполнения</div>
            <div class="error" v-else-if="$v.password.$dirty && !$v.password.minLength">
              Мин. длинна поля {{ $v.password.$params.minLength.min }} символов(а)
            </div>
            <div class="error" v-if="errors.password">{{errors.password}}</div>


            <div class="register-form__item">
              <input class="register-form__input"
                     type="password"
                     name="password_confirm"
                     placeholder="Подтвердите пароль"
                     v-model="password_confirm"
                     :class="{invalid: ($v.password_confirm.$dirty && !$v.password_confirm.sameAsPassword)}"
              >
              <span class="mdi mdi-lock-open-outline icon-lock"
                    :class="{invalid_icon: ($v.password_confirm.$dirty && !$v.password_confirm.sameAsPassword)}"
              ></span>
            </div>
            <div class="error" v-if="!$v.password_confirm.sameAsPassword">Пароли должны совпадать</div>

            <p class="register-form__subtitle">Дата рождения</p>
            <div class="register-form__date-wrap">

              <div class="register-form__date-select">
                <select class="register-form__date" v-model="day"
                        :class="{invalid: ($v.day.$dirty && !$v.day.required)}"
                >
                  <option disabled selected value="">День</option>
                  <option v-for="(day, index) in days"
                          :key="index"
                  >
                    {{day}}
                  </option>
                </select>
              </div>

              <div class="register-form__date-select">
                <select class="register-form__date" v-model="month"
                        :class="{invalid: ($v.month.$dirty && !$v.month.required)}"
                >
                  <option disabled selected value="">Месяц</option>
                  <option v-for="(month,key, index) in months"
                          :key="index"
                          :value="key"
                  >
                    {{month}}
                  </option>
                </select>
              </div>

              <div class="register-form__date-select">
                <select class="register-form__date" v-model="year"
                        :class="{invalid: ($v.year.$dirty && !$v.year.required)}"
                >
                  <option disabled selected value="">Год</option>
                  <option v-for="(year, index) in years"
                          :key="index"
                  >
                    {{year}}
                  </option>
                </select>
              </div>
            </div>
            <div class="error" v-if="$v.day.$dirty && (!$v.day.required || !$v.month.required || !$v.year.required )">
              Выберите дату полностью
            </div>

            <p class="register-form__subtitle">Пол</p>

            <div class="register-form__gender-wrap">
              <label :class="{invalid: ($v.gender.$dirty && !$v.gender.required)}">
                <input class="register-form__gender-input"
                       id="male" type="radio"
                       name="gender"
                       value="male"
                       v-model="gender"
                >
                <span class="register-form__gender-style"></span>
                Мужской
              </label>

              <label :class="{invalid: ($v.gender.$dirty && !$v.gender.required)}">
                <input class="register-form__gender-input"
                       id="female" type="radio"
                       name="gender"
                       value="female"
                       v-model="gender"
                >
                <span class="register-form__gender-style"></span>
                Женский
              </label>
            </div>
            <div class="error" v-if="$v.gender.$dirty && !$v.gender.required">Выберите пол</div>

            <!--<p class="register-form__login">
              <router-link to="/login">
                Уже есть аккаунт?
              </router-link>
            </p>-->
            <button class="register-form__btn">Регистрация</button>
          </form>
        </div>
      </div>

    </div>
  </div>
</template>

<script>
import { required, minLength, sameAs, email, maxLength, numeric } from 'vuelidate/lib/validators'

export default {
    name: "Register",
    data: () => ({
        ActiveClass: false,
        days: [],
        months: {'01':'Январь', '02':'Февраль', '03':'Март', '04':'Апрель', '05':'Май', '06':'Июнь', '07':'Июль',
          '08':'Август', '09':'Сентябрь', '10':'Октябрь', '11':'Ноябрь', '12':'Декабрь' },
        years: [],

        name: '',
        lastname: '',
        email: '',
        password: '',
        password_confirm: '',
        day: '',
        month: '',
        year: '',
        gender: null,

        errors: {

        },

    }),
    validations: {
      //валидаторы из библиотеки vuelidate
      name: {required, minLength: minLength(3)},
      lastname: {required, minLength: minLength(3)},
      email: {required, email},
      password: {required, minLength: minLength(6)},
      password_confirm: {sameAsPassword: sameAs('password')},
      day: {required},
      month: {required},
      year: {required},
      gender: {required}
    },
    methods: {

     range(start, end) {
        let arr = []
        for (let i = start; i <= end; i++){
          arr.push(i)
        }
        return arr
    },

    register() {

        if (this.$v.$invalid) {
           this.$v.$touch()//активизировать валид
           return
        }

        let date = this.year + '-' + this.month + '-' +  this.day
        let formData = new FormData
        formData.append('name', this.name)
        formData.append('lastname', this.lastname)
        formData.append('email', this.email)
        formData.append('password', this.password)
        formData.append('password_confirmation', this.password_confirm)
        formData.append('gender', this.gender)
        formData.append('date_birth', date)

        this.axios({
            method: 'POST',
            url: '/register',
            data: formData,
            headers: {
              'Content-Type': 'multipart/form-data'
            }
        })
          .then(resp => {
              this.form = {}
            if (resp.status === 200) {
              this.$toasted.show('Регистрация прошла успешно',{
                 type: 'success',
              })
              this.$router.push('/login')
            }

          })
          .catch(errors => {

              switch (errors.response.data.status) {
                case 422:
                  this.errors = errors.response.data.errors
                  break;
                default:
                  console.log(error)
                  break;
              }
          })
          .finally(() => this.isLoading = false)
        }

    },
    mounted() {
        this.days = this.range(1, 31)
        this.years = this.range(1950, 2020)

        if (this.$route.name === 'Register'){
          this.ActiveClass = true
        }
    }
}
</script>


<style scoped lang="sass">
.active
  font-weight: 700!important
  color: deepskyblue!important
.wrapper
  position: relative
  background: linear-gradient(to right, rgba(0,0,0, 0.7) , rgba(0,0,0, 0.7)), url(../../assets/images/bg-3.jpg) fixed no-repeat
  background-size: cover
  width: 100%
  min-height: 100vh
  color: aliceblue
  overflow-y: hidden
.register__inner
  font-family: inherit
  padding: 10px 0
  height: 100vh
  display: flex
  justify-content: space-between
  align-items: center
  .reg-options
    position: relative
    z-index: 9
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
        z-index: 99
        a
          color: aliceblue
  .intro
    margin-top: 100px
    max-width: 550px
    padding: 10px 30px
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
  .register-form
    max-width: 460px
    min-height: 629px
    min-width: 350px
    background: #fff
    box-shadow: 0 0 35px rgba(0,0,0, .4)
    padding: 30px 40px 40px 40px
    border-radius: 6px
    position: relative
    z-index: 10
    margin-left: 60px
    color: #5A5A5A
    .register-form__title
      margin: 30px 0 15px
      text-align: center
      color: deepskyblue
      font-weight: 700
      font-family: 'Amatic SC', cursive
      font-size: 54px
    .register-form__subtitle
      color: deepskyblue
      margin: 10px 0 5px 0
      font-size: 14px
    .login-form__item
      display: flex
    .register-form__input
      margin: 0 10px 10px 10px
      background: #fff !important
      border: none
      box-shadow: none
      width: 97%
      border-bottom: 2px solid rgba(#363838, .4)
      color: #333
      line-height: 1.2
      height: 44px
      background: 0 0
      padding: 0 7px 0 23px
      transition: all .3s ease-in-out
      &:focus
        outline: none
        border: none
        color: #5A5A5A
        border-bottom: 2px solid rgba(deepskyblue, .9)
    input.register-form__input
      background-color: #fff
    .register-form__item
      position: relative
    .icon-lock
      position: absolute
      left: 0
      bottom: 20px
      font-size: 25px
      color: #adadad
    .register-form__date-wrap
      display: flex
      justify-content: space-between
    .register-form__date-select
      position: relative
      z-index: 2
      &::after
        content: "▼"
        padding: 0 8px
        font-size: 12px
        position: absolute
        color: silver
        right: 5px
        top: 6px
        z-index: 1
        text-align: center
        width: 10%
        height: 100%
        pointer-events: none
        box-sizing: border-box
      .register-form__date
        border: none
        outline: none
        appearance: none
        color: #5A5A5A
        border-bottom: 2px solid rgba(#363838, .4)
        padding: 3px 17px 1px 3px
        position: relative
        transition: all .4s ease-in
        &:focus
          border-bottom: 2px solid rgba(deepskyblue, .9)
    .register-form__gender-wrap
      display: flex
      justify-content: space-evenly
      label
        margin-left: 20px
        position: relative
      .register-form__gender-input
        position: absolute
        width: 1px
        height: 1px
        overflow: hidden
        clip: rect(0 0 0 0)
      .register-form__gender-style
        position: absolute
        width: 18px
        height: 18px
        top: 3px
        border: 2px solid silver
        border-radius: 50%
        margin-left: -20px
      .register-form__gender-input:checked + .register-form__gender-style::before
        content: ""
        width: 10px
        height: 10px
        position: absolute
        background-color: deepskyblue
        top: 50%
        left: 50%
        border-radius: 50%
        transform: translate(-50%, -50%)
      //.register-form__gender-input:focus + .register-form__gender-style
        box-shadow: 0 0 0 2px deepskyblue

    .register-form__login
      margin-bottom: 20px
      display: flex
      justify-content: flex-end
      a
        color: deepskyblue
    .register-form__gender-wrap
      margin-bottom: 5px
    .register-form__btn
      margin-top: 15px
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
      background: deepskyblue
      background: linear-gradient(55deg, deepskyblue,deepskyblue, deepskyblue, #fc00ff)
      &:hover
        background: -webkit-linear-gradient(left,#fc00ff,#fc00ff,deepskyblue,deepskyblue)
  input:focus + .mdi
    transition: all .5s
    color: deepskyblue !important
</style>