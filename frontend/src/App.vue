<template>
  <div id="app">
    <router-view/>
  </div>
</template>
<script>
import {mapActions, mapGetters} from "vuex";
import axios from "axios";

export default {
  name: "App",
  components: {},
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
      'getLoginUserId'
    ])
  },
  methods: {
    ...mapActions([
      'receiveCurrentUser',
      'fetchPosts'
    ]),

    async online(user_id) {
      try{
        let resp = await axios({
          method: 'POST',
          url: `/online/${user_id}`,
        });

        return resp

      } catch(e){
        console.log(e)
      }
    }
  },
  updated() {

  },
  mounted() {

    /*this.online(this.getLoginUserId)

    setInterval(() => {
      this.online(this.getLoginUserId)
    }, 1000 * 60 * 5)*/

  }
};
</script>


<style lang="scss"></style>
