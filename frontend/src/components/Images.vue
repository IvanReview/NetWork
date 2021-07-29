<template>
  <div class="images" >
    <div class="container">
      <div class="images__inner">

        <LeftSecondBar  />

        <main class="images-main" >
          <!--всплывающее окно для картинки-->
          <transition name="fade">
            <popup v-if="isPopupShow" @closePopup="closePopup"
                   :image="image_data"
                   :user="getCurrentUser"
                   @addComment  = addCommentForImage
            />
          </transition>

          <!--Загрузить фоточки-->
          <div class="load-image">
            <form class="load-image__form">
              <input multiple class="load-image__input"
                     type="file"
                     ref="newImageGallery" id="file"
                     @change="attachImageAfterLoad"
              >
              <label for="file"><span class="fileName" ref="fileName">Выбрать фото (удалить по клику)</span></label>
              <button class="load-image__btn" @click.prevent="addImages">Загрузить</button>
            </form>
          </div>

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
import LeftBar from "./common/LeftBar";
import FsLightbox from "fslightbox-vue";
import Popup from "./common/Popup";
import {mapActions, mapGetters} from "vuex";
import Loader from "./loader/Loader";
import * as url from "../helpers/http_service"
import LeftSecondBar from "./common/LeftSecondBar";

export default {
  name: "Images",
  components: {LeftSecondBar, Loader, Popup, LeftBar, FsLightbox },
  data() {
    return {
      image_data: '',
      isPopupShow: false,
      isLoading: false,
      toggler: false,
      images: [],
      fileStore: [],

      current_user_id: ''
    }
  },
  computed: {
    ...mapGetters([
        'getImages',
        'getLoginUserId',
        'getImage',
        'getCurrentUser'
    ])
  },
  methods: {
    ...mapActions([
        'addImagesInBd',
        'fetchImages',
        'fetchCommentsForImage',
        'addCommentToImage'
    ]),


    addImages() {
      this.isLoading = true
      let user_id = this.current_user_id

      let formData = new FormData
      formData.append('user_id', user_id)

      let key = 'images';
      this.fileStore.forEach((image, index) => {
        formData.append(`${key}[${index}]`, image)
      })

      this.addImagesInBd( formData).then(resp => {
        if (resp === 200) {
          this.fileStore = []
          let images_container = document.querySelectorAll('.for_del')
          for (let image of images_container) {
            image.remove()
          }

          this.isLoading = false
        }
      })
    },

    addCommentForImage(image_id, text) {

      let user_id = this.current_user_id

      let formData = new FormData
      formData.append('author_id', user_id)
      formData.append('image_id', image_id)
      formData.append('text', text)

      this.addCommentToImage(formData).then(resp => {
        if (resp ===200) {

        }
      })
    },

    //показать изображения галлереи после загрузки
    attachImageAfterLoad() {

      let files = this.$refs.newImageGallery.files

      let parentContainer = this.$refs.gallery_container
      let container = []


      //если количество файлов > количества контейнеров добавляем еще
      if (container.length < files.length) {

        for (let index = 0; index < files.length - container.length; index++) {

          let el = document.createElement('div')
          el.classList.add('images__item', 'empty_container', 'for_del')
          el.style.cursor = 'pointer'
          el.style.display = 'flex'
          el.style.alignItems = 'center'
          el.style.padding = '10px 0'

          parentContainer.prepend(el)
        }
        container = parentContainer.querySelectorAll('.empty_container')
      }

      for (let i in files) {
        if (files.hasOwnProperty(i)) {

          //пушим файлы в переменную и получаеи индекс
          let addElemId = this.fileStore.push(files[i]) - 1

          this.showImageGallery(files[i], container[i])

          this.deleteDisplayImage(addElemId, container[i])
        }
      }
    },

    //непосредственно отображение изображения галлереи
    showImageGallery(file, container) {
      let reader = new FileReader()

      //содержимое контейнера удаляем
      container.innerHTML = ''

      reader.readAsDataURL(file);

      reader.onload = function (e) {

        //внутри контейнера создаем тег img
        container.innerHTML = '<img class="images__item-image" style="max-width: 280px" src="">'

        //вставляем в img файл
        container.querySelector('img').setAttribute('src', reader.result)

        container.classList.remove('empty_container')

      };

    },

    //удалить изображение по клику
    deleteDisplayImage(addElemId, container) {

      container.addEventListener('click', () => {

        //сносим контаинер
        container.remove()

        //и файл из переменной
        delete this.fileStore[addElemId]

        this.fileStore = this.fileStore.filter((file) => file !== "undefined")
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
      this.current_user_id = this.getLoginUserId
  },
  mounted() {
    let user_id = this.current_user_id

    this.fetchImages(user_id)


  }
}
</script>

<style scoped lang="sass">
  .images__inner
    display: grid
    grid-template-columns: 250px 2fr
    grid-gap: 30px
  .images__wrap
    display: flex
    justify-content: space-between
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