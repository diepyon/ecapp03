<template>
  <vue-wave-surfer :src="file" :options="options"></vue-wave-surfer>
</template>
<script>
export default {
  data() {
    return {
      options: {
      },
      file: 'http://example.com/file.mp3'
    }
  }
}
</script>