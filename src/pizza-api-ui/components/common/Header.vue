<template>
  <nav class="navbar navbar-expand-lg navbar-light bg-light shadow mt-2 rounded">
    <nuxt-link class="navbar-brand" to="/" tag="a">Pizza Order App</nuxt-link>
    <a
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



      <div class="btn-group ml-auto">
        <div>
        <a
          href="javascript:void(0)"
          class="btn dropdown-toggle"
          data-toggle="dropdown"
          aria-haspopup="true"
          aria-expanded="false"
        >
          <i class="fa fa-shopping-cart" aria-hidden="true"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-right p-0" style="min-width: 500px;">
          <div class="card bg-light mb-0 shadow">
            <div class="card-header">Cart</div>
            <div class="card-body pb-0">
                <p class="h4 text-center mb-4" v-if="cartTotal == 0">There is no product in your cart.</p>
              <div style="max-height: 240px; overflow: auto;" v-if="CartItems != null && CartItems.length > 0">
                <table class="table">
                  <tbody>
                    <HeaderCart v-for="(product, index) in CartItems" :key="index" :product-index="index" />
                  </tbody>
                </table>
              </div>
              <table class="table" v-if="CartItems.length > 0">
                <tbody>
                  <tr>
                    <th colspan="4" class="align-middle text-right">Total Price</th>
                    <td class="align-middle text-right" style="width: 100px;">${{CartTotal | numberFormat}}</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          <nuxt-link to="/cart" tag="a" class="btn btn-lg btn-secondary btn-block" style="color:#f5f5f5;"  v-if="CartItems.length > 0">
            <i class="fa fa-check-circle"></i> Give
            Order
          </nuxt-link>
        </div>
        </div>

        <div class="btn-group">
          <a

            class="btn dropdown-toggle"
            data-toggle="dropdown"
            aria-haspopup="true"
            aria-expanded="false"
          >
            <i class="fa fa-user-circle" aria-hidden="true"></i>
          </a>
          <div class="dropdown-menu dropdown-menu-right">
            <nuxt-link class="dropdown-item" to="/profile" tag="a">
              <i class="fa fa-user-cog" aria-hidden="true"></i> Account
              Settings
            </nuxt-link>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" @click="logout">
              <i class="fa fa-sign-out" aria-hidden="true"></i> Sign Out
            </a>
          </div>
        </div>
      </div>
    </div>
  </nav>
</template>

<script>
import HeaderCart from "@/components/common/Header-Cart";

export default {
  components: {
    HeaderCart
  },
  data(){
    return {
      cartTotal : 123
    }
  },
methods:{
  logout(){
    this.$store.dispatch("logout","/login");
  }
},
  computed : {
    CartItems()
    {
      return this.$store.getters.getCart;
    },
    CartTotal()
    {
      this.cartTotal = this.$store.getters.getCartTotal;
      return this.$store.getters.getCartTotal;
    }
  },
  watch:{
    CartTotal()
    {
      this.cartTotal = this.$store.getters.getCartTotal;
    }
  }
};
</script>

<style>

</style>
