<template>
  <form class="form-signin mt-4" v-if="$store.getters.getAccess">
          <h1 class="h4 mb-3 font-weight-normal text-center"><i class="fa fa-user-cog" aria-hidden="true"></i> Account Settings</h1>
          {{userData}}
          <CustomerForm :all-fields="true" :save-txt="'Save'" :id="1"/>
      </form>
</template>

<script>
import CustomerForm from "@/components/customer/CustomerForm";

export default {
  components: {
    CustomerForm
  }
};
</script>

<style>
.form-signin {
        width: 100%;
        max-width: 330px;
        padding: 15px;
        margin: auto;
    }
    .form-signin .checkbox {
        font-weight: 400;
    }
    .form-signin .form-control {
        position: relative;
        box-sizing: border-box;
        height: auto;
        padding: 10px;
        font-size: 16px;
    }
    .form-signin .form-control:focus {
        z-index: 2;
    }
    .form-signin input.top-input {
        margin-bottom: -1px;
        border-bottom-right-radius: 0;
        border-bottom-left-radius: 0;
    }
    .form-signin input.bottom-input {
        margin-top: -1px;
        margin-bottom: 10px;
        border-top-left-radius: 0;
        border-top-right-radius: 0;
    }

    .form-signin input.roundless-input,.form-signin select.roundless-input {
        margin-top: -1px;
        border-radius: 0;
        border-radius: 0;
    }
</style>
