<template>
  <header class="header">
    <div class="container">
      <div class="header__inner">

        <router-link to="/" class="logo">
          <img :src="require('/src/assets/images/logo.png')">
        </router-link>

        <div class="header-form">
          <div class="search-form">
            <input class="search-form__input" type="text" name="search" placeholder="Search" v-model="search">
            <button type="submit" class="search-form__btn" @click.prevent="searchMeth">
              <span class="mdi mdi-magnify"></span>
            </button>
          </div>
        </div>

        <nav class="menu">
          <ul class="menu__list">
            <li class="menu__list-item">
              <router-link to="/about"
                           class="menu__list-link"
              >
                About
              </router-link>
            </li>
            <li class="menu__list-item">
              <router-link to="/gallery" class="menu__list-link">Gallery</router-link>
            </li>
            <li class="menu__list-item">
              <router-link to="/gallery" class="menu__list-link">Mune</router-link>
            </li>
          </ul>
        </nav>
      </div>
    </div>
  </header>
</template>

<script>
export default {
  name: "Header",
  data() {
    return {
      search: '',
    }
  }
}
</script>

<style lang="sass" scoped>
.header
  background: #231F20
  padding: 10px 0
  border: none
  border-radius: 0
  margin-bottom: 0
  position: fixed
  right: 0
  left: 0
  z-index: 1000
  &__inner
    display: flex
    justify-content: space-between
    max-height: 340px
    .menu__list
      display: flex
      justify-content: space-between
    .menu__list-item
      padding: 5px
      margin: 0 10px
    .menu__list-link
      color: aliceblue
  &-form
    position: relative
    .search-form__input
      background: rgba(255,255,255, 0.2)
      border: 0
      box-shadow: none
      min-width: 235px
      color: #fff
      height: 26px
      border-radius: 13px
      font-size: 13px
      padding-left: 35px
    .search-form__btn
      background-color: transparent
      cursor: pointer
      border: none
      color: aliceblue
      position: absolute
      left: 5px
      top: 1px
      z-index: 99
</style>