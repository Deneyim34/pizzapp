import Vue from 'vue';

import BootstrapVue from 'bootstrap-vue';
//import VueCookies from 'vue-cookies';
import Cookie from 'js-cookie';
import Vuelidate from 'vuelidate';
import VueResource from 'vue-resource';

Vue.use(BootstrapVue);
Vue.use(VueResource);
//Vue.use(VueCookies);
Vue.use(Cookie);
Vue.use(Vuelidate);

Vue.filter("numberFormat", (num) => {
  return parseFloat(num).toFixed(2).toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,');
})

Vue.mixin({
  methods: {
    showAlert(type, title, message){
      let AlertData = {
        title,
        type,
        message
      };
      this.$store.dispatch("setAlertData", AlertData);

    }
  }
})

