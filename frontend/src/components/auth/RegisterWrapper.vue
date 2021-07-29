<template>
  <template>
    <div class="wrapper">
      <div class="container">
        <div class="register__inner">

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
                <router-link  to="/login">Войти</router-link>
              </li>
              <li class="reg-option__nav-item">
                <router-link :class="{active: ActiveClass}" to="/register">Регистрация</router-link>
              </li>
            </ul>
          </div>

          <div class="register-form">
            <h4 class="register-form__title">Регистрация</h4>
            <p class="register-form__subtitle">Войти в свой аккаунт</p>
            <form class="register-form__form">
              <input class="register-form__input" type="email" name="email" placeholder="Введите email">
              <input class="register-form__input" type="password" name="password" placeholder="Введите пароль">
              <p class="register-form__forgot">
                <router-link to="/register">
                  Уже есть аккаунт?
                </router-link>
              </p>
              <button class="register-form__btn">Войти</button>
            </form>
          </div>
        </div>

      </div>
    </div>
  </template>

</template>

<script>
export default {
  name: "RegisterWrapper"
}
</script>

<style lang="sass">
.active
  font-weight: 700 !important
  color: teal !important
.wrapper
  position: relative
  background: linear-gradient(to right, rgba(0,0,0, 0.7) , rgba(0,0,0, 0.7)), url(../../assets/images/bg-3.jpg) fixed no-repeat
  background-size: cover
  width: 100%
  min-height: 100vh
  color: aliceblue
  overflow-y: hidden


</style>