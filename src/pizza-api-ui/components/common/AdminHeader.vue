<template>
  <nav class="navbar navbar-expand-lg navbar-light bg-light shadow mt-2 rounded">
    <nuxt-link to="/admin" tag="a" class="navbar-brand">Pizza Order App</nuxt-link>
    <a
      href="javascript:void(0)"
      class="navbar-toggler"
      data-toggle="collapse"
      data-target="#navbarSupportedContent"
      aria-controls="navbarSupportedContent"
      aria-expanded="false"
      aria-label="Toggle navigation"
    >
      <span class="navbar-toggler-icon"></span>
    </a>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">

      <ul class="navbar-nav mr-auto">
        <nuxt-link to="/admin" tag="li" class="nav-item active">
          <a class="nav-link">
            Dashboard
            <span class="sr-only">(current)</span>
          </a>
        </nuxt-link>
        <nuxt-link to="/admin/products" tag="li" class="nav-item">
          <a class="nav-link">Pizza Collection</a>
        </nuxt-link>
        <nuxt-link to="/admin/orders" tag="li" class="nav-item">
          <a class="nav-link">Orders</a>
        </nuxt-link>
        <nuxt-link to="/admin/customers" tag="li" class="nav-item">
          <a class="nav-link">Customers</a>
        </nuxt-link>
      </ul>

      <div class="ml-auto">
        <a class="dropdown-item" @click="logout">
          <i class="fa fa-sign-out" aria-hidden="true"></i> Sign Out
        </a>
      </div>
    </div>
  </nav>
</template>

<script>

export default {
  methods:{
  logout(){
    this.$store.dispatch("logout","/admin/login");
  }
}
};
</script>

<style>

</style>
