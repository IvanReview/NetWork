<template>
  <div style="background-color: deepskyblue">
    <div class="container">
      <div class="error">
        <h1 class="error-code">404 </h1>
        <h3 class="error-text">Not Found</h3>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "Error404"
}
</script>

<style scoped lang="sass">
  .container
    height: 100vh
    display: flex
    align-items: center
  .error
    display: flex
    flex-direction: column
    align-items: center
    flex: 1 0 auto
    line-height: 80px
    .error-code
      text-align: center
      vertical-align: middle
      font-size: 170px
      line-height: 170px
      font-weight: 800
      color: #424242
      text-shadow: rgb(61 61 61 / 30%) 1px 1px, rgb(61 61 61 / 20%) 2px 2px, rgb(61 61 61 / 30%) 3px 3px
    .error-text
      font-size: 40px
      font-weight: 800
      color: dimgray

</style>