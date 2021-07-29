<template>
  <aside class="right">
    <div class="right-sidebar__wrap">
      <h3 class="right-sidebar__title">Who to Follow</h3>
      <div class="follow-user">
        <img class="follow-user__image" src="../../assets/images/user-1.jpg" alt="" >
        <div class="follow-user__info">
          <h5 class="follow-user__name"><a class="follow-user__link" href="/">Diana Amber</a></h5>
          <a class="follow-user__action" href="#" >Add friend</a>
        </div>
      </div>

      <div class="follow-user">
        <img class="follow-user__image" src="../../assets/users/user-4.jpg" alt="" >
        <div class="follow-user__info">
          <h5 class="follow-user__name"><a class="follow-user__link" href="/">Diana Amber</a></h5>
          <a class="follow-user__action" href="#" >Add friend</a>
        </div>
      </div>
      <div class="follow-user">
        <img class="follow-user__image" src="../../assets/users/user-2.jpg" alt="" >
        <div class="follow-user__info">
          <h5 class="follow-user__name"><a class="follow-user__link" href="/">Diana Amber</a></h5>
          <a class="follow-user__action" href="#" >Add friend</a>
        </div>
      </div>

      <div class="follow-user">
        <img class="follow-user__image" src="../../assets/users/user-5.jpg" alt="" >
        <div class="follow-user__info">
          <h5 class="follow-user__name"><a class="follow-user__link" href="/">Diana Amber</a></h5>
          <a class="follow-user__action" href="#" >Add friend</a>
        </div>
      </div>

    </div>

  </aside>
</template>

<script>
export default {
  name: "RightBar"
}
</script>

<style scoped lang="sass">
.right
  .right-sidebar__wrap
    .right-sidebar__title
      margin-bottom: 30px
    .follow-user
      display: flex
      margin-bottom: 20px
      padding: 8px 0
      border-bottom: 1px solid rgba(#5a5a5a, .1)
      .follow-user__image
        width: 50px
        height: 50px
        border-radius: 50%
      .follow-user__info
        margin-left: 15px
        .follow-user__link
          color: #149AC9
          font-size: 17px
        .follow-user__action
          color: #39b54a

</style>