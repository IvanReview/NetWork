<template>
  <div class="hello">
    <div class="main-wrapper">
      <Header />

      <div>
        <section class="main">
          <div class="container">
            <div class="main__inner">

              <LeftBar/>

              <router-view></router-view>

            </div>
          </div>
        </section>

      </div>


      <!--<Footer/>-->
    </div>
  </div>
</template>

<script>
import LeftBar from "./common/LeftBar";
import {mapActions, mapGetters} from "vuex";
import Header from "./common/Header";
export default {
  name: 'HelloWorld',
  components: {Header, LeftBar},
  data() {
    return {
      logged_user_id: '',
    }
  },
  props: {
    msg: String
  },
  computed: {
    ...mapGetters([
        'getCurrentUser'
    ])
  },
  methods: {
    ...mapActions([
        'receiveCurrentUser'
    ])
  },
  created() {
    this.axios.defaults.headers.common["Authorization"] = `Bearer ` + localStorage.getItem('token')
  },
  mounted() {

  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style  lang="sass">
.main
  padding-top: 100px
  .main__inner
    display: grid
    grid-template-columns: 210px 2fr
    grid-gap: 20px

</style>
