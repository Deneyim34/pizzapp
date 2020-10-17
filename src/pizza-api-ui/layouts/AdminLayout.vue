<template>
  <div>
    <div class="container" style="padding-bottom: 100px;">
      <AdminHeader v-if="$store.getters.getAdminAccess"/>
      <nuxt />
    </div>
    <Footer v-if="$store.getters.getAdminAccess"/>
      <Alert v-if="$store.getters.getAdminAccess"/>
  </div>
</template>

<script>
import AdminHeader from "@/components/common/AdminHeader"
import Footer from "@/components/common/Footer"
import Alert from "@/components/common/Alert"

export default {
  middleware : "adminAuth",
  components : {
    AdminHeader,
    Footer,
    Alert
  },
  data(){
    return {
      access: false
    }
  },
  beforeMount(){
    this.$store.dispatch("getRedirectURL");
    this.$store.dispatch("resetFilters");
    return this.$store.dispatch("authCheck","/admin/");
  }
}
</script>

<style>
html,
    body {
      height: 100%;
    }

    body {
      background-color: #f5f5f5;
      padding-top: 0;
    }

</style>
