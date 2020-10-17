<template>
  <div v-if="$store.getters.getAccess">
    <div class="card mt-5">
      <h5 class="card-header">Shopping Cart Details</h5>
      <div class="card-body">
        <p class="h3 text-center" v-if="cartTotal == 0">There is no product in your cart.</p>
        <table id="cart" class="table table-hover table-condensed"  v-if="CartItems.length > 0">
          <thead>
            <tr>
              <th style="width:50%">Product</th>
              <th style="width:5%">Size</th>
              <th style="width:10%">Price</th>
              <th style="width:8%">Quantity</th>
              <th style="width:18%" class="text-center">Subtotal</th>
              <th style="width:9%"></th>
            </tr>
          </thead>
          <tbody>
            <CartItem v-for="(product, index) in CartItems" :key="index" :product-index="index" />
          </tbody>
          <tfoot>
            <tr class="d-block d-sm-none">
              <td class="text-center">
                <strong>Total ${{CartTotal | numberFormat}}</strong>
              </td>
            </tr>
            <tr>
              <td></td>
              <td colspan="3" class></td>
              <td class="d-none d-sm-block text-center" colspan="2">
                <strong>Total ${{CartTotal | numberFormat}}</strong>
              </td>
              <td></td>
            </tr>
          </tfoot>
        </table>
      </div>
    </div>

    <div class="row mt-5"  v-if="CartTotal > 0">
      <div class="col-xs-12 col-md-12">
        <div class="card">
          <h5 class="card-header">Payment Details</h5>
          <div class="card-body">
            <form role="form">
              <!--<div class="d-block">
                <div class="custom-control custom-radio">
                  <input
                    id="credit"
                    name="paymentMethod"
                    type="radio"
                    class="custom-control-input"
                    checked
                    required
                  />
                  <label class="custom-control-label" for="credit">Credit card</label>
                </div>
                <div class="custom-control custom-radio">
                  <input
                    id="debit"
                    name="paymentMethod"
                    type="radio"
                    class="custom-control-input"
                    required
                  />
                  <label class="custom-control-label" for="debit">Debit card</label>
                </div>
                <div class="custom-control custom-radio">
                  <input
                    id="paypal"
                    name="paymentMethod"
                    type="radio"
                    class="custom-control-input"
                    required
                  />
                  <label class="custom-control-label" for="paypal">Paypal</label>
                </div>
              </div>-->
              <div class="row">
                <div class="col-md-6 mb-3">
                  <label for="cc-name">Name on card</label>
                  <input type="text" class="form-control" id="cc-name" placeholder required :class="{'is-invalid' : $v.cardInfos.cardName.$error}" @blur="$v.cardInfos.cardName.$touch()" v-model="cardInfos.cardName"/>
                  <small class="text-muted">Full name as displayed on card</small>
                  <div class="feedback" style="color:red;" v-if="$v.cardInfos.cardName.$error">Name on card is required</div>
                </div>
                <div class="col-md-6 mb-3">
                  <label for="cc-number">Credit card number</label>
                  <input type="text" class="form-control" id="cc-number" placeholder required :class="{'is-invalid' : $v.cardInfos.cardNumber.$error}" @blur="$v.cardInfos.cardNumber.$touch()" v-model="cardInfos.cardNumber" />
                  <div class="feedback" style="color:red;" v-if="$v.cardInfos.cardNumber.$error">Credit card number is required</div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-3 mb-3">
                  <label for="cc-expiration">Expiration</label>
                  <input type="text" class="form-control" id="cc-expiration" placeholder required :class="{'is-invalid' : $v.cardInfos.cardExpiration.$error}" @blur="$v.cardInfos.cardExpiration.$touch()" v-model="cardInfos.cardExpiration" />
                  <div class="feedback" style="color:red;" v-if="$v.cardInfos.cardExpiration.$error">Expiration date required</div>
                </div>
                <div class="col-md-3 mb-3">
                  <label for="cc-expiration">CVV</label>
                  <input type="text" class="form-control" id="cc-cvv" placeholder required :class="{'is-invalid' : $v.cardInfos.cardCvv.$error}" @blur="$v.cardInfos.cardCvv.$touch()" v-model="cardInfos.cardCvv"  />
                  <div class="feedback" style="color:red;" v-if="$v.cardInfos.cardCvv.$error">Security code required</div>
                </div>
              </div>
            </form>
          </div>
        </div>

        <div class="row mt-5">
          <div class="col-xs-12 col-md-3">
            <nuxt-link to="/" tag="a" class="btn btn-warning">
              <i class="fa fa-angle-left"></i> Continue Shopping
            </nuxt-link>
          </div>
          <div class="col-md-6 d-none d-sm-block"></div>
          <div class="col-xs-12 col-md-3 text-right">
            <button href="javascript:void(0);" class="btn btn-secondary" @click="giveOrder" :disabled="$v.$invalid">
              Give Order
              <i class="fa fa-angle-right"></i>
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import CartItem from "@/components/CartItem";
import {required, minLength, maxLength, numeric} from 'vuelidate/lib/validators'

export default {
  components: {
    CartItem
  },
  data(){
    return {
      cartTotal : 123,
      cardInfos:{
        cardName:"Serdar Arslanağız",
        cardNumber:"1234123412341234",
        cardExpiration:"01/25",
        cardCvv:"123",
        cardType:""
      }
    }
  },
  validations:{
    cardInfos:{
      cardName:{
        required
      },
      cardNumber : {
        required,
        numeric,
        minLength:minLength(16),
        maxLength:maxLength(16),
      },
      cardExpiration : {
        required
      },
      cardCvv : {
        required,
        numeric,
        minLength:minLength(3),
        maxLength:maxLength(3),
      }

    }
  },
  methods:{
    giveOrder(){
      if(this.cartTotal > 0)
      this.$store.dispatch("giveOrder",this.cardInfos);
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
  }
};
</script>

<style scoped>
.cart-title {
  display: inline;
  font-weight: bold;
}

.checkbox.pull-right {
  margin: 0;
}

.pl-ziro {
  padding-left: 0px;
}
</style>
