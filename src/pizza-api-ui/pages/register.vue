<template>
  <div class="text-center">
    <form class="form-signin">
      <h1 class="h4 mb-3 font-weight-normal">Pizza Order App | Sign Up</h1>
      <RegisterForm :save-txt="'Sign Up'"/>

      <p class="mt-2 text-muted">
        Have an account?
        <nuxt-link to="/login" tag="a">Sign In</nuxt-link>
      </p>
      <p class="mt-5 mb-3 text-muted">&copy; 2019</p>
    </form>
  </div>
</template>

<script>
import RegisterForm from "@/components/customer/RegisterForm";

export default {
  layout:'LoginLayout',
  components: {
    RegisterForm
  }
};
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

    body {
        display: -ms-flexbox !important;
        display: block !important;
        -ms-flex-align: center !important;
        align-items: center !important;
        padding-top: 40px;
        padding-bottom: 40px;
        background-color: #f5f5f5;
    }

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

.form-signin input.roundless-input,
.form-signin select.roundless-input {
  margin-top: -1px;
  border-radius: 0;
  border-radius: 0;
}
</style>
