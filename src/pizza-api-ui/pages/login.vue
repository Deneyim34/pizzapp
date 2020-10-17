<template>
  <div class="form-signin text-center">
        <h1 class="h3 mb-3 font-weight-normal">Pizza Order App | Login</h1>
        <label for="inputEmail" class="sr-only">Email address</label>
        <input type="email" id="inputEmail" class="form-control" placeholder="Email address" required autofocus v-model="authDatas.username">
        <label for="inputPassword" class="sr-only">Password</label>
        <input type="password" id="inputPassword" class="form-control" placeholder="Password" required v-model="authDatas.password">
        <div class="btn btn-sm btn-danger btn-block" v-if="error != ''">{{error}}</div>
        <button class="btn btn-lg btn-primary btn-block" @click.prevent="login" >Sign in</button>
        <p class="mt-2 text-muted">Don't have an account? <nuxt-link to="/register" tag="a">Sign Up</nuxt-link></p>
        <p class="mt-5 mb-3 text-muted">&copy; 2019</p>
    </div>
</template>

<script>
export default {
  layout:'LoginLayout',
  data(){
    return{
      authDatas:{
        username:"customer@pizzapp.test",
        password:"password",
        redirect:"/"
      },
      error : ""
    }
  },
  methods : {
    login(){
      this.error = '';
      this.$store.dispatch("login",this.authDatas)
      .then((res)=>{
        this.error = res;
      });
    }
  }

}
</script>

<style scoped>
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

    .form-signin input[type="email"] {
        margin-bottom: -1px;
        border-bottom-right-radius: 0;
        border-bottom-left-radius: 0;
    }
    .form-signin input[type="password"] {
        margin-bottom: 10px;
        border-top-left-radius: 0;
        border-top-right-radius: 0;
    }
</style>
