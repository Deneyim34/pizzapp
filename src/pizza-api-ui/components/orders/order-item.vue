<template>
  <div class="float-left p-2 m-1 border">
        {{orderDatas.quantity}}x
        <img :src="'http://api.pizzapp.test/uploads/products/'+orderDatas.image" alt width="48" height="48" class="border rounded" /> {{orderDatas.name}}
        <span class="badge badge-secondary">{{orderDatas.size}}</span>
    </div>
</template>

<script>
export default {
props:{
  orderDatas : {
    required : true,
    type:Object
  }
}
}
</script>

<style>

</style>
