<template>
  <div class="friends">
    <div  class="user" v-for="user in friends">
      <img class="user__image"
           :src="user.image ?  $store.state.serverUrl + user.image
                   : require(`../assets/images/no_image.jpg`)" alt="user">

      <div class="user-info">
        <h5 class="user-info__name">
          <router-link class="user-info__link" :to="`/user/${user.id}`" >
            {{user.name}} {{user.lastname}}
          </router-link>
        </h5>
        <p class="user-info__position">Software Engineer</p>
      </div>

      <div class="user-info__action">
        <button v-if="Number(user.relation_type) === 2" class="user-info2__btn" @click="acceptFriend(user.id)">
          Принять
        </button>

        <button class="user-info__btn" @click="rejectFriend(user.id)">
          Отклонить
        </button>

      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "FriendAccept",
  props: {
    friends: {
      type: Array,
      default: () => []
    },
  },
  methods: {
    rejectFriend(friend_id) {
      this.$emit('rejectFriend', friend_id)
    },

    acceptFriend(friend_id) {
      this.$emit('acceptFriendship', friend_id)
    }
  }
}
</script>

<style scoped lang="sass">
.friends
  display: flex
  flex-direction: column
  position: relative
  .users__title
    text-align: left
    margin-bottom: 30px
    font-size: 25px
    padding: 0 30px
  .no-friends
    margin: 0 auto
    font-size: 21px
  .user
    display: flex
    padding: 20px
    background-color: #E6E6FA
    .user__image
      height: 120px
      width: 120px
      border-radius: 50%
    .user-info
      margin-left: 40px
      width: 380px
      .user-info__name
      .user-info__link
        font-size: 25px
        font-weight: 800
        color: #149AC9
      .user-info__position
        line-height: 26px
        color: #6d6e71
        font-size: 13px
      .user-info__message
        color: #39b54a
        cursor: pointer
    .user-info__action
      text-align: center
      display: flex
      flex-direction: column
      justify-content: center
      align-items: center
      font-size: 13px
      .user-info__btn
        display: block
        border: none
        background: #EE204D
        transition: all .5s ease-in
        border-radius: 40px
        padding: 5px 20px
        color: aliceblue
        cursor: pointer
        margin-top: 5px
        &:hover
          background-color: darkorchid
      .user-info2__btn
        border: none
        background: #149AC9
        transition: all .5s ease-in
        border-radius: 40px
        padding: 5px 20px
        color: aliceblue
        cursor: pointer
        margin-top: 5px
        &:hover
          background-color: #8dc63f

</style>