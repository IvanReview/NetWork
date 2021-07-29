<template>
    <div class="edit-user">
      <div class="container">
        <div class="users__inner">

          <LeftSecondBar />

          <main class="main-edit">

            <h2 class="users__title">Редактировать инфо</h2>

            <p>At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium
              voluptatum deleniti
            </p>

            <div class="main__content">

              <form class="form__edit" @submit.prevent="editProfile">
                <div class="form__item">
                  <label for="name">Имя</label>
                  <input class="form__input" type="text" name="name" id="name" v-model="user_edit.name" >
                </div>
                <div class="form__item">
                  <label for="name">Фамилия</label>
                  <input class="form__input" type="text" v-model="user_edit.lastname">
                </div>
                <div class="form__item">
                  <label for="name">Email</label>
                  <input class="form__input" type="text" v-model="user_edit.email">
                </div>
                <div class="form__item">
                  <label for="name">Пароль</label>
                  <input class="form__input" type="password" v-model="user_edit.password">
                </div>

                <div class="form__item">
                  <label for="name">Подтвердить пароль</label>
                  <input class="form__input" type="password" v-model="user_edit.password_confirm">
                </div>

                <h5>Дата рождения</h5>
                <div class="form__date-wrap">

                  <div class="form__date-select">
                    <select class="form__date" v-model="user_edit.day">
                      <option disabled selected value="">День</option>
                      <option v-for="(day, index) in days"
                              :key="index"
                      >
                        {{day}}
                      </option>
                    </select>
                  </div>

                  <div class="form__date-select">
                    <select class="form__date" v-model="user_edit.month"

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

                  <div class="form__date-select">
                    <select class="form__date" v-model="user_edit.year">
                      <option disabled selected value="">Год</option>
                      <option v-for="(year, index) in years"
                              :key="index"
                      >
                        {{year}}
                      </option>
                    </select>
                  </div>
                </div>

                <h5>Пол</h5>
                <div class="form__gender-wrap">
                  <label >
                    <input class="register-form__gender-input"
                           id="male" type="radio"
                           name="gender"
                           value="1"
                           v-model="user_edit.gender"
                    >
                    <span class="register-form__gender-style"></span>
                    Мужской
                  </label>

                  <label >
                    <input class="register-form__gender-input"
                           id="female" type="radio"
                           name="gender"
                           value="0"
                           v-model="user_edit.gender"
                    >
                    <span class="register-form__gender-style"></span>
                    Женский
                  </label>
                </div>

                <div class="form__item">
                  <label for="name">Обо мне</label>
                  <textarea class="form__textarea" v-model="user_edit.about_me" ></textarea>
                </div>

                <div class="load-image">
                  <input multiple class="form__image-input"
                         type="file"
                         ref="editImage" id="file"
                         @change="attachImageAfterLoad($event.target.files[0])"
                  >
                  <label for="file"><span class="fileName" ref="fileName">Выбрать фото </span></label>
                </div>
                <!--Аватарка-->
                <div  class="form__image-wrap">
                  <img class="form__image"
                       :src=" user_edit.image ? $store.state.serverUrl + user_edit.image : require(`../assets/images/no_image.jpg`)"
                       ref="newUserImage"/>
                </div>

                <button class="form__btn">Редактировать</button>
              </form>

            </div>
          </main>

          <right-bar/>
        </div>
      </div>
    </div>
</template>

<script>
import {mapActions, mapGetters} from "vuex";
import LeftBar from "./common/LeftBar";
import RightBar from "./common/RightBar";
import { required, minLength, sameAs, email, maxLength, numeric } from 'vuelidate/lib/validators'
import LeftSecondBar from "./common/LeftSecondBar";

export default {
  name: "EditUser",
  components: {LeftSecondBar, RightBar, LeftBar},
  data () {
    return {
      days: [],
      months: {'01':'Январь', '02':'Февраль', '03':'Март', '04':'Апрель', '05':'Май', '06':'Июнь', '07':'Июль',
        '08':'Август', '09':'Сентябрь', '10':'Октябрь', '11':'Ноябрь', '12':'Декабрь' },
      years: [],

      user_edit: {
        id: '',
        name: '',
        lastname: '',
        email: '',
        password: '',
        password_confirm: '',
        about_me: '',
        day: '',
        month: '',
        year: '',
        gender: '',
        image: null,
      }
    }
  },
  computed: {
    ...mapGetters([
      'getFriends',
      'getCurrentUser',
      'getLoginUserId'
    ]),

  },
  methods: {
    ...mapActions([
        'editUserProfile',
        'receiveCurrentUser'
    ]),

    splitDate() {
      let arr  = this.getCurrentUser.date_birth.split('-')
      this.user_edit.year  = arr[0]
      this.user_edit.month = arr[1]
      this.user_edit.day = arr[2]
    },

    range(start, end) {
      let arr = []
      for (let i = start; i <= end; i++){
        arr.push(i)
      }
      return arr
    },


    attachImageAfterLoad(file) {

      if (file) {

        this.user_edit.image = file
        let reader = new FileReader();
        reader.readAsDataURL(file)

        reader.addEventListener('load',  () => {
          this.$refs.newUserImage.src = reader.result;
        })
      }
    },


    editProfile() {
      let date = this.user_edit.year + '-' + this.user_edit.month + '-' +  this.user_edit.day
      let formData = new FormData

      formData.append('name', this.user_edit.name)
      formData.append('lastname', this.user_edit.lastname)
      formData.append('email', this.user_edit.email)
      formData.append('gender', this.user_edit.gender)
      formData.append('date_birth', date)
      formData.append('about_me', this.user_edit.about_me)
      formData.append('image', this.user_edit.image)

      if (this.user_edit.password || this.user_edit.password_confirm) {
        formData.append('password', this.user_edit.password)
        formData.append('password_confirmation', this.user_edit.password_confirm)
      }

      this.editUserProfile({user_id: this.user_edit.id, formData}).then(resp => {
        if (resp === 200) {
          this.$toasted.show('Данные еспешно отредактированны',{
            type: 'success',
          })
        }
      })
    }


  },
  created() {

    /*this.receiveCurrentUser(this.getLoginUserId)
    console.log(this.user_edit, 'created')*/

  },
  updated() {
    /*this.user_edit = this.getCurrentUser
    if (this.user_edit.date_birth) this.splitDate()*/


  },
  mounted() {
    setTimeout(() => {
      this.user_edit = this.getCurrentUser
      this.days = this.range(1, 31)
      this.years = this.range(1950, 2020)
      if (this.user_edit.date_birth) this.splitDate()

    }, 400)




  }
}
</script>


<style scoped lang="sass">
.users__inner
  display: grid
  grid-template-columns: 250px 3fr 200px
.users__title
  margin-bottom: 40px
.main-edit
  padding: 0 40px
  background-color: #fff
.form__edit
  padding: 30px 40px 40px 40px
  border-radius: 6px
  position: relative
  z-index: 10
  color: #5A5A5A
  .form__item
    padding: 10px 0
  .form__input
    background: #fff !important
    border: none
    box-shadow: none
    width: 97%
    border-bottom: 1px solid rgba(#363838, .4)
    color: #333
    line-height: 1.2
    height: 44px
    background: 0 0
    padding: 0 7px 0 3px
    transition: all .3s ease-in-out
    &:focus
      outline: none
      border: none
      color: #5A5A5A
      border-bottom: 2px solid rgba(deepskyblue, .9)
  .form__date-wrap
    display: flex
    justify-content: space-between
    padding: 10px 0
  .form__gender-wrap
    padding: 10px 0
  .form__textarea
    width: 100%
    background: #fff
    border: 1px solid rgba(#363838, .4)
    box-shadow: none
    border-radius: 4px
    color: #939598
    transition: all .3s ease-in
    height: 120px
    &:focus
      outline: none
      color: #5A5A5A
      border: 2px solid rgba(deepskyblue, .9)
  .form__btn
    background-color: #8dc63f
    top: 0
    border-radius: 30px
    color: aliceblue
    padding: 7px 20px
    border: none
    cursor: pointer
    transition: all .5s ease-in
    margin-top: 20px
    width: 100%
    &:hover
      opacity: .68
      text-decoration: none
  .load-image
   margin: 20px 0
  .form__image-input
    width: 0.01px
    height: 0.01px
    opacity: 0
    overflow: hidden
    position: absolute
    z-index: 1
  .form__image-input + label
    width: 100%
    z-index: 0
    font-size: 16px
    text-transform: uppercase
    color: white
    background-color: deepskyblue
    opacity: .9
    box-shadow: 0 0 10px black
    display: inline-block
    cursor: pointer
    padding: 15px 30px
    transition: all .3s ease
  .form__image
    max-height: 300px
  .form__date-wrap
    display: flex
    justify-content: space-between
    .form__date-select
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
      .form__date
        border: none
        outline: none
        appearance: none
        color: #5A5A5A
        border-bottom: 2px solid rgba(#363838, .4)
        padding: 5px 17px 1px 5px
        position: relative
        transition: all .4s ease-in
        &:focus
          border-bottom: 2px solid rgba(deepskyblue, .9)
</style>