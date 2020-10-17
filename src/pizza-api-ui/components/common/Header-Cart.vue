<template>
  <tr>
    <th scope="row" style="width: 50px;" class="align-middle">
      <img :src="'http://api.pizzapp.test/uploads/products/'+CartItem.image" width="48" height="48" class="border rounded" />
    </th>
    <td class="align-middle" style="width: 200px;">{{CartItem.quantity}}x {{CartItem.name}}</td>
    <td class="align-middle" style="width: 30px;">{{CartItem.size_name}}</td>
    <td class="align-middle text-right" style="width: 100px;">${{CartItem.price * CartItem.quantity | numberFormat}}</td>
    <td class="align-middle actions" data-th>
      <a href="javascript:void(0)" class="btn btn-danger btn-sm" @click="removeFromCart()">
        <i class="fa fa-trash-o"></i>
      </a>
    </td>
  </tr>
</template>

<script>
export default {
  props :{
    productIndex : {
      required : true
    }
  },
  methods:{
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
}
</script>

<style>

</style>
