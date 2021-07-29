<template>
  <div class="images" >
    <div class="container">
      <div class="images__inner">

        <user-left-bar :user="getCurrentUserOrFriend" :friends="getFriends"/>

        <main class="images-main" >
          <!--всплывающее окно для картинки-->
          <transition name="fade">
            <PopupFriend v-if="isPopupShow" @closePopup="closePopup"
                   :image="image_data"
                   :user="getCurrentUser"
                   @addComment  = addCommentForImage
            />
          </transition>

          <!--Загрузить фоточки-->
          <h2 style="text-align: center; margin: 20px">Фоточки </h2>


          <loader v-if="isLoading"/>

          <!--Картинки-->

          <div class="images__wrap" ref="gallery_container">
            <p class="no-image" v-if="!getImages.length && !fileStore.length">Загрузите фото!</p>

            <div v-else class="images__item" v-for="image in getImages">
              <img  class="images__item-image"  :src="$store.state.serverUrl + image.name" @click="showPopup(image)">
            </div>
          </div>

        </main>

      </div>
    </div>

  </div>
</template>

<script>
import Loader from "./loader/Loader";
import PopupFriend from "./common/PopupFriend";
import LeftBar from "./common/LeftBar";
import FsLightbox from "fslightbox-vue";
import {mapActions, mapGetters} from "vuex";
import UserLeftBar from "./common/UserLeftBar";

export default {
  name: "ImagesUser",
  components: {UserLeftBar, Loader, PopupFriend, LeftBar, FsLightbox },
  data() {
    return {
      image_data: '',
      isPopupShow: false,
      isLoading: false,
      toggler: false,
      images: [],
      fileStore: [],

      logged_user_id: '',
      userId: '',
    }
  },
  watch: {
    '$route.params.id': {
      immediate: true,
      handler() {

        this.logged_user_id = this.getLoginUserId

        this.userId = window.location.href.split('/')[4]


      },
    },
  },
  computed: {
    ...mapGetters([
      'getImages',
      'getLoginUserId',
      'getImage',
      'getCurrentUser',
      'getUserData',
      'getFriends',
      'getCurrentUserOrFriend'

    ])
  },
  methods: {
    ...mapActions([
      'fetchImages',
      'fetchCommentsForImage',
      'addCommentToImage',
      'receiveUserOrFriendData'
    ]),



    addCommentForImage(image_id, text) {

      let user_id = this.logged_user_id

      let formData = new FormData
      formData.append('author_id', user_id)
      formData.append('image_id', image_id)
      formData.append('text', text)

      this.addCommentToImage(formData).then(resp => {
        if (resp ===200) {

        }
      })
    },


    showPopup(image) {
      this.image_data  = image
      this.isPopupShow = true
    },

    closePopup() {
      this.isPopupShow = false
    }
  },
  updated() {

  },
  created() {
    this.logged_user_id = this.getLoginUserId
  },
  mounted() {

    this.fetchImages(this.userId)

    this.receiveUserOrFriendData({user_id: this.logged_user_id, friend_id: this.userId})
  }
}
</script>

<style scoped lang="sass">
.images__inner
  display: grid
  grid-template-columns: 250px 3fr
  grid-gap: 30px
.images__wrap
  display: flex
  justify-content: space-around
.images__item
  display: flex
  align-items: center
  padding: 10px 10px
  cursor: pointer
  background-color: antiquewhite
  margin: 5px
.images__item-image
  max-width: 280px
.images-main
  display: flex
  flex-direction: column
.images__wrap
  display: flex
  flex-wrap: wrap
.load-image__form
  display: flex
  margin-bottom: 30px
  .load-image__input
    width: 0.01px
    height: 0.01px
    opacity: 0
    overflow: hidden
    position: absolute
    z-index: -1
  .load-image__input + label
    width: 100%
    z-index: 0
    font-size: 16px
    text-transform: uppercase
    color: white
    background-color: #6495ED
    opacity: .9
    box-shadow: 0 0 10px black
    display: inline-block
    cursor: pointer
    padding: 15px 30px
    transition: all .3s ease
  .load-image__btn
    background-color: #AB82FF
    border: none
    box-shadow: 0 0 10px black
    color: aliceblue
    padding: 0 15px
    cursor: pointer
    transition: all ease-in .4s
    &:hover
      background-color: orangered
.fade-enter-active, .fade-leave-active
  transition: opacity .6s
.fade-enter, .fade-leave-to
  opacity: 0
.no-image
  width: 100%
  text-align: center
  margin-top: 30px
  font-size: 22px


</style>