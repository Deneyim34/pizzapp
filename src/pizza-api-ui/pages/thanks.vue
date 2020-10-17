<template>
  <div class="card bg-light mb-3 shadow mt-4" v-if="$store.getters.getAccess">
    <div class="card-header h4">
      <div class="float-left">Thank You!</div>
    </div>
    <div class="card-body pt-0 pb-0 mb-0 jumbotron">
      <div class="jumbotron text-center">
        <h1 class="display-3">Thank you for your order</h1>
        <p class="lead">
          <strong>Your order has been placed and is being processed</strong>
        </p>
        <hr />
        <p>&nbsp;</p>
        <p class="lead">
          <nuxt-link class="btn btn-primary btn-sm" to="/" tag="a">
            Continue to homepage
          </nuxt-link>
        </p>
      </div>
    </div>
  </div>
</template>

<script>
export default {
};
</script>

<style >
</style>
