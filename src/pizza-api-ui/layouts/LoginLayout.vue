<template>
  <div>
      <nuxt />
  </div>
</template>

<script>
export default {

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

    html,
    body {
        height: 100%;
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
</style>