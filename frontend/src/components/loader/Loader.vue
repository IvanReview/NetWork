<template>
  <div class="loader">
    <div class="lds-dual-ring"></div>
  </div>
  
</template>

<script>
export default {
  name: "Loader"
}
</script>

<style scoped>
.loader{
  width: 100%;
  text-align: center;
  margin: 20px;
}

.lds-dual-ring {
  display: inline-block;
  width: 80px;
  height: 80px;
}
.lds-dual-ring:after {
  content: " ";
  display: block;
  width: 64px;
  height: 64px;
  margin: 8px;
  border-radius: 50%;
  border: 6px solid orangered;
  border-color: deepskyblue transparent deepskyblue transparent;
  animation: lds-dual-ring 1.2s linear infinite;
}
@keyframes lds-dual-ring {
  0% {
    transform: rotate(0deg);
  }
  100% {
    transform: rotate(360deg);
  }
}

</style>