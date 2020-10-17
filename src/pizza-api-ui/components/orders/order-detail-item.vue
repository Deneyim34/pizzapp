<template>
  <tr>
    <td class="center">1</td>
    <td class="left strong"><img :src="'http://api.pizzapp.test/uploads/products/'+orderDatas.image" width="64" height="64" ></td>
    <td class="left strong">{{ orderDatas.name }}</td>
    <td class="left">{{ orderDatas.description }}</td>

    <td class="right">${{ orderDatas.price | numberFormat }}</td>
    <td class="center">{{ orderDatas.quantity }}</td>
    <td class="right">${{ orderDatas.total_price | numberFormat }}</td>
  </tr>
</template>

<script>
export default {
  props:{
    orderDatas : {
      required : true,
      type : Object
    }
  }
};
</script>

<style>
</style>
