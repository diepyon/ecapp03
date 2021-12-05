<template>
  <vue-wave-surfer :src="file" :options="options"></vue-wave-surfer>
</template>
<script>
export default {
  data() {
    return {
      options: {
      },
      file: '/storage/stock_sample/f77fc4e0.mp3'
    }
  },
  mounted() {

  },  
 
}
</script>