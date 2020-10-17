<template>
  <div v-if="AlertData != null" style="z-index: 999999999;">
    <b-modal ref="my-modal" hide-header hide-footer v-model="AlertShow">
      <div class="d-block text-center">
        <b-alert show :variant="AlertData.type">{{AlertData.message}}</b-alert>
      </div>
      <b-button class="mt-3" variant="secondary" block @click="AlertShow = !AlertShow">Close</b-button>
    </b-modal>
  </div>
</template>

<script>
export default {
  data(){
    return {
      AlertShow: this.modalShow
    }
  },
  computed : {

    AlertData(){
      return this.$store.getters.getAlertData;
    },
    modalShow(){
      return this.$store.getters.getAlertShow;
    }
  },
  watch:{
    modalShow(){
      this.AlertShow = this.modalShow;
    },
    AlertShow(){
      this.$store.dispatch("setAlertShow",this.AlertShow);
    }
  }
}
</script>
