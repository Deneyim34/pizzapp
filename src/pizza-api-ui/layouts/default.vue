<template>
  <div>
    <div class="container" style="padding-bottom: 100px;">
      <Header v-if="$store.getters.getAccess"/>
      <nuxt />
    </div>
    <Footer v-if="$store.getters.getAccess"/>
      <Alert  style="z-index: 999999999;" v-if="$store.getters.getAccess"/>
  </div>
</template>

<script>
import Header from "@/components/common/Header"
import Footer from "@/components/common/Footer"
import Alert from "@/components/common/Alert"

export default {
  middleware : "auth",
components : {
  Header,
  Footer,
  Alert
},
beforeMount(){
  this.$store.dispatch("getRedirectURL");
  this.$store.dispatch("getCart");
  this.$store.dispatch("resetFilters");
  return this.$store.dispatch("authCheck","/");
}
}
</script>

<style>
textarea:hover,
    input:hover,
    textarea:active,
    input:active,
    textarea:focus,
    select:focus,
    select:active,
    select:hover,
    input:focus,
    button:focus,
    button:active,
    button:hover,
    label:focus,
    .btn:active,
    .btn.active
    {
        outline:0px !important;
        -webkit-appearance:none;
        box-shadow: none !important;
    }

html,
    body {
      height: 100%;
    }

    body {
      background-color: #f5f5f5;
      padding-top: 0;
    }

</style>
