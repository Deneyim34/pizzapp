<template>
  <tr v-if="$store.getters.getCart[productIndex] != null">
    <td data-th="Product">
      <div class="row">
        <div class="col-sm-3 d-none d-sm-block">
          <img :src="'http://api.pizzapp.test/uploads/products/'+CartItem.image" alt="..." width="64" height="64" class="img-responsive" />
        </div>
        <div class="col-sm-9">
          <h4 class="nomargin">{{CartItem.name}}</h4>
          <p>
            {{CartItem.description}}
          </p>
        </div>
      </div>
    </td>

    <td data-th="Size">{{CartItem.size_name}}</td>
    <td data-th="Price">${{CartItem.price}}</td>
    <td data-th="Quantity">
      <input type="number" class="form-control text-center" v-model="$store.getters.getCart[productIndex].quantity" @change="changeQuantity"/>
    </td>
    <td data-th="Subtotal" class="text-center">${{CartItem.price * CartItem.quantity | numberFormat}}</td>
    <td class="actions" data-th>
      <a href="javascript:void(0)" class="btn btn-danger btn-sm" @click="removeFromCart()">
        <i class="fa fa-trash-o"></i>
      </a>
    </td>
  </tr>
</template>

<script>
export default {
  data(){
    return{
      product : {...this.CartItem}
    }
  },
  props :{
    productIndex : {
      required : true
    }
  },
  methods:{
    changeQuantity(e)
    {
      if(this.$store.getters.getCart[this.productIndex].quantity <= 0)
      {
        this.$store.dispatch("removeFromCart",this.productIndex);
      }
      else{
        this.$store.dispatch("changeQuantity",this.productIndex);
      }
    },
    removeFromCart(){
      this.$store.dispatch("removeFromCart",this.productIndex);
    }
  },
  computed : {
    CartItem()
    {
      return this.$store.getters.getCart[this.productIndex];
    }
  }
};
</script>

<style>
</style>
